define(['./default', 'libs/jquery', 'libs/jquery.ui', 'jquery.slider', 'libs/bootstrap'], function(init) {
	return {init: function() {
		init.init();
		
		$("#mailShare").popover({
	        placement : 'bottom',
	        content   : "In Mail Share you will find those pictures that fellow users have uploaded but haven't been " +
	                    "processed yet. If you enjoy having the most accurate happy hour information help us by contributing!",
	        title     : "Contribute",
	        trigger   : 'manual'
	    });
	
	    $("#form input").popover({
	        placement : 'bottom',
	        content   : "Use the search box to look for your favorite pubs or explore different suburbs, " +
	            "and filter the result using the time slider to see only promos going on when you are going out",
	        title     : "Find what you need",
	        trigger   : 'manual'
	    });
	
	    $("#summary-bar").popover({
	        placement : 'top',
	        content   : "This links give you information about venues offering happy hours right now, soon, " +
	                    "or those that you just missed. You can click on them to toggle visibility.",
	        title     : "What's going on?",
	        trigger   : 'manual'
	    });
	
	    $("#help").hover(function() {
            var selector = "#form input, #summary-bar, #mailShare";

            // We don't want to show mailShare popOver when menu is on mobile mode
            if ($(this).closest('div').hasClass('in')) {
                selector = "#form input, #summary-bar";
            }
	        $(selector).popover('toggle');
	    });


	    
	    $('select#valueA, select#valueB').selectToUISlider({
	    	labels: 6,
	    	tooltip: false,
	    	sliderOptions: {
	    		stop: function() {
	    			var value0 = $('select#valueA').val();
	    			var value1 = $('select#valueB').val();
	    			AppMap.setMarkersByTime(value0, value1);
	    		}
	    	}
	    });
	    
	    
	    
	    
	    AppMap = Dunia.Map.create({
	        longitude: myOptions.longitude,
	        latitude:  myOptions.latitude,
	        map:       new google.maps.Map(document.getElementById("map_canvas"), myOptions)
	    });

        google.maps.event.addListener(AppMap.map.getStreetView(), 'visible_changed', function() {
            var toggle = AppMap.map.getStreetView().getVisible();
            if (toggle == false) {
                AppMap.setMarkersSize(32, 37);
            } else {
                AppMap.setMarkersSize(250, 289);
            }
        });

	    var input = document.getElementById('location');
	    var autocomplete = new google.maps.places.Autocomplete(input);

	    autocomplete.bindTo('bounds', AppMap.map);

		var view = Ember.View.create({
			templateName: 'summary-bar',
			summaries: Dunia.summary.summaries,
			toggle: function(event) {
				event.preventDefault();
				var object = event.context;

				object.set('flag', !(object.get('flag')));
				AppMap.setMarkersVisible(object.get('name'), object.get('flag'));
			}
		});

		view.appendTo('#summary-bar');

	    google.maps.event.addListener(autocomplete, 'place_changed', function() {
	        var place = autocomplete.getPlace();
	        AppMap.cleanMarkers();
	        AppMap.map.setCenter(place.geometry.location);
	    });
	    
	    google.maps.event.addListener(AppMap.map, 'idle', function(event) {
	    	AppMap.fetchBars();
	    });
	    
	    $('#day-select').change(function() {
	    	AppMap.cleanMarkers();
	    	AppMap.fetchBars();
	    });
	    
	}}
});