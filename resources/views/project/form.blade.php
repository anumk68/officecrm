@extends('layouts.app')
@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Create Project</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Project</a></li>
                                    <li class="breadcrumb-item active">Create Project</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Create New Project</h4>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="outer-repeater" method="POST" action="{{ route('save.project') }}">
                                    @csrf
                                    <div data-repeater-list="outer-group" class="outer">
                                        <div data-repeater-item class="outer">
                                            <div class="form-group row mb-4">
                                                <label for="date" class="col-form-label col-lg-2">Date</label>
                                                <div class="col-lg-10">
                                                    <input type="date" name="date" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-lg-2">Project</label>
                                                <div class="col-lg-10">
                                                    <textarea type="text" name="project_name" style="height: 100px;"
                                                        class="form-control"
                                                        placeholder="Enter project Description..."></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label class="col-form-label col-lg-2">Deadline</label>
                                                <div class="col-lg-10">
                                                    <div class="input-daterange input-group" data-provide="datepicker">
                                                        <input type="date" name="deadline" class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label for="priority" class="col-form-label col-lg-2">Priority</label>
                                                <div class="col-lg-10">
                                                    <select id="priority" name="priority" class="form-control" required>
                                                        <option value="Low">Low</option>
                                                        <option value="Medium">Medium</option>
                                                        <option value="High">High</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row mb-4">
                                                <label for="assigned_to" class="col-form-label col-lg-2">Assign
                                                    To</label>
                                                <div class="col-lg-10">
                                                    <select id="assigned_to" name="assigned_to"
                                                        class="form-select form-select-sm" required>
                                                        <option value="">-- Assign User --</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->full_name }}
                                                                ({{ $user->role }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-end">
                                        <div class="col-lg-10">
                                            <button type="submit" class="btn btn-primary">Create Project</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection