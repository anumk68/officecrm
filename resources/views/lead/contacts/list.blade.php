@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-address-book"></i> All Persons Contact</h4>
                            <p class="mb-0 opacity-75">Contact with person.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <a href="{{ route('contact.create') }}">
                                        <button class="btn btn-primary">Add Contact</button>
                                    </a>

                                    <button type="button" class="btn btn-danger" id="bulkDeleteBtn" disabled>
                                        <i class="fa-solid fa-trash"></i> Delete Selected
                                    </button>
                                </div>

                                <form id="bulkDeleteForm" action="{{ route('contacts.bulkDelete') }}" method="POST">
                                    @csrf

                                    <table id="datatable"
                                        class="table table-bordered table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Emails</th>
                                                <th>Contact Numbers</th>
                                                <th>Address</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contacts as $index => $contact)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="contact-checkbox" name="contact_ids[]"
                                                            value="{{ $contact->id }}">
                                                    </td>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $contact->name }}</td>
                                                    <td>
                                                        @php
                                                            $emails = is_array($contact->emails)
                                                                ? $contact->emails
                                                                : json_decode($contact->emails, true);
                                                        @endphp
                                                        @if (is_array($emails))
                                                            @foreach ($emails as $email)
                                                                <div>
                                                                    {{ is_array($email) ? implode(' - ', $email) : $email }}
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            {{ $contact->emails }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $phones = is_array($contact->contact_numbers)
                                                                ? $contact->contact_numbers
                                                                : json_decode($contact->contact_numbers, true);
                                                        @endphp
                                                        @if (is_array($phones))
                                                            @foreach ($phones as $phone)
                                                                <div>
                                                                    {{ is_array($phone) ? implode(' - ', $phone) : $phone }}
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            {{ $contact->contact_numbers }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- MAIN ADDRESS --}}
                                                        <strong>Main:</strong><br>
                                                        {{ $contact->village }},
                                                        {{ $contact->cityData->name ?? '' }},
                                                        {{ $contact->stateData->name ?? '' }},<br>
                                                        {{ $contact->countryData->name ?? '' }}
                                                        @if ($contact->zip)
                                                            - {{ $contact->zip }}
                                                        @endif

                                                        {{-- SUB ADDRESS --}}
                                                        @if ($contact->sub_country || $contact->sub_state || $contact->sub_city || $contact->sub_village)
                                                            <hr class="my-1">
                                                            <strong>Optional:</strong><br>
                                                            {{ $contact->sub_village ?? '' }},
                                                            {{ $contact->subCityData->name ?? '' }},
                                                            {{ $contact->subStateData->name ?? '' }},
                                                            {{ $contact->subCountryData->name ?? '' }}
                                                            @if ($contact->sub_zip)
                                                                - {{ $contact->sub_zip }}
                                                            @endif
                                                        @endif
                                                    </td>

                                                    <td>{{ $contact->created_at->format('d M Y') }}</td>
                                                    <td class="d-flex gap-2">
                                                        <a href="{{ route('contacts.edit', $contact->id) }}"
                                                            class="btn btn-sm btn-warning">Edit</a>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDeleteSingle({{ $contact->id }})">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.contact-checkbox');
        const bulkBtn = document.getElementById('bulkDeleteBtn');
        const bulkForm = document.getElementById('bulkDeleteForm');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkBtn();
            });
        }
        checkboxes.forEach(cb => cb.addEventListener('change', toggleBulkBtn));

        function toggleBulkBtn() {
            const selected = document.querySelectorAll('.contact-checkbox:checked').length;
            if (bulkBtn) {
                bulkBtn.disabled = selected === 0;
            }
        }
        if (bulkBtn) {
            bulkBtn.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                const selectedCheckboxes = document.querySelectorAll('.contact-checkbox:checked');
                const count = selectedCheckboxes.length;
                if (count === 0) {
                    Swal.fire('No Contact Selected', 'Please select at least one contact to delete.', 'warning');
                    return;
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete ${count} contact(s)!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkForm.submit();
                    }
                });
            });
        }

        function confirmDeleteSingle(contactId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to delete this contact?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/contacts/${contactId}`;
                    form.style.display = 'none';
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
