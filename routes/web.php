<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ReportController;
use App\Livewire\Dashboard;
use App\Livewire\Exams\ExamPage;
use App\Livewire\Management\Index as ManagementIndex;
use App\Livewire\Management\Courses as ManagementCoursesIndex;
use App\Livewire\Management\Questions\Edit as EditQuestion;
use App\Livewire\Management\Questions\Index as ManagementQuestionsIndex;
use App\Livewire\Management\Questions\ManageQuestions\MultipartQuestionSubQuestions as ManageSubQuestionsMultipartQuestion;
use App\Livewire\Management\Questions\ManageQuestions\MultipartQuestionSubQuestionsEdit as EditMultipartQuestion;
use App\Livewire\Exams\Index as ExamIndex;
use App\Livewire\ExamResults\Index as ExamResultsIndex;
use App\Livewire\ExamResults\Show as ExamResultsShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('management')->group(function () {
        Route::get('/', ManagementIndex::class)->name('management.index');
        Route::get('/courses/{classroom_id}', ManagementCoursesIndex::class)->name('management.courses.index');
        Route::get('/courses/{classroom_id}/questions/{term}/{classroom_course_id}', ManagementQuestionsIndex::class)->name('management.courses.questions.index');
        Route::get('/subquestions/{question_id}', ManageSubQuestionsMultipartQuestion::class)->name('management.courses.questions.sub-questions');
        Route::get('/edit/{question_id}', EditQuestion::class)->name('management.courses.questions.edit');
        Route::get('/edit-multipart-question/{question_id}', EditMultipartQuestion::class)->name('management.courses.questions.edit-multipart-question.edit');
    });

    Route::prefix('exams')->group(function () {
        Route::get('/', ExamIndex::class)->name('exam.index');
        Route::get('/{exam_id}',ExamPage::class)->name('exam.page');
    });

    Route::prefix('exam-result')->group(function () {
        Route::get('/', ExamResultsIndex::class)->name('exam-result.index');
        Route::get('/{student_exam_id}',ExamResultsShow::class)->name('exam-result.show');
    });

    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'test'])->name('report');
        Route::get('exam-paper/{classroom_course_id}/{term}', [ReportController::class, 'getExamPaper'])->name('report.exam-paper');
    });
});

Route::get('excel', [ExcelController::class, 'index'])->name('excel');
Route::post('/importQuestions', [ExcelController::class, 'importQuestions'])->name('excel.importQuestions');
Route::post('/importSubQuestions', [ExcelController::class, 'importSubQuestions'])->name('excel.importSubQuestions');
Route::post('/importGeneralBehaviors', [ExcelController::class, 'importGeneralBehaviors'])->name('excel.importGeneralBehaviors');


Route::get('temp',function (){
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    dd(auth()->user()->permissions);
});
require __DIR__ . '/auth.php';
