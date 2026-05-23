<?php

namespace App\Service;

use App\Models\Exam\StudentExamAnswer;
use App\Models\Management\ClassroomStudent;
use App\Models\Management\ExamInfo;
use App\Models\Management\Option;
use App\Models\Management\Question;
use App\Models\Management\SubQuestion;
use App\Models\Management\SubQuestionOption;
use Carbon\Carbon;

class ExamService
{
    /**
     * Get question types
     * @param null $selected_type
     * @param array $except
     * @return array|string
     */
    public static function getQuestionTypes($selected_type = null, $except = []): array|string
    {
        $types = [
            'multiple_choice' => 'Multiple Choice',
            'multipart_question' => 'Multipart Question',
        ];

        if (!empty($except)) {
            foreach ($except as $item) {
                unset($types[$item]);
            }
        }

        if (!is_null($selected_type)) {
            return $types[$selected_type];
        }

        return $types;
    }

    /**
     * Create question
     * @param int $classroom_course_id
     * @param string $question_type
     * @param string $title
     * @param string $term
     * @return mixed
     */
    public static function newQuestion(int $classroom_course_id, string $question_type, string $title, string $term): mixed
    {
        return Question::create([
            'classroom_course_id' => $classroom_course_id,
            'question_type' => $question_type,
            'title' => $title,
            'term' => $term,
            'adder' => auth()->user()->id
        ]);
    }

    /**
     * Create option for question
     * @param int $question_id
     * @param string $title
     * @param bool $correct_answer
     * @return mixed
     */
    public static function newOption(int $question_id, string $title, bool $correct_answer = false): mixed
    {
        return Option::create([
            'question_id' => $question_id,
            'option' => $title,
            'correct' => $correct_answer,
            'adder' => auth()->user()->id,
        ]);
    }

    /**
     * Create sub question
     * @param int $question_id
     * @param string $question_type
     * @param string $title
     * @return mixed
     */
    public static function newSubQuestion(int $question_id, string $question_type, string $title): mixed
    {
        return SubQuestion::create([
            'question_id' => $question_id,
            'question_type' => $question_type,
            'title' => $title,
            'adder' => auth()->user()->id
        ]);
    }

    /**
     * Create option for sub question
     * @param int $sub_question_id
     * @param string $title
     * @param bool $correct_answer
     * @return mixed
     */
    public static function newSubQuestionOption(int $sub_question_id, string $title, bool $correct_answer = false): mixed
    {
        return SubQuestionOption::create([
            'sub_question_id' => $sub_question_id,
            'option' => $title,
            'correct' => $correct_answer,
            'adder' => auth()->user()->id,
        ]);
    }

    /**
     * Get number of questions in exam
     * @param $classroom_course_id
     * @param $term
     * @return int|null
     */
    public static function getNoOfQuestions($classroom_course_id, $term): ?int
    {
        $exam_info = ExamInfo::where('classroom_course_id', $classroom_course_id)
            ->where('term', $term)
            ->where('type', 'number_of_questions')
            ->first();

        return !empty($exam_info) ? (int)$exam_info->value : null;
    }

    /**
     * Get exam date
     * @param $classroom_course_id
     * @param $term
     * @return string|null
     */
    public static function getExamDate($classroom_course_id, $term): ?string
    {
        $exam_info = ExamInfo::where('classroom_course_id', $classroom_course_id)
            ->where('term', $term)
            ->where('type', 'exam_date')
            ->first();

        return !empty($exam_info) ? \Carbon\Carbon::parse($exam_info->value)->format('Y-m-d') : null;
    }

    /**
     * Get exam time
     * @param $classroom_course_id
     * @param $term
     * @return string|null
     */
    public static function getExamTime($classroom_course_id, $term): ?string
    {
        $exam_info = ExamInfo::where('classroom_course_id', $classroom_course_id)
            ->where('term', $term)
            ->where('type', 'exam_time')
            ->first();

        return !empty($exam_info) ? $exam_info->value : null;
    }

    /**
     * Get exam duration
     * @param $classroom_course_id
     * @param $term
     * @return string|null
     */
    public static function getExamDuration($classroom_course_id, $term): ?string
    {
        $exam_info = ExamInfo::where('classroom_course_id', $classroom_course_id)
            ->where('term', $term)
            ->where('type', 'exam_duration')
            ->first();

        return !empty($exam_info) ? $exam_info->value : null;
    }

    /**
     * Return exam dates
     * @param $classroom_course_id
     * @return array
     */
    public static function getExamDates($classroom_course_id): array
    {
        $info = [];
        $types = ['first', 'second', 'retake'];

        foreach ($types as $type) {
            ${"$type"} = ExamInfo::where('classroom_course_id', $classroom_course_id)
                ->where('term', $type)
                ->get();

            if (${"$type"}->isNotEmpty()) {
                $info[] = ${"$type"}->where('type', 'exam_date')->first()?->value;
            }
        }
        return $info;
    }

    /**
     * Return full exam information
     * @param $classroom_course_id
     * @return array
     */
    public static function getFullExamInfo($classroom_course_id): array
    {
        $info = [];
        $types = ['first', 'second', 'retake'];

        foreach ($types as $type) {
            ${"$type"} = ExamInfo::where('classroom_course_id', $classroom_course_id)
                ->where('term', $type)
                ->get();

            if (${"$type"}->isNotEmpty()) {
                $info[$type] = [
                    'date' => ${"$type"}->where('type', 'exam_date')->first()?->value,
                    'time' => ${"$type"}->where('type', 'exam_time')->first()?->value,
                    'duration' => ${"$type"}->where('type', 'exam_duration')->first()?->value,
                ];
            }
        }
        return $info;
    }

    /**
     * Check exam status
     * @param $classroom_course_id
     * @return ?string
     */
    public static function checkExamStatus($classroom_course_id): ?string
    {
        foreach (self::getFullExamInfo($classroom_course_id) as $term => $data) {
            $start = Carbon::parse($data['date'] . ' ' . $data['time']);
            $end = (clone $start)->addMinutes((int)$data['duration']);

            $show_button = Carbon::now()->between($start, $end);

            if ($show_button) return $term;
        }
        return null;
    }

    /**
     * Check student exists in classroom or not
     * @param $classroom_student_id
     * @param $classroom_course_id
     * @return bool
     */
    public static function checkStudentExistsInClassroom($classroom_student_id, $classroom_course_id): bool
    {
        return ClassroomStudent::whereId($classroom_student_id)
            ->whereHas('classroomInfo', function ($q) use ($classroom_course_id) {
                $q->whereHas('courses', function ($q) use ($classroom_course_id) {
                    $q->whereId($classroom_course_id);
                });
            })->exists();
    }

    /**
     * Get number of questions
     * Default => 60
     * @param $classroom_course_id
     * @param $term
     * @return int|null
     */
    public static function getNumberOfQuestions($classroom_course_id, $term): ?int
    {
        $number_of_questions = ExamInfo::where('classroom_course_id', $classroom_course_id)
            ->where('term', $term)
            ->where('type', 'number_of_questions')
            ->first();

        return !empty($number_of_questions) ? (int)$number_of_questions->value : 60;
    }

    /**
     * Check selected answer in multiple answer questions
     * @param $student_exam_question_id
     * @return mixed
     */
    public static function checkSelectedAnswerMultipleAnswer($student_exam_question_id): mixed
    {
        return StudentExamAnswer::where('student_exam_question_id', $student_exam_question_id)->first()?->option_id;
    }
}