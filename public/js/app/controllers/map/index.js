require(['bootstrap'], function($) {
	$('document').ready(function() {
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
	        $("#form input, #summary-bar, #mailShare").popover('toggle');
	    })
	
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
	});
});