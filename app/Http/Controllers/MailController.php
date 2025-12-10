<?php
namespace App\Http\Controllers;

use App\Models\Mail as ModelsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MailController extends Controller
{
    public function inbox()
    {
        try {
            $mails = ModelsMail::latest()->get();
            return view('lead_old.mail.index', compact('mails'));
        } catch (\Exception $e) {
            Log::error("Inbox load failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to load inbox.');
        }
    }

    public function drafts()
    {
        try {
            $drafts = ModelsMail::where('is_draft', true)->latest()->get();
            return view('lead_old.mail.drafts', compact('drafts'));
        } catch (\Exception $e) {
            Log::error("Drafts load failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to load drafts.');
        }
    }

    public function sent()
    {
        try {
            $mails = ModelsMail::where('is_draft', false)->latest()->get();
            return view('lead_old.mail.index', compact('mails'));
        } catch (\Exception $e) {
            Log::error("Sent mails load failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to load sent mails.');
        }
    }

    public function trash()
    {
        try {
            $trashMails = ModelsMail::onlyTrashed()->latest()->get();
            return view('lead_old.mail.trash', compact('trashMails'));
        } catch (\Exception $e) {
            Log::error("Trash load failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to load trash.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
            'cc' => 'nullable|string',
            'bcc' => 'nullable|string',
            'subject' => 'required|string',
            'body' => 'nullable|string',
            'attachments' => 'nullable',
        ]);

        try {
            $mail = new ModelsMail();
            $mail->to = $request->to;
            $mail->cc = $request->cc;
            $mail->bcc = $request->bcc;
            $mail->subject = $request->subject;
            $mail->message = $request->body;
            $mail->is_draft = $request->action === 'draft';

            $attachments = [];

            if ($request->hasFile('attachments')) {
                $paths = [];
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments');
                    $paths[] = $path;
                    $attachments[] = $path;
                }
                $mail->attachments = json_encode($paths);
            }

            if ($request->action === 'send') {
                Mail::send([], [], function ($message) use ($mail, $attachments) {
                    $message->to(array_map('trim', explode(',', $mail->to)))
                        ->subject($mail->subject ?? 'No Subject')
                        ->html($mail->message);

                    if (!empty($mail->cc)) {
                        $message->cc(array_map('trim', explode(',', $mail->cc)));
                    }

                    if (!empty($mail->bcc)) {
                        $message->bcc(array_map('trim', explode(',', $mail->bcc)));
                    }

                    foreach ($attachments as $path) {
                        $fullPath = storage_path('app/' . $path);
                        if (file_exists($fullPath)) {
                            $message->attach($fullPath);
                        } else {
                            Log::warning('Attachment not found: ' . $fullPath);
                        }
                    }
                });
                $mail->save();

                return redirect()->route('mails.inbox')->with('success', '✅ Email sent successfully!');
            }

            $mail->save();
            return redirect()->route('mails.drafts')->with('success', '💾 Draft saved successfully!');
        } catch (\Exception $e) {
            Log::error("Mail store failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to save/send mail.');
        }
    }

    public function show($id)
    {
        try {
            $mail = ModelsMail::findOrFail($id);
            $attachments = $mail->attachments ? json_decode($mail->attachments, true) : [];
            return view('lead_old.mail.view', compact('mail', 'attachments'));
        } catch (\Exception $e) {
            Log::error("Mail view failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to load mail.');
        }
    }

    public function destroy($id)
    {
        try {
            $mail = ModelsMail::findOrFail($id);
            $mail->delete();
            return back()->with('success', 'Mail moved to Trash successfully');
        } catch (\Exception $e) {
            Log::error("Mail delete failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to delete mail.');
        }
    }

    public function restore($id)
    {
        try {
            $mail = ModelsMail::withTrashed()->findOrFail($id);
            $mail->restore();
            return redirect()->route('mails.trash')->with('success', 'Mail restored successfully');
        } catch (\Exception $e) {
            Log::error("Mail restore failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to restore mail.');
        }
    }

    public function forceDelete($id)
    {
        try {
            $mail = ModelsMail::withTrashed()->findOrFail($id);

            if ($mail->attachments) {
                $attachments = json_decode($mail->attachments, true);
                foreach ($attachments as $path) {
                    if (Storage::exists($path)) {
                        Storage::delete($path);
                    }
                }
            }

            $mail->forceDelete();
            return redirect()->route('mails.trash')->with('success', 'Mail and its attachments deleted permanently');
        } catch (\Exception $e) {
            Log::error("Mail force delete failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to delete permanently.');
        }
    }

    public function sendDraft($id)
    {
        try {
            $mail = ModelsMail::findOrFail($id);

            if ($mail->is_draft) {
                $attachments = json_decode($mail->attachments, true) ?? [];

                Mail::send([], [], function ($message) use ($mail, $attachments) {
                    $message->to(explode(',', $mail->to))
                        ->subject($mail->subject ?? 'No Subject')
                        ->html($mail->message);

                    if ($mail->cc) {
                        $message->cc(explode(',', $mail->cc));
                    }
                    if ($mail->bcc) {
                        $message->bcc(explode(',', $mail->bcc));
                    }

                    foreach ($attachments as $path) {
                        $fullPath = storage_path('app/' . $path);
                        if (file_exists($fullPath)) {
                            $message->attach($fullPath);
                        }
                    }
                });

                $mail->is_draft = false;
                $mail->save();

                return redirect()->route('mails.drafts')->with('success', '✅ Draft mail sent successfully!');
            }

            return redirect()->route('mails.drafts')->with('info', 'ℹ️ This email was already sent.');
        } catch (\Exception $e) {
            Log::error("Send draft failed: " . $e->getMessage());
            return back()->with('error', '❌ Failed to send draft.');
        }
    }

    public function trashforceBulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        try {
            if (!empty($ids)) {
                $mails = ModelsMail::withTrashed()->whereIn('id', $ids)->get();

                foreach ($mails as $mail) {
                    if ($mail->attachments) {
                        $attachments = json_decode($mail->attachments, true);
                        foreach ($attachments as $path) {
                            if (Storage::exists($path)) {
                                Storage::delete($path);
                            }
                        }
                    }
                    $mail->forceDelete();
                }

                return response()->json(['message' => 'Selected mails permanently deleted.']);
            }
            return response()->json(['message' => 'No mails selected.'], 400);
        } catch (\Exception $e) {
            Log::error("Bulk delete failed: " . $e->getMessage());
            return response()->json(['message' => '❌ Failed to delete mails.'], 500);
        }
    }
    public function mailbulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids) || !is_array($ids)) {
            return back()->with('toast_error', 'No mail selected!');
        }
        try {
            Mail::whereIn('id', $ids)->delete();
            return back()->with('toast_success', count($ids) . ' activit' . (count($ids) > 1 ? 'ies' : 'y') . ' deleted!');
        } catch (\Exception $e) {
            Log::error('Bulk Mail Delete Error: ' . $e->getMessage(), ['ids' => $ids]);
            return back()->with('toast_error', 'Failed to delete mail.');
        }
    }
}
