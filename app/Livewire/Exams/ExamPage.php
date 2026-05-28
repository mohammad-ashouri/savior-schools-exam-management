<?php

namespace App\Livewire\Exams;

use App\Models\Exam\StudentExam;
use App\Models\Exam\StudentExamAnswer;
use App\Models\Exam\StudentExamQuestion;
use App\Models\Management\Question;
use App\Models\Management\SubQuestion;
use App\Service\DataService;
use App\Service\ExamService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class ExamPage extends Component
{
    public StudentExam $student_exam;

    public $exam_date;

    public $exam_time;

    public $exam_duration;

    public $questions = [];

    public $selected_question_id = null;

    public $selected_question;

    public $show_next_button = true;

    public $show_previous_button = false;

    public $show_end_button = false;

    public function mount($exam_id): void
    {
        $this->student_exam = StudentExam::findOrFail($exam_id);

        $my_students = DataService::getStudents();
        abort_if(!in_array($this->student_exam->classroomStudentInfo->applianceInfo->id, $my_students), 403);

        $this->questions = $this->student_exam
            ->questions()
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                return [
                    'exam_id' => $this->student_exam->id,
                    'question_id' => $row->id,
                    'id' => $row->questionInfo->id,
                ];
            })
            ->toArray();

        $this->selected_question_id = $this->questions[0]['question_id'];

        $this->dispatch('preventCopy');
    }

    /**
     * Check exam status
     * @return void
     */
    public function checkExamStatus(): void
    {
        $exam_status = ExamService::checkExamStatus($this->student_exam->classroomCourseInfo->id);
        if ($exam_status == null) {
            session()->flash('error','Exam time is over!');
            $this->redirect(route('exam.index'),navigate: true);
        }

        $this->exam_date = ExamService::getExamDate($this->student_exam->classroomCourseInfo->id, $exam_status);
        $this->exam_time = ExamService::getExamTime($this->student_exam->classroomCourseInfo->id, $exam_status);
        $this->exam_duration = ExamService::getExamDuration($this->student_exam->classroomCourseInfo->id, $exam_status);
    }

    /**
     * Show question
     * @return void
     */
    public function showQuestion(): void
    {
        $selected_question = StudentExamQuestion::find($this->selected_question_id);
        $question = Question::where('id', $selected_question->question_id)->firstOrFail();

        $this->selected_question = [
            'id' => $question->id,
            'question_type' => $question->question_type,
            'title' => $question->title,
            'options' => $question->options()
                ->get()
                ->mapWithKeys(function ($option) {
                    return [$option->id => $option->option];
                })
                ->toArray(),
            'sub_questions' => $question->subquestions()->get()->map(function ($row) {
                return [
                    'id' => $row->id,
                    'question_type' => $row->question_type,
                    'title' => $row->title,
                    'options' => $row->options()
                        ->get()
                        ->mapWithKeys(function ($option) {
                            return [$option->id => $option->option];
                        })
                        ->toArray(),
                ];
            })->toArray(),
        ];

        $first_question_id = current($this->questions)['question_id'];
        $last_question_id = end($this->questions)['question_id'];

        $this->show_previous_button = ($this->selected_question_id != $first_question_id);
        $this->show_next_button = ($this->selected_question_id != $last_question_id);
        $this->show_end_button = ($this->selected_question_id == $last_question_id);
    }

    /**
     * Set option after clicking on option
     * @param $option_id
     * @param $sub_question_id
     * @param $sub_question_option_id
     * @return void
     */
    public function setOption($option_id = null, $sub_question_id = null, $sub_question_option_id = null): void
    {
        if ($option_id != null) {
            StudentExamAnswer::updateOrCreate([
                'student_exam_question_id' => $this->selected_question_id,
                'user_id' => auth()->user()->id,
            ], [
                'option_id' => $option_id['option_id'],
            ]);
        }
        if ($sub_question_id != null and $sub_question_option_id != null) {
            StudentExamAnswer::updateOrCreate([
                'student_exam_question_id' => $this->selected_question_id,
                'user_id' => auth()->user()->id,
                'sub_question_id' => $sub_question_id,
            ], [
                'sub_question_option_id' => $sub_question_option_id,
            ]);
        }
    }

    public function getQuestion($question_id): void
    {
        $this->selected_question_id = $question_id;
    }

    /**
     * Next question
     * @return void
     */
    public function nextQuestion(): void
    {
        switch ($this->selected_question['question_type']){
            case 'multiple_choice':
                if (ExamService::checkSelectedAnswerMultipleAnswer($this->selected_question_id) == null) {
                    $this->dispatch('open-modal', 'next-notif');
                    return;
                }
                break;
            case 'multipart_question':
                if (!ExamService::checkHowManyQuestionsAnsweredInMultipartQuestion($this->selected_question_id)) {
                    $this->dispatch('open-modal', 'next-notif');
                    return;
                }
                break;
        }
        if (!$this->selected_question_id) dd($this->selected_question);
        $this->getQuestion($this->selected_question_id += 1);
    }

    /**
     * Previous question
     * @return void
     */
    public function previousQuestion(): void
    {
        $this->getQuestion($this->selected_question_id -= 1);
    }

    /**
     * End exam and redirect to exam index
     * @return void
     */
    public function endExam(): void
    {
        $this->redirect(route('exam.index'), navigate: true);
        session()->flash('success', 'Exam finished successfully.');
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $this->showQuestion();
        $this->checkExamStatus();

        return view('livewire.exams.exam-page')
            ->title('Exam Page | ' . $this->student_exam->classroomCourseInfo->courseInfo->name . " | " . $this->student_exam->classroomCourseInfo->courseInfo->gradeInfo->name);
    }
}
