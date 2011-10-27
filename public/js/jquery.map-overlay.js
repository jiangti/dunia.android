/**
 * Map Overlay plugin
 * @author Victor
 * 
 * Creates a google map on the specified set of divs and adds an overlay div that will slide on top of it
 */

(function($) { 
	
	$.fn.mapOverlay = function(options) {
		
		$.fn.mapOverlay.STATUS_CLOSED = 0;
		$.fn.mapOverlay.STATUS_OPEN   = 1;
		$.fn.mapOverlay.STATUS_FOLDED = 2;
		
		var defaults = {
			css : {
				background : "url('/img/stripe.png') repeat",
				height	   : '560px',
				position   : 'absolute',
				width	   : '0',
				right	   : '0',
				top		   : '0'//,
				//overflow   : 'hidden'
			},
			width 		: '33%',
			coordinates : new Array(),
			rounded		: true
		};
		
		$.fn.mapOverlay.status = $.fn.mapOverlay.STATUS_CLOSED;
		
		return this.each(function(index) {
			if(options) {
				$.extend(defaults, options);
			}
			
			$(this).wrap('<div class="mapWrapper" />');
			if (defaults.rounded) {
				$('.mapWrapper').prepend('<div class="round tl"></div><div class="round t"></div><div class="round tr"></div>' +
						   '<div class="round rt"></div><div class="round br"></div><div class="round b"></div>' +
						   '<div class="round bl"></div><div class="round lt"></div>');
			}
			
			var overlay = $('<div></div>').addClass('layer');
			overlay.css(defaults.css);
			$(this).after(overlay);
			
			$.fn.mapOverlay.buildUI(overlay, defaults)
			
			var map 	= new google.maps.Map(this, {scrollwheel: false, mapTypeId: google.maps.MapTypeId.ROADMAP, maxZoom: 18});
			var bounds  = new google.maps.LatLngBounds();
			
			for (var i in defaults.coordinates) {
				
				var marker = $.fn.mapOverlay.addMarker(map, defaults.coordinates[i].lat, defaults.coordinates[i].lng, defaults.coordinates[i].name, defaults.coordinates[i].marker);
				google.maps.event.addListener(marker, 'click', (function(id, map, lat, lng) {
					return function() {
						$.ajax({
							url: '/venue/summary/idVenue/' + id,
							success : function(data) {
								overlay.find('.overlay-content').html(data);
								overlay.animate({
									width 		    : defaults.width,
									'padding-left'  : '1em',
									'padding-right' : '1em'
								}, 500, function() {
									overlay.find('.overlay-content').width(overlay.width());
									overlay.find('.overlay-title').width(overlay.width());
							    });
								overlay.corner('left 20px');
								$.fn.mapOverlay.status = $.fn.mapOverlay.STATUS_OPEN;
							}
						});		
						return false;
					}
				}) (defaults.coordinates[i].id, map, defaults.coordinates[i].lat, defaults.coordinates[i].lng, defaults.coordinates[i].marker));

				bounds.extend(new google.maps.LatLng(defaults.coordinates[i].lat, defaults.coordinates[i].lng));
			}
			
			map.fitBounds(bounds);
		});
	};
	
	
	$.fn.mapOverlay.addMarker = function(map, lat, lng, title, icon) {
		var latlng = new google.maps.LatLng(lat, lng);
	    
		var marker = new google.maps.Marker({
		      position: latlng, 
		      animation: google.maps.Animation.DROP,
		      map: map, 
		      title: title
		}); 
		
		if (icon) {
			var icn = new google.maps.MarkerImage(icon);//, null, null, null,
				      // The origin for this image is 0,0.
				      // The anchor for this image is the base of the flagpole at 0,32.
				      //new google.maps.Size(40, 40));//, size?:Size, origin?:Point, anchor?:Point, scaledSize?:Size)
			marker.setIcon(icn);
		}
		
		return marker;
	};
	
	$.fn.mapOverlay.buildUI = function(overlay, defaults) {
		var titleBar 	 = $('<div></div>').addClass('overlay-title');
		var closeButton  = $('<a href="#" class="layout-close"><img src="/img/icns/close.png" alt="Close" width="24" height="24" /></a>');
		var toggleButton = $('<a href="#" class="layout-toggle"><img src="/img/icns/right.png" alt="Toggle" width="24" height="24" /></a>');
		
		titleBar.append(toggleButton);
		titleBar.append(closeButton);
		overlay.prepend(titleBar);
		
		var content = $('<div></div>').addClass('overlay-content');
		overlay.append(content);
		
		
		
		//var toggleHandle =  $('<div class="overlayHandle">s</div>');
		//overlay.prepend(toggleHandle);
		
		
		
		
		closeButton.click(function() {
			overlay.animate({
				width 		    : 0,
				'padding-left'  : 0,
				'padding-right' : 0
			}, function() {
				$('.addIcon').css('position', 'relative');
			});
			return false;
		});
		
		toggleButton.click(function() {
			if($.fn.mapOverlay.status == $.fn.mapOverlay.STATUS_OPEN) {
				overlay.animate({
					width 		    : '10px',
				}, function() {
					$('.addIcon').css('position', 'relative');
				});
				$('.layout-toggle').find('img').attr('src', '/img/icns/left.png');
				$.fn.mapOverlay.status = $.fn.mapOverlay.STATUS_FOLDED;
			} else {
				overlay.animate({
					width 		    : defaults.width,
					'padding-left'  : '1em',
					'padding-right' : '1em'
				});
				$('.addIcon').css('position', 'absolute');
				$('.layout-toggle').find('img').attr('src', '/img/icns/right.png');
				$.fn.mapOverlay.status = $.fn.mapOverlay.STATUS_OPEN;
			}
			return false;
		});
	};
	
	
}) (jQuery);

