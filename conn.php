<?php
$dbName = "wmsdb";
$server = "localhost";
/* Local Username and Password */
// $userName = "root";
// $password = "";
/* Server Username and Password */
$userName = "gjmadmin";
$password = "Gjma@2019";
$conn = mysqli_connect($server, $userName, $password, $dbName) or die ('MySQL connect failed. ' . mysqli_error($conn));
if (mysqli_connect_errno($conn))
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
/* if($conn){
    echo "Connection Success";
}else{
    echo "Connection Not Success";
} */
?>