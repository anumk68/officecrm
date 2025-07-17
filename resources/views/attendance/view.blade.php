@extends('layouts.app')
@section('content')
    @include('layouts.header')

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div style="padding-top:100px">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <main class="main-content">
                                <div class="row p-4">
                                    <div class="container">
                                        <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul class="mb-0">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            @if(session('success'))
                                                <div class="alert alert-success">{{ session('success') }}</div>
                                            @endif
                                            @if(session('error'))
                                                <div class="alert alert-danger">{{ session('error') }}</div>
                                            @endif
                                            @if(Auth::user()->role !== 'manager')
                                                <div class="mb-3 float-end" style="margin-left: 10px;">
                                                    @if($currentStatus === 'Login')
                                                        <form action="{{ route('employeeLogout') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Logout</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('save.attendance') }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary">Login</button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <a href="{{ route('attendance.history') }}"><button
                                                            class="btn btn-primary">Attendance History</button></a>
                                                </div>
                                            @endif
                                            <div class="container-fluid p-4 border shadow-sm rounded bg-white" style="width: 100%; overflow-x: auto;">
                                                <div id="calendar"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </main>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <div class="card border shadow-sm rounded">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Leaves Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6>Full day : 1</h6>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Sick Leaves : 0</h6>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Half Day Leaves : 1</h6>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Short Day Leave : 1</h6>
                                    </div>
                                    <a href="{{ route('leaves') }}" class="btn btn-outline-primary btn-sm">
                                        View All Your Leaves
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </body>
@endsection