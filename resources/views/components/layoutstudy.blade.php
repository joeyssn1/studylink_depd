{{-- views/components/layoutstudy.blade.php --}}

<div class="flex flex-col items-center justify-center py-20">
    <x-studypagetitle studyTitle="{{ $studyTitle }}" />

    <div class="w-full max-w-4xl mt-8">
        {{ $slot }}
    </div>
</div>
