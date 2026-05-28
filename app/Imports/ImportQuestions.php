<?php

namespace App\Imports;

use App\Models\Management\Option;
use App\Models\Management\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportQuestions implements ToModel
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
    }

    public function model(array $row)
    {
        $question = Question::create([
            'classroom_course_id' => 180,
            'question_type' => 'multiple_choice',
            'title' => $row[0],
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
