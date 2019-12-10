<?php
require "conn.php";

$username = $_POST['username'];
$password =base64_encode($_POST['password']);
$imeiNumber = $_POST['IMEINumber'];
$result = mysqli_query($conn,"select * from user where UserName ='$username' and Password ='$password' and IMEINumber ='$imeiNumber'");
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_array($result);
    $previlege=$row["Privilege"];
    if($previlege=='1' || $previlege =='11' || $previlege =='12'){
        $response["Success"]=2;
    }else{
        $response["Success"]=1;
    }
}else{
    $response["Success"]=0;
    $response["Message"]="Invalid Credentials";
}
mysqli_close($conn);
echo json_encode($response);
?>