<?php
require_once "conn.php";

$result = mysqli_query($conn, "Select * from employee where (DesignationID=8 or DesignationID=9) and (Status=1)");

if (mysqli_num_rows($result) > 0) {
    $response["AssistanceList"] = array();
    while ($row = mysqli_fetch_array($result)) {
        $list= array();
        $list["Assistance"] = $row["FirstName"].' '.$row["LastName"];
        array_push($response["AssistanceList"], $list);
    }
    $response["success"] =1;
}else{
    // no Code found
    $response["success"] = 0;
    $response["message"] = "No Assistance";
}
mysqli_close($conn);
echo json_encode($response);

?>