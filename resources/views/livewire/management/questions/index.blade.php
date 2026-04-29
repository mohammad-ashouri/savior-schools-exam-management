@php use App\Service\TextService; @endphp
<div x-cloak
     x-data="{
    question_type:@entangle('question_form.question_type'),
{{--    init() {--}}
{{--        this.$watch('question_type', value => {--}}

{{--        });--}}
{{--    }--}}
}"
>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight">
            Management | {{ $this->classroom_course->classroomInfo->academicYearInfo->name }}
            | {{ $this->classroom_course->classroomInfo->gradeInfo->name }}
            | {{ $this->classroom_course->classroomInfo->name }} | {{ $this->classroom_course->courseInfo->name }}
            | {{ TextService::getTermTypeTitle($this->term) }} Exam Questions
        </h2>
    </x-slot>

    <x-modal maxWidth="5xl" name="create">
        <form
                wire:submit.prevent="createQuestion"
        >
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight"
                    >Create Question
                    </h2>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="mt-4 space-y-1">
                        <x-input-label>Question Type</x-input-label>
                        <x-select-input
                                placeholder="Select question type"
                                wire:model="question_form.question_type"
                                :options="$question_form->question_types"
                                title="Select question type..."/>
                        <x-input-error class="mt-2" :messages="$errors->get('question_form.question_type')"/>
                    </div>
                </div>
                <div x-transition x-show="question_type!=null" class="grid grid-cols-2 gap-2">
                    <div class="mt-4 space-y-1">
                        <x-input-label>Title</x-input-label>
                        <div wire:ignore>
                            <x-textbox class="w-full tinymce-editor" id="title-editor"
                                       placeholder="Enter question title"/>
                        </div>
                        <input type="hidden" id="title-editor-input" wire:model="question_form.title">
                        <x-input-error class="mt-2" :messages="$errors->get('question_form.title')"/>
                    </div>
                    <div class="mt-4 space-y-1">
                        <x-input-label>Attachment Image</x-input-label>
                        <x-filepond::upload wire:model="question_form.image"
                                            :allowMultiple="false"
                                            :instantUpload="true"
                                            server-headers='@json(["X-CSRF-TOKEN" => csrf_token()])'
                                            :chunkSize="2000000"
                                            :accept="'image/jpg,image/png,image/jpeg,image.bmp'"/>
                        <x-input-error class="mt-2" :messages="$errors->get('question_form.image')"/>

                        <div x-transition x-show="question_type=='multiple_choice'"
                             class="grid grid-cols-1 gap-2 mt-4 space-y-1">
                            <div class="mt-4 space-y-1">
                                <x-input-label>Correct Answer</x-input-label>
                                <x-select-input
                                        placeholder="Select correct answer"
                                        wire:model="question_form.correct_answer"
                                        :options="[
                                            '1'=>'Option 1',
                                            '2'=>'Option 2',
                                            '3'=>'Option 3',
                                            '4'=>'Option 4',
                                        ]"
                                        title="Select question type..."/>
                                <x-input-error class="mt-2" :messages="$errors->get('question_form.correct_answer')"/>
                            </div>
                        </div>
                    </div>
                </div>
                <x-management.questions.multiple-choice-form/>
            </div>
            <div class="flex items-center justify-end gap-2 p-4">
                <x-secondary-button type="button" x-on:click="$dispatch('close-modal','create');$wire.set('question_form.question_type', null);">Close
                </x-secondary-button>
                <x-primary-button wire:loading.remove type="submit">Create</x-primary-button>
                <span wire:loading class="text-gray-500">Processing...</span>
            </div>
        </form>
    </x-modal>

    <header class="bg-secondary dark:bg-gray-800 shadow">
        <div class=" mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <x-success-button
                    x-on:click="$dispatch('open-modal','create')"
            >
                New Question
            </x-success-button>
        </div>
    </header>

    <div class="py-3 gap-y-1">
        <div class=" mx-auto sm:px-6 lg:px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:tables.classroom-course-questions-table
                            :classroom_course_id="$this->classroom_course->id"/>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', function () {
                // Function to sync TinyMCE content with Livewire
                function syncTinyMCEWithLivewire() {
                    const editors = [
                        {editorId: 'title-editor', inputId: 'title-editor-input'},
                    ];

                    editors.forEach(({editorId, inputId}) => {
                        const editor = tinymce.get(editorId);
                        const input = document.getElementById(inputId);

                        if (editor && input) {
                            // Set initial content from Livewire model to editor
                            if (input.value) {
                                editor.setContent(input.value);
                            }

                            // Update hidden input when editor content changes
                            editor.on('Change input Undo Redo', function () {
                                const content = editor.getContent();
                                input.value = content;
                                input.dispatchEvent(new Event('input'));
                            });

                            // Listen for Livewire updates to sync back to editor
                            Livewire.on('updateTinyMCE', ({id, content}) => {
                                if (id === editorId && editor) {
                                    editor.setContent(content || '');
                                }
                            });
                        }
                    });
                }

                // Initialize sync when TinyMCE is ready
                if (window.tinymce) {
                    tinymce.on('AddEditor', function (e) {
                        e.editor.on('init', function () {
                            syncTinyMCEWithLivewire();
                        });
                    });
                }
            });
        </script>
    @endpush

</div>
