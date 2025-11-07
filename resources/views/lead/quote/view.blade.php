@extends('layouts.app')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="container my-5">

                    <div class="card shadow-lg border-0 rounded-4">
                        <div
                            class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">Quote Details</h2>
                            <div>
                                <a href="{{ route('quotes.index') }}">
                                    <button class="btn btn-light btn-sm me-2">
                                        <i class="bi bi-arrow-left"></i> Back
                                    </button>
                                </a>
                                <button class="btn btn-light btn-sm me-2" onclick="window.print()">
                                    <i class="bi bi-printer"></i> Print
                                </button>
                                <a href="{{ route('quotes.print', $quote->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-download"></i> Download PDF
                                </a>
                            </div>
                        </div>

                        <div class="card-body">

                            {{-- Quote Info --}}
                            <div class="mb-4">
                                <h4 class="text-primary">{{ $quote->subject }}</h4>
                                <p class="text-muted">{{ $quote->description }}</p>
                                <h5 class="fw-bold">Grand Total:
                                    <span class="text-success">₹{{ number_format($quote->grand_total, 2) }}</span>
                                </h5>
                            </div>

                            {{-- Address Section --}}
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h5 class="fw-bold text-secondary">Billing Address</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>Country:</strong>
                                                {{ $quote->billing_address['country'] ?? 'N/A' }}</li>
                                            <li><strong>State:</strong>
                                                {{ $quote->billing_address['state'] ?? 'N/A' }}</li>
                                            <li><strong>City:</strong>
                                                {{ $quote->billing_address['city'] ?? 'N/A' }}</li>
                                            <li><strong>Postcode:</strong>
                                                {{ $quote->billing_address['postcode'] ?? 'N/A' }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded bg-light">
                                        <h5 class="fw-bold text-secondary">Shipping Address</h5>
                                        <ul class="list-unstyled mb-0">
                                            <li><strong>Country:</strong>
                                                {{ $quote->shipping_address['country'] ?? 'N/A' }}</li>
                                            <li><strong>State:</strong>
                                                {{ $quote->shipping_address['state'] ?? 'N/A' }}</li>
                                            <li><strong>City:</strong>
                                                {{ $quote->shipping_address['city'] ?? 'N/A' }}</li>
                                            <li><strong>Postcode:</strong>
                                                {{ $quote->shipping_address['postcode'] ?? 'N/A' }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            {{-- Contact Person --}}
                            <div class="row mt-4">
                                {{-- Contact Person --}}
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Contact Person</h5>
                                    @if ($quote->person)
                                        <p><strong>Name:</strong> {{ $quote->person->name }}</p>
                                        <p><strong>Emails:</strong>
                                            {{ $quote->person->emails ? implode(', ', is_string($quote->person->emails) ? json_decode($quote->person->emails, true) : $quote->person->emails) : 'N/A' }}
                                        </p>
                                        <p><strong>Numbers:</strong>
                                            {{ $quote->person->contact_numbers ? implode(', ', is_string($quote->person->contact_numbers) ? json_decode($quote->person->contact_numbers, true) : $quote->person->contact_numbers) : 'N/A' }}
                                        </p>

                                        </p>

                                    @else
                                        <p class="text-muted">No contact person assigned.</p>
                                    @endif
                                </div>

                                {{-- Lead Info --}}
                                <div class="col-md-6">
                                    <h5 class="text-secondary">Lead Information</h5>
                                    @if ($quote->lead)
                                        <p><strong>Title:</strong> {{ $quote->lead->lead_title }}</p>
                                        <p><strong>Status:</strong> {{ $quote->lead->status }}</p>
                                        <p><strong>Value:</strong>
                                            ₹{{ number_format($quote->lead->lead_value, 2) }}</p>
                                        <p><strong>Source:</strong> {{ $quote->lead->source ?? 'N/A' }}</p>
                                        <p><strong>Created At:</strong>
                                            {{ $quote->lead->created_at->format('d M, Y') }}</p>
                                    @else
                                        <p class="text-muted">No lead linked.</p>
                                    @endif
                                </div>
                            </div>


                            <hr>

                            {{-- Items Table --}}
                            <h5 class="mt-4 text-secondary">Projects</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle shadow-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Discount</th>
                                            <th class="text-end">Tax</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quote->items as $item)
                                            <tr>
                                                <td>{{ $item->project->name }}</td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                                                <td class="text-end">
                                                    ₹{{ number_format($item->discount_amount ?? 0, 2) }}</td>
                                                <td class="text-end">
                                                    ₹{{ number_format($item->tax_amount ?? 0, 2) }}</td>
                                                <td class="text-end">₹{{ number_format($item->total, 2) }}</td>
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
@endsection