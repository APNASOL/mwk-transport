<?php 
session_start();
$connect = mysqli_connect("localhost", "root", "", "pharmacy1"); 

if(isset($_POST["add_to_cart"])){
     if(isset($_SESSION["shopping_cart"]))  
      {  
           $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");  
           if(!in_array($_POST["id"], $item_array_id))  
           {  
                $count = count($_SESSION["shopping_cart"]);  
                $item_array = array(  
                     'item_id'               =>     $_POST["id"],  
                     'item_name'             =>     $_POST["name"],  
                     'item_price'            =>     $_POST["price"],  
                     'date'                  =>     $_POST["date"]
                );  
                $_SESSION["shopping_cart"][$count] = $item_array;
               echo '<script>window.location="pos.php"</script>';
           }  
           else  
           {  
                echo '<script>alert("Item Already Added")</script>';  
                echo '<script>window.location="pos.php"</script>';  
           }  
      }  
      else  
      {  
           $item_array = array(  
            'item_id'               =>     $_POST["id"],  
            'item_name'             =>     $_POST["name"],  
            'item_price'            =>     $_POST["price"],  
            'date'                  =>     $_POST["date"]
           );  
           $_SESSION["shopping_cart"][0] = $item_array;
          echo '<script>window.location="pos.php"</script>';
      } 
 }




 if(isset($_GET["action"]))  
 {  
      if($_GET["action"] == "delete")  
      {  
           foreach($_SESSION["shopping_cart"] as $keys => $values)  
           {  
                if($values["item_id"] == $_GET["id"])  
                {  
                     unset($_SESSION["shopping_cart"][$keys]);  
                     echo '<script>alert("Item Removed")</script>';  
                     echo '<script>window.location="pos.php"</script>';  
                }  
           }  
      } 
     else if($_GET["action"] == "other")
     {
    if(isset($_POST["add_to_cart_other"])){
     if(isset($_SESSION["shopping_cart"]))  
      {  
           $item_array_id = array_column($_SESSION["shopping_cart"], "item_id");  
           if(!in_array($_POST["id"], $item_array_id))  
           {  
                $count = count($_SESSION["shopping_cart"]);  
                $item_array = array(  
                     'item_id'               =>     $_POST["id"],  
                     'item_name'             =>     $_POST["p_name"],  
                     'item_price'            =>     $_POST["p_price"],  
                     'item_quantity'         =>     $_POST["p_quantity"],
                     'item_profit'           =>     $_POST["profit"],
                     'item_shop'             =>     $_POST["shop"]
                );  
                $_SESSION["shopping_cart"][$count] = $item_array;
               echo '<script>window.location="pos.php"</script>';
           }  
           else  
           {  
                echo '<script>alert("Item Already Added")</script>';  
                echo '<script>window.location="pos.php"</script>';  
           }  
      }  
      else  
      {  
           $item_array = array(  
                'item_id'               =>     $_POST["id"],  
                'item_name'             =>     $_POST["p_name"],  
                'item_price'            =>     $_POST["p_price"],  
                'item_quantity'         =>     $_POST["p_quantity"],
                'item_profit'           =>     $_POST["profit"],
                'item_shop'             =>     $_POST["shop"]
           );  
           $_SESSION["shopping_cart"][0] = $item_array;
          echo '<script>window.location="pos.php"</script>';
      }  
 }
     }
 } 


?>