<?php
include_once('layout/head.php');
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Employee Accounts
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <!-- /result -->
                            <a class="box-title">  <?php if (isset($_GET['r'])): ?>
                                    <?php
                                    $r = $_GET['r'];
                                    if ($r == 'added') {
                                        $classs = 'success';
                                    } else if ($r == 'updated') {
                                        $classs = 'info';
                                    } else if ($r == 'deleted') {
                                        $classs = 'danger';
                                    } else {
                                        $classs = 'hide';
                                    }
                                    ?>
                                    <div class="alert alert-<?php echo $classs ?> <?php echo $classs; ?>">
                                        <strong>Successfully <?php echo $r; ?>!</strong>
                                    </div>
                                <?php endif; ?></a>
                            <a mycart="" data-toggle="modal" data-target="#add" type="submit" class="btn btn-primary pull-right btn-m "><i class="fa fa-plus"> </i>
                                Add Account </a>

                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Employee ID</th>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Role</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php
                                    $result = $db->prepare("SELECT * FROM tbl_users WHERE active='0' ORDER BY id DESC");
                                    $result->execute();
                                    for ($i = 1;
                                    $row = $result->fetch();
                                    $i++){
                                    $id = $row['id']; ?>
                                    <td> <?php echo $i; ?></td>

                                    <td><img height="50" width="50" src="images/user/<?= $row['pic']; ?>"></td>
                                    <td> <?php echo $row['userNo']; ?></td>
                                    <td> <?php echo $row['lname']; ?></td>
                                    <td> <?php echo $row['fname']; ?></td>
                                    <td> <?php echo $row['role']; ?></td>
                                    <td> <?php echo $row['username']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                <i class="fa fa-fw fa-gear"> </i>
                                                <span class="caret"></span>
                                            </button>
<!-- DITO NAKA COMMENT LANG -->
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#edit<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-fw fa-pencil">
                                                            Edit</i></a></li>
                                                <li>
                                                    <a href="#updatePassword<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-fw fa-user">
                                                            Change Password</i></a></li>
                                                <li>
                                                    <a href="modal/user.php?do=delete&id=<?php echo $id; ?>" onclick="return  confirm('Deactive user? There is NO undo!')"><i class="fa fa-fw fa-trash">
                                                            Deactive</i></a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                              <?php } ?>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>




    <!-- /.Add -->
    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Fill Employee Details</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="modal/user.php?do=add" method="post" enctype="multipart/form-data">
                        <div class="box-body">

                            <?php $result = $db->prepare("SELECT COUNT(*) FROM tbl_users LIMIT 1");
                            $result->execute();
                            for ($i = 0; $row = $result->fetch(); $i++) {
                                $dtl = date('Ymd');
                                $genid = $row['0'] + 1; ?>
                                <div class="form-group"><label class="col-sm-2 control-label">Employee Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" name="pic" required></div>
                                </div>


                                <div class="form-group"><label class="col-sm-2 control-label">Employee ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="<?php echo 'K-' . $dtl . $genid; ?>" name="userNo" placeholder="Employee ID NO" readonly required>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Must contain at least one special characters, one number and one uppercase and lowercase letter, and at least 6 or more characters" class="form-control" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Role</label>
                                <div class="col-sm-10">
                                    <select name="role" class="form-control" required>
                                        <option>Admin</option>
                                        <option>Staff</option>
                                    </select></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Lastname</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="lastname" placeholder="Lastname" autofocus required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Firstname</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="firstname" placeholder="Firstname" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Gender</label>
                                <div class="col-sm-10">
                                    <select name="gender" class="form-control" required>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Birthday</label>
                                <div class="col-sm-10">
                                    <input onClick="_setAge();" type="date" class="form-control" name="bday" placeholder="Birthday" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Civil Status</label>
                                <div class="col-sm-10">
                                    <select name="cs" class="form-control" required>
                                        <option>Single</option>
                                        <option>Married</option>
                                        <option>Widowed</option>
                                    </select></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Email Address</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Contact Number</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="contact" pattern="11" placeholder="Contact Number" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" placeholder="Address" required>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $result = $db->prepare("SELECT * FROM tbl_users ORDER BY id DESC");
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $id = $row['id']; ?>
    <!-- /.Edit -->
    <div class="modal fade" id="edit<?php echo $id; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Edit</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="modal/user.php?do=edit&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="pic1" value="<?php echo $row['pic']; ?>" class="form-control">

                        <div class="form-group"><label class="col-sm-2 control-label">Employee Picture</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="pic" required></div>
                        </div>


                        <div class="box-body">
                            <input value="<?php echo $row['id']; ?>" type="hidden" class="form-control" name="id">
                            <div class="form-group"><label class="col-sm-2 control-label">Employee ID</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['userNo']; ?>" type="text" class="form-control" name="userNo" placeholder="Employee ID NO" readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Lastname</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['lname']; ?>" type="text" class="form-control" name="lastname" placeholder="Lastname" autofocus required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Firstname</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['fname']; ?>" type="text" class="form-control" name="firstname" placeholder="Firstname" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Role</label>
                                <div class="col-sm-10">
                                    <select name="role" class="form-control" required>
                                        <option><?php echo $row['role']; ?> </option>
                                        <option>Admin</option>
                                        <option>Staff</option>
                                    </select></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['username']; ?>" type="text" class="form-control" name="username" placeholder="Username" required readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['password']; ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{6,}" title="Must contain at least one special characters, one number and one uppercase and lowercase letter, and at least 6 or more characters" type="password" class="form-control" name="password" placeholder="Password" required readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Gender</label>
                                <div class="col-sm-10">
                                    <select name="gender" class="form-control" required>
                                        <option><?php echo $row['gender']; ?></option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Age</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['age']; ?>" type="number" class="form-control" name="age" placeholder="Age" readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Birthday</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['bday']; ?>" type="date" class="form-control" name="bday" placeholder="Birthday" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Civil Status</label>
                                <div class="col-sm-10">
                                    <select name="cs" class="form-control" required>
                                        <option><?php echo $row['cs']; ?></option>
                                        <option>Single</option>
                                        <option>Married</option>
                                        <option>Widowed</option>
                                    </select></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Email Address</label>
                                <div class="col-sm-10">
                                    <input type="email" value="<?php echo $row['email']; ?>" class="form-control" name="email" placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Contact Number</label>
                                <div class="col-sm-10">
                                    <input type="number" value="<?php echo $row['contact']; ?>" class="form-control" name="contact" placeholder="Contact Number" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?php echo $row['address']; ?>" class="form-control" name="address" placeholder="Address" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updatePassword<?php echo $id; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Update Password</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="modal/user.php?do=changePassword&id=<?php echo $id; ?>" method="post">
                        <div class="box-body">
                            <input value="<?php echo $row['id']; ?>" type="hidden" class="form-control" name="id">
                            <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['username']; ?>" type="text" class="form-control" name="username" placeholder="Username" required readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['password']; ?>" type="password" class="form-control" name="password" placeholder="Password" required readonly>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Type Password</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="cPassword" placeholder="Type Current Password" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">New Password</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nPassword" placeholder="New Password" required>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php } ?>


<?php include_once('layout/footer.php'); ?>