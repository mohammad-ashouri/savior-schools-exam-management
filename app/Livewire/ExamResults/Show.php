<?php

namespace App\Livewire\ExamResults;

use App\Models\Exam\StudentExam;
use App\Service\ExamService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Exam Result")]
class Show extends Component
{
    public StudentExam $student_exam;

    public $exam_date;
    public $exam_time;

    public $exam_duration;

    /**
     * Mount the component
     * @param $student_exam_id
     * @return void
     */
    public function mount($student_exam_id): void
    {
        $this->student_exam = StudentExam::findOrFail($student_exam_id);

        $this->exam_date = ExamService::getExamDate($this->student_exam->classroomCourseInfo->id, $this->student_exam->term);
        $this->exam_time = ExamService::getExamTime($this->student_exam->classroomCourseInfo->id, $this->student_exam->term);
        $this->exam_duration = ExamService::getExamDuration($this->student_exam->classroomCourseInfo->id, $this->student_exam->term);
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
        return view('livewire.exam-results.show');
    }
}
