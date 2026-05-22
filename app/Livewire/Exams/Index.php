<?php

namespace App\Livewire\Exams;

use App\Models\Management\ClassroomCourse;
use App\Models\Management\ClassroomStudent;
use App\Models\Management\StudentApplianceStatus;
use App\Models\Management\StudentInformation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Exams')]
class Index extends Component
{
    public $appliances = [];

    public $courses = [];

    public $student = '';

    public ClassroomStudent $classroom_student;

    public function getStudents(): void
    {
        $my_students = StudentInformation::where('guardian', auth()->user()->id)->get()->pluck('student_id')->toArray();
        $this->appliances = StudentApplianceStatus::whereIn('student_id', $my_students)
            ->whereHas('academicYearInfo', function ($query) {
                $query->where('status', 1);
            })
            ->get()->pluck('id')->toArray();
    }

    public function getCourses(): void
    {
        $this->classroom_student = ClassroomStudent::findOrFail($this->student);
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $this->getStudents();
        return view('livewire.exams.index', [
            'students' => ClassroomStudent::whereIn('appliance_id', $this->appliances)
                ->whereHas('classroomInfo', function ($query) {
                    $query->where('status', 1);
                })
                ->get()
                ->mapWithKeys(function ($classroom_student) {
                    return [
                        $classroom_student->id =>
                            $classroom_student->applianceInfo->studentGeneralInfo->en_fullname,
                    ];
                })
                ->toArray(),
        ]);
    }
}
