<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Log;

class EmailTemplateController extends Controller
{
    // Show list of templates
    public function index()
    {
        $templates = EmailTemplate::latest()->get();
        return view('templates.index', compact('templates'));
    }

    // Show create form
    public function create()
    {
        return view('templates.create');
    }

    // Store new template
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name',
            'subject' => 'required|string|max:255|unique:email_templates,subject',
            'body' => 'required',
        ]);

        EmailTemplate::create($request->only('name', 'subject', 'body'));

        return redirect()->route('templates.index')->with('success', 'Template created successfully!');
    }


    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name,' . $id,
            'subject' => 'required|string|max:255|unique:email_templates,subject,' . $id,
            'body' => 'required|string',
        ]);

        $template = EmailTemplate::findOrFail($id);
        $template->name = $request->input('name');
        $template->subject = $request->input('subject');
        $template->body = $request->input('body');
        $template->save();

        return redirect()->route('templates.index')->with('success', 'Template updated successfully!');
    }

    // Delete template
    public function destroy($id)
    {
        EmailTemplate::findOrFail($id)->delete();

        return back()->with('success', 'Template deleted successfully!');
    }

    public function templatebulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return back()->with('toast_error', 'No templates selected!');
        }
        try {
            EmailTemplate::whereIn('id', $ids)->delete();
            return back()->with('toast_success', count($ids) . ' template' . (count($ids) > 1 ? 's' : '') . ' deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Bulk Template Delete Error: ' . $e->getMessage(), ['ids' => $ids]);
            return back()->with('toast_error', 'Failed to delete selected templates.');
        }
    }

}
