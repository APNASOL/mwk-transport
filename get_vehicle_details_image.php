<?php
include 'Controllers/DatabaseController.php';
$conn = OpenCon();
session_start();
if (isset($_POST['date'])) {
    $table_daily_result = '';
    $date = $_POST['date'];
    $c_id = $_SESSION['current_vehicle_id'];

 
    if ($c_id != "empty") {
       $vehicle_details = mysqli_query($conn, "SELECT * FROM media WHERE vehicle_id =  $c_id AND date = '$date';");
       $row= mysqli_fetch_array($vehicle_details);
       if($row){
       $imagePath = $row["path"];
       $image = $row["name"];
       }
    }

    if ($date!= "") {

        $table_daily_result .= ' 
                        <div class="card card-body p-3">
                            <div class="row">
                                <div class="col-md-7">
                                  <h5>Trips Attachment</h5>
                                </div>';
                                if($row){
                               $table_daily_result .= ' <div class="col-md-2">
                                   <img data-bs-toggle="modal" data-bs-target="#viweImage" src="uploads/' . $imagePath . '" alt="Trips attachment" class="img-thumbnail" style="max-width: 100px;">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-warning" onclick="document.getElementById(\'changeImageForm\').style.display=\'block\';">Change Image</button>
                                </div>
                                ';
                                }else{
                               $table_daily_result .= '<div class="col-md-2"> No Photo uploaded</div> <div class="col-md-3">
                                    <button class="btn btn-dark" onclick="document.getElementById(\'uploadForm\').style.display=\'block\';">Upload Image</button>
                                </div>';
                                } 
                            $table_daily_result .= '</div>
                        </div>

                        <!-- Upload Form -->
    <div id="uploadForm" style="display:none;" class="mt-4 mb-3">
        <form action="Controllers/TripsController.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="date" name="date" class="form-control" value="'.$date.'" readonly>
                <label for="photo">Select Photo to Upload:</label>
                <input type="file" name="photo" id="photo" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-dark">Upload Photo</button>
        </form>
    </div>

    <!-- Change Image Form -->
    <div id="changeImageForm" style="display:none;" class="mt-4 mb-3">
        <form action="Controllers/TripsController.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="date" name="date" class="form-control" value="'.$date.'" readonly>
                <label for="photo">Select New Photo:</label>
                <input type="file" name="photo" id="photo" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-dark">Change Photo</button>
        </form>
    </div>


     <!-- Modal Give to Vehicle -->
    <div class="modal fade" id="viweImage" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Photo View</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>';
                    if($row){
                    $table_daily_result .= '<div class="modal-body">
                        <img id="myImg" src="uploads/' .$imagePath. '" alt="Trips attachment" class="img-thumbnail">
                    </div>';
                    }
                $table_daily_result .= '</div>
            </div>
          </div>
        </div>

    

                        ';
        echo $table_daily_result;
    }
}

?>
