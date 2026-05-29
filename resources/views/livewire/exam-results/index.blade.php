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
                    <div class="w-full grid grid-cols-5 items-end justify-center gap-3">
                        <div class="flex-1">
                            <x-input-label>Academic Year</x-input-label>

                            <x-select-input
                                    placeholder="Select Academic Year"
                                    wire:model.live="academic_year"
                                    :options="$this->academic_years"
                                    title="Select Academic Year..."
                            />

                            <x-input-error
                                    class="mt-2"
                                    :messages="$errors->get('academic_year')"
                            />
                        </div>
                        <x-spinners.ring-resize target="academic_year" text="Loading classrooms..."/>
                        @if(!empty($this->classrooms))
                            <div class="flex-1">
                                <x-input-label>Classroom</x-input-label>

                                <x-select-input
                                        placeholder="Select Classroom"
                                        wire:model.live="classroom_id"
                                        :options="$this->classrooms"
                                        title="Select Classroom..."
                                />

                                <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('classroom_id')"
                                />
                            </div>
                        @elseif(empty($this->classrooms) && $this->academic_year)
                            Classrooms not found!
                        @endif
                        <x-spinners.ring-resize target="classroom_id" text="Loading courses..."/>
                        @if(!empty($this->classroom_courses))
                            <div class="flex-1">
                                <x-input-label>Course</x-input-label>

                                <x-select-input
                                        placeholder="Select Course"
                                        wire:model.live="classroom_course_id"
                                        :options="$this->classroom_courses"
                                        title="Select Course..."
                                />

                                <x-input-error
                                        class="mt-2"
                                        :messages="$errors->get('classroom_course_id')"
                                />
                            </div>
                        @elseif(empty($this->classroom_courses) && $this->classroom_id)
                            Courses not found!
                        @endif
                        <x-spinners.ring-resize target="classroom_course_id" text="Loading exams..."/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($classroom_course_id))
        <div class="py-3 gap-y-1">
            <div class=" mx-auto sm:px-6 lg:px-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <livewire:tables.exam-results.index-table
                                :key="'exam-results-'.$classroom_course_id"
                                :classroom_course_id="$classroom_course_id"
                        />
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
