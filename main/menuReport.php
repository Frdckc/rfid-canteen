<?php
include_once('connect.php');
?>
<body onload="window.print();">
<style media="print">
    @page {
        size: auto;
        margin: 0;
    }
</style>
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i>
                    <small class="pull-right">   <?= $_GET['cat'] ?> Report from&nbsp;<?= $_GET['d1'] ?>
                        &nbsp;to&nbsp;<?= $_GET['d2'] ?></small>

            </div>
            </h2>
        </div>
        <!-- /.col -->
</div>


<!-- Table row -->
<div class="row">
    <div class="col-xs-12 table-responsive">
        <table width="100%" class="table table-striped">
            <thead>
            <tr>
                <th align="left">#</th>
                <th align="left">Code</th>
                <th align="left">Name</th>
                <th align="left">Category</th>
                <th align="left">Price</th>
                <th align="left">QTY</th>
                <?php if ($_GET['cat'] == 'Expiration') { ?>
                    <th align="left">Exp. Date</th>
                <?php } else { ?>
                    <th align="left">Delivered Date</th>
                <?php } ?>
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

                $xtotal = '0';
                if (isset($_GET['d1'])) {
                    $d1 = $_GET['d1'];
                    $d2 = $_GET['d2'];
                    $result = $db->prepare("SELECT * FROM tbl_menu WHERE  $dt BETWEEN '$d1' AND '$d2' ORDER BY  $dt ASC ");
                } else {
                    $result = $db->prepare("SELECT * FROM tbl_menu ORDER BY $dt ASC");
                }
                $result->execute();
                for ($i = 1;
                $row = $result->fetch();
                $i++){
                $id = $row['id']; ?>
                <td> <?= $i; ?></td>
                <td> <?= $row['code']; ?></td>
                <td> <?= $row['name']; ?></td>
                <td> <?= $row['category']; ?></td>
                <td> <?= $row['price']; ?></td>
                <td> <?= $row['qty']; ?></td>
                <?php if ($_GET['cat'] == 'Expiration') { ?>
                    <td> <?= $row['expDate']; ?></td>

                <?php } else { ?>
                    <td> <?= $row['deliveredDate']; ?></td>

                <?php } ?>
            </tr>
            <?php } ?>
            <tr>
                <td></td>
            </tr>

            </tbody>
        </table>
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->


</section>
<!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html> 