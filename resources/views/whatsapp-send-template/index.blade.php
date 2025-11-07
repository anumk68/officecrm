@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Oops...</strong><br>
                        {!! nl2br(e(session('error'))) !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                     <div class="alert alert-danger">
                        <p><strong>Please fix the following errors:</strong></p>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>WhatsApp Contact List</h4>
                                <div class="mb-3 float-end">
                                    <button id="composeBtn" class="btn btn-primary" disabled data-bs-toggle="modal"
                                        data-bs-target="#composeModal">
                                        Compose Message
                                    </button>
                                </div>
                                <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Contact Numbers</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($contacts as $index => $contact)
                                            @php
                                                $phones = is_array($contact->contact_numbers) ? $contact->contact_numbers : json_decode($contact->contact_numbers, true);
                                                $phones = is_array($phones) ? $phones : [];
                                                $firstPhone = !empty($phones) ? (is_array($phones[0]) ? ($phones[0]['number'] ?? '') : $phones[0]) : '';
                                            @endphp
                                            <tr>
                                                <td><input type="checkbox" class="contact-checkbox" value="{{ $firstPhone }}"></td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $contact->name }}</td>
                                                <td>
                                                    @foreach ($phones as $phone)
                                                        <div>{{ is_array($phone) ? ($phone['label'] . ': ' . $phone['number']) : $phone }}</div>
                                                    @endforeach
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
    <div class="modal fade" id="composeModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <form action="{{ route('whatsapp.send.post') }}" method="POST" id="composeForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header"><h5 class="modal-title">Compose WhatsApp Message</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <input type="hidden" name="numbers" id="selectedNumbers">
                        <input type="hidden" name="header_handle" id="headerHandleInput">
                        <input type="hidden" name="button_urls" id="buttonUrlsInput">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Recipients:</label>
                            <div id="recipient-display" class="border rounded p-2 bg-light" style="min-height: 40px; max-height: 80px; overflow-y: auto;">
                                <span class="text-muted">No contacts selected.</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Select Template:</label>
                            <select id="templateSelect" name="template_name" class="form-select" required>
                                <option value="">-- Select Template --</option>
                                @foreach ($templates as $template)
                                    <option value="{{ $template['name'] }}"
                                            data-header-handle="{{ $template['header_handle'] ?? '' }}"
                                            data-button-urls="{{ json_encode($template['button_urls'] ?? []) }}">
                                        {{ $template['display_name'] ?? $template['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="sendMsgBtn" class="btn btn-success">
                            <span class="btn-text">Send Message</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const composeBtn = document.getElementById('composeBtn');
            const checkboxes = document.querySelectorAll('.contact-checkbox');
            const selectAll = document.getElementById('selectAll');
            const form = document.getElementById('composeForm');
            const sendBtn = document.getElementById('sendMsgBtn');
            const selectedNumbersInput = document.getElementById('selectedNumbers');
            const recipientDisplay = document.getElementById('recipient-display');
            const templateSelect = document.getElementById('templateSelect');
            const headerHandleInput = document.getElementById('headerHandleInput');
            const buttonUrlsInput = document.getElementById('buttonUrlsInput');
            function updateContactSelection() {
                const selectedNumbers = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value).filter(Boolean);
                composeBtn.disabled = selectedNumbers.length === 0;
                if (selectedNumbers.length === 0) {
                    recipientDisplay.innerHTML = '<span class="text-muted">No contacts selected.</span>';
                    selectedNumbersInput.value = '';
                } else {
                    recipientDisplay.innerHTML = selectedNumbers.map(n => `<span class="badge bg-primary me-1 mb-1">${n}</span>`).join(' ');
                    selectedNumbersInput.value = JSON.stringify(selectedNumbers);
                }
            }
            checkboxes.forEach(cb => cb.addEventListener('change', updateContactSelection));
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateContactSelection();
            });
            function updateTemplateSelection() {
                if (!templateSelect.value) {
                    headerHandleInput.value = '';
                    buttonUrlsInput.value = '';
                    return;
                }
                const selectedOption = templateSelect.options[templateSelect.selectedIndex];
                headerHandleInput.value = selectedOption.getAttribute('data-header-handle');
                buttonUrlsInput.value = selectedOption.getAttribute('data-button-urls');
            }
            templateSelect.addEventListener('change', updateTemplateSelection);
            if (form) {
                form.addEventListener('submit', function() {
                    sendBtn.disabled = true;
                    sendBtn.querySelector('.spinner-border').classList.remove('d-none');
                    sendBtn.querySelector('.btn-text').textContent = 'Sending...';
                });
            }
        });
    </script>
@endsection
