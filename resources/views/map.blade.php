@extends('layouts.app')

@push('after-style')
<link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
<x-leaflet></x-leaflet>
<link rel="stylesheet" href="/css/map/markerCluster.css">
<style>
  .leaflet-control-layers-selector {
    top: 0;
    border-radius: 3px;
}

.leaflet-control-layers-selector:hover {
    background-color: #199900;
}

[type="checkbox"]:checked,
[type="checkbox"]:checked:hover,
[type="checkbox"]:checked:focus,
[type="radio"]:checked:hover,
[type="radio"]:checked:focus {
    border-color: transparent;
    background-color: #199900;
}

.leaflet-control-layers-group-name {
    font-size: small;
    font-weight: 600;
    margin-bottom: 0.2em;
    margin-left: 3px;
}
.leaflet-control-layers-group {
    margin-bottom: 0.5em;
}

.leaflet-control-layers label {
    font-size: 1em;
    top: 0;
    transition: font-size 0.5s ease, color 0.5s ease;
}

.leaflet-control-layers label:hover {
    font-size: 1.025em;
    color: #199900;
}

.leaflet-control-layers-scrollbar {
    overflow-y: scroll;
    padding-right: 10px;
}

.leaflet-tooltip.no-background {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    color: none;
    text-shadow: 1px 1px 0px #fff, -1px -1px 0px #fff, 1px -1px 0px #fff,
        -1px 1px 0px #fff;
}

.modal-dialog {
        max-height: calc(100% - 2rem);
        overflow-y: auto;
    }
</style>
@endpush

@section('content')

        <div id="map" role="map" class="h-[88vh] w-screen mx-auto overflow-hidden z-10">
        </div>

        <x-modal></x-modal>
        
@endsection

@push('after-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js"></script>
  <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js" charset="utf-8"></script>
  <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
  <script src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js"></script>
  <script src="{{ asset('js/map/shp.js') }}"></script>
  <script src="{{ asset('js/map/leaflet.shapefile.js') }}"></script>
  <script src="{{ asset('js/map/Control.Geocoder.js') }}"></script>
  <script src="{{ asset('js/map/leaflet.groupedlayercontrol.js') }}"></script>
  <script src="{{ asset('js/map/leaflet.markercluster141.js') }}"></script>
  <script src="{{ asset('js/map/base.js') }}"></script>
  <script src="{{ asset('js/map/core.js') }}"></script>
@endpush

