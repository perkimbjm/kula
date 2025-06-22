@once
  <link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-o9N1j8r+Un5t8rqN2N0sVv2ob2y4S5W+vLrjzxo1P8M="
    crossorigin=""
  />
  <script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-Q22K+T4fTbqUHOfMc5pqFbPG0q2UJaXn+cFryV07s0g="
    crossorigin=""
    defer
  ></script>
  <style>
    .leaflet-control-fullscreen:hover {
      background-color: #f8f9fa !important;
    }
    .leaflet-control-fullscreen:active {
      background-color: #e9ecef !important;
    }
    #map-{{ $componentId }} {
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      overflow: hidden;
    }
  </style>
@endonce

{{-- Gunakan ID unik --}}
<div id="map-{{ $componentId }}" style="min-height:65vh; margin-bottom:1rem;"></div>

@push('scripts')
<script>
  document.addEventListener('livewire:load', function () {
    // Ambil data dari Blade:
    let lat = @js($lat);
    let lng = @js($lng);
    let compId = @js($componentId);

    // Inisiasi map & tile:
    const map = L.map(`map-${compId}`).setView([lat, lng], 18);
    L.tileLayer('https://tile.openstreetmap.de/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Marker draggable:
    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    // Drag → update Livewire state:
    marker.on('dragend', e => {
      const p = e.target.getLatLng();
      const component = Livewire.find(compId);
      if (component) {
        component.set('lat', p.lat);
        component.set('lng', p.lng);
      }
    });

    // Klik map → pindah marker & state:
    map.on('click', e => {
      marker.setLatLng(e.latlng);
      const component = Livewire.find(compId);
      if (component) {
        component.set('lat', e.latlng.lat);
        component.set('lng', e.latlng.lng);
      }
    });

    // Input ↔ marker sync dua arah dengan delay untuk menghindari konflik
    let inputTimeout;
    function syncFromInputs() {
      clearTimeout(inputTimeout);
      inputTimeout = setTimeout(() => {
        const latInput = document.querySelector(`input[name="lat"]`);
        const lngInput = document.querySelector(`input[name="lng"]`);

        if (latInput && lngInput) {
          const la = parseFloat(latInput.value);
          const lo = parseFloat(lngInput.value);
          if (!isNaN(la) && !isNaN(lo)) {
            marker.setLatLng([la, lo]);
            map.setView([la, lo]);
          }
        }
      }, 300);
    }

    // Event listeners untuk input
    document.addEventListener('input', function(e) {
      if (e.target.name === 'lat' || e.target.name === 'lng') {
        syncFromInputs();
      }
    });

    // Livewire hook: setelah update, pastikan marker & view sesuai state
    Livewire.hook('message.processed', (message, component) => {
      if (component && component.id === compId) {
        const la = component.get('lat');
        const lo = component.get('lng');
        if (la && lo && !isNaN(la) && !isNaN(lo)) {
          marker.setLatLng([la, lo]);
          map.setView([la, lo]);
        }
      }
    });

    // Tambahkan zoom control dan fullscreen
    map.zoomControl.setPosition('topright');

    // Tambahkan tombol fullscreen sederhana
    const fullscreenButton = L.Control.extend({
      onAdd: function() {
        const button = L.DomUtil.create('button', 'leaflet-control-fullscreen');
        button.innerHTML = '⛶';
        button.style.cssText = `
          width: 30px;
          height: 30px;
          background: white;
          border: 2px solid rgba(0,0,0,0.2);
          border-radius: 4px;
          cursor: pointer;
          font-size: 16px;
        `;
        button.onclick = function() {
          const mapContainer = document.getElementById(`map-${compId}`);
          if (mapContainer.requestFullscreen) {
            mapContainer.requestFullscreen();
          }
        };
        return button;
      }
    });
    map.addControl(new fullscreenButton({ position: 'topright' }));
  });
</script>
@endpush
