<?php
if(!isset($db)) {
    include_once('connect.php');
}

if (isset($_SESSION['logged_in'])) if($_SESSION['logged_in']== "false"){
    header("Location: ../index.php");
    exit;
}

// Role-based redirects removed per request to allow all roles to navigate freely.
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>    <?=$system_title;?></title>
    <link rel="shortcut icon" href="images/gfaLogo.jpg" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="assets/plugins/datatables/dataTables.bootstrap.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="assets/dist/css/skins/_all-skins.min.css"> 
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

 <!--Refresh JS -->
<script src="http://code.jquery.com/jquery-latest.js"></script>
</head>

  
<body class="hold-transition skin-yellow sidebar-mini">
<div class="wrapper">


    <?php

  include_once('layout/header.php');
  include_once('layout/sidebar.php');

  ?>
