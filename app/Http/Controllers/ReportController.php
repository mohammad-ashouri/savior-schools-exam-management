<?php

namespace App\Http\Controllers;

use App\Models\Management\ClassroomCourse;
use App\Service\TextService;
use Illuminate\Http\Request;
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

        $questions = $classroom_course->questions($term)->get();

        dd($questions);
        return pdf()
            ->footerView('components.pdfs.footer')
            ->view('livewire.reports.types.exam-paper', [
                'classroom_course' => $classroom_course,
                'term' => TextService::getTermTypeTitle($term),
            ])
            ->name('invoice-2023-04-10.pdf');
    }
}
