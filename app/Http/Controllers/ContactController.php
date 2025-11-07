<?php
namespace App\Http\Controllers;

use App\Models\City;
use App\Models\ContactPersons;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    // public function index()
    // {
    //     try {
    //         $contacts = ContactPersons::get();
    //         return view('lead.contacts.list', compact('contacts'));
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching contacts: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Something went wrong while fetching contacts.');
    //     }
    // }
    public function index()
    {
        try {
            $contacts = ContactPersons::with(['countryData', 'stateData', 'cityData', 'subCountryData', 'subStateData', 'subCityData'])->get();
            return view('lead.contacts.list', compact('contacts'));
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching contacts.');
        }
    }


    public function create()
    {
        try {
            $countries = Country::all();
            return view('lead.contacts.create', compact('countries'));
        } catch (\Exception $e) {
            Log::error('Error opening create contact form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }
    public function getStates($countryId)
    {
        return response()->json(State::where('countryId', $countryId)->get());
    }

    public function getCities($stateId)
    {
        return response()->json(City::where('stateId', $stateId)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
            'phones' => 'nullable|array',
            'phones.*' => 'nullable|string|max:20',

            // Main Address
            'country' => 'required|integer',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'address' => 'required|string|max:255',
            'zip' => 'nullable|string|max:20',

            // Sub Address
            'sub_country' => 'nullable|integer',
            'sub_state' => 'nullable|integer',
            'sub_city' => 'nullable|integer',
            'sub_village' => 'nullable|string|max:255',
            'sub_zip' => 'nullable|string|max:20',
        ]);

        try {
            $contact = new ContactPersons();
            $contact->name = $validated['name'];
            $contact->emails = json_encode($validated['emails']);
            $contact->contact_numbers = json_encode($validated['phones'] ?? []);

            // Main Address save in separate columns
            $contact->country = $validated['country'];
            $contact->state = $validated['state'];
            $contact->city = $validated['city'];
            $contact->village = $validated['address'];
            $contact->zip = $validated['zip'] ?? null;

            // Sub Address save in separate columns
            $contact->sub_country = $validated['sub_country'] ?? null;
            $contact->sub_state = $validated['sub_state'] ?? null;
            $contact->sub_city = $validated['sub_city'] ?? null;
            $contact->sub_village = $validated['sub_village'] ?? null;
            $contact->sub_zip = $validated['sub_zip'] ?? null;

            $contact->save();

            return redirect()->route('contact.index')->with('success', 'Contact created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating contact: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create contact.' . $e->getMessage());
        }
    }


    // public function store(Request $request)
    // {

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'emails' => 'required|array|min:1',
    //         'emails.*' => 'required|email',
    //         'phones' => 'nullable|array',
    //         'phones.*' => 'nullable|string|max:20',
    //         'address' => 'required|string|max:255',
    //     ]);
    //     try {
    //         $contact = new ContactPersons();
    //         $contact->name = $validated['name'];
    //         $contact->emails = json_encode($validated['emails']);
    //         $contact->contact_numbers = json_encode($validated['phones'] ?? []);
    //         $contact->address = $validated['address'];
    //         $contact->save();

    //         return redirect()->route('contact.index')->with('success', 'Contact created successfully!');
    //     } catch (\Exception $e) {
    //         Log::error('Error creating contact: ' . $e->getMessage());
    //         return redirect()->back()->withInput()->with('error', 'Failed to create contact.');
    //     }
    // }

    public function edit($id)
    {
        try {
            $contact = ContactPersons::findOrFail($id);
            return view('lead.contacts.edit', compact('contact'));
        } catch (\Exception $e) {
            Log::error('Error editing contact: ' . $e->getMessage());
            return redirect()->route('contact.index')->with('error', 'Contact not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'emails' => 'required|array|min:1',
            'emails.*' => 'required|email',
            'contact_numbers' => 'nullable|array',
            'contact_numbers.*' => 'nullable|string|max:20',

            // Main Address
            'country' => 'required|integer',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'address' => 'required|string|max:255',
            'zip' => 'nullable|string|max:20',

            // Sub Address
            'sub_country' => 'nullable|integer',
            'sub_state' => 'nullable|integer',
            'sub_city' => 'nullable|integer',
            'sub_village' => 'nullable|string|max:255',
            'sub_zip' => 'nullable|string|max:20',
        ]);

        try {
            $contact = ContactPersons::findOrFail($id);

            $contact->name = $validated['name'];
            $contact->emails = json_encode($validated['emails']);
            $contact->contact_numbers = json_encode($validated['contact_numbers'] ?? []);

            // Main Address
            $contact->country = $validated['country'];
            $contact->state = $validated['state'];
            $contact->city = $validated['city'];
            $contact->village = $validated['address'];
            $contact->zip = $validated['zip'] ?? null;

            // Sub Address
            $contact->sub_country = $validated['sub_country'] ?? null;
            $contact->sub_state = $validated['sub_state'] ?? null;
            $contact->sub_city = $validated['sub_city'] ?? null;
            $contact->sub_village = $validated['sub_village'] ?? null;
            $contact->sub_zip = $validated['sub_zip'] ?? null;

            $contact->save();

            return redirect()->route('contact.index')->with('success', 'Contact updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating contact: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update contact.');
        }
    }


    public function destroy($id)
    {
        try {
            $contact = ContactPersons::findOrFail($id);
            $contact->delete();

            return redirect()->route('contact.index')->with('success', 'Contact deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage());
            return redirect()->route('contact.index')->with('error', 'Failed to delete contact.');
        }
    }
    public function contactbulkDelete(Request $request)
    {
        try {
            $ids = $request->contact_ids;
            if (!$ids || !is_array($ids) || count($ids) === 0) {
                return back()->with('error', 'No contacts selected for deletion.');
            }
            $ids = array_filter($ids, function ($id) {
                return is_numeric($id) && $id > 0;
            });
            if (count($ids) === 0) {
                return back()->with('error', 'No valid contacts selected.');
            }
            $deleted = ContactPersons::whereIn('id', $ids)->delete();
            if ($deleted === 0) {
                return back()->with('error', 'No matching contacts found for deletion.');
            }
            return back()->with('success', "Successfully deleted {$deleted} contact(s).");
        } catch (\Exception $e) {
            Log::error('Contact Bulk Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete contacts. Please try again.');
        }
    }


}
