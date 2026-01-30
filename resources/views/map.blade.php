<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Live Flight Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/map.css') }}">
</head>
<body>

<div id="map"></div>

<script>
let map, infoWindow;
const planes = {};
const lines  = {};

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 4,
        center: { lat: 50, lng: 10 },
        mapTypeId: "terrain",
        disableDefaultUI: true
        
    });

    infoWindow = new google.maps.InfoWindow();

    loadFlights();
    setInterval(loadFlights, 60000);// updato lidmasinas atrasanas vietu katru minuti
}

function planeIcon(heading = 0, country = "") {
    return {
        path: "M280-80v-100l120-84v-144L80-280v-120l320-224v-176q0-33 23.5-56.5T480-880q33 0 56.5 23.5T560-800v176l320 224v120L560-408v144l120 84v100l-200-60-200 60Z",
        fillColor: countryColor(country),
        fillOpacity: 1,
        strokeWeight: 0,
        scale: 0.05,
        rotation: heading,
        anchor: new google.maps.Point(480, -480)
    };
}

function loadFlights() {
    fetch("/flights")
        .then(r => r.json())
        .then(list => list.forEach(updatePlane))
        .catch(console.error);
}

function updatePlane(f) {
    if (!f.latitude || !f.longitude) return;

    const pos = new google.maps.LatLng(+f.latitude, +f.longitude);

    if (!planes[f.aircraft_id]) {
        const marker = new google.maps.Marker({
            map,
            position: pos,
            icon: planeIcon(f.heading, f.origin_country),
            clickable: true,
            optimized: false
        });

        marker.addListener("click", () => showInfo(marker, f));
        planes[f.aircraft_id] = marker;
    } else {
        const m = planes[f.aircraft_id];
        m.setPosition(pos);
        m.setIcon(planeIcon(f.heading, f.origin_country));
    }

    updateDirectionLine(f.aircraft_id, pos, f.heading);
}

function showInfo(marker, f) {
    infoWindow.setContent(`
        <div class="info-window">
            <div class="header">✈ ${f.callsign || "Unknown Flight"}</div>

            <div class="row">
                <span>From</span>
                <b>${f.origin_country || "Unknown"}</b>
            </div>

            <div class="row">
                <span>Speed</span>
                <b>${f.velocity ?? "—"} m/s</b>
            </div>

            <div class="row">
                <span>Altitude</span>
                <b>${f.geo_altitude ?? f.baro_altitude ?? "—"} m</b>
            </div>

            <div class="row">
                <span>Heading</span>
                <b>${headingText(f.heading)}</b>
            </div>

            <div class="footer">
                Last contact: ${f.last_contact}
            </div>
        </div>
    `);

    infoWindow.open(map, marker);
}

// uzzime liniju uz to pusi kur dodas lidmasina
function updateDirectionLine(id, from, heading = 0) {
    const rad = heading * Math.PI / 180;
    const dist = 300000;//metri

    const to = {
        lat: from.lat() + (dist * Math.cos(rad)) / 111320,
        lng: from.lng() +
            (dist * Math.sin(rad)) /
            (111320 * Math.cos(from.lat() * Math.PI / 180))
    };

    const color = headingColor(heading);

    if (!lines[id]) {
        lines[id] = new google.maps.Polyline({
            map,
            strokeWeight: 3,
            strokeOpacity: 0.9,
            icons: [{
                icon: {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                    scale: 4,
                    strokeColor: color
                },
                offset: "100%"
            }]
        });
    }

    lines[id].setOptions({ strokeColor: color });
    lines[id].setPath([from, to]);
}
//lidmasinu krasas no valsts kura tika veidotas
function countryColor(c = "") {
    c = c.toLowerCase();
    const m = {
        france: "#1e88e5",
        "united states": "#e53935",
        turkey: "#c62828",
        india: "#fb8c00",
        austria: "#d32f2f",
        thailand: "#1565c0",
        mexico: "#2e7d32",
        chile: "#283593",
        germany: "#212121",
        portugal:"#e6127f",
        south_Africa:"#e6d412",
        switzerland:"#ba132c",
        sweden:"#0bc1d9",
        algeria:"#8bf20c",
        estonia:"#0c2ff2",
        greece:"#0c14f2",
    };

    for (const k in m) if (c.includes(k)) return m[k];
    return "#43a047"; // zals defaulta stav ja nav identificeta valstss
}
//virziens uz kuru lidmasinas dodas pec krasam
function headingColor(h = 0) {
    if (h >= 315 || h < 45) return "#1b5e20";   // ziemeli
    if (h >= 45 && h < 135) return "#2e7d32";  // austrumi
    if (h >= 135 && h < 225) return "#ef6c00"; // dienvidi
    return "#33691e";  // rietumi
}
// attelo virzienu tekstu uz info card
function headingText(h = 0) {
    if (h >= 315 || h < 45) return "North";
    if (h >= 45 && h < 135) return "East";
    if (h >= 135 && h < 225) return "South";
    return "West";
}
</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDvJ0s8DCNoBooQv14N2cuPj3xuUjml3s&callback=initMap"
    async defer>
</script>

</body>
</html>
