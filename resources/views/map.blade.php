@extends('layouts.app')

@push('after-style')
<link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
<x-leaflet></x-leaflet>
<link rel="stylesheet" href="{{ asset('css/iconLayers.css') }}" />
<link rel="stylesheet" href="{{ asset('css/leaflet.pm.css') }}">
<link rel="stylesheet" href="{{ asset('css/Leaflet.PolylineMeasure.css') }}">
<link rel="stylesheet" href="{{ asset('css/leaflet.toolbar.css') }}" />
<link rel="stylesheet" href="{{ asset('css/leaflet-sidepanel.css') }}" />

@endpush

@section('content')

        <div id="map" role="map" class="h-[88vh] w-screen mx-auto overflow-hidden">
        </div>
        
@endsection

@push('after-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
  <script defer src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js" charset="utf-8"></script>
  <script defer src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
  <script defer src="{{ asset('js/base.js') }}"></script>
  <x-icon-basemap></x-icon-basemap>
  <script defer src="{{ asset('js/iconLayers.js') }}"></script> 
  <script defer src="{{ asset('js/Leaflet.PolylineMeasure.js') }}"></script>
  <script defer src="{{ asset('js/leaflet.toolbar.js') }}"></script>
  <script defer src="{{ asset('js/leaflet.pm.min.js') }}"></script>
  <script defer src="{{ asset('js/leaflet-sidepanel.min.js') }}"></script>
  <script src="{{ asset('js/core.js') }}"></script>

@endpush

