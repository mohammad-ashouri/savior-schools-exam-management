<?php

namespace App\Service;

use App\Models\Management\ExamInfo;
use App\Models\Management\Option;
use App\Models\Management\Question;
use App\Models\Management\SubQuestion;
use App\Models\Management\SubQuestionOption;

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
}