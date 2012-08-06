require.config({
  baseUrl: "/js",
  paths: {
	"jquery": "/libs/jquery.js",
    "bootstrap": "/contrib/bootstrap/js/bootstrap.min",
  },
  shim: {
	  'bootstrap': 'jquery'
  }
});

require(['libs/routes'], function() {
	var app = new routes();
	app.get("/$", function(req) {
		require(['app/controllers/map/index'], function() {
			
		});
	});
});
