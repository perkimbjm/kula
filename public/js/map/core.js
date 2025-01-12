let map;

const mapCenter = [-2.33668, 115.46028];
const mapZoom = 18;

map = L.map(document.getElementById("map"), {
    zoom: mapZoom,
    fullscreenControl: { pseudoFullscreen: true },
    layers: [googleSatellite],
    center: mapCenter,
});

setTimeout(function () {
    map.invalidateSize();
}, 1000);

function zoomToDefault() {
    map.setView(mapCenter, mapZoom);
}

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

let homeButton = L.easyButton({
    id: "my-home-button", // an id for the generated button
    position: "topleft",
    type: "replace",
    leafletClasses: true,
    states: [
        {
            stateName: "zoom-to-default",
            icon: "fa-home",
            title: "Go to initial view",
            onClick: function (btn, map) {
                zoomToDefault();
                btn.state("zoom-to-default"); // change state on click!
            },
        },
    ],
}).addTo(map);

L.Control.geocoder({
    position: "topleft",
    collapsed: true,
}).addTo(map);

/* GPS enabled geolocation control set to follow the user's location */
const locateControl = L.control
    .locate({
        position: "topleft",
        drawCircle: true,
        follow: true,
        setView: true,
        keepCurrentZoomLevel: true,
        markerStyle: {
            weight: 1,
            opacity: 0.8,
            fillOpacity: 0.8,
        },
        circleStyle: {
            weight: 0.8,
            clickable: true,
        },
        icon: "fa fa-location-arrow",
        metric: true,
        strings: {
            title: "Lokasiku",
            outsideMapBoundsMsg: "Kamu tampaknya berada di luar jangkauan peta",
        },
        locateOptions: {
            maxZoom: 18,
            watch: true,
            enableHighAccuracy: true,
            maximumAge: 10000,
            timeout: 10000,
        },
    })
    .addTo(map);

/*Scale Map*/
L.control.scale({ imperial: false }).addTo(map);

function showModal(title, content) {
    document.getElementById("feature-title").innerText = title;
    document.getElementById("feature-info").innerHTML = content;
    const modal = document.getElementById("featureModal");
    if (modal) {
        modal.classList.remove("hidden");
    }
}

function closeModal() {
    const modal = document.getElementById("featureModal");
    if (modal) {
        modal.classList.add("hidden");
    }
}

// Tambahkan event listener untuk tombol close
document
    .getElementById("closeModalButton")
    .addEventListener("click", closeModal);
document
    .getElementById("closeModalFooterButton")
    .addEventListener("click", closeModal);

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

// Buat fungsi untuk memuat data kecamatan
let kecamatan = L.geoJson(null, {
    style: function (feature) {
        return {
            color: "#2F4F4F",
            fill: true,
            fillColor:
                {
                    Awayan: "#FFD700",
                    "Batu Mandi": "#008080",
                    Halong: "#FF6347",
                    Juai: "#008000",
                    Lampihong: "#FFA500",
                    Paringin: "#87CEEB",
                    "Paringin Selatan": "#FFFF00",
                    "Tebing Tinggi": "#CCFFCC",
                }[feature.properties.KECAMATAN] || "#FFFFFF",
            fillOpacity: 0.6,
            opacity: 0.5,
            width: 0.001,
            clickable: true,
        };
    },
    onEachFeature: function (feature, layer) {
        layer.on({
            mouseover: function (e) {
                let layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "blue",
                    opacity: 1,
                });
            },
            mouseout: function (e) {
                kecamatan.resetStyle(e.target);
            },
            click: zoomToFeature,
        });
    },
});

let tooltipKecamatan = L.layerGroup();
let isKecamatanLoaded = false;

// Fungsi untuk memuat data kecamatan
function loadKecamatanData() {
    if (!isKecamatanLoaded) {
        fetch("data/KECAMATAN_AR.geojson", {})
            .then((response) => response.json())
            .then((data) => {
                L.geoJson(data, {
                    onEachFeature: function (feature, layer) {
                        let tooltip = L.tooltip({
                            permanent: true,
                            direction: "center",
                            className: "no-background",
                        })
                            .setContent(feature.properties.KECAMATAN)
                            .setLatLng(layer.getBounds().getCenter());

                        tooltipKecamatan.addLayer(tooltip);
                    },
                });
                kecamatan.addData(data);
                isKecamatanLoaded = true;
            })
            .catch((error) => {
                console.error("Error loading kecamatan data:", error);
            });
    }
}

