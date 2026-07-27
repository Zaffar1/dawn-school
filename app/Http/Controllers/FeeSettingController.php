<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\FeeSetting;
use Illuminate\Http\Request;

class FeeSettingController extends Controller
{
    public function index()
    {
        // Get all active classes and their fee settings
        $classes = SchoolClass::where('status', 'active')->with(['feeSetting'])->get();
        return view('fees.settings', compact('classes'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'fees' => 'required|array',
            'fees.*.class_id' => 'required|exists:classes,id',
            'fees.*.admission_fee' => 'required|numeric|min:0',
            'fees.*.monthly_fee' => 'required|numeric|min:0',
            'fees.*.exam_fee' => 'required|numeric|min:0',
        ]);

        foreach ($validated['fees'] as $feeData) {
            FeeSetting::updateOrCreate(
                ['class_id' => $feeData['class_id']],
                [
                    'admission_fee' => $feeData['admission_fee'],
                    'monthly_fee' => $feeData['monthly_fee'],
                    'exam_fee' => $feeData['exam_fee'],
                ]
            );
        }

        return redirect()->route('fee-settings.index')->with('success', 'Fee settings updated successfully.');
    }
}
