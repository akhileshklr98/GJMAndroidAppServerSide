<?php
require_once "conn.php";

$userName = $_POST['UserName'];
$currentDate = date("Y-m-d");
$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");

if(mysqli_num_rows($resultmain)>0) {
    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];
    
    $result = mysqli_query($conn, "select * from backdatepermission where ToDate='$currentDate' and EmployeeID='$employeeID' and ScreenID=9 order by ID desc limit 1");
    if (mysqli_num_rows($result)>0) {
        while($row = mysqli_fetch_array($result)){
            $response["success"] = 1;
            $response["fromDate"] = date('d-m-Y', strtotime($row['FromDate']));
        }
    }else{
        $response["success"] = 2;
    }
}else{
    // no Code found
    $response["success"] = 0;
    $response["message"] = "No Statusfound";
}
mysqli_close($conn);
echo json_encode($response);
?>