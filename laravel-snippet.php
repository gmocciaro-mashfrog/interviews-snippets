public function approveLeaves(Request $request)
{
    $leaves = Leave::where('status', 'pending')->get();

    foreach ($leaves as $leave) {

        if ($leave->employee->department->manager_id !== auth()->id()) {
            continue;
        }

        $leave->status = 'approved';
        $leave->approved_by = auth()->id();
        $leave->save();

        Mail::to($leave->employee->email)
            ->send(new LeaveApprovedMail($leave));
    }

    return response()->json([
        'message' => 'Leaves approved',
        'leaves' => $leaves
    ]);
}
