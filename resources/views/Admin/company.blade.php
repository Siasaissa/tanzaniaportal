<!DOCTYPE html>
<html lang="en">

@include('layouts.adminhead')

<body class="g-sidenav-show bg-gray-100">
@include('layouts.adminnavbar')

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
    @include('layouts.topnav')

    <div class="container-fluid py-4">

        <div class="row mb-3">
            <div class="col-12">
                <h3 class="text-warning">Create Company</h3>
                <p class="text-sm">Admin can register a company to use the system</p>
            </div>
        </div>

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.store.company') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- COMPANY NAME --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <label class="text-warning">Company Name</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control border border-2 rounded-2 px-3"
                                placeholder="Enter company name"
                                value="{{ old('company_name') }}"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- COMPANY EMAIL --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <label class="text-warning">Company Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control border border-2 rounded-2 px-3"
                                placeholder="company@email.com"
                                value="{{ old('company_email') }}"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- COMPANY PHONE --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <label class="text-warning">Company Phone</label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control border border-2 rounded-2 px-3"
                                placeholder="+255..."
                                value="{{ old('company_phone') }}"
                            >
                        </div>
                    </div>
                </div>

                {{-- COMPANY LOGO --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <label class="text-warning">Company Logo</label>
                            <input
                                type="file"
                                name="logo"
                                class="form-control border border-2 rounded-2 px-3"
                                accept="image/*"
                            >
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <label class="text-warning">Description</label>
                            <input
                                type="text"
                                name="company_desc"
                                class="form-control border border-2 rounded-2 px-3"
                                placeholder="Enter company Description"
                                value="{{ old('company_Desc') }}"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- STATUS --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <label class="text-warning">Company Status</label>
                            <select name="status" class="form-control border border-2 rounded-2 px-3">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- PROVIDER --}}
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <label class="text-warning">Provider</label>
                            <input type="text" name="provider" placeholder="Enter Provider Name" class="form-control border border-2 rounded-2 px-3">
                        </div>
                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-warning">
                        Create Company
                    </button>
                </div>

            </div>
        </form>

        @include('layouts.footer')
    </div>
</main>

<script src="../assets/js/core/bootstrap.min.js"></script>
</body>
</html>
