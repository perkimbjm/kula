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

.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 800px;
}

.custom-popup .leaflet-popup-content {
    margin: 0;
    padding: 10px;
}

.popup-container {
    padding: 10px;
    font-family: Arial, sans-serif;
}

.popup-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.popup-section {
    margin-bottom: 10px;
    font-size: 14px;
    color: #555;
}

.popup-section strong {
    color: #000;
}

.popup-image {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin-top: 5px;
}

@media (max-width: 600px) {
    .custom-popup .leaflet-popup-content-wrapper {
        max-width: 90%;
    }
}

@media (min-width: 640px) {
    .leaflet-right .leaflet-control {
        margin-right: 2rem;
    }
}
</style>
@endpush

@section('content')

        <div id="map" role="map" class="h-[88vh] w-screen mx-auto overflow-hidden z-10 px-2">
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

