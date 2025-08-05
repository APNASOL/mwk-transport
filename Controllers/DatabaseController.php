<?php
// function OpenCon()
// {
//     $dbhost = "localhost";
//     $dbuser = "root";
//     $dbpass = ""; 
//     $db = "apnasole_mwk_transport";
//     $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);
//     return $conn;
// }
function OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "u103076311_trans_kwm895";
    $dbpass = "p0q~?FqM]"; 
    $db = "u103076311_trans_kwm";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);
    return $conn;
}

function CloseCon($conn)
{
    $conn->close();
}

 


 