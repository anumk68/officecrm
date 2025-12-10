<?php
// app/Http/Controllers/PolicyController.php
namespace App\Http\Controllers;

use App\Http\Requests\StorePolicyRequest;
use App\Models\Policy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PolicyController extends Controller
{
    // list (public to all authenticated users)
    public function index()
    {
        $policies = Policy::latest()->paginate(25);
        return view('policies.index', compact('policies'));
    }

    // create form (HR/manager only)
    public function create()
    {
        $this->authorizeUpload();
        return view('policies.create');
    }

    // store upload
    public function store(StorePolicyRequest $request)
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $name = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('policies', $name, 'public');
                $data['file_path'] = $path;
            }

            $data['uploaded_by'] = Auth::id();

            Policy::create($data);

            return redirect()->route('policies.index')->with('success', 'Policy uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Policy store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to upload policy.');
        }
    }

    // show details page
    public function show(Policy $policy)
    {
        return view('policies.show', compact('policy'));
    }

    // delete (HR/manager only)
    public function destroy(Policy $policy)
    {
        $this->authorizeUpload();

        try {
            // delete file from disk if exists
            if ($policy->file_path && Storage::disk('public')->exists($policy->file_path)) {
                Storage::disk('public')->delete($policy->file_path);
            }

            $policy->delete();

            return redirect()->route('policies.index')->with('success', 'Policy deleted.');
        } catch (\Exception $e) {
            Log::error('Policy delete error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete policy.');
        }
    }

    // optional direct download route (if you prefer controller response)
    public function download(Policy $policy)
    {
        if (!$policy->file_path || !Storage::disk('public')->exists($policy->file_path)) {
            abort(404);
        }
        return response()->download(storage_path('app/public/' . $policy->file_path), $policy->filename());
    }

    // helper to check role
    protected function authorizeUpload()
    {
        if (! (auth()->check() && in_array(auth()->user()->role, ['hr', 'manager']))) {
            abort(403);
        }
    }
}
