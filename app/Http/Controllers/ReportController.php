<?php

namespace App\Http\Controllers;

use App\Models\Management\ClassroomCourse;
use App\Service\ExamService;
use App\Service\TextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use function Spatie\LaravelPdf\Support\pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('layouts.stimulsoft-layout');
    }

    public function test()
    {
        return view('livewire.reports.types.exam-paper');
    }

    public function getExamPaper($classroom_course_id, $term)
    {
        abort_unless(in_array($term, ['first', 'second', 'retake']), 403);

        $classroom_course = ClassroomCourse::where('status', 1)->whereId($classroom_course_id)->firstOrFail();

        $questions = $classroom_course->questions($term)
            ->inRandomOrder()
            ->take(ExamService::getNoOfQuestions($classroom_course->id, $term) ?? 60)
            ->get()
            ->map(function ($question) {
                $data = [
                    'id' => $question->id,
                    'question_type' => $question->question_type,
                    'title' => $question->title,
                ];

                switch ($question->question_type) {
                    case 'multiple_choice':
                        $data['options'] = $question->options()->pluck('option', 'id')->toArray();
                        break;
                    case 'multipart_question':
                        $data['sub_questions'] = $question->subQuestions()
                            ->inRandomOrder()
                            ->get()
                            ->map(function ($query) {
                                switch ($query->question_type) {
                                    case 'multiple_choice':
                                        return [
                                            'id' => $query->id,
                                            'question' => $query->title,
                                            'options' => $query->options->pluck('option', 'id')->toArray()
                                        ];
                                    default:
                                        return [];
                                }
                            })->toArray();
                        break;
                }
                return $data;
            })
            ->toArray();

        if (empty($questions)) abort(404, 'No questions found.');

        $time = now();
        $file_name = "exam-paper-$classroom_course->id-$time";

        return pdf()
            ->footerView('components.pdfs.footer')
            ->view('livewire.reports.types.exam-paper', [
                'classroom_course' => $classroom_course,
                'term_value' => $term,
                'term' => TextService::getTermTypeTitle($term),
                'questions' => $questions,
            ])
            ->name($file_name);
    }
}
