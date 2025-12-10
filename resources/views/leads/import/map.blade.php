@extends('layouts.app')

@section('content')
<style>
    .container-box {
       
        background: #fff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .page-title {
        font-size: 24px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .column-card {
        border: 1px solid #ddd;
        padding: 16px;
        border-radius: 10px;
        background: #f9f9f9;
        display: flex;
        align-items: center;
        transition: .2s;
        cursor: pointer;
    }

    .column-card:hover {
        background: #eef5ff;
        border-color: #4285f4;
        transform: translateY(-2px);
    }

    .column-checkbox {
        transform: scale(1.3);
        margin-right: 12px;
        cursor: pointer;
    }

    .col-title {
        font-weight: 600;
        color: #333;
        margin-left: 5px;
    }

    /* Table */
   .preview-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
    margin-top: 15px;
    border-radius: 8px;
    overflow: hidden;
}

.preview-table thead th {
    background: #f4f7fc;
    padding: 12px;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #dfe6f1;
    text-transform: capitalize;
}

.preview-table tbody tr:nth-child(even) {
    background: #fafafa;
}

.preview-table tbody tr:hover {
    background: #eef4ff;
    transition: 0.2s;
}

.preview-table td {
    padding: 10px;
    border-bottom: 1px solid #e5e7eb;
    color: #444;
}

.preview-table th:first-child,
.preview-table td:first-child {
    padding-left: 14px;
}

.preview-table th:last-child,
.preview-table td:last-child {
    padding-right: 14px;
}


    .lead-source-box {
        width: 250px;
        margin: 20px auto;
    }

    .submit-btn {
        background: #2563eb;
        font-size: 16px;
        padding: 12px 35px;
        border-radius: 8px;
        border: none;
        transition: .2s;
    }

    .submit-btn:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
    }
</style>

    <div style="padding-top:100px">
        <main class="main-content">
<div class="container-box">

    <h3 class="page-title">
        Select Columns to Import  
        <br>
        <small style="font-size: 14px; color:#666;">Total Columns: {{ $totalheaders }}</small>
    </h3>

    <form action="{{ route('leads.import.map') }}" method="POST">
        @csrf

        <input type="hidden" name="import_id" value="{{ $import->id }}">

        <!-- COLUMN LIST -->
        <div class="row">
            @foreach ($headers as $i => $header)
                <div class="col-md-4 mb-3">
                    <label class="column-card">
                        <input type="checkbox" class="column-checkbox column-toggle"
                               data-col="{{ $i }}" checked name="map[{{ $i }}]"
                               value="{{ $header }}">

                        <span class="col-title">{{ $i+1 }}. {{ $header }}</span>
                    </label>
                </div>
            @endforeach
        </div>

        <!-- EXCEL PREVIEW -->
        <h4 class="mt-4 mb-2" style="font-weight:600;">Preview</h4>

    <div class="table-responsive">
    <table class="preview-table" id="datatable">
        <thead>
            <tr>
                @foreach ($headers as $i => $head)
                    <th class="col-{{ $i }}">{{ $head }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($rowsPreview as $row)
                <tr>
                    @foreach ($row as $i => $value)
                        <td class="col-{{ $i }}">{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

        <!-- Lead Source Selection -->
        <div class="lead-source-box">
            <select name="source" class="form-select" required>
                <option value="">-- Select Lead Source --</option>
                @foreach ($leadSources as $src)
                    <option value="{{ $src->id }}">{{ $src->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="text-center mt-3">
            <button class="btn btn-primary submit-btn" type="submit">
                Convert Selected Columns to Leads
            </button>
        </div>

    </form>

</div>
</main>
</div>

<script>
document.querySelectorAll('.column-toggle').forEach(chk => {
    chk.addEventListener('change', function() {
        let colIndex = this.dataset.col;
        let cells = document.querySelectorAll('.col-' + colIndex);
        cells.forEach(cell => cell.style.display = this.checked ? "" : "none");
    });
});
</script>

@endsection