// Buat fungsi untuk memuat data desa
let desa = L.geoJson(null, {
    style: function (feature) {
        return {
            color: "skyblue",
            fill: true,
            fillColor: "lightgrey",
            fillOpacity: 0.4,
            opacity: 0.85,
            width: 0.005,
            clickable: true,
        };
    },
    onEachFeature: function (feature, layer) {
        layer.on({
            mouseover: function (e) {
                let layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "blue",
                    opacity: 1,
                });
            },
            mouseout: function (e) {
                desa.resetStyle(e.target);
            },
            click: zoomToFeature,
        });
    },
});

let tooltipDesa = L.layerGroup();
let isDesaLoaded = false;

// Fungsi untuk memuat data desa
function loadDesaData() {
    if (!isDesaLoaded) {
        fetch("data/DESA_AR.geojson")
            .then((response) => response.json())
            .then((data) => {
                L.geoJson(data, {
                    onEachFeature: function (feature, layer) {
                        let tooltip = L.tooltip({
                            permanent: true,
                            direction: "center",
                            className: "no-background",
                        })
                            .setContent(feature.properties.DESA)
                            .setLatLng(layer.getBounds().getCenter());

                        tooltipDesa.addLayer(tooltip);
                    },
                });
                desa.addData(data);
                isDesaLoaded = true;
            })
            .catch((error) => {
                console.error("Error loading desa data:", error);
            });
    }
}

// Buat fungsi untuk memuat data kumuh
let kumuh = L.geoJson(null, {
    style: function (feature) {
        return {
            color: "grey",
            fillColor: "magenta",
            fillOpacity: 0.5,
            opacity: 0.5,
            width: 0.001,
            clickable: true,
            title: feature.properties.KECAMATAN,
            riseOnHover: true,
        };
    },
    onEachFeature: function (feature, layer) {
        if (feature.properties) {
            let content =
                "<table class='table-auto w-full'>" +
                "<tr><th class='text-left'>LUASAN KUMUH</th><td>" +
                feature.properties.LUAS_ha +
                " Ha</td></tr>" +
                "<tr><th class='text-left'>LOKASI KUMUH</th><td>" +
                feature.properties.LOKASI_KUM +
                "</td></tr>" +
                "</table>";
            layer.on({
                click: function (e) {
                    showModal(feature.properties.KECAMATAN, content);
                },
            });
        }
        layer.on({
            mouseover: function (e) {
                let layer = e.target;
                layer.setStyle({
                    weight: 3,
                    color: "#00FFFF",
                    opacity: 1,
                });
                if (!L.Browser.ie && !L.Browser.opera) {
                    layer.bringToFront();
                }
            },
            mouseout: function (e) {
                kumuh.resetStyle(e.target);
            },
            click: zoomToFeature,
        });
    },
});

let isKumuhLoaded = false;

function loadKumuhData() {
    if (!isKumuhLoaded) {
        fetch("data/KUMUH_AR.geojson")
            .then((response) => response.json())
            .then((data) => {
                kumuh.addData(data);
                isKumuhLoaded = true;
            })
            .catch((error) => {
                console.error("Error loading kumuh data:", error);
            });
    }
}

const createClusterGroup = () => {
    return L.markerClusterGroup({
        chunkedLoading: true,
        chunkInterval: 200,
        chunkDelay: 50,
        maxClusterRadius: (zoom) => {
            return zoom <= 13 ? 80 : zoom <= 15 ? 40 : 20;
        },
        spiderfyOnMaxZoom: false,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        removeOutsideVisibleBounds: true,
        animate: true,
        animateAddingMarkers: false,
        disableClusteringAtZoom: 16,
        maxZoom: 16,
    });
};

const isValidLatLng = (lat, lng) => {
    return (
        lat &&
        lng &&
        !isNaN(lat) &&
        !isNaN(lng) &&
        lat >= -90 &&
        lat <= 90 &&
        lng >= -180 &&
        lng <= 180
    );
};

const rtlh = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
        if (!isValidLatLng(latlng.lat, latlng.lng)) {
            console.warn("Invalid coordinates detected:", latlng);
            return null;
        }

        const marker = L.marker(latlng, {
            icon: L.icon({
                iconUrl: "/img/home-red.png",
                iconSize: [16, 16],
                iconAnchor: [8, 16],
                popupAnchor: [0, -16],
            }),
        });

        marker.options.clickable = true;
        marker.options.riseOnHover = true;
        marker.options.bubblingMouseEvents = false;

        return marker;
    },
    onEachFeature: function (feature, layer) {
        if (feature.properties) {
            const content = `<table class='table-auto w-full'>
                <tr><th class='text-left'>ID</th><td>${
                    feature.properties.id || "-"
                }</td></tr>
                <tr><th class='text-left'>Nama</th><td>${
                    feature.properties.name || "-"
                }</td></tr>
                <tr><th class='text-left'>Alamat</th><td>${
                    feature.properties.address || "-"
                }</td></tr>
                <tr><th class='text-left'>Jumlah Penghuni</th><td>${
                    feature.properties.people || "-"
                }</td></tr>
                <tr><th class='text-left'>Pondasi</th><td>${
                    feature.properties.pondasi || "-"
                }</td></tr>
                <tr><th class='text-left'>Atap</th><td>${
                    feature.properties.atap || "-"
                }</td></tr>
                <tr><th class='text-left'>Dinding</th><td>${
                    feature.properties.dinding || "-"
                }</td></tr>
                <tr><th class='text-left'>Lantai</th><td>${
                    feature.properties.lantai || "-"
                }</td></tr>
                <tr><th class='text-left'>Status</th><td>${
                    feature.properties.status || "-"
                }</td></tr>
                <tr><th class='text-left'>Catatan</th><td>${
                    feature.properties.note || "-"
                }</td></tr>
            </table>`;
            layer.bindPopup(content, {
                closeButton: true,
                autoPan: false,
                maxWidth: 300,
            });
        }
    },
    filter: function (feature) {
        const coords = feature.geometry.coordinates;
        return isValidLatLng(coords[1], coords[0]);
    },
});

const rtlhCluster = createClusterGroup();

let isRtlhLoaded = false;
let activePopup = null;

async function loadRtlhData() {
    if (!isRtlhLoaded) {
        try {
            const response = await fetch("https://sibedahseru.web.id/api/rtlh");
            const data = await response.json();

            if (!Array.isArray(data.data)) {
                throw new Error("Data yang diterima bukan array");
            }

            const validFeatures = data.data
                .filter((item) => isValidLatLng(item.lat, item.lng))
                .map((item) => ({
                    type: "Feature",
                    geometry: {
                        type: "Point",
                        coordinates: [item.lng, item.lat],
                    },
                    properties: {
                        id: item.id,
                        name: item.name,
                        address: item.address,
                        people: item.people,
                        pondasi: item.pondasi,
                        atap: item.atap,
                        dinding: item.dinding,
                        lantai: item.lantai,
                        status: item.status,
                        note: item.note,
                    },
                }));

            if (validFeatures.length > 0) {
                const geoJsonData = {
                    type: "FeatureCollection",
                    features: validFeatures,
                };

                rtlh.clearLayers();
                rtlh.addData(geoJsonData);

                rtlhCluster.clearLayers();
                rtlhCluster.addLayer(rtlh);

                if (!map.hasLayer(rtlhCluster)) {
                    map.addLayer(rtlhCluster);
                    console.log("Layer rtlhCluster ditambahkan");
                }

                isRtlhLoaded = true;
                console.log("Data RTLH berhasil dimuat");
            }
        } catch (error) {
            console.error("Error loading RTLH data:", error);
        }
    }
}

// Event listeners
map.on("zoomstart", function () {
    if (activePopup) {
        map.closePopup(activePopup);
        activePopup = null;
    }
});

// Event handler untuk memuat data
map.on("overlayadd", function (e) {
    if (e.name === "Kecamatan " || e.name === "Nama Kecamatan") {
        loadKecamatanData();
    } else if (e.name === "Kel / Desa" || e.name === "Nama Desa") {
        loadDesaData();
    } else if (e.name === "Permukiman Kumuh") {
        loadKumuhData();
    } else if (e.name === "Rumah Tidak Layak Huni") {
        loadRtlhData();
    }
});

map.on("overlayremove", function (e) {
    // Handle Kecamatan layer
    if (e.name === "Kecamatan ") {
        if (map.hasLayer(kecamatan)) {
            map.removeLayer(kecamatan);
        }
    }

    // Handle Nama Kecamatan layer
    if (e.name === "Nama Kecamatan") {
        if (map.hasLayer(tooltipKecamatan)) {
            map.removeLayer(tooltipKecamatan);
        }
    }

    // Handle Desa layer
    if (e.name === "Kel / Desa") {
        if (map.hasLayer(desa)) {
            map.removeLayer(desa);
        }
    }

    // Handle Nama Desa layer
    if (e.name === "Nama Desa") {
        if (map.hasLayer(tooltipDesa)) {
            map.removeLayer(tooltipDesa);
        }
    }

    // Handle Kumuh layer
    if (e.name === "Permukiman Kumuh") {
        if (map.hasLayer(kumuh)) {
            map.removeLayer(kumuh);
        }
    }

    if (e.name === "Rumah Tidak Layak Huni") {
        if (map.hasLayer(rtlh)) {
            map.removeLayer(rtlh);
        }
    }
});

