<?php

namespace App\Imports;

use App\Models\Management\Option;
use App\Models\Management\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportQuestions implements ToModel
{
    public $classroom_course_id;

    public function __construct($classroom_course_id){
        $this->classroom_course_id = $classroom_course_id;
    }

    public function model(array $row)
    {
        if (empty($row[0])) dd($row[1]);
        $question = Question::create([
            'classroom_course_id' => $this->classroom_course_id,
            'question_type' => 'multiple_choice',
            'title' => trim($row[0]),
            'term' => 'second',
            'adder' => 1
        ]);

        for ($i = 1; $i < 5; $i++) {
            switch ($row[5]) {
                case 'a':
                case 'A':
                case 'الف':
                case 'أ':
                    $correct = 1;
                    break;
                case 'b':
                case 'B':
                case 'ب':
                    $correct = 2;
                    break;
                case 'c':
                case 'C':
                case 'ج':
                    $correct = 3;
                    break;
                case 'd':
                case 'D':
                case 'د':
                    $correct = 4;
                    break;
                default:
                    $correct = $row[5];
            }
            Option::create([
                'question_id' => $question->id,
                'option' => $row[$i],
                'correct' => $correct == $i,
                'adder' => 1
            ]);
        }
    }
}
