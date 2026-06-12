<div
        x-cloak
        x-data="{
    question_type:@entangle('question_form.question_type'),
    }
">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight">
            Management | Edit Question
        </h2>
    </x-slot>
    <div class="py-3 gap-y-1">
        <div class=" mx-auto sm:px-6 lg:px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form
                        wire:submit.prevent="editQuestion"
                >
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight"
                            >Edit Question
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="mt-4 space-y-1">
                                <x-input-label>Question Type</x-input-label>
                                <x-select-input
                                        placeholder="Select question type"
                                        disabled
                                        class="bg-gray-300"
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
                                    <x-textbox class="w-full tinymce-editor" id="edit-title-editor"
                                               placeholder="Enter question title"/>
                                </div>
                                <input type="hidden" id="edit-title-editor-input" wire:model="question_form.title">
                                <x-input-error class="mt-2" :messages="$errors->get('question_form.title')"/>
                            </div>
                            <div class="mt-4 space-y-1">
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
                                        <x-input-error class="mt-2"
                                                       :messages="$errors->get('question_form.correct_answer')"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-management.questions.multiple-choice-form/>
                    </div>
                    <div class="flex items-center justify-end gap-2 p-4">
                        <x-secondary-button type="button"
                                            x-on:click="$dispatch('close-modal','edit');$wire.set('question_form.question_type', null);">
                            Close
                        </x-secondary-button>
                        <x-primary-button wire:loading.remove type="submit">Save</x-primary-button>
                        <span wire:loading class="text-gray-500">Processing...</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function initTinyMCE() {

            if (!window.tinymce) return;

            // جلوگیری از duplicate init
            tinymce.remove('.tinymce-editor');

            tinymce.init({
                selector: '.tinymce-editor',

                plugins: 'code table lists link autolink autosave image media preview save wordcount fullscreen searchreplace visualblocks visualchars nonbreaking pagebreak charmap anchor insertdatetime advlist help',

                toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | code',

                // paste_as_text: true,

                paste_preprocess: function (plugin, args) {
                    args.content = args.content.replace(/<table[\s\S]*?<\/table>/gi, '');

                    args.content = args.content.replace(/<\/?(tr|td|tbody|thead)[^>]*>/gi, '');
                },
                setup: function (editor) {

                    editor.on('init', function () {

                        const input =
                            document.getElementById(editor.id + '-input');

                        if (input) {
                            editor.setContent(input.value || '');
                        }
                    });

                    editor.on('change keyup', function () {

                        const input =
                            document.getElementById(editor.id + '-input');

                        if (input) {

                            input.value = editor.getContent();

                            input.dispatchEvent(
                                new Event('input', {bubbles: true})
                            );
                        }
                    });
                }
            });
        }

        document.addEventListener('livewire:init', () => {

            initTinyMCE();

            // بعد از هر رندر لایووایر
            Livewire.hook('morphed', () => {

                setTimeout(() => {
                    initTinyMCE();
                }, 50);
            });

            // پاک کردن editor ها
            Livewire.on('clear-tinymce', () => {

                const editors = tinymce.get(); // همه editorها

                Object.values(editors).forEach(editor => {

                    if (!editor) return;

                    editor.setContent('');

                    const input = document.getElementById(editor.id + '-input');

                    if (input) {
                        input.value = '';
                        input.dispatchEvent(new Event('input', {bubbles: true}));
                    }
                });
            });

        });
    </script>

</div>