function addLayerToControl(layer, name) {
    if (layer && name && layerControl) {
        if (layer._leaflet_id) {
            groupedOverlays.IMPORT[name] = layer;

            layerControl.remove();

            layerControl = L.control.groupedLayers(
                baseLayers,
                groupedOverlays,
                {
                    collapsed: document.body.clientWidth <= 1367,
                    exclusiveGroups: [],
                }
            );

            layerControl.addTo(map);
        } else {
            console.error(`Layer "${name}" tidak memiliki _leaflet_id yang valid.`);
        }
    } else {
        console.error(`Gagal menambahkan layer "${name}" ke layer control: Parameter tidak valid.`);
    }
}

let groupedOverlays = {
    "<b>BATAS ADMINISTRASI</b>": {
        "Kecamatan ": kecamatan,
        "Nama Kecamatan": tooltipKecamatan,
        "Kel / Desa": desa,
        "Nama Desa": tooltipDesa,
    },

    "<b>TEMATIK</b>": {
        "Permukiman Kumuh": kumuh,
        "Rumah Tidak Layak Huni": rtlhCluster,
    },
    IMPORT: {},
};

let baseLayers = {
    "Satellite ": googleSatellite,

    "RBI ": rbi,

    "Google Maps": googleMaps,

    "Greyscale ": cartoLight,

    "Dark ": cartoDark,
};

let layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
    collapsed: document.body.clientWidth <= 1367,
});

layerControl.addTo(map);

let currentMarker;

let onClicked = function (e) {
    if (currentMarker) {
        map.removeLayer(currentMarker);
    }
    currentMarker = L.marker(e.latlng, {
        icon: L.icon({
            iconUrl: "/img/logo-footer.png",
            iconAnchor: [12, 28],
            popuplanAnchor: [0, -25],
        }),
    })
        .addTo(map)
        .bindPopup(
            "Koordinat titik ini di " +
                "<br>Lattitude : " +
                e.latlng.lat.toString() +
                "<br>" +
                "Longitude : " +
                e.latlng.lng.toString()
        );
};

map.on("contextmenu", onClicked);

function popUp(geo) {
    map.fitBounds(geo.getBounds());
    geo.eachLayer(function (layer) {
        let properties = layer.feature.properties;
        let popupContent =
            "<div class='popup-content'><table class='table-auto w-full border-collapse border border-gray-300'>";
        Object.entries(properties).forEach(([key, value]) => {
            popupContent += `<tr><th class='text-left border border-gray-300 p-2'>${key}</th><td class='border border-gray-300 p-2'>${value}</td></tr>`;
        });
        popupContent += "</table></div>";
        layer.bindPopup(popupContent);
    });
}

let geo;


function geoJsonData(file) {
    let reader = new FileReader();
    reader.onload = function () {
        const geoJsonLayer = L.layerGroup();

        const geo = omnivore
            .geojson(reader.result)
            .on("ready", function () {
                this.eachLayer(function (layer) {
                    geoJsonLayer.addLayer(layer);
                });
                popUp(this);
            })
            .addTo(geoJsonLayer);

        geoJsonLayer.addTo(map);

        const fileName = file.name.split(".").slice(0, -1).join(".");

        addLayerToControl(geoJsonLayer, fileName);
    };
    reader.readAsDataURL(file);
}

function gpxData(file) {
    let reader = new FileReader();
    reader.onload = function () {
        const gpxLayer = L.layerGroup();
        const geo = omnivore
            .gpx(reader.result)
            .on("ready", function () {
                popUp(geo);
            })
            .addTo(gpxLayer);
        gpxLayer.addTo(map);
        const fileName = file.name.split(".").slice(0, -1).join(".");
        groupedOverlays.IMPORT[fileName] = gpxLayer;

        layerControl.remove();
        layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
            collapsed: document.body.clientWidth <= 1367,
        });
        layerControl.addTo(map);
    };
    reader.readAsDataURL(file);
}

function csvData(file) {
    let reader = new FileReader();
    reader.onload = function () {
        const csvLayer = L.layerGroup();
        const geo = omnivore.csv.parse(reader.result).addTo(csvLayer);
        popUp(geo);
        csvLayer.addTo(map);
        const fileName = file.name.split(".").slice(0, -1).join(".");
        groupedOverlays.IMPORT[fileName] = csvLayer;

        layerControl.remove();
        layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
            collapsed: document.body.clientWidth <= 1367,
        });
        layerControl.addTo(map);
    };
    reader.readAsText(file);
}

