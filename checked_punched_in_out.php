<?php
require_once "conn.php";

$returnFlag = 0;
$userName= $_POST['UserName'];
$punchedDate= date("Y-m-d", strtotime($_POST['punchDate']));
$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");

if(mysqli_num_rows($resultmain)>0) {

    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];
    $userID = $rowmain['ID'];
    $punchDate=date("Y-m-d");

    if(isset($_POST['type']) && $_POST['type']!=null) {
        $punchType = $_POST['type'];

        if($punchType=="Punch In"){
            $punchTypeID='1';
            $mySchedule= $_POST['MySchedule'];

            if(isset($_POST['MySchedule']) && ($_POST['MySchedule']=="MySchedule"))
            {
                $result = mysqli_query($conn, "select * from punchattendance where PunchDate='$punchedDate' and EmployeeID='$employeeID' ");

            }else{
                $result = mysqli_query($conn, "select * from punchattendance where PunchDate='$punchedDate' and EmployeeID='$employeeID' and TypeOfPunch='$punchTypeID'");

            }

        }

        if($punchType=="Punch Out"){
            $punchTypeIDIN='1';
            $resultin = mysqli_query($conn, "select * from punchattendance where PunchDate='$punchedDate' and EmployeeID='$employeeID' and TypeOfPunch='$punchTypeIDIN'");
            if(mysqli_num_rows($resultin)>0){

                $punchTypeID='2';
                $result = mysqli_query($conn, "select * from punchattendance where PunchDate='$punchedDate' and EmployeeID='$employeeID' and TypeOfPunch='$punchTypeID'");
            }else{
                $returnFlag++;
            }
        }
    }else{
        $result = mysqli_query($conn, "select * from punchattendance where PunchDate='$punchedDate' and EmployeeID='$employeeID'");
    }
    if(mysqli_num_rows($result) ==1) {

        $response["CheckPunch"] = array();
        while ($row = mysqli_fetch_array($result)) {
            $status = array();
            $punchDate = $row['PunchDate'];
            $punchTime = $row['PunchTime'];
            $punchType = $row['TypeOfPunch'];

            $status["PunchDate"] = $row["PunchDate"];
            $status["PunchTime"] = date('h:i:A',strtotime($row["PunchTime"]));
            $status["TypeOfPunch"] = $row["TypeOfPunch"];

            // push single Chitty into final response array
            array_push($response["CheckPunch"], $status);
            if($row["TypeOfPunch"]=='2'){
                $response["success"] = 2;
            }else if($row["TypeOfPunch"]=='1'){
                $response["success"] = 1;
            }

        }
    }else if(mysqli_num_rows($result) == 2){
        $response["success"] = 5;
    }
    else{
        if($returnFlag>0){
            $response["success"] = 4;
        }
        else{
            $result1 = mysqli_query($conn, "select * from punchattendance where EmployeeID='$employeeID' and PunchDate<'$punchedDate' order by ID desc limit 1");
            if(mysqli_num_rows($result1)==1){
                while($row1 = mysqli_fetch_array($result1)){
                    if ($row1['TypeOfPunch'] == 1) {
                        $response["success"] = 6;
                    }else{
                        if($punchType=="Punch Out"){
                            $result2 = mysqli_query($conn, "select * from scheduledetail 
                                                                inner join schedules on schedules.ID = scheduledetail.ScheduleID 
                                                                where schedules.EmployeeID='$employeeID' and schedules.ScheduleDate='$punchedDate' and schedules.Status=1 ");
                            if(mysqli_num_rows($result2)>0){
                                $response["success"] = 7;
                            }
                            else{
                                $response["success"] = 3;
                            }
                        }
                        else{
                            $response["success"] = 3;
                        }
                    }
                }
            }else{
                if($punchType=="Punch Out"){
                    $result2 = mysqli_query($conn, "select * from scheduledetail 
                                                        inner join schedules on schedules.ID = scheduledetail.ScheduleID 
                                                        where schedules.EmployeeID='$employeeID' and schedules.ScheduleDate='$punchedDate' and scheduledetail.Status=1 ");
                    if(mysqli_num_rows($result2)>0){
                        $response["success"] = 7;
                    }
                    else{
                        $response["success"] = 3;
                    }
                }
                else{
                    $response["success"] = 3;
                }
            }
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