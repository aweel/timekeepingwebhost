if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
        $('#timeIn').click(function () {
            $.ajax({
                url: 'savelocation.php',
                data: {
                    'lat': position.coords.latitude,
                    'lng': position.coords.longitude
                },
                type: 'POST',
                success: function (result) {
                    // If your backend page sends something back
                    alert(result);
                }
            });
        });

        $('#timeOut').click(function () {
            $.ajax({
                url: 'savelocation.php',
                data: {
                    'lat': position.coords.latitude,
                    'lng': position.coords.longitude
                },
                type: 'POST',
                success: function (result) {
                    // If your backend page sends something back
                    alert(result);
                }
            });
        });
        // Use the 2 lines below to center your map on user location (you need a map instance at this point)
        //userLoc = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        //map.panTo(userLoc);
    });
}