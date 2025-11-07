<?php

namespace App\Http\Controllers;

use App\Models\ContactPersons;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailEmailTemplateController extends Controller
{
    public function mail_contact()
    {
        try {
            $contacts = ContactPersons::get();
            $templates = EmailTemplate::get();
            return view('mail-send-template.index', compact('contacts', 'templates'));
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching contacts.');
        }
    }

    public function getTemplate($id)
    {
        try {
            $template = EmailTemplate::findOrFail($id);
            return response()->json([
                'subject' => $template->subject,
                'body' => strip_tags($template->body),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Template not found.'], 404);
        }
    }

    public function sendMail(Request $request)
    {
        $request->validate([
            'to' => 'required',
            'from' => 'required',
            'template_id' => 'required|exists:email_templates,id',
        ]);

        $emails = $request->input('to');

        if (is_string($emails)) {
            $emails = json_decode($emails, true);
        }

        if (!is_array($emails)) {
            $emails = [$emails];
        }

        // Clean and normalize emails
        $emails = array_map(function ($email) {
            if (is_string($email) && str_starts_with($email, '[')) {
                $decoded = json_decode($email, true);
                return is_array($decoded) ? $decoded ?? null : $email;
            }
            return $email;
        }, $emails);
        $emails = array_filter($emails, fn($e) => !empty($e));

        try {
            $template = EmailTemplate::find($request->template_id);
            $subject = $template->subject;
            $body = $template->body;
            $contacts = ContactPersons::get(['name', 'emails']);

            foreach ($emails as $email) {
                $contactName = 'Customer';

                foreach ($contacts as $contact) {
                    $contactEmails = is_array($contact->emails) ? $contact->emails : json_decode($contact->emails, true);
                    if (is_array($contactEmails) && in_array($email, $contactEmails)) {
                        $contactName = $contact->name;
                        break;
                    }
                }

                $personalizedBody = str_replace(['{{name}}', '{name}'], $contactName, $body);

                if (strpos($body, '{{name}}') === false && strpos($body, '{name}') === false) {
                    $personalizedBody = "<p>Dear <b>{$contactName}</b>,</p>" . $body;
                }

                // Add a nice signature/footer
                $footer = "
                <br><br>
                <hr style='border:0; border-top:1px solid #ddd;'>
                <p style='font-size:14px; color:#555;'>
                    Thanks & Regards,<br>
                    <strong>{$request->from}</strong><br>
                    <span style='font-size:13px; color:#888;'></span>
                </p>
            ";

                $finalBody = "
                <div style='font-family: Arial, sans-serif; line-height:1.6; font-size:15px; color:#333;'>
                    {$personalizedBody}
                    {$footer}
                </div>
            ";

                Mail::send([], [], function ($message) use ($request, $email, $subject, $finalBody) {
                    $message->to($email)
                        ->subject($subject)
                        ->html($finalBody);
                });
            }

            return back()->with('success', 'Emails sent successfully!');
        } catch (\Exception $e) {
            Log::error('Mail sending failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            // return back()->with('error', 'Failed to send mail. ' . $e->getMessage());
        }
    }
}
