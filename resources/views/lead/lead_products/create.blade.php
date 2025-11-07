@extends('layouts.app')

@section('content')

    <div style="padding-top:100px">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                        {{-- Top Bar --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4>Create Project</h4>
                            <button form="productForm" class="btn btn-primary">Save Project</button>
                        </div>

                        {{-- Form --}}
                        <form id="productForm" action="{{ route('lead-products.store') }}" method="POST">
                            @csrf

                            <!-- Product Name -->
                            <div class="mb-3">
                                <label class="form-label">Project Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="mb-3">
                                <label class="form-label">Price *</label>
                                <input type="text" name="price" class="form-control" value="{{ old('price') }}">
                                @error('price')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </main>
    </div>

@endsection
