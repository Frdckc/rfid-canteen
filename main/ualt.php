<?php include_once('layout/head.php'); ?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User
                <small>Activity Logs</small>
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
                                    <div class="alert alert-<?=$classs ?> <?=$classs; ?>">
                                        <strong>Successfully <?=$r; ?>!</strong>
                                    </div>
                                <?php endif; ?></a>

                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Date / Time</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php
                                    $role=$_GET['role'];
                                    if ($role=='Parent'){
                                           $result = $db->prepare("SELECT *,ua.id as uaid,type as role FROM tbl_ualt u INNER JOIN tbl_students ua ON ua.id=u.userID  WHERE type='$role' ORDER BY u.id DESC");
                                  
                                    }else{
                                          $result = $db->prepare("SELECT *,ua.id as uaid FROM tbl_users u INNER JOIN tbl_ualt ua ON u.id=ua.userID  WHERE role='$role' ORDER BY ua.id DESC");
                                   
                                    }
                                   $result->execute();
                                    for ($i = 1;
                                    $row = $result->fetch();
                                    $i++){
                                    $id = $row['uaid']; ?>
                                    <td> <?=$i; ?></td>
                                    <td> <?=$row['lname'] . ' ' . $row['fname']; ?></td>
                                    <td> <?=$row['role']; ?></td>
                                    <td> <?=$row['dt']; ?></td>
                                    <td> <?=$row['action']; ?></td>

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

    </div>


<?php include_once('layout/footer.php'); ?>