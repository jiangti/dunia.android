

<script type="text/javascript">
    $(document).ready(function() {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var address = position.address;
                var html = '';

                if (address) {
                    html += "<p>Ok, we've got you near " + address.streetNumber + ' ' + address.street + ' in ' + address.city + '</p>';
                }

                html += "<div id='venues'><p>Please wait while we find your closest venues...</p>";
                $('#info').html(html);

                $.ajax({
                    type : 'POST',
                    url  : '/venue/locate',
                    data : {longitude: position.coords.longitude, latitude: position.coords.latitude},
                    success : function (data) {
                        $("#venues").html(data);
                        $("#venues").find('ul').listview();
                    }
                });
                
            }, 
            function (msg) {
                var s = document.querySelector('#info');
                s.innerHTML = typeof msg == 'string' ? msg : "failed";
                s.className = 'fail';
                
            });
        } else {
            $('#info').html('Geolocation is not supported :(');
        }
    });

</script>

<div id="info"></div>
