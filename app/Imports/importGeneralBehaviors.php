<?php

namespace App\Imports;

use App\Models\Management\GeneralBehavior;
use App\Models\Management\StudentApplianceStatus;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class importGeneralBehaviors implements ToModel
{
    public function model(array $row)
    {
        $student_appliance_status = StudentApplianceStatus::where('student_id', $row[0])->orderByDesc('academic_year')->first();

        if (!$row[1] or $row[1] == null) {
            $row[1] = 0;
        }

        GeneralBehavior::create([
            'classroom_id' => 3,
            'appliance_id' => $student_appliance_status->id,
            'grade' => $row[1],
            'adder' => 171293,
        ]);
    }
}
