<?php

namespace App\Livewire\ExamResults;

use App\Models\Management\AcademicYear;
use App\Models\Management\Classroom;
use App\Models\Management\ClassroomCourse;
use App\Models\UserAccessInformation;
use App\Traits\CheckPermissions;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Exam Results')]
class Index extends Component
{
    use CheckPermissions;

    public array $academic_years = [];

    public array $classrooms = [];

    public array $classroom_courses = [];

    public ?int $academic_year = null;

    public ?int $classroom_id = null;

    public ?int $classroom_course_id = null;

    /**
     * Get academic years
     * @return void
     */
    public function getAcademicYears(): void
    {
        $academic_years = AcademicYear::query();

        if (auth()->user()->hasRole(['Super Admin'])) {
            $this->academic_years = $academic_years->orderByDesc('created_at')->get()->pluck('name', 'id')->toArray();
        } elseif (auth()->user()->hasRole(['Principal', 'Admissions Officer']) and !auth()->user()->hasRole(['Super Admin'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            dd($filteredArray);
        } elseif (auth()->user()->hasRole(['Teacher'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);
            dd($filteredArray);
        } else {
            abort(403, 'Access denied.');
        }

    }

    /**
     * Get grades after updated academic year
     * @return void
     */
    public function updatedAcademicYear(): void
    {
        $this->reset(['classrooms', 'classroom_courses', 'classroom_id', 'classroom_course_id']);
        $this->getClassrooms();
    }

    /**
     * Get classrooms
     * @return void
     */
    private function getClassrooms(): void
    {
        $this->classrooms = Classroom::where('academic_year_id', $this->academic_year)->pluck('name', 'id')->toArray();
    }

    /**
     * Get grades after updated academic year
     * @return void
     */
    public function updatedClassroomId(): void
    {
        $this->reset(['classroom_courses', 'classroom_course_id']);
        $this->getClassroomCourses();
    }

    /**
     * Get classroom courses
     * @return void
     */
    private function getClassroomCourses(): void
    {
        $this->classroom_courses = ClassroomCourse::where('classroom_id', $this->classroom_id)
            ->get()
            ->sortBy(fn($item) => $item->courseInfo?->name)
            ->mapWithKeys(function (ClassroomCourse $classroom_course) {
                return [
                    $classroom_course->id => $classroom_course->courseInfo->name . " - " . $classroom_course->teacherInfo->english_fullname
                ];
            })->toArray();
        $this->dispatch('refreshTable');
    }

    /**
     * Mount the component
     * @return void
     */
    public function mount(): void
    {
        $this->getAcademicYears();
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        if (!auth()->user()->can("exam-management.results")) {
            abort(403, 'Access denied.');
        }
        return view('livewire.exam-results.index');
    }
}