function kmlData(file) {
    let reader = new FileReader();
    reader.onload = function () {
        const kmlLayer = L.layerGroup();
        const geo = omnivore.kml.parse(reader.result).addTo(kmlLayer);
        popUp(geo);
        kmlLayer.addTo(map);
        const fileName = file.name.split(".").slice(0, -1).join(".");
        groupedOverlays.IMPORT[fileName] = kmlLayer;

        layerControl.remove();
        layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
            collapsed: document.body.clientWidth <= 1367,
        });
        layerControl.addTo(map);
    };
    reader.readAsText(file);
}

function wktData(file) {
    let reader = new FileReader();
    reader.onload = function () {
        const wktLayer = L.layerGroup();
        const geo = omnivore.wkt.parse(reader.result).addTo(wktLayer);
        popUp(geo);
        wktLayer.addTo(map);
        const fileName = file.name.split(".").slice(0, -1).join(".");
        groupedOverlays.IMPORT[fileName] = wktLayer;

        layerControl.remove();
        layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
            collapsed: document.body.clientWidth <= 1367,
        });
        layerControl.addTo(map);
    };
    reader.readAsText(file);
}

function shpData(file) {
    const reader = new FileReader();
    reader.onload = function (e) {
        const buffer = e.target.result;
        shp(buffer)
            .then(function (geojson) {
                const shpLayer = L.layerGroup();
                const layer = L.geoJson(geojson, {
                    onEachFeature: function (feature, layer) {
                        if (feature.properties) {
                            const keys = Object.keys(feature.properties);
                            const values = Object.values(feature.properties);

                            let popupContent =
                                '<div class="max-h-100px max-w-87.5px overflow-auto">' +
                                '<table class="table-auto w-full border-collapse border border-gray-300">';

                            popupContent += "<tr>";
                            keys.forEach((key) => {
                                popupContent += `<th class="text-left border border-gray-300 p-2 overflow-auto">${key}</th>`;
                            });
                            popupContent += "</tr>";

                            popupContent += "<tr>";
                            values.forEach((value) => {
                                popupContent += `<td class="border border-gray-300 p-2 overflow-auto">${value}</td>`;
                            });
                            popupContent += "</tr>";

                            popupContent += "</table></div>";
                            layer.bindPopup(popupContent);
                        }
                    },
                });
                shpLayer.addLayer(layer);
                shpLayer.addTo(map);
                const fileName = file.name.split(".").slice(0, -1).join(".");
                groupedOverlays.IMPORT[fileName] = shpLayer;

                layerControl.remove();
                layerControl = L.control.groupedLayers(
                    baseLayers,
                    groupedOverlays,
                    {
                        collapsed: document.body.clientWidth <= 1367,
                    }
                );
                layerControl.addTo(map);
                map.fitBounds(layer.getBounds());
            })
            .catch(function (error) {
                console.error("Error loading shapefile:", error);
            });
    };
    reader.readAsArrayBuffer(file);
}

// Add Vector Layers
function vectorData() {
    let inputNode = document.createElement("input");
    inputNode.setAttribute("type", "file");
    inputNode.setAttribute("id", "leaflet-draw-shapefile-selector");
    inputNode.setAttribute("accept", ".geojson,.gpx,.csv,.kml,.wkt,.zip");

    inputNode.addEventListener("change", function (e) {
        let files = inputNode.files;
        let file = files[0];
        let parts = file.name.split(".");
        let ext = parts[parts.length - 1];

        if (ext.toLowerCase() == "geojson") {
            geoJsonData(file);
        } else if (ext.toLowerCase() == "gpx") {
            gpxData(file);
        } else if (ext.toLowerCase() == "csv") {
            csvData(file);
        } else if (ext.toLowerCase() == "kml") {
            kmlData(file);
        } else if (ext.toLowerCase() == "wkt") {
            wktData(file);
        } else if (ext.toLowerCase() == "zip") {
            shpData(file);
        }
    });
    inputNode.click();
}

if (L && L.easyButton) {
    L.easyButton({
        states: [
            {
                stateName: "upload",
                icon: "fa fa-upload fa-lg",
                title: "Add Layers (shapefile, geojson, gpx, csv, kml, wkt)",
                onClick: function (btn, map) {
                    vectorData();
                },
            },
        ],
    }).addTo(map);
}
