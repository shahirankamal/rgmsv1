@extends('layouts.app')

@section('content')
<div class="background-wrapper">
</div>

<style>
.background-wrapper {
    background-image: url('/images/building.jpg');  /* Replace with your image path */
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 100vh;
    padding: 20px;
}

.card {
    background-color: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
}

.card-header {
    border-radius: 8px 8px 0 0;
}

.btn-primary {
    background-color: #0d47a1;
    border-color: #0d47a1;
}

.btn-primary:hover {
    background-color: #1976d2;
    border-color: #1976d2;
}

.text-primary {
    color: #0d47a1 !important;
}

.border-primary {
    border-color: #0d47a1 !important;
}
</style>
@endsection