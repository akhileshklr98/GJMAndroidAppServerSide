<?php
require_once "conn.php"; 

$returnFlag = 0;
$userName= $_POST['UserName'];
$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");
$date= date('Y-m-d');
if(mysqli_num_rows($resultmain)>0) {

    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];
    $myScheduleID = $_POST['MyScheduleID'];
        $result = mysqli_query($conn, "select * from emobworkreport where  EmployeeID='$employeeID' and ScheduleID='$myScheduleID'");
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if ($row['Time'] != '' && $row['OutTime'] == '') {
                    $response["success"] = 2;// Workreport  Ok(Save Work Report)
                }elseif ($row['Time'] != '' && $row['OutTime'] != '') {
                    $response["success"] = 3;// Workreport  already Done
                }
            }
        }else {
            $result1 = mysqli_query($conn, "select * from emobworkreport where  EmployeeID='$employeeID' and Status='Visited'  and emobworkreport.OutTime is null and Date='$date'");//To check whether any previous "Enter" & no "Exit & Report"
            // if (mysqli_num_rows($result1) > 0) {
            //     $response["success"] = 4;//pending work report
            // }
            // else{
            //     $response["success"] = 1;// inserting into emobworkreport
            // }
            if (mysqli_num_rows($result1) > 0) {
                while ($row = mysqli_fetch_array($result1)) {
                    $scheduleId = $row['ScheduleID'];
                }
                $response["success"] = 4;//pending work report
                $response["ScheduleID"] = $scheduleId;
            }
            else{
                $response["success"] = 1;// inserting into emobworkreport
                $response["ScheduleID"] = "";
            }
        }

    }else {
// no Code found
    $response["success"] = 0;
    $response["message"] = "No Statusfound";
}
mysqli_close($conn);
echo json_encode($response);
?>