<?php include 'Master/head.php';?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php';?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php';?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1>pumps</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item">pumps</li>
          <li class="breadcrumb-item active">Details</li>
        </ol>
      </nav>
      <!-- <div class="d-flex justify-content-end">
        <a class="btn btn-dark" href="pump-create.php"><i class="bi bi-plus-lg"></i> Add new pump
        </a>
      </div> -->
    </div><!-- End Page Title -->

    <section class="section">
      <?php
if (@$_GET['successMessage']) {
    ?>
      <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
        <?php echo $_GET['successMessage']; ?>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

      <?php
}
?>
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">pumps</h5>

          <?php
$conn = OpenCon();
$pump_id = $_GET['pump_id'];
$pump = mysqli_query($conn, "SELECT * FROM pumps WHERE id =  $pump_id");
$pump_name = "";
$pumps_details = mysqli_query($conn, "SELECT * FROM pumps_details WHERE pump_id =  $pump_id");
?>
          <div class="card card-body p-4">
            <?php
while ($single = mysqli_fetch_array($pump)) {
    $pump_name = $single['name'];
    echo "<strong>Name: " . $single['name'] . "</strong>";

}?>
          </div>

        </div>


        <!-- Table with stripped rows -->
        <div class="card  card-body">
          <table class="table table-striped datatable" id="example">
            <thead>

              <tr>
                <th scope="col">#</th>
                <th scope="col">Date</th></th>
                <th scope="col">Notes</th>
                <th scope="col">Type</th>
                <th scope="col">Credit</th>
                <th scope="col">Debit</th>
                <th scope="col">Balance</th>
              </tr>

            </thead>
            <tbody>
              <?php
$i = 1;
$balance = 0;
while ($pump = mysqli_fetch_array($pumps_details)) {
    if ($pump['type'] == 'Trip') {
        $balance = $balance + $pump['debit'];
    } else {
        $balance = $balance + $pump['debit'] - $pump['credit'];
    }
    if ($pump['type'] == 'Company due') {
        ?>
              <tr class="bg-danger" >
                <th class="text-white" scope="row"><?php echo $i++; ?></th>

                <td class="text-white"><?php echo $pump['date']; ?></td>
                <td class="text-white"><?php echo $pump['note']; ?></td>
                <td class="text-white"><?php echo $pump['type']; ?></td>
                <td class="text-white"><?php echo $pump['credit']; ?></td>
                <td class="text-white"><?php echo $pump['debit']; ?></td>
                <td class="text-white"><?php echo $balance; ?></td>

              </tr>
              <?php } else if ($pump['type'] == 'Cash payment') {
        ?>
              <tr class="bg-success" >
                <th class="text-white" scope="row"><?php echo $i++; ?></th>

                <td class="text-white"><?php echo $pump['date']; ?></td>
                <td class="text-white"><?php echo $pump['note']; ?></td>
                <td class="text-white"><?php echo $pump['type']; ?></td>
                <td class="text-white"><?php echo $pump['credit']; ?></td>
                <td class="text-white"><?php echo $pump['debit']; ?></td>
                <td class="text-white"><?php echo $balance; ?></td>

              </tr>
              <?php } else {?>
              <tr>
                <th scope="row"><?php echo $i++; ?></th>

                <td><?php echo $pump['date']; ?></td>
                <td><?php echo $pump['note']; ?></td>
                <td><?php echo $pump['type']; ?></td>
                <td><?php echo $pump['credit']; ?></td>
                <td><?php echo $pump['debit']; ?></td>
                <td><?php echo $balance; ?></td>

              </tr>
                <?php }}?>
            </tbody>
          </table>
        </div>
        <!-- End Table with stripped rows -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'Master/footer.php';?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include 'Master/scripts.php';?>
  <!-- Scripts -->
<style>

</style>
<script>
    $(document).ready(function () {

        $('#example').DataTable({
            searching: false,
            "paging": false,
            "info": false,
            order: [],

            dom: 'Bfrtip',
            buttons: [{
                className: 'btn btn-dark',
                extend: 'pdfHtml5',
                text: 'Download details',
                title: '<?php echo $pump_name; ?>',
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
                        text: 'Pump details',
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
</script>
</body>

</html>