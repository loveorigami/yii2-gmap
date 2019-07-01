/**
 * @param id
 * @param options
 * @param markersArray
 */
function multiMarker(id, options, markersArray) {

    var markers = [];

    $.each(markersArray, function (key, val) {
        if (val.open) {
            options.center = val.position;
        }
        markers.push({
            position: val.position,
            content: val.content
        });
    });

    $(id).gmap3(options)
        // because previous doesn't return anything, "then" still get the previous result which is the map
        .then(function (map) {
            google.maps.event.addListener(map, "zoom_changed", function () {
                localStorage.mapZoom = map.getZoom();
            });

            if (localStorage.mapZoom != null) {
                map.setZoom(parseInt(localStorage.mapZoom));
            }
        })
        .then(function (map) {
            google.maps.event.addListener(map, "center_changed", function () {
                //Set local storage variables.
                mapCentre = map.getCenter();

                localStorage.mapLat = mapCentre.lat();
                localStorage.mapLng = mapCentre.lng();
            });

            if (localStorage.mapLat != null && localStorage.mapLng != null) {
                map.setCenter(new google.maps.LatLng(localStorage.mapLat, localStorage.mapLng));
            }
        })
        .infowindow(markers)
        .cluster({
            size: 200,
            markers: markers,
            cb: function (markers) {
                if (markers.length > 1) { // 1 marker stay unchanged (because cb returns nothing)
                    if (markers.length < 25) {
                        return {
                            content: "<div class='cluster cluster-1'>" + markers.length + "</div>",
                            x: -26,
                            y: -26
                        }
                    } else if (markers.length < 50) {
                        return {
                            content: "<div class='cluster cluster-2'>" + markers.length + "</div>",
                            x: -28,
                            y: -28
                        }
                    } else {
                        return {
                            content: "<div class='cluster cluster-3'>" + markers.length + "</div>",
                            x: -33,
                            y: -33
                        }
                    }
                }
            }
        })
        .then(function (cluster) {
            var map = this.get(0);
            var infowindow = this.get(1);
            var markers = cluster.markers();
            markers.forEach(function (item, i) {
                if (map.center.lng() === item.position.lng()) {
                    infowindow[i].open(map, item);
                }
                item.addListener('click', function () {
                    infowindow[i].open(map, item);
                });
            })
        })
        .on({
            click: function (marker, clusterOverlay, cluster, event) {
                if (clusterOverlay) {
                    clusterOverlay.overlay.getMap().fitBounds(clusterOverlay.overlay.getBounds());
                }
            }
        })
    ;
}
