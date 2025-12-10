@extends('layouts.app')

@section('content')

    <div style="padding-top:100px ">
        <main class="main-content">
            @yield('content')
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded  bg-white ">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('save.attendance') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label>Login At</label>
                                <input type="time" name="login_time" class="form-control" required>
                            </div>
                            <button class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection