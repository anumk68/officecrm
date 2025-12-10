<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update Request</title>
</head>
<body style="font-family:Arial, sans-serif; background:#f9f9f9; padding:20px;">
    <div style="background:#fff; padding:20px; border-radius:8px; max-width:600px; margin:auto;">
        <h3>Profile Update Request from {{ $user->full_name }}</h3>
        <p>The following changes have been requested:</p>

        <ul>
            @foreach ($data as $key => $value)
                @if (!empty($value))
                    <li><strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                @endif
            @endforeach
        </ul>

        <p style="margin-top:20px;">
            <a href="{{ $approveUrl }}"
               style="background:#28a745;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;margin-right:10px;display:inline-block;">
               ✅ Approve
            </a>

            <a href="{{ $rejectUrl }}"
               style="background:#dc3545;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;">
               ❌ Reject
            </a>
        </p>

        <p style="margin-top:30px;color:#666;">This is an automated email from the HR system.</p>
    </div>
</body>
</html>
