<?php
include_once('layout/head.php');
?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Student Accounts
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
                                    <div class="alert alert-<?= $classs ?> <?= $classs; ?>">
                                        <strong>Successfully <?= $r; ?>!</strong>
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
                                    <th>Student ID</th>
                                    <th>RFID No</th>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Username</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php
                                    $result = $db->prepare("SELECT * FROM tbl_students   ORDER BY id DESC");
                                    $result->execute();
                                    for ($i = 1;
                                    $row = $result->fetch();
                                    $i++){
                                    $id = $row['id'];
                                    $stat = $row['status'];?>
                                    <td> <?= $i; ?></td>

                                    <td><img height="50" width="50" src="images/student/<?= $row['pic']; ?>"></td>
                                    <td> <?= $row['studentNo']; ?></td>
                                    <td> <?= $row['rfidno']; ?></td>
                                    <td> <?= $row['lname']; ?></td>
                                    <td> <?= $row['fname']; ?></td>
                                    <td> <?= $row['username']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                <i class="fa fa-fw fa-gear"> </i>
                                                <span class="caret"></span>
                                            </button>
<!-- DITO NAKACOMMENT LANG -->
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#edit<?= $id; ?>" data-toggle="modal"><i class="fa fa-fw fa-pencil">
                                                            Edit</i></a></li>
                                                <li>
                                                    <a href="#updatePassword<?= $id; ?>" data-toggle="modal"><i class="fa fa-fw fa-user">
                                                            Change Password</i></a></li>
                                                <li>
                                                    <?php if($stat=='Active'){ ?>
                                                       <a href="modal/student.php?do=Inactive&id=<?= $id; ?>" onclick="return  confirm('Deactive user? Are you sure ?')"><i class="fa fa-fw fa-ban">
                                                            Deactive</i>
                                                            </a>
                                                    <?php }else{ ?>
                                                       <a href="modal/student.php?do=Active&id=<?= $id; ?>" onclick="return  confirm('Active user? Are you sure ?')"><i class="fa fa-fw fa-thumbs-up">
                                                            Active</i>
                                                            </a>
                                                    
                                                    <?php }?>
                                                 
                                                 
                                                            <a href="modal/student.php?do=delete&id=<?= $id; ?>" onclick="return  confirm('Delete user? Are you sure ?')"><i class="fa fa-fw fa-trash">
                                                            Delete</i>
                                                            </a>
                                                            
                                                            </li>
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
                    <h4 class="modal-title" id="exampleModalLabel">Fill Student Details</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="modal/student.php?do=add" method="post" enctype="multipart/form-data">
                        <div class="box-body">

                     <div class="form-group"><label class="col-sm-2 control-label">Student Image</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" name="pic" required></div>
                                </div>


                                <div class="form-group"><label class="col-sm-2 control-label">Student ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="" name="studentNo"  onchange="showUsername();" placeholder="Student ID" id="studentNo"  required>
                                    </div>
                                </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Student RFID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="" name="rfidno" placeholder="RFID No"   required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" placeholder="Username" id="username" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" title="Must contain at least one special characters, one number and one uppercase and lowercase letter, and at least 6 or more characters" class="form-control" name="password" placeholder="Password" required>
                                </div>
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

<?php $result = $db->prepare("SELECT * FROM tbl_students ORDER BY id DESC");
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $id = $row['id']; ?>
    <!-- /.Edit -->
    <div class="modal fade" id="edit<?= $id; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Edit</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="modal/student.php?do=edit&id=<?= $id; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="pic1" value="<?= $row['pic']; ?>" class="form-control">

                        <div class="form-group"><label class="col-sm-2 control-label">Student Picture</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="pic" required></div>
                        </div>


                        <div class="box-body">
                            <input value="<?= $row['id']; ?>" type="hidden" class="form-control" name="id">
                            <div class="form-group"><label class="col-sm-2 control-label">Student ID</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['studentNo']; ?>" type="text" class="form-control" name="studentNo" placeholder="Student ID NO" readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Lastname</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['lname']; ?>" type="text" class="form-control" name="lastname" placeholder="Lastname" autofocus required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Firstname</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['fname']; ?>" type="text" class="form-control" name="firstname" placeholder="Firstname" required>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['username']; ?>" type="text" class="form-control" name="username" placeholder="Username" required readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['password']; ?>" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{6,}" title="Must contain at least one special characters, one number and one uppercase and lowercase letter, and at least 6 or more characters" type="password" class="form-control" name="password" placeholder="Password" required readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Gender</label>
                                <div class="col-sm-10">
                                    <select name="gender" class="form-control" required>
                                        <option><?= $row['gender']; ?></option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select></div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Age</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['age']; ?>" type="number" class="form-control" name="age" placeholder="Age" readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Birthday</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['bday']; ?>" type="date" class="form-control" name="bday" placeholder="Birthday" data-inputmask="'alias': 'yyyy-mm-dd'" data-mask required>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Email Address</label>
                                <div class="col-sm-10">
                                    <input type="email" value="<?= $row['email']; ?>" class="form-control" name="email" placeholder="Email Address" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Contact Number</label>
                                <div class="col-sm-10">
                                    <input type="number" value="<?= $row['contact']; ?>" class="form-control" name="contact" placeholder="Contact Number" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text" value="<?= $row['address']; ?>" class="form-control" name="address" placeholder="Address" required>
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

    <div class="modal fade" id="updatePassword<?= $id; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Update Password</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="modal/student.php?do=changePassword&id=<?= $id; ?>" method="post">
                        <div class="box-body">
                            <input value="<?= $row['id']; ?>" type="hidden" class="form-control" name="id">
                            <div class="form-group"><label class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['username']; ?>" type="text" class="form-control" name="username" placeholder="Username" required readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input value="<?= $row['password']; ?>" type="password" class="form-control" name="password" placeholder="Password" required readonly>
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

    <script type="text/javascript">
        function showUsername() {
            document.getElementById('username').value = document.getElementById("studentNo").value;
        }
    </script>
<?php include_once('layout/footer.php'); ?>