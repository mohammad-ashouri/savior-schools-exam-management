<?php

namespace App\Livewire\Exams;

use App\Models\Exam\StudentExam;
use App\Models\Exam\StudentExamQuestion;
use App\Models\Management\ClassroomCourse;
use App\Models\Management\ClassroomStudent;
use App\Models\Management\ExamInfo;
use App\Models\Management\StudentApplianceStatus;
use App\Models\Management\StudentInformation;
use App\Service\DataService;
use App\Service\ExamService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Exams')]
class Index extends Component
{
    public $appliances = [];

    public $courses = [];

    #[Url]
    public $student = '';

    public ClassroomStudent $classroom_student;

    public ClassroomCourse $selected_course;

    public function getCourses(): void
    {
        $this->classroom_student = ClassroomStudent::findOrFail($this->student);
    }

    /**
     * Set selected data after click on start exam
     * @param $classroom_course_id
     * @return void
     */
    #[On('set-selected-data')]
    public function setSelectedData($classroom_course_id): void
    {
        $term = ExamService::checkStudentExistsInClassroom($this->student, $classroom_course_id);
        abort_if(!$term, 403);

        $finished_exam = ExamService::checkStudentFinishedExam($this->student, $classroom_course_id, ExamService::checkExamStatus($classroom_course_id));

        if ($finished_exam) {
            $this->dispatch('open-modal', 'finished-notif');
        } else {
            $this->selected_course = ClassroomCourse::find($classroom_course_id);
            $this->dispatch('open-modal', 'start-exam');
        }
    }

    /**
     * Make questions and start exam
     * @return void
     */
    public function startExam(): void
    {
        $term = ExamService::checkExamStatus($this->selected_course->id);
        $number_of_questions = ExamService::getNumberOfQuestions($this->selected_course->id, $term);

        $student_exam = StudentExam::where('classroom_student_id', $this->student)
            ->where('classroom_course_id', $this->selected_course->id)
            ->where('term', $term)
            ->first();

        if (empty($student_exam)) {
            $student_exam = StudentExam::create([
                'classroom_student_id' => $this->student,
                'classroom_course_id' => $this->selected_course->id,
                'term' => $term
            ]);

            $questions = $this->selected_course->questions($term)->inRandomOrder()->take($number_of_questions)->get();
            foreach ($questions as $question) {
                StudentExamQuestion::create([
                    'student_exam_id' => $student_exam->id,
                    'question_id' => $question->id,
                ]);
            }
        }

        $this->redirect(route('exam.page', ['exam_id' => $student_exam->id]), navigate: true);
    }

    /**
     * Render the component
     * @return View|Application|Factory|\Illuminate\View\View
     */
    public function render(): View|Application|Factory|\Illuminate\View\View
    {
        $this->appliances = DataService::getMyStudents();

        if (!empty($this->student)) {
            $this->getCourses();
        }

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
