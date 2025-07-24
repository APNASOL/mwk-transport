<?php include 'Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Vehicles</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Vehicles</li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-end">

                <!-- <a href="vehicle-expenses-transaction.php" type="button" class="btn btn-dark">
                    Vehicle Transactions
                </a> -->
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
            <div class="card">
                <div class="card-body">

                    <?php
$conn = OpenCon();
$date = date("Y-m-d");

    $currentdate = explode("-", $date);

    $dat = $currentdate[2];
    $month = $currentdate[1];
    $year = $currentdate[0];
$vehicle_id = $_GET['vehicle_id'];
$vehicle = mysqli_query($conn, "SELECT * FROM vehicles WHERE id =  '$vehicle_id'");
$single_vehicle_details = mysqli_fetch_array($vehicle);
$vehicle_details = "";
$vehicle_no = "";
$vehicle_owner = "";
if ($vehicle_id != "empty") {
    $vehicle_details = mysqli_query($conn, "SELECT * FROM vehicle_details WHERE vehicle_id =  $vehicle_id ORDER BY date");
    $vehicle_no = $single_vehicle_details['number'] ?? "";
    $owner_name = $single_vehicle_details['owner_name'] ?? "";
    ?>
                  <div class="row">

                    <div class="col-md-9">
                       <h5 class="card-title">Vehicle Name: <strong><?php echo $vehicle_no; ?></strong> Owner :  <strong><?php echo $owner_name; ?></strong>
                        
                       </h5>
                    </div>

                    <div class="col-md-3 mt-3">

                        <button type="button" id="monthly" class="btn btn-dark form-control">Monthly Details</button>

                        <button type="button" id="single" class="btn btn-dark form-control">Single Date</button>

                    </div>

                  </div>
                    <?php
}
?>

                  <div id="monthlyDetails">
                        <div class="row">
                            
                            <div class="col-md-7 mt-3">
                                <h5 class="card-title">Select year and month </h5>
                            </div>

                            <div class="col-md-2">
                                <input type="hidden" value="' . $_SESSION['current_vehicle_id'] .'" name="m_c_id" id="m_v_id" />
                                <label>Year</label>
                                <select class="form-control chosen" placeholder="" name="year" id="selected_year_v">
                                    <?php
                                        $selectuserquery = "SELECT * FROM years ORDER BY id";
                                        $selectuser = mysqli_query($conn, $selectuserquery);
                                        while ($p_s_result = mysqli_fetch_array($selectuser)) {
                                            if ($p_s_result["year"] == $year) {?>
                                                <option value="<?php echo $p_s_result["year"];?>" selected><?php echo $p_s_result["year"];?></option>
                                                <?php
                                                    } else {
                                                ?>
                                                    <option value="<?php echo $p_s_result["year"];?>"><?php echo $p_s_result["year"];?></option>
                                                <?php
                                                            }

                                                        }
                                                    ?>
                                    </select>
                            </div>
                                        
                            <div class="col-md-2">
                                <label>Month</label>
                                <select class="form-control chosen" placeholder="" name="year" id="selected_month_v"> 
                                    <?php
                                            $selectmonthquery = "SELECT * FROM months ORDER BY id";
                                            $selectmonth = mysqli_query($conn, $selectmonthquery);
                                            while ($m_result = mysqli_fetch_array($selectmonth)) {
                                                if ($m_result["code"] == $month) { ?>
                                                    <option value="<?php echo $m_result["code"]?>" selected><?php echo $m_result["name"];?></option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="<?php echo $m_result["code"];?>"><?php echo $m_result["name"];?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                </select>
                            </div>
                            <div class="col-md-1 mt-2">
                            <button  class="mt-4 btn btn-dark btn-flat m_search" name="day_search_button">  <i class="bi bi-search"></i> </button>
                                                
                            </div>         
                          </div>
                        </div>

                        <div class="monthly_result" id="monthlyView">
                        </div>
                   </div>

                   <div id="singleDetails">
                        <div class="row">
                            
                            <div class="col-md-8 ps-4">
                                <h5 class="card-title">Select Date </h5>
                            </div>

                            <div class="col-md-3">
                                <input type="hidden" value="' . $_SESSION['current_vehicle_id'] .'" name="m_c_id" id="m_v_id" />
                                
                                <input type="date" class="form-control" name="select_date" id="select_date"/>
                            </div>
                                        
                            <div class="col-md-1 pe-4">
                            <button  class="btn btn-dark btn-flat d_search" name="day_search_button">  <i class="bi bi-search"></i> </button>
                                                
                            </div>         
                          </div>
                        </div>

                        <div class="date_result" id="singleView">
                        </div>
                   </div>
            </div>
        </section>


        <!-- Modal Cash trip Details Modal-->
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

        </div>
        <!-- end modals -->





        <!-- ======= Footer ======= -->
        <?php include 'Master/footer.php';?>
        <!-- End Footer -->

        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                class="bi bi-arrow-up-short"></i></a>

        <!-- Scripts -->
        <?php include 'Master/scripts.php';?>
        <!-- Scripts -->

</body>
<script>
    
    

     $(document).ready(function(){

        var year_s = document.getElementById("selected_year_v").value;
        var month_s = document.getElementById("selected_month_v").value;
        var mc_id_s = document.getElementById("m_v_id").value;

        getMonthlySalesRow(year_s, month_s, mc_id_s);

             $("#single").show();
             $("#monthly").hide();
             $("#singleDetails").hide();
             $("#monthlyDetails").show();
             $("#singleView").hide();
             $("#monthlyView").show();
     });

     document.getElementById("monthly").addEventListener("click", function(event) {
             $("#single").show();
             $("#monthly").hide();
             $("#singleDetails").hide();
             $("#monthlyDetails").show();
             $("#singleView").hide();
             $("#monthlyView").show();
     });

     document.getElementById("single").addEventListener("click", function(event) {
             $("#single").hide();
             $("#monthly").show();
             $("#singleDetails").show();
             $("#monthlyDetails").hide();
             $("#singleView").show();
             $("#monthlyView").hide();
     });

     $(document).on('click', '.m_search', function (e) {

         //alert("Please select year and month both");
         var year = document.getElementById("selected_year_v").value;
         var month = document.getElementById("selected_month_v").value;
         var mc_id = document.getElementById("m_v_id").value;

         if (year == "" && month == "") {
             alert("Please select year and month both");
         } else {
             getMonthlySalesRow(year, month, mc_id);
         }

     });
     function getMonthlySalesRow(selected_year, selected_month, mc_id) {

$.ajax({
    type: 'POST',
    url: 'get_monthly_vehicle_details.php',
    data: {
        year: selected_year,
        month: selected_month,
        cid: mc_id
    },
    dataType: 'text',
    success: function (response) {
        $('.monthly_result').html(response)

    }

});

}


$(document).on('click', '.d_search', function (e) {

        var date = document.getElementById("select_date").value;

        if (date == "") {
            alert("Please select date");
        } else {
            getDailySalesRow(date);
        }

});

function getDailySalesRow(date) {
    //alert(date);
    $.ajax({
    type: 'POST',
    url: 'get_daily_vehicle_details.php',
    data: {
      date: date,
     },
    dataType: 'text',
    success: function (response) {
     $('.date_result').html(response)
     }
    });

}

</script>

<style>
    #amount {
        color: red;
    }


</style>

</html>