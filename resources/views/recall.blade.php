{{-- <x-layout title="Active Recall Session">
    <div class="max-w-5xl mx-auto mt-10 px-4">
        <h1 class="text-2xl font-bold mb-2">Active Recall: {{ $study->subject_name }}</h1>
<hr class="mb-8">

<div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    <div class="bg-white p-6 border rounded-xl shadow-sm">
        <h3 class="font-bold mb-4 text-gray-700">Upload Materi PDF Baru</h3>
        <form action="{{ route('material.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept="application/pdf" required
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#c9a348] file:text-white hover:file:bg-[#b89237] mb-4">
            <button type="submit" class="w-full py-2  bg-[#c9a348] text-white rounded-lg hover:bg-[#b89237] font-bold">
                Upload File
            </button>
        </form>
    </div>

    <div class="bg-white p-6 border rounded-xl shadow-sm">
        <h3 class="font-bold mb-4 text-gray-700">Pilih Materi Sebelumnya</h3>
        <div class="space-y-3 max-h-60 overflow-y-auto pr-2">
            @forelse ($materials as $material)
            <div class="flex items-center justify-between p-3 border rounded-lg hover:border-[#c9a348] transition cursor-pointer group">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📄</span>
                    <p class="text-sm font-medium text-gray-700 truncate w-40">{{ $material->file_name }}</p>
                </div>
                <button class="text-xs bg-gray-100 px-3 py-1 rounded-full group-hover:bg-[#c9a348] group-hover:text-white transition">
                    Pilih
                </button>
            </div>
            @empty
            <p class="text-sm text-gray-400 italic text-center py-4">Belum ada materi yang diupload.</p>
            @endforelse
        </div>
    </div>
</div>
</div>
</x-layout> --}}

{{-- views/recall.blade.php --}}
<x-layout title="Active Recall Chat">
    <div class="max-w-6xl mx-auto mt-10 px-4 flex flex-col md:flex-row gap-6">

        <div id="material-section" class="w-full md:w-1/3 space-y-6">
            <div class="bg-white p-6 border rounded-xl shadow-sm">
                <h3 class="font-bold mb-4 text-gray-700">Materi PDF</h3>
                <form action="{{ route('material.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                    @csrf
                    <input type="file" name="file" accept="application/pdf" required
                        class="block w-full text-xs mb-2">
                    <button type="submit"
                        class="w-full py-2 bg-[#c9a348] hover:bg-[#b89237] text-white rounded-lg text-sm font-bold">Upload
                        Baru</button>
                </form>

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @foreach ($materials as $material)
                    <div onclick="selectMaterial({{ $material->material_id }}, '{{ $material->file_name }}')"
                        class="p-3 border rounded-lg hover:border-[#c9a348] cursor-pointer transition flex justify-between items-center group">
                        <span class="text-sm truncate w-40">{{ $material->file_name }}</span>
                        <span class="text-xs text-gray-400 group-hover:text-[#c9a348]">Pilih</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto mt-10 px-4 flex flex-col md:flex-row gap-6">
        <div class="mb-10 flex-1 flex flex-col bg-white border rounded-xl shadow-sm h-[600px]">
            <div class="p-4 border-b bg-gray-50 rounded-t-xl flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-gray-800">AI Study Partner</h2>
                    <p id="selected-material-name" class="text-xs text-gray-500 italic">Pilih materi untuk memulai</p>
                </div>
                <span class="px-3 py-1 bg-green-100 text-green-600 text-xs font-bold rounded-full">Online</span>
            </div>

            <div id="chat-box" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                <div class="flex justify-start">
                    <div class="bg-white border text-gray-800 p-3 rounded-lg max-w-[80%] shadow-sm">
                        Halo {{ auth()->user()->fullname }}! 👋 Silakan pilih materi di atas, lalu beri tahu
                        aku berapa soal yang ingin kamu kerjakan hari ini.
                    </div>
                </div>
            </div>

            <div id="chat-input-area" class="p-4 border-t bg-white rounded-b-xl flex gap-2">
                <input
                    id="chat-input"
                    type="text"
                    placeholder="Type: generate 5 questions"
                    class="flex-1 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a348]"
                    disabled />
                <button
                    id="send-btn"
                    class="px-4 py-2 bg-[#c9a348] hover:bg-[#b89237] text-white rounded-lg text-sm font-bold disabled:opacity-50"
                    disabled>
                    Send
                </button>
            </div>

        </div>
    </div>
</x-layout>

