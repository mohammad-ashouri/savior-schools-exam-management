<?php

namespace App\Models\Exam;

use App\Models\Management\ClassroomCourse;
use App\Models\Management\ClassroomStudent;
use App\Models\User;
use App\Service\ExamService;
use App\Service\LogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExam extends Model
{
    use SoftDeletes;

    protected $table = "student_exams";
    protected $fillable = [
        'id',
        'classroom_student_id',
        'classroom_course_id',
        'term',
        'finished_at',
        'status',
    ];

    protected $hidden = [
        'adder',
        'editor',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function booted(): void
    {
        static::created(function ($model) {
            LogService::log('student exams', [
                'job' => 'student exams created',
                'value' => $model->toArray(),
            ]);
        });
        static::updated(function ($model) {
            LogService::log('student exams', [
                'job' => 'student exams updated',
                'old value' => $model->getOriginal(),
                'new value' => $model->getDirty(),
            ]);
        });
    }

    public function classroomStudentInfo(): BelongsTo
    {
        return $this->belongsTo(ClassroomStudent::class, 'classroom_student_id');
    }

    public function classroomCourseInfo(): BelongsTo
    {
        return $this->belongsTo(ClassroomCourse::class, 'classroom_course_id');
    }

    public function questions()
    {
        return $this->hasMany(StudentExamQuestion::class, 'student_exam_id');
    }

    public function allQuestionsNumber()
    {
        $count = 0;
        foreach ($this->questions as $question) {
            switch ($question->questionInfo->question_type) {
                case('multiple_choice'):
                    $count++;
                    break;
                case('multipart_question'):
                    $count += $question->questionInfo->subQuestions->count();
                    break;
            }
        }
        return $count;
    }

    public function correctAnswers()
    {
        $count = 0;
        foreach ($this->questions as $question) {
            switch ($question->questionInfo->question_type) {
                case('multiple_choice'):
                    if (isset($question->studentAnswer->first()->option_id)) {
                        $count = ExamService::optionIsTrueInMultipleChoiceQuestion($question->studentAnswer->first()->option_id) ? $count + 1 : $count;
                    }
                    break;
                case('multipart_question'):
                    foreach ($question->questionInfo->subQuestions as $sub_question) {
                        $answered = $question->studentAnswer->where('sub_question_id', $sub_question->id)->first();
                        if (!empty($answered)) {
                            $count = $sub_question->options->where('correct', true)->first()->id == $answered->sub_question_option_id ? $count + 1 : $count;
                        }
                    }
                    break;
            }

        }
        return $count;
    }

    public function wrongAnswers()
    {
        $count = 0;
        foreach ($this->questions as $question) {
            switch ($question->questionInfo->question_type) {
                case('multiple_choice'):
                    if (isset($question->studentAnswer->first()->option_id)) {
                        $count = !ExamService::optionIsTrueInMultipleChoiceQuestion($question->studentAnswer->first()->option_id) ? $count + 1 : $count;
                    }
                    break;
                case('multipart_question'):
                    foreach ($question->questionInfo->subQuestions as $sub_question) {
                        $answered = $question->studentAnswer->where('sub_question_id', $sub_question->id)->first();
                        if (!empty($answered)) {
                            $count = $sub_question->options->where('correct', true)->first()->id != $answered->sub_question_option_id ? $count + 1 : $count;
                        }
                    }
                    break;
            }

        }
        return $count;
    }

    public function unansweredQuestions()
    {

    }
}
