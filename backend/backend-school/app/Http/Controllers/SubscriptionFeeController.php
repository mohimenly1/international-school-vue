<?php

// app/Http/Controllers/SubscriptionFeeController.php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SubscriptionFee;
use Illuminate\Http\Request;

class SubscriptionFeeController extends Controller
{
    public function index()
    {
        $fees = SubscriptionFee::all();
        return response()->json($fees);
    }

    public function storeSubscriptionFees(Request $request, Student $student)
    {
        // Validate subscription fees
        $validatedData = $request->validate([
            'subscriptionFees' => 'required|array',
            'subscriptionFees.*.subscription_fee_id' => 'required|exists:subscription_fees,id',
            'subscriptionFees.*.amount' => 'required|numeric'
        ]);

        // Attach subscription fees to the student
        $student->subscriptionFees()->sync($validatedData['subscriptionFees']);

        return response()->json(['message' => 'Subscription fees saved successfully']);
    }
}
