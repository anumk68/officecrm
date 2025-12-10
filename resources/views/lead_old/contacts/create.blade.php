@extends('layouts.app')

@section('content')
    <div style="padding-top:100px">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                        {{-- Top Bar --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Create Contact</h4>
                            <button form="employeeForm" class="btn btn-primary">Save Contact</button>
                        </div>

                        {{-- Form --}}
                        <form id="employeeForm" action="{{ route('contact.store') }}" method="POST">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Emails -->
                            <div class="mb-3" id="email-section">
                                <label class="form-label">Emails *</label>

                                @php
                                    $oldEmails = old('emails', ['']);
                                @endphp

                                @foreach ($oldEmails as $index => $email)
                                    <div class="input-group mb-2">
                                        <input type="email" name="emails[]" class="form-control"
                                            value="{{ is_array($email) ? implode(' - ', $email) : $email }}">
                                        @if ($index > 0)
                                            <button type="button" class="btn btn-danger remove-field">Remove</button>
                                        @endif
                                    </div>
                                @endforeach

                                @error('emails')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                @error('emails.*')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <span class="text-primary" style="cursor:pointer" onclick="addEmail()">+ Add More</span>
                            </div>

                            <!-- Contact Numbers -->
                            <div class="mb-3" id="phone-section">
                                <label class="form-label">Contact Numbers</label>

                                @php
                                    $oldPhones = old('phones', ['']);
                                @endphp

                                @foreach ($oldPhones as $index => $phone)
                                    <div class="input-group mb-2">
                                        <input type="text" name="phones[]" class="form-control"
                                            value="{{ is_array($phone) ? implode(' - ', $phone) : $phone }}">
                                        @if ($index > 0)
                                            <button type="button" class="btn btn-danger remove-field">Remove</button>
                                        @endif
                                    </div>
                                @endforeach

                                @error('phones.*')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                <span class="text-primary" style="cursor:pointer" onclick="addPhone()">+ Add More</span>
                            </div>
                            <div class="row">
                                <!-- Address -->
                                <div class="col-6 mb-3">
                                    <h4>Address 1*</h4>

                                    <label class="form-label">Country</label>
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label mt-2">State</label>
                                    <select name="state" id="state" class="form-control">
                                        <option value="">Select State</option>
                                    </select>

                                    <label class="form-label mt-2">City</label>
                                    <select name="city" id="city" class="form-control">
                                        <option value="">Select City</option>
                                    </select>

                                    <label class="form-label mt-2">Vill.</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror

                                    <label class="form-label mt-2">Zip</label>
                                    <input type="text" name="zip" class="form-control">
                                </div>

                                <div class="col-6 mb-3">
                                    <h4>Sub Address (Optional)</h4>

                                    <label class="form-label">Country</label>
                                    <input type="text" name="sub_country" class="form-control"
                                        value="{{ old('sub_country') }}">

                                    <label class="form-label mt-2">State</label>
                                    <input type="text" name="sub_state" class="form-control"
                                        value="{{ old('sub_state') }}">

                                    <label class="form-label mt-2">City</label>
                                    <input type="text" name="sub_city" class="form-control"
                                        value="{{ old('sub_city') }}">

                                    <label class="form-label mt-2">Village</label>
                                    <input type="text" name="sub_village" class="form-control"
                                        value="{{ old('sub_village') }}">

                                    <label class="form-label mt-2">Zip</label>
                                    <input type="text" name="sub_zip" class="form-control"
                                        value="{{ old('sub_zip') }}">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function addEmail() {
            let container = document.getElementById('email-section');
            let newGroup = document.createElement('div');
            newGroup.classList.add('input-group', 'mb-2');
            newGroup.innerHTML = `
                    <input type="email" name="emails[]" class="form-control">
                    <button type="button" class="btn btn-danger remove-field">Remove</button>
                `;
            container.insertBefore(newGroup, container.lastElementChild);
        }

        function addPhone() {
            let container = document.getElementById('phone-section');
            let newGroup = document.createElement('div');
            newGroup.classList.add('input-group', 'mb-2');
            newGroup.innerHTML = `
                    <input type="text" name="phones[]" class="form-control">
                    <button type="button" class="btn btn-danger remove-field">Remove</button>
                `;
            container.insertBefore(newGroup, container.lastElementChild);
        }

        // Remove field (only if more than one present)
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-field')) {
                let parentSection = e.target.closest('.mb-3');
                let totalFields = parentSection.querySelectorAll('.input-group').length;
                if (totalFields > 1) {
                    e.target.closest('.input-group').remove();
                }
            }
        });
    </script>

    <script>
        // Load States when Country changes
        document.getElementById('country').addEventListener('change', function() {
            let countryId = this.value;

            if (!countryId) return;

            fetch(`{{ url('get-states') }}/${countryId}`)
                .then(res => res.json())
                .then(states => {
                    let stateDropdown = document.getElementById('state');
                    stateDropdown.innerHTML = '<option value="">Select State</option>';

                    states.forEach(state => {
                        stateDropdown.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                    });

                    document.getElementById('city').innerHTML = '<option value="">Select City</option>';
                });
        });

        // Load Cities when State changes
        document.getElementById('state').addEventListener('change', function() {
            let stateId = this.value;

            if (!stateId) return;

            fetch(`{{ url('get-cities') }}/${stateId}`)
                .then(res => res.json())
                .then(cities => {
                    let cityDropdown = document.getElementById('city');
                    cityDropdown.innerHTML = '<option value="">Select City</option>';

                    cities.forEach(city => {
                        cityDropdown.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                });
        });
    </script>
@endsection
