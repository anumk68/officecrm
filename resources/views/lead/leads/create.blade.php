@extends('layouts.app')

@section('content')

    <div style="padding-top:100px">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                        {{-- Top Bar --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Create Lead</h4>

                        </div>
                        <form action="{{ route('leads.store') }}" method="POST">
                            @csrf

                            <!-- Contact Person -->
                            <div class="mb-3">
                                <label>Contact Person *</label>
                                <select name="contact_person_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach ($contacts as $contact)
                                        @php
                                            $emails = is_array($contact->emails)
                                                ? $contact->emails
                                                : json_decode($contact->emails, true);
                                        @endphp
                                        <option value="{{ $contact->id }}" {{ old('contact_person_id') == $contact->id ? 'selected' : '' }}>
                                            {{ $contact->name . ' - ' . ($emails[0] ?? '') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('contact_person_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Lead Product -->
                            <div class="mb-3">
                                <label>Lead Product *</label>
                                <select name="lead_product_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('lead_product_id') == $product->id ? 'selected' : '' }}> {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('lead_product_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Lead Title -->
                            <div class="mb-3">
                                <label>Lead Title *</label>
                                <input type="text" name="lead_title" value="{{ old('lead_title') }}" class="form-control">
                                @error('lead_title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label>Status *</label>
                                <select name="status" class="form-control">
                                    <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In
                                        Progress</option>
                                    <option value="won" {{ old('status') == 'won' ? 'selected' : '' }}>Won</option>
                                    <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Lead Value -->
                            <div class="mb-3">
                                <label>Lead Value</label>
                                <input type="text" name="lead_value" value="{{ old('lead_value') }}" class="form-control">
                                @error('lead_value')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Source -->
                            <div class="mb-3">
                                <label>Source</label>
                                <input type="text" name="source" value="{{ old('source') }}" class="form-control">
                                @error('source')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Save Lead</button>
                        </form>

                    </div>

                </div>
            </div>
        </main>
    </div>

@endsection