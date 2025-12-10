@extends('layouts.app')
@section('content')

<div class="container py-4 px-4">   {{-- Added padding top/bottom/left/right --}}

    <h3 class="mb-4">Preview Imported Leads</h3>

    {{-- Summary --}}
    <div class="alert alert-info">
        <strong>Total Rows:</strong> {{ $import->total_rows }} |
        <strong>Valid:</strong> {{ count($validRows) }} |
        <strong>Failed:</strong> {{ count($failedRows) }}
    </div>

    {{-- FAILED ROWS --}}
    @if(count($failedRows) > 0)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-danger text-white">
            Invalid Rows ({{ count($failedRows) }})
        </div>

        <div class="card-body p-3"> {{-- Extra padding --}}
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="80">#</th>
                        <th>Data</th>
                        <th>Errors</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($failedRows as $fail)
                    <tr>
                        <td>{{ $fail['row_num'] }}</td>

                        <td>
                            <pre class="mb-0">{{ print_r($fail['data'], true) }}</pre>
                        </td>

                        <td>
                            <ul class="mt-2 mb-0">
                                @foreach($fail['errors'] as $err)
                                <li class="text-danger">{{ $err }}</li>
                                @endforeach
                            </ul>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif


    {{-- VALID ROWS TABLE --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">
            Valid Leads ({{ count($validRows) }})
        </div>

        <div class="card-body p-3"> {{-- Extra padding --}}
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        @foreach($mapping as $mapCol)
                            @if($mapCol)
                                <th>{{ ucfirst($mapCol) }}</th>
                            @endif
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($validRows as $row)
                    <tr>
                        @foreach($mapping as $field)
                            @if($field)
                                <td>{{ $row[$field] ?? '' }}</td>
                            @endif
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Confirm Button --}}
    <form action="{{ route('leads.import.confirm') }}" method="POST">
        @csrf

        <input type="hidden" name="import_id" value="{{ $import->id }}">

        @foreach($validRows as $index => $row)
            @foreach($row as $key => $value)
                <input type="hidden" name="validRows[{{ $index }}][{{ $key }}]" value="{{ $value }}">
            @endforeach
        @endforeach

        <button class="btn btn-primary btn-lg mt-3" type="submit">Confirm & Import Leads</button>
    </form>

</div>

@endsection
