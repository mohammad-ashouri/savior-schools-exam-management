<div x-transition x-show="question_type=='multiple_choice'" class="grid grid-cols-2 gap-2 mt-4 space-y-1">
    <div>
        <x-input-label>Option 1</x-input-label>
        <div wire:ignore>
            <x-textbox class="w-full tinymce-editor" id="option1-editor"
                       placeholder="Enter Option 1"/>
        </div>
        <input type="hidden" id="option1-editor-input" wire:model="question_form.option1">
        <x-input-error class="mt-2" :messages="$errors->get('question_form.option1')"/>
    </div>
    <div>
        <x-input-label>Option 2</x-input-label>
        <div wire:ignore>
            <x-textbox class="w-full tinymce-editor" id="option2-editor"
                       placeholder="Enter Option 2"/>
        </div>
        <input type="hidden" id="option2-editor-input" wire:model="question_form.option2">
        <x-input-error class="mt-2" :messages="$errors->get('question_form.option2')"/>
    </div>
    <div>
        <x-input-label>Option 3</x-input-label>
        <div wire:ignore>
            <x-textbox class="w-full tinymce-editor" id="option3-editor"
                       placeholder="Enter Option 3"/>
        </div>
        <input type="hidden" id="option3-editor-input" wire:model="question_form.option3">
        <x-input-error class="mt-2" :messages="$errors->get('question_form.option3')"/>
    </div>
    <div>
        <x-input-label>Option 4</x-input-label>
        <div wire:ignore>
            <x-textbox class="w-full tinymce-editor" id="option4-editor"
                       placeholder="Enter Option 4"/>
        </div>
        <input type="hidden" id="option4-editor-input" wire:model="question_form.option4">
        <x-input-error class="mt-2" :messages="$errors->get('question_form.option4')"/>
    </div>

</div>