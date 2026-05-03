<?php

namespace App\Livewire\Reports\Types;

use App\Models\Management\ClassroomCourse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use function Spatie\LaravelPdf\Support\pdf;

class ExamPaper extends Component
{
    public $classroom_course;

    public string $term;

    public ClassroomCourse $classroom_course_model;

    public string $term_title;

    public function mount($classroom_course_id,$term)
    {
    }
}