<script>
    let currentMaterialId = null;
    let currentStudyId = {{$study -> study_id}};
    let questions = [];
    let currentQuestionIndex = 0;
    let score = 0;

    const chatBox = document.getElementById('chat-box');
    const inputArea = document.getElementById('chat-input-area');

    document.getElementById('chat-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') processChatInput();
    });

    document.getElementById('send-btn').addEventListener('click', processChatInput);




    function appendMessage(sender, text, isBot = true) {
        const div = document.createElement('div');
        div.className = `flex ${isBot ? 'justify-start' : 'justify-end'} animate-fade-in`;
        div.innerHTML = `
            <div class="${isBot ? 'bg-white border text-gray-800' : 'bg-[#c9a348] text-white'} p-3 rounded-lg max-w-[80%] shadow-sm mb-2">
                ${text}
            </div>
        `;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // 1. Dipicu otomatis saat klik materi di sidebar
    function selectMaterial(id, name) {
        currentMaterialId = id;
        questions = [];
        currentQuestionIndex = 0;
        score = 0;

        document.getElementById('selected-material-name').innerText = "Materi: " + name;

        appendMessage('AI',
            `Materi **${name}** dipilih.<br>Ketik <b>generate X questions</b> lalu tekan Send.`);

        const input = document.getElementById('chat-input');
        const sendBtn = document.getElementById('send-btn');

        input.disabled = false;
        sendBtn.disabled = false;
        input.focus();
    }


    // 2. Konfirmasi jumlah soal
    function confirmGenerate(num) {
        appendMessage('User', `Buatkan aku ${num} soal.`, false);
        generateAI(num);
    }

    // 3. Proses Generate AI
    function processChatInput() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (!text) return;

        appendMessage('User', text, false);
        input.value = '';

        const match = text.match(/generate\s+(\d+)\s+questions/i);
        if (!match) {
            appendMessage('AI', 'Gunakan format: <b>generate 5 questions</b>');
            return;
        }

        const num = parseInt(match[1]);
        if (num < 1 || num > 10) {
            appendMessage('AI', 'Jumlah soal harus antara 1–10.');
            return;
        }

        generateAI(num);
    }

    async function generateAI(num) {
        appendMessage('AI', '📖 Membaca materi & menyiapkan soal...');

        const url = "{{ route('recall.generate') }}";
        const payload = {
            study_id: currentStudyId,
            material_id: currentMaterialId,
            num_questions: num
        };

        console.log('[generateAI] url:', url);
        console.log('[generateAI] payload:', payload);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            console.log('[generateAI] status:', response.status, response.statusText);
            console.log('[generateAI] ok:', response.ok);
            console.log('[generateAI] content-type:', response.headers.get('content-type'));

            // Read raw text first (so you can see HTML error pages too)
            const raw = await response.text();
            console.log('[generateAI] raw response:', raw);

            // Try parse JSON
            let data = null;
            try {
                data = JSON.parse(raw);
            } catch (e) {
                console.error('[generateAI] JSON parse failed:', e);
                appendMessage('AI', 'Server tidak mengembalikan JSON (cek Console raw response).');
                return;
            }

            console.log('[generateAI] parsed json:', data);

            if (!response.ok) {
                appendMessage('AI', `HTTP ${response.status}<br><pre>${escapeHtml(raw).slice(0, 1500)}</pre>`);
                return;
            }

            if (data.status !== 'success') {
                appendMessage('AI', `❌ ${data.error || 'Gagal membuat soal.'}<br><pre>${escapeHtml(JSON.stringify(data, null, 2)).slice(0, 1500)}</pre>`);
                return;
            }

            questions = data.questions || [];
            appendMessage('AI', `Siap! ${questions.length} soal dibuat.`);
            setTimeout(showQuestion, 800);

        } catch (err) {
            console.error('[generateAI] fetch threw:', err);
            appendMessage('AI', `Koneksi bermasalah: ${err?.message || err}`);
        }
    }

    // Tiny helper for safe <pre> rendering (avoid breaking your HTML)
    function escapeHtml(s) {
        return String(s)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }



    // 4. Menampilkan Soal satu per satu
    function showQuestion() {
        if (currentQuestionIndex >= questions.length) {
            finishSesi();
            return;
        }

        const q = questions[currentQuestionIndex];
        appendMessage('AI', `**Pertanyaan ${currentQuestionIndex + 1}:** <br> ${q.question_detail}`);

        inputArea.innerHTML = `
            <div class="grid grid-cols-2 gap-2">
                ${['a', 'b', 'c', 'd'].map(opt => `
                    <button onclick="submitJawaban('${opt.toUpperCase()}', ${q.question_id})" 
                        class="p-3 border-2 rounded-xl hover:border-[#c9a348] hover:bg-orange-50 text-left text-sm transition-all duration-200">
                        <span class="font-bold text-[#c9a348]">${opt.toUpperCase()}.</span> ${q['option_' + opt]}
                    </button>
                `).join('')}
            </div>
        `;
    }

    // 5. Submit Jawaban & Feedback
    async function submitJawaban(pilihan, qId) {
        inputArea.innerHTML = `<p class="text-center italic text-gray-400">Mengecek jawaban...</p>`;

        try {
            const response = await fetch("{{ route('recall.submit') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    question_id: qId,
                    answer: pilihan
                })
            });

            const data = await response.json();

            if (data.is_correct) {
                appendMessage('AI', `✅ **Benar!** Kamu hebat.`);
                score++;
            } else {
                // Menampilkan jawaban benar dan penjelasan (jika ada)
                let feedback = `❌ **Salah.** Jawaban yang benar adalah **${data.correct_answer}**.`;
                if (data.explanation) {
                    feedback +=
                        `<br><small class="text-gray-500 mt-1 italic">Penjelasan: ${data.explanation}</small>`;
                }
                appendMessage('AI', feedback);
            }

            currentQuestionIndex++;
            setTimeout(showQuestion, 2000); // Jeda 2 detik agar user sempat baca feedback
        } catch (error) {
            alert('Gagal mengirim jawaban.');
        }
    }

    // 6. Akhir Sesi
    function finishSesi() {
        appendMessage('AI',
            `🏁 **Sesi Selesai!** <br> Kamu berhasil menjawab **${score}** dari **${questions.length}** soal dengan benar.`
        );

        inputArea.innerHTML = `
            <div class="flex flex-col gap-2 w-full">
                <p class="text-center text-sm text-gray-500 mb-2">Mau belajar materi lain?</p>
                <a href="{{ route('home') }}" class="block text-center w-full py-3 bg-[#c9a348] text-white rounded-lg font-bold shadow-md">
                    Selesai & Kembali ke Home
                </a>
            </div>
        `;
    }
</script>