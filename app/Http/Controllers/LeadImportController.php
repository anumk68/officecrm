<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeadImport;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\LeadActivity;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LeadImportController extends Controller
{
    public function showUpload()
    {
        return view('leads.import.upload');
    }
    public function upload(Request $request)
    {
        // Validate file type
        $request->validate([
            'file' => 'required',
        ]);

        try {
            $path = $request->file('file')->store('imports', 'public');
            $rows = Excel::toArray([], $request->file('file'));

            $firstSheet = $rows[0] ?? [];

            $import = LeadImport::create([
                'filename'      => $path,
                'original_name' => $request->file('file')->getClientOriginalName(),
                'total_rows'    => max(count($firstSheet) - 1, 0),
                'uploaded_by'   => auth()->id(),
            ]);

            return redirect()->route('leads.import.map.show', $import->id);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return back()
                ->withErrors([
                    'file' =>
                    "The file you uploaded was downloaded directly from a Lead Campaign and is not a valid Excel format. 
                Please open the file in Microsoft Excel and re-save it as a proper Excel Workbook (.xlsx), then upload it again."
                ])
                ->withInput();
        } catch (\Exception $e) {

            return back()
                ->withErrors([
                    'file' =>
                    "Unable to read the file. Please ensure it is a valid Excel (.xlsx, .xls, .csv) and try again."
                ])
                ->withInput();
        }
    }

    public function showMap($id)
    {
        $import = LeadImport::findOrFail($id);
        $fullPath = Storage::disk('public')->path($import->filename);

        $rows = Excel::toArray([], $fullPath);
        $sheet = $rows[0] ?? [];

        $headers = $sheet[0] ?? [];
        $rowsPreview = array_slice($sheet, 1, 10); // show first 10 rows
        $leadSources = LeadSource::all();
        $totalheaders = count($headers);

        return view('leads.import.map', compact('headers', 'import', 'rowsPreview', 'leadSources', 'totalheaders'));
    }
    public function map(Request $request)
    {
        $request->validate([
            'import_id' => 'required|exists:lead_imports,id',
            'map'       => 'required|array',
        ]);

        $import = LeadImport::find($request->import_id);
        $fullPath = Storage::disk('public')->path($import->filename);

        if (!file_exists($fullPath)) {
            return back()->with('error', 'File missing in storage!');
        }

        // Read Excel
        $rows = Excel::toArray([], $fullPath);
        $sheet = $rows[0] ?? [];

        if (empty($sheet)) {
            return back()->with('error', 'Excel file is empty!');
        }

        $headers = $sheet[0];
        unset($sheet[0]); // remove header row

        // Allowed DB columns from Lead model
        $allowed = (new Lead())->getFillable();

        $inserted = 0;

        foreach ($sheet as $row) {

            $leadData = [];
            $metaData = [];

            foreach ($request->map as $colIndex => $mappedFieldName) {

                if (!$mappedFieldName) continue;

                $field = strtolower(trim($mappedFieldName));
                $value = $row[$colIndex] ?? null;
                /** Convert created_time properly */
                if ($field === 'created_time') {

                    if (is_numeric($value)) {
                        // Excel serial date → convert to Y-m-d
                        $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                    } else {
                        // If date like "11-9-23"
                        $value = date('Y-m-d', strtotime($value));
                    }
                }

                if (in_array($field, $allowed)) {
                    // Direct fillable fields
                    $leadData[$field] = $value;
                } else {
                    // Extra → store in meta
                    $metaData[$field] = $value;
                }
            }

            if (empty($leadData) && empty($metaData)) continue;

            // Auto-lead-source
            $leadData['lead_source_id'] = $request->source ?? null;
            $leadData['created_by'] = auth()->id();


            // Add meta JSON (if any)
            if (!empty($metaData)) {
                $leadData['meta'] = json_encode($metaData);
            }

            Lead::create($leadData);
            $inserted++;
        }

        return redirect()->route('leads.import.show')
            ->with('success', "$inserted leads imported successfully!");
    }


    public function showPreview($id)
    {
        $import = LeadImport::findOrFail($id);
        $validRows  = json_decode($import->valid_rows, true) ?? [];
        $failedRows = json_decode($import->failed_rows, true) ?? [];
        $mapping    = json_decode($import->mapping, true) ?? [];

        return view('leads.import.preview', compact('import', 'validRows', 'failedRows', 'mapping'));
    }


    // public function confirm(Request $request)
    // {
    //     $request->validate([
    //         'import_id' => 'required|exists:lead_imports,id',
    //         'validRows' => 'required|array'
    //     ]);

    //     $import = LeadImport::find($request->import_id);

    //     $successCount = 0;

    //     foreach ($request->validRows as $row) {
    //         Lead::create([
    //             'name' => $row['name'] ?? null,
    //             'company' => $row['company'] ?? null,
    //             'email' => $row['email'] ?? null,
    //             'phone' => $row['phone'] ?? null,
    //             'notes' => $row['notes'] ?? null,
    //             'source' => 'Excel Upload',
    //             'created_by' => auth()->id(),
    //         ]);

    //         $successCount++;
    //     }

    //     // Update import result
    //     $import->update([
    //         'success_count' => $successCount,
    //         'failed_count' => ($import->total_rows - $successCount),
    //         'status' => 'completed'
    //     ]);

    //     return redirect()->route('leads.index')
    //         ->with(
    //             'success',
    //             "Excel Import Completed. 
    //         Imported: $successCount, 
    //         Failed: " . ($import->total_rows - $successCount)
    //         );
    // }
}
