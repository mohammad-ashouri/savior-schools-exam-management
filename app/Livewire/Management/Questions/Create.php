<?php

namespace App\Livewire\Management\Questions;

use App\Models\Management\ClassroomCourse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

class Create extends Component
{
    public ClassroomCourse $classroom_course;

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
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        return view('livewire.management.questions.create')
            ->title("Management | Classroom Course | Questions | Create");
    }
}
