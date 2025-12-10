<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Task Assigned</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: black;">
    <h2 style="color: black;">New Task Assigned</h2>

    <p>Hello <strong>{{ $user->full_name }}</strong>,</p>

    <p>A new task has been assigned to you.</p>

    <h3 style="color: black;">Task Details:</h3>
    <ul>
        <li><strong>Task:</strong> {{ $task->task }}</li>
        <li><strong>Website:</strong> <a href="{{ $task->website }}" style="color: black;">{{ $task->website }}</a></li>
        <li><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y') }}</li>
        <li><strong>Priority:</strong> {{ $task->priority }}</li>
        <li><strong>Assigned By:</strong> {{ $task->assigner->full_name ?? 'N/A' }}</li>
    </ul>

    <p>
        <a href="{{ url('/dashboard') }}" style="display: inline-block; padding: 10px 20px; background-color: #3490dc; color: white; text-decoration: none; border-radius: 5px;">
            View Task
        </a>
    </p>

    <p>Thanks</p>
</body>
</html>
