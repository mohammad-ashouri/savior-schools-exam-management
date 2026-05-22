<div class="flex gap-1 justify-center">
    @if($buttons)
        @if(in_array('edit',$buttons))
            @if(isset($route))
                <x-secondary-button
                        wire:navigate
                        href="{{ $route }}"
                        title="Edit"
                >Edit
                </x-secondary-button>
            @else
                <x-secondary-button
                        x-on:click="$dispatch('open-modal', 'edit'); $dispatch('get_data', { id: {{ $row->id }} } );"
                        title="Edit"
                >Edit
                </x-secondary-button>
            @endif
        @endif

        @if(in_array('delete',$buttons))
            <x-danger-button
                    x-on:click="$dispatch('open-modal', 'confirm-delete'); $dispatch('set_delete_id', { id: {{ $row->id }} }); $dispatch('set-selected-id', [{{ $row->id }}]);"
                    title="Delete"
            >
                Delete
            </x-danger-button>
        @endif

        @if(in_array('courses',$buttons))
            <x-primary-button
                    wire:navigate
                    href="{{ $courses_route ?? $courses_route_name }}"
                    title="Courses"
            >
                Courses
            </x-primary-button>
        @endif

        @if(in_array('first_term_exam',$buttons))
            <x-primary-button
                    wire:navigate
                    href="{{ $first_term_exam_route_name }}"
            >
                First Term Exam
            </x-primary-button>
        @endif

        @if(in_array('second_term_exam',$buttons))
            <x-primary-button
                    wire:navigate
                    href="{{ $second_term_exam_route_name }}"
            >
                Second Term Exam
            </x-primary-button>
        @endif

        @if(in_array('retake_exam',$buttons))
            <x-primary-button
                    wire:navigate
                    href="{{ $retake_exam_route_name }}"
            >
                Retake Exam
            </x-primary-button>
        @endif

        @if(in_array('sub questions',$buttons))
            <x-primary-button
                    wire:navigate
                    href="{{ $sub_questions_route_name }}"
            >
                Subquestions
            </x-primary-button>
        @endif

        @if(in_array('start_exam',$buttons))
            <x-primary-button
                    wire:navigate
                    href="{{ $exam_route_name }}"
            >
                Start Exam
            </x-primary-button>
        @endif

    @endif
</div>
