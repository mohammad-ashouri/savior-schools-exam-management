<?php

namespace App\Livewire\Management\Questions;

use App\Livewire\Forms\Management\QuestionForm;
use App\Models\Management\Question;
use App\Service\ExamService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Edit Question")]
class Edit extends Component
{
    public Question $question;

    public QuestionForm $question_form;

    public function getQuestionData()
    {
        $this->question_form->question_type = $this->question->question_type;
        $this->question_form->title = $this->question->title;

        $data = [
            'title' => $this->question->title,
            'option1' => '',
            'option2' => '',
            'option3' => '',
            'option4' => '',
            'correct_answer' => null,
        ];
        switch ($this->question->question_type) {
            case 'multiple_choice':
                $options = $this->question->options()->orderBy('id')->get();
                foreach ($options as $index => $option) {
                    $field = 'option' . ($index + 1);
                    $this->question_form->$field = $option->option;
                    $data[$field] = $option->option;
                    if ($option->correct) {
                        $this->question_form->correct_answer = $index + 1;
                        $data['correct_answer'] = $index + 1;
                    }
                }
                break;
        }
        $this->dispatch('load-tinymce-content', data: $data);
    }

    public function editQuestion()
    {
        $this->question_form->validate();

        $question = ExamService::editQuestion($this->question->id, $this->question_form->title);
        switch ($this->question_form->question_type) {
            case 'multiple_choice':
                $this->question_form->validate([
                    'option1' => 'required|string',
                    'option2' => 'required|string',
                    'option3' => 'required|string',
                    'option4' => 'required|string',
                    'correct_answer' => 'required|integer|in:1,2,3,4',
                ]);

                $options = $this->question->options()->orderBy('id')->get();
                foreach ($options as $index => $option) {
                    $i = $index + 1;
                    $field = 'option' . $i;
                    ExamService::editOption($option->id, $this->question_form->$field, $this->question_form->correct_answer == $i ? true : false);
                }
                break;
            case 'multipart_question':
                break;
        }

        $this->redirect(route('management.courses.questions.index', [
            'classroom_id' => $this->question->classroomCourseInfo->classroom_id,
            'classroom_course_id' => $this->question->classroom_course_id,
            'term' => $this->question->term
        ]), navigate: true);
    }

    public function mount($question_id)
    {
        $this->question = Question::findOrFail($question_id);
        $this->getQuestionData();
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $this->question_form->question_types = ExamService::getQuestionTypes();

        return view('livewire.management.questions.edit');
    }
}
