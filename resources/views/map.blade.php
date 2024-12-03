@extends('layouts.app')

@push('after-style')
<link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
<x-leaflet></x-leaflet>

@endpush

@section('content')

        <div id="map" role="map" class="h-[88vh] w-screen mx-auto overflow-hidden z-10">
        </div>
        
@endsection

@push('after-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
  <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js" charset="utf-8"></script>
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
  <script src="{{ asset('js/map/Control.Geocoder.js') }}"></script>
  <script src="{{ asset('/js/map/leaflet.groupedlayercontrol.js') }}"></script>
  <script src="{{ asset('js/map/base.js') }}"></script>
  <script src="{{ asset('js/map/core.js') }}"></script>
@endpush

