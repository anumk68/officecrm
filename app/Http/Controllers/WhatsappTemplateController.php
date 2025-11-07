<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappTemplateController extends Controller
{
    // Show list of templates
    // public function index()
    // {
    //     $templates = WhatsappTemplate::latest()->get();
    //     return view('whatapptemplates.index', compact('templates'));
    // }


    public function index()
    {
        try {
            $apiKey = env('INTERAKT_API_KEY');
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->get('https://api.interakt.ai/v1/public/track/organization/templates', [
                        'offset' => 0,
                        'autosubmitted_for' => 'all',
                        'approval_status' => 'APPROVED',
                        'variable_present' => 'No',
                        'language' => 'all',
                    ]);
            if ($response->failed()) {
                return back()->with('error', 'Failed to fetch templates from Interakt.');
            }
            $data = $response->json();
            $templates = collect($data['results']['templates'] ?? [])
                ->where('approval_status', 'APPROVED')
                ->map(function ($template) {
                    return [
                        'id' => $template['id'] ?? '',
                        'name' => $template['name'] ?? '',
                        'display_name' => $template['display_name'] ?? '',
                        'language' => $template['language'] ?? '',
                        'category' => $template['category'] ?? '',
                        'body' => strip_tags($template['body'] ?? ''),
                        'approval_status' => $template['approval_status'] ?? '',
                        'created_by' => $template['created_by_name'] ?? 'Interakt Admin',
                        'created_at' => $template['created_at_utc'] ?? '',
                    ];
                })
                ->values();
            return view('whatapptemplates.index', compact('templates'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }




    // Show create form
    public function create()
    {
        return view('whatapptemplates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:whatsapp_templates,name',
            'body' => 'required',
        ]);

        WhatsappTemplate::create($request->only('name', 'body'));

        return redirect()->route('whatapptemplates.index')->with('success', 'Whatsapp Template created successfully!');
    }

    public function edit($id)
    {
        $template = WhatsappTemplate::findOrFail($id);
        return view('whatapptemplates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:whatsapp_templates,name,' . $id,
            'body' => 'required|string',
        ]);

        $template = WhatsappTemplate::findOrFail($id);
        $template->name = $request->input('name');

        $template->body = $request->input('body');
        $template->save();

        return redirect()->route('whatapptemplates.index')->with('success', 'WhatsApp Template updated successfully!');
    }

    // Delete template
    public function destroy($id)
    {
        WhatsappTemplate::findOrFail($id)->delete();

        return back()->with('success', 'Whats app Template deleted successfully!');
    }
}
