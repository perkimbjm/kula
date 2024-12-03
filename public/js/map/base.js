/* Basemap Layers */
const cartoLight = L.tileLayer(
    "https://cartodb-basemaps-{s}.global.ssl.fastly.net/light_all/{z}/{x}/{y}{r}.png",
    {
        maxZoom: 21,
        alt: "light basemap",
        detectRetina: true,
        attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://cartodb.com/attributions">CartoDB</a>',
    }
);

const googleSatellite = L.tileLayer(
    "https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}",
    {
        maxZoom: 21,
        subdomains: ["mt0", "mt1", "mt2", "mt3"],
        detectRetina: true,
        attribution:
            "Maps Data: Google, Citra 2021 TerraMetrics, Data peta &copy; 2021",
    }
);

const googleMaps = L.tileLayer(
    "https://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}",
    {
        maxZoom: 21,
        subdomains: ["mt0", "mt1", "mt2", "mt3"],
        detectRetina: true,
        attribution: "Powered by Google , Data Peta: &copy; 2021",
    }
);

const cartoDark = L.tileLayer(
    "https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png",
    {
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: "abcd",
        detectRetina: true,
        maxZoom: 21,
        alt: "dark basemap",
    }
);

const otopomap = L.tileLayer("//{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors. OpenTopoMap.org",
    detectRetina: true,
});

const osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    detectRetina: true,
    maxZoom: 21,
    tileSize: 256,
    zoomOffset: 0,
});

const cartoVoyager = L.tileLayer(
    "https://cartodb-basemaps-1.global.ssl.fastly.net/rastertiles/voyager/{z}/{x}/{y}{r}.png",
    {
        maxZoom: 21,
        detectRetina: true,
        attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: "abcd",
    }
);

// basemap from esri
const rbi = L.tileLayer(
    "https://geoservices.big.go.id/rbi/rest/services/BASEMAP/Rupabumi_Indonesia/MapServer/tile/{z}/{y}/{x}",
    {
        attribution:
            "Tiles &copy; RBI Indonesia Geospasial Portal, Badan Informasi Geospasial",
        maxZoom: 18,
        subdomains: ["server", "services"],
        tileSize: 512,
        zoomOffset: -1,
        opacity: 1,
        zIndex: 0,
        detectRetina: true,
    }
);
