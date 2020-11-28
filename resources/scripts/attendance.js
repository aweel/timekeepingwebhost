var options1 = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0
};

/*if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
}
else {
    alert("You need to use mobile phone to use this website.");
    location.replace("https://rbgm-medical.com/");
}*/

var imageJsData;

navigator.permissions.query({name:'geolocation'}).then(function(result) {
    if (result.state === 'granted') {
        console.log("geolocation ok")
    } else if (result.state === 'prompt') {
        console.log("geolocation not ok")
    }
    // Don't do anything if the permission was denied.
});



navigator.geolocation.watchPosition(function(position) {
    console.log("i'm tracking you!");
},
function(error) {
    if (error.code == error.PERMISSION_DENIED)
        console.log("you denied me :-(");
});

// Set constraints for the video stream
var constraints = { video: { facingMode: "user" }, audio: false };
// Define constants
const   cameraView = document.querySelector("#camera--view"),
        cameraOutput = document.querySelector("#camera--output"),
        cameraSensor = document.querySelector("#camera--sensor"),
        cameraTriggerIn = document.querySelector("#timeIn"),
        cameraTriggerOut = document.querySelector("#timeOut")
// Access the device camera and stream to cameraView
function cameraStart() {
    navigator.mediaDevices
        .getUserMedia(constraints)
        .then(function(stream) {
            track = stream.getTracks()[0];
            cameraView.srcObject = stream;
        })
        .catch(function(error) {
            console.log(error);
        });
}
// Start the video stream when the window loads
window.addEventListener("load", cameraStart, false);
if (navigator.geolocation)
{
    
    if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
        navigator.mediaDevices.getUserMedia({video: true});
        console.log("Camera detected.");
    }
    
    //Spinning modal
    function modal(){
        $('.modal').modal('show');
        setTimeout(function () {
            console.log('modal working');
            $('.modal').modal('hide');
        }, 1000);
    }

    //For modal
    var load = new Promise((resolve, reject) => {
    // do whatever you require in here.
    // when you are happy to fulfil, call resolve.
    // if error, reject!

    //Spinning modal
    function modal(){
        $('.modal').modal('show');
        setTimeout(function () {
            console.log('modal working');
            $('.modal').modal('hide');
        }, 2000);
    }
    resolve(load); //or
    reject(load);
    });


    //Alert on top of page after insert or error
    function bannerAlert(result, res){
        if (result.type == "error") {
            //$("#result").addClass("alert alert-success fade");
            //$('#result').html("<div class='alert alert-danger'>"+"Not Okay"+"</div>");
            $('#timeError').show();
        } else {
            //show
            switch (res) {
                case "in":
                    $('#timeInSuccess').show();
                    window.setTimeout(function () {
                        $("#timeInSuccess").fadeTo(500, 0).slideUp(500, function () {
                            /*$(this).remove();*/$('#timeInSuccess').hide();
                        });
                    }, 3000)
                    break;

                case "out":
                    $('#timeOutSuccess').show();
                    window.setTimeout(function () {
                        $("#timeOutSuccess").fadeTo(500, 0).slideUp(500, function () {
                            /*$(this).remove();*/$('#timeOutSuccess').hide();
                        });
                    }, 3000)
                    break;
            }
        }
    }

    //Get location and image when user click timeIn & timeOut
    navigator.geolocation.getCurrentPosition(function(position) {

        // make function
        function make(arg){
            // do something

            $('#timeIn').click(function () {
                cameraSensor.width = cameraView.videoWidth;
                cameraSensor.height = cameraView.videoHeight;
                cameraSensor.getContext("2d").drawImage(cameraView, 0, 0);
                //cameraOutput.src = cameraSensor.toDataURL("image/webp");
                imageJsData = cameraSensor.toDataURL("image/webp");
                $.ajax({
                    url: 'savelocation.php',
                    data: {
                        'image': imageJsData,
                        'lat': position.coords.latitude,
                        'lng': position.coords.longitude,
                        'type': "IN",
                        'notes': $('#notes').val()
                    },
                    type: 'POST',
                    beforeSend: function () {
                        modal();
                        console.log("sending image");
                    },
                    success: function(result) {
                        console.log("image sent");
                        bannerAlert(result, "in");
                    }
                });
            });

            $('#timeOut').click(function () {
                cameraSensor.width = cameraView.videoWidth;
                cameraSensor.height = cameraView.videoHeight;
                cameraSensor.getContext("2d").drawImage(cameraView, 0, 0);
                //cameraOutput.src = cameraSensor.toDataURL("image/webp");
                imageJsData = cameraSensor.toDataURL("image/webp");
                $.ajax({
                    url: 'savelocation.php',
                    data: {
                        'lat': position.coords.latitude,
                        'lng': position.coords.longitude,
                        'type': "OUT",
                        'notes': $('#notes').val(),
                        'image': imageJsData,
                    },
                    type: 'POST',
                    beforeSend: function () {
                        modal()
                    },
                    success: function (result) {
                        // If your backend page sends something back
                        bannerAlert(result, "out")
                    }
                });
            });
        }

        // use it!
        load.then((x) => { make(x)}).catch((x) => {console.log(x)})

        // Use the 2 lines below to center your map on user location (you need a map instance at this point)
        //userLoc = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        //map.panTo(userLoc);
    });
}