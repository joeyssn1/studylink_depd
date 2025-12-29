<?php

namespace App\Http\Controllers;

use App\Models\StudyTechnique;
use App\Models\Pomodoro;
use App\Models\StudyCounting;
use App\Models\Material;
use App\Models\Question;
use App\Models\UserAnswer;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Smalot\PdfParser\Parser; // Untuk membaca PDF
use Illuminate\Support\Facades\Http;

class StudyController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'study_type' => 'required|in:ActiveRecall,Pomodoro',
        ]);

        $study = StudyTechnique::create([
            'user_id'      => Auth::id(),
            'study_type'   => $request->study_type,
            'subject_name' => $request->subject_name,
        ]);
        // 2. Update atau Buat data di StudyCounting
        // updateOrCreate akan mencari data berdasarkan user_id, jika tidak ada maka dibuat baru
        $counting = StudyCounting::firstOrCreate(
            ['user_id' => Auth::id()],
            ['pomodoro_count' => 0, 'active_count' => 0]
        );

        // 3. Tambahkan count berdasarkan tipenya
        if ($request->study_type === 'Pomodoro') {
            $counting->increment('pomodoro_count');
            return redirect()->route('pomodoro.show', $study->study_id);
        } else {
            $counting->increment('active_count');
            return redirect()->route('active-recall.show', $study->study_id);
        }

        // Redirect berdasarkan tipe studi
        if ($request->study_type === 'ActiveRecall') {
            return redirect()->route('active-recall.show', $study->study_id);
        }

        return redirect()->route('pomodoro.show', $study->study_id);
    }

    public function pomodoroPage($id)
    {
        // Ambil data study untuk memastikan datanya ada
        $study = StudyTechnique::findOrFail($id);

        return view('pomodoro', compact('study'));
    }

    public function storePomodoro(Request $request)
    {
        $request->validate([
            'study_id' => 'required|exists:studytechnique,study_id',
            'focus_time' => 'required|integer|min:1|max:60',
            'rest_time' => 'required|integer|min:1|max:60',
        ]);

        // Proses simpan
        Pomodoro::create([
            'study_id' => $request->study_id,
            'focus_time' => $request->focus_time,
            'rest_time' => $request->rest_time,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function history()
    {
        // Mengambil semua riwayat belajar user yang sedang login
        $histories = StudyTechnique::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('history', compact('histories'));
    }

    public function activeRecallPage($id)
    {
        $study = StudyTechnique::findOrFail($id);

        // Ambil semua materi PDF yang pernah diupload user ini
        $materials = Material::where('user_id', Auth::id())->latest()->get();

        return view('recall', compact('study', 'materials'));
    }

    public function uploadMaterial(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:10240', // Maksimal 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('materials', 'public'); // Simpan di storage/app/public/materials

            Material::create([
                'user_id' => Auth::id(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => 'pdf',
            ]);

            return back()->with('success', 'Materi berhasil diunggah!');
        }
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'study_id' => 'required|exists:studytechnique,study_id',
            'material_id' => 'required|exists:material,material_id',
            'num_questions' => 'required|integer|min:1|max:10',
        ]);

        try {
            $material = Material::findOrFail($request->material_id);
            $filePath = storage_path('app/public/' . $material->file_path);

            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();

            $cleanText = preg_replace('/\s+/', ' ', $text);
            $context = substr($cleanText, 0, 4000);

            // Prompt diperingkas tanpa field explanation
            $prompt = "Buatkan {$request->num_questions} soal pilihan ganda dari materi ini. 
                   Berikan output HANYA JSON array dengan format:
                   [{\"question\":\"\",\"a\":\"\",\"b\":\"\",\"c\":\"\",\"d\":\"\",\"correct_answer\":\"A/B/C/D\"}]
                   
                   Materi: " . $context;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.api_key'),
                'Content-Type' => 'application/json',
                'HTTP-Referer' => 'http://localhost:9090',
                'X-Title' => 'StudyLink',
            ])->timeout(120) // <--- Tambahkan ini untuk menunggu hingga 2 menit
                ->connectTimeout(60) // Waktu tunggu maksimal saat menyambungkan ke server
                ->withoutVerifying()
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'qwen/qwen-2.5-vl-7b-instruct:free',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.5,
                ]);

            if ($response->failed()) {
                return response()->json(['error' => 'AI sibuk', 'details' => $response->body()], 500);
            }

            $content = $response->json()['choices'][0]['message']['content'];
            $cleanJson = preg_replace('/```json|```/', '', $content);
            $questionsData = json_decode(trim($cleanJson), true);

            foreach ($questionsData as $data) {
                Question::create([
                    'study_id' => $request->study_id,
                    'question_detail' => $data['question'],
                    'option_a' => $data['a'],
                    'option_b' => $data['b'],
                    'option_c' => $data['c'],
                    'option_d' => $data['d'],
                    'correct_answer' => $data['correct_answer'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'questions' => Question::where('study_id', $request->study_id)->latest()->take($request->num_questions)->get()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal proses PDF', 'message' => $e->getMessage()], 500);
        }
    }


    public function submitAnswer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,question_id',
            'answer' => 'required|in:A,B,C,D',
        ]);

        $question = Question::findOrFail($request->question_id);
        $isCorrect = ($request->answer === $question->correct_answer);

        $userAnswer = UserAnswer::create([
            'user_id' => Auth::id(),
            'question_id' => $request->question_id,
            'user_answers' => $request->answer,
            'is_correct' => $isCorrect,
        ]);

        return response()->json([
            'status' => 'success',
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
        ]);
    }
}
