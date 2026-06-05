@php use App\Service\ExamService; @endphp
<div wire:poll.30s="checkExamStatus">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Exam: {{ $this->student_exam->classroomCourseInfo->courseInfo->name . " | " . $this->student_exam->classroomCourseInfo->courseInfo->gradeInfo->name }}
        </h2>
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Student: {{ $this->student_exam->classroomStudentInfo->applianceInfo->student_id . " - " . $this->student_exam->classroomStudentInfo->applianceInfo->studentGeneralInfo->en_fullname }}
        </h2>
        <h2 class="font-semibold text-xl text-gray-100 dark:text-gray-200 leading-tight mb-2">
            Exam Date And Time: {{ $this->exam_date . " - " . $this->exam_time . " - " . $this->exam_duration }} Minutes
        </h2>
        @php
            $duration = (int) $this->exam_duration;

            $endTime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->exam_date . ' ' . $this->exam_time
            )->addMinutes($duration)->format('Y-m-d H:i:s');
        @endphp

        <div
                x-data="{
                    endTime: new Date('{{ $endTime }}').getTime(),
                    remaining: '00:00:00',

                    init() {
                        this.updateTimer();

                        setInterval(() => {
                            this.updateTimer();
                        }, 1000);
                    },

                    updateTimer() {
                        let distance = this.endTime - Date.now();

                        if (distance <= 0) {
                            this.remaining = '00:00:00';
                            $wire.dispatch('finish-exam');
                            return;
                        }

                        let hours = Math.floor(distance / (1000 * 60 * 60));
                        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        this.remaining =
                            String(hours).padStart(2, '0') + ':' +
                            String(minutes).padStart(2, '0') + ':' +
                            String(seconds).padStart(2, '0');
                    }
                }"
                class="text-red-500 font-bold text-2xl"
        >
            ⏳ Time Remaining:
            <span x-text="remaining"></span>
        </div>
    </x-slot>

    <x-modal name="next-notif" :show="$errors->isNotEmpty()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                You cannot proceed to the next question without answering this one.
            </h2>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Close
                </x-secondary-button>

                <x-spinners.ring-resize target="startExam" text="Redirecting..."/>
            </div>
        </div>
    </x-modal>

    <x-modal name="finish-exam" :show="$errors->isNotEmpty()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Ready to finish the exam?
            </h2>
            <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                Once submitted, your answers will be finalized and cannot be changed.
            </h2>
            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Close
                </x-secondary-button>

                <x-success-button
                        wire:loading.remove
                        wire:target="endExam"
                        wire:click="endExam"
                        class="ms-3">
                    Yes, Finished!
                </x-success-button>
                <x-spinners.ring-resize target="endExam" text="Entering..."/>
            </div>
        </div>
    </x-modal>
    <div class="py-3 gap-y-1">
        <div class=" mx-auto sm:px-6 lg:px-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                    @foreach($questions as $key=>$question)
                        @php
                            $status = $question['status'] ?? 'normal'; // normal, selected, answered, flagged, etc.

                            $isSelected = ($question['question_id'] == $selected_question_id);
                            $hasAnswer = (ExamService::checkSelectedAnswerMultipleChoice($question['question_id']) != null);

                            if ($isSelected && $hasAnswer) {
                                $status = 'review';
                            } elseif ($isSelected) {
                                $status = 'selected';
                            } elseif ($hasAnswer) {
                                $status = 'answered';
                            } else {
                                $status = 'normal';
                            }

                            $gradientClass = match($status) {
                                'selected' => 'from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800',
                                'answered' => 'from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800',
                                'review' => 'from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700',
                                default => 'from-slate-600 to-gray-700 hover:from-slate-700 hover:to-gray-800'
                            };
                        @endphp

                        <button
                                class="w-10 py-3 bg-gradient-to-r {{ $gradientClass }} text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 text-center">
                            {{ ++$key }}
                        </button>
                    @endforeach
                </div>
            </div>
            <div class="bg-white mt-3  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    @switch($this->selected_question['question_type'])
                        @case('multiple_choice')
                            <div class="mb-6">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 font-bold">?</span>
                                    </div>
                                    <h3 class="flex-1 text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">
                                        {!! $this->selected_question['title'] !!}
                                    </h3>
                                </div>
                            </div>

                            <div class="grid gap-3">
                                @php
                                    $letters = ['A', 'B', 'C', 'D', 'E', 'F'];
                                    $index = 0;
                                @endphp
                                @foreach($this->selected_question['options'] as $id => $option)
                                    <label class="flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-md flex items-center justify-center mr-3">
                                            <span class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ $letters[$index++] }}</span>
                                        </div>
                                        <input type="radio"
                                               name="question_option"
                                               id="option_{{ $id }}"
                                               value="{{ $id }}"
                                               @checked(ExamService::checkSelectedAnswerMultipleChoice($this->selected_question_id)==$id)
                                               wire:click="setOption({option_id: {{ $id }}})"
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                        <label for="option_{{ $id }}"
                                               class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer flex-1">
                                            {!! $option !!}
                                        </label>
                                    </label>
                                @endforeach
                            </div>
                            @break
                        @case('multipart_question')
                            <div class="mb-6">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 font-bold">?</span>
                                    </div>
                                    <h3 class="flex-1 text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">
                                        {!! $this->selected_question['title'] !!}
                                    </h3>
                                </div>
                            </div>
                            @foreach($this->selected_question['sub_questions'] as $sub_question)
                                <div wire:key="op-key-{{ $sub_question['id'] }}" class="grid gap-3 my-10 pl-12">
                                    <div class="">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                                <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $loop->iteration }}</span>
                                            </div>
                                            <h3 class="flex-1 text-lg font-semibold text-gray-900 dark:text-white leading-relaxed">
                                                {!! $sub_question['title'] !!}
                                            </h3>
                                        </div>
                                    </div>
                                    @php
                                        $letters = ['A', 'B', 'C', 'D'];
                                        $index = 0;
                                    @endphp
                                    @foreach($sub_question['options'] as $id => $option)
                                        <label wire:key="op-key-{{ $id }}"
                                               class="flex items-center p-3 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-md flex items-center justify-center mr-3">
                                                <span class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ $letters[$index++] }}</span>
                                            </div>
                                            <input type="radio"
                                                   name="question_option_{{ $sub_question['id'] }}"
                                                   id="option_{{ $id }}"
                                                   value="{{ $id }}"
                                                   wire:loading.remove
                                                   wire:loading.attr="disabled"
                                                   @checked(ExamService::checkSelectedAnswerMultipartQuestion($this->selected_question_id,$sub_question['id'])==$id)
                                                   wire:click="setOption(null, {{ $sub_question['id'] }},{{ $id }})"
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                            <label for="option_{{ $id }}"
                                                   class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer flex-1">
                                                {!! $option !!}
                                            </label>
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                            @break
                    @endswitch
                    <div class="flex justify-between items-center align-middle mt-6">
                        @if($show_previous_button)
                            <button
                                    wire:click="previousQuestion"
                                    wire:target="previousQuestion,setOption"
                                    wire:loading.remove
                                    class="px-6 py-3 bg-gradient-to-r from-slate-600 to-gray-700 hover:from-slate-700 hover:to-gray-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                Previous
                            </button>
                            <x-spinners.ring-resize target="previousQuestion,setOption" text="Wait..."/>
                        @endif

                        @if($show_next_button)
                            <button
                                    wire:click="nextQuestion"
                                    wire:target="nextQuestion,setOption"
                                    wire:loading.remove
                                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                Next
                            </button>
                            <x-spinners.ring-resize target="nextQuestion,setOption" text="Wait..."/>
                        @endif

                        @if($show_end_button)
                            <button
                                    wire:click="$dispatch('open-modal','finish-exam')"
                                    wire:loading.remove
                                    wire:target="setOption"
                                    class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                Finish!
                            </button>
                            <x-spinners.ring-resize text="Wait..."/>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(app()->environment('production'))
        @vite(['resources/js/copy-blocker.js'])
    @endif
</div>

