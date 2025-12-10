<?php

namespace App\Http\Controllers;

use App\Models\ContactPersons;
use App\Models\Lead;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WhatsappsentTemplateController extends Controller
{
    // public function whatsapp_contact()
    // {
    //     try {
    //         $contacts = ContactPersons::get();
    //         $templates = WhatsappTemplate::get();
    //         return view('whatsapp-send-template.index', compact('contacts', 'templates'));
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching contacts: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Something went wrong while fetching contacts.');
    //     }
    // }

    public function whatsapp_contact()
    {
        try {
            $contacts = Lead::where('status', 'converted')
                ->whereHas('approvable', function ($q) {
                    $q->where('status', 'approved');
                })
                ->get();
            $templates = $this->fetchApprovedInteraktTemplates();
            return view('whatsapp-send-template.index', compact('contacts', 'templates'));
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching contacts.' . $e->getMessage());
        }
    }
    private function fetchApprovedInteraktTemplates()
    {
        $apiKey = env('INTERAKT_API_KEY');
        if (empty($apiKey)) {
            Log::error('CRITICAL: INTERAKT_API_KEY is not set in the .env file.');
            return collect();
        }
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $apiKey,
            ])->get('https://api.interakt.ai/v1/public/track/organization/templates', [
                        'approval_status' => 'APPROVED',
                        'limit' => 100,
                    ]);
            if ($response->failed()) {
                Log::error('Failed to fetch templates from Interakt.', ['status' => $response->status(), 'body' => $response->body()]);
                return collect();
            }
            $data = $response->json();
            $templates = collect($data['results']['templates'] ?? [])
                ->map(function ($template) {
                    $header_handle = $template['header_handle_file_url'] ?? null;
                    $button_data = [];
                    if (isset($template['components'])) {
                        foreach ($template['components'] as $component) {
                            if ($component['type'] === 'BUTTONS' && isset($component['buttons'])) {
                                foreach ($component['buttons'] as $button) {
                                    if ($button['type'] === 'URL') {
                                        $button_data[] = $button['url'];
                                    }
                                }
                            }
                        }
                    }
                    return [
                        'id' => $template['id'] ?? '',
                        'name' => $template['name'] ?? '',
                        'display_name' => $template['display_name'] ?? '',
                        'body' => strip_tags($template['body'] ?? ''),
                        'header_handle' => $header_handle,
                        'button_urls' => $button_data,
                    ];
                })
                ->values();
            return $templates;
        } catch (\Exception $e) {
            Log::error('Exception while fetching Interakt templates: ' . $e->getMessage());
            return collect();
        }
    }

    private function sendTemplateMessage($number, $templateName, $bodyParameters = [], $headerHandle = null, $buttonValues = [])
    {
        $apiUrl = 'https://api.interakt.ai/v1/public/message/';
        $token = env('INTERAKT_API_KEY');
        $cleanNumber = preg_replace('/\D/', '', $number);
        $payload = [
            "countryCode" => "+91",
            "phoneNumber" => $cleanNumber,
            "callbackData" => "crm_template_send",
            "type" => "Template",
            "template" => [
                "name" => $templateName,
                "languageCode" => "en",
                "bodyValues" => $bodyParameters,
            ],
        ];
        if ($headerHandle) {
            $payload['template']['headerValues'] = [$headerHandle];
        }
        if (!empty($buttonValues)) {
            $payload['buttonValues'] = $buttonValues;
        }
        $response = Http::withHeaders([
            'Authorization' => "Basic {$token}",
            'Content-Type' => 'application/json',
        ])->post($apiUrl, $payload);
        $responseData = $response->json();
        if ($response->failed() || empty($responseData['result'])) {
            Log::error('❌ Failed to send Interakt template message', [
                'status_code' => $response->status(),
                'sent_payload' => $payload,
                'interakt_response' => $responseData,
            ]);
            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Unknown error from Interakt'
            ];
        }
        Log::info('✅ Interakt message sent successfully', ['response' => $responseData]);
        return [
            'success' => true,
            'message' => 'Message sent successfully'
        ];
    }
    public function sendMessage(Request $request)
    {
        $request->validate([
            'template_name' => 'required|string',
            'numbers' => 'required|string',
            'header_handle' => 'nullable|string',
            'button_urls' => 'nullable|string',
        ]);
        $numbers = json_decode($request->input('numbers', '[]'), true);
        if (empty($numbers)) {
            return back()->with('error', 'No contacts were selected.');
        }
        $templateName = $request->input('template_name');
        $headerHandle = $request->input('header_handle');
        $buttonUrlPatterns = json_decode($request->input('button_urls', '[]'), true);
        $successCount = 0;
        $failureCount = 0;
        $errorMessages = [];
        try {
            $contactsData = ContactPersons::get(['name', 'contact_numbers']);
            foreach ($numbers as $number) {
                $contactName = 'Customer';
                foreach ($contactsData as $contact) {
                    $contactNumbers = is_array($contact->contact_numbers) ? $contact->contact_numbers : json_decode($contact->contact_numbers, true);
                    if (is_array($contactNumbers) && in_array($number, array_column($contactNumbers, 'number'))) {
                        $contactName = $contact->name;
                        break;
                    }
                }
                $finalButtonValues = [];
                if (!empty($buttonUrlPatterns)) {
                    $cleanNumber = preg_replace('/\D/', '', $number);
                    foreach ($buttonUrlPatterns as $pattern) {
                        $finalUrl = str_replace('{{1}}', $cleanNumber, $pattern);
                        $finalButtonValues[] = [$finalUrl];
                    }
                }
                $bodyParameters = [$contactName];
                $response = $this->sendTemplateMessage($number, $templateName, $bodyParameters, $headerHandle, $finalButtonValues);
                if ($response['success']) {
                    $successCount++;
                } else {
                    $failureCount++;
                    $errorMessages[] = "{$number}: " . ($response['message'] ?? 'Unknown Error');
                }
            }
            if ($failureCount > 0) {
                $errorMessage = "{$successCount} messages sent. {$failureCount} messages failed. Errors: " . implode('; ', $errorMessages);
                return back()->with('error', $errorMessage);
            }
            return back()->with('success', 'All ' . $successCount . ' WhatsApp messages were sent successfully!');
        } catch (\Exception $e) {
            Log::error('WhatsApp sending failed with an exception: ' . $e->getMessage());
            return back()->with('error', 'An unexpected system error occurred.');
        }
    }
    private function sendPlainMessage($number, $message)
    {
        $apiUrl = 'https://api.interakt.ai/v1/public/message/';
        $token = env('INTERAKT_API_TOKEN');
        $payload = [
            'countryCode' => '+91',
            'phoneNumber' => $number,
            'type' => 'Text',
            'data' => [
                'message' => $message
            ],
        ];
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'Content-Type' => 'application/json',
        ])->post($apiUrl, $payload);
        if (!$response->successful()) {
            Log::error('❌ Failed to send plain message: ' . $response->body());
        }
        return $response;
    }
}
