<?php

namespace App\Livewire\Forms\Management;

use Livewire\Attributes\Validate;
use Livewire\Form;

class QuestionForm extends Form
{
    public array $question_types;

    #[Validate('required|in:multiple_choice,multipart_question')]
    public $question_type = null;

    #[Validate('required|string')]
    public ?string $title;

    #[Validate('nullable|image')]
    public $image;

    public string $option1 = '';
    public string $option2 = '';
    public string $option3 = '';
    public string $option4 = '';

    public ?int $correct_answer = null;
}
