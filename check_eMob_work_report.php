<?php
require_once "conn.php";

$returnFlag = 0;
$userName = $_POST['UserName'];
$resultmain = mysqli_query($conn, "select * from user where UserName ='$userName'");

if (mysqli_num_rows($resultmain) > 0) {

    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];

    $Status = $_POST['Status'];
    $myScheduleID = $_POST['MyScheduleID'];
    if (isset($_POST['Report']) && $_POST['Report'] == "Report") {
        $result = mysqli_query($conn, "select * from emobworkreport where Status='$Status' and EmployeeID='$employeeID' and ScheduleID='$myScheduleID'");
    }else {
        $result = mysqli_query($conn, "select * from emobworkreport where EmployeeID='$employeeID' and ScheduleID='$myScheduleID'");
    }
    if (mysqli_num_rows($result) > 0) {
        $response["CheckVisited"] = array();
        while ($row = mysqli_fetch_array($result)) {
            $status = array();
            $date = $row['Date'];
            $time = $row['Time'];
            $status = $row['Status'];

            $status["Date"] = $row["PunchDate"];
            $status["Time"] = $row["PunchTime"];
            $status["Status"] = $row["TypeOfPunch"];

            array_push($response["CheckVisited"], $status);
        }
        if (isset($_POST['Report']) && $_POST['Report'] == "Report") {
            $resultemob = mysqli_query($conn, "select * from scheduledetail where scheduledetail.ID='$myScheduleID' and scheduledetail.Status='1'");
            if (mysqli_num_rows($resultemob) > 0) {
                $response["success"] = 3;
            } else {
                $response["success"] = 4;
            }
        }else {
            $response["success"] = 1;
        }

    } else {
        $response["success"] = 2;
    }

} else {
// no Code found
    $response["success"] = 0;
    $response["message"] = "No Statusfound";
}
mysqli_close($conn);
echo json_encode($response);
?>