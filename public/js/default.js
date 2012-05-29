$(document).ready(function() {

    Dunia = Ember.Application.create();
    
    var marker = Em.Object.extend({
    	count: 0,
    	flag: true,
    	incrementCount: function() {
    		this.set('count', this.get('count') + 1);
    	}
    });

    Dunia.markerProperties = Em.Object.create({
        now: marker.create({
        	name:   'now',
        	title:  'Its On Now',
        	icon:   '/img/icons/markers/half.png',
            smallIcon: '/img/icons/markers/half_sml.png',
            zIndex: 20
        }),
        earlier: marker.create({
        	name:   'earlier',
        	title:  'Miss It',
            icon: '/img/icons/markers/empty.png',
            smallIcon: '/img/icons/markers/empty_sml.png',
            zIndex: 18
        }),
        later: marker.create({
        	name:   'later',
        	title:  'Coming Soon',
            icon: '/img/icons/markers/full.png',
            smallIcon: '/img/icons/markers/full_sml.png',
            zIndex: 19
        }),
        none: marker.create({
        	name:   'none',
        	title:  'Me Not Happy',
            icon: '/img/icons/markers/bar.png',
            smallIcon: '/img/icons/markers/beer_sml.png',
            zIndex: 15
        })
    });
    
    Dunia.summary = Em.Object.create({
    	summaries: [
            Dunia.markerProperties.now, 
    	    Dunia.markerProperties.earlier,
    	    Dunia.markerProperties.later, 
    	    Dunia.markerProperties.none
        ]
    });
    

    Dunia.Map = Em.Object.extend({
        longitude:  null,
        latitude:   null,
        markers:    new Array(),
        mapMarkers: new Array(),
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
                url: '/map/fetch-bound/lat/' + latitude + '/long/' + longitude + '/ne/' + map.getBounds().getNorthEast().lat() + ',' + map.getBounds().getNorthEast().lng() + '/sw/' + map.getBounds().getSouthWest().lat() + ',' + map.getBounds().getSouthWest().lng() + '/zoom/' + map.getZoom(),
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
            var markers = this.get('mapMarkers');
            if (markers) {
                for (var i = 0; i < markers.length; i++ ) {
                    markers[i].setMap(null);
                }
            }
            
            Dunia.markerProperties.now.set('count', 0);
            Dunia.markerProperties.earlier.set('count', 0);
            Dunia.markerProperties.later.set('count', 0);
            Dunia.markerProperties.none.set('count', 0);

            this.markers    = new Array();
            this.mapMarkers = new Array();
        },
        
        setMarkersVisible: function(type, flag) {
        	var markers = this.get('mapMarkers');
            if (markers) {
                for (var i = 0; i < markers.length; i++ ) {
                	if (markers[i].type == type) markers[i].setVisible(flag);
                }
            }
        },

        setMarkers: function(markers) {
            var map = this.get('map');
            var infoWindow = this.get('infoWindow');
            
            var $this = this;

            $.each(markers, function(index, marker) {

                var name    = marker.name[0];
                var id      = marker.id[0];
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
                        if (promo.itsOn != 'none') {
                            promos += '<div class="deal"><div class="left">';
                            if (promo.price > 0) {
                                promos += '<sup>$</sup><span class="price">' + promo.price + '</span> ';
                            }
                            promos += '<span class="liquorType">' + promo.liquorType + '</span></div>' +
                                ' <div class="right">' +
                                    '<div class="size"><img src="/img/icons/navbar/50-beaker.png" width="11" height="13" /> ' + (promo.liquorSize == null ? '-' : promo.liquorSize) + '</div>' +
                                    '<div class="time"><img src="/img/icons/navbar/11-clock.png" width="13" height="13" /> ' + promo.timeStart + '-' + promo.timeEnd + '</div>' +
                                '</div></div>';
                        }
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
                    '<a href="' + marker.url[0] + '" title="' + name + '" data-ajax="false">More info >></a>' +
                    '</div>' +
                    '</div>'

                var properties = Dunia.markerProperties[type] || {};
                
                var mapMarker = new google.maps.Marker({
                    map: map,
                    position: point,
                    icon:     properties.icon,
                    shadow:   properties.shadow,
                    zIndex:   properties.zIndex,
                    html:     overlayHtml,
                    idBeer:   id,
                    type:     type
                });
                
                $this.get('mapMarkers').push(mapMarker);
                
                properties.incrementCount();

                google.maps.event.addListener(mapMarker, 'click', function() {
                    infoWindow.setContent(this.html);
                    infoWindow.setOptions({maxWidth: 500});
                    infoWindow.open(map, this);
                });
            });
        }
    });

    Dunia.Location = Em.Object.create({
        address: '',

        setAddress: function(position) {
            var self = this;

            if (position.address) {
                self.set('address', address.streetNumber + ' ' + address.street + ', ' + address.city);
            } else {
                var geocoder = new google.maps.Geocoder();
                var latlng   = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                geocoder.geocode({'latLng': latlng}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var components = results[0].address_components;
                            self.set('address', components[0].long_name + ' ' + components[1].long_name + ', ' + components[2].long_name);
                        }
                    }
                });
            }
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

