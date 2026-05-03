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
                    <livewire:tables.exams.classroom-courses-table />
                </div>
            </div>
        </div>
    </div>
</div>
