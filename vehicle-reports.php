<?php include 'Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Vehicle Reports</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Vehicle</li>
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
            <?php

$connect = OpenCon();
?>

            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>

                                    <h5 class="card-title">Single Vehicle Reports
                                    </h5>
                                </div>
                                <div class="mt-3">

                                    <select class="form-control chosen" placeholder="" name="reporttype"
                                        id="sale_report">
                                        <option value="2" selected>Monthly</option>
                                        <option value="3">Yearly</option>
                                        <option value="4">Custom</option>
                                    </select>
                                </div>


                            </div>


                            <!-- BEGIN: Content-->
                            <div class="app-content content">
                                <div class="content-wrapper">
                                     
                                    <hr>
                                    <div id="result">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END: Content-->





                    </div>
                </div>

            </div>
            </div>



        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include 'Master/footer.php';?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> -->
    <?php include 'Master/scripts.php';?>
    <!-- Scripts -->

</body>
<script>
    $(document).ready(function () {
        var selected_date = document.getElementById("sale_report");
        var selected_date_id = selected_date.value;

        getSalesRow(selected_date_id);

        $('select[name="reporttype"]').on('change', function () {

            var selected_date = document.getElementById("sale_report");

            var selected_date_id = selected_date.value;
            getSalesRow(selected_date_id);

        });

        function getSalesRow(selected_date_id) {
            $.ajax({

                type: 'POST',
                url: 'VehicleReport/get_single_vehicle_report.php',
                data: {
                    id: selected_date_id
                },
                dataType: 'text',
                success: function (response) {
                    $('#result').html(response)

                }
            });

        }
    });
</script>

</html>