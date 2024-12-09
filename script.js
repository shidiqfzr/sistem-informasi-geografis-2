// Initialize Map
let map;
let radiusCircle;
let markers = [];
let infowindow;

function initMap() {
    // Inisialisasi peta
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -0.051768590714182675, lng: 109.31147947328273 },
        zoom: 16,
    });

    // Initialize InfoWindow
    infowindow = new google.maps.InfoWindow();

    // Event untuk tombol View Store
    document.getElementById("view-store-btn").addEventListener("click", fetchAndDisplayStores);

    // Event untuk slider radius
    const radiusSlider = document.getElementById("radius-slider");
    radiusSlider.addEventListener("input", () => {
        document.getElementById("radius-value").textContent = radiusSlider.value;
        filterStoresByRadius(radiusSlider.value);
    });

    // Event untuk tombol Hide Store
    document.getElementById("hide-store-btn").addEventListener("click", clearMarkers);
}

// Fetch toko dari server dan tampilkan pada peta
function fetchAndDisplayStores() {
    // Hapus marker sebelumnya
    clearMarkers();

    // Ambil data toko dari server (fetch_toko.php)
    fetch("fetch_toko.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(store => {
                // Tambahkan marker ke peta
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(store.latitude), lng: parseFloat(store.longitude) },
                    map: map,
                    title: store.nama_toko,
                });

                // Tambahkan InfoWindow untuk marker
                marker.addListener("click", () => {
                    infowindow.setContent(
                        `<strong>${store.nama_toko}</strong><br>${store.alamat}`
                    );
                    infowindow.open(map, marker);
                });

                markers.push(marker); // Simpan marker ke array
            });
        })
        .catch(error => console.error("Error fetching store data:", error));
}

// Filter marker toko berdasarkan radius slider
function filterStoresByRadius(radius) {
    const radiusInMeters = radius * 1000; // Radius dalam meter
    const mapCenter = map.getCenter();

    // Tambahkan atau perbarui lingkaran radius di peta
    if (radiusCircle) {
        radiusCircle.setMap(null);
    }
    radiusCircle = new google.maps.Circle({
        center: mapCenter,
        radius: radiusInMeters,
        map: map,
        fillColor: "#FF0000",
        fillOpacity: 0.3,
        strokeColor: "#FF0000",
        strokeOpacity: 0.5,
    });

    // Tampilkan marker toko yang berada di dalam radius
    markers.forEach(marker => {
        const distance = google.maps.geometry.spherical.computeDistanceBetween(
            mapCenter,
            marker.getPosition()
        );

        if (distance <= radiusInMeters) {
            marker.setMap(map); // Tampilkan marker
        } else {
            marker.setMap(null); // Sembunyikan marker
        }
    });
}

// Hapus semua marker dari peta
function clearMarkers() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    if (radiusCircle) {
        radiusCircle.setMap(null); // Hapus lingkaran radius
    }
}

// Google Maps API Initialization
window.initMap = initMap;