/**
 * Google Map manager - renders map and put markers
 * Address priority - House Number, Street Direction, Street Name, Street Suffix, City, State, Zip, Country
 */
yii.googleMapManager = (function ($) {
    var pub = {
        nextAddress: 0,
        zeroResult: 0,
        delay: 300,
        bounds: [],
        geocoder: [],
        markerClusterer: false,
        markerClustererOptions: {gridSize: 50, maxZoom: 17},
        markers: [],
        infoWindow: [],
        infoWindowOptions: [],
        containerId: 'map_canvas',
        geocodeData: [],
        mapOptions: {
            center: new google.maps.LatLng(45.666464, 37.686693)
        },
        listeners: [],
        renderEmptyMap: true,
        map: null,
        init: function () {
        },
        initModule: function (options) {
            initOptions(options).done(function () {
                google.maps.event.addDomListener(window, 'load', initializeMap());
            });
        },
        /**
         * Get address and place it on map
         */
        getAddress: function (location, htmlContent, loadMap) {
            pub.drawMarker(location.position, htmlContent);
            pub.delay = 300;
            loadMap();
        },
        updatePosition: function (position) {
            var coordinates = [position];
            if (!pub.isPositionUnique(position)) {
                var latitude = position.lat();
                var lngModify = (Math.abs(Math.cos(latitude)) / 111111) * -5;
                var iteration = 0;
                while (true) {
                    iteration++;
                    var newLng = position.lng() + (lngModify * iteration);
                    position = new google.maps.LatLng(latitude + 0.00001, newLng);
                    if (pub.isPositionUnique(position)) {
                        break;
                    }
                    lngModify *= -1;
                }
            }

            coordinates.push(position);

            var path = new google.maps.Polyline({
                path: coordinates,
                geodesic: true,
                strokeColor: '#00AAFF',
                strokeOpacity: 1.0,
                strokeWeight: 0.4
            });
            path.setMap(pub.map);

            return position;
        },
        isPositionUnique: function (position) {
            //console.log(position);
            if (pub.markers.length != 0) {
                for (var i = 0; i < pub.markers.length; i++) {
                    var existingMarker = pub.markers[i];
                    var pos = existingMarker.getPosition();
                    //if a marker already exists in the same position as this marker
                    if (position.equals(pos)) {
                        return false;
                    }
                }
            }
            return true;
        },
        drawMarker: function (position, htmlContent) {
            var coords = position.split(',');
            var latlng = pub.updatePosition(new google.maps.LatLng(coords[0], coords[1])); //Object { lat: _.Q/this.lat(), lng: _.Q/this.lng() }
            pub.bounds.extend(latlng);

            var marker = new google.maps.Marker({
                map: pub.map,
                position: latlng
            });

            bindInfoWindow(marker, pub.map, pub.infoWindow, htmlContent);
            pub.markerClusterer.addMarker(marker);
            pub.markers.push(marker);

            if ((pub.nextAddress + 1) == pub.geocodeData.length) {
                if (pub.userOptions.mapOptions.center) {
                    pub.map.setCenter(pub.mapOptions.center);
                } else {
                    google.maps.event.addListenerOnce(pub.map, 'bounds_changed', function () {
                        if (pub.map.getZoom() > 17) {
                            pub.map.setZoom(17);
                        }
                    });
                    pub.map.fitBounds(pub.bounds);
                }
            }
        }
    };

    /**
     * Setup googleMapManager properties
     */
    function initOptions(options) {
        var deferred = $.Deferred();
        pub.bounds = new google.maps.LatLngBounds();
        pub.geocoder = new google.maps.Geocoder();
        pub.infoWindow = new google.maps.InfoWindow(pub.infoWindowOptions);
        pub.map = null;
        pub.markerClusterer = null;
        pub.geocodeData = [];
        pub.nextAddress = 0;
        pub.zeroResult = 0;
        pub.markers = [];
        pub.userOptions = options;
        $.extend(true, pub, options);
        deferred.resolve();

        return deferred;
    }

    /**
     * Register listeners
     */
    function registerListeners() {
        for (listener in pub.listeners) {
            if (pub.listeners.hasOwnProperty(listener)) {
                var object = pub.listeners[listener].object;
                var event = pub.listeners[listener].event;
                var handler = pub.listeners[listener].handler;
                google.maps.event.addListener(pub[object], event, handler);
            }
        }
    }

    /**
     * Binds a map marker and infoWindow together on click
     * @param marker
     * @param map
     * @param infoWindow
     * @param html
     */
    function bindInfoWindow(marker, map, infoWindow, html) {
        google.maps.event.addListener(marker, 'click', function () {
            infoWindow.setContent(html['html']);
            infoWindow.open(map, marker);
        });
        if (html['infoOpen'] == true) {
            infoWindow.setContent(html['html']);
            infoWindow.open(map, marker);
        }
    }

    function initializeMap() {
        var container = document.getElementById(pub.containerId);
        container.style.width = '100%';
        container.style.height = '100%';
        pub.map = new google.maps.Map(container, pub.mapOptions);
        setTimeout(function () {
            pub.markerClusterer = new MarkerClusterer(pub.map, [], pub.markerClustererOptions);
            registerListeners();
            loadMap();
        }, 1000);
    }

    /**
     * Dynamic call fetchPlaces function with delay
     */
    function loadMap() {
        setTimeout(function () {
            if (pub.nextAddress < pub.geocodeData.length) {
                var location = {
                    position: pub.geocodeData[pub.nextAddress].position
                };

                var htmlContent = {
                    html : pub.geocodeData[pub.nextAddress].htmlContent,
                    infoOpen : pub.geocodeData[pub.nextAddress].open
                };

                pub.getAddress(location, htmlContent, loadMap);
                pub.nextAddress++;
            }
        }, pub.delay);
    }

    return pub;
})(jQuery);
