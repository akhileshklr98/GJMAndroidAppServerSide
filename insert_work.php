<?php
require_once "conn.php"; 

$return = 0;
$currentdate=date("Y-m-d");
date_default_timezone_set("Asia/Kolkata");

$current=date("Y-m-d H:i:A");
$split=explode(" ",$current);
$currentTime=$split[1];

$myscheduleID = $_POST['MyScheduleID'];
$userName= $_POST['UserName'];

$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");
if(mysqli_num_rows($resultmain)>0) {
    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];

    $date = $currentdate;

    // $gpslatitude = $_POST['GpsLatitude'];
    // $splitlatitude = explode(":", $gpslatitude);
    // $latitude = $splitlatitude[1];
    $latitude = $_POST['GpsLatitude'];
//    $latitude = 10.7710725;

    // $gpslongitude = $_POST['GpsLongitude'];
    // $splitlongitude = explode(":", $gpslongitude);
    // $longitude = $splitlongitude[1];
    $longitude = $_POST['GpsLongitude'];
//    $longitude = 76.6904655;
    $address='';
    $latAndLong='';
    if($latitude!='' && $longitude!=''){

        //$url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
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

    $status = $_POST['Status'];
    $resultCheck=mysqli_query($conn,"Select * from emobworkreport where ScheduleID='$myscheduleID' and EmployeeID='$employeeID'");
    if(mysqli_num_rows($resultCheck)>0){
       if($status=='Report'){
           $result = mysqli_query($conn, "update emobworkreport set OutLattitude='$latitude',OutLongitude='$longitude',OutTime='$currentTime',OutAddress='$address' where ScheduleID='$myscheduleID' and EmployeeID='$employeeID'");
           $response["success"] =3;
       }else{
           $response["success"] =2;
       }

    }else{
        $result = mysqli_query($conn, "insert into emobworkreport (ScheduleID, EmployeeID, Date, Lattitude, Longitude, Status, Time, Address)
                                   values ('$myscheduleID', '$employeeID', '$date','$latitude', '$longitude', '$status','$currentTime','$address')");
        $response["success"] =1;
    }

}else{
    $response["success"] =0;
}
mysqli_close($conn);
echo json_encode($response);
?>