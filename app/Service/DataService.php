<?php

namespace App\Service;

use App\Models\Management\StudentApplianceStatus;
use App\Models\Management\StudentInformation;

class DataService
{
    /**
     * Return parent's students
     * @return array
     */
    public static function getMyStudents(): array
    {
        $my_students = StudentInformation::where('guardian', auth()->user()->id)->get()->pluck('student_id')->toArray();
        return StudentApplianceStatus::whereIn('student_id', $my_students)
            ->whereHas('academicYearInfo', function ($query) {
                $query->where('status', 1);
            })
            ->get()->pluck('id')->toArray();
    }

    /**
     * Check selected student is for this parent or not
     * @param $student_id
     * @return bool
     */
    public static function checkIsMyStudentOrNot($student_id): bool
    {
        $my_students=self::getMyStudents();
        if (in_array($student_id, $my_students)) {
            return true;
        }
        return false;
    }
}