define(['libs/ember', 'libs/jquery.lightbox-0.5', 'libs/moment.min', 'libs/jquery.scrollTo-min', 'libs/jwerty'], function() {
	return {init: function() {
		var center = new google.maps.LatLng(parseFloat(config.lat), parseFloat(config.long));
	    var myOptions = {
	        zoom: 16,
	        center: center,
	        mapTypeId: google.maps.MapTypeId.ROADMAP,
	        streetViewControl: false
	    };
	
	    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	    var markerShadow = new google.maps.MarkerImage('/img/icons/markers/marker-shadow.png', new google.maps.Size(41,41), new google.maps.Point(0,0), new google.maps.Point(13,41));
	
	    new google.maps.Marker({
	        map: map,
	        position: center,
	        icon: '/img/icons/markers/marker.png',
	        shadow: markerShadow
	    });
	
	    var panorama = map.getStreetView();
	    panorama.setPosition(center);
	    panorama.setPov({
	        heading: 265,
	        zoom: 0,
	        pitch: 0
	        }
	    );
	
	    $('#street-view').click(function() {
	        var toggle = panorama.getVisible();
	
	        if (toggle == false) {
	            panorama.setVisible(true);
	            $(this).text('Map');
	        } else {
	            panorama.setVisible(false);
	            $(this).text('Street View');
	        }
	        return false;
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
	
		window.App = AppClass.create();
	
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
				classNames: ['unstyled'],
	            contentBinding: 'App.AppController.tips',
	            itemViewClass: Ember.View.extend({
	            	tagName: ''
	            }),
	            emptyView: Ember.View.extend({
	                template: Ember.Handlebars.compile("Loading tips.. this may take a while.")
	            })
			})
		});
		
		App.tipsView.appendTo('#tipsWrapper');
		
		$('form fieldset input[id*="time"][value=""]:gt(0)').parents('fieldset').each(function(k, v) {
		    var $dt = $(v).parent();
		    var $dd = $dt.prev();
		    
		    $dt.hide();
		    $dd.hide();
		});


		
		$('#addMore').click(function() {
			var $fieldset =  $('form fieldset:hidden:first');
			var $dt = $fieldset.parent();
			var $dd = $dt.prev();

			$dt.slideDown();
			$dd.slideDown();
		});

        $('#like').click(function() {
            var self  = $(this),
                id    = self.attr('data-id'),
                url   = '/pub/',
                label = self.find('span');

            if (label.text() == 'Like') {
                url += 'like/';
            } else {
                url += 'unlike/';
            }

            url += 'id/' + id;

            $.ajax({
                url: url,
                cache: false,
                success: function() {
                    if (label.text() == 'Like') {
                        label.text('Unlike');
                        self.find('i').addClass('icon-white');
                        self.addClass('btn-success');
                    } else {
                        label.text('Like');
                        self.find('i').removeClass('icon-white');
                        self.removeClass('btn-success');
                    }
                }
            });
            return false;
        });

		//var $form = $('form.uniform');
		//
		//jwerty.key('alt+shift+S', function () { $form.submit(); });
		//jwerty.key('alt+shift+U', function () { $('#url').click().focus(); });
		//jwerty.key('alt+shift+E', function () { $('#email').click().focus(); });
		//jwerty.key('alt+shift+C', function () { $('#isChecked').click().focus(); });
	
	
	}}
});