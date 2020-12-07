<?php include "layout/header.php";?>

<main id="wrapper">
  <div class="container p-b-5" style=" position: center; ">
    <div class="row" >
      <div class="col" >
        <table id="example" class="table table-striped table-bordered" style="width:100%">
          <thead>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Type</th>
            <th>Note</th>
            <th>Location</th>
            <th>Image</th>
          </tr>
          </thead>
          <tbody>
          <?php
            //Select all records for the whole month
            $query_search = $pdo->prepare(" SELECT id, capturetype, lat, lng, address, notes, image, DATE(capturedate) as mydate, TIME(capturedate) as mytime FROM location LEFT JOIN images ON (location.id=images.locId) WHERE location.empId = :empId ORDER BY location.id DESC LIMIT 26 ");
            $query_search->bindParam(":empId", $param_empId2, PDO::PARAM_INT);
            $param_empId2 = $_SESSION["empId"];
            $query_search->execute();
            
            if($query_search->rowCount() >= 1) {
              if($row2 = $query_search->fetchAll()) {
                ?>
                <?php foreach($row2 as $rows2):?>
                  <tr>
                    <td><?php echo date('Y-m-d', strtotime($rows2["mydate"])) ;?></td>
                    <td><?php echo date('h:i A', strtotime($rows2["mytime"])) ;?></td>
                    <td><?php echo $rows2["capturetype"] ;?></td>
                    <td><?php echo $rows2["notes"] ;?></td>
                    <td><a href="https://www.google.com/search?q=<?php echo $rows2["lat"].", ".$rows2["lng"] ;?>"><?php echo  $rows2["address"]?></a></td>
                    <td><img style="width: 100px;height: 100px" src="<?php echo $rows2["image"] ;?>" alt="image"></td>
                  </tr>
                <?php endforeach;
              }
            }
          ?>
          </tbody>
          <tfoot>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Type</th>
            <th>Note</th>
            <th>Location</th>
            <th>Image</th>
          </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</main>

<script type="text/javascript">
  function formatAMPM(date) {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    return hours + ':' + minutes + ' ' + ampm;
  }
</script>
<script type="text/javascript">
  
  //Adjust table based on viewport
  function getViewport() {
    let iHeight = window.screen.height;
    let iWidth = window.screen.width;
    
    if (iWidth === 1024 && iHeight === 1366) {
      return "74vh";
    }
    else if (iWidth === 768 && iHeight === 1024) {
      return "62vh";
    }
    else if (iWidth === 414 && iHeight === 896) {
      return "48vh";
    }
    else if (iWidth === 412 && iHeight === 869) {
      return "47vh";
    }
    else if (iWidth === 480 && iHeight === 853) {
      return "46vh";
    }
    else if (iWidth === 412 && iHeight === 847) {
      return "45vh";
    }
    else if (iWidth === 412 && iHeight === 846) {
      return "45vh";
    }
    else if (iWidth === 412 && iHeight === 824) {
      return "44vh";
    }
    else if (iWidth === 375 && iHeight === 812) {
      return "43vh";
    }
    else if (iWidth === 360 && iHeight === 740) {
      return "38vh";
    }
    else if (iWidth === 414 && iHeight === 736) {
      return "38vh";
    }
    else if (iWidth === 412 && iHeight === 732) {
      return "37vh";
    }
    else if (iWidth === 375 && iHeight === 667) {
      return "31vh";
    }
    else if (iWidth === 280 && iHeight === 653) {
      return "30vh";
    }
    else if (iWidth === 360 && iHeight === 640) {
      return "28vh";
    }
    else if (iWidth === 320 && iHeight === 568) {
      return "20vh";
    }
    else if (iWidth === 320 && iHeight === 480) {
      return "30vh";
    }
    else if (iHeight <= 480) {
      return "9vh";
    }
    return '35vh';
  }
  
  //Datatable options
  $(document).ready(function() {
    $('#example').DataTable({
      responsive: true,
      dom:  "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
          //"<'row'<'col-sm-12' tr>>" +
          //for hr
          "<'row'<'col-sm-10 col-md-8'tr><'col-sm-2 col-md-4'Q>>" +
          //"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
          "<'row'<'col-sm-12 col-md-4'i><'col-sm-12 col-md-8'p>>",
      "scrollY":  getViewport(), //"480px",
      "scrollCollapse": true,
      buttons: [
        {
          extend: 'excel',
          title: 'Timekeeping',
          filename: 'Record timekeeping',
          footer: false
        },
        'copy', 'csv', 'pdf'
      ],
      "order": [[ 0, "desc" ]],
      "autoWidth": true,
      "autoHeight": true,
    });
    $('#example').DataTable().columns.adjust();
  });
</script>

<?php include "layout/footer.php" ?>


