<?php

namespace App\Livewire\Management\Questions\ManageQuestions;

use App\Livewire\Forms\Management\QuestionForm;
use App\Models\Management\ClassroomCourse;
use App\Models\Management\Question;
use App\Models\Management\SubQuestion;
use App\Models\Management\SubQuestionOption;
use App\Service\ExamService;
use App\Service\FileManagerService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

class MultipartQuestionSubQuestions extends Component
{
    public Question $question;

    public QuestionForm $question_form;

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
     * Mount the component
     * @param string $question_id
     * @return void
     */
    public function mount(string $question_id): void
    {
        $this->question = Question::where('id', $question_id)->firstOrFail();
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

                $question = ExamService::newSubQuestion($this->question->id, $this->question_form->question_type, $this->question_form->title);

                for ($i = 1; $i <= 4; $i++) {
                    $field = 'option' . $i;
                    ExamService::newSubQuestionOption($question->id, $this->question_form->$field, $this->question_form->correct_answer == $i ? true : false);
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
    }

    /**
     * Delete question
     * @return void
     */
    public function deleteQuestion(): void
    {
        SubQuestion::findOrFail($this->selected_question_id)->delete();
        SubQuestionOption::where('sub_question_id', $this->selected_question_id)->delete();
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
        $this->question_form->question_types = ExamService::getQuestionTypes(except: ["multipart_question"]);

        return view('livewire.management.questions.manage-questions.multipart-question-sub-questions')
            ->title("Management | Classroom Course | Questions | Sub Questions");
    }
}
