<?php
require_once "conn.php";

$return = 0;
$id=$_POST["MyScheduleID"];
$result = mysqli_query($conn, "select scheduledetail.ID,schedules.ScheduleDate,scheduledetail.CustomerRemarks,
                     scheduledetail.Status,scheduledetail.Reason,scheduledetail.ScheduleID,scheduledetail.HospitalID,
                     hospital.AFFNo,hospital.HospitalName,hospital.Address,hospital.ContactPerson,hospital.ContactNo,schedules.EmployeeID
                from schedules
                inner join scheduledetail on schedules.ID=scheduledetail.ScheduleID
                inner join hospital on scheduledetail.HospitalID=hospital.ID
                where scheduledetail.ID ='$id'");
if (mysqli_num_rows($result) > 0) {
    $response["WorkReport"] = array();
    while ($row = mysqli_fetch_array($result)) {
        $status = array();
        $status["AffNo"] = $row["AFFNo"];
        $status["Hospital"] = $row["HospitalName"];
        $status["Address"] = $row["Address"];
        $status["ContactPerson"] = $row["ContactPerson"];
        $status["ContactNumber"] = $row["ContactNo"];
        $status["MyScheduleID"] = $row["ID"];
        $status["EmployeeID"] = $row["EmployeeID"];
        
        $resultstatus = mysqli_query($conn,"select Status from emobworkreport where `ScheduleID`='$id'  order by ScheduleID desc");
        if(mysqli_num_rows($resultstatus)>0){
            $rowStatus = mysqli_fetch_array($resultstatus);
            $stat = $rowStatus['Status'];
        }
        if($stat=='Visited'){
            $status["Visited"] = 1;
        }else if($stat=='NotVisited'){
            $status["NotVisited"] = 1;
        }
        // push single Chitty into final response array
        array_push($response["WorkReport"], $status);
    }
    $response["success"] = 1;
} else {
    // no Code found
    $response["success"] = 0;
    $response["message"] = "No Statusfound";
}
mysqli_close($conn);
echo json_encode($response);
?>