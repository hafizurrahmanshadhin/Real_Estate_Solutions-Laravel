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
                                            <label class="form-label">Titles: <span class="text-danger">*</span></label>
                                            <div id="titles-wrapper">
                                                @php
                                                    $titles = old('titles', $heroSection->items ?? []);
                                                    if (!is_array($titles)) {
                                                        $titles = [];
                                                    }
                                                    if (empty($titles)) {
                                                        $titles = [''];
                                                    }
                                                @endphp
                                                @foreach ($titles as $i => $title)
                                                    <div class="input-group mb-2 title-item">
                                                        <input type="text" name="titles[]"
                                                            class="form-control @error('titles.' . $i) is-invalid @enderror"
                                                            placeholder="Please Enter Title" value="{{ $title }}"
                                                            maxlength="255" required>
                                                        <button type="button" class="btn btn-danger remove-title"
                                                            @if (count($titles) == 1) style="display:none;" @endif>
                                                            <i class="ri-delete-bin-2-line"></i>
                                                        </button>
                                                    </div>
                                                    @error('titles.' . $i)
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm" id="add-title"><i
                                                    class="ri-add-line"></i> Add Title</button>
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


            // Add new title input
            $('#add-title').on('click', function() {
                let index = $('#titles-wrapper .title-item').length;
                let html = `
            <div class="input-group mb-2 title-item">
                <input type="text" name="titles[]" class="form-control" placeholder="Please Enter Title" maxlength="255" required>
                <button type="button" class="btn btn-danger remove-title"><i class="ri-delete-bin-2-line"></i></button>
            </div>
        `;
                $('#titles-wrapper').append(html);
                $('.remove-title').show();
            });

            // Remove title input
            $(document).on('click', '.remove-title', function() {
                $(this).closest('.title-item').remove();
                if ($('#titles-wrapper .title-item').length === 1) {
                    $('.remove-title').hide();
                }
            });

            // Hide remove button if only one title
            if ($('#titles-wrapper .title-item').length === 1) {
                $('.remove-title').hide();
            }


            // Form validation
            $('#heroSectionForm').on('submit', function(e) {
                let hasTitle = false;
                $('input[name="titles[]"]').each(function() {
                    if ($(this).val().trim() !== '') hasTitle = true;
                });
                if (!hasTitle) {
                    e.preventDefault();
                    toastr.error('At least one title is required.');
                    $('input[name="titles[]"]').first().focus();
                    return false;
                }

                let content = $('#content').val().trim();
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
