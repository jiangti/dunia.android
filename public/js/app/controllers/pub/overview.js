define(['libs/ember', 'libs/jquery.lightbox-0.5', 'libs/moment.min', 'libs/jquery.scrollTo-min'], function() {
	return {init: function() {
	var center = new google.maps.LatLng(parseFloat(config.lat), parseFloat(config.long));
    var myOptions = {
        zoom: 16,
        center: center,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    var markerShadow = new google.maps.MarkerImage('/img/icons/markers/marker-shadow.png', new google.maps.Size(41,41), new google.maps.Point(0,0), new google.maps.Point(13,41));

    new google.maps.Marker({
        map: map,
        position: center,
        icon: '/img/icons/markers/marker.png',
        shadow: markerShadow
    });


    $('#edit-section-button').click(function(e) {
		e.preventDefault();
		$.scrollTo($('#edit-section'), 500);
	});
	
    $('a.open').click(function() {
		popup = window.open($('#url').val() , 'popup', 'height=600,width=800,scrollbars=1,status=1,location=1,resizable=1');
		if (window.focus) { popup.focus(); }
		return false;
	});
	

	$('form fieldset legend > a').click(function(e) {
		e.preventDefault();
		var $this = $(this);
		$this.parents('fieldset').remove();
	});

	$('#image-region a.image').lightBox({fixedNavigation:true});

	AppClass = Ember.Application.extend({
		ready: function() {
			this.AppController.initialize(config.pub);
		}
	});

	App = AppClass.create();

	App.TipProxyClass = Ember.ArrayProxy.extend({
		
	});

	App.TipClass = Ember.Object.extend();

	App.AppControllerClass = Ember.Object.extend({
		row: null,
		tips: App.TipProxyClass.create({content: []}),
		tipUrl: config.tipUrl,
		initialize: function(data) {
			this.row = data;
			this.loadTips();
		},
		loadTips: function() {
			var $this = this;
			$.ajax({
				url: this.tipUrl,
				data: {idFoursquare: this.row.idFoursquare},
				success: function(data) {
                    if (data.length) {
                        data.forEach(function(value, index) {
                            value.user.canonicalUrl = 'https://foursquare.com/user/' + value.user.id;
                            value.date              = moment(value.createdAt * 1000).format('MMMM D, YYYY');

                            App.TipClass.create(value);
                            $this.tips.pushObject(App.TipClass.create(value));
                        });
                        $('#tipsWrapper').prepend('<h2>Tips' +
                            '<div class="right">' +
                            '<img src="/img/icons/social/poweredByFoursquare_gray.png" width="128" height="14" />' +
                            '</div>' +
                            '</h2>');
                    }
				}
			});
		}
	});

	App.AppController = App.AppControllerClass.create({});

	App.tipsView = Ember.View.create({
		templateName: 'tipsView',
		tipsCollectionView: Ember.CollectionView.extend({
			tagName: 'ul',
            contentBinding: 'App.AppController.tips',
            itemViewClass: Ember.View.extend({
            	tagName: ''
            })
		})
	});
	
	App.tipsView.appendTo('#tipsWrapper');

	$('.promo.glow > .time[title]').tooltip();
	
	}}
});