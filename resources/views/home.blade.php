@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-green-700 via-green-600 to-green-700 dark:bg-green-900">

    <x-hero />
    <x-ticket />
    <x-info :information="$information" />
</div>
@endsection