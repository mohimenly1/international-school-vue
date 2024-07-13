<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\AbsenceCalculator;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    protected $absenceCalculator;

    public function __construct(AbsenceCalculator $absenceCalculator)
    {
        $this->absenceCalculator = $absenceCalculator;
    }

    public function calculateAbsences(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $this->absenceCalculator->calculateMonthlyAbsencesAndSalary($employee);

        return response()->json(['message' => 'Absences calculated and salary adjusted successfully.']);
    }
}
