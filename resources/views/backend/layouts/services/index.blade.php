@extends('backend.app')

@section('title', 'Services')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                {{-- Services Item Table --}}
                @include('backend.layouts.services.service-items.table')

                {{-- Square Footage Size Table --}}
                @include('backend.layouts.services.footage-size.table')

                {{-- Services List Table --}}
                @include('backend.layouts.services.services-list.table')

                {{-- Add-ons table --}}
                @include('backend.layouts.services.add-ons.table')
            </div>
        </div>
    </div>

    {{-- Modal For Service Item --}}
    @include('backend.layouts.services.service-items.modal')

    {{-- Modal For Square Footage Range --}}
    @include('backend.layouts.services.footage-size.modal')

    {{-- Modal For Service --}}
    @include('backend.layouts.services.services-list.modal')

    {{-- Modal For Add-ons --}}
    @include('backend.layouts.services.add-ons.modal')
@endsection

@push('scripts')
    {{-- This Script is for Services Module Item --}}
    @include('backend.layouts.services.service-items.script')

    {{-- This Script is for Square Footage Sizes Module --}}
    @include('backend.layouts.services.footage-size.script')

    {{-- This Script is for Services Module --}}
    @include('backend.layouts.services.services-list.script')

    {{-- This Script is for Add-ons Module --}}
    @include('backend.layouts.services.add-ons.script')
@endpush
