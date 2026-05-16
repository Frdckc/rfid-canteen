<?php 
include_once('../connect.php'); 


if ($_GET['do']=='notAvailable') { //Cashier Remove   
	$id=$_GET['id'];

	$sql = "UPDATE  tbl_menu SET qty='0'  WHERE  id='$id'";
	$q = $db->prepare($sql);
	$q->execute(array());	 
	header("location: ../menu.php");
} 

if ($_GET['do']=='void') { //Cook  
	$invoiceNo=$_GET['invoiceNo'];

	$sql = "DELETE FROM tbl_purchases   WHERE invoiceNo='$invoiceNo'  ";
	$q = $db->prepare($sql);
	$q->execute(array());	  
	header("location: ../timelineCook.php");
} 


if ($_GET['do']=='removeOrder') { //Cashier Remove  
	$invoiceNo=$_GET['invoiceNo'];
	$menuID=$_GET['menuID'];

	$sql = "DELETE FROM tbl_purchases   WHERE   invoiceNo='$invoiceNo'  AND menuID='$menuID'";
	$q = $db->prepare($sql);
	$q->execute(array());	 
	header("location: ../timelineCashier.php");
} 

if ($_GET['do']=='menuPlus') { //Cashier Remove  
	$invoiceNo=$_GET['invoiceNo'];
	$menuID=$_GET['menuID'];
	$xqty=$_GET['qty']+1;
	$xprice=$_GET['xprice'];
	$xtotal=$_GET['xtotal']+$_GET['xprice'];

	$sql = "UPDATE  tbl_purchases  SET xqty='$xqty',xtotal='$xtotal' WHERE invoiceNo='$invoiceNo'  AND menuID='$menuID'";
	$q = $db->prepare($sql);
	$q->execute(array());	 
	header("location: ../timelineCashier.php");
} 

if ($_GET['do']=='menuMinus') { //Cashier Remove  
	$invoiceNo=$_GET['invoiceNo'];
	$menuID=$_GET['menuID'];
	$xqty=$_GET['qty']-1;
	$xprice=$_GET['xprice'];
	$xtotal=$_GET['xtotal']-$_GET['xprice'];

	$sql = "UPDATE  tbl_purchases  SET xqty='$xqty',xtotal='$xtotal' WHERE invoiceNo='$invoiceNo'  AND menuID='$menuID'";
	$q = $db->prepare($sql);
	$q->execute(array());	 
	header("location: ../timelineCashier.php");
} 



if ($_GET['do']=='cashier') { //Cashier  
	$invoiceNo=$_GET['invoiceNo'];

	$sql = "UPDATE tbl_purchases SET stat='1' WHERE invoiceNo='$invoiceNo' AND xfinished='0' AND stat='0'";
	$q = $db->prepare($sql);
	$q->execute(array());	 
	header("location: ../timelineCashier.php");
} 

if ($_GET['do']=='cook') { //Cook  
	$invoiceNo=$_GET['invoiceNo'];

	$sql = "UPDATE tbl_purchases SET stat='2' WHERE invoiceNo='$invoiceNo' AND xfinished='0' AND stat='1'";
	$q = $db->prepare($sql);
	$q->execute(array());	 
	header("location: ../timelineCook.php");
} 
if ($_GET['do']=='cookItem') { //Cook  
	$invoiceNo=$_GET['invoiceNo'];
	$menuID=$_GET['menuID'];

	$sql = "UPDATE tbl_purchases SET stat='2' WHERE invoiceNo='$invoiceNo' AND menuID='$menuID' AND xfinished='0' AND stat='1'";
	$q = $db->prepare($sql);
	$q->execute(array());	 
	header("location: ../timelineCook.php");
}
 
 if ($_GET['do']=='billing') { //Billing  
	$invoiceNo=$_GET['invoiceNo']; 
	$sql = "UPDATE tbl_purchases SET xfinished='1' WHERE invoiceNo='$invoiceNo' AND xfinished='0' AND stat='3'";
	$q = $db->prepare($sql);
	$q->execute(array());	

$sql = "UPDATE tbl_payment SET received='1' WHERE invoiceNo='$invoiceNo' AND received='0' ";
	$q = $db->prepare($sql);
	$q->execute(array());	

	header("location: ../billing.php");
} 
 
?>