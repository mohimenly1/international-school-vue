<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use App\Services\AbsenceCalculator;

class SalaryController extends Controller
{
    protected $absenceCalculator;

    public function __construct(AbsenceCalculator $absenceCalculator)
    {
        $this->absenceCalculator = $absenceCalculator;
    }

    public function adjustSalary(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $salary = Salary::where('employee_id', $employee->id)->first();

        if (!$salary) {
            return response()->json(['message' => 'Salary record not found.'], 404);
        }

        $deduction = $request->input('deduction', 0);
        $salary->total_salary -= $deduction;
        $salary->save();

        return response()->json(['message' => 'Salary adjusted successfully.']);
    }

    public function setSalary(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);

        $salary = Salary::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'base_salary' => $request->input('base_salary', 0),
                'hourly_rate' => $request->input('hourly_rate', null),
                'total_salary' => $request->input('base_salary', 0)
            ]
        );

        return response()->json(['message' => 'Salary set successfully.']);
    }

    public function calculateAbsences(Request $request, $employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $this->absenceCalculator->calculateMonthlyAbsencesAndSalary($employee);

        return response()->json(['message' => 'Absences calculated and salary adjusted successfully.']);
    }

    public function show($employeeId)
    {
        $salary = Salary::where('employee_id', $employeeId)->firstOrFail();
        return response()->json($salary);
    }
}
