<?php include 'Master/head.php';
$conn = OpenCon();

// session_start();
$v_name = $_SESSION['current_vehicle_number'];

$date = date("Y-m-d");
$currentdate = explode("-", $date);
$dat = $currentdate[2];
$month = $currentdate[1];
$year = $currentdate[0];
?>

<body>

  <!-- ======= Header ======= -->
  <?php include 'Master/header.php'; ?>
  <!-- ======= Sidebar ======= -->
  <?php include 'Master/aside.php'; ?>
  <!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">

      <h1><?= __t('ProfitBook')?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html"><?= __t('home')?></a></li>
          <li class="breadcrumb-item"><?= __t('ProfitBook')?></li>
          <li class="breadcrumb-item active"><?= __t('Index')?></li>
        </ol>
      </nav>
      <div class="d-flex justify-content-end">
        <!-- <a class="btn btn-dark" href="fuel-create.php"><i class="bi bi-plus-lg"></i> Add new fuel
        </a> -->
      </div>
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


          <!-- <div class="row">
                        
                        <div class="col-md-7 mt-3">
                            <h5 class="card-title">Select year and month </h5>
                        </div>

                        <div class="col-md-2">
                            <input type="hidden" value="<?php echo $_SESSION['current_vehicle_id']; ?>" name="m_c_id" id="m_v_id" />
                            <label>.</label>
                            <select class="form-control chosen" placeholder="" name="year" id="selected_year_v">
                                <?php
                                $selectuserquery = "SELECT * FROM years ORDER BY id";
                                $selectuser = mysqli_query($conn, $selectuserquery);
                                while ($p_s_result = mysqli_fetch_array($selectuser)) {
                                  if ($p_s_result["year"] == $year) { ?>
                                              <option value="<?php echo $p_s_result["year"]; ?>" selected><?php echo $p_s_result["year"]; ?></option>
                                            <?php
                                  } else {
                                    ?>
                                                <option value="<?php echo $p_s_result["year"]; ?>"><?php echo $p_s_result["year"]; ?></option>
                                            <?php
                                  }

                                }
                                ?>
                                </select>
                        </div>
                                    
                        <div class="col-md-2">
                            <label>.</label>
                            <select class="form-control chosen" placeholder="" name="year" id="selected_month_v"> 
                                <?php
                                $selectmonthquery = "SELECT * FROM months ORDER BY id";
                                $selectmonth = mysqli_query($conn, $selectmonthquery);
                                while ($m_result = mysqli_fetch_array($selectmonth)) {
                                  if ($m_result["code"] == $month) { ?>
                                                <option value="<?php echo $m_result["code"] ?>" selected><?php echo $m_result["name"]; ?></option>
                                                <?php
                                  } else {
                                    ?>
                                                <option value="<?php echo $m_result["code"]; ?>"><?php echo $m_result["name"]; ?></option>
                                                <?php
                                  }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-1 mt-2">
                        <button  class="mt-4 btn btn-dark btn-flat m_search" name="day_search_button">  <i class="bi bi-search"></i> </button>
                                            
                        </div>         
                      </div> -->
        </div>


        <!-- <div class="monthly_result">
                          code started for Vehicle boxs -->

        <!-- End Table with stripped rows -->
        <!--</div> -->
        <!-- End Table with stripped rows -->

        <?php
        $profits = mysqli_query($conn, "SELECT * FROM profit_book where vehicle_id = '$current_vehicle_id'");
        ?>
        <!-- Table with stripped rows -->
        <table class="table table-striped" id="pumpsTable">
          <thead>

            <tr>
              <th scope="col">#</th>
              <th scope="col"> <?= __t('Date')?></th>
              <th scope="col"> <?= __t('Month')?></th>
              <th scope="col"> <?= __t('Note')?></th>
              <th scope="col"> <?= __t('Amount')?></th>
              <th scope="col"> <?= __t('Balance')?></th>
            </tr>

          </thead>
          <tbody>
            <?php
            $i = 1;
            $balance = 0;
            while ($profit = mysqli_fetch_array($profits)) {
              $date = $profit["date"];
              $amount = $profit["cash_out"];
              $month_n = $profit["month"];
              $note = $profit["note"];

              $balance = $balance + $amount;
              $months = array(
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July ',
                'August',
                'September',
                'October',
                'November',
                'December',
              );
              $currentdate = explode("-", $date);

              $dat = $currentdate[2];
              $month = $currentdate[1];
              $year = $currentdate[0];
              $month_name = $months[$month - 1];

              $res = $dat . ' ' . $month_name . ' , ' . $year;

              ?>
              <tr>
                <th scope="row"><?php echo $i++; ?></th>
                <td>
                  <!-- <a href="Controllers/PumpController.php?pump_id=<?php echo $pump['id']; ?>&process=details"  
                        class="btn btn-sm fs-6"
                        title="Details"></a> -->
                  <?php echo $res; ?>

                </td>
                <td><?php echo $month_n; ?>, <?php echo $year; ?></td>
                <td><?php echo $note; ?></td>

                <td><?php echo $amount; ?></td>
                <td><?php echo $balance; ?></td>

              </tr>
            <?php } ?>
          </tbody>
        </table>
        <!-- End Table with stripped rows -->

      </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'Master/footer.php'; ?>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include 'Master/scripts.php'; ?>


  <script>

    // var year_s = document.getElementById("selected_year_v").value;
    // var month_s = document.getElementById("selected_month_v").value;


    // getMonthlySalesRow(year_s, month_s);

    // $(document).on('click', '.m_search', function (e) {

    //   //alert("Please select year and month both");
    //   var year = document.getElementById("selected_year_v").value;
    //   var month = document.getElementById("selected_month_v").value;

    //   if (year == "" && month == "") {
    //     alert("Please select year and month both");
    //   } else {
    //     getMonthlySalesRow(year, month);
    //   }

    // });
    // function getMonthlySalesRow(selected_year, selected_month) {
    //   $.ajax({
    //     type: 'POST',
    //     url: 'get_monthly_fuel_details.php',
    //     data: {
    //       year: selected_year,
    //       month: selected_month,
    //     },
    //     dataType: 'text',
    //     success: function (response) {
    //       $('.monthly_result').html(response)

    //     }
    //   });

    // }

    $(document).ready(function () {

      $('#pumpsTable').DataTable({
        searching: false,
        "paging": false,
        "info": false,
        order: [],

        dom: 'Bfrtip',
        buttons: [{
          className: 'btn btn-dark',
          extend: 'pdfHtml5',
          text: 'Download details',
          title: 'Profit Withdraw details',
          init: function (api, node, config) {
            $(node).removeClass('btn-primary');
            $(node).on('click', function () {
              $(this).addClass('btn-success');
            });
          },

          exportOptions: {
            columns: [0, 1, 2, 3, 4, 5] // export only columns
          },
          customize: function (doc) {
            // Add a header to the PDF document
            doc.content.unshift({
              text: 'Profit Withdraw details',
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
  <!-- Scripts -->

</body>

</html>