@extends('layouts.app')

@section('content')

    <div style="padding-top:100px">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                        <div class="container-fluid">
                            <h4 class="mb-4">Edit Quote</h4>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form id="quoteForm" action="{{ route('quotes.update', $quote->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Subject</label>
                                        <input type="text" name="subject"
                                            class="form-control @error('subject') is-invalid @enderror"
                                            value="{{ old('subject', $quote->subject) }}">
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $quote->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Sales Owner</label>
                                        <select name="sales_owner_id"
                                            class="form-control @error('sales_owner_id') is-invalid @enderror">
                                            <option value="">Select</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ old('sales_owner_id', $quote->user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sales_owner_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Person</label>
                                        <select name="person_id"
                                            class="form-control @error('person_id') is-invalid @enderror">
                                            <option value="">Select</option>
                                            @foreach ($persons as $person)
                                                <option value="{{ $person->id }}"
                                                    {{ old('person_id', $quote->person_id) == $person->id ? 'selected' : '' }}>
                                                    {{ $person->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('person_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Lead</label>
                                        <select name="lead_id" class="form-control @error('lead_id') is-invalid @enderror">
                                            <option value="">Select</option>
                                            @foreach ($leads as $lead)
                                                <option value="{{ $lead->id }}"
                                                    {{ old('lead_id', $quote->lead_id) == $lead->id ? 'selected' : '' }}>
                                                    {{ $lead->lead_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('lead_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <h5>Billing Address</h5>
                                        <select name="billing_country"
                                            class="form-control mb-2 @error('billing_country') is-invalid @enderror">
                                            <option value="">-- Select Country --</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name ?? $country->code }}"
                                                    {{ old('billing_country', $quote->billing_address['country'] ?? '') == ($country->name ?? $country->code) ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input name="billing_state" class="form-control mb-2"
                                            value="{{ old('billing_state', $quote->billing_address['state'] ?? '') }}"
                                            placeholder="State">

                                        <input name="billing_city" class="form-control mb-2"
                                            value="{{ old('billing_city', $quote->billing_address['city'] ?? '') }}"
                                            placeholder="City">

                                        <input name="billing_postcode" class="form-control mb-2"
                                            value="{{ old('billing_postcode', $quote->billing_address['postcode'] ?? '') }}"
                                            placeholder="Postcode">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <h5>Shipping Address</h5>
                                        <select name="shipping_country"
                                            class="form-control mb-2 @error('shipping_country') is-invalid @enderror">
                                            <option value="">-- Select Country --</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->name ?? $country->code }}"
                                                    {{ old('shipping_country', $quote->shipping_address['country'] ?? '') == ($country->name ?? $country->code) ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input name="shipping_state" class="form-control mb-2"
                                            value="{{ old('shipping_state', $quote->shipping_address['state'] ?? '') }}"
                                            placeholder="State">

                                        <input name="shipping_city" class="form-control mb-2"
                                            value="{{ old('shipping_city', $quote->shipping_address['city'] ?? '') }}"
                                            placeholder="City">

                                        <input name="shipping_postcode" class="form-control mb-2"
                                            value="{{ old('shipping_postcode', $quote->shipping_address['postcode'] ?? '') }}"
                                            placeholder="Postcode">
                                    </div>

                                    <h5 class="mt-4">Quote Items</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Project</th>
                                                <th>Qty</th>
                                                <th>Price (₹)</th>
                                                <th>Amount (₹)</th>
                                                <th>Discount %</th>
                                                <th>Discount ₹</th>
                                                <th>Tax %</th>
                                                <th>Tax ₹</th>
                                                <th>Total (₹)</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsTable">
                                            @if (old('items'))
                                                @foreach (old('items') as $index => $oldItem)
                                                    <tr data-index="{{ $index }}">
                                                        <td>
                                                            <select name="items[{{ $index }}][project_id]"
                                                                class="form-control project-select">
                                                                <option value="">-- Select Project --</option>
                                                                @foreach ($leadprojects as $project)
                                                                    <option value="{{ $project->id }}"
                                                                        {{ $oldItem['project_id'] == $project->id ? 'selected' : '' }}>
                                                                        {{ $project->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" min="0"
                                                                name="items[{{ $index }}][qty]"
                                                                class="form-control calc"
                                                                value="{{ $oldItem['qty'] ?? 0 }}"></td>
                                                        <td><input type="number" min="0" step="0.01"
                                                                name="items[{{ $index }}][price]"
                                                                class="form-control calc"
                                                                value="{{ $oldItem['price'] ?? 0 }}"></td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][amount]"
                                                                class="form-control" readonly
                                                                value="{{ $oldItem['amount'] ?? 0 }}"></td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <select name="items[{{ $index }}][discount_select]"
                                                                    class="form-control discount-select">
                                                                    <option value="0">0%</option>
                                                                    <option value="5"
                                                                        {{ ($oldItem['discount_select'] ?? 0) == 5 ? 'selected' : '' }}>
                                                                        5%</option>
                                                                    <option value="10"
                                                                        {{ ($oldItem['discount_select'] ?? 0) == 10 ? 'selected' : '' }}>
                                                                        10%</option>
                                                                    <option value="15"
                                                                        {{ ($oldItem['discount_select'] ?? 0) == 15 ? 'selected' : '' }}>
                                                                        15%</option>
                                                                    <option value="20"
                                                                        {{ ($oldItem['discount_select'] ?? 0) == 20 ? 'selected' : '' }}>
                                                                        20%</option>
                                                                </select>
                                                                <input type="number" min="0" step="0.01"
                                                                    name="items[{{ $index }}][discount_custom]"
                                                                    class="form-control discount-custom"
                                                                    placeholder="or %"
                                                                    value="{{ $oldItem['discount_custom'] ?? '' }}" />
                                                            </div>
                                                            {{-- ✅ ADD THIS HIDDEN FIELD --}}
                                                            <input type="hidden"
                                                                name="items[{{ $index }}][discount_percent]"
                                                                class="discount-percent-value"
                                                                value="{{ $oldItem['discount_percent'] ?? 0 }}">
                                                        </td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][discount_amount]"
                                                                class="form-control" readonly
                                                                value="{{ $oldItem['discount_amount'] ?? 0 }}"></td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <select name="items[{{ $index }}][tax_select]"
                                                                    class="form-control tax-select">
                                                                    <option value="0">0%</option>
                                                                    <option value="5"
                                                                        {{ ($oldItem['tax_select'] ?? 0) == 5 ? 'selected' : '' }}>
                                                                        5%</option>
                                                                    <option value="12"
                                                                        {{ ($oldItem['tax_select'] ?? 0) == 12 ? 'selected' : '' }}>
                                                                        12%</option>
                                                                    <option value="18"
                                                                        {{ ($oldItem['tax_select'] ?? 0) == 18 ? 'selected' : '' }}>
                                                                        18%</option>
                                                                    <option value="28"
                                                                        {{ ($oldItem['tax_select'] ?? 0) == 28 ? 'selected' : '' }}>
                                                                        28%</option>
                                                                </select>
                                                                <input type="number" min="0" step="0.01"
                                                                    name="items[{{ $index }}][tax_custom]"
                                                                    class="form-control tax-custom" placeholder="or %"
                                                                    value="{{ $oldItem['tax_custom'] ?? '' }}" />
                                                            </div>
                                                            {{-- ✅ ADD THIS HIDDEN FIELD --}}
                                                            <input type="hidden"
                                                                name="items[{{ $index }}][tax_percent]"
                                                                class="tax-percent-value"
                                                                value="{{ $oldItem['tax_percent'] ?? 0 }}">
                                                        </td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][tax_amount]"
                                                                class="form-control" readonly
                                                                value="{{ $oldItem['tax_amount'] ?? 0 }}"></td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][total]"
                                                                class="form-control" readonly
                                                                value="{{ $oldItem['total'] ?? 0 }}"></td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger deleteRow"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($quote->items as $index => $item)
                                                    <tr data-index="{{ $index }}">
                                                        <td>
                                                            <select name="items[{{ $index }}][project_id]"
                                                                class="form-control project-select">
                                                                <option value="">-- Select Project --</option>
                                                                @foreach ($leadprojects as $project)
                                                                    <option value="{{ $project->id }}"
                                                                        {{ $item->project_id == $project->id ? 'selected' : '' }}>
                                                                        {{ $project->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" min="0"
                                                                name="items[{{ $index }}][qty]"
                                                                class="form-control calc" value="{{ $item->quantity }}">
                                                        </td>
                                                        <td><input type="number" min="0" step="0.01"
                                                                name="items[{{ $index }}][price]"
                                                                class="form-control calc" value="{{ $item->price }}">
                                                        </td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][amount]"
                                                                class="form-control" readonly
                                                                value="{{ $item->amount }}"></td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <select name="items[{{ $index }}][discount_select]"
                                                                    class="form-control discount-select">
                                                                    <option value="0">0%</option>
                                                                    <option value="5"
                                                                        {{ ($item->discount_percent ?? 0) == 5 ? 'selected' : '' }}>
                                                                        5%</option>
                                                                    <option value="10"
                                                                        {{ ($item->discount_percent ?? 0) == 10 ? 'selected' : '' }}>
                                                                        10%</option>
                                                                    <option value="15"
                                                                        {{ ($item->discount_percent ?? 0) == 15 ? 'selected' : '' }}>
                                                                        15%</option>
                                                                    <option value="20"
                                                                        {{ ($item->discount_percent ?? 0) == 20 ? 'selected' : '' }}>
                                                                        20%</option>
                                                                </select>
                                                                <input type="number" min="0" step="0.01"
                                                                    name="items[{{ $index }}][discount_custom]"
                                                                    class="form-control discount-custom"
                                                                    placeholder="or %"
                                                                    value="{{ !in_array($item->discount_percent ?? 0, [0, 5, 10, 15, 20]) ? $item->discount_percent ?? '' : '' }}" />
                                                            </div>
                                                            <input type="hidden"
                                                                name="items[{{ $index }}][discount_percent]"
                                                                class="discount-percent-value"
                                                                value="{{ $item->discount_percent ?? 0 }}">
                                                        </td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][discount_amount]"
                                                                class="form-control" readonly
                                                                value="{{ $item->discount_amount }}"></td>
                                                        <td>
                                                            <div class="d-flex gap-2">
                                                                <select name="items[{{ $index }}][tax_select]"
                                                                    class="form-control tax-select">
                                                                    <option value="0">0%</option>
                                                                    <option value="5"
                                                                        {{ ($item->tax_percent ?? 0) == 5 ? 'selected' : '' }}>
                                                                        5%</option>
                                                                    <option value="12"
                                                                        {{ ($item->tax_percent ?? 0) == 12 ? 'selected' : '' }}>
                                                                        12%</option>
                                                                    <option value="18"
                                                                        {{ ($item->tax_percent ?? 0) == 18 ? 'selected' : '' }}>
                                                                        18%</option>
                                                                    <option value="28"
                                                                        {{ ($item->tax_percent ?? 0) == 28 ? 'selected' : '' }}>
                                                                        28%</option>
                                                                </select>
                                                                <input type="number" min="0" step="0.01"
                                                                    name="items[{{ $index }}][tax_custom]"
                                                                    class="form-control tax-custom" placeholder="or %"
                                                                    value="{{ !in_array($item->tax_percent ?? 0, [0, 5, 12, 18, 28]) ? $item->tax_percent ?? '' : '' }}" />
                                                            </div>
                                                            <input type="hidden"
                                                                name="items[{{ $index }}][tax_percent]"
                                                                class="tax-percent-value"
                                                                value="{{ $item->tax_percent ?? 0 }}">
                                                        </td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][tax_amount]"
                                                                class="form-control" readonly
                                                                value="{{ $item->tax_amount }}"></td>
                                                        <td><input type="number" step="0.01"
                                                                name="items[{{ $index }}][total]"
                                                                class="form-control" readonly
                                                                value="{{ $item->total }}"></td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger deleteRow"><i
                                                                    class="fas fa-trash-alt"></i></button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                    <button type="button" id="addRow" class="btn btn-sm btn-primary">+ Add
                                        Item</button>

                                    <div class="card mb-3 mt-3">
                                        <div class="card-body text-end">
                                            <p>Sub Total: <span id="subTotal">₹0.00</span></p>
                                            <p>Discount: <span id="discountTotal">₹0.00</span></p>
                                            <p>Tax: <span id="taxTotal">₹0.00</span></p>
                                            <h5>Grand Total: <span id="grandTotal">₹0.00</span></h5>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Update Quote</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let leadProjects = @json($leadprojects);
        let rowIndex = {{ old('items') ? count(old('items')) : $quote->items->count() }};

        function readPercent(row, selectSelector, customSelector) {
            const customEl = row.querySelector(customSelector);
            const selEl = row.querySelector(selectSelector);
            let customVal = parseFloat(customEl?.value);
            if (!isNaN(customVal) && customVal !== 0) {
                return customVal;
            }
            return parseFloat(selEl?.value) || 0;
        }

        function calculateRow(row) {
            let qty = parseFloat(row.querySelector('[name*="[qty]"]').value) || 0;
            let price = parseFloat(row.querySelector('[name*="[price]"]').value) || 0;
            let amount = qty * price;

            // Get discount percentage
            let discountPercent = readPercent(row, '.discount-select', '.discount-custom');
            let discountAmount = amount * (discountPercent / 100);

            // Get taxable amount
            let taxable = amount - discountAmount;

            // Get tax percentage
            let taxPercent = readPercent(row, '.tax-select', '.tax-custom');
            let taxAmount = taxable * (taxPercent / 100);

            // Calculate total
            let total = taxable + taxAmount;

            // Helper to set field values
            const setField = (selector, value) => {
                const el = row.querySelector(selector);
                if (el) el.value = Number(value || 0).toFixed(2);
            };

            // Set calculated amounts
            setField('[name*="[amount]"]', amount);
            setField('[name*="[discount_amount]"]', discountAmount);
            setField('[name*="[tax_amount]"]', taxAmount);
            setField('[name*="[total]"]', total);

            // ✅ UPDATE HIDDEN PERCENTAGE FIELDS
            const discountPercentField = row.querySelector('.discount-percent-value');
            if (discountPercentField) {
                discountPercentField.value = discountPercent.toFixed(2);
            }

            const taxPercentField = row.querySelector('.tax-percent-value');
            if (taxPercentField) {
                taxPercentField.value = taxPercent.toFixed(2);
            }

            calculateTotals();
        }

        function calculateTotals() {
            let subTotal = 0,
                discountTotal = 0,
                taxTotal = 0,
                grandTotal = 0;

            document.querySelectorAll('#itemsTable tr').forEach(row => {
                let amount = parseFloat(row.querySelector('[name*="[amount]"]').value) || 0;
                let discount = parseFloat(row.querySelector('[name*="[discount_amount]"]').value) || 0;
                let tax = parseFloat(row.querySelector('[name*="[tax_amount]"]').value) || 0;
                let total = parseFloat(row.querySelector('[name*="[total]"]').value) || 0;

                subTotal += amount;
                discountTotal += discount;
                taxTotal += tax;
                grandTotal += total;
            });

            document.getElementById('subTotal').innerText = "₹" + subTotal.toFixed(2);
            document.getElementById('discountTotal').innerText = "₹" + discountTotal.toFixed(2);
            document.getElementById('taxTotal').innerText = "₹" + taxTotal.toFixed(2);
            document.getElementById('grandTotal').innerText = "₹" + grandTotal.toFixed(2);
        }

        function attachRowListeners(row) {
            row.querySelectorAll(
                'input.calc, input.discount-custom, select.discount-select, input.tax-custom, select.tax-select, select.project-select, input[name*="[qty]"], input[name*="[price]"]'
            ).forEach(el => {
                el.addEventListener('input', () => calculateRow(row));
                el.addEventListener('change', () => calculateRow(row));
            });

            const del = row.querySelector('.deleteRow');
            if (del) {
                del.addEventListener('click', function() {
                    row.remove();
                    calculateTotals();
                });
            }
        }

        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.getElementById('itemsTable');
            const idx = rowIndex++;
            const tr = document.createElement('tr');
            tr.setAttribute('data-index', idx);

            let projectOptions = `<option value="">-- Select Project --</option>`;
            leadProjects.forEach(p => {
                projectOptions += `<option value="${p.id}">${p.name}</option>`;
            });

            tr.innerHTML = `
            <td>
                <select name="items[${idx}][project_id]" class="form-control project-select">
                    ${projectOptions}
                </select>
            </td>
            <td><input type="number" min="0" name="items[${idx}][qty]" class="form-control calc" value="0"></td>
            <td><input type="number" min="0" step="0.01" name="items[${idx}][price]" class="form-control calc" value="0.00"></td>
            <td><input type="number" step="0.01" name="items[${idx}][amount]" class="form-control" readonly></td>
            <td>
                <div class="d-flex gap-2">
                    <select name="items[${idx}][discount_select]" class="form-control discount-select">
                        <option value="0">0%</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="15">15%</option>
                        <option value="20">20%</option>
                    </select>
                    <input type="number" min="0" step="0.01" name="items[${idx}][discount_custom]" class="form-control discount-custom" placeholder="or %" />
                </div>
                <input type="hidden" name="items[${idx}][discount_percent]" class="discount-percent-value">
            </td>
            <td><input type="number" step="0.01" name="items[${idx}][discount_amount]" class="form-control" readonly></td>
            <td>
                <div class="d-flex gap-2">
                    <select name="items[${idx}][tax_select]" class="form-control tax-select">
                        <option value="0">0%</option>
                        <option value="5">5%</option>
                        <option value="12">12%</option>
                        <option value="18">18%</option>
                        <option value="28">28%</option>
                    </select>
                    <input type="number" min="0" step="0.01" name="items[${idx}][tax_custom]" class="form-control tax-custom" placeholder="or %" />
                </div>
                <input type="hidden" name="items[${idx}][tax_percent]" class="tax-percent-value">
            </td>
            <td><input type="number" step="0.01" name="items[${idx}][tax_amount]" class="form-control" readonly></td>
            <td><input type="number" step="0.01" name="items[${idx}][total]" class="form-control" readonly></td>
            <td>
                <button type="button" class="btn btn-sm btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button>
            </td>
        `;

            tbody.appendChild(tr);
            attachRowListeners(tr);
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#itemsTable tr').forEach(row => {
                attachRowListeners(row);
                calculateRow(row);
            });
        });

        document.getElementById('quoteForm').addEventListener('submit', function(e) {
            document.querySelectorAll('#itemsTable tr').forEach(row => calculateRow(row));
            const validRowExists = Array.from(document.querySelectorAll('#itemsTable tr')).some(row => {
                const qty = parseFloat(row.querySelector('[name*="[qty]"]').value) || 0;
                const price = parseFloat(row.querySelector('[name*="[price]"]').value) || 0;
                return qty > 0 && price > 0;
            });
            if (!validRowExists) {
                e.preventDefault();
                alert('Please enter qty and price for at least one item.');
                return false;
            }
        });
    </script>

@endsection
