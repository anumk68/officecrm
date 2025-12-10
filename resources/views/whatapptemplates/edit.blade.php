@extends('layouts.app')

@section('title', 'Edit WhatsApp Template')

@section('content')
  <div style="padding-top:100px;">
    <main class="main-content">
        <div class="row p-4">
            <div class="container">
                <div class="container-fluid p-4 border shadow-sm rounded bg-primary text-white">
                    <h4 class="mb-0">Edit Whatsapp Template</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Edit Form --}}
                    <form action="{{ route('whatapptemplates.update', $template->id) }}" method="POST">
                        @csrf
                        @method('PUT')  <!-- Use PUT for updating -->

                        <div class="mb-3">
                            <label class="form-label fw-bold">Template Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $template->name) }}" placeholder="e.g. Welcome Email" required>
                        </div>

                      
                        <div class="mb-3">
                            <label class="form-label fw-bold">Body</label>
                            <textarea id="editor" name="body" class="form-control" rows="8" placeholder="Email HTML or plain text..." required>{{ old('body', $template->body) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('templates.index') }}" class="btn btn-secondary">← Back</a>
                            <button type="submit" class="btn btn-success"> Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
  </div>

{{-- ✅ Include CKEditor (WYSIWYG) --}}
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
@endsection
