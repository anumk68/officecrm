@extends('layouts.app')

@section('content')
@php
    // load countries & dependent lists for prefill (works even if controller didn't pass them)
    $countries = \App\Models\Country::all();
    $states = $contact->country ? \App\Models\State::where('countryId', $contact->country)->get() : collect();
    $cities = $contact->state ? \App\Models\City::where('stateId', $contact->state)->get() : collect();

    $subStates = $contact->sub_country ? \App\Models\State::where('countryId', $contact->sub_country)->get() : collect();
    $subCities = $contact->sub_state ? \App\Models\City::where('stateId', $contact->sub_state)->get() : collect();
@endphp

<div style="padding-top:100px">
    <main class="main-content">
        <div class="row p-4">
            <div class="container">
                <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                    {{-- Top Bar --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Edit Contact</h4>
                        <button form="employeeForm" class="btn btn-primary">Update Contact</button>
                    </div>

                    {{-- Form --}}
                    <form id="employeeForm" action="{{ route('contacts.update', $contact->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $contact->name) }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- Emails --}}
                        <div class="mb-3" id="emails-section">
                            <label class="form-label">Emails *</label>

                            @php
                                $emails = is_array($contact->emails) ? $contact->emails : (json_decode($contact->emails, true) ?? []);
                                $oldEmails = old('emails', $emails);
                            @endphp

                            @foreach($oldEmails as $index => $email)
                                <div class="input-group mb-2 email-group">
                                    <input type="email" name="emails[]" class="form-control" value="{{ $email }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">Remove</button>
                                </div>
                            @endforeach

                            @if(empty($oldEmails))
                                <div class="input-group mb-2 email-group">
                                    <input type="email" name="emails[]" class="form-control">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">Remove</button>
                                </div>
                            @endif

                            @error('emails') <small class="text-danger">{{ $message }}</small> @enderror
                            @error('emails.*') <small class="text-danger">{{ $message }}</small> @enderror

                            <div><span class="text-primary" style="cursor:pointer" onclick="addEmail()">+ Add More</span></div>
                        </div>

                        {{-- Contact Numbers --}}
                        <div class="mb-3" id="phones-section">
                            <label class="form-label">Contact Numbers</label>

                            @php
                                $phones = is_array($contact->contact_numbers) ? $contact->contact_numbers : (json_decode($contact->contact_numbers, true) ?? []);
                                $oldPhones = old('contact_numbers', $phones);
                            @endphp

                            @foreach($oldPhones as $index => $phone)
                                <div class="input-group mb-2 phone-group">
                                    <input type="text" name="contact_numbers[]" class="form-control" value="{{ $phone }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">Remove</button>
                                </div>
                            @endforeach

                            @if(empty($oldPhones))
                                <div class="input-group mb-2 phone-group">
                                    <input type="text" name="contact_numbers[]" class="form-control">
                                    <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">Remove</button>
                                </div>
                            @endif

                            @error('contact_numbers.*') <small class="text-danger">{{ $message }}</small> @enderror

                            <div><span class="text-primary" style="cursor:pointer" onclick="addPhone()">+ Add More</span></div>
                        </div>

                        <hr>

                        {{-- MAIN ADDRESS --}}
                        <h5>Main Address</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Country *</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}" {{ (int)old('country', $contact->country) === $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">State *</label>
                                <select name="state" id="state" class="form-control">
                                    <option value="">Select State</option>
                                    @foreach($states as $s)
                                        <option value="{{ $s->id }}" {{ (int)old('state', $contact->state) === $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">City *</label>
                                <select name="city" id="city" class="form-control">
                                    <option value="">Select City</option>
                                    @foreach($cities as $ct)
                                        <option value="{{ $ct->id }}" {{ (int)old('city', $contact->city) === $ct->id ? 'selected' : '' }}>
                                            {{ $ct->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Village / Address *</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $contact->village) }}">
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">ZIP</label>
                                <input type="text" name="zip" class="form-control" value="{{ old('zip', $contact->zip) }}">
                                @error('zip') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <hr>

                        {{-- SUB ADDRESS --}}
                        <h5>Sub Address (Optional)</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Country</label>
                                <select name="sub_country" id="sub_country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}" {{ (int)old('sub_country', $contact->sub_country) === $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_country') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">State</label>
                                <select name="sub_state" id="sub_state" class="form-control">
                                    <option value="">Select State</option>
                                    @foreach($subStates as $s)
                                        <option value="{{ $s->id }}" {{ (int)old('sub_state', $contact->sub_state) === $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_state') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">City</label>
                                <select name="sub_city" id="sub_city" class="form-control">
                                    <option value="">Select City</option>
                                    @foreach($subCities as $ct)
                                        <option value="{{ $ct->id }}" {{ (int)old('sub_city', $contact->sub_city) === $ct->id ? 'selected' : '' }}>
                                            {{ $ct->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_city') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Village</label>
                                <input type="text" name="sub_village" class="form-control" value="{{ old('sub_village', $contact->sub_village) }}">
                                @error('sub_village') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">ZIP</label>
                                <input type="text" name="sub_zip" class="form-control" value="{{ old('sub_zip', $contact->sub_zip) }}">
                                @error('sub_zip') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- scripts --}}
<script>
    // Add/remove dynamic email/phone fields
    function addEmail() {
        const container = document.getElementById('emails-section');
        const newGroup = document.createElement('div');
        newGroup.className = 'input-group mb-2 email-group';
        newGroup.innerHTML = `
            <input type="email" name="emails[]" class="form-control">
            <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">Remove</button>
        `;
        // insert before the "+ Add More" link (last child)
        container.insertBefore(newGroup, container.querySelector('div')?.nextSibling || null);
    }

    function addPhone() {
        const container = document.getElementById('phones-section');
        const newGroup = document.createElement('div');
        newGroup.className = 'input-group mb-2 phone-group';
        newGroup.innerHTML = `
            <input type="text" name="contact_numbers[]" class="form-control">
            <button type="button" class="btn btn-outline-danger" onclick="removeField(this)">Remove</button>
        `;
        container.insertBefore(newGroup, container.querySelector('div')?.nextSibling || null);
    }

    function removeField(btn) {
        const group = btn.closest('.input-group');
        const container = group.closest('.mb-3');
        const total = container.querySelectorAll('.input-group').length;
        if (total > 1) group.remove();
        else alert('At least one field is required.');
    }

    // AJAX load states/cities for main and sub addresses
    document.addEventListener('DOMContentLoaded', () => {
        const countryEl = document.getElementById('country');
        const stateEl = document.getElementById('state');
        const cityEl = document.getElementById('city');

        const subCountryEl = document.getElementById('sub_country');
        const subStateEl = document.getElementById('sub_state');
        const subCityEl = document.getElementById('sub_city');

        countryEl?.addEventListener('change', function() {
            const countryId = this.value;
            stateEl.innerHTML = '<option value="">Loading...</option>';
            cityEl.innerHTML = '<option value="">Select City</option>';

            if (!countryId) {
                stateEl.innerHTML = '<option value="">Select State</option>';
                return;
            }

            fetch(`{{ url('get-states') }}/${countryId}`)
                .then(res => res.json())
                .then(states => {
                    stateEl.innerHTML = '<option value="">Select State</option>';
                    states.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = s.name;
                        stateEl.appendChild(opt);
                    });
                })
                .catch(() => {
                    stateEl.innerHTML = '<option value="">Select State</option>';
                });
        });

        stateEl?.addEventListener('change', function() {
            const stateId = this.value;
            cityEl.innerHTML = '<option value="">Loading...</option>';
            if (!stateId) {
                cityEl.innerHTML = '<option value="">Select City</option>';
                return;
            }
            fetch(`{{ url('get-cities') }}/${stateId}`)
                .then(res => res.json())
                .then(cities => {
                    cityEl.innerHTML = '<option value="">Select City</option>';
                    cities.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = c.name;
                        cityEl.appendChild(opt);
                    });
                })
                .catch(() => {
                    cityEl.innerHTML = '<option value="">Select City</option>';
                });
        });

        // SUB address listeners
        subCountryEl?.addEventListener('change', function() {
            const countryId = this.value;
            subStateEl.innerHTML = '<option value="">Loading...</option>';
            subCityEl.innerHTML = '<option value="">Select City</option>';

            if (!countryId) {
                subStateEl.innerHTML = '<option value="">Select State</option>';
                return;
            }

            fetch(`{{ url('get-states') }}/${countryId}`)
                .then(res => res.json())
                .then(states => {
                    subStateEl.innerHTML = '<option value="">Select State</option>';
                    states.forEach(s => {
                        const opt = document.createElement('option');
                        opt.value = s.id;
                        opt.textContent = s.name;
                        subStateEl.appendChild(opt);
                    });
                })
                .catch(() => {
                    subStateEl.innerHTML = '<option value="">Select State</option>';
                });
        });

        subStateEl?.addEventListener('change', function() {
            const stateId = this.value;
            subCityEl.innerHTML = '<option value="">Loading...</option>';
            if (!stateId) {
                subCityEl.innerHTML = '<option value="">Select City</option>';
                return;
            }
            fetch(`{{ url('get-cities') }}/${stateId}`)
                .then(res => res.json())
                .then(cities => {
                    subCityEl.innerHTML = '<option value="">Select City</option>';
                    cities.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value = c.id;
                        opt.textContent = c.name;
                        subCityEl.appendChild(opt);
                    });
                })
                .catch(() => {
                    subCityEl.innerHTML = '<option value="">Select City</option>';
                });
        });
    });
</script>
@endsection
