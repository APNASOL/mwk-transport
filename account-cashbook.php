<?php include 'Master/head.php';
$date = date("Y-m-d");
$currentdate = explode("-", $date);
$dat = $currentdate[2];
$month = $currentdate[1];
$year = $currentdate[0];

?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Cash Book</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>

                    <li class="breadcrumb-item active">Cash Book</li>
                </ol>
            </nav>

            <?php
                $conn = OpenCon();
                 
                $customers = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
            ?>

            <div class="card">
                <div class="card-body">
                    <!-- Floating Labels Form -->
                    <form class="row mt-3" action="Controllers/CashBookController.php" method="post" onsubmit="handleSubmit(event, this)"> 
                        <div class="col">

                            <div class="form-floating">
                                <select class="form-select" id="customer" name="customer_id" aria-label="customers" required>
                                    <?php while ($customer = mysqli_fetch_array($customers)) {?>
                                    <option selected value="<?php echo $customer['id'] ?>"><?php echo $customer['name'] ?>
                                    </option>
                                    <?php }?>
                                    <option hidden>Select Customer</option>
                                </select>
                                <label for="vehicle">Customers</label>
                            </div>

                        </div>

                        <div class="col">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="amount" name="amount" required placeholder="Amount">
                                <label for="amount">Amount</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                <label for="date">Date</label>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" id="vehicle" name="vehicle_id" required aria-label="vehicles">

                                    <option value="<?php echo $current_vehicle_id ?>">
                                        <?php echo $current_vehicle_number ?>
                                    </option>
                                    <option value="Company">Company</option>

                                    <!-- <option  hidden>Please select vehicle from the list</option> -->
                                </select>
                                <label for="vehicle">Vehicle</label>
                            </div>
                        </div>

                        <div class="col-md-2">

                            <div class="text-center">
                                <input type="hidden" name="customer_transaction" value="customer_transaction">
                                <button type="submit" class="btn btn-dark">Received</button>

                            </div>

                        </div>

                    </form>
                    <!-- Floating Labels Form -->

                </div>
            </div>

            <div class="card">
                <div class="card-body mt-3">
                    <div class="row">
                        

                            <div class="col-md-3">
                                <button type="button" class="btn btn-block btn-success" data-bs-toggle="modal"
                                    data-bs-target="#RecieveFromVehicle">
                                    Recieve from Vehicle
                                </button>
                            </div>

                            <div class="col-md-3">
                                <button type="button" class="btn btn-block btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#giveToVehicle">
                                    Give to Vehicle
                                </button>
                            </div>

                            <div class="col-md-3">
                                <button type="button" class="btn btn-block btn-info" data-bs-toggle="modal"
                                    data-bs-target="#payToPumps">
                                    Pay to Pump
                                </button>
                            </div>

                            <div class="col-md-3">
                                <button type="button" class="btn btn-block btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#returnCash">
                                    Return
                                </button>
                            </div>

                            <!--<div class="col-md-2">-->
                            <!--    <button type="button" class="btn btn-block btn-success" data-bs-toggle="modal"-->
                            <!--        data-bs-target="#proditWithdraw">-->
                            <!--        Withdraw-->
                            <!--    </button>-->
                            <!--</div>-->


                    </div>

                </div>
            </div>

        </div><!-- End Page Title -->
        
        <!-- Modal Give to Vehicle -->
        <div class="modal fade" id="proditWithdraw" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Profit withdraw</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Floating Labels Form -->
                        <form class="row g-3" action="Controllers/ProfitController.php" method="post" onsubmit="handleSubmit(event, this)">

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control"
                                        value="<?php echo $current_vehicle_number;?>">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id;?>">
                                    <label for="vehicle">Vehicle</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                            <div class="form-floating">
                            <select class="form-control chosen" placeholder="" name="month" id="selected_month">
                                <?php
                                            $selectmonthquery = "SELECT * FROM months ORDER BY id";
                                            $selectmonth = mysqli_query($conn, $selectmonthquery);
                                            while ($m_result = mysqli_fetch_array($selectmonth)) { 
                                    if($m_result["code"]==$month){
                                        ?><option value="<?php echo $m_result["name"];?>" selected><?php echo $m_result["name"];?></option>
                                        <?php
                                                }else{
                                                     ?><option value="<?php echo $m_result["name"];?>"><?php echo $m_result["name"]; ?></option>
                                                     <?php
                                                }
                                            }?>
                                        </select>
                                <label for="date">Month</label>
                            </div>
                        </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        required placeholder="Amount">
                                    <label for="amount">Amount</label>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="note" name="note">
                                    <label for="date">Note</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                    <label for="date">Date</label>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="text-center">
                                    <input type="hidden" name="profit_withdraw"
                                        class="btn btn-dark" value="profit_withdraw">
                                        
                                        <button type="submit"
                                        class="btn btn-dark">Submit</button>

                                </div>

                            </div>

                        </form>
                        <!-- Floating Labels Form -->
                    </div>
                </div>
            </div>
          </div>
        </div>
        
        <!-- Modal Give to Vehicle -->
        <div class="modal fade" id="giveToVehicle" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Give To Vehicle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Floating Labels Form -->
                        <form class="row g-3" action="Controllers/CashBookController.php" method="post" onsubmit="handleSubmit(event, this)">

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" readonly class="form-control"
                                        value="<?php echo $current_vehicle_number;?>">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $current_vehicle_id;?>">
                                    <label for="vehicle">Vehicle</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        required placeholder="Amount">
                                    <label for="amount">Amount</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                    <label for="date">Date</label>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="text-center">
                                    <input type="hidden" name="give_to_vehicle_transaction" class="btn btn-dark" value="give_to_vehicle_transaction">
                                    <button type="submit" class="btn btn-dark">Submit</button>

                                </div>

                            </div>

                        </form>
                        <!-- Floating Labels Form -->
                    </div>
                </div>
            </div>
          </div>
        </div>
        <!-- Modal recieve to Vehicle -->
        <div class="modal fade" id="RecieveFromVehicle" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Recieved from Vehicle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Floating Labels Form -->
                        <form class="row g-3" action="Controllers/CashBookController.php" method="post" onsubmit="handleSubmit(event, this)">

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="text" readonly class="form-control"
                                                value="<?php echo $current_vehicle_number;?>">
                                            <input type="hidden" name="vehicle_id"
                                                value="<?php echo $current_vehicle_id;?>">
                                            <label for="vehicle">Vehicle</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        required placeholder="Amount">
                                    <label for="amount">Amount</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                    <label for="date">Date</label>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="text-center">
                                    <input type="hidden" name="receive_from_vehicle_transaction" class="btn btn-dark" value="receive_from_vehicle_transaction">
                                    <button type="submit" class="btn btn-dark">Submit</button>
                                </div>

                            </div>

                        </form>
                        <!-- Floating Labels Form -->
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Modal Pay to pump -->

        <div class="modal fade" id="payToPumps" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pay to pump</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Floating Labels Form -->
                        <form class="row g-3" action="Controllers/CashBookController.php" method="post" onsubmit="handleSubmit(event, this)">

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <select class="form-select" id="pump" name="pump_id" aria-label="vehicles">
                                        <?php
                    $pumps = mysqli_query($conn, "SELECT * FROM pumps where vehicle_id = '$current_vehicle_id'");
                    while ($pump = mysqli_fetch_array($pumps)) {?>
                                        <option value="<?php echo $pump['id'] ?>"><?php echo $pump['name'] ?>
                                        </option>
                                        <?php }?>
                                        <option Selected hidden>Please select pump from the list</option>
                                    </select>
                                    <label for="pump">Pump</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        required placeholder="Amount">
                                    <label for="amount">Amount</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                    <label for="date">Date</label>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="text-center">
                                    <input type="hidden" name="pay_to_pump_transaction" class="btn btn-dark" value="pay_to_pump_transaction">
                                    <button type="submit" class="btn btn-dark">Submit</button>
                                </div>

                            </div>

                        </form>
                        <!-- Floating Labels Form -->
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- end modals -->
        <!--Return cash -->
        <div class="modal fade" id="returnCash" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Return Cash</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Floating Labels Form -->
                        <form class="row g-3" action="Controllers/CashBookController.php" method="post" onsubmit="handleSubmit(event, this)">

                            <div class="col-md-12">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="customer" name="customer_id" aria-label="customers">
                                        <?php 
                  $customers = mysqli_query($conn, "SELECT * FROM customers where vehicle_id = '$current_vehicle_id'");
                  while ($customer = mysqli_fetch_array($customers)) {?>
                                        <option value="<?php echo $customer['id'] ?>"><?php echo $customer['name'] ?>
                                        </option>
                                        <?php }?>
                                        <option Selected hidden>Select Customer</option>
                                    </select>
                                    <label for="vehicle">Customers</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        required placeholder="Amount">
                                    <label for="amount">Amount</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="date" name="date" required placeholder="date">
                                    <label for="date">Date</label>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="text-center">
                                    <input type="hidden" name="return_cash" class="btn btn-dark" value="return_cash">
                                    <button type="submit" class="btn btn-dark">Submit</button>
                                </div>

                            </div>

                        </form>
                        <!-- Floating Labels Form -->
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- end modals -->

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
                        <div class="row">
                        
                        <div class="col-md-7 mt-3">
                            <h5 class="card-title">Select year and month </h5>
                        </div>

                        <div class="col-md-2">
                            <input type="hidden" value="<?php echo $_SESSION['current_vehicle_id'];?>" name="m_c_id" id="m_v_id" />
                            <label>.</label>
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
                            <label>.</label>
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

         
          <div class="monthly_result">
                        <!--  code started for Vehicle boxs -->
 
                        <!-- End Table with stripped rows -->
          </div>


                        </div>
                    </div>

                </div>
            </div>



        </section>

    </main><!-- End #main -->
    

