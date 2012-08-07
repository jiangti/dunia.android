require.config({
	paths: {
        'jquery.min': '/contrib/jquery-ui-1.8.17.custom/js/jquery-ui-1.8.17.custom.min',
        'jquery.slider': '/contrib/selectToUiSlider/js/selectToUISlider.jQuery',
        'google.map': 'http://maps.googleapis.com/maps/api/js?sensor=true&amp;libraries=places'
    }
});

// Start the main app logic.
requirejs(['libs/route'], function(app) {
		app.get("^/$", function(req) {
			require(['app/controllers/map/index'], function(init) {
			    init.init();
			});
		});
		
		app.get("/pub/email", function(req) {
			require(['app/controllers/pub/email'], function(init) {
				init.init();
			});
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