<?php

namespace App\Http\Controllers;

use App\Models\StudyTechnique;
use App\Models\Pomodoro;
use App\Models\StudyCounting;
use App\Models\Material;
use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\MaterialSummary;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $study = StudyTechnique::with('pomodoro')
            ->where('study_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pomodoro', compact('study'));
    }

    public function storePomodoro(Request $request)
    {
        $request->validate([
            'study_id'   => 'required|exists:studytechnique,study_id',
            'focus_time' => 'required|integer|min:1|max:60',
            'rest_time'  => 'required|integer|min:1|max:60',
            'cycles'     => 'required|integer|min:1|max:10',
        ]);

        // Proses simpan
        Pomodoro::updateOrCreate(
            ['study_id' => $request->study_id],
            [
                'focus_time'        => $request->focus_time,
                'rest_time'         => $request->rest_time,
                'total_cycles'      => $request->cycles,
                'current_cycle'     => 1,
                'is_focus'          => true,
                'remaining_seconds' => $request->focus_time * 60,
                'status'            => 'in_progress',
                'started_at'        => now(),
            ]
        );

        return response()->json(['status' => 'success']);
    }

    public function savePomodoroState(Request $request)
    {
        $request->validate([
            'study_id'          => 'required|exists:studytechnique,study_id',
            'remaining_seconds' => 'required|integer|min:0',
            'current_cycle'     => 'required|integer|min:1',
            'is_focus'          => 'required|boolean',
            'focus_time'        => 'required|integer|min:1',
            'rest_time'         => 'required|integer|min:1',
            'total_cycles'      => 'required|integer|min:1',
        ]);

        $pomodoro = Pomodoro::where('study_id', $request->study_id)->first();

        if (!$pomodoro || $pomodoro->status === 'completed') {
            return response()->json(['status' => 'ignored']);
        }

        $pomodoro->update([
            'focus_time'        => $request->focus_time,
            'rest_time'         => $request->rest_time,
            'total_cycles'      => $request->total_cycles,
            'current_cycle'     => $request->current_cycle,
            'remaining_seconds' => $request->remaining_seconds,
            'is_focus'          => $request->is_focus,
            'status'            => 'paused',
        ]);

        return response()->json(['status' => 'saved']);
    }

    public function completePomodoro($id)
    {
        $pomodoro = Pomodoro::where('study_id', $id)->firstOrFail();

        $pomodoro->update([
            'status' => 'completed',
            'completed_at' => now(),
            'remaining_seconds' => 0,
        ]);

        StudyCounting::where('user_id', auth()->id())
            ->increment('pomodoro_count');

        return redirect()->route('study.history');
    }

    public function history()
    {
        $histories = StudyTechnique::with('pomodoro')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('history', compact('histories'));
    }

    public function destroy($id)
    {
        $study = StudyTechnique::where('study_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $study->delete();

        return redirect()->route('study.history')
            ->with('success', 'Study session deleted.');
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
        // Basic request logging (keep this while debugging)
        Log::info('generateQuestions INPUT', $request->all());

        $request->validate([
            'study_id'       => 'required|integer|exists:studytechnique,study_id',
            'material_id'    => 'required|integer|exists:material,material_id',
            'num_questions'  => 'required|integer|min:1|max:10',
        ]);

        $response = null; // IMPORTANT: prevent "Undefined variable $response"

        try {
            // 1) Enforce ownership
            $study = StudyTechnique::where('study_id', $request->study_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $material = Material::where('material_id', $request->material_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // 2) Ensure file exists
            $filePath = storage_path('app/public/' . $material->file_path);
            Log::info('generateQuestions FILE', ['filePath' => $filePath, 'exists' => file_exists($filePath)]);

            if (!file_exists($filePath)) {
                return response()->json([
                    'error' => 'File PDF tidak ditemukan di storage',
                    'path'  => $material->file_path,
                ], 404);
            }

            // 3) Ensure summary exists (do NOT hard-fail on rate limit; fallback to raw text)
            $material->load('summary');

            $summaryText = null;

            if ($material->summary && is_string($material->summary->summary_text) && trim($material->summary->summary_text) !== '') {
                $summaryText = trim($material->summary->summary_text);
                Log::info('generateQuestions SUMMARY', ['source' => 'cached', 'len' => strlen($summaryText)]);
            } else {
                Log::info('generateQuestions SUMMARY', ['source' => 'generateSummary() attempt']);

                try {
                    $this->generateSummary($material);
                    $material->load('summary');

                    if ($material->summary && is_string($material->summary->summary_text) && trim($material->summary->summary_text) !== '') {
                        $summaryText = trim($material->summary->summary_text);
                        Log::info('generateQuestions SUMMARY', ['source' => 'generated', 'len' => strlen($summaryText)]);
                    }
                } catch (\Throwable $e) {
                    // This is where 429/502 often happens — do not stop the whole flow
                    Log::warning('generateQuestions SUMMARY FAILED - fallback', [
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            // Fallback context if summary is unavailable
            if (!$summaryText) {
                // Parse PDF text as fallback
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($filePath);
                $rawText = preg_replace('/\s+/', ' ', $pdf->getText());
                $summaryText = substr($rawText, 0, 6000);

                Log::info('generateQuestions CONTEXT', ['source' => 'pdf_fallback', 'len' => strlen($summaryText)]);
            }

            $context = $summaryText;

            // 4) Build strict prompt (JSON only)
            $num = (int) $request->num_questions;

            $prompt = <<<PROMPT
Buat {$num} soal pilihan ganda (A, B, C, D) berdasarkan materi berikut.

Keluaran WAJIB berupa JSON array saja (tanpa teks tambahan, tanpa markdown, tanpa code fence).
Format tiap item:
{
  "question": "...",
  "a": "...",
  "b": "...",
  "c": "...",
  "d": "...",
  "correct_answer": "A" | "B" | "C" | "D"
}

Materi:
{$context}
PROMPT;

            // 5) OpenRouter call (DO NOT change model)
            $apiKey = config('services.openrouter.api_key');
            if (!$apiKey) {
                return response()->json(['error' => 'OPENROUTER_API_KEY belum diset di .env'], 500);
            }

            Log::info('generateQuestions OPENROUTER', ['model' => 'google/gemini-2.0-flash-exp:free']);

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'HTTP-Referer'  => config('app.url', 'http://localhost'),
                'X-Title'       => config('app.name', 'StudyLink'),
            ])
                ->timeout(120)
                // Backoff retry helps a lot for transient 429/502 on free tier
                ->retry(4, 2000)
                ->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'google/gemini-2.0-flash-exp:free',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.4,
                ]);

            Log::info('generateQuestions OPENROUTER RESP', [
                'status' => $response->status(),
            ]);

            if ($response->failed()) {
                Log::error('generateQuestions OPENROUTER FAILED', [
                    'status' => $response->status(),
                    'body'   => $response->body(), // FULL BODY in log
                ]);

                // Return FULL, not truncated
                return response()->json([
                    'error'        => 'OpenRouter request failed',
                    'status'       => $response->status(),
                    'raw_response' => $response->body(), // FULL BODY
                ], $response->status());
            }

            $json = $response->json();

            // 6) Extract model output safely
            $content = data_get($json, 'choices.0.message.content');
            if (!is_string($content) || trim($content) === '') {
                Log::error('generateQuestions EMPTY CONTENT', ['raw' => $json]);
                return response()->json([
                    'error' => 'OpenRouter returned empty content',
                    'raw'   => $json,
                ], 502);
            }

            Log::info('generateQuestions RAW AI', ['content' => mb_substr($content, 0, 4000)]);

            // 7) Clean and parse JSON (handles accidental ```json fences)
            $clean = trim(preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($content)));

            // More robust: if model wraps JSON with extra text, try extracting the array
            $decoded = json_decode($clean, true);

            if (!is_array($decoded)) {
                if (preg_match('/\[\s*{.*}\s*\]/s', $clean, $m)) {
                    $decoded = json_decode($m[0], true);
                }
            }

            if (!is_array($decoded)) {
                Log::warning('generateQuestions BAD JSON', [
                    'cleaned' => mb_substr($clean, 0, 4000),
                ]);

                return response()->json([
                    'error'       => 'AI output bukan JSON array yang valid',
                    'raw_content' => $content,
                    'cleaned'     => $clean,
                ], 422);
            }

            $questionsData = $decoded;

            // 8) Validate each item and insert
            $created = [];
            foreach ($questionsData as $i => $data) {
                if (!is_array($data)) continue;

                $required = ['question', 'a', 'b', 'c', 'd', 'correct_answer'];
                foreach ($required as $key) {
                    if (!array_key_exists($key, $data) || trim((string) $data[$key]) === '') {
                        return response()->json([
                            'error' => "Item #{$i} tidak lengkap (missing/empty: {$key})",
                            'item'  => $data,
                        ], 422);
                    }
                }

                $ca = strtoupper(trim((string) $data['correct_answer']));
                if (!in_array($ca, ['A', 'B', 'C', 'D'], true)) {
                    return response()->json([
                        'error' => "Item #{$i} correct_answer harus A/B/C/D",
                        'item'  => $data,
                    ], 422);
                }

                $created[] = Question::create([
                    'study_id'        => $study->study_id,
                    'question_detail' => (string) $data['question'],
                    'option_a'        => (string) $data['a'],
                    'option_b'        => (string) $data['b'],
                    'option_c'        => (string) $data['c'],
                    'option_d'        => (string) $data['d'],
                    'correct_answer'  => $ca,
                ]);
            }

            return response()->json([
                'status'    => 'success',
                'count'     => count($created),
                'questions' => $created,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Study/material tidak ditemukan atau bukan milik user',
            ], 404);
        } catch (\Throwable $e) {
            Log::error('generateQuestions EXCEPTION', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // If OpenRouter was called and failed, return FULL body (not truncated)
            if ($response instanceof \Illuminate\Http\Client\Response) {
                if ($response->failed()) {
                    return response()->json([
                        'error'        => 'OpenRouter request failed',
                        'status'       => $response->status(),
                        'raw_response' => $response->body(), // FULL BODY
                    ], $response->status());
                }
            }

            // Otherwise return the real exception
            return response()->json([
                'error'   => 'Gagal generate questions',
                'message' => $e->getMessage(),
            ], 500);
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

    public function generateSummary(Material $material)
    {
        $filePath = storage_path('app/public/' . $material->file_path);

        if (!file_exists($filePath)) {
            throw new \Exception('PDF file not found');
        }

        // Parse PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = preg_replace('/\s+/', ' ', $pdf->getText());

        // Hard limit to avoid token explosion
        $context = substr($text, 0, 6000);

        $prompt = <<<PROMPT
Ringkas materi berikut menjadi ringkasan belajar yang:
- jelas
- padat
- terstruktur
- fokus pada konsep utama dan definisi penting

Materi:
{$context}
PROMPT;

        $apiKey = config('services.openrouter.api_key');
        if (!$apiKey) {
            throw new \Exception('OPENROUTER_API_KEY not configured');
        }

        // Call OpenRouter (Gemini Flash)
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'HTTP-Referer'  => config('app.url', 'http://localhost'),
            'X-Title'       => config('app.name', 'StudyLink'),
        ])
            ->timeout(120)
            ->retry(3, 800) // IMPORTANT: handles Gemini/OpenRouter flakiness
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'google/gemini-2.0-flash-exp:free',
                'messages' => [
                    [
                        'role'    => 'user',
                        'content' => $prompt, // text-only (correct for PHP)
                    ],
                ],
                'temperature' => 0.3,
            ]);

        if ($response->failed()) {
            Log::error('OPENROUTER SUMMARY FAILED', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \Exception('AI summary failed: HTTP ' . $response->status());
        }

        $json = $response->json();

        $summaryText = data_get($json, 'choices.0.message.content');

        if (!is_string($summaryText) || trim($summaryText) === '') {
            throw new \Exception('Empty summary returned from Gemini');
        }

        return MaterialSummary::updateOrCreate(
            ['material_id' => $material->material_id],
            [
                'summary_text' => trim($summaryText),
                'ai_model'     => 'google/gemini-2.0-flash-exp',
            ]
        );
    }
}
