@extends('tenant.layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}" />
@endpush

@section('content')
    <tenant-pos-farmacia-index :configuration2="{{ \App\Models\Tenant\Configuration::getPublicConfig() }}"
        :configuration="{{ $configuration }}" :soap-company="{{ json_encode($soap_company) }}"
        :business-turns="{{ $business_turns }}" :type-user="{{ json_encode(Auth::user()->type) }}"
        :is-print="{{ json_encode($configuration->auto_print) }}">
    </tenant-pos-farmacia-index>
@endsection

@push('scripts')
    <script src="{{ asset('js/sha-256.min.js') }}"></script>
    <script src="{{ asset('js/qz-tray.js') }}"></script>
    <script src="{{ asset('js/rsvp-3.1.0.min.js') }}"></script>
    <script src="{{ asset('js/jsrsasign-all-min.js') }}"></script>
    <script src="{{ asset('js/sign-message.js') }}"></script>
    <script src="{{ asset('js/function-qztray.js') }}"></script>
@endpush