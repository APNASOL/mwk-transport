<?php include '../Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include '../Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include '../Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Customer Reports</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Customer</li>
                    <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-end">
                 
            </div>
        </div><!-- End Page Title -->

        <section class="section">
            <?php
          if (@$_GET['successMessage']) {
              ?>
            <div class="alert alert-success bg-success text-light alert-dismissible fade show" role="alert">
                <?php echo $_GET['successMessage']; ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>

            <?php
          }
        ?>


            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Records</h5>

                            <?php
 
 
$connect =  OpenCon();
?>
                            <!-- BEGIN: Content-->
                            <div class="app-content content">
                                <div class="content-wrapper">
                                    <div class="content-body">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <h2><b>Single Customer Reports</b></h2>
                                            </div>
                                            <div class="col-md-2">
                    <h4 align="right">Customer:</h4>
                   </div>
                   <div class="col-sm-3">
                                
                                <select class="form-control chosen target" placeholder="Choose an product" name="c_id" id="c_id">
                                    <?php
                                    $selectuserquery = "SELECT * FROM customers ORDER BY id";
                                    $selectuser = mysqli_query($connect, $selectuserquery);
                                    while ($p_s_result = mysqli_fetch_array($selectuser)) {      ?>
                                        <option value="<?php echo $p_s_result["id"]; ?>">
                                            <?php echo $p_s_result["name"]; ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                                            <div class="col-md-2" style="margin-top:0px;">
                                                <table>
                                                    <tr>
                                                        <td width="5%">
                                                            <select class="form-control chosen" placeholder=""
                                                                name="reporttype" id="sale_report">
                                                                <option value="2" selected>Monthly</option>
                                                                <option value="3">Yearly</option>
                                                                <option value="4">Custom</option>
                                                            </select>
                                                        </td>

                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="result">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: Content-->
                      

                            <script>
                            var selected_date = document.getElementById("sale_report");
                            var selected_date_id = selected_date.value;

                            getSalesRow(selected_date_id);

                            $('select[name="reporttype"]').on('change', function() {

                                var selected_date = document.getElementById("sale_report");

                                var selected_date_id = selected_date.value;
                                getSalesRow(selected_date_id);

                            });

                            function getSalesRow(selected_date_id) {
                              //alert(selected_date_id);
                             
                                $.ajax({
                                    type: 'POST',
                                    url: 'get_single_customer_report.php',
                                    data: {
                                        id: selected_date_id
                                    },
                                    dataType: 'text',
                                    success: function(response) {
                                        $('#result').html(response)

                                    }
                                });

                            }
                            </script>


                        </div>
                    </div>

                </div>
            </div>



        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include '../Master/footer.php';?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <?php include '../Master/scripts.php';?>
    <!-- Scripts -->

</body>

</html>