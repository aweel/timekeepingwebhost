<?php include "./layout/header.php";
//TODO jomel 02222021 if there is something wrong with connection please check path.ini
$path = parse_ini_file("path.ini", true);
$pathstr = $path['PATH']['path'];
$mapscredentials = parse_ini_file($pathstr, true);

$mapstoken = $mapscredentials['GOOGLE_MAPS_TOKEN']['mapstoken'];

$error = array(
    "err1" => "",
    "err2" => "",
    "subject" => "",
    "message" => ""
);

if (!empty($_SESSION["err1"]))
    $error["err1"] = $_SESSION["err1"];
?>

<main>
  <div class="container">
    <div class="d-flex justify-content-center">
      
      <div class="card text-black bg-light m-1" style="width: 18rem; border-radius:20px;">
        <canvas id="camera--sensor" style="display: none"></canvas>
        <img width="100%" height="10rem" src="//:0" alt="" id="camera--output" style="display: none">
        <video style="border-radius:15px;width:100%;height: 10rem" width="100%" height="10rem" id="camera--view" autoplay playsinline></video> <!-- width="100%" height="auto" -->
        <div class="mx-auto">
          <h5 class="card-title" style="text-align: center; font-weight:lighter" ><?php echo $_SESSION["firstname"]." ".$_SESSION["lastname"] ?></h5>
          <p class="card-text" style="text-align: center; font-weight:lighter" id="clock"></p>
        </div>
      </div>
      
      <div class="card text-black bg-light m-1" style="width: 18rem; border-radius:20px;">
        <div class="card-card-img-top" id="map" style="width:100%;height:10rem;border-radius:15px;"></div>
        <div class="card-body">
          <h5 class="card-title" style="text-align: center; font-weight:lighter" >Coordinates</h5>
          <p class="card-text" id="jsClock"></p>
        </div>
      </div>
    </div>
    
    <div class="row p-1">
      <div class="col text-center">
        <!--<label for="notes">Note </label>-->
        <!--<textarea id="notes" name="notes" placeholder="Note..." rows="2" cols="30" style="font-size: 10pt"></textarea>-->
        <div class="input-group input-group-sw w-50 mx-auto">
          <div class="input-group-prepend">
            <span class="input-group-text">Note...</span>
          </div>
          <textarea id="notes" name="notes" class="form-control" aria-label="Note..."></textarea>
        </div>
      </div>
    
    </div>
    <div class="row align-items-star">
      <div class="col text-center">
        <button type="button" class="btn btn-primary" id="timeIn">IN</button>
      </div>
      <div class="col text-center">
        <button type="button" class="btn btn-primary" id="timeTransfer">TRANSFER</button>
      </div>
      <div class="col text-center">
        <button type="button" class="btn btn-primary" id="timeOut">OUT</button>
      </div>
    </div>
  </div>
</main>
<script defer src="resources/scripts/attendance.js"></script>
<script defer src="resources/scripts/maps.js"></script>
<script defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $mapstoken ?>&callback=initMap"></script>
<script>

        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);

            //Convert to AM/PM
            var hours = h;
            var minutes = m;
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            hours = hours < 10 ? '0' + hours : hours;
            minutes = minutes < 10 ? minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            document.getElementById('clock').innerHTML = strTime;
        }

        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }

        startTime();
        // Start the clock
        /*$( document ).ready(function() {
            startTime();
        });*/

    </script>

<?php include "./layout/footer.php" ?>