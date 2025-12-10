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

                        <form action="{{ route('leads.store') }}" method="POST" id="leadForm">
                            @csrf

                            <!-- Basic Fields -->
                            <div class="mb-3">
                                <label>Name<span style="color: red">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Email<span style="color: red">*</span></label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Phone <span style="color: red">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control">
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>City</label>
                                <input type="text" name="city" value="{{ old('city') }}" class="form-control">
                                @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label>Platform</label>
                                <select name="platform" class="form-control">
                                    <option value="">Select Platform</option>
                                    <option value="ig" {{ old('platform') == 'ig' ? 'selected' : '' }}>Instagram</option>
                                    <option value="fb" {{ old('platform') == 'fb' ? 'selected' : '' }}>Facebook</option>
                                    <option value="yt" {{ old('platform') == 'yt' ? 'selected' : '' }}>Youtube</option>
                                    <option value="gm" {{ old('platform') == 'gm' ? 'selected' : '' }}>Email</option>
                                    <option value="wp" {{ old('platform') == 'wp' ? 'selected' : '' }}>Whatsapp</option>
                                </select>
                                @error('platform')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Additional Data Fields -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Additional Information</h5>
                                    <button type="button" id="addMoreBtn" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div id="additionalFieldsContainer">
                                    <!-- Dynamic fields will be added here -->
                                    @if(old('additional_fields'))
                                        @foreach(old('additional_fields') as $index => $field)
                                            <div class="row additional-field mb-2" data-index="{{ $index }}">
                                                <div class="col-md-5">
                                                    <input type="text" name="additional_fields[{{ $index }}][label]"
                                                        value="{{ $field['label'] ?? '' }}" class="form-control"
                                                        placeholder="Label (e.g., Company, Industry, etc.)">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="text" name="additional_fields[{{ $index }}][value]"
                                                        value="{{ $field['value'] ?? '' }}" class="form-control"
                                                        placeholder="Value">
                                                </div>
                                                <div class="col-md-2">
                                                    @if($index > 0)
                                                        <button type="button" class="btn btn-danger remove-field-btn">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Default one field -->
                                        <div class="row additional-field mb-2" data-index="0">
                                            <div class="col-md-5">
                                                <input type="text" name="additional_fields[0][label]" class="form-control"
                                                    placeholder="Label (e.g., Company, Industry, etc.)">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" name="additional_fields[0][value]" class="form-control"
                                                    placeholder="Value">
                                            </div>
                                            <div class="col-md-2">
                                                <!-- First field can't be removed -->
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <small class="text-muted">Add as many additional fields as you need</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Lead</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let fieldIndex = {{ old('additional_fields') ? count(old('additional_fields')) : 1 }};
            const container = document.getElementById('additionalFieldsContainer');

            // Add more fields
            document.getElementById('addMoreBtn').addEventListener('click', function () {
                const newField = document.createElement('div');
                newField.className = 'row additional-field mb-2';
                newField.setAttribute('data-index', fieldIndex);
                newField.innerHTML = `
                        <div class="col-md-5">
                            <input type="text" name="additional_fields[${fieldIndex}][label]"
                                   class="form-control"
                                   placeholder="Label (e.g., Company, Industry, etc.)">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="additional_fields[${fieldIndex}][value]"
                                   class="form-control"
                                   placeholder="Value">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-field-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                container.appendChild(newField);
                fieldIndex++;
            });
            container.addEventListener('click', function (e) {
                if (e.target.closest('.remove-field-btn')) {
                    const field = e.target.closest('.additional-field');
                    if (field && field.getAttribute('data-index') !== '0') {
                        field.remove();
                    }
                }
            });
        });
    </script>
    <style>
        .additional-field {
            transition: all 0.3s ease;
        }

        .remove-field-btn {
            width: 100%;
        }
    </style>
@endsection
