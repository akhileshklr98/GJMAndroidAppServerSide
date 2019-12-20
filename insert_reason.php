<?php
require_once "conn.php";

$return = 0;
date_default_timezone_set("Asia/Kolkata");

$userName= $_POST['UserName'];

$resultmain = mysqli_query($conn,"select * from user where UserName ='$userName'");
if(mysqli_num_rows($resultmain)>0) {

    $rowmain = mysqli_fetch_array($resultmain);
    $employeeID = $rowmain['EmployeeID'];
    $userID = $rowmain['ID'];

        $Affiliation='';
        $Training='';
        $Retraining='';
        $Segregation='';
        $BForm='';
        $Collection='';
        $Verification='';
        $ReVerification='';
        $OfficeWork='';
        $Marketing='';
        $Payment='';
        $Supply='';
        $supervisorDuty='';
        $Meeting='';
        $trainingAssistant='';
        $inchargeDuty='';
        $FollowupDate='';
        $QRCodeSupply = '';
        $DistRepMeeting = '';
        $Others = '';

        $id = null;
        $id = $_POST['MyScheduleID'];

        if(isset($_POST['status'])){
            $visitStatus=$_POST['status'];

            if($visitStatus=="Visited"){
                $VisitStatus=2;
            }else if($visitStatus=="NotVisited"){
                $VisitStatus=3;
            }

            if(isset($_POST['checkAffiliation'])){
                $Affiliation = $_POST["checkAffiliation"];
            }
            if(isset($_POST['checkTraining'])){
                $Training = $_POST["checkTraining"];
            }
            if(isset($_POST['checkRetraining'])){
                $Retraining = $_POST["checkRetraining"];
            }
            if(isset($_POST['checkSegregation'])){
                $Segregation = $_POST["checkSegregation"];
            }
            if(isset($_POST['checkBForm'])){
                $BForm = $_POST["checkBForm"];
            }
            if(isset($_POST['checkMedicineCollection'])){
                $Collection = $_POST["checkMedicineCollection"];
            }
            if(isset($_POST['checkReVerification'])) {
                $ReVerification = $_POST["checkReVerification"];
            }
            if(isset($_POST['checkVerification'])) {
                $Verification = $_POST["checkVerification"];
            }
            if(isset($_POST['checkOfficeWork'])) {
                $OfficeWork = $_POST["checkOfficeWork"];
            }
            if(isset($_POST['checkMarketing'])) {
                $Marketing = $_POST["checkMarketing"];
            }
            if(isset($_POST['checkPaymentFollowup'])) {
                $Payment = $_POST["checkPaymentFollowup"];
            }
            if(isset($_POST['checkSupply'])) {
                $Supply = $_POST["checkSupply"];
            }
            if(isset($_POST['checkSupervisor'])) {
                $supervisorDuty = $_POST["checkSupervisor"];
            }
            if(isset($_POST['checkMeeting'])) {
                $Meeting = $_POST["checkMeeting"];
            }
            if(isset($_POST['checkTrainingAssistant'])) {
                $trainingAssistant = $_POST["checkTrainingAssistant"];
            }
            if(isset($_POST['checkInchargeDuty'])) {
                $inchargeDuty = $_POST["checkInchargeDuty"];
            }
            if(isset($_POST['checkQRCodeSupply'])) {
                $QRCodeSupply = $_POST["checkQRCodeSupply"];
            }
            if(isset($_POST['checkDistRepMeeting'])) {
                $DistRepMeeting = $_POST["checkDistRepMeeting"];
            }
            if(isset($_POST['checkOthers'])) {
                $Others = $_POST["checkOthers"];
            }

        }
        if(isset($_POST['FollowDate'])){
            $FollowupDate = date('Y-m-d',strtotime($_POST['FollowDate']));
        }

        $TokenNo=$_POST["TokenNo"];
        $TrainingDate = date('Y-m-d',strtotime($_POST['txtTrainingDate']));
        $StartTime=trim($_POST["txtStartTime"]);
        $FinishTime =trim($_POST["txtFinishTime"]);
        $Sessions=trim($_POST["txtSessions"]);
        $Attendee =trim($_POST["txtAttendee"]);
        $AssistanceName =$_POST["txtAssistance"];

        $sqlassist = mysqli_query($conn,"select * from employee where  concat(`FirstName`,' ',`LastName`) ='$AssistanceName'");
        if(mysqli_num_rows($sqlassist)>0) {
            $rowAssist = mysqli_fetch_array($sqlassist);
            $Assistance = $rowAssist['ID'];
        }

        $TrainingRemark =trim($_POST["txtTrainingRemark"]);

        $SegregationA='';
        $SegregationB='';
        $SegregationC='';
        $SegregationD='';
        $SegregationE='';
        $SegregationF='';
        $SegregationG='';

        $Reason=$_POST['Remarks'];

            if($VisitStatus==2) //2 means Visited
            {
                if(isset($_POST['chkQ1'])) {
                    $SegregationA = $_POST["chkQ1"];
                }

                if(isset($_POST['chkQ2'])) {
                    $SegregationB = $_POST["chkQ2"];
                }

                if(isset($_POST['chkQ3'])) {
                    $SegregationC = $_POST["chkQ3"];
                }

                if(isset($_POST['chkQ4'])) {
                    $SegregationD = $_POST["chkQ4"];
                }

                if(isset($_POST['chkQ5'])) {
                    $SegregationE = $_POST["chkQ5"];
                }

                if(isset($_POST['chkQ6'])) {
                    $SegregationF = $_POST["chkQ6"];
                }
                if(isset($_POST['chkQ7'])) {
                    $SegregationG = $_POST["chkQ7"];
                }

                $CustomerRemarks=$_POST["customerRemarks"];

                if($Affiliation!=0 || $Training!=0 || $Retraining!=0 || $Segregation!=0 || $BForm!=0 || $OfficeWork!=0 || $Marketing!=0 || $QRCodeSupply!=0 || $DistRepMeeting!=0 || $Others!=0||
                    $Payment!=0 || $Collection!=0 || $Verification!=0 || $ReVerification!=0 || $Supply!=0 || $supervisorDuty!=0 || $Meeting!=0|| $trainingAssistant!=0|| $inchargeDuty!=0)
                {
                    $resultMain=mysqli_query($conn,"update scheduledetail set Status='$VisitStatus' where ID='$id';");

                    if($Affiliation!='') {

                        $result=mysqli_query($conn,"select * from schedulepurpose where DetailID='$id' and Purpose=1;");
                        if(mysqli_num_rows($result)==0) {
                            $result1 = mysqli_query($conn,"insert into schedulepurpose (DetailID,Purpose) values('$id','$Affiliation')");
                            if($result1){
                                $result2 = mysqli_query($conn,"insert into followup (PurposeID,FollowupDate) values('$id','$FollowupDate')");
                                $result3=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id';");
                            }
                        }else{
                            $result1=mysqli_query($conn,"update followup set FollowupDate='$FollowupDate' where PurposeID='$id';");
                            $result2=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id';");
                        }
                    }

                    if($Training!='') {

                        $result=mysqli_query($conn,"select * from schedulepurpose where DetailID='$id' and Purpose=2;");
                        if(mysql_num_rows($result)==0) {
                            $result1 = mysqli_query($conn,"insert into schedulepurpose (DetailID,Purpose)
                                 values('$id','$Training')");
                            if ($result1) {
                                $getschedulepurposeID = mysqli_query($conn, "select * from schedulepurpose where DetailID='$id' and Purpose=2;");
                                while ($row = mysqli_fetch_array($getschedulepurposeID)) {
                                    $schedulePurposeID = $row['ID'];

                                    if ($TrainingDate != '0000-00-00' || $TrainingDate != '') {
                                        $result2 = mysqli_query($conn, "insert into training (PurposeID,SchedulePurposeID,TrainingDate,StartTime,FinishTime,Sessions,NoOfAttendee,Assistance,Remark)
                                     values('$id','$schedulePurposeID','$TrainingDate','$StartTime','$FinishTime','$Sessions','$Attendee','$Assistance','$TrainingRemark')");
                                    }
                                    $result3 = mysqli_query($conn, "insert into followup (PurposeID,FollowupDate)
                                          values('$id','$FollowupDate')");
                                }
                            }
                        }else{
                            while ($row = mysqli_fetch_array($result)) {
                                $schedulePurposeID = $row['ID'];

                                $result1 = mysqli_query($conn, "select * from training where PurposeID='$id';");
                                if (mysql_num_rows($result1) == 0) {
                                    if ($TrainingDate != '0000-00-00' || $TrainingDate != '') {
                                        $result2 = mysqli_query($conn, "insert into training (PurposeID,SchedulePurposeID,TrainingDate,StartTime,FinishTime,Sessions,NoOfAttendee,Assistance,Remark)
                                            values('$id','$schedulePurposeID','$TrainingDate','$StartTime','$FinishTime','$Sessions','$Attendee','$Assistance','$TrainingRemark')");
                                    }
                                } else {
                                    $result2 = mysqli_query($conn, "update training set TrainingDate='$TrainingDate',StartTime='$StartTime',FinishTime='$FinishTime',Sessions='$Sessions',
                                              NoOfAttendee='$Attendee',Assistance='$Assistance',Remark='$TrainingRemark' where PurposeID='$id';");
                                }

                                $result3 = mysqli_query($conn, "select * from followup where PurposeID='$id';");
                                if (mysql_num_rows($result3) == 0) {
                                    $result4 = mysqli_query($conn, "insert into followup (PurposeID,FollowupDate)
                                         values('$id','$FollowupDate')");
                                } else {
                                    $result4 = mysqli_query($conn, "update followup set FollowupDate='$FollowupDate' where PurposeID='$id';");
                                }

                            }
                        }

                    }

                    if($Retraining!='') {

                        $result=mysqli_query($conn,"select * from schedulepurpose where DetailID='$id' and Purpose=3;");
                        if(mysql_num_rows($result)==0) {
                            $result1  = mysqli_query($conn,"insert into schedulepurpose (DetailID,Purpose)
                                 values('$id','$Retraining')");
                            if ($result1) {
                                $getschedulepurposeID = mysqli_query($conn, "select * from schedulepurpose where DetailID='$id' and Purpose=3;");
                                while ($row = mysqli_fetch_array($getschedulepurposeID)) {
                                    $schedulePurposeID = $row['ID'];

                                    if ($TrainingDate != '0000-00-00' || $TrainingDate != '') {
                                        $result2 = mysqli_query($conn, "insert into training (PurposeID,SchedulePurposeID,TrainingDate,StartTime,FinishTime,Sessions,NoOfAttendee,Assistance,Remark)
                                     values('$id','$schedulePurposeID','$TrainingDate','$StartTime','$FinishTime','$Sessions','$Attendee','$Assistance','$TrainingRemark')");
                                    }
                                    $result3 = mysqli_query($conn, "insert into followup (PurposeID,FollowupDate)
                                          values('$id','$FollowupDate')");
                                }
                            }
                        }else{
                            while ($row = mysqli_fetch_array($result)) {
                                $schedulePurposeID = $row['ID'];
                                $result1 = mysqli_query($conn, "select * from training where PurposeID='$id'");
                                if (mysql_num_rows($result1) == 0) {
                                    if ($TrainingDate != '0000-00-00' || $TrainingDate != '') {

                                        $result2 = mysqli_query($conn, "insert into training (PurposeID,SchedulePurposeID,TrainingDate,StartTime,FinishTime,Sessions,NoOfAttendee,Assistance,Remark)
                                     values('$id','$schedulePurposeID','$TrainingDate','$StartTime','$FinishTime','$Sessions','$Attendee','$Assistance','$TrainingRemark')");
                                    }
                                } else {
                                    $result2 = mysqli_query($conn, "update training set TrainingDate='$TrainingDate',StartTime='$StartTime',FinishTime='$FinishTime',Sessions='$Sessions',
                                     NoOfAttendee='$Attendee',Assistance='$Assistance',Remark='$TrainingRemark' where PurposeID='$id';");
                                }
                                $result3 = mysqli_query($conn, "select * from followup where PurposeID='$id'");
                                if (mysql_num_rows($result3) == 0) {
                                    $result4 = mysqli_query($conn, "insert into followup (PurposeID,FollowupDate)
                             values('$id','$FollowupDate')");
                                } else {
                                    $result3 = mysqli_query($conn, "update followup set FollowupDate='$FollowupDate' where PurposeID='$id'");
                                }

                            }
                        }
                    }

                    if($Segregation!='') {
                        $result=mysqli_query($conn,"select * from schedulepurpose where DetailID='$id' and Purpose=4;");
                        if(mysql_num_rows($result)==0) {
                            $result1 = mysqli_query($conn,"insert into schedulepurpose (DetailID,Purpose)
                                 values('$id','$Segregation')");
                            if ($result1) {
                                // $result2 = mysqli_query($conn,"insert into segregation (PurposeID,Q1,Q1Remark,Q2,Q2Remark,Q3,Q3Remark,Q4,Q4Remark,Q5,Q5Remark,Q6,Q6Remark)
                                //      values('$id','$SegregationA','$SegregationARemark','$SegregationB','$SegregationBRemark','$SegregationC','$SegregationCRemark',
                                //             '$SegregationD','$SegregationDRemark','$SegregationE','$SegregationERemark','$SegregationF','$SegregationFRemark')");
                                $result2 = mysqli_query($conn,"insert into segregation (PurposeID,Q1,Q2,Q3,Q4,Q5,Q6,Q7)
                                        values('$id','$SegregationA','$SegregationB','$SegregationC','$SegregationD','$SegregationE','$SegregationF','$SegregationG')");
                                $result3=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id';");
                            }
                        }else{
                            // $result4=mysqli_query($conn,"update segregation set Q1='$SegregationA',Q1Remark='$SegregationARemark',Q2='$SegregationB',Q2Remark='$SegregationBRemark',
                            //           Q3='$SegregationC',Q3Remark='$SegregationCRemark',Q4='$SegregationD',Q4Remark='$SegregationDRemark',Q5='$SegregationE',
                            //           Q5Remark='$SegregationERemark',Q6='$SegregationF',Q6Remark='$SegregationFRemark' where PurposeID='$id';");
                            $result4=mysqli_query($conn,"update segregation set Q1='$SegregationA',Q2='$SegregationB',
                                       Q3='$SegregationC',Q4='$SegregationD',Q5='$SegregationE',Q6='$SegregationF',Q7='$SegregationG' where PurposeID='$id';");

                            $result5=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id';");
                        }
                    }

                    if($Verification!='') {
                        $result=mysqli_query($conn,"select * from schedulepurpose where DetailID='$id' and Purpose=7");
                        if(mysql_num_rows($result)==0) {
                            $result1 = mysqli_query($conn,"insert into schedulepurpose (DetailID,Purpose)
                             values('$id','$Verification')");
                            if ($result1) {
                                $result2 = mysqli_query($conn,"insert into verification (PurposeID,TokenNo) values('$id','$TokenNo')");

                                $result3=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id'");
                            }
                        }else{
                            $result1=mysqli_query($conn,"update verification set TokenNo='$TokenNo' where ID='$id'");

                            $result2=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id'");
                        }
                    }

                    if($ReVerification!='') {
                        $result=mysqli_query($conn,"select * from schedulepurpose where DetailID='$id' and Purpose=18");
                        if(mysql_num_rows($result)==0) {
                            $result1 = mysqli_query($conn,"insert into schedulepurpose (DetailID,Purpose)
                             values('$id','$ReVerification')");
                            if ($result1) {
                                $result2 = mysqli_query($conn,"insert into verification (PurposeID,TokenNo) values('$id','$TokenNo')");

                                $result3=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id'");
                            }
                        }else{
                            $result1=mysqli_query($conn,"update verification set TokenNo='$TokenNo' where ID='$id'");

                            $result2=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id'");
                        }
                    }

                    if($BForm!='' || $OfficeWork!='' || $Marketing!='' || $Payment!='' || $Collection!='' || $Supply!='' || $supervisorDuty!='' || 
                    $Meeting!=''|| $trainingAssistant!=''|| $inchargeDuty!='' || $QRCodeSupply!='' || $DistRepMeeting!='' || $Others!='')
                    {
                        $purposeID='';
                        $purpose='';
                        if($BForm!=''){ $purposeID=6;$purpose=$BForm;}
                        if($OfficeWork!=''){ $purposeID=10;$purpose=$OfficeWork;}
                        if($Marketing!=''){ $purposeID=11;$purpose=$Marketing;}
                        if($Payment!=''){ $purposeID=8;$purpose=$Payment;}
                        if($Collection!=''){ $purposeID=5;$purpose=$Collection;}
                        if($Supply!=''){ $purposeID=12;$purpose=$Supply;}
                        if($supervisorDuty!=''){ $purposeID=13;$purpose=$supervisorDuty;}
                        if($Meeting!=''){ $purposeID=14;$purpose=$Meeting;}
                        if($trainingAssistant!=''){ $purposeID=15;$purpose=$trainingAssistant;}
                        if($inchargeDuty!=''){ $purposeID=19;$purpose=$inchargeDuty;}
                        if($QRCodeSupply!=''){ $purposeID=17;$purpose=$QRCodeSupply;}
                        if($DistRepMeeting!=''){ $purposeID=16;$purpose=$DistRepMeeting;}
                        if($Others!=''){ $purposeID=9;$purpose=$Others;}

                        $result=mysqli_query($conn,"select * from schedulepurpose where DetailID='$id' and Purpose='$purposeID'");

                        if(mysql_num_rows($result)==0) {
                            $result1 = mysqli_query($conn,"insert into schedulepurpose (DetailID,Purpose)
                                 values('$id','$purpose')");
                            if ($result1) {
                                $result2=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id';");
                            }
                        } else{
                            $result1=mysqli_query($conn,"update scheduledetail set CustomerRemarks='$CustomerRemarks' where ID='$id'");
                        }
                    }
                }else{
                    Alert('Choose Atleast One Purpose\nData Not Saved');
                }
            }

            else if ($VisitStatus ==3) //3 means Not Visited
            {
                $result=mysqli_query($conn, "update scheduledetail set Status='$VisitStatus',Reason='$Reason' where ID='$id'");
            }

    $response["success"] =1;
}else{
    $response["success"] =0;
}
mysqli_close($conn);
echo json_encode($response);
?>