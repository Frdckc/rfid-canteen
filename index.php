<?php
session_start();

// Redirect if already logged in (for any role)
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: main/index.php");
    exit;
}

include_once('main/connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= htmlspecialchars($system_title ?? 'Login'); ?></title>
  <link rel="shortcut icon" href="main/images/gfaLogo.jpg" />

  <!-- Responsive -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="main/assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="main/assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="main/assets/plugins/iCheck/square/blue.css">
</head>

<body class="hold-transition login-page" style="
  background: url(main/images/gfa.png) no-repeat center center fixed;
  background-size: cover;
  padding-top: 60px;
  padding-bottom: 40px;
">

<div class="login-box">
  <div class="login-box-body">
    <div class="login-logo">
      <img src="main/images/gfaLogo.jpg" width="200" height="200" alt="System Logo">
    </div>

    <p class="login-box-msg">Log In</p>

    <!-- Show error message if login failed -->
    <?php if (isset($_SESSION['login_error'])): ?>
      <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['login_error']); ?>
      </div>
      <?php unset($_SESSION['login_error']); ?>
    <?php endif; ?>

    <form action="main/modal/user.php?do=login" method="post" autocomplete="off">
      <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <!-- Optionally add "Remember me" checkbox here -->
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Log In</button>
        </div>
      </div>
    </form>

  </div>
</div>

<!-- jQuery -->
<script src="main/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap -->
<script src="main/assets/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="main/assets/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
  });
</script>
</body>
</html>
