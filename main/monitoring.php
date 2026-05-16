<?php include_once('layout/head.php'); ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Monitoring
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

                            <form action="monitoring.php" method="get">
                                <center><strong>From :
                                        <input type="date" name="d1" class="tcal" value="<?php if (isset($_GET['d1'])) {
                                            echo $_GET['d1'];
                                        } ?>"/> To:
                                        <input type="date" name="d2" class="tcal" value="<?php if (isset($_GET['d2'])) {
                                            echo $_GET['d2'];
                                        } ?>"/>
                                              <?php if (isset($_GET['d1'])) { ?>
                                        <a href="monitoringPrint.php?d1=<?=$_GET['d1']; ?>&d2=<?=$_GET['d2']; ?>" target="_blank" type="submit" class="btn btn-primary pull-right btn-sm "><i class="fa fa-print"> </i>
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
                            <table id=" " class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>QTY</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <?php
                                    if (isset($_GET['d1'])) {
                                        $d1 = $_GET['d1'];
                                        $d2 = $_GET['d2'];
                                        $result = $db->prepare("SELECT *,COALESCE(SUM(xqty),0)ttqty,COALESCE(SUM(xtotal),0)tttotal FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID WHERE xfinished='1' AND p.dt BETWEEN '$d1' AND '$d2' GROUP BY p.menuID ORDER BY p.menuID DESC");
                                    } else {
                                        $result = $db->prepare("SELECT *,COALESCE(SUM(xqty),0)ttqty,COALESCE(SUM(xtotal),0)tttotal FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID WHERE xfinished='1' GROUP BY p.menuID ORDER BY p.menuID DESC");
                                    }
                                    $result->execute();
                                    for ($i = 1;
                                    $row = $result->fetch();
                                    $i++){
                                    $id = $row['id']; ?>
                                    <td> <?=$i; ?></td>
                                    <td> <?=$row['name']; ?></td>
                                    <td> <?=$row['xprice']; ?></td>
                                    <td> <?=$row['ttqty']; ?></td>
                                    <td> <?=$row['tttotal']; ?></td>
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