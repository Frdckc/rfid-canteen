<?php
include_once('../connect.php');

// Check if the form for adding a user is submitted
if (isset($_POST['lastname'])) {
    // Collect form data
    $lastname = $_POST['lastname'];
    $firstname = $_POST['firstname'];
    $role = $_POST['role'];  // Get the role from the form (Admin or Staff)
    $username = $_POST['username'];
    $password = ($_POST['password']);
    $gender = $_POST['gender'];
    $cs = $_POST['cs'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $zero = '0';  // Default active status (0 means inactive)

    // Get and format the user's date of birth (bday) and calculate their age
    $bday = $_POST['bday'];
    $userNo = $_POST['userNo'];
    $age = date('d/m/Y', strtotime(str_replace('-', '/', $bday)));
    $age = dob($age);  // Function to calculate the age based on date of birth

    // Check if the user is under 18, if so, display an error message and stop the process
    if ($age < 18) {
        $message = "Age is invalid.";
        echo "<script type='text/javascript'>alert('$message');history.go(-1);</script>";
        exit();
    }
}


// If the action is 'add' (adding a new user)
if ($_GET['do'] == 'add') {
    // Handle file upload for the profile picture
    $temp = $_FILES["pic"]["tmp_name"];
    $pic = $_FILES["pic"]["name"];
    move_uploaded_file($temp, "../images/user/" . $pic);

    // Check if a user with the same username and password already exists
    $result = $db->prepare("SELECT * FROM tbl_users WHERE username='$username' AND password='$password' ");
    $result->execute();
    if ($row = $result->fetch()) {
        // If the user exists, alert and prevent further action
        $message = "User is existed.";
        echo "<script type='text/javascript'>alert('$message');history.go(-1);</script>";
        exit();
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO tbl_users(pic,fname, lname, role, active, username, password,gender,cs,email,contact,address,bday,age,userNo) VALUES ('$pic','$firstname','$lastname','$role','$zero','$username','$password','$gender','$cs','$email','$contact','$address','$bday','$age','$userNo')";
        $q = $db->prepare($sql);
        $q->execute(array());
        // Redirect to the user page with a success message
        header("location: ../user.php?r=added");
        exit();
    }
}

// If the action is 'goLogin' (redirect to login page after registration)
if ($_GET['do'] == 'goLogin') {
    $distime = $_SESSION['distime'];
    if ($_SESSION['attemp'] == '0') {
        $message = "You can now login.";
    } else {
        $message = "Wait until for a while.";
    }
    echo "<script type='text/javascript'>alert('$message');window.location.href='../../index.php';</script>";
    exit();
}

// If the action is 'login' (user login)
if ($_GET['do'] == 'login') {
    $a = $_POST['username'];
    $b = $_POST['password'];

    // Check if the username exists in the database
    $result = $db->prepare("SELECT *,CONCAT(fname,' ',lname) fullname FROM tbl_users WHERE username='$a'");
    $result->execute();
    if ($row = $result->fetch()) {
        // Set session variables if user is found
        $_SESSION['userID'] = $row['id'];
        $_SESSION['fullname'] = $row['fullname'];
        // Normalize role casing/spacing to avoid misrouting
        $_SESSION['role'] = ucfirst(strtolower(trim($row['role'])));
        $_SESSION['pic'] = $row['pic'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['attemp'] = $row['logattempt'];

        // If login attempts reached 5, lock the account
        if ($row['logattempt'] == '5') {
            $_SESSION['attemp'] = '0';
            $_SESSION['login_error'] = 'Unable to login. Please contact your administrator to reactivate your account.';
            header("Location: ../../index.php");
            exit();
        } elseif ($b == $row['password']) {
            // Reset attempts and log user in if password matches
            session_regenerate_id(true); // prevent session mix-ups between users
            $_SESSION['attemp'] = '0';
            $_SESSION['logged_in'] = "true";
            $role = $_SESSION['role'];
            $fullname = $_SESSION['fullname'];

            $userID = $_SESSION['userID'];
            $action = "Logged-in.";
            $r = $db->prepare("INSERT INTO tbl_ualt(userID, dt,type, action) VALUES ('$userID','$dt', '$role', '$action')");
            $r->execute(array());
                   $q = $db->prepare("UPDATE tbl_users SET logattempt='0' WHERE id='$userID'");
            $q->execute(array());

            // Redirect based on role (Admin -> dashboard, Staff -> menu)
            if (strcasecmp($role, 'Admin') === 0) {
                header("Location: ../index.php");
            } elseif (strcasecmp($role, 'Staff') === 0) {
                header("Location: ../menu.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            // Increment failed login attempts and update them in the database
            $_SESSION['attemp'] = $_SESSION['attemp'] + 1;
            $stat = $_SESSION['attemp'];
            $id = $row['id'];
            $q = $db->prepare("UPDATE tbl_users SET logattempt='$stat' WHERE id='$id'");
            $q->execute(array());

            // Lock the account if 5 failed attempts
            if ($_SESSION['attemp'] == '5') {
                $_SESSION['attemp'] = 0;
                $_SESSION['login_error'] = 'Unable to login. Please contact your administrator to reactivate your account.';
                header("Location: ../../index.php");
                exit();
            } else {
                // Show error with attempt count and return to login page
                $_SESSION['login_error'] = 'Invalid username or password. Attempt: ' . $stat;
                header("Location: ../../index.php");
                exit();
            }
        }
    } else {
        // If the user is not found, reset the attempts and show error
        $_SESSION['attemp'] = '0';
        $_SESSION['login_error'] = 'Invalid user does not exist.';
        header("Location: ../../index.php");
        exit();
    }
}


if ($_GET['do'] == 'loginparents') {
    $a = $_POST['username'];
    $b = $_POST['password'];

    $result = $db->prepare("SELECT *,CONCAT(fname,' ',lname) fullname FROM tbl_students WHERE username='$a'");
    $result->execute();
    if ($row = $result->fetch()) {
        $_SESSION['userID'] = $row['id'];
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['role'] = 'Parent';  // ✅ role always set
        $_SESSION['pic'] = $row['pic'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['attemp'] = $row['logattempt']?? 0;
        $_SESSION['balance_limit'] = $row['per_day_balance'];
        $_SESSION['balance_amount'] = $row['balance_amount'];

        if ($row['status'] == 'Deactive') { 
            $_SESSION['login_error'] = 'Deactivated account.';
            header("Location: ../../parents-login.php");
            exit();
        }

        if ($b == $row['password']) {
            session_regenerate_id(true); // prevent session mix-ups between users
            $_SESSION['attemp'] = '0';
            $_SESSION['logged_in'] = "true";
            
            $role = $_SESSION['role'] ?? 'Parent';   // ✅ fix undefined $role
            $fullname = $_SESSION['fullname'];

            $userID = $_SESSION['userID'];
            $action = "Logged-in.";
            $r = $db->prepare("INSERT INTO tbl_ualt(userID, dt, type, action) VALUES ('$userID','$dt','$role','$action')");
            $r->execute(array());

            // Silent redirect on successful parent login
            header("Location: ../balances.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Invalid username or password.';
            header("Location: ../../parents-login.php");
            exit();
        }
    } else {
        $_SESSION['attemp'] = '0';
        $_SESSION['login_error'] = 'Invalid user does not exist.';
        header("Location: ../../parents-login.php");
        exit();
    }
}



// If the action is 'autoLogout' (automatic logout after a session timeout)
if ($_GET['do'] == 'autoLogout') {
    $userID = $_SESSION['userID'];
    $role = $_SESSION['role'] ?? ''; // ✅ define role safely
    $action = "Logged-out.";

    $r = $db->prepare("INSERT INTO tbl_ualt(userID, dt, type, action) VALUES ('$userID','$dt','$role','$action')");
    $r->execute(array());

    unset($_SESSION['userID']);
    unset($_SESSION['fname']);
    unset($_SESSION['role']);
    unset($_SESSION['username']);
    $_SESSION['logged_in'] = "false";
    session_unset();
    session_destroy();

    // Silent redirect on auto logout
    if ($role === 'Admin' || $role === 'Staff') {
        header("Location: ../../index.php");
    } else if ($role === 'Parent') {
        header("Location: ../../parents-login.php");
    } else {
        header("Location: ../../index.php");
    }
    exit();
}

// If the action is 'logout' (manual logout)
if ($_GET['do'] == 'logout') {
    $userID = $_SESSION['userID'];
    $role   = $_SESSION['role'] ?? ''; // ✅ fix undefined role
    $action = "Logged-out.";

    $r = $db->prepare("INSERT INTO tbl_ualt(userID, dt, type, action) VALUES ('$userID', '$dt', '$role', '$action')");
    $r->execute(array());

    // Silent redirect on manual logout
    if ($role === 'Admin' || $role === 'Staff') { 
        header("Location: ../../index.php");
    } else if ($role === 'Parent') {
        header("Location: ../../parents-login.php");
    } else {
        header("Location: ../../index.php");
    }

    unset($_SESSION['userID']);
    unset($_SESSION['fname']);
    unset($_SESSION['role']);
    unset($_SESSION['username']);
    $_SESSION['logged_in'] = "false";
    session_unset();
    session_destroy();

    exit();
}


// If the action is 'edit' (edit user details)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_GET['do'] == 'edit') {
        // Handle file upload for profile picture
        $pic = $_FILES["pic"]["name"];
        if ($pic == "") {
            $pic = $_POST['pic1'];  // Use the existing picture if no new picture is uploaded
        } else {
            $temp = $_FILES["pic"]["tmp_name"];
            $pic = $_FILES["pic"]["name"];
            move_uploaded_file($temp, "../images/user/" . $pic);
        }

        // Update user details in the database
        $sql = "UPDATE tbl_users SET pic=?, fname=?, lname=?, role=?, active=?, username=?,gender=?,cs=?,email=?,contact=?,address=?,bday=?,age=? WHERE id=?";
        $q = $db->prepare($sql);
        $q->execute(array($pic, $firstname, $lastname, $role, $zero, $username, $gender, $cs, $email, $contact, $address, $bday, $age, $id));
        header("location: ../user.php?r=updated");
    }

    // If the action is 'delete', deactivate the user by setting 'active' to 1
    if ($_GET['do'] == 'delete') {
        $q = $db->prepare("UPDATE tbl_users SET active='1' WHERE id='$id'");
        $q->execute(array());
        header("location: ../user.php?r=deleted");
    }

        // Change Admin Password
    if (isset($_GET['do']) && $_GET['do'] == 'changePassword') {
        $cpassword = $_POST['cPassword'];
        $npassword = $_POST['nPassword'];
        $rpassword = $_POST['rPassword'];

        if ($npassword !== $rpassword) {
            echo "<script>alert('New and re-typed passwords do not match.');window.location.href='../user.php';</script>";
            exit();
        }

        $result = $db->prepare("SELECT * FROM tbl_users WHERE id = :id AND password = :cpassword");
        $result->execute([':id' => $id, ':cpassword' => $cpassword]);

        if ($result->fetch()) {
            $update = $db->prepare("UPDATE tbl_users SET password = :npassword WHERE id = :id");
            $update->execute([':npassword' => $npassword, ':id' => $id]);
            header("Location: ../user.php?r=updated");
            exit();
        } else {
            echo "<script>alert('Current password is incorrect.');window.location.href='../user.php';</script>";
            exit();
        }
    }

    // Change User Password
    if (isset($_GET['do']) && $_GET['do'] == 'changePasswordUser') {
        $cpassword = $_POST['cPassword'];
        $npassword = $_POST['nPassword'];
        $rpassword = $_POST['rPassword'];

        if ($npassword !== $rpassword) {
            echo "<script>alert('New and re-typed passwords do not match.');window.location.href='../user.php';</script>";
            exit();
        }

        $result = $db->prepare("SELECT * FROM tbl_users WHERE id = :id AND password = :cpassword");
        $result->execute([':id' => $id, ':cpassword' => $cpassword]);

        if ($result->fetch()) {
            $update = $db->prepare("UPDATE tbl_users SET password = :npassword WHERE id = :id");
            $update->execute([':npassword' => $npassword, ':id' => $id]);
            echo "<script>alert('Password changed successfully.');history.go(-1);</script>";
            exit();
        } else {
            echo "<script>alert('Current password is incorrect.');history.go(-1);</script>";
            exit();
        }
    }

    // If the action is 'delete', delete the user from the database
    if ($_GET['do'] == 'delete') {
        $q = $db->prepare("DELETE FROM tbl_users WHERE id=?");
        $q->execute([$id]);
        header("location: ../user.php?r=deleted");
    }
}

// Function to log user actions (e.g., login/logout) in the database
function ualt($action)
{
    date_default_timezone_set('Asia/Manila');
    $dt = date('Y-m-d h:i:s');
    $r = $db->prepare("INSERT INTO tbl_ualt(id, dt, action) VALUES ('$userID','$dt','$action')");
    $r->execute(array());
}

?>