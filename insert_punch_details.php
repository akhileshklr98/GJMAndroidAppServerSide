<?php
require_once "conn.php";

$return = 0;
date_default_timezone_set("Asia/Kolkata");

$userName= $_POST['UserName'];
$punchType= $_POST['PunchType'];
if($punchType=="Punch In"){
    $punchTypeID=1;
}else{
    $punchTypeID=2;
}

$currentdate= $_POST['Date'];
$punchTime= date('H:i:A',strtotime($_POST['Time']));
$date=date("Y-m-d");

$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");
if(mysqli_num_rows($resultmain)>0) {
    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];
    $userID = $rowmain['ID'];

    $punchDate =date("Y-m-d",strtotime($currentdate));

    $gpslatitude = $_POST['GpsLatitude'];
    $splitlatitude = explode(":", $gpslatitude);
    $latitude = trim($splitlatitude[1]);
//    $latitude = 10.7710725;

    $gpslongitude = $_POST['GpsLongitude'];
    $splitlongitude = explode(":", $gpslongitude);
    $longitude = trim($splitlongitude[1]);
//    $longitude = 76.6904655;

    $address='';
    $latAndLong='';
    if($latitude!='' && $longitude!=''){
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&sensor=false&key=AIzaSyD9AYA7g9KaKQ-idseLcIWa_gRSSTknfg0';
        $json = @file_get_contents($url);
        $data=json_decode($json);

        $status = $data->status;
        if($status=="OK"){
            $address=$data->results[0]->formatted_address;
        }else {
            $address=false;
        }
    }
    $result=mysqli_query($conn, "select * from punchattendance where PunchDate='$punchDate' and EmployeeID='$employeeID' and TypeOfPunch='$punchTypeID'");

    if(mysqli_num_rows($result)>0) {
//        while ($row = FetchArray($result)) {
//            $punching = array();
//            $punching["PunchDate"] = $row["PunchDate"];
//            $punching["PunchTime"] = $row["PunchTime"];
//            $punching["TypeOfPunch"] = $row["TypeOfPunch"];
//            array_push($response["Punch"], $punching);
//
//        }
        $response["success"] =1;
    }else{
        $result =mysqli_query($conn, "insert into punchattendance(EmployeeID,TypeOfPunch,PunchDate,Date,PunchTime,Latitude,Longitude,Location) values('$employeeID','$punchTypeID','$punchDate','$date','$punchTime','$latitude','$longitude','$address')");
        $response["success"] =2;
    }
}else{
    $response["success"] =0;
}
mysqli_close($conn);
echo json_encode($response);
?>