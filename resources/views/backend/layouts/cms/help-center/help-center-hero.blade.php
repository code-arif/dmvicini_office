@extends('backend.app', ['title' => 'Hero section'])

@section('content')
    <!--app-content open-->
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                {{-- PAGE-HEADER --}}
                <div class="page-header">
                    <div>
                        <h1 class="page-title">Help Center Page Content - Hero Section</h1>
                    </div>
                    <div class="ms-auto pageheader-btn">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Help Center</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Hero</li>
                        </ol>
                    </div>
                </div>

                <div class="row">
                    {{-- Left Card --}}
                    <div class="col-md-6">
                        <div class="card box-shadow-1">
                            <div class="card-header bg-light">
                                <h3>We're here for you</h3>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" method="post" action="{{ route('cms.update.hero') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    {{-- section title --}}
                                    <div class="form-group">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Enter title" id="title"
                                            value="{{ $data->title ?? (old('title') ?? '') }}">
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- section description --}}
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description:</label>
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="5">{{ old('description', $data->description ?? '') }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Save Change</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Right Card --}}

                </div>
            </div>
        </div>
    </div>
    <!-- CONTAINER CLOSED -->
@endsection



@push('scripts')
@endpush
