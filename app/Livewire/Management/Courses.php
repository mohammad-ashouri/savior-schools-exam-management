<?php

namespace App\Livewire\Management;

use App\Models\Management\ClassroomCourse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

class Courses extends Component
{
    public ClassroomCourse $classroom_course;

    /**
     * Mount the component
     * @param int $classroom_id
     * @return void
     */
    public function mount(int $classroom_id): void
    {
        $this->classroom_course = ClassroomCourse::where('status', 1)->where('classroom_id', $classroom_id)->firstOrFail();
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
        return view('livewire.management.courses')
            ->title("Management | Classroom Course");
    }
}
