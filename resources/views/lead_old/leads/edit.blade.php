@extends('layouts.app')

@section('content')

    <div style="padding-top:100px">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                        {{-- Top Bar --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Edit Lead</h4>
                        </div>

                        <form action="{{ route('leads.update', $lead->id) }}" method="POST">
                            @csrf
                            @method('PUT')

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
                                        <option value="{{ $contact->id }}" {{ old('contact_person_id', $lead->contact_person_id) == $contact->id ? 'selected' : '' }}>
                                            {{ $contact->name . ' - ' . $emails[0] ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Lead Product -->
                            <div class="mb-3">
                                <label>Lead Product *</label>
                                <select name="lead_product_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('lead_product_id', $lead->lead_product_id) == $product->id ? 'selected' : '' }}> {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Lead Title -->
                            <div class="mb-3">
                                <label>Lead Title *</label>
                                <input type="text" name="lead_title" class="form-control"
                                    value="{{ old('lead_title', $lead->lead_title) }}">
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label>Status *</label>
                                <select name="status" class="form-control">
                                    <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New
                                    </option>
                                    <option value="in_progress" {{ old('status', $lead->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="won" {{ old('status', $lead->status) == 'won' ? 'selected' : '' }}>Won
                                    </option>
                                    <option value="lost" {{ old('status', $lead->status) == 'lost' ? 'selected' : '' }}>Lost
                                    </option>
                                </select>
                            </div>

                            <!-- Lead Value -->
                            <div class="mb-3">
                                <label>Lead Value</label>
                                <input type="text" name="lead_value" class="form-control"
                                    value="{{ old('lead_value', $lead->lead_value) }}">
                            </div>

                            <!-- Source -->
                            <div class="mb-3">
                                <label>Source</label>
                                <input type="text" name="source" class="form-control"
                                    value="{{ old('source', $lead->source) }}">
                            </div>

                            <!-- Notes -->
                            <div class="mb-3">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control">{{ old('notes', $lead->notes) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Lead</button>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>
@endsection