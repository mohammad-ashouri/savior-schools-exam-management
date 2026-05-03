<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function Pest\Laravel\json;

class ExamController extends Controller
{
    public function questions($classroom_course_id, $term)
    {
        return response()->json([
            'questions' => [
                ['hello' => 'go'],
                ['hello' => 'go1'],
                ['hello' => 'go2'],
                ['hello' => 'go3'],
                ['hello' => 'go4'],
                ['hello' => 'go5']
            ]
        ]);
    }
}
