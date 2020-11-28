if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
    console.log("Camera detected.");
    navigator.mediaDevices.getUserMedia({video: true})
}


// Set constraints for the video stream
var constraints = { video: { facingMode: "user" }, audio: false };
// Define constants
const cameraView = document.querySelector("#camera--view"),
    cameraOutput = document.querySelector("#camera--output"),
    cameraSensor = document.querySelector("#camera--sensor"),
    cameraTriggerIn = document.querySelector("#timeIn"),
    cameraTriggerOut = document.querySelector("#timeOut")
    //cameraTrigger = document.querySelector("#camera--trigger")


// Access the device camera and stream to cameraView
function cameraStart() {
    navigator.mediaDevices
        .getUserMedia(constraints)
        .then(function(stream) {
        track = stream.getTracks()[0];
        cameraView.srcObject = stream;
    })
    .catch(function(error) {
        console.error("Oops. Something is broken.", error);
    });
}
// Take a picture when cameraTrigger is tapped
cameraTriggerIn.onclick = function() {
    cameraSensor.width = cameraView.videoWidth;
    cameraSensor.height = cameraView.videoHeight;
    cameraSensor.getContext("2d").drawImage(cameraView, 0, 0);
    cameraOutput.src = cameraSensor.toDataURL("image/webp");
    let imgIn =(cameraSensor.toDataURL("image/webp"));
    $.ajax({
        type: "POST",
        url: "savelocation.php",
        data: { 'data' : imgIn },
        success: function(data) {
            console.log("image in sent");
        }
    });
    cameraOutput.classList.add("taken");

};

cameraTriggerOut.onclick = function() {
    cameraSensor.width = cameraView.videoWidth;
    cameraSensor.height = cameraView.videoHeight;
    cameraSensor.getContext("2d").drawImage(cameraView, 0, 0);
    cameraOutput.src = cameraSensor.toDataURL("image/webp");
    let imgOut = (cameraSensor.toDataURL("image/webp"));
    $.ajax({
        type: "POST",
        url: "savelocation.php",
        data: { 'data' : imgOut },
        success: function(data) {
            console.log("image out sent");
        }
    });
    cameraOutput.classList.add("taken");

};
// Start the video stream when the window loads
window.addEventListener("load", cameraStart, false);