
function codeAddress(address, map, img) {
	var geocoder = new google.maps.Geocoder();
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map, 
            animation: google.maps.Animation.DROP,
            position: results[0].geometry.location,
            icon : img
        });
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
}

function addMarker(map, lat, lng, title) {
	var latlng = new google.maps.LatLng(lat, lng);
	    
	var marker = new google.maps.Marker({
	      position: latlng, 
	      animation: google.maps.Animation.DROP,
	      map: map, 
	      title: title,
	}); 
	return marker;
}

