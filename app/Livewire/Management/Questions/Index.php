<?php

namespace App\Livewire\Management\Questions;

use App\Livewire\Forms\Management\QuestionForm;
use App\Models\Management\ClassroomCourse;
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
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $this->question_form->question_types = ExamService::getQuestionTypes();
        return view('livewire.management.questions.index')
            ->title("Management | Classroom Course | Questions");
    }
}
