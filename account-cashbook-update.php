<?php include 'Master/head.php';?>

<body>

    <!-- ======= Header ======= -->
    <?php include 'Master/header.php';?>
    <!-- ======= Sidebar ======= -->
    <?php include 'Master/aside.php';?>
    <!-- End Sidebar-->

    <main id="main" class="main">

        <div class="pagetitle">

            <h1>Update record</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>

                    <li class="breadcrumb-item active">Cash Book Record Update</li>
                </ol>
            </nav>


            <?php

$conn = OpenCon();

$cashbook_record_id = $_GET['cashbook_id'];
$Records = mysqli_query($conn, "SELECT * FROM cashbook WHERE id = '$cashbook_record_id'");
$record = mysqli_fetch_array($Records);

$consume_id = $record['consume_id'];

$cashbook_record_id = $record['id'];

$operation_for = $record['operation_for'];
$date = $record['date'];
$cash_out = $record['cash_out'];
$cash_in = $record['cash_in'];
$notes = $record['notes'];

if ($operation_for == "customer" || $operation_for == 'Cash return') {
    $Consumer_record = mysqli_query($conn, "SELECT * FROM customers WHERE id = '$consume_id'");
    $consumer_obj = mysqli_fetch_array($Consumer_record);
    $consumer = $consumer_obj['name'];
} else if ($operation_for == "Payed to pump") {
    $Consumer_record = mysqli_query($conn, "SELECT * FROM pumps WHERE id = '$consume_id'");
    $consumer_obj = mysqli_fetch_array($Consumer_record);
    $consumer = $consumer_obj['name'];
} else if ($operation_for == "Given to vehicle" || $operation_for == "Recieved from vehicle" || $operation_for == "Company cash" || $operation_for == "Vehicle profit") {
    $Consumer_record = mysqli_query($conn, "SELECT * FROM vehicles WHERE id = '$consume_id'");
    $consumer_obj = mysqli_fetch_array($Consumer_record);
    $consumer = $consumer_obj['number'];

    $consumer_id = $consumer_obj['id'];

}

?>
            <div class="card">
                <div class="card-body p-5">
                    <form class="row g-3" action="Controllers/CashBookController.php" method="post" onsubmit="handleSubmit(event, this)">
                        <input type="hidden" class="form-control" name="cashbook_record_id"
                            value="<?php echo $cashbook_record_id; ?>">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" readonly class="form-control" value="<?php echo $consumer; ?>">
                                
                                <input type="hidden" class="form-control" name="consume_id" required
                                    value="<?php echo $consume_id; ?>">
                                <label for="amount">Consumer</label>

                            </div>
                        </div>

                        <div class="col-md-6 ">
                            <div class="form-floating">
                                <input type="text" readonly class="form-control" id="operation_for" name="operation_for" 
                                    value="<?php echo $operation_for; ?>">
                                <label for="amount">Operation for</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="date" name="date" required
                                    value="<?php echo $date; ?>">
                                <label for="amount">Date</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="hidden" name="old_cash_in" value="<?php echo $cash_in; ?>">
                                
                                <?php if ($notes == 'Returned cash back to customer' || $notes == 'Cash given to vehicle' || $notes == 'Payed to pump' ||$operation_for == 'Vehicle profit') {?>
                                <input type="text" readonly class="form-control" id="cash_in" name="cash_in" required
                                    value="<?php echo $cash_in; ?>">
                                <label for="amount">Cash in</label>
                            </div>
                            <?php } else {?>
                            <input type="text" class="form-control" id="cash_in" name="cash_in"
                                value="<?php echo $cash_in; ?>" required>
                            <label for="amount">Cash in</label>
                        </div>
                        <?php }?>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="hidden" name="old_cash_out" value="<?php echo $cash_out; ?>">
                        <?php if ($notes == 'Returned cash back to customer' || $notes == 'Cash given to vehicle' || $notes == 'Payed to pump' ||$operation_for == 'Vehicle profit') {?>
                        <input type="text" class="form-control" id="cash_out" name="cash_out" required
                            value="<?php echo $cash_out; ?>">
                        <?php } else {?>
                        <input type="text" readonly class="form-control" id="cash_out" name="cash_out"
                            value="<?php echo $cash_out; ?>">
                        <?php }?>
                        <label for="amount">Cash out</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" readonly class="form-control" id="notes" name="notes" required
                            value="<?php echo $notes; ?>">
                        <label for="amount">Notes</label>
                    </div>
                </div>

                <?php if($operation_for == "Vehicle profit"){
                    ?>
                     <p style="color:red;">This is Profit Book Entry, update or delete from profit book</p>
                     <?php
                }else{?>
                    <div class="col-md-9"></div>
                    <div class="col-md-3 mt-1">
                         <input type="hidden" name="chashbook_enteries_update" value="Update">
                         <button type="submit" class="btn btn-dark btn-block form-control" >Update</button>    
            
                    </div>
                </form>
                <br>
                <form class="row g-3" action="Controllers/CashBookController.php" method="post" onsubmit="handleSubmit(event, this)">
                    
                        <input type="hidden"  name="cashbook_record_id" value="<?php echo $cashbook_record_id; ?>">
                        <input type="hidden" name="consume_id" value="<?php echo $consume_id; ?>">
                        <input type="hidden" name="old_cash_out" value="<?php echo $cash_out; ?>">
                        <input type="hidden" name="old_cash_in" value="<?php echo $cash_in; ?>">
                        <input type="hidden" name="operation_for" value="<?php echo $operation_for; ?>">
                     
                    <div class="col-md-9"></div>
                    <div class="col-md-3">
                          <input  type="hidden" name="chashbook_enteries_delete" value="Delete">
                          <button type="submit" class="btn btn-danger btn-block form-control" onclick="return confirmDelete()">Delete</button> 
                    </div>
                            
                </form>
                <?php } ?>
                            
            </div>
        </div>



        </div>


    </main>

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

function handleSubmit(event, form) {
    //event.preventDefault(); // Prevent the form from submitting immediately

        var button = form.querySelector("button[type='submit']");
        button.disabled = true;
        button.innerText = 'Loading...'; // Optional: Change button text to indicate loading

    // Simulate an async operation (e.g., an API call or some processing)
    
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

    function confirmDelete() {
        return confirm("Are you sure you want to delete this record?");
    }

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
    </script>

    <style>
        .font-weight {
            font-weight: bold !important;
        }
    </style>
</body>



</html>