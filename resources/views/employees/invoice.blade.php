<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Employee Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
        }

        .table img {
            max-width: 120px;
            max-height: 120px;
        }
    </style>
</head>

<body>
    <h2>Employee Invoice</h2>
    <table class="table">
        <tr>
            <th>Unique ID</th>
            <td>{{ $employee->unique_id }}</td>
        </tr>
        <tr>
            <th>Full Name</th>
            <td>{{ $employee->full_name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $employee->email }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td>{{ ucfirst($employee->role) }}</td>
        </tr>
        <tr>
            <th>Designation</th>
            <td>{{ $employee->position }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($employee->status) }}</td>
        </tr>
        <tr>
            <th>Joining Date</th>
            <td>{{ $employee->joining_date?->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <th>DOB</th>
            <td>{{ $employee->dob?->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <th>Per Month Salary</th>
            <td>{{ $employee->per_month_salary }}</td>
        </tr>
        <tr>
            <th>Per Day Salary</th>
            <td>{{ $employee->per_day_salary }}</td>
        </tr>
        <tr>
            <th>PAN Card</th>
            <td>
                @if(getBase64Image($employee->pan_card))
                    <img src="{{ getBase64Image($employee->pan_card) }}">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>Aadhaar Card</th>
            <td>
                @if(getBase64Image($employee->aadhaar_card))
                    <img src="{{ getBase64Image($employee->aadhaar_card) }}">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>Qualification</th>
            <td>
                @if(getBase64Image($employee->last_qualification))
                    <img src="{{ getBase64Image($employee->last_qualification) }}">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>Salary Slip</th>
            <td>
                @if(getBase64Image($employee->salary_slip))
                    <img src="{{ getBase64Image($employee->salary_slip) }}">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>Experience Letter</th>
            <td>
                @if(getBase64Image($employee->previous_experience_letter))
                    <img src="{{ getBase64Image($employee->previous_experience_letter) }}">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>Offer Letter</th>
            <td>
                @if(getBase64Image($employee->previous_offer_letter))
                    <img src="{{ getBase64Image($employee->previous_offer_letter) }}">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>Bank Copy</th>
            <td>
                @if(getBase64Image($employee->bank_copy))
                    <img src="{{ getBase64Image($employee->bank_copy) }}">
                @else
                    N/A
                @endif
            </td>
        </tr>
    </table>
</body>

</html>
