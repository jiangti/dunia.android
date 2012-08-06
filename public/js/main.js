require.config({
	paths: {
        'jquery.min': '/contrib/jquery-ui-1.8.17.custom/js/jquery-ui-1.8.17.custom.min',
        'jquery.slider': '/contrib/selectToUiSlider/js/selectToUISlider.jQuery'
    }
});

// Start the main app logic.
requirejs(['libs/route'], function(app) {
	app.get("/$", function(req) {
		require(['app/controllers/map/index']);
	});
});