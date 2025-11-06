@extends('backend.app', ['title' => 'Footer Management'])

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

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

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('cms.footer.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-8">
                            <!-- Logo & Slogan -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Logo & Slogan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Logo</label>
                                            <input type="file" name="logo" class="form-control" accept="image/*">
                                            @if ($data->logo)
                                                <div class="mt-2">
                                                    <img src="{{ asset('/' . $data->logo) }}" class="img-thumbnail"
                                                        width="80">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label>Slogan Line 1</label>
                                            <input type="text" name="slogan_line1"
                                                value="{{ old('slogan_line1', $data->slogan_line1) }}" class="form-control">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Slogan Line 2</label>
                                            <input type="text" name="slogan_line2"
                                                value="{{ old('slogan_line2', $data->slogan_line2) }}" class="form-control">
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
                                        <label>Title</label>
                                        <input type="text" name="subscribe_title"
                                            value="{{ old('subscribe_title', $data->subscribe_title) }}"
                                            class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Description</label>
                                        <textarea name="subscribe_description" rows="3" class="form-control">{{ old('subscribe_description', $data->subscribe_description) }}</textarea>
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
                                        <label>Copyright</label>
                                        <input type="text" name="copyright"
                                            value="{{ old('copyright', $data->copyright) }}" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Disclaimer</label>
                                        <input type="text" name="disclaimer"
                                            value="{{ old('disclaimer', $data->disclaimer) }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-4">

                            <!-- Social Links -->
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="card-title">Social Links</h3>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addSocial()">Add</button>
                                </div>
                                <div class="card-body" id="social-links-container">
                                    @foreach (old('social_links', $data->social_links ?? []) as $index => $link)
                                        <div class="social-item mb-3 p-3 border">
                                            <div class="row g-2">
                                                <div class="col-4">
                                                    <select name="social_links[{{ $index }}][platform]"
                                                        class="form-control">
                                                        <option value="">Platform</option>
                                                        @foreach (['linkedin', 'tiktok', 'youtube', 'medium', 'facebook', 'instagram', 'twitter', 'x'] as $plat)
                                                            <option value="{{ $plat }}"
                                                                {{ ($link['platform'] ?? '') == $plat ? 'selected' : '' }}>
                                                                {{ ucfirst($plat) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-5">
                                                    <input type="url" name="social_links[{{ $index }}][url]"
                                                        value="{{ $link['url'] ?? '' }}" placeholder="https://..."
                                                        class="form-control">
                                                </div>
                                                <div class="col-2">
                                                    <input type="file" name="social_links[{{ $index }}][icon]"
                                                        class="form-control form-control-sm" accept="image/*,.svg">
                                                    @if (isset($link['icon']))
                                                        <img src="{{ asset('/' . $link['icon']) }}" width="24"
                                                            class="mt-1">
                                                    @endif
                                                </div>
                                                <div class="col-1">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="this.closest('.social-item').remove()">×</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Footer Links -->
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="card-title">Footer Links</h3>
                                    <button type="button" class="btn btn-sm btn-primary"
                                        onclick="addFooterLink()">Add</button>
                                </div>
                                <div class="card-body" id="footer-links-container">
                                    @foreach (old('footer_links', $data->footer_links ?? []) as $index => $link)
                                        <div class="footer-link-item mb-2 p-2 border d-flex gap-2">
                                            <input type="text" name="footer_links[{{ $index }}][title]"
                                                value="{{ $link['title'] ?? '' }}" placeholder="Title"
                                                class="form-control form-control-sm">
                                            <input type="text" name="footer_links[{{ $index }}][url]"
                                                value="{{ $link['url'] ?? '' }}" placeholder="/url"
                                                class="form-control form-control-sm">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="this.parentElement.remove()">×</button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="card mt-4">
                                <div class="card-body text-center">
                                    <button type="submit" class="btn btn-success btn-lg">Update Footer</button>
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
        let socialIndex = {{ count(old('social_links', $data->social_links ?? [])) }};
        let footerLinkIndex = {{ count(old('footer_links', $data->footer_links ?? [])) }};

        function addSocial() {
            const container = document.getElementById('social-links-container');
            const html = `
            <div class="social-item mb-3 border">
                <div class="row g-1">
                    <div class="col-2">
                        <select name="social_links[${socialIndex}][platform]" class="form-control">
                            <option value="">Platform</option>
                            @foreach (['linkedin', 'tiktok', 'youtube', 'medium', 'facebook', 'instagram', 'twitter', 'x'] as $plat)
                                <option value="{{ $plat }}">{{ ucfirst($plat) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="url" name="social_links[${socialIndex}][url]" placeholder="https://..." class="form-control">
                    </div>
                    <div class="col-5">
                        <input type="file" name="social_links[${socialIndex}][icon]" class="form-control form-control-sm" accept="image/*,.svg">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.social-item').remove()">×</button>
                    </div>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
            socialIndex++;
        }

        function addFooterLink() {
            const container = document.getElementById('footer-links-container');
            const html = `
        <div class="footer-link-item mb-2 p-2 border d-flex gap-2">
            <input type="text" name="footer_links[${footerLinkIndex}][title]" placeholder="Title" class="form-control form-control-sm">
            <input type="text" name="footer_links[${footerLinkIndex}][url]" placeholder="/url" class="form-control form-control-sm">
            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.remove()">×</button>
        </div>`;
            container.insertAdjacentHTML('beforeend', html);
            footerLinkIndex++;
        }
    </script>
@endpush
