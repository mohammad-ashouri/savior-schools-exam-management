<?php

use App\Livewire\Dashboard;
use App\Livewire\Management\Index as ManagementIndex;
use App\Livewire\Management\Courses as ManagementCoursesIndex;
use App\Livewire\Management\Questions\Index as ManagementQuestionsIndex;
use App\Livewire\Management\Questions\Create as ManagementQuestionsCreate;
use App\Livewire\Management\Questions\ManageQuestions\MultipartQuestionSubQuestions as ManageSubQuestionsMultipartQuestion;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::prefix('management')->group(function () {
        Route::get('/', ManagementIndex::class)->name('management.index');
        Route::get('/courses/{classroom_id}', ManagementCoursesIndex::class)->name('management.courses.index');
        Route::get('/courses/{classroom_id}/questions/{term}/{classroom_course_id}', ManagementQuestionsIndex::class)->name('management.courses.questions.index');
        Route::get('/courses/{classroom_id}/questions/{term}/{classroom_course_id}/create', ManagementQuestionsCreate::class)->name('management.courses.questions.create');
        Route::get('/subquestions/{question_id}', ManageSubQuestionsMultipartQuestion::class)->name('management.courses.questions.sub-questions');
    });
});

require __DIR__ . '/auth.php';
