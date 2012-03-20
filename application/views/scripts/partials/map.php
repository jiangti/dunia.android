<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/js/maps.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
    	var latlng = new google.maps.LatLng(<?php echo $this->options['address']->latitude; ?>, <?php echo $this->options['address']->longitude; ?>);
    	var myOptions = {
        	center: latlng,
        	mapTypeId: google.maps.MapTypeId.ROADMAP,
        	zoom: 17
    };
    var map = new google.maps.Map(document.getElementById("map"), myOptions);

	var marker = new google.maps.Marker({
	      position: latlng, 
	      animation: google.maps.Animation.DROP,
	      map: map
	});
	
    $("#map").css('width','<?php echo isset($this->options['width']) ? $this->options['width'] : 450; ?>');
    $("#map").css('height','<?php echo isset($this->options['height']) ? $this->options['height'] : 300; ?>');
  });

</script>
<div class="mapWrapper">
<div class="round tl"></div><div class="round t"></div><div class="round tr"></div>
<div class="round rt"></div><div class="round br"></div><div class="round b"></div>
<div class="round bl"></div><div class="round lt"></div>
						   
<div id="map"></div>
</div>