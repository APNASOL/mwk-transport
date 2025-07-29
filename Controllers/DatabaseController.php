<?php
function OpenCon()
{
    $dbhost = "localhost";
    $dbuser = "u103076311_trans_kwm895";
    $dbpass = "p0q~?FqM]";
    //$dbuser = "root";
    //$dbpass = "";
    $db = "u103076311_trans_kwm";
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);
    return $conn;
}

function CloseCon($conn)
{
    $conn->close();
}