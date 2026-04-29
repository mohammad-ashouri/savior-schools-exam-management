<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight">
            Management | {{ $this->classroom_course->classroomInfo->academicYearInfo->name }} | {{ $this->classroom_course->classroomInfo->name }} | {{ $this->classroom_course->classroomInfo->gradeInfo->name }}
        </h2>
    </x-slot>

    <div class="py-3 gap-y-1">
        <div class=" mx-auto sm:px-6 lg:px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <livewire:tables.classroom-courses-table :classroom_id="$this->classroom_course->classroom_id"/>
                </div>
            </div>
        </div>
    </div>
</div>
