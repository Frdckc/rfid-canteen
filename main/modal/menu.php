<?php 
include_once('../connect.php'); 

   if ($_GET['do']=='removeDis') { 
 unset($_SESSION['cusType']);
  unset($_SESSION['disNo']);
  unset($_SESSION['disName']);
  $_SESSION['cusType']='Walk-in';
  $message = "Discount successfully removed.";
      echo "<script type='text/javascript'>alert('$message');window.location.href='../cart.php';</script>";

}


if ($_GET['do']=='addtable') { //Insert  table 
	
	$code = $_POST['code'];  
	$result = $db->prepare("SELECT * FROM tbl_table WHERE code='$code' ");
	$result->execute(); 
	if($row = $result->fetch()) {
		$message = "Table is existed.";
		echo "<script type='text/javascript'>alert('$message');window.location.href='../menu.php';</script>";
		exit();
	} else {  
		$q = $db->prepare("INSERT INTO tbl_table(code) VALUES ('$code')");
		$q->execute(array());	
		header("location: ../menu.php?r=added");
		exit();
	} 
}

if ($_GET['do']=='addcategory') { //Insert  Category 
	$category = $_POST['category'];  
	$result = $db->prepare("SELECT * FROM tbl_category WHERE category='$category' ");
	$result->execute(); 
	if($row = $result->fetch()) {
		$_SESSION['flash'] = ['type'=>'warning','msg'=>'Category already exists.'];
		header("location: ../menu.php#addcat");
		exit();
	} else {  
		$q = $db->prepare("INSERT INTO tbl_category(category) VALUES ('$category')");
		$q->execute(array());	
		$_SESSION['flash'] = ['type'=>'success','msg'=>'Category added successfully.'];
		header("location: ../menu.php#addcat");
		exit();
	} 
}

if(isset($_POST['code'])) {
	$code = strtoupper($_POST['code']);
	$pname = strtoupper($_POST['pname']);
	$description = $_POST['description'];
	$price = $_POST['price']; 
	$qty = $_POST['qty']; 
	$category = $_POST['category']; 
	$expDate = $_POST['expDate'];  
	$imgUrl = $_FILES["imgUrl"]["name"]; 
	$dt =  date('Y-m-d h:i:s'); 

	$temp = $_FILES["imgUrl"]["tmp_name"]; 
	$name = $_FILES["imgUrl"]["name"]; 
	move_uploaded_file($temp,"../images/menu/".$name);

}

if ($_GET['do']=='add') { //Insert 

	$result = $db->prepare("SELECT * FROM tbl_menu WHERE code='$code' OR name='$pname' ");
	$result->execute(); 
	if($row = $result->fetch()) {
		$_SESSION['flash'] = ['type'=>'warning','msg'=>'Menu already exists.'];
		header("location: ../menu.php#add");
		exit();
	} else { 

		$sql = "INSERT INTO tbl_menu(code, name, description, price, qty, category, expDate, imgUrl, dt) VALUES ('$code', '$pname', '$description', '$price', '$qty', '$category', '$expDate', '$imgUrl', '$dt')";
		$q = $db->prepare($sql);
		$q->execute(array());	
		$_SESSION['flash'] = ['type'=>'success','msg'=>'Item added successfully.'];
		header("location: ../menu.php#add");
		exit();
	}
} 

if (isset($_GET['id'])) {
	$id=$_GET['id'];  
	if ($_GET['do']=='edit') { //Edit  

		if ($imgUrl == ""){
			$imgUrl = $_POST['imgUrl1']; 
		}
		$sql = "UPDATE tbl_menu SET  code=?, name=?, description=?, price=?, qty=?, category=?, expDate=?, imgUrl=?, dt=? WHERE id=?";
		$q = $db->prepare($sql);
		$q->execute(array($code,$pname,$description,$price,$qty,$category,$expDate,$imgUrl,$dt,$id));
		header("location: ../menu.php?r=updated");
	}
}



?>