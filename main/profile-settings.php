<?php include_once('layout/head.php'); ?>
<?php $user_id = $_SESSION['userID']; ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user text-blue"></i> Profile Settings
            <small>Manage your personal information</small>
        </h1>
    </section>

    <?php
    if (isset($_POST['updateProfile'])) {
        $lastname  = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $username  = $_POST['username'];
        $password  = $_POST['password'];
        $gender    = $_POST['gender'];
        $email     = $_POST['email'];
        $contact   = $_POST['contact'];
        $address   = $_POST['address'];
        $rfidno    = $_POST['rfidno'];
        $bday      = $_POST['bday'];
        $studentNo = $_POST['studentNo'];
        $zero      = '1';

        // Compute age from birthday
        $age = date('d/m/Y', strtotime(str_replace('-', '/', $bday)));
        $age = dob($age);
        if ($age < 18) {
            echo "<script>alert('Age is invalid.');history.go(-1);</script>";
            exit();
        }

        // Handle profile picture
        $pic = $_FILES["pic"]["name"];
        if ($pic == "") {
            $pic = $_POST['pic1'];
        } else {
            $temp = $_FILES["pic"]["tmp_name"];
            move_uploaded_file($temp, "images/student/" . $pic);
        }

        // Update profile
        $sql = "UPDATE tbl_students 
                SET pic=?, fname=?, lname=?, status=?, username=?, password=?, gender=?, email=?, contact=?, address=?, bday=?, age=? 
                WHERE id=?";
        $q = $db->prepare($sql);
        $q->execute([$pic, $firstname, $lastname, $zero, $username, $password, $gender, $email, $contact, $address, $bday, $age, $user_id]);


        echo "<script>alert('Profile updated successfully.');window.location.href='profile-settings.php';</script>";
    }
    ?>

    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-edit"></i> Update Profile</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        $result = $db->prepare("SELECT * FROM tbl_students WHERE id=?");
                        $result->execute([$user_id]);
                        if ($row = $result->fetch()) { ?>
                        
                        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="pic1" value="<?= $row['pic']; ?>">

                            <!-- Profile Picture -->
                            <div class="form-group text-center">
                                <div class="col-sm-12">
                                    <img src="images/student/<?= $row['pic']; ?>" 
                                         class="img-circle" alt="Profile Picture" width="120" height="120"
                                         style="border:3px solid #3c8dbc; margin-bottom:10px;">
                                    <input type="file" class="form-control" name="pic">
                                </div>
                            </div>

                            <!-- Student Info -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Student ID</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['studentNo']; ?>" type="text" class="form-control" name="studentNo" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Lastname</label>
                                <div class="col-sm-4">
                                    <input value="<?= $row['lname']; ?>" type="text" class="form-control" name="lastname" required>
                                </div>
                                <label class="col-sm-2 control-label">Firstname</label>
                                <div class="col-sm-4">
                                    <input value="<?= $row['fname']; ?>" type="text" class="form-control" name="firstname" required>
                                </div>
                            </div>

                            <!-- Account -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-4">
                                    <input value="<?= $row['username']; ?>" type="text" class="form-control" name="username" readonly>
                                </div>
                                <label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-4">
                                    <input value="<?= $row['password']; ?>" type="password" class="form-control" name="password"
                                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{6,}" 
                                           title="Must contain at least one special character, one number, one uppercase, one lowercase letter, and be at least 6 characters long"
                                           required>
                                </div>
                            </div>

                            <!-- Gender, Age, Birthday -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Gender</label>
                                <div class="col-sm-4">
                                    <select name="gender" class="form-control" required>
                                        <option><?= $row['gender']; ?></option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                                <label class="col-sm-2 control-label">Age</label>
                                <div class="col-sm-4">
                                    <input value="<?= $row['age']; ?>" type="number" class="form-control" name="age" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Birthday</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['bday']; ?>" type="date" class="form-control" name="bday" required>
                                </div>
                            </div>

                            <!-- Contact -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-4">
                                    <input type="email" value="<?= $row['email']; ?>" class="form-control" name="email" required>
                                </div>
                                <label class="col-sm-2 control-label">Contact</label>
                                <div class="col-sm-4">
                                    <input type="number" value="<?= $row['contact']; ?>" class="form-control" name="contact" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?= $row['address']; ?>" class="form-control" name="address" required>
                                </div>
                            </div>

                            <!-- Update Button -->
                            <div class="box-footer text-right">
                                <button type="submit" name="updateProfile" class="btn btn-success">
                                    <i class="fa fa-check"></i> Update Profile
                                </button>
                            </div>
                        </form>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include_once('layout/footer.php'); ?>