<!-- Modal Give to Vehicle -->
        <div class="modal fade" id="deleteRecord" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Cash Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <p>Are you sure you want to delete this record?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn-danger " id="confirmDelete">Yes</button>
                    </div>
                </div>
            </div>
        </div>
<input type="hidden" id="recordId">
    <!-- ======= Footer ======= -->
    <?php include 'Master/footer.php';?>
    <!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Scripts -->
    <?php include 'Master/scripts.php';?>
    <!-- Scripts -->

    <!-- custom style -->
    <style>
        .gap-2 {
            margin-left: 2px;
        }
    </style>
    <script>

var year_s = document.getElementById("selected_year_v").value;
     var month_s = document.getElementById("selected_month_v").value;


     getMonthlySalesRow(year_s, month_s);

     $(document).on('click', '.m_search', function (e) {

         //alert("Please select year and month both");
         var year = document.getElementById("selected_year_v").value;
         var month = document.getElementById("selected_month_v").value;

         if (year == "" && month == "") {
             alert("Please select year and month both");
         } else {
             getMonthlySalesRow(year, month);
         }

     });
     function getMonthlySalesRow(selected_year, selected_month) {
        $.ajax({
            type: 'POST',
            url: 'get_monthly_cashbook_details.php',
            data: {
                year: selected_year,
                month: selected_month,
            },
            dataType: 'text',
            success: function (response) {
                $('.monthly_result').html(response)

            }
        });
    }
    
    
    // Handle delete button click in the modal
    $('.deleteRecord').on('click', function () {
        var id = $(this).data("id");
        
        $('#recordId').prop("value", id);
    });

    // Handle delete button click in the modal
    $('#confirmDelete').on('click', function () {
        //alert("Delete record ");
        var id = $('#recordId').val(); 
        //alert("Delete record "+id);
        window.location.href = 'Controllers/CashBookController.php?cashbook_id=' + id +'&process=delete';
    });
    
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
                    title: 'Cash Book details',
                    init: function (api, node, config) {
                        $(node).removeClass('btn-primary');
                        $(node).on('click', function () {
                            $(this).addClass('btn-success');
                        });
                    },

                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6] // export only columns
                    },
                    customize: function (doc) {
                        // Add a header to the PDF document
                        doc.content.unshift({
                            text: 'Cash Book details',
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

    function handleSubmit(event, form) {
    //event.preventDefault(); // Prevent the form from submitting immediately

    var button = form.querySelector("button[type='submit']");
    button.disabled = true;
    button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
    }

    </script>

    <style>
        .font-weight {
            font-weight: bold !important;
        }
    </style>
    
</body>

</html>