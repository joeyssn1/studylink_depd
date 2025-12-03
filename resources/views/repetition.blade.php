<x-layout title="Spaced Repetition">
    <x-layoutstudy studyTitle="Spaced Repetition">
        <div class="flex flex-col items-center">
            <h2 class="text-xl font-bold mb-4">Your Spaced Repetition Schedule</h2>
            <table class="border border-gray-400 border-collapse mx-6 text-[16px]">
                <thead class="bg-[#c9a348] text-white">
                    <tr>
                        <th class="border border-gray-300 px-2 py-2 text-center">Subject</th>
                        <th class="border border-gray-300 px-2 py-2 text-center">Time</th>
                        <th class="border border-gray-300 px-2 py-2 text-center">Intervals</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-2 py-2 text-center">Artificial Intelligence</td>
                        <td class="border border-gray-300 px-2 py-2 text-center">Friday 14/11/2025, 16:00-20:00</td>
                        <td class="border border-gray-300 px-2 py-2 text-center">8x</td>
                    </tr>

                    <tr>
                        <td class="border border-gray-300 px-2 py-2 text-center">Artificial Intelligence</td>
                        <td class="border border-gray-300 px-2 py-2 text-center"></td>
                        <td class="border border-gray-300 px-2 py-2 text-center"></td>
                    </tr>

                    <tr>
                        <td class="border border-gray-300 px-2 py-2 text-center">Artificial Intelligence</td>
                        <td class="border border-gray-300 px-2 py-2 text-center"></td>
                        <td class="border border-gray-300 px-2 py-2 text-center"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="bg-[#c9a348] hover:bg-yellow-600 text-white px-4 py-2 rounded-lg whitespace-nowrap mt-10 font-bold">
                Add new material
            </button>
    </x-layoutstudy>
</x-layout>
