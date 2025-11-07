<?php
namespace App\Http\Controllers;

use App\Models\LeadProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadProductController extends Controller
{
    // Show all products
    public function index()
    {
        try {
            $products = LeadProduct::with('contact')->latest()->get();
            return view('lead.lead_products.list', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while fetching products.');
        }
    }

    // Show product create form
    public function create()
    {
        try {
            return view('lead.lead_products.create');
        } catch (\Exception $e) {
            Log::error('Error opening create product form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    // Store new product
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string',
            'description' => 'nullable|string',
        ]);
        try {
            LeadProduct::create([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            return redirect()->route('lead-products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create product.');
        }
    }

    // Show product edit form
    public function edit($id)
    {
        try {
            $product = LeadProduct::findOrFail($id);
            return view('lead.lead_products.edit', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error opening edit form: ' . $e->getMessage());
            return redirect()->route('lead-products.index')->with('error', 'Product not found.');
        }
    }

    // Update existing product
    public function update($id, Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string',
            'description' => 'nullable|string',
        ]);
        try {
            LeadProduct::findOrFail($id)->update([
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
            ]);

            return redirect()->route('lead-products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update product.');
        }
    }

    // Delete product
    public function destroy($id)
    {
        try {
            $data = LeadProduct::findOrFail($id);
            $data->delete();

            return redirect()->back()->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete product.');
        }
    }
    public function productsbulkDelete(Request $request)
    {
        $ids = explode(',', $request->ids);

        LeadProduct::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected projects deleted successfully.');
    }

}
