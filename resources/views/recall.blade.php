{{-- views/recall.blade.php --}}
<x-layout title="Active Recall">

    <div class="max-w-7xl mx-auto mt-10 px-4 grid grid-cols-1 md:grid-cols-4 gap-8">

        <aside class="md:col-span-1 space-y-6">

            <div class="bg-white border rounded-2xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">
                    📚 Study Materials
                </h3>

                <form
                    action="{{ route('material.upload') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mb-6"
                >
                    @csrf
                    <input
                        type="file"
                        name="file"
                        accept="application/pdf"
                        required
                        class="block w-full text-xs mb-2"
                    >

                    <button
                        type="submit"
                        class="w-full py-2 bg-[#c9a348] hover:bg-[#b89237] text-white rounded-lg text-sm font-bold transition"
                    >
                        Upload PDF
                    </button>
                </form>

                <div class="space-y-2 max-h-80 overflow-y-auto pr-1 custom-scrollbar">
                    @foreach ($materials as $material)
                        <div
                            onclick="selectMaterial({{ $material->material_id }}, '{{ $material->file_name }}')"
                            class="p-3 border rounded-xl cursor-pointer hover:border-[#c9a348] hover:bg-orange-50 transition flex justify-between items-center"
                        >
                            <span class="text-sm truncate w-40">
                                {{ $material->file_name }}
                            </span>
                            <span class="text-xs text-gray-400">
                                Select
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

        </aside>

        <!-- CHAT AREA -->
        <section class="md:col-span-3">

            <div class="flex flex-col bg-white border rounded-2xl shadow-sm h-[650px]">

                <!-- CHAT HEADER -->
                <div class="p-5 border-b bg-gray-50 rounded-t-2xl flex justify-between items-center">
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg">
                            AI Study Partner
                        </h2>
                        <p
                            id="selected-material-name"
                            class="text-xs text-gray-500 italic"
                        >
                            Select a material to begin
                        </p>
                    </div>

                    <span
                        class="px-3 py-1 bg-green-100 text-green-600 text-xs font-bold rounded-full"
                    >
                        Online
                    </span>
                </div>

                <!-- CHAT MESSAGES -->
                <div
                    id="chat-box"
                    class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 custom-scrollbar"
                >
                    <div class="flex justify-start animate-fade-in">
                        <div class="bg-white border text-gray-800 p-4 rounded-2xl max-w-[80%] shadow-sm">
                            Hi <b>{{ auth()->user()->fullname }}</b> 👋 <br>
                            Choose a PDF material, then type:<br>
                            <b>generate 5 questions</b>
                        </div>
                    </div>
                </div>

                <!-- CHAT INPUT -->
                <div
                    id="chat-input-area"
                    class="p-4 border-t bg-white rounded-b-2xl flex gap-2"
                >
                    <input
                        id="chat-input"
                        type="text"
                        placeholder="Type: generate 5 questions"
                        class="flex-1 border rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#c9a348]"
                        disabled
                    >

                    <button
                        id="send-btn"
                        class="px-5 py-2 bg-[#c9a348] hover:bg-[#b89237] text-white rounded-xl text-sm font-bold disabled:opacity-50 transition"
                        disabled
                    >
                        Send
                    </button>
                </div>

            </div>

        </section>
    </div>

</x-layout>

