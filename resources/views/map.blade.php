<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .text-center { text-align: center; }
        #map { width: 100%; height: 1000px; }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        let map, activeInfoWindow, markers = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 28.626137, lng: 79.821603 },
                zoom: 15
            });

            // Initialize markers
            const initialMarkers = @json($initialMarkers);

            initialMarkers.forEach((markerData, index) => {
                const marker = new google.maps.Marker({
                    position: markerData.position,
                    label: markerData.label,
                    draggable: markerData.draggable,
                    map
                });
                markers.push(marker);

                const infowindow = new google.maps.InfoWindow({
                    content: `<b>${markerData.position.lat}, ${markerData.position.lng}</b>`,
                });

                marker.addListener("click", () => {
                    if (activeInfoWindow) activeInfoWindow.close();
                    infowindow.open({ anchor: marker, shouldFocus: false, map });
                    activeInfoWindow = infowindow;
                });

                marker.addListener("dragend", (event) => {
                    console.log("Marker moved:", event.latLng.lat(), event.latLng.lng());
                });
            });
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDvJ0s8DCNoBooQv14N2cuPj3xuUjml3s&callback=initMap"
        async defer>
    </script>
</body>
</html>
