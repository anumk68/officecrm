<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;

class LeadApprovalController extends Controller
{
    use NotifiesUsers;
    public function approve(Lead $lead, Request $request)
    {
        $request->validate([
            'client_requirement' => 'required|string',
            'budget_confirmation' => 'required|string',
            'authenticity' => 'required|string',
            'document' => 'nullable|file|max:5000',
        ]);

        $approval = $lead->approvable;

        $filePath = null;
        if ($request->hasFile('document')) {
            $filePath = $request->file('document')->store('lead_documents', 'public');
        }

        $approval->update([
            'approved_by' => auth()->id(),
            'status'               => 'approved',
            'client_requirement'   => $request->client_requirement,
            'budget_confirmation'  => $request->budget_confirmation,
            'authenticity'         => $request->authenticity,
            'document'             => $filePath,
            'approved_at'          => now(),
        ]);
        $this->notifyDevelopmentTeamOfNewProject($lead, auth()->user());
        return back()->with('success', 'Lead approved for project creation.');
    }

    public function reject(Lead $lead)
    {
        request()->validate(['notes' => 'required']);
        $approval = $lead->approvable;
        $approval->update([
            'status' => 'rejected',
            'notes' => request('notes'),
            'approved_by' => auth()->id()
        ]);

        // revert lead state
        // $lead->update([
        //     'status' => 'not_converted',
        //     'color' => 'white'
        // ]);

        return back()->with('success', 'Lead rejected.');
    }
}
