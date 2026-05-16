<?php
include_once('../connect.php');

if (isset($_POST['lastname'])) {
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $role = $_POST['role'];
    $username = $_POST['username'];
    $password = ($_POST['password']);

    $gender = $_POST['gender'];
    $cs = $_POST['cs'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $zero='1';
    $rfidno= $_POST['rfidno'];


    $bday = $_POST['bday'];
    $studentNo = $_POST['studentNo'];
    $age = date('d/m/Y', strtotime(str_replace('-', '/', $bday)));
    $age= dob($age);

    if ($age<18) {
        $message = "Age is invalid.";
        echo "<script type='text/javascript'>alert('$message');history.go(-1);</script>";
        exit();
    }
}

if ($_GET['do']=='add') { //Insert

    $temp = $_FILES["pic"]["tmp_name"];
    $pic = $_FILES["pic"]["name"];
    move_uploaded_file($temp,"../images/student/".$pic);


    $result = $db->prepare("SELECT * FROM tbl_students WHERE rfidno='$rfidno'  OR studentNo='$studentNo'");
    $result->execute();
    if($row = $result->fetch()) {
        $message = "User is existed.";
        echo "<script type='text/javascript'>alert('$message');history.go(-1);</script>";
        exit();
    }

    $result = $db->prepare("SELECT * FROM tbl_students WHERE username='$username' AND password='$password' ");
    $result->execute();
    if($row = $result->fetch()) {
        $message = "User is existed.";
        echo "<script type='text/javascript'>alert('$message');history.go(-1);</script>";
        exit();
    } else {
        $sql = "INSERT INTO tbl_students(pic,fname, lname,  status, username, password,gender,email,contact,address,bday,age,studentNo,rfidno) VALUES ('$pic','$firstname','$lastname','$zero','$username','$password','$gender','$email','$contact','$address','$bday','$age','$studentNo','$rfidno')";
        $q = $db->prepare($sql);
        $q->execute(array());
        header("location: ../students.php?r=added");
        exit();
    }
}

if (isset($_GET['id'])) {
    $id=$_GET['id'];
 
    if ($_GET['do']=='addBalance') { //Edit
        $old = $_POST['old'];
        $addBalance =$old+ $_POST['addBalance'];
        $sql = "UPDATE tbl_students SET balance_amount=? WHERE id=?";
        $q = $db->prepare($sql);
        $q->execute(array($addBalance,$id));
        
        
        $trans= date('YmdHis');
        $amt=$_POST['addBalance'];
        $sql = "INSERT INTO tbl_transactions(transNo, studentID,category,trans_amount,new_balance) VALUES ( '$trans','$id','IN','$amt','$addBalance')";
        $q = $db->prepare($sql);
        $q->execute(array());

        header("location: ../balance.php?r=updated");

    }


    if ($_GET['do']=='edit') { //Edit
        $pic = $_FILES["pic"]["name"];
        if ($pic==""){
            $pic=$_POST['pic1'];
        }else{
            $temp = $_FILES["pic"]["tmp_name"];
            $pic = $_FILES["pic"]["name"];
            move_uploaded_file($temp,"../images/student/".$pic);
        }


        $sql = "UPDATE tbl_students SET pic=?,rfidno=?, fname=?, lname=?,  status=?, username=?,gender=?,email=?,contact=?,address=?,bday=?,age=? WHERE id=?";
        $q = $db->prepare($sql);
        $q->execute(array($pic,$rfidno,$firstname,$lastname,$zero,$username,$gender,$email,$contact,$address,$bday,$age,$id));
        header("location: ../students.php?r=updated");
    }

    if ($_GET['do']=='delete') {
        $q = $db->prepare("DELETE FROM tbl_students WHERE id = ?");
        $q->execute([$id]);
        header("location: ../students.php?r=deleted");
    }
    
       if ($_GET['do']=='Active') {
        $q = $db->prepare("UPDATE  tbl_students SET status='Active' WHERE id = ?");
        $q->execute([$id]);
        header("location: ../students.php?r=Active");
    }
    
      if ($_GET['do']=='Inactive') {
        $q = $db->prepare("UPDATE  tbl_students SET status='Inactive' WHERE id = ?");
        $q->execute([$id]);
        header("location: ../students.php?r=Inactive");
    }

    if ($_GET['do']=='changePassword') { //Changepassword
        $cpassword = ($_POST['cPassword']);
        $rpassword = ($_POST['rPassword']);


        $npassword = ($_POST['nPassword']);
        $result = $db->prepare("SELECT * FROM tbl_students WHERE id='$id' AND password='$cpassword'");
        $result->execute();
        if($row = $result->fetch()) {
            $q = $db->prepare("UPDATE tbl_students SET password='$npassword' WHERE id='$id'");
            $q->execute(array());
            header("location: ../students.php?r=updated");
        } else {
            $message = "Invalid password.";
            echo "<script type='text/javascript'>alert('$message');window.location.href='../students.php';</script>";
            exit();

        }
    }

    

}


?>