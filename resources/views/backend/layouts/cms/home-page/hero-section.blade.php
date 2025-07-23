@extends('backend.app')

@section('title', 'Home Page | Hero Section')

@push('styles')
    <style>
        .dropify-wrapper {
            height: 285px;
        }
    </style>
@endpush

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Hero Section Management</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('home-page.hero-section.update') }}"
                                enctype="multipart/form-data" id="heroSectionForm">
                                @csrf
                                @method('PATCH')
                                <div class="row gy-4">
                                    <div class="col-md-12">
                                        <div>
                                            <label for="title" class="form-label">Title: <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                name="title" id="title" placeholder="Please Enter Title"
                                                value="{{ old('title', $heroSection->title ?? '') }}" maxlength="255">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="content" class="form-label">Content: <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                                            placeholder="Please Insert Content Description">{{ old('content', $heroSection->content ?? '') }}</textarea>
                                        @error('content')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div>
                                            <label for="image" class="form-label">Hero Image:</label>
                                            <input type="hidden" name="remove_image" value="0">
                                            <input class="form-control dropify @error('image') is-invalid @enderror"
                                                type="file" name="image" id="image" accept="image/*"
                                                data-default-file="{{ $heroSection && $heroSection->getRawImagePath() ? asset($heroSection->getRawImagePath()) : '' }}"
                                                data-allowed-file-extensions="jpg jpeg png gif webp"
                                                data-max-file-size="20M">
                                            @error('image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <small class="text-muted">
                                                Supported formats: JPG, JPEG, PNG, GIF, WEBP. Max size: 20MB
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i>Update Hero Section
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize Dropify
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag and drop an image here or click',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong happened.'
                }
            });

            // Initialize CKEditor
            ClassicEditor
                .create(document.querySelector('#content'), {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    }
                })
                .catch(error => {
                    console.error('CKEditor Error:', error);
                });

            // Form validation
            $('#heroSectionForm').on('submit', function(e) {
                let title = $('#title').val().trim();
                let content = $('#content').val().trim();

                if (!title) {
                    e.preventDefault();
                    toastr.error('Title is required.');
                    $('#title').focus();
                    return false;
                }

                if (!content) {
                    e.preventDefault();
                    toastr.error('Content is required.');
                    $('#content').focus();
                    return false;
                }
            });
        });
    </script>
@endpush
