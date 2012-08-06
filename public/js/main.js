requirejs.config({
	paths: {
		"jquery.ui": "/contrib/jquery-ui-1.8.17.custom/js/jquery-ui-1.8.17.custom.min.js"
	}
});

// Start the main app logic.
requirejs(['libs/ember', 'libs/routes'], function() {
	var app = new routes();
	app.get("/$", function(req) {
		require(['app/controllers/map/index']);
	});
});