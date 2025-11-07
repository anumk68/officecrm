<?php

namespace App\Http\Controllers;

use App\Mail\QuoteMail;
use App\Models\ContactPersons;
use App\Models\Lead;
use App\Models\LeadProduct;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class QuoteController extends Controller
{
    /**
     * List all quotes
     */
    public function index()
    {
        try {
            $quotes = Quote::with(['person', 'lead', 'user', 'items.project'])->latest()->get();
            return view('lead.quote.index', compact('quotes'));
        } catch (\Exception $e) {
            Log::error("Quote index failed: " . $e->getMessage());
            return back()->with("error", "Failed to load quotes." . $e->getMessage());
        }
    }

    /**
     * Show create form
     */
    public function create()
    {
        try {
            $users = User::where('role', 'manager')->get();
            $persons = ContactPersons::get();
            $leads = Lead::get();
            $leadprojects = LeadProduct::get();
            $countries = DB::table('countries')->get();
            return view('lead.quote.create', compact('users', 'persons', 'leads', 'leadprojects', 'countries'));
        } catch (\Exception $e) {
            Log::error("Quote create form failed: " . $e->getMessage());
            return back()->with("error", "Failed to open create form." . $e->getMessage());
        }
    }

    /**
     * Store new quote
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'sales_owner_id' => 'required|integer',
            'person_id' => 'required|integer',
            'lead_id' => 'required|integer',
            'billing_country' => 'required|string',
            'billing_state' => 'required|string',
            'billing_city' => 'required|string',
            'billing_postcode' => 'required|string',
            'shipping_country' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_postcode' => 'required|string',
            'items' => 'required|array',
        ]);
        // dd($request->all());
        try {
            $quote = Quote::create([
                'subject' => $request->subject,
                'description' => $request->description,
                'expired_at' => $request->expires_at,
                'user_id' => $request->sales_owner_id,
                'person_id' => $request->person_id,
                'lead_id' => $request->lead_id,
                'billing_address' => [
                    'country' => $request->billing_country,
                    'state' => $request->billing_state,
                    'city' => $request->billing_city,
                    'postcode' => $request->billing_postcode,
                ],
                'shipping_address' => [
                    'country' => $request->shipping_country,
                    'state' => $request->shipping_state,
                    'city' => $request->shipping_city,
                    'postcode' => $request->shipping_postcode,
                ],
                'sub_total' => collect($request->items)->sum('amount'),
                'discount_percent' => collect($request->items)->sum('discount_percent'),
                'discount_amount' => collect($request->items)->sum('discount_amount'),
                'tax_percent' => collect($request->items)->sum('tax_percent'),
                'tax_amount' => collect($request->items)->sum('tax_amount'),
                'grand_total' => collect($request->items)->sum('total'),
            ]);

            foreach ($request->items as $item) {
                QuoteItem::create([
                    'name' => $item['product'] ?? null,
                    'project_id' => $item['project_id'] ?? null,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'discount_percent' => $item['discount_percent'],
                    'discount_amount' => $item['discount_amount'],
                    'tax_percent' => $item['tax_percent'],
                    'tax_amount' => $item['tax_amount'],
                    'total' => $item['total'],
                    'quote_id' => $quote->id,
                ]);
            }

            return redirect()->route('quotes.index')->with('success', 'Quote created successfully!');
        } catch (\Exception $e) {
            Log::error("Quote store failed: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to create quote.". $e->getMessage());
        }
    }


    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'subject' => 'required|string|max:255',
    //         'description' => 'nullable|string',
    //         'expires_at' => 'nullable|date',
    //         'sales_owner_id' => 'required|integer',
    //         'person_id' => 'required|integer',
    //         'lead_id' => 'required|integer',
    //         'billing_country' => 'required|string',
    //         'billing_state' => 'required|string',
    //         'billing_city' => 'required|string',
    //         'billing_postcode' => 'required|string',
    //         'shipping_country' => 'required|string',
    //         'shipping_state' => 'required|string',
    //         'shipping_city' => 'required|string',
    //         'shipping_postcode' => 'required|string',
    //         'items' => 'required|array',
    //     ]);

    //     try {
    //         $quote = Quote::create([
    //             'subject' => $request->subject,
    //             'description' => $request->description,
    //             'expired_at' => $request->expires_at,
    //             'user_id' => $request->sales_owner_id,
    //             'person_id' => $request->person_id,
    //             'lead_id' => $request->lead_id,
    //             'billing_address' => [
    //                 'country' => $request->billing_country,
    //                 'state' => $request->billing_state,
    //                 'city' => $request->billing_city,
    //                 'postcode' => $request->billing_postcode,
    //             ],
    //             'shipping_address' => [
    //                 'country' => $request->shipping_country,
    //                 'state' => $request->shipping_state,
    //                 'city' => $request->shipping_city,
    //                 'postcode' => $request->shipping_postcode,
    //             ],
    //             'sub_total' => collect($request->items)->sum('amount'),
    //             'discount_amount' => collect($request->items)->sum('discount_amount'),
    //             'tax_amount' => collect($request->items)->sum('tax_amount'),
    //             'grand_total' => collect($request->items)->sum('total'),
    //         ]);

    //         foreach ($request->items as $item) {
    //             QuoteItem::create([
    //                 'name' => $item['product'] ?? null,
    //                 'project_id' => $item['project_id'] ?? null,
    //                 'quantity' => $item['qty'],
    //                 'price' => $item['price'],
    //                 'discount_amount' => $item['discount_amount'],
    //                 'tax_amount' => $item['tax_amount'],
    //                 'total' => $item['total'],
    //                 'quote_id' => $quote->id,
    //             ]);
    //         }

    //         return redirect()->route('quotes.index')->with('success', 'Quote created successfully!');
    //     } catch (\Exception $e) {
    //         Log::error("Quote store failed: " . $e->getMessage());
    //         return back()->withInput()->with("error", "Failed to create quote." . $e->getMessage());
    //     }
    // }

    /**
     * View a single quote
     */
    public function view($id)
    {
        try {
            $quote = Quote::with(['items', 'person', 'lead'])->findOrFail($id);
            return view('lead.quote.view', compact('quote'));
        } catch (\Exception $e) {
            Log::error("Quote view failed: " . $e->getMessage());
            return back()->with("error", "Failed to load quote.");
        }
    }

    /**
     * Generate PDF and download
     */
    public function print($id)
    {
        try {
            $quote = Quote::with(['items', 'person', 'lead'])->findOrFail($id);
            $pdf = Pdf::loadView('lead.quote.pdf', compact('quote'))->setPaper('a4', 'portrait');
            return $pdf->download('CRM-quote-' . $quote->id . '.pdf');
        } catch (\Exception $e) {
            Log::error("Quote print failed: " . $e->getMessage());
            return back()->with("error", "Failed to generate PDF.");
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $users = User::get();
            $quote = Quote::with('items')->findOrFail($id);
            $salesOwners = User::all();
            $persons = ContactPersons::all();
            $leads = Lead::get();
            $leadprojects = LeadProduct::get();
            $countries = DB::table('countries')->get();
            return view('lead.quote.edit', compact('quote', 'salesOwners', 'persons', 'leads', 'users', 'countries', 'leadprojects'));
        } catch (\Exception $e) {
            Log::error("Quote edit form failed: " . $e->getMessage());
            return back()->with("error", "Failed to open edit form.");
        }
    }

    /**
     * Update a quote
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'sales_owner_id' => 'required|integer',
            'person_id' => 'required|integer',
            'lead_id' => 'required|integer',
            'billing_country' => 'required|string',
            'billing_state' => 'required|string',
            'billing_city' => 'required|string',
            'billing_postcode' => 'required|string',
            'shipping_country' => 'required|string',
            'shipping_state' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_postcode' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.project_id' => 'required|integer',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'items.*.tax' => 'nullable|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
        ]);
        // dd($request->all());
        try {
            $quote = Quote::findOrFail($id);

            $quote->update([
                'subject' => $request->subject,
                'description' => $request->description,
                'expired_at' => $request->expires_at,
                'user_id' => $request->sales_owner_id,
                'person_id' => $request->person_id,
                'lead_id' => $request->lead_id,
                'billing_address' => [
                    'country' => $request->billing_country,
                    'state' => $request->billing_state,
                    'city' => $request->billing_city,
                    'postcode' => $request->billing_postcode,
                ],
                'shipping_address' => [
                    'country' => $request->shipping_country,
                    'state' => $request->shipping_state,
                    'city' => $request->shipping_city,
                    'postcode' => $request->shipping_postcode,
                ],
                'sub_total' => collect($request->items)->sum('amount'),
                'discount_amount' => collect($request->items)->sum('discount_amount'),
                'tax_amount' => collect($request->items)->sum('tax_amount'),
                'grand_total' => collect($request->items)->sum('total'),
            ]);

            QuoteItem::where('quote_id', $quote->id)->delete();

            foreach ($request->items as $item) {
                QuoteItem::create([
                    'name' => $item['product'] ?? null,
                    'project_id' => $item['project_id'] ?? null,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'discount_amount' => $item['discount_amount'],
                    'tax_percent' => $item['tax_percent'] ?? 0,
                    'tax_amount' => $item['tax_amount'],
                    'total' => $item['total'],
                    'quote_id' => $quote->id,
                ]);
            }

            return redirect()->route('quotes.index')->with('success', 'Quote updated successfully!');
        } catch (\Exception $e) {
            Log::error("Quote update failed: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to update quote." . $e->getMessage());
        }
    }

    /**
     * Send mail with PDF
     */
    public function sendMail($id)
    {
        try {
            $quote = Quote::with(['items', 'person', 'lead'])->findOrFail($id);
            $pdfContent = Pdf::loadView('lead.quote.pdf', compact('quote'))->output();

            if (empty($pdfContent) || strlen($pdfContent) < 100) {
                return back()->with('error', 'PDF generation failed or is empty.');
            }

            $emails = !empty($quote->person->emails)
                ? json_decode($quote->person->emails, true)
                : [];

            if (empty($emails)) {
                return back()->with('error', 'No email addresses found for this person.');
            }

            Mail::to($emails)->send(new QuoteMail($quote, $pdfContent));
            return redirect()->route('quotes.index')->with('success', 'Quote sent successfully with PDF attachment.');
        } catch (\Exception $e) {
            Log::error("Mail sending failed: " . $e->getMessage());
            return back()->with("error", "Mail sending failed.");
        }
    }

    /**
     * Delete a quote
     */
    public function destroy($id)
    {
        try {
            $quote = Quote::findOrFail($id);
            $quote->delete();
            return redirect()->back()->with('success', 'Quote deleted successfully');
        } catch (\Exception $e) {
            Log::error("Quote delete failed: " . $e->getMessage());
            return back()->with("error", "Failed to delete quote.");
        }
    }
    public function quotebulkDelete(Request $request)
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids) || !is_array($ids)) {
                return back()->with('error', 'No quotes selected!');
            }
            Quote::whereIn('id', $ids)->delete();
            return back()->with('success', count($ids) . ' quote(s) deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Bulk Quote Delete Error: ' . $e->getMessage(), [
                'ids' => $ids ?? null,
                'user_id' => auth()->id()
            ]);
            return back()->with('error', 'Failed to delete quotes. Please try again.');
        }
    }
}
