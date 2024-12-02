@extends('layouts.app')

@section('content')
<div class="flex flex-col min-h-screen">
    <div class="flex-grow">
        <iframe 
            src="{{ asset('data/pedoman.pdf') }}" 
            class="w-full h-screen"
            style="height: calc(100vh - 64px);">
        </iframe>
    </div>
</div>
@endsection