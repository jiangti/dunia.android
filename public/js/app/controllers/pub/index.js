define(['libs/jquery', 'libs/jquery.ui'], function(){
	return {init: function() {
		$( "#tags" ).autocomplete({
			source: '/pub/search/format/json',
			select: function (event, ul) {
				document.location = '/pub/overview/id/' + ul.item.id;
			}
		});
		
		$("#new-pub").click(function() {
			document.location = "/pub/overview/name/" + encodeURIComponent($( "#tags" ).val());
		});
	}}
});