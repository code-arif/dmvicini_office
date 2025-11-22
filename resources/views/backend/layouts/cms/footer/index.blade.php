@extends('backend.app', ['title' => 'Footer Management'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <!-- PAGE HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Footer Management</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">CMS</a></li>
                            <li class="breadcrumb-item active">Footer</li>
                        </ol>
                    </div>
                </div>

                <!-- ALERTS -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('cms.footer.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-7">
                            <!-- Logo & Slogan -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Logo & Slogan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Logo</label>
                                            <input type="file" name="logo" id="logoInput" class="form-control"
                                                accept="image/*">
                                            @error('logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <!-- Logo Preview -->
                                            <div id="logoPreview" class="mt-3"
                                                @if (!$data->logo) style="display: none;" @endif>
                                                <img src="{{ $data->logo ? asset('/' . $data->logo) : '' }}"
                                                    alt="Logo Preview" class="img-thumbnail border"
                                                    style="max-width: 150px; max-height: 150px;">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Slogan</label>
                                            <input type="text" name="slogan_line"
                                                value="{{ old('slogan_line', $data->slogan_line) }}" class="form-control"
                                                placeholder="Your company slogan">
                                            @error('slogan_line')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Subscribe Section -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Subscribe Form</h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="subscribe_title"
                                            value="{{ old('subscribe_title', $data->subscribe_title) }}"
                                            class="form-control" placeholder="e.g., Subscribe to our newsletter">
                                        @error('subscribe_title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="subscribe_description" rows="3" class="form-control"
                                            placeholder="Enter subscribe section description">{{ old('subscribe_description', $data->subscribe_description) }}</textarea>
                                        @error('subscribe_description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Copyright & Disclaimer -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Legal Text</h3>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Copyright</label>
                                        <input type="text" name="copyright"
                                            value="{{ old('copyright', $data->copyright) }}" class="form-control"
                                            placeholder="© 2024 Your Company. All rights reserved.">
                                        @error('copyright')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Disclaimer</label>
                                        <textarea name="disclaimer" rows="2" class="form-control" placeholder="Enter disclaimer text">{{ old('disclaimer', $data->disclaimer) }}</textarea>
                                        @error('disclaimer')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-5">

                            <!-- Social Links -->
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="card-title mb-0">Social Links</h3>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addSocial()">
                                        <i class="fa fa-plus"></i> Add Social
                                    </button>
                                </div>
                                <div class="card-body" id="social-links-container">
                                    @php
                                        $socialLinks = is_string($data->social_links)
                                            ? json_decode($data->social_links, true) ?? []
                                            : $data->social_links ?? [];
                                    @endphp

                                    @forelse (old('social_links', $socialLinks) as $index => $link)
                                        <div class="social-item mb-3 p-3 border">
                                            <div class="row g-1">
                                                <div class="col-md-2">
                                                    <label class="form-label small">Platform</label>
                                                    <select name="social_links[{{ $index }}][platform]"
                                                        class="form-control form-control-sm">
                                                        <option value="">Select</option>
                                                        @foreach (['facebook' => 'Facebook', 'instagram' => 'Instagram', 'twitter' => 'Twitter', 'x' => 'X (Twitter)', 'linkedin' => 'LinkedIn', 'youtube' => 'YouTube', 'tiktok' => 'TikTok', 'medium' => 'Medium'] as $key => $plat)
                                                            <option value="{{ $key }}"
                                                                {{ ($link['platform'] ?? '') == $key ? 'selected' : '' }}>
                                                                {{ $plat }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">URL</label>
                                                    <input type="url" name="social_links[{{ $index }}][url]"
                                                        value="{{ $link['url'] ?? '' }}" placeholder="https://..."
                                                        class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Icon (Optional)</label>
                                                    <input type="file" name="social_links[{{ $index }}][icon]"
                                                        class="form-control form-control-sm" accept="image/*,.svg">
                                                    @if (!empty($link['icon']))
                                                        <input type="hidden"
                                                            name="social_links[{{ $index }}][existing_icon]"
                                                            value="{{ $link['icon'] }}">
                                                        <img src="{{ asset('/' . $link['icon']) }}" width="30"
                                                            height="30" class="mt-2 border">
                                                    @endif
                                                </div>
                                                <div class="col-md-1 d-flex align-items-center" style="margin-bottom: 10px">
                                                    <button type="button" class="btn btn-danger btn-sm w-100"
                                                        onclick="this.closest('.social-item').remove()">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted text-center">No social links added yet. Click "Add Social" to
                                            start.</p>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="card">
                                <div class="card-body text-center">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fa fa-save"></i> Update Footer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Logo Live Preview
        document.getElementById('logoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logoPreview');
            const img = preview.querySelector('img');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        let socialIndex =
            {{ count(old('social_links', is_string($data->social_links) ? json_decode($data->social_links, true) ?? [] : $data->social_links ?? [])) }};

        function addSocial() {
            const container = document.getElementById('social-links-container');

            // Remove "no social links" message if exists
            const emptyMsg = container.querySelector('p.text-muted');
            if (emptyMsg) emptyMsg.remove();

            const html = `
            <div class="social-item mb-3 p-3 border">
                <div class="row g-1">
                    <div class="col-md-2">
                        <label class="form-label small">Platform</label>
                        <select name="social_links[${socialIndex}][platform]" class="form-control form-control-sm">
                            <option value="">Select</option>
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                            <option value="twitter">Twitter</option>
                            <option value="x">X (Twitter)</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="youtube">YouTube</option>
                            <option value="tiktok">TikTok</option>
                            <option value="medium">Medium</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">URL</label>
                        <input type="url" name="social_links[${socialIndex}][url]" placeholder="https://..." class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Icon (Optional)</label>
                        <input type="file" name="social_links[${socialIndex}][icon]" class="form-control form-control-sm" accept="image/*,.svg">
                    </div>
                    <div class="col-md-1 d-flex align-items-end" style="margin-bottom:3px">
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="this.closest('.social-item').remove()">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            socialIndex++;
        }
    </script>
@endpush
