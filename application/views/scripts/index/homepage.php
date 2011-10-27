<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="/js/maps.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

    	var markers = new Array();

		<?php if (count($this->venues)) {
			foreach($this->venues as $venue) {
				echo "markers.push({id : {$venue->id}, lat : '{$venue->address->latitude}', lng : '{$venue->address->longitude}', name : '" . addslashes($venue->name) . "'});\n";
			}
		}
		?>
		
		$("#map").mapOverlay({coordinates : markers});
    });

</script>

<div id="map"></div>
