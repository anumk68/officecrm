@extends('layouts.app')
@section('content')
    @include('layouts.header')

    <body data-topbar="dark">

        <div id="layout-wrapper">
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
                                @if(Auth::user()->role !== 'manager')
                                    <div class="mb-3 float-end">
                                        <a href="{{ route('create.leave') }}"><button class="btn btn-primary">
                                                Add Leave</button></a>
                                    </div>
                                @endif
                                @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div> @endif
                                <table id="leavesTable" class="display table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Leave Type</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            @if(Auth::user()->role !== 'manager')
                                                <th>Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaves as $item)
                                            <tr>
                                                <td>{{ $item->user->full_name }}</td>
                                                <td>{{ $item->date_from }}</td>
                                                <td>{{ $item->date_to }}</td>
                                                <td>{{ $item->leave_type }}</td>
                                                <td>{{ $item->reason }}</td>
                                                <td> @if(Auth::user()->role === 'manager')
                                                    <form action="{{ route('leave.updateStatus', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status" onchange="this.form.submit()"
                                                            class="form-select form-select-sm">
                                                            <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="accepted" {{ $item->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                            <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                        </select>
                                                    </form>
                                                @else
                                                        {{ ucfirst($item->status) }}
                                                    @endif
                                                </td>
                                                @if(Auth::user()->role !== 'manager')
                                                    <td>
                                                        <form action="{{ route('delete.leave', $item->id) }}" method="POST"
                                                            style="display:inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Delete this item?')">Delete</button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
    </body>
@endsection