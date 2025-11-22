@extends('backend.app')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- PAGE HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Profile Settings</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </div>
                </div>

                <!-- PROFILE CARD -->
                <div class="row" id="user-profile">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-12 col-md-12 col-xl-6">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <!-- Profile Picture -->
                                            <div class="profile-img-main rounded"
                                                style="width: 125px; height: 125px; overflow: hidden;">
                                                <img src="{{ Auth::user()->avatar ? asset(Auth::user()->avatar) : asset('default/profile.jpg') }}"
                                                    alt="Profile Picture" class="m-0 p-1"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            </div>
                                            <!-- Profile Info -->
                                            <div class="ms-4">
                                                <h4>{{ Auth::user()->profile->first_name ?? '' }}
                                                    {{ Auth::user()->profile->last_name ?? '' }}</h4>
                                                <p class="text-muted mb-2">{{ Auth::user()->email ?? 'N/A' }}</p>
                                                <a href="#" class="btn btn-primary btn-sm" id="uploadImageBtn">
                                                    <i class="fa fa-camera"></i> Update Picture
                                                </a>
                                                <input type="file" name="avatar" id="profile_picture_input"
                                                    style="display: none;" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TABS -->
                            <div class="border-top">
                                <div class="wideget-user-tab">
                                    <div class="tab-menu-heading">
                                        <div class="tabs-menu1">
                                            <ul class="nav">
                                                <li><a href="#editProfile" class="active show" data-bs-toggle="tab">
                                                        <i class="fa fa-user"></i> Edit Profile</a>
                                                </li>
                                                <li><a href="#updatePassword" data-bs-toggle="tab">
                                                        <i class="fa fa-lock"></i> Update Password</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <!-- EDIT PROFILE TAB -->
                            <div class="tab-pane active show" id="editProfile">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Profile Information</h3>
                                    </div>
                                    <div class="card-body">
                                        <form class="form-horizontal" method="POST"
                                            action="{{ route('setting.admin.profile.update') }}">
                                            @csrf

                                            <div class="row">
                                                <!-- First Name -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="first_name" class="form-label">First Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('first_name') is-invalid @enderror"
                                                        name="first_name" placeholder="Enter First Name"
                                                        value="{{ old('first_name', Auth::user()->profile->first_name ?? '') }}"
                                                        required>
                                                    @error('first_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Last Name -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="last_name" class="form-label">Last Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text"
                                                        class="form-control @error('last_name') is-invalid @enderror"
                                                        name="last_name" placeholder="Enter Last Name"
                                                        value="{{ old('last_name', Auth::user()->profile->last_name ?? '') }}"
                                                        required>
                                                    @error('last_name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Email -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="email" class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" placeholder="Enter Email"
                                                        value="{{ old('email', Auth::user()->email ?? '') }}" required>
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-save"></i> Update Profile
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- UPDATE PASSWORD TAB -->
                            <div class="tab-pane" id="updatePassword">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Change Password</h3>
                                    </div>
                                    <div class="card-body">
                                        <form class="form-horizontal" method="post"
                                            action="{{ route('setting.admin.rofile.update.password') }}">
                                            @csrf
                                            @method('POST')

                                            <div class="row">
                                                <!-- Current Password -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="old_password" class="form-label">Current Password <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control @error('old_password') is-invalid @enderror"
                                                        name="old_password" placeholder="Enter Current Password" required>
                                                    @error('old_password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- New Password -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="password" class="form-label">New Password <span
                                                            class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        name="password" placeholder="Enter New Password" required>
                                                    @error('password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Confirm Password -->
                                                <div class="col-md-12 mb-3">
                                                    <label for="password_confirmation" class="form-label">Confirm Password
                                                        <span class="text-danger">*</span></label>
                                                    <input type="password"
                                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                                        name="password_confirmation" placeholder="Confirm New Password"
                                                        required>
                                                    @error('password_confirmation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="col-12">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa fa-key"></i> Update Password
                                                    </button>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle Image button click to trigger file input
            $('#uploadImageBtn').click(function(e) {
                e.preventDefault();
                $('#profile_picture_input').click();
            });

            // Handle file input change to upload the selected image
            $('#profile_picture_input').change(function() {
                if (this.files && this.files[0]) {
                    var formData = new FormData();
                    formData.append('avatar', this.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');

                    // Show loading
                    NProgress.start();

                    $.ajax({
                        url: "{{ route('update.admin.profile.picture') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            NProgress.done();
                            if (response.success) {
                                // Update the profile picture src in the profile settings page
                                $('.profile-img-main img').attr('src', response.image_url);

                                // Also update the profile picture in the header view page
                                $('.profile-img-change').attr('src', response.image_url);

                                toastr.success('Profile picture updated successfully.');
                            } else {
                                toastr.error(response.message ||
                                    'Failed to update profile picture.');
                            }
                        },
                        error: function(xhr) {
                            NProgress.done();
                            let errorMsg =
                                'An error occurred while updating the profile picture.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            toastr.error(errorMsg);
                        }
                    });
                }
            });

            // Preview image before upload
            $('#profile_picture_input').on('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.profile-img-main img').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endpush
