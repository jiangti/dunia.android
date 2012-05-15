$(document).ready(function() {

    Dunia = Ember.Application.create();
    Dunia.summary = Em.Object.create({
        now: 0,
        later: 0,
        earlier: 0,
        none: 0
    });

    Dunia.markerProperties = Em.Object.create({
        now: {
            icon:   '/img/icons/markers/half.png',
            zIndex: 20
        },
        earlier: {
            icon: '/img/icons/markers/empty.png',
            zIndex: 18
        },
        later: {
            icon: '/img/icons/markers/full.png',
            zIndex: 19
        },
        none: {
            icon: '/img/icons/markers/bar.png',
            zIndex: 15
        }
    });

    Dunia.Map = Em.Object.extend({
        longitude:  null,
        latitude:   null,
        markers:    new Array(),
        infoWindow: new google.maps.InfoWindow(),

        fetchBars: function() {
            var self      = this;
            var longitude = this.get('longitude');
            var latitude  = this.get('latitude');
            var markers   = this.get('markers');
            var map       = this.get('map');

            $.ajax({
                success: function(data) {

                    var diffData = diff(data, markers);
                    var mapDiff  = diffData.slice();

                    self.setMarkers(mapDiff);

                    $.each(diffData, function(key, value) {
                        markers.push(value);
                    });
                },
                url: '/map/fetch-bound/lat/' + latitude + '/long/' + longitude + '/ne/' + map.getBounds().getNorthEast().lat() + ',' + map.getBounds().getNorthEast().lng() + '/sw/' + map.getBounds().getSouthWest().lat() + ',' + map.getBounds().getSouthWest().lng(),
                dataType: 'json'
            });
        },

        center: function(latitude, longitude) {
            latitude  = typeof latitude  !== 'undefined' ? latitude  : this.get('latitude');
            longitude = typeof longitude !== 'undefined' ? longitude : this.get('longitude');

            var map    = this.get('map');
            var center = new google.maps.LatLng(latitude, longitude);

            new google.maps.Marker({
                map: map,
                position: center,
                icon: '/img/icons/markers/current.png'
            });
            map.panTo(center);
        },

        cleanMarkers: function() {
            var markers = this.get('markers');
            if (markers) {
                for (var i = 0; i < markers.length; i++ ) {
                    markers[i].setMap(null);
                }
            }

            Dunia.summary.set('earlier', 0);
            Dunia.summary.set('now', 0);
            Dunia.summary.set('later', 0);
            Dunia.summary.set('none', 0);
        },

        setMarkers: function(markers) {
            var map = this.get('map');
            var infoWindow = this.get('infoWindow');

            $.each(markers, function(index, marker) {

                var name    = marker.name[0];
                var address = marker.address[0];
                var type    = marker.itsOn[0];
                var promos  = '';

                var point = new google.maps.LatLng(
                    parseFloat(marker.lat[0]),
                    parseFloat(marker.lng[0])
                );

                if (type == 'none') {
                    promos = 'There are no promos available for today';
                } else {
                    for (var j = 0; j < marker.promos.length; j++) {
                        var promo = marker.promos[j];
                        promos += '<div class="deal">';
                        if (promo.price > 0) {
                            promos += '$' + promo.price + ' ';
                        }
                        promos += promo.liquorType + ' (' + promo.timeStart + ' to ' + promo.timeEnd + ')</div>';
                    }
                }

                var overlayHtml = '<div class="overlay">' +
                    '<h2>' + name + '</h2>' +
                    '<div class="overlayContent">' +
                    '<h3>Today\'s deals</h3>' +
                    '<div>' + promos + '</div>' +
                    '</div>' +
                    '<div class="foot">' +
                    '<div class="address">' + address + '</div>' +
                    '<a href="/pub/overview/id/' + marker.id[0] + '" title="' + name + '" data-ajax="false">More info >></a>' +
                    '</div>' +
                    '</div>'

                var properties = Dunia.markerProperties[type] || {};

                Dunia.summary.set(type, Dunia.summary.get(type) + 1);

                var mapMarker = new google.maps.Marker({
                    map: map,
                    position: point,
                    icon:     properties.icon,
                    shadow:   properties.shadow,
                    zIndex:   properties.zIndex,
                    html:     overlayHtml
                });

                google.maps.event.addListener(mapMarker, 'click', function() {
                    infoWindow.setContent(this.html);
                    infoWindow.open(map, this);
                });
            });
        }
    });

});


/**
 * Utility functions
 */

/**
 * Calculates the difference between obj2 and obj1
 * @param {Array} obj2
 * @param {Array} obj1
 * @return {Array}
 */
function diff(obj2, obj1) {
    var result = [];

    var temporary = [];

    $.each(obj1, function (key, value) {
        temporary.push(value.id[0]);
    });

    $.each(obj2, function (key, value) {
        if (temporary.indexOf(value.id[0]) == -1) {
            result.push(value);
        }
    });

    return result;
}

String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};

