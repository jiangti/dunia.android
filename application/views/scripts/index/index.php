<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="/js/maps.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

    	var markers = new Array();

		<?php if (count($this->venues)) {
			// remove this shit once we link to deals
			$i=0;
			$icons = array('img/icons/markers/beer.png', 'img/icons/markers/steak.png', 'img/icons/markers/wine.png', 'img/icons/markers/beer.png');
			
			foreach($this->venues as $venue) {
				echo "markers.push({id : {$venue->id}, lat : '{$venue->address->latitude}', lng : '{$venue->address->longitude}', name : '" . addslashes($venue->name) . "', marker : '" . $this->baseUrl($icons[($i++) % 3]) . "'});\n";
			}
		}
		?>
		
		$("#map").mapOverlay({coordinates : markers});
    });

</script>

<div id="map"></div>