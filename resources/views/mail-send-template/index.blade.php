@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Mail Contact List</h4>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-3 float-end">
                                    <button id="composeBtn" class="btn btn-primary" disabled data-bs-toggle="modal"
                                        data-bs-target="#composeModal">
                                        Compose Mail
                                    </button>
                                </div>

                                <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Emails</th>
                                            <th>Contact Numbers</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contacts as $index => $contact)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="contact-checkbox"
                                                        value="{{ $contact->email ?? (is_array($contact->emails) ? $contact->emails[0] : json_decode($contact->emails, true)[0] ?? '') }}">
                                                </td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $contact->name }}</td>
                                                <td>
                                                    @php
                                                        // Decode the emails if it's in JSON format
                                                        $emails = is_array($contact->emails)
                                                            ? $contact->emails
                                                            : json_decode($contact->emails, true);
                                                    @endphp

                                                    @if (is_array($emails) && count($emails) > 0)
                                                        <!-- Display the first email from the array -->
                                                        <div>{{ $emails[0] }}</div>
                                                    @else
                                                        <!-- If it's a single email, display it directly -->
                                                        <div>{{ $contact->emails }}</div>
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
                                                            <div>{{ is_array($phone) ? implode(' - ', $phone) : $phone }}
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        {{ $contact->contact_numbers }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= Modal ================= -->

<div class="modal fade" id="composeModal" tabindex="-1"
     data-bs-backdrop="static"
     data-bs-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('mail.send.post') }}" method="POST" id="composeForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Compose Email</h5>
                    <!-- Close button (this will still work) -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="to[]" id="selectedEmails" class="form-control">

                    <div class="mb-3">
                        <label>From: - ( Name/Email )</label>
                        <input type="text" name="from" class="form-control" placeholder="Please enter from"  >
                    </div>

                    <div class="mb-3">
                        <label>Select Template:</label>
                        <select id="templateSelect" name="template_id" class="form-select"  >
                            <option value="">-- Select Template --</option>
                            @foreach ($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="sendMailBtn" class="btn btn-success">
                        <span class="btn-text">Send Mail</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('composeForm');
    const sendBtn = document.getElementById('sendMailBtn');
    const spinner = sendBtn.querySelector('.spinner-border');
    const text = sendBtn.querySelector('.btn-text');

    form.addEventListener('submit', function() {
        sendBtn.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Sending...';
    });
});
</script>

    <!-- ================= Scripts ================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const composeBtn = document.getElementById('composeBtn');
            const checkboxes = document.querySelectorAll('.contact-checkbox');
            const selectAll = document.getElementById('selectAll');
            const selectedEmailsInput = document.getElementById('selectedEmails');

            // Laravel route pattern for fetching template data
            const getTemplateUrl = "{{ route('mail.getTemplate', ['id' => ':id']) }}";

            // Enable/disable compose button based on checkbox selection
            checkboxes.forEach(cb => cb.addEventListener('change', updateSelection));
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateSelection();
            });

            function updateSelection() {
                const selectedEmails = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value)
                    .filter(Boolean);

                composeBtn.disabled = selectedEmails.length === 0;
                selectedEmailsInput.value = JSON.stringify(selectedEmails);
            }

            // Auto-fill subject and body on template select
            // const templateSelect = document.getElementById('templateSelect');
            // const subjectInput = document.getElementById('subject');
            // const bodyPreview = document.getElementById('bodyPreview');
            // const bodyHidden = document.getElementById('bodyHidden');

            // templateSelect.addEventListener('change', function() {
            //     const id = this.value;

            //     if (!id) {
            //         subjectInput.value = '';
            //         bodyPreview.innerHTML = '';
            //         bodyHidden.value = '';
            //         return;
            //     }

            //     const url = getTemplateUrl.replace(':id', id);

            //     fetch(url)
            //         .then(res => res.json())
            //         .then(data => {
            //             if (data.error) {
            //                 alert('Template not found.');
            //                 subjectInput.value = '';
            //                 bodyPreview.innerHTML = '';
            //                 bodyHidden.value = '';
            //                 return;
            //             }

            //             subjectInput.value = data.subject || '';
            //             bodyPreview.innerHTML = data.body || '';
            //             bodyHidden.value = data.body || '';
            //         })
            //         .catch(err => {
            //             console.error('Error loading template:', err);
            //             alert('Failed to load template.');
            //             subjectInput.value = '';
            //             bodyPreview.innerHTML = '';
            //             bodyHidden.value = '';
            //         });
            // });
        });
    </script>
@endsection
