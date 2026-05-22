<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight">
            Exams
        </h2>
    </x-slot>

    <div class="py-3 gap-y-1">
        <div class=" mx-auto sm:px-6 lg:px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-end gap-3 w-96">
                        <div class="flex-1">
                            <x-input-label>Student</x-input-label>

                            <x-select-input
                                    placeholder="Select student"
                                    wire:model="student"
                                    :options="$students"
                                    title="Select student..."
                            />

                            <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('student')"
                            />
                        </div>
                        <div>
                            <x-success-button
                                    class="py-3"
                                    wire:loading.remove
                                    wire:click="getCourses"
                            >
                                Get Exams
                            </x-success-button>
                            <x-spinners.ring-resize target="getCourses" text="Getting..."/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($classroom_student))
        <div class="py-3 gap-y-1">
            <div class=" mx-auto sm:px-6 lg:px-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <livewire:tables.exams.exam-classroom-courses-table
                                :key="$classroom_student->classroom_id"
                                :student="$student"
                                :classroom_id="$classroom_student->classroom_id"/>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
