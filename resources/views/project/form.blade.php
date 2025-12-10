    @extends('layouts.app')

    @section('content')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <a href="{{ route('projects') }}" class="btn btn-secondary btn-sm me-2">
                                        ← Back
                                    </a>

                                    <h4 class="mb-sm-0 font-size-18 ">Create New Project</h4>

                                </div>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="#">Project</a></li>
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

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('save.project') }}">
                                        @csrf

                                        <!-- =============================
                                                                BASIC PROJECT INFO
                                                            ============================== -->
                                        <h4 class="mb-3">Basic Project Information</h4>
                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Select Lead</label>
                                            <div class="col-lg-10">
                                                <select name="lead_id" id="lead_id" class="form-control">
                                                    <option value="">Select Lead</option>

                                                    @foreach ($approvedLeads as $lead)
                                                        <option value="{{ $lead->id }}"
                                                            {{ old('lead_id') == $lead->id ? 'selected' : '' }}>
                                                            {{ $lead->lead_id }} - {{ $lead->full_name }}
                                                            ({{ $lead->city }})
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Project Name</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="project_name" class="form-control"
                                                    value="{{ old('project_name') }}" placeholder="Enter project name">
                                            </div>
                                     

                                            <label class="col-form-label col-lg-2">Company Name</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="company_name" class="form-control"
                                                    value="{{ old('company_name') }}" placeholder="Auto-filled from lead">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Service Type</label>
                                            <div class="col-lg-4">
                                                <select name="service_type" id="service_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Website Development">Website Development</option>
                                                    <option value="Application Development">Application Development</option>
                                                    <option value="Software Development">Software Development</option>
                                                    <option value="Digital Services">Digital Services</option>
                                                    <option value="other">Other</option>
                                                </select>

                                                <!-- Custom Field (Not in DB) -->
                                                <input type="text" name="service_type_custom" id="service_type_other"
                                                    class="form-control mt-2 d-none"
                                                    placeholder="Enter custom service type">
                                            </div>

                                            <label class="col-form-label col-lg-2">Sub-Service</label>
                                            <div class="col-lg-4">
                                                <select name="sub_service" id="sub_service" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="E-commerce">E-commerce</option>
                                                    <option value="Business Website">Business Website</option>
                                                    <option value="Android App">Android App</option>
                                                    <option value="iOS App">iOS App</option>
                                                    <option value="Custom Features">Custom Features</option>
                                                    <option value="API Integrations">API Integrations</option>
                                                    <option value="other">Other</option>
                                                </select>

                                                <!-- Custom Field (Not in DB) -->
                                                <input type="text" name="sub_service_custom" id="sub_service_other"
                                                    class="form-control mt-2 d-none" placeholder="Enter custom sub-service">
                                            </div>
                                        </div>


                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Deadline</label>
                                            <div class="col-lg-4">
                                                <input type="date" name="deadline" class="form-control"
                                                    value="{{ old('deadline') }}">
                                            </div>

                                            <label class="col-form-label col-lg-2">Priority</label>
                                            <div class="col-lg-4">
                                                <select name="priority" class="form-control">
                                                    <option value="Low">Low</option>
                                                    <option value="Medium">Medium</option>
                                                    <option value="High">High</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Status</label>
                                            <div class="col-lg-4">
                                                <select name="status" class="form-control">
                                                    <option value="active">Active</option>
                                                    <option value="done">Done</option>
                                                    <option value="on-hold">Hold</option>
                                                </select>
                                            </div>

                                            <label class="col-form-label col-lg-2">Project Color</label>
                                            <div class="col-lg-4">
                                                <input type="color" name="color" class="form-control"
                                                    style="height: 40px;" value="#276CDC" value="{{ old('color') }}">
                                            </div>
                                        </div>


                                        <!-- =============================
                                                                DOMAIN DETAILS
                                                            ============================== -->
                                        <hr>
                                        <h4 class="mb-3">Domain Details</h4>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Domain Name</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="domain_name" class="form-control"
                                                    value="{{ old('domain_name') }}" placeholder="Enter domain name">
                                            </div>

                                            <label class="col-form-label col-lg-2">Domain Registrar</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="domain_registrar" class="form-control"
                                                    value="{{ old('domain_registrar') }}"
                                                    placeholder="GoDaddy, Namecheap...">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Domain Expiry</label>
                                            <div class="col-lg-10">
                                                <input type="date" name="domain_expiry" class="form-control"
                                                    value="{{ old('domain_expiry') }}">
                                            </div>
                                        </div>


                                        <!-- =============================
                                                                HOSTING DETAILS
                                                            ============================== -->
                                        <hr>
                                        <h4 class="mb-3">Hosting Details</h4>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Hosting Provider</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="hosting_provider" class="form-control"
                                                    placeholder="Hostinger, AWS, Bluehost..."
                                                    value="{{ old('hosting_provider') }}">
                                            </div>

                                            <label class="col-form-label col-lg-2">Server Type</label>
                                            <div class="col-lg-4">
                                                <select name="server_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Shared">Shared</option>
                                                    <option value="VPS">VPS</option>
                                                    <option value="Cloud">Cloud</option>
                                                    <option value="Dedicated">Dedicated</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Hosting Expiry</label>
                                            <div class="col-lg-10">
                                                <input type="date" name="hosting_expiry" class="form-control"
                                                    value="{{ old('hosting_expiry') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">cPanel / Login URL</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="cpanel_url" class="form-control"
                                                    value="{{ old('cpanel_url') }}"
                                                    placeholder="https://example.com:2083">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Username</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="cpanel_username" class="form-control"
                                                    value="{{ old('cpanel_username') }}">
                                            </div>

                                            <label class="col-form-label col-lg-2">Password</label>
                                            <div class="col-lg-4">
                                                <input type="password" name="cpanel_password" class="form-control"
                                                    value="{{ old('cpanel_password') }}">
                                            </div>
                                        </div>


                                        <!-- =============================
                                                            CONFIDENTIAL - EMAIL & LOGIN DETAILS
                                                            ============================== -->
                                        <hr>
                                        <h4 class="mb-3 text-danger">Confidential Credentials (Only Team Leader Access)
                                        </h4>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Project Email</label>
                                            <div class="col-lg-4">
                                                <input type="email" name="project_email" class="form-control"
                                                    placeholder="project@example.com" value="{{ old('project_email') }}">
                                            </div>

                                            <label class="col-form-label col-lg-2">Email Password</label>
                                            <div class="col-lg-4">
                                                <input type="password" name="project_email_password" class="form-control"
                                                    value="{{ old('project_email_password') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">SMTP Host</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="smtp_host" class="form-control"
                                                    value="{{ old('smtp_host') }}">
                                            </div>

                                            <label class="col-form-label col-lg-2">SMTP Port</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="smtp_port" class="form-control"
                                                    value="{{ old('smtp_port') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Backup Email</label>
                                            <div class="col-lg-10">
                                                <input type="email" name="backup_email" class="form-control"
                                                    value="{{ old('backup_email') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Admin Panel URL</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="admin_url" class="form-control"
                                                    value="{{ old('admin_url') }}">
                                            </div>

                                            <label class="col-form-label col-lg-2">Admin User</label>
                                            <div class="col-lg-4">
                                                <input type="text" name="admin_username" class="form-control"
                                                    value="{{ old('admin_username') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Admin Password</label>
                                            <div class="col-lg-10">
                                                <input type="password" name="admin_password" class="form-control"
                                                    value="{{ old('admin_password') }}">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-4">
                                            <label class="col-form-label col-lg-2">Other External Credentials</label>
                                            <div class="col-lg-10">
                                                <textarea name="other_credentials" class="form-control" rows="3"
                                                    placeholder="FTP, API keys, Payment Gateway Logins etc." value="{{ old('other_credentials') }}"></textarea>
                                            </div>
                                        </div>


                                        <!-- Submit -->
                                        <div class="row justify-content-end">
                                            <div class="col-lg-10">
                                                <button type="submit" class="btn btn-primary">Save Project</button>
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

        <script>
            $('#service_type').on('change', function() {
                if ($(this).val() === 'other') {
                    $('#service_type_other').removeClass('d-none');
                } else {
                    $('#service_type_other').addClass('d-none').val('');
                }
            });

            $('#sub_service').on('change', function() {
                if ($(this).val() === 'other') {
                    $('#sub_service_other').removeClass('d-none');
                } else {
                    $('#sub_service_other').addClass('d-none').val('');
                }
            });
        </script>

    @endsection
