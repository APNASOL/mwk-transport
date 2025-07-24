<?php  
    include 'Controllers/DatabaseController.php';
    $connect = OpenCon();
    if(isset($_POST['id'])){
    $table_result='';
    $id=$_POST['id'];
    $p_id = $_POST['p_id'];
    
    $date = date("Y-m-d");
    
    $currentdate = explode("-",$date);
			
        $dat = $currentdate[2];
        $month = $currentdate[1];
        $year = $currentdate[0];
       
        
       

     if($id==2){
       
         $table_result.='
            <div class="row">
                    <div class="col-md-7">
                    <h5 class="card-title"> Select Year and Month </h5>
                   </div>
                   <div class="col-md-5" style="margin-top:0px;">
                      <table >
                         <tr>
                         <input type="hidden" id="p_partner_id" name="p_id" value="'.$p_id.'">
                         
                                <td width="10%">
                                <label>Year</label>
                              <select class="form-control chosen" placeholder="" name="year" id="selected_year">';
                                            $selectuserquery = "SELECT * FROM years ORDER BY id";
                                            $selectuser = mysqli_query($connect, $selectuserquery);
                                            while ($p_s_result = mysqli_fetch_array($selectuser)) { 
                                                
                                                if($p_s_result["year"]==$year){
                                                    $table_result .='<option value="'.$p_s_result["year"].'" selected>'.$p_s_result["year"].'</option>';
                                                }else{
                                                     $table_result .='<option value="'.$p_s_result["year"].'">'.$p_s_result["year"].'</option>';
                                                }
                                                
                                    
                                            }
                                            $table_result .= '
                                        </select></td>  
                         
                           
                          
                          <td width="5%">
              
                              <button  class="form-control btn btn-dark mt-4 btn-flat m_search" name="day_search_button"><i class="fa fa-search"></i> Search </button>
                          </td>
                        
                          </tr>
                       </table>
                   </div>
                </div>
                   <hr>
                   <div class="monthly_result">
                   <div class="card">
              <div class="card-content collapse show">
                <div class="card-body card-dashboard dataTables_wrapper dt-bootstrap">
                   <div class="table-responsive">  
                     <table id="example" class="table table-bordered">
                         <thead>
                         <tr>
                          <th width="5%">No</th>      
                          <th width="10%">Date</th>
                          <th width="25%">Message</th>
                          <th width="10%">Credit</th>
                          <th width="10%">Debit</th>
                          <th width="10%">Balance</th>
                          
                                                </tr>
                             </thead>
                             <tr>
                             <td colspan="6" align="center"> No Record found</td>
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
        
    else if($id==4){
        
        
         $table_result.='
            <div class="row">
                    <div class="col-md-5">
                    <h5 class="card-title"> Select Date Range </h5>
                   </div>
                   <div class="col" style="margin-top:0px;">
                      <table >
                         <tr>
                         <input type="hidden" id="p_partner_id" name="p_id" value="'.$p_id.'">
                          <td width="6%">
                          <label>From date</label>
                              <input type="date" class="form-control" name="day" id="from_date" required>
                          </td>
                           
                          <td width="6%">
                          <label>To date</label>
                              <input type="date" class="form-control" name="day" id="to_date" required>
                          </td>
                          
                          <td width="4%">
              
                              <button  class="form-control btn btn-dark mt-4 btn-flat c_search" name="day_search_button"><i class="fa fa-search"></i> Search</button>
                          </td>
                        
                          </tr>
                       </table>
                   </div>
                </div>
                   <hr>
                   <div class="monthly_result">
                   <div class="card">
              <div class="card-content collapse show">
                <div class="card-body card-dashboard dataTables_wrapper dt-bootstrap">
                   <div class="table-responsive">  
                     <table id="example" class="table table-bordered">
                     <thead>
                     <tr>
                      <th width="5%">No</th>      
                      <th width="10%">Date</th>
                      <th width="25%">Message</th>
                      <th width="10%">Credit</th>
                      <th width="10%">Debit</th>
                      <th width="10%">Balance</th>
                      
                                            </tr>
                         </thead>
                         <tr>
                         <td colspan="6" align="center"> No Record found</td>
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
     var year_s = document.getElementById("selected_year").value;
     var partner = document.getElementById("p_partner_id");
     var partner_id = partner.value;
     //alert(mine_id);
     getMonthlyPartnerRow(year_s,partner_id);

     $(document).on('click', '.m_search', function (e) {


         var year = document.getElementById("selected_year").value;
         var partner = document.getElementById("p_partner_id");
         var partner_id = partner.value;
     
         if (year == "") {
             alert("Please select year");
         } else {
            getMonthlyPartnerRow(year,partner_id);
         }

     });

     $(document).on('click', '.c_search', function (e) {

         var fromdate = document.getElementById("from_date").value;
         var todate = document.getElementById("to_date").value;
         var partner = document.getElementById("p_partner_id");
         var partner_id = partner.value;

         if (fromdate == "" && todate == "") {
             alert("Please select both date!");
         } else if (fromdate > todate) {
             alert("From date must be less then to date!");
         } else {
            getCustomPartnerRow(fromdate, todate,partner_id);

         }

     });


     function getMonthlyPartnerRow(selected_year,partner_id) {
         //alert(mine_id);
         $.ajax({
             type: 'POST',
             url: 'get_monthly_partner_details.php',
             data: {
                 year: selected_year,
                 p_id:partner_id
             },
             dataType: 'text',
             success: function (response) {
                 $('.monthly_result').html(response)

             }

         });

     }

     function getCustomPartnerRow(from_date, to_date,partner_id) {

         $.ajax({
             type: 'POST',
             url: 'get_custom_partner_details.php',
             data: {
                 f_date: from_date,
                 t_date: to_date,
                 p_id:partner_id
             },
             dataType: 'text',
             success: function (response) {
                 $('.monthly_result').html(response)

             }

         });

     }

 </script>