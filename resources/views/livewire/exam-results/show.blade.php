@php use App\Service\ExamService; @endphp
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Academic Year: {{ $this->student_exam->classroomCourseInfo->classroomInfo->academicYearInfo->name }}
        </h2>
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Exam: {{ $this->student_exam->classroomCourseInfo->courseInfo->name . " | " . $this->student_exam->classroomCourseInfo->courseInfo->gradeInfo->name }}
        </h2>
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Student: {{ $this->student_exam->classroomStudentInfo->applianceInfo->student_id . " - " . $this->student_exam->classroomStudentInfo->applianceInfo->studentGeneralInfo->en_fullname }}
        </h2>
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Exam Date And Time: {{ $this->exam_date . " - " . $this->exam_time . " - " . $this->exam_duration }} Minutes
        </h2>
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Finished At: {{ $this->student_exam->finished_at }}
        </h2>
    </x-slot>

    <div class="py-3 gap-y-1">
        <div class=" mx-auto sm:px-6 lg:px-6">
            <div class="bg-white mt-3  overflow-hidden shadow-sm sm:rounded-lg">
                @foreach($this->student_exam->questions as $question)
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="mb-6">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $loop->iteration }}</span>
                                </div>
                                <h3 class="flex-1 text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">
                                    {!! $question->questionInfo->title !!}
                                </h3>
                            </div>
                        </div>
                        @switch($question->questionInfo->question_type)
                            @case('multiple_choice')

                                <div class="grid gap-3">
                                    @php
                                        $letters = ['A', 'B', 'C', 'D'];
                                        $index = 0;
                                    @endphp
                                    @foreach($question->questionInfo->options as $option)
                                        @php
                                            $selected=ExamService::checkSelectedAnswerMultipleAnswer($question->id)==$option->id;
                                        @endphp
                                        <label class="flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200
                                        @if($selected && $option->correct) bg-green-300 @endif
                                        @if($selected && !$option->correct) bg-red-400 @endif
                                        ">
                                            <div class="flex-shrink-0 w-8 h-8 {{ $option->correct ? 'bg-green-400' : 'bg-gray-100' }} dark:bg-gray-700 rounded-md flex items-center justify-center mr-3">
                                                <span class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ $letters[$index++] }}</span>
                                            </div>
                                            <input type="radio"
                                                   disabled
                                                   name="question_option_{{ $question->id }}"
                                                   id="option_{{ $option->id }}"
                                                   value="{{ $option->id }}"
                                                   @checked($selected)
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="option_{{ $option->id }}"
                                                   class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer flex-1">
                                                {!! $option->option !!}
                                            </label>
                                        </label>
                                    @endforeach
                                </div>
                                @break
                            @case('multipart_question')
                                @foreach($question->questionInfo->subQuestions as $sub_question)
                                    <div wire:key="op-key-{{ $sub_question->id }}" class="grid gap-3 my-10 pl-12">
                                        <div class="">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $loop->iteration }}</span>
                                                </div>
                                                <h3 class="flex-1 text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">
                                                    {!! $sub_question->title !!}
                                                </h3>
                                            </div>
                                        </div>
                                        @php
                                            $letters = ['A', 'B', 'C', 'D'];
                                            $index = 0;
                                        @endphp
                                        @foreach($sub_question->options as $option)
                                            @php
                                                $selected=ExamService::checkSelectedAnswerMultipartQuestion($question->id,$sub_question->id)==$option->id;
                                            @endphp
                                            <label wire:key="op-key-{{ $option->id }}"
                                                   class="flex
                                                   @if($selected && $option->correct) bg-green-300 @endif
                                                   @if($selected && !$option->correct) bg-red-400 @endif
                                                   items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                                <div class="flex-shrink-0 w-8 h-8 {{ $option->correct ? 'bg-green-400' : 'bg-gray-100' }} dark:bg-gray-700 rounded-md flex items-center justify-center mr-3">
                                                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ $letters[$index++] }}</span>
                                                </div>
                                                <input type="radio"
                                                       name="question_option_{{ $option->id }}"
                                                       disabled
                                                       id="option_{{ $option->id }}"
                                                       @checked($selected)
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                                <label for="option_{{ $option->id }}"
                                                       class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer flex-1">
                                                    {!! $option->option !!}
                                                </label>
                                            </label>
                                        @endforeach
                                    </div>
                                @endforeach
                                @break
                        @endswitch
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

