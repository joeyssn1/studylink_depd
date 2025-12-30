{{-- views/history.blade.php --}}
<x-layout title="Study History">
    <div class="max-w-5xl mx-auto mt-10 px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Riwayat Belajar</h1>

        <div class="bg-white shadow-sm border rounded-xl overflow-hidden my-10">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#c9a348] border-b">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold text-white uppercase">Teknik</th>
                        <th class="px-6 py-4 text-sm font-semibold text-white uppercase">Subjek / Topik</th>
                        <th class="px-6 py-4 text-sm font-semibold text-white uppercase text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($histories as $history)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    {{ $history->study_type === 'Pomodoro' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                    {{ $history->study_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $history->subject_name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 text-sm text-right">
                                {{-- Menggunakan Carbon untuk format Indonesia --}}
                                {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('l, d F Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada riwayat belajar. Semangat belajarnya!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>