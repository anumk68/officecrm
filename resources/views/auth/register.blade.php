  @include('layouts.app')

{{-- @section('content') --}}
<main class="auth-minimal-wrapper">
    <div class="auth-minimal-inner">
        <div class="minimal-card-wrapper">
            <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                    <img src="{{ asset('admin/images/logo-abbr.png') }}" alt="" class="img-fluid">
                </div>
                <div class="card-body p-sm-5">
                    <h2 class="fs-20 fw-bolder mb-4">Register</h2>
                    <h4 class="fs-13 fw-bold mb-2">Manage all your Duralux crm</h4>
                    <p class="fs-12 fw-medium text-muted">Let's get you all setup, so you can verify your personal account and begine setting up your profile.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="w-100 mt-4 pt-2">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="full_name" class="form-control" placeholder="Full Name" value="{{ old('full_name') }}" required>
                        </div>
                        <div class="mb-4">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-4">
                            <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}" required>
                        </div>
                        <div class="mb-4">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="mb-4">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                        </div>

                        <!-- Profile Picture Upload -->
                        <div class="mb-4">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_pic" class="form-control">
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="fw-bold mb-3">Select Role</label><br>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="manager" value="manager" {{ old('role') == 'manager' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="manager">Manager</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="team_leader" value="team_leader" {{ old('role') == 'team_leader' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="team_leader">Team Leader</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="role" id="team_member" value="team_member" {{ old('role') == 'team_member' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="team_member">Team Member</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Create Account</button>
                        </div>
                    </form>
                    <div class="mt-5 text-muted">
                        <span>Already have an account?</span>
                        <a href="{{ route('login') }}" class="fw-bold">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
{{-- @endsection --}}
