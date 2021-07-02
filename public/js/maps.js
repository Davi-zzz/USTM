// Traduz as informações
L.drawLocal.draw.toolbar.undo.text = "Deletar último ponto";
L.drawLocal.draw.toolbar.actions.text = "Cancelar";
L.drawLocal.draw.handlers.polygon.tooltip.start =
    "Clique para começar a desenhar a área.";
L.drawLocal.draw.handlers.polygon.tooltip.cont =
    "Clique para continuar a desenhar a área.";
L.drawLocal.draw.handlers.polygon.tooltip.end =
    "Clique no primeiro ponto para fechar esta área.";
L.drawLocal.edit.toolbar.actions.cancel.text = "Cancelar";
L.drawLocal.edit.toolbar.actions.save.text = "Salvar";
L.drawLocal.edit.handlers.remove.tooltip.text = "Clique na área para remover";

var input_coordinates = document.getElementById("coordinates");
var input_parent_area = document.getElementById("parent_area");
var input_city_coordinates = (document.getElementById("city_coordinates") ? document.getElementById("city_coordinates").value.split(",") : null);
var input_color = document.getElementById("color");

var latitude = -10.1835604;
var longitude = -48.3337793;

// center of the map
var center = input_city_coordinates || [latitude, longitude];
// Create the map
var map = L.map("map").setView(center, 13);

// Set up the OSM layer
L.tileLayer("http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}", {
    attribution:
        '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 18,
    subdomains: ["mt0", "mt1", "mt2", "mt3"],
}).addTo(map);

// FeatureGroup is to store editable layers
var drawnItems = new L.FeatureGroup();

map.addLayer(drawnItems);

var drawControl = new L.Control.Draw({
    position: "topright",
    draw: {
        marker: false,
        circle: false,
        polyline: false,
        rectangle: false,
        polygon: {
            allowIntersection: false,
            showArea: true,
            shapeOptions: {
                color: "#f6c23e",
            },
        },
    },
    edit: {
        featureGroup: drawnItems,
        edit: false,
    },
});

map.addControl(drawControl);

if (input_coordinates.value) {
    var parsedJson = JSON.parse(input_coordinates.value);

    var geoJSON = L.geoJson(parsedJson, {
        onEachFeature: onEachFeature,
    });

    //map.fitBounds(geoJSON.getBounds());
}

if (input_parent_area && input_parent_area.value) {
    var parsedJson = JSON.parse(input_parent_area.value);

    var geoJSON = L.geoJson(parsedJson, {});

    drawnItems.addLayer(geoJSON);

    map.fitBounds(geoJSON.getBounds());
}

map.on("draw:created", function (e) {
    var type = e.layerType,
        layer = e.layer;

    var shape = layer.toGeoJSON();
    var shape_for_db = JSON.stringify(shape);

    input_coordinates.value = shape_for_db;

    drawnItems.addLayer(layer);
    // Do whatever else you need to. (save to db; add to map etc)
    map.addLayer(layer);

    var button_draw = document.querySelector(".leaflet-draw-draw-polygon");

    button_draw.style.display = "none";

    var total_area_input = document.querySelector('input[name="total_area"]');

    if (type == "polygon") {
        var area_mt = geodesicArea(layer.getLatLngs()[0]);
        var area_acres = area_mt / 10000;

        if (total_area_input) {
            total_area_input.value = convertFloatToMoeda(area_acres);
        }
    }
});

map.on("draw:deleted", function (e) {
    var button_draw = document.querySelector(".leaflet-draw-draw-polygon");

    button_draw.style.display = "block";

    input_coordinates.value = "";
});

$("#inp-city_id").on("change", function (event) {
    var coords = $(this).select2("data")[0];
    map.panTo(new L.LatLng(coords.latitude, coords.longitude));
});

function onEachFeature(feature, layer) {
    layer.options.color = "#f6c23e";
    drawnItems.addLayer(layer);
}

function geodesicArea(latLngs) {
    var pointsCount = latLngs.length,
        area = 0.0,
        d2r = Math.PI / 180,
        p1,
        p2;

    if (pointsCount > 2) {
        for (var i = 0; i < pointsCount; i++) {
            p1 = latLngs[i];
            p2 = latLngs[(i + 1) % pointsCount];
            area +=
                (p2.lng - p1.lng) *
                d2r *
                (2 + Math.sin(p1.lat * d2r) + Math.sin(p2.lat * d2r));
        }
        area = (area * 6378137.0 * 6378137.0) / 2.0;
    }

    return Math.abs(area);
}
