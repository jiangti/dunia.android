requirejs.config({
});

// Start the main app logic.
requirejs(['libs/ember', 'libs/routes'], function() {
	var app = new routes();
	app.get("/$", function(req) {
		require(['app/controllers/map/index']);
	});
});