define(['libs/ember'], function(Ember) {
	return {init: function() {
		var timeout = null;
		window.Dunia = Ember.Application.create();
	    
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
	        	title:  'It\'s on now',
	        	icon:   '/img/icons/markers/now.svg',
                marker: new google.maps.MarkerImage('/img/icons/markers/now.svg', new google.maps.Size(58, 88), null, null, new google.maps.Size(29, 44)),
	            zIndex: 20
	        }),
	        earlier: marker.create({
	        	name:   'earlier',
	        	title:  'Missed it',
	            icon: '/img/icons/markers/gone.svg',
                marker: new google.maps.MarkerImage('/img/icons/markers/gone.svg', new google.maps.Size(58, 88), null, null, new google.maps.Size(29, 44)),
	            zIndex: 18
	        }),
	        later: marker.create({
	        	name:   'later',
	        	title:  'Coming soon',
	            icon: '/img/icons/markers/coming.svg',
                marker: new google.maps.MarkerImage('/img/icons/markers/coming.svg', new google.maps.Size(58, 88), null, null, new google.maps.Size(29, 44)),
	            zIndex: 19
	        }),
	        none: marker.create({
	        	name:   'none',
	        	title:  'Not today',
	            icon: '/img/icons/markers/notoday.svg',
                marker: new google.maps.MarkerImage('/img/icons/markers/notoday.svg', new google.maps.Size(58, 88), null, null, new google.maps.Size(29, 44)),
	            zIndex: 15
	        }),
	        zero: marker.create({
	        	name:   'zero',
	        	title:  'Zero',
	            icon: '/img/icons/markers/none.svg',
                marker: new google.maps.MarkerImage('/img/icons/markers/none.svg', new google.maps.Size(58, 88), null, null, new google.maps.Size(29, 44)),
	            zIndex: 10
	        })
	    });
	    
	    Dunia.summary = Em.Object.create({
	    	summaries: [
	            Dunia.markerProperties.now, 
	    	    Dunia.markerProperties.earlier,
	    	    Dunia.markerProperties.later, 
	    	    Dunia.markerProperties.none,
	    	    Dunia.markerProperties.zero
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
	            
	            var data = {
	                	lat: map.center.lat(),
	                	long: map.center.lng(),
	                	ne: map.getBounds().getNorthEast().lat() + ',' + map.getBounds().getNorthEast().lng(),
	                	sw: map.getBounds().getSouthWest().lat() + ',' + map.getBounds().getSouthWest().lng(),
	                	zoom: map.getZoom()
	            };
	            
	            if ($('#day-select')) {
	            	data['day'] = $('#day-select').val();
	            }
	
	            $.ajax({
	                success: function(data) {
	
	                    var diffData = diff(data, markers);
	                    var mapDiff  = diffData.slice();
	
	                    self.setMarkers(mapDiff);
	
	                    $.each(diffData, function(key, value) {
	                        markers.push(value);
	                    });
	                },
	                url: '/map/fetch-bound/' ,
	                dataType: 'json',
	                data: data
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
	            Dunia.markerProperties.zero.set('count', 0);
	
	            this.markers    = new Array();
	            this.mapMarkers = new Array();
	        },
	        
	        showAll: function () {
	        	var markers = this.get('mapMarkers');
	            if (markers) {
	                for (var i = 0; i < markers.length; i++ ) {
	                	markers[i].setVisible(true);
	                }
	            }
	        },
	        setMarkersVisible: function(type, flag) {
	        	var markers = this.get('mapMarkers');
	            if (markers) {
	                for (var i = 0; i < markers.length; i++ ) {
	                	if (markers[i].type == type) markers[i].setVisible(flag);
	                }
	            }
	        },
	        
	        setMarkersByTime: function(timeStart, timeEnd) {
	        	var markers = this.get('mapMarkers');
	            if (markers) {
	                for (var i = 0; i < markers.length; i++ ) {
	                	var isVisible = false;
	                	markers[i].promos.forEach(function(value, key) {
	                		if (value.itsOn != 'none') {
	                			if (value.timeStart >= timeStart && value.timeEnd <= timeEnd) {
	                				isVisible = true;
	                			}
	                		}
	                	});
	                	markers[i].setVisible(isVisible);
	                }
	            }
	        },

            setMarkersSize: function(width, height) {
                var markers = this.get('mapMarkers');

                if (markers) {
                    for (var i = 0; i < markers.length; i++ ) {
                        markers[i].icon.size       = new google.maps.Size(2 * width, 2 * height);
                        markers[i].icon.scaledSize = new google.maps.Size(width, height);
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
	                var pubType = marker.icon[0];
	                
	                var point = new google.maps.LatLng(
	                    parseFloat(marker.lat[0]),
	                    parseFloat(marker.lng[0])
	                );
	                
	                if (type == 'none') {
	                    promos += '<h3>Weekly Specials</h3>' +
	                        '<div>' + marker.dealise + '</div>';
	                } else {
	                    promos += '<h3 class="hidden-phone">Today\'s deals</h3>' +
	                        '<div>';
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
	                    promos += '</div>';
	                }
	
	                var overlayHtml = '<div class="overlay">' +
	                    '<h2><a href="' + marker.url[0] + '" title="' + name + '" data-ajax="false">';
	
	                if (pubType) {
	                    overlayHtml += '<img src="' + pubType + '" width="28" height="28" /> ';
	                }
	
	                overlayHtml += name + '</a></h2>' +
	                    '<div class="overlayContent">' + promos + '</div>' +
	                    '<div class="foot">' +
	                    '<div class="address">' + address + '</div>' +
	                    '<a href="' + marker.url[0] + '" title="' + name + '" data-ajax="false">More info >></a>' +
	                    '</div>' +
	                    '</div>'
	
	                var properties = Dunia.markerProperties[type] || {};
	                
	                var markerData = {
	                    map: map,
	                    position: point,
	                    icon:     properties.marker,
	                    shadow:   properties.shadow,
	                    zIndex:   properties.zIndex,
	                    html:     overlayHtml,
	                    idBeer:   id,
	                    type:     type,
	                    idPubType: marker.idPubType
	                };
	                
	                
	                
	                if (marker.promos) {
	                	markerData.promos = marker.promos; 
	                } else {
	                	markerData.promos = [];
	                }
	                
	                var mapMarker = new google.maps.Marker(markerData);
	                
	                mapMarker.setVisible(properties.flag);
	                
	                $this.get('mapMarkers').push(mapMarker);
	                
	                properties.incrementCount();
	
	                google.maps.event.addListener(mapMarker, 'click', function() {
	                    infoWindow.setContent(this.html);
	                    infoWindow.open(map, this);
	                });
	                
	                google.maps.event.addListener(mapMarker, 'mouseover', function() {
	                	var $this = this;
	                	timeout = setTimeout(function() {
	                		infoWindow.setContent($this.html);
	                		infoWindow.open(map, $this);
	                	}, 400);
	                });
	                
	                google.maps.event.addListener(mapMarker, 'mouseout', function() {
	                	if (timeout) {
	                		clearTimeout(timeout);
	                	}
	                	timeout = null; 
	                });
	            });
	        },
	        chikaBowBow: function(elem) { 
	        	/** Becuase its gonna get bouncy. **/
	        	this.mapMarkers.filterProperty('idPubType', elem.value).forEach(function(value, index) {
	        		if (elem.checked) {
	        			value.setAnimation(google.maps.Animation.BOUNCE);
	        		} else {
	        			value.setAnimation(null);
	        		}
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
	}}
});