{{-- ================= SCRIPT ================= --}}
<script>
    let currentMaterialId = null;
    let currentStudyId = {{ $study->study_id }};
    let questions = [];
    let currentQuestionIndex = 0;
    let score = 0;

    const chatBox = document.getElementById('chat-box');
    const inputArea = document.getElementById('chat-input-area');

    document.getElementById('chat-input').addEventListener('keydown', e => {
        if (e.key === 'Enter') processChatInput();
    });
    document.getElementById('send-btn').addEventListener('click', processChatInput);

    function appendMessage(sender, text, isBot = true) {
        const div = document.createElement('div');
        div.className = `flex ${isBot ? 'justify-start' : 'justify-end'} animate-fade-in`;
        div.innerHTML = `
            <div class="${isBot ? 'bg-white border text-gray-800' : 'bg-[#c9a348] text-white'} 
                        p-4 rounded-2xl max-w-[80%] shadow-sm">
                ${text}
            </div>
        `;
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function selectMaterial(id, name) {
        currentMaterialId = id;
        questions = [];
        currentQuestionIndex = 0;
        score = 0;

        document.getElementById('selected-material-name').innerText =
            "Material: " + name;

        appendMessage('AI',
            `📘 Material <b>${name}</b> selected.<br>
            Type <b>generate X questions</b> to begin.`
        );

        document.getElementById('chat-input').disabled = false;
        document.getElementById('send-btn').disabled = false;
        document.getElementById('chat-input').focus();
    }

    function processChatInput() {
        const input = document.getElementById('chat-input');
        const text = input.value.trim();
        if (!text) return;

        appendMessage('User', text, false);
        input.value = '';

        const match = text.match(/generate\s+(\d+)\s+questions/i);
        if (!match) {
            appendMessage('AI', 'Use format: <b>generate 5 questions</b>');
            return;
        }

        const num = parseInt(match[1]);
        if (num < 1 || num > 10) {
            appendMessage('AI', 'Number of questions must be 1–10.');
            return;
        }

        generateAI(num);
    }

    async function generateAI(num) {
        appendMessage('AI', '📖 Preparing questions...');

        const response = await fetch("{{ route('recall.generate') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                study_id: currentStudyId,
                material_id: currentMaterialId,
                num_questions: num
            })
        });

        const data = await response.json();

        if (data.status !== 'success') {
            appendMessage('AI', '❌ Failed to generate questions.');
            return;
        }

        questions = data.questions;
        appendMessage('AI', `✅ ${questions.length} questions ready.`);
        setTimeout(showQuestion, 800);
    }

    function showQuestion() {
        if (currentQuestionIndex >= questions.length) {
            finishSesi();
            return;
        }

        const q = questions[currentQuestionIndex];
        appendMessage('AI',
            `<b>Question ${currentQuestionIndex + 1}:</b><br>${q.question_detail}`
        );

        inputArea.innerHTML = `
            <div class="grid grid-cols-2 gap-3">
                ${['a','b','c','d'].map(opt => `
                    <button
                        onclick="submitJawaban('${opt.toUpperCase()}', ${q.question_id})"
                        class="p-4 border-2 rounded-xl hover:border-[#c9a348] hover:bg-orange-50 text-left text-sm transition"
                    >
                        <b class="text-[#c9a348]">${opt.toUpperCase()}.</b>
                        ${q['option_' + opt]}
                    </button>
                `).join('')}
            </div>
        `;
    }

    async function submitJawaban(answer, qId) {
        inputArea.innerHTML = `<p class="text-center text-gray-400 italic">Checking answer...</p>`;

        const response = await fetch("{{ route('recall.submit') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ question_id: qId, answer })
        });

        const data = await response.json();

        if (data.is_correct) {
            appendMessage('AI', '✅ Correct!');
            score++;
        } else {
            appendMessage(
                'AI',
                `❌ Wrong. Correct answer: <b>${data.correct_answer}</b>`
            );
        }

        currentQuestionIndex++;
        setTimeout(showQuestion, 2000);
    }

    function finishSesi() {
        appendMessage(
            'AI',
            `🏁 Session finished!<br>
             Score: <b>${score}</b> / <b>${questions.length}</b>`
        );

        inputArea.innerHTML = `
            <a
                href="{{ route('home') }}"
                class="block text-center w-full py-3 bg-[#c9a348] text-white rounded-xl font-bold shadow-md"
            >
                Finish & Back to Home
            </a>
        `;
    }
</script>

<style>
.animate-fade-in {
    animation: fadeIn .2s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #d1b35c;
    border-radius: 999px;
}
</style>
