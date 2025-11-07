<tr>
    <td>
        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="{{ $checkboxClass }}">
    </td>
    <td>{{ $index }}</td>
    <td>{{ $employee->unique_id }}</td>
    <td>{{ $employee->full_name }}</td>
    <td>{{ $employee->position }}</td>
    <td>{{ $employee->email }}</td>
    <td>{{ $employee->status }}</td>
    <td>{{ ucwords(str_replace('_', ' ', $employee->role)) }}</td>
    <td>
        <div class="dropdown">
            <button class="btn btn-sm btn-warning dropdown-toggle px-3 py-1" type="button"
                id="actionMenu{{ $employee->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow rounded-3 p-2"
                aria-labelledby="actionMenu{{ $employee->id }}">
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="{{ route('employees.show', $employee->id) }}">
                        <i class="bi bi-person-lines-fill text-primary"></i> View Details
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="{{ route('view.employees.attendance', $employee->id) }}">
                        <i class="bi bi-calendar-check text-success"></i> View Attendance
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                        href="{{ route('employees.edit', $employee->id) }}">
                        <i class="bi bi-pencil-square text-warning"></i> Edit
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    {{-- NO FORM HERE - Just a button --}}
                    <button type="button" class="dropdown-item d-flex align-items-center gap-2 text-danger py-2"
                        onclick="confirmDelete({{ $employee->id }})">
                        <i class="bi bi-trash-fill"></i> Delete
                    </button>
                </li>
            </ul>
        </div>
    </td>
</tr>
