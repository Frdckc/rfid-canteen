<?php include_once('layout/head.php'); ?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Transaction Report
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

                            <form action="report.php" method="get">
                                <center><strong>From :
                                        <input type="date" name="d1" class="tcal" value="<?php if (isset($_GET['d1'])) {
                                            echo $_GET['d1'];
                                        } ?>"/> To:
                                        <input type="date" name="d2" class="tcal" value="<?php if (isset($_GET['d2'])) {
                                            echo $_GET['d2'];
                                        } ?>"/>
                                              <?php if (isset($_GET['d1'])) { ?>
                                        <a href="reportPrint.php?d1=<?php echo $_GET['d1']; ?>&d2=<?php echo $_GET['d2']; ?>" target="_blank" type="submit" class="btn btn-primary pull-right btn-sm "><i class="fa fa-print"> </i>
                                            Print</a>
                                            <?php }?>

                                        <button mycart="" data-toggle="modal" type="submit" class="btn btn-primary pull-right btn-sm ">
                                            <i class="fa fa-search"> </i> Show
                                        </button>
                                    </strong></center>
                            </form>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction No</th>
                                    <th>Student   Name</th>
                                    <th>Category</th>
                                    <th>Total Amount</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php
                                    if (isset($_GET['d1'])) {
                                        $d1 = $_GET['d1'];
                                        $d2 = $_GET['d2'];
                                        $result = $db->prepare("SELECT * FROM tbl_transactions WHERE  created_at BETWEEN '$d1' AND '$d2'  ");
                                    } else {
                                        $result = $db->prepare("SELECT *,CONCAT(fname,' ' ,lname)name  FROM tbl_transactions t INNER JOIN tbl_students s ON s.id=t.studentID ");
                                    }
                                    $result->execute();
                                    for ($i = 1;
                                    $row = $result->fetch();
                                    $i++){
                                    $id = $row['id']; ?>
                                    <td> <?php echo $i; ?></td>
                                    <td> <?php echo $row['transNo']; ?></td>
                                    <td> <?php echo $row['name']; ?></td>
                                    <td> <?php echo $row['category']; ?></td>
                                    <td> <?php echo $row['trans_amount']; ?></td>
                                    <td> <?php echo $row['created_at']; ?></td>
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


<?php include_once('layout/footer.php'); ?>