<?php

namespace App\Http\Controllers;

use App\Imports\importGeneralBehaviors;
use App\Imports\ImportQuestions;
use App\Imports\ImportSubQuestions;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('Super Admin')) {
            return view('temporary.excelimporter');
        }
    }

    public function ImportQuestions(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new ImportQuestions(), $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function ImportSubQuestions(Request $request)
    {
        $file = $request->file('excel_file');
        $q_id = $request->q_id;

        // Validate the uploaded file as needed

        Excel::import(new ImportSubQuestions($q_id), $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }

    public function importGeneralBehaviors(Request $request)
    {
        $file = $request->file('excel_file');

        // Validate the uploaded file as needed

        Excel::import(new importGeneralBehaviors(), $file);

        return redirect()->back()->with('success', 'داده‌ها با موفقیت وارد شدند.');
    }
}
