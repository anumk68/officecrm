@extends('layouts.app')

@section('content')

    <div style="padding-top:100px ">
        <main class="main-content">
            @yield('content')
            <div class="row p-4">
                <div class="container">

                    <div class="container-fluid p-4 border shadow-sm rounded  bg-white ">
                     <h3><b> Add Leave </b></h3>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('save.leave') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label>Date From</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Date To</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Leave Type</label>
                                <select name="leave_type" class="form-select"> select leave type
                                    <option value="Full Day Leave" selected>Full Day Leave</option>
                                    <option value="Half Day Leave">Half Day Leave</option>
                                    <option value="Short Leave">Short Leave</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-group">Reason</label>
                                <textarea name="reason" id="reson" class="form-control"></textarea>
                            </div>
                            <button class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("date_from").setAttribute("min", today);
            document.getElementById("date_to").setAttribute("min", today);
        });
    </script>

@endsection
