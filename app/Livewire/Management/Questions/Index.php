<?php

namespace App\Livewire\Management\Questions;

use App\Livewire\Forms\Management\QuestionForm;
use App\Models\Management\ClassroomCourse;
use App\Models\Management\ExamInfo;
use App\Models\Management\Option;
use App\Models\Management\Question;
use App\Service\ExamService;
use App\Service\FileManagerService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    public ClassroomCourse $classroom_course;

    public QuestionForm $question_form;

    public string $term;

    public string $term_title;

    public $no_of_questions;

    public $exam_date;

    public $exam_time;

    public $exam_duration;

    public $selected_question_id;

    protected $listeners = [
        'resetFields',
        'set-selected-id' => 'setSelectedId',
    ];

    /**
     * Set selected question id after dispatched event
     * @param $id
     * @return void
     */
    public function setSelectedId($id): void
    {
        $this->selected_question_id = $id;
    }

    /**
     * Reset fields after dispatch event
     * @return void
     */
    public function resetFields(): void
    {
        $this->reset(
            'question_form.question_type',
            'question_form.title',
            'question_form.image',
            'question_form.option1',
            'question_form.option2',
            'question_form.option3',
            'question_form.option4',
            'question_form.correct_answer',
        );
    }

    /**
     * Mount the component
     * @param int $classroom_id
     * @param int $classroom_course_id
     * @param string $term
     * @return void
     */
    public function mount(int $classroom_id, int $classroom_course_id, string $term): void
    {
        abort_unless(in_array($term, ['first', 'second', 'retake']), 403);

        $this->classroom_course = ClassroomCourse::where('status', 1)->where('id', $classroom_course_id)->firstOrFail();

        $this->no_of_questions = ExamService::getNoOfQuestions($this->classroom_course->id, $this->term);

        $this->exam_date = ExamService::getExamDate($this->classroom_course->id, $this->term);

        $this->exam_time = ExamService::getExamTime($this->classroom_course->id, $this->term);

        $this->exam_duration = ExamService::getExamDuration($this->classroom_course->id, $this->term);
    }

    /**
     * Set number of questions in exam
     * @return void
     */
    public function setNoOfQuestions(): void
    {
        $this->validate(['no_of_questions' => 'required|integer|min:10']);
        ExamInfo::updateOrCreate([
            'classroom_course_id' => $this->classroom_course->id,
            'term' => $this->term,
            'type' => 'number_of_questions',
        ], [
            'value' => $this->no_of_questions,
            'user' => auth()->user()->id
        ]);

        $this->dispatch('show-notification', 'success-notification');
    }

    /**
     * Set exam date
     * @return void
     */
    public function setExamDate(): void
    {
        $this->validate(['exam_date' => 'required|date|after:today']);
        ExamInfo::updateOrCreate([
            'classroom_course_id' => $this->classroom_course->id,
            'term' => $this->term,
            'type' => 'exam_date',
        ], [
            'value' => $this->exam_date,
            'user' => auth()->user()->id
        ]);

        $this->dispatch('show-notification', 'success-notification');
    }

    /**
     * Set exam time
     * @return void
     */
    public function setExamTime(): void
    {
        $this->validate(['exam_time' => 'required|date_format:H:i']);
        ExamInfo::updateOrCreate([
            'classroom_course_id' => $this->classroom_course->id,
            'term' => $this->term,
            'type' => 'exam_time',
        ], [
            'value' => $this->exam_time,
            'user' => auth()->user()->id
        ]);

        $this->dispatch('show-notification', 'success-notification');
    }

    /**
     * Set exam duration
     * @return void
     */
    public function setExamDuration(): void
    {
        $this->validate(['exam_duration' => 'required|integer|min:1|max:360']);
        ExamInfo::updateOrCreate([
            'classroom_course_id' => $this->classroom_course->id,
            'term' => $this->term,
            'type' => 'exam_duration',
        ], [
            'value' => $this->exam_duration,
            'user' => auth()->user()->id
        ]);

        $this->dispatch('show-notification', 'success-notification');
    }

    /**
     * Create question
     * @return void
     */
    public function createQuestion(): void
    {
        $this->question_form->validate();

        switch ($this->question_form->question_type) {
            case 'multiple_choice':
                $this->question_form->validate([
                    'option1' => 'required|string',
                    'option2' => 'required|string',
                    'option3' => 'required|string',
                    'option4' => 'required|string',
                    'correct_answer' => 'required|integer|in:1,2,3,4',
                ]);

                $question = ExamService::newQuestion($this->classroom_course->id, $this->question_form->question_type, $this->question_form->title, $this->term);

                for ($i = 1; $i <= 4; $i++) {
                    $field = 'option' . $i;
                    ExamService::newOption($question->id, $this->question_form->$field, $this->question_form->correct_answer == $i ? true : false);
                }
                break;
            case 'multipart_question':
                $question = ExamService::newQuestion($this->classroom_course->id, $this->question_form->question_type, $this->question_form->title, $this->term);
                break;
        }

        if (isset($question) and $this->question_form->image) {
            FileManagerService::saveFile($this->image, 'questions', $question->id, Question::class, 'question_image');
        }

        $this->dispatch('close-modal', 'create');
        $this->dispatch('show-notification', 'success-notification');
        $this->dispatch('refreshTable');
        $this->dispatch('clear-tinymce');
    }

    /**
     * Delete question
     * @return void
     */
    public function deleteQuestion(): void
    {
        Question::findOrFail($this->selected_question_id)->delete();
        Option::where('question_id', $this->selected_question_id)->delete();
        $this->dispatch('refreshTable');
        $this->dispatch('close-modal', 'confirm-delete');
        $this->dispatch('show-notification', 'success-notification');
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        if (!auth()->user()->can("exam-management.manage-exams")) {
            abort(403, 'Access denied.');
        }
        $this->question_form->question_types = ExamService::getQuestionTypes();
        return view('livewire.management.questions.index')
            ->title("Management | Classroom Course | Questions");
    }
}
