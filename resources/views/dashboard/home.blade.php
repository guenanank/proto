@extends('sb-admin')
@section('title', 'Home')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
            @endif

            You are logged in!
        </div>
    </div>

</div>
@endsection

@push('scripts')
@endpush
