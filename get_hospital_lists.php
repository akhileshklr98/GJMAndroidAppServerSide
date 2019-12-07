<?php
require_once "conn.php";

$userName= $_POST['UserName'];

$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");
if(mysqli_num_rows($resultmain)>0) {
    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];

    $date = date("Y-m-d");

    $result = mysqli_query($conn, "select schedules.BranchID,schedules.EmployeeID,schedules.ScheduleDate,schedules.ScheduleToDate,scheduledetail.*,
                                    employee.FirstName,employee.LastName,hospital.HospitalName,hospital.AFFNo,PurposeOne.PurposeName as PurposeOneName,
                                    PurposeTwo.PurposeName as PurposeTwoName
                                    from schedules
                                    left join employee on schedules.EmployeeID=employee.ID
                                    left join scheduledetail on schedules.ID=scheduledetail.ScheduleID
                                    left join hospital on scheduledetail.HospitalID=hospital.ID
                                    left join purposeofvisit as PurposeOne on scheduledetail.Purpose1ID=PurposeOne.ID
                                    left join purposeofvisit as PurposeTwo on scheduledetail.Purpose2ID=PurposeTwo.ID
                                    where schedules.ScheduleDate='$date' and schedules.EmployeeID='$employeeID'
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
            $resultstatus = mysqli_query($conn, "select Status from emobworkreport where ScheduleID='$scheduledetailID'
                            order by ScheduleID desc;");
            if (mysqli_num_rows($resultstatus) > 0) {
                while ($rowStatus = mysqli_fetch_array($resultstatus)) {
                    $scheduleStatus = $rowStatus['Status'];
                }
            } else {
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

    }
    else{
        $response["success"] = 2;
    }
   
}
else {
    // no Code found
    $response["success"] = 0;
    $response["message"] = "No Details found";
}
echo json_encode($response);
mysqli_close($conn);
?>