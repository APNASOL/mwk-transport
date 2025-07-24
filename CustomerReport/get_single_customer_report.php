 <?php
include '../Controllers/DatabaseController.php';
$connect = OpenCon();
session_start();
if (isset($_POST['id'])) {
    $table_result = '';
    $id = $_POST['id'];
    $date = date("Y-m-d");

    $currentdate = explode("-", $date);

    $dat = $currentdate[2];
    $month = $currentdate[1];
    $year = $currentdate[0];

   if ($id == 2) {

        $table_result .= '
            <div class="row">
                    <div class="col-md-5 mt-3">
                    <h5 class="card-title">Select customer year and month
                                    </h5>

                   </div>
                   <div class="col-md-7">
                      <table class="table">
                         <tr>

                          <td>
                            <label>Customer</label>
                             <select class="form-control chosen target" placeholder="Choose an product" name="m_c_id" id="m_c_id">';
        $selectuserquery = "SELECT * FROM customers where vehicle_id =".$_SESSION['current_vehicle_id']." ORDER BY id ";
        $selectuser = mysqli_query($connect, $selectuserquery);
        while ($p_s_result = mysqli_fetch_array($selectuser)) {
            $table_result .= '<option value="' . $p_s_result["id"] . '">
                                                    ' . $p_s_result["name"] . ' </option>';
        }
        $table_result .= '
                                        </select>
                          </td>

                           <td colspan="2">
                           <label>Year</label>
                              <select class="form-control chosen" placeholder="" name="year" id="selected_year">';
        $selectuserquery = "SELECT * FROM years ORDER BY id";
        $selectuser = mysqli_query($connect, $selectuserquery);
        while ($p_s_result = mysqli_fetch_array($selectuser)) {

            if ($p_s_result["year"] == $year) {
                $table_result .= '<option value="' . $p_s_result["year"] . '" selected>' . $p_s_result["year"] . '</option>';
            } else {
                $table_result .= '<option value="' . $p_s_result["year"] . '">' . $p_s_result["year"] . '</option>';
            }

        }
        $table_result .= '
                                        </select></td>

                          <td colspan="2">
                          <label>Month</label>
                              <select class="form-control chosen" placeholder="" name="year" id="selected_month"> ';
        $selectmonthquery = "SELECT * FROM months ORDER BY id";
        $selectmonth = mysqli_query($connect, $selectmonthquery);
        while ($m_result = mysqli_fetch_array($selectmonth)) {
            if ($m_result["code"] == $month) {
                $table_result .= '<option value="' . $m_result["code"] . '" selected>' . $m_result["name"] . '</option>';
            } else {
                $table_result .= '<option value="' . $m_result["code"] . '">' . $m_result["name"] . '</option>';
            }
        }
        $table_result .= '
                                        </select></td>
                          <td >

                              <button  class="mt-4 btn btn-dark btn-flat m_search" name="day_search_button">  <i class="bi bi-search"></i> </button>
                          </td>

                          </tr>
                       </table>
                   </div>
                </div>
                   <hr>
                   <div class="monthly_result">
                   <div class="card">
              <div class="card-content collapse show">
                <div class=" card-dashboard dataTables_wrapper dt-bootstrap">
                   <div class="table-responsive">
                     <table id="example" class="table table-bordered">
                         <thead>
                         <tr>
                         <th>Date</th>
                         <th>Load type</th>
                         <th>Weight</th>
                         <th>PPT</th>
                         <th>Expense</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                          </tr>
                             </thead>
                             <tr>
                             <td colspan="8" align="center"> No Record found</td>
                             </tr>
                             </table>
                             </div>
                             </div>
                             </div>
                             </div>
                             </div>'
        ;
        echo $table_result;

    }
    //last 7 days search
    else if ($id == 3) {

        $table_result .= '
            <div class="row">
                    <div class="col-md-5 mt-3">
                    <h5 class="card-title">Select year & customer</h5>
                    </div>
                   <div class="col-md-7">
                      <table class="table">
                         <tr>

                          <td>
                          <label>Customer</label>
                             <select class="form-control chosen target" placeholder="Choose an product" name="y_c_id" id="y_c_id">';
        $selectuserquery = "SELECT * FROM customers where vehicle_id =".$_SESSION['current_vehicle_id']." ORDER BY id";
        $selectuser = mysqli_query($connect, $selectuserquery);
        while ($p_s_result = mysqli_fetch_array($selectuser)) {
            $table_result .= '<option value="' . $p_s_result["id"] . '">
                                                                            ' . $p_s_result["name"] . ' </option>';
        }
        $table_result .= '
                                                                </select>
                                                </td>

                                                <td >
                                                <label>Year</label>
                                                    <select class="form-control chosen" placeholder="" name="year" id="selected_year_y">';
        $selectuserquery = "SELECT * FROM years ORDER BY id";
        $selectuser = mysqli_query($connect, $selectuserquery);
        while ($p_s_result = mysqli_fetch_array($selectuser)) {

            if ($p_s_result["year"] == $year) {
                $table_result .= '<option value="' . $p_s_result["year"] . '" selected>' . $p_s_result["year"] . '</option>';
            } else {
                $table_result .= '<option value="' . $p_s_result["year"] . '">' . $p_s_result["year"] . '</option>';
            }

        }
        $table_result .= '
                                        </select>
                                    </td>
                                                <td>

                                                    <button  class="mt-4 btn btn-dark btn-flat y_search" name="day_search_button"><i class="bi bi-search"></i>  </button>
                                                </td>

                                                </tr>
                                            </table>
                                        </div>
                                        </div>
                            </div>
                   <hr>
                   <div class="monthly_result">
                   <div class="card">
                        <div class="card-content collapse show">
                            <div class=" card-dashboard dataTables_wrapper dt-bootstrap">
                            <div class="table-responsive">
                                <table id="example" class="table table-bordered">
                                    <thead>
                                   <tr>
                         <th>Date</th>
                         <th>Load type</th>
                         <th>Weight</th>
                         <th>PPT</th>
                         <th>Expense</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                          </tr>
                                        </thead>
                                        <tr>
                                        <td colspan="8" align="center"> No Record found</td>
                                        </tr>
                                        </table>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>'
        ;
        echo $table_result;

    } else if ($id == 4) {

        $table_result .= '
            <div class="row">
                    <div class="col-md-4">
                        <h5 class="card-title"> Select customer & date range </h5>
                   </div>
                   <div class="col-md-8 table-responsive">
                      <table class="table">
                         <tr>

                          <td>
                          <label>Customer</label>
                             <select class="form-control chosen target" placeholder="Choose an product" name="c_c_id" id="c_c_id">';
        $selectuserquery = "SELECT * FROM customers where vehicle_id =".$_SESSION['current_vehicle_id']." ORDER BY id";
        $selectuser = mysqli_query($connect, $selectuserquery);
        while ($p_s_result = mysqli_fetch_array($selectuser)) {
            $table_result .= '<option value="' . $p_s_result["id"] . '">
                                                    ' . $p_s_result["name"] . ' </option>';
        }
        $table_result .= '
                                        </select>
                          </td>


                          <td>
                            <label>From date</label>
                              <input type="date" class="form-control" name="day" id="from_date" required>
                          </td>

                          <td>
                          <label>From date</label>
                              <input type="date" class="form-control" name="day" id="to_date" required>
                          </td>

                          <td >

                              <button  class="mt-4 btn btn-dark btn-flat c_search" name="day_search_button"><i class="bi bi-search"></i></button>
                          </td>

                          </tr>
                       </table>
                   </div>
                </div>
                   <hr>
                   <div class="monthly_result">
                   <div class="card">
              <div class="card-content collapse show">
                <div class=" card-dashboard dataTables_wrapper dt-bootstrap">
                   <div class="table-responsive">
                     <table id="example" class="table table-bordered">
                         <thead>
                        <tr>
                         <th>Date</th>
                         <th>Load type</th>
                         <th>Weight</th>
                         <th>PPT</th>
                         <th>Expense</th>
                         <th>Credit</th>
                         <th>Debit</th>
                         <th>Balance</th>
                          </tr>
                             </thead>
                             <tr>
                             <td colspan="8" align="center"> No Record found</td>
                             </tr>
                             </table>
                             </div>
                             </div>
                             </div>
                             </div>
                             </div>'
        ;
        echo $table_result;
    }

}
?>

 <script>
     $(document).on('click', '.d_search', function (e) {

         var today = document.getElementById("d_date").value;
         if (today == "") {
             alert("Please select date");
         } else {

             getDailySalesRow(today);

         }

     });

     var year_s = document.getElementById("selected_year").value;
     var month_s = document.getElementById("selected_month").value;
     var mc_id_s = document.getElementById("m_c_id").value;
     getMonthlySalesRow(year_s, month_s, mc_id_s);

     $(document).on('click', '.m_search', function (e) {


         var year = document.getElementById("selected_year").value;
         var month = document.getElementById("selected_month").value;
         var mc_id = document.getElementById("m_c_id").value;


         if (year == "" && month == "") {
             alert("Please select year and month both");
         } else {

             getMonthlySalesRow(year, month, mc_id);


         }

     });

     $(document).on('click', '.c_search', function (e) {

         var fromdate = document.getElementById("from_date").value;
         var todate = document.getElementById("to_date").value;
         var cc_id = document.getElementById("c_c_id").value;

         if (fromdate == "" && todate == "") {
             alert("Please select both date!");
         } else if (fromdate > todate) {
             alert("From date must be less then to date!");
         } else {

             getCustomSalesRow(fromdate, todate, cc_id);


         }

     });

     $(document).on('click', '.y_search', function (e) {

         var year = document.getElementById("selected_year_y").value;
         var yc_id = document.getElementById("y_c_id").value;
         //alert(yc_id);

         if (year == "") {
             alert("Please select year");
         } else {

             getYearlySalesRow(year, yc_id);


         }

     });

     function getDailySalesRow(selected_date) {

         $.ajax({
             type: 'POST',
             url: 'CustomerReport/get_daily_shop_purchase_report.php',
             data: {
                 date: selected_date
             },
             dataType: 'text',
             success: function (response) {
                 $('.daily_result').html(response)

             }

         });

     }

     function getMonthlySalesRow(selected_year, selected_month, mc_id) {

         $.ajax({
             type: 'POST',
             url: 'CustomerReport/get_monthly_single_customer_report.php',
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

     function getCustomSalesRow(from_date, to_date, cc_id) {

         $.ajax({
             type: 'POST',
             url: 'CustomerReport/get_custom_single_customer_report.php',
             data: {
                 f_date: from_date,
                 t_date: to_date,
                 cid: cc_id
             },
             dataType: 'text',
             success: function (response) {
                 $('.monthly_result').html(response)

             }

         });

     }

     function getYearlySalesRow(selected_year, yc_id) {

         $.ajax({
             type: 'POST',
             url: 'CustomerReport/get_yearly_single_customer_report.php',
             data: {
                 year: selected_year,
                 cid: yc_id
             },
             dataType: 'text',
             success: function (response) {
                 $('.monthly_result').html(response)

             }

         });

     }
     $(document).ready(function() {
    $('#CustomTable').DataTable({
    searching: false,
    "paging": false,
    "info": false,
    order: [],

    dom: 'Bfrtip',
    buttons: [{
        className: 'btn btn-dark',
        extend: 'pdfHtml5',
        text: 'Download details',
        title: 'Custom report details',
        init: function (api, node, config) {
        $(node).removeClass('btn-primary');
        $(node).on('click', function () {
            $(this).addClass('btn-success');
        });
        },

        exportOptions: {
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8] // export only columns
        },
        customize: function (doc) {
        // Add a header to the PDF document
        doc.content.unshift({
            text: 'Custom report',
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