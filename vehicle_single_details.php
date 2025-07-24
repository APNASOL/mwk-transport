<?php include 'Master/head.php';?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php';?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php';?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>Vehicle Details</h1>
      <div class="d-flex justify-content-end">
        <!-- <a class="btn btn-dark" href="trip-create.php"><i class="bi bi-plus-lg"></i> Add new trip
        </a> -->
      </div>
    </div><!-- End Page Title -->

    <section class="section">
      
      <div class="card">

      <?php if (@$_GET['process'] == 'edit') {

       $date = $_GET['date']; ?>

       <from>
         <input type="hidden" class="form-control" id="selectDate" name="date" value="<?php echo $date; ?>">
       </from>

        <div class="card-body">
           <div id="imageResult">

           </div> 
          <div id="result">

          </div>

        </div>

       <?php

} 

?>

      </div>
    </section>

  </main><!-- End #main -->

  <!-- The Modal -->
<div id="myModal" class="modal">

  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

  <!-- ======= Footer ======= -->
  <?php include 'Master/footer.php';?>
  <!-- End Footer -->

  <div class="modal fade" id="tripDetailsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="details_modal">

                    </div>
                </div>
            </div>
        </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include 'Master/scripts.php';?>
  <script>

function fetchDetails(transaction_id, type) {
        $.ajax({
            type: 'POST',
            url: 'Controllers/VehicleController.php?process=EntryDetails',
            data: {
                transaction_id: transaction_id,
                type: type
            },
            success: function (data) {
                console.log(data);
                $('#details_modal').html(data)
            }
        });
    }


    $(document).ready(function () {

        var date = $('#selectDate').val();
        getRow(date);
        getImageData(date);

        $('#example').DataTable({
            searching: false,
            "paging": false,
            "info": false,
            // Set the default pagination limit to 25
            pageLength: 100,
            order: [],

            dom: 'Bfrtip',
            buttons: [{
                className: 'btn btn-dark',
                extend: 'pdfHtml5',
                text: 'Download details',
                title: 'Trips details',
                init: function(api, node, config) {
                    $(node).removeClass('btn-primary');
                    $(node).on('click', function() {
                        $(this).addClass('btn-success');
                    });
                },

                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6] // export only columns
                },
                customize: function (doc) {
                    // Add a header to the PDF document
                    doc.content.unshift({
                        text: 'Trip details',
                        style: 'header'
                    });

                    // Add a style for the header text
                    doc.styles.header = {
                        fontSize: 18,
                        bold: true,
                        margin: [0, 0, 0, 10]
                    };

                },

                orientation: 'portrait',
                pageSize: 'A4'
            }]
        });

    });

    function getRow(date) {
             $.ajax({
                 type: 'POST',
                 url: 'get_vehicle_single_details.php',
                 data: {
                     date: date,
                 },
                 dataType: 'text',
                 success: function (response) {
                     $('#result').html(response)
                 }
             });

     }

     function getImageData(date) {
        //alert(date);
             $.ajax({
                 type: 'POST',
                 url: 'get_vehicle_details_image.php',
                 data: {
                     date: date,
                 },
                 dataType: 'text',
                 success: function (response) {
                     $('#imageResult').html(response)
                 }
             });

     }


     // Get the modal


</script>
  <!-- Scripts -->

</body>

</html>