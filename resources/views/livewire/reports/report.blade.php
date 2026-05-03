@php use Morilog\Jalali\Jalalian; @endphp

<div>
    @switch ($this->report_type)
        @case('ExamPaper')
            <livewire:reports.types.exam-paper/>
            @break
        @default
            {{ abort(404,'Entry not found') }}
    @endswitch
</div>
