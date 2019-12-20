<?php
require_once "conn.php";

$userName= $_POST['UserName'];

$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");
if(mysqli_num_rows($resultmain)>0) {
    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];

    $date = date("Y-m-d");

    $result = mysqli_query($conn, "select schedules.BranchID,schedules.EmployeeID,schedules.ScheduleDate,schedules.ScheduleToDate,scheduledetail.*,
                                    hospital.HospitalName,hospital.AFFNo,PurposeOne.PurposeName as PurposeOneName,emobworkreport.OutTime,emobworkreport.Status as emobStatus
                                    from schedules
                                    left join scheduledetail on schedules.ID=scheduledetail.ScheduleID
                                    left join hospital on scheduledetail.HospitalID=hospital.ID
                                    left join purposeofvisit as PurposeOne on scheduledetail.Purpose1ID=PurposeOne.ID
                                    left join emobworkreport on scheduledetail.ID=emobworkreport.ScheduleID
                                    where schedules.ScheduleDate='$date' and schedules.EmployeeID='$employeeID' and scheduledetail.Status=1
                                    order by schedules.ScheduleDate desc");

    if (mysqli_num_rows($result) > 0) {
        $response["myscheduleList"] = array();
        while ($row = mysqli_fetch_array($result)) {
            $slNo++;
            $scheduleDetails = array();
            $scheduleDetails["AffNo"] = $row["AFFNo"];
            $scheduledetailID = $row['ID'];
            $scheduleDetails["myScheduleID"] = $scheduledetailID;
            $scheduleDetails["HospitalName"] = $row["HospitalName"];
            $scheduleDetails["PurposeName"] = $row["PurposeOneName"];

            $scheduleType = '';

            if ($row['ScheduleType'] == 1) {
                $scheduleDetails["ScheduleType"] = 'Main Schedule';
            } else if ($row['ScheduleType'] == 2) {
                $scheduleDetails["ScheduleType"] = 'Out of Schedule';
            }

            $scheduleStatus = '';
            if($row['emobStatus']!=''){
                $scheduleStatus=$row['emobStatus'];
            }
            else{
                if ($row['Status'] == 0) {
                    $scheduleStatus = "Assigned";
                }
                if ($row['Status'] == 1) {
                    $scheduleStatus = "Approved";
                }
                if ($row['Status'] == 2) {
                    $scheduleStatus = "Visited";
                }
                if ($row['Status'] == 3) {
                    $scheduleStatus = "Not Visited";
                }

            }

            $scheduleDetails["Status"] = $scheduleStatus;
            array_push($response["myscheduleList"], $scheduleDetails);
        }
        $response["success"] = 1;

    }else{
        $response["success"] = 2;
    }
   
}else {
    // no Code found
    $response["success"] = 0;
    $response["message"] = "No Details found";
}
echo json_encode($response);
mysqli_close($conn);
?>