<?php include_once('layout/head.php'); ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Report (<?= $_GET['cat']; ?>)

            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">


                    <div class="box box-primary">
                        <div class="box-header">


                            <form action="menu-report.php" method="get">
                                <input type="hidden" name="cat" value="<?= $_GET['cat']; ?>">
                                <center><strong>From :
                                        <input type="date" name="d1" class="tcal" value="<?php if (isset($_GET['d1'])) {
                                            echo $_GET['d1'];
                                        } ?>"/> To:
                                        <input type="date" name="d2" class="tcal" value="<?php if (isset($_GET['d2'])) {
                                            echo $_GET['d2'];
                                        } ?>"/>
                                              <?php if (isset($_GET['d1'])) { ?>
                                        <a href="menuReport.php?d1=<?= $_GET['d1']; ?>&d2=<?= $_GET['d2']; ?>&cat=<?= $_GET['cat']; ?>" target="_blank" type="submit" class="btn btn-primary pull-right btn-sm "><i class="fa fa-print"> </i>
                                            Print</a>
<?php } ?>
                                        <button mycart="" data-toggle="modal" type="submit" class="btn btn-primary pull-right btn-sm ">
                                            <i class="fa fa-search"> </i> Show
                                        </button>
                                    </strong></center>
                            </form>

                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>QTY</th>

                                    <th>Exp. Date</th>

                                    <th>Delivered Date</th>

                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <?php
                                    if ($_GET['cat'] == 'Expiration') {
                                        $dt = 'expDate';
                                    } else {
                                        $dt = 'deliveredDate';

                                    }

                                    if (isset($_GET['d1'])) {
                                        $d1 = $_GET['d1'];
                                        $d2 = $_GET['d2'];

                                        $result = $db->prepare("SELECT * FROM tbl_menu WHERE $dt BETWEEN '$d1' AND '$d2' ORDER BY  $dt ASC ");
                                    } else {
                                        $result = $db->prepare("SELECT * FROM tbl_menu ORDER BY $dt ASC");
                                    }

                                    $result->execute();
                                    for ($i = 1;
                                    $row = $result->fetch();
                                    $i++){
                                    $id = $row['id']; ?>
                                    <td> <?php echo $i; ?></td>
                                    <td><img height="50" width="50" src="assets/uploaded/<?= $row['imgUrl']; ?>"></td>

                                    <td> <?= $row['code']; ?></td>
                                    <td> <?= $row['name']; ?></td>
                                    <td> <?= $row['category']; ?></td>
                                    <td> <?= $row['price']; ?></td>

                                    <td> <?= $row['qty']; ?></td>
                                    <td> <?= $row['expDate']; ?></td>
                                    <td> <?= $row['deliveredDate']; ?></td>

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