define(['libs/jquery', 'libs/jquery.form', 'libs/jquery.lightbox-0.5.min', 'masonry'], function() {
	return {init: function() {
		
		var $table = $('#mailshare');
		$("input[name*='subject']", $table).autocomplete({
			source: '/pub/search/format/json',
			select: function (event, ul) {
				var $this = $(this);
				$form = $this.parents('form');
				$('input[name*="hidden"]', $form).val(ul.item.id);
				$('input[type*="submit"]', $form).enable(true);
			}
		});
		
		$('form', $table).ajaxForm({success: function(responseText, statusText, xhr, $form) {
			var data = jQuery.parseJSON(responseText);
			$('.delete', $form).unbind('click').html('<a target="_blank" href="' + config.url + data.id + '">Go to Pub Edit Page</a>');
		}});
		
		$('input[type*="submit"]', $table).enable(false);
		
		$(".delete").click(function(e) {
			e.preventDefault();
			var $this = $(this);
			var id = $this.attr('data-mail');
			$.get('/mailshare/delete/id/' + id);
			$this.parents('div.grid_4').fadeOut();
		});

		$('#mailshare form .image-region a.image').lightBox({fixedNavigation:true});

		$('#mailshare .clockwise, #mailshare .anticlockwise').click(function(e) {
			e.preventDefault();
			
			var $this = $(this);
			
			var imagePath = $this.attr('data-image-path');

			var $aImg = $this.siblings('a.image');

			var src = $('img', $aImg).attr('src');


			if ($this.hasClass('clockwise')) {
				$.get('/mailshare/rotate/imagePath/{0}/rotate/right'.format(encodeURIComponent(imagePath)), function (responseText) {
						$('img', $aImg).attr('src', src + '&hash=' + responseText);
					}
				);
			} else {
				$.get('/mailshare/rotate/imagePath/{0}/rotate/left'.format(encodeURIComponent(imagePath)), function (responseText) {
						$('img', $aImg).attr('src', src + '&hash=' + responseText);
				    }
				);
			}
			
		});
		
		var $container = $('#container');
		$container.masonry({
	        itemSelector : '.item',
	        isAnimated: false
	    });
	}}
});