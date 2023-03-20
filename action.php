<?php
session_start();
include 'lib/library.class.php';
$lib = new library;
$hostname = $lib->hostname();
error_reporting(E_ALL);
if(isset($_POST['servicetype']) && $_POST['servicetype'] == 'getamount'){	
	$res = $lib->getamount($_POST['author_type'],$_POST['nofauthors'],$_POST['nofpages'],'payment_gateway');
	
	echo json_encode($res);
}

if(!empty($_POST['mihpayid'])){
	// print_r($_POST);
	$txnid = $_POST['txnid'];
	$paymentstatus = $_POST['status'];
	$paymentmode = $_POST['mode'];
	
	$userfirstname = $_POST['firstname'];
	$useremail = $_POST['email'];
	
	$sr = 1;
	foreach ($_POST as $key => $value) {
		$txnDetailsarray[] = $key . "=" . $value;
	}
	$txnDetails = implode("|", $txnDetailsarray);
	
	$pagekey = $_SESSION['payment_gateway']['pagekey'];
	$paperId = $_SESSION['payment_gateway']['paperId'];
	
	if($paymentstatus == 'success'){
		$updateFinalmanuscript = $lib->update("finialmanuscript",array("pagekey"=>$pagekey,"paperId"=>$paperId),array("paymode"=>$paymentmode,"txnid"=>$txnid,"txnDetails"=>$txnDetails,"paymentstatus"=>$paymentstatus,"is_done"=>1));	
	} else {
		$updateFinalmanuscript = $lib->update("finialmanuscript",array("pagekey"=>$pagekey,"paperId"=>$paperId),array("paymode"=>$paymentmode,"txnid"=>$txnid,"txnDetails"=>$txnDetails,"paymentstatus"=>$paymentstatus,"is_done"=>0));
	}
	
	if($updateFinalmanuscript){
		$manuscriptDetails = $lib->select("finialmanuscript",array("pagekey"=>$pagekey,"paperId"=>$paperId),'AND');
		$to  = $manuscriptDetails[0]['nameofauthor']." <".$manuscriptDetails[0]['email'].">";				
		$subject = 'About Final Manuscript Submssion- Dt. '.date("d-m-Y").' Time '.date("h:i:s") . ' | IJIES | '.$_POST['paperId'];
		
		$mail = $lib->sendmail_finalmanuscript($to,$subject,$manuscriptDetails[0]['nameofauthor'],$pagekey,$paperId);
		
		if($mail){
			echo "1";
			header('Location: '.$hostname.'final-submission/'.$pagekey);
		} else {
			echo "0";
			header('Location: '.$hostname.'final-submission/'.$pagekey);
		}
	}
}

?>