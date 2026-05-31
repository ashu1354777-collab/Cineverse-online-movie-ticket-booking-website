<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
include "connection.php";

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
if (empty($_POST)) {
	echo "<b>Error:</b> No POST data received. This page must be called by Paytm callback.<br>";
	echo "Please ensure CALLBACK_URL in pgRedirect.php is http://localhost/cineverse/project/pgResponse.php<pre>" . htmlspecialchars(print_r($_REQUEST,true)) . "</pre>";
	exit;
}

$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.

if ($isValidChecksum == "TRUE") {
	if (isset($_POST["STATUS"]) && $_POST["STATUS"] == "TXN_SUCCESS") {
		//Payment success: update booking record
		$t1 = "";
		if (isset($_POST['ORDERID'])) { $t1 = mysqli_real_escape_string($con, $_POST['ORDERID']); }
		elseif (isset($_POST['ORDER_ID'])) { $t1 = mysqli_real_escape_string($con, $_POST['ORDER_ID']); }
		$t2 = isset($_POST['TXNAMOUNT']) ? mysqli_real_escape_string($con, $_POST['TXNAMOUNT']) : "0";

		if ($t1 !== "") {
			$qry = "UPDATE bookingtable SET amount='$t2' WHERE ORDERID='$t1'";
			mysqli_query($con, $qry);
		}

		header('Location: reciept.php?id=' . urlencode($t1));
		exit;
	} else {
		//Payment failed (or other status)
		$t1 = isset($_POST['ORDERID']) ? mysqli_real_escape_string($con, $_POST['ORDERID']) : "";
		if ($t1 !== "") {
			$qry = "UPDATE bookingtable SET amount='Failed' WHERE ORDERID='$t1'";
			mysqli_query($con, $qry);
		}
		header('Location: fail.html');
		exit;
	}
} else {
	// Detailed mismatch handling for debugging and fallback if needed
	$t1 = "";
	if (isset($_POST['ORDERID'])) { $t1 = mysqli_real_escape_string($con, $_POST['ORDERID']); }
	elseif (isset($_POST['ORDER_ID'])) { $t1 = mysqli_real_escape_string($con, $_POST['ORDER_ID']); }
	$t2 = isset($_POST['TXNAMOUNT']) ? mysqli_real_escape_string($con, $_POST['TXNAMOUNT']) : "0";
	if ($t1 !== "") {
		$qry = "UPDATE bookingtable SET amount='Checksum Mismatched ($t2)' WHERE ORDERID='$t1'";
		mysqli_query($con, $qry);
	}
	echo "<b>Checksum mismatched.</b>";
	echo "<br/>ORDER_ID=" . htmlspecialchars($t1 ?: (isset($_POST['ORDER_ID']) ? $_POST['ORDER_ID'] : ''));
	echo "<br/>TXN_AMOUNT=" . htmlspecialchars($t2);
	if (isset($_POST['CHECKSUMHASH'])) {
		echo "<br/>CHECKSUMHASH=" . htmlspecialchars($_POST['CHECKSUMHASH']);
	}
	// do not redirect automatically - let admin inspect
}

