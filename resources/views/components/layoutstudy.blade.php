{{-- views/components/layoutstudy.blade.php --}}

<div class="flex flex-col items-center justify-center py-16">
        <x-studypagetitle studyTitle="{{ $studyTitle }}" />

        {{{ $slot }}}
    </div>