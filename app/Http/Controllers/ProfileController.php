<?php
namespace App\Http\Controllers;

use App\Models\UpdateProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
    /**
     * Show profile page
     */
    public function index()
    {
        try {
            $userId = Auth::user()->id;
            $admin = User::where("id", $userId)->first();
            $pendingRequest = UpdateProfile::where('user_id', $userId)
                ->where('status', 'pending')
                ->first();
            return view("profile.profile", compact("admin", "pendingRequest"));
        } catch (\Exception $e) {
            Log::error("Profile load failed: " . $e->getMessage());
            return back()->with("error", "Failed to load profile page.");
        }
    }
    public function updateold(Request $request)
    {
        $request->validate([
            'full_name' => 'nullable',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        try {
            $admin = Auth::user();
            $profilePicPath = $admin->profile_pic;
            if ($request->hasFile('profile_pic')) {
                if ($profilePicPath && File::exists(public_path('storage/' . $profilePicPath))) {
                    File::delete(public_path('storage/' . $profilePicPath));
                }
                $image = $request->file('profile_pic');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/'), $imageName);
                $profilePicPath = $imageName;
            }
            $admin->full_name = $request->full_name ?? $admin->full_name;
            $admin->profile_pic = $profilePicPath;
            $admin->save();
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error("Profile update failed: " . $e->getMessage());
            return back()->with('error', 'Failed to update profile.' . $e->getMessage());
        }
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        try {
            $admin = Auth::user();
            if (!Hash::check($request->current_password, $admin->password)) {
                return response()->json([
                    'errors' => ['current_password' => ['Current password is incorrect']]
                ], 422);
            }
            $admin->password = Hash::make($request->new_password);
            $admin->save();
            return response()->json(['message' => 'Password changed successfully.']);
        } catch (\Exception $e) {
            Log::error("Password change failed: " . $e->getMessage());
            return response()->json(['error' => 'Failed to change password.'], 500);
        }
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $existingRequest = UpdateProfile::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        if ($existingRequest) {
            return back()->with('error', 'You already have a pending update request awaiting HR approval.');
        }
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'joining_date' => 'nullable|date',
            'profile_pic' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        $profilePicPath = $request->profile_pic;
        if ($request->hasFile('profile_pic')) {
            if ($profilePicPath && File::exists(public_path('storage/' . $profilePicPath))) {
                File::delete(public_path('storage/' . $profilePicPath));
            }
            $image = $request->file('profile_pic');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/'), $imageName);
            $profilePicPath = $imageName;
        }
        $updateRequest = UpdateProfile::create([
            'user_id' => $user->id,
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'whatsapp_number' => $request->input('whatsapp_number'),
            'position' => $request->input('position'),
            'dob' => $request->input('dob'),
            'joining_date' => $request->input('joining_date'),
            'status' => 'pending',
            'profile_pic' => $profilePicPath,
        ]);
        $hrEmail = 'rahulsharma.digirush@gmail.com';
        $approveUrl = route('profile.approve', $updateRequest->id);
        $rejectUrl = route('profile.reject', $updateRequest->id);
        Mail::send('emails.profile_update_request', [
            'user' => $user,
            'data' => [
                'user_id' => $user->id,
                'full_name' => $request->input('full_name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                'whatsapp_number' => $request->input('whatsapp_number'),
                'position' => $request->input('position'),
                'dob' => $request->input('dob'),
                'joining_date' => $request->input('joining_date'),
                'status' => 'pending',
                'profile_pic' => $profilePicPath,
            ],
            'approveUrl' => $approveUrl,
            'rejectUrl' => $rejectUrl,
        ], function ($message) use ($hrEmail) {
            $message->to($hrEmail)->subject('Profile Update Request');
        });
        return back()->with('success', 'Your update request has been sent to HR for approval.');
    }
    public function approve($id)
    {
        $request = UpdateProfile::find($id);
        if (!$request) {
            return response('
        <div style="text-align:center;margin-top:100px;">
            <h2 style="color:red;">⚠️ This request has already been processed.</h2>
        </div>');
        }
        $user = $request->user;
        if (!$user) {
            return response('<h3 style="color:red;">User not found.</h3>', 404);
        }
        $user->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'whatsapp_number' => $request->whatsapp_number,
            'position' => $request->position,
            'dob' => $request->dob,
            'joining_date' => $request->joining_date,
            'profile_pic' => $request->profile_pic,
        ]);
        $request->delete();
        return response('
    <div style="font-family:Arial,sans-serif; background:#f9f9f9; padding:20px; text-align:center;">
        <div style="background:#fff; padding:30px; border-radius:8px; display:inline-block;">
            <h2 style="color:green;">✅ Profile Approved Successfully</h2>
            <p>This profile update has been applied successfully.</p>
            <button disabled style="background:gray;color:white;padding:10px 20px;border:none;border-radius:5px;">Approved</button>
        </div>
    </div>');
    }
    public function reject($id)
    {
        $request = UpdateProfile::find($id);
        if (!$request) {
            return response('
        <div style="text-align:center;margin-top:100px;">
            <h2 style="color:red;">⚠️ This request has already been processed.</h2>
        </div>');
        }
        if ($request->profile_pic && file_exists(public_path('storage/' . $request->profile_pic))) {
            unlink(public_path('storage/' . $request->profile_pic));
        }
        $request->delete();
        return response('
    <div style="font-family:Arial,sans-serif; background:#f9f9f9; padding:20px; text-align:center;">
        <div style="background:#fff; padding:30px; border-radius:8px; display:inline-block;">
            <h2 style="color:red;">❌ Profile Rejected</h2>
            <p>This profile update request has been rejected successfully.</p>
            <button disabled style="background:gray;color:white;padding:10px 20px;border:none;border-radius:5px;">Rejected</button>
        </div>
    </div>');
    }

}
