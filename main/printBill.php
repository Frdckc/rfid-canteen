<?php include_once('layout/head.php'); ?>

    <script>
        $(document).ready(function () {
            setInterval(function () {
                $("#refreshMoto").load("billing.php #refreshMoto");
            }, 1000);
        });
    </script>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Billing
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- row -->
            <div id="refreshMoto">
                <div class="col-md-12">
                    <!-- The time line -->
                    <ul class="timeline">
                        <!-- timeline time label -->
                        <li class="time-label">
            <span class="bg-red">
        Pay out<!--       <?php echo date('d M. Y'); ?>  -     <?php echo date('h:i:s A '); ?> -->
            </span>
                        </li>
                        <!-- /.timeline-label -->


                        <!-- timeline item -->
                        <?php
                        $result = $db->prepare("SELECT *,SUM(xprice*xqty)as totals,p.dt as pdt,pp.cash-pp.xtotal as pxtotal FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID INNER JOIN tbl_payment pp ON pp.invoiceNo=p.invoiceNo WHERE xfinished='0' AND stat='3' GROUP by p.invoiceNo,p.stat  ORDER BY p.id ASC");
                        $result->execute();
                        for ($i = 1; $row = $result->fetch(); $i++) {
                            $invoiceNo = $row['invoiceNo']; ?>
                            <li>
                                <i class="fa   bg-blue">₱</i>
                                <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i> <?php

                  $date = date_create($row['pdt']);
                  $date = date_format($date, 'H:i');
                  $currentDate = strtotime($date);
                  $futureDate = $currentDate;
                  echo $formatDate = date("H:i", $futureDate); ?> mins ago</span>
                                    <h3 class="timeline-header">
                                        <a href="#">   <?php echo '00' . $i . ') - ' . $row['tableCode'] . ' - ( ' . $row['xtotal'] . ' ) change ' . $row['pxtotal']; ?> </a>
                                    </h3>
                                    <div class="timeline-body">


                                        <div class="table-responsive">
                                            <table class="table no-margin">
                                                <thead>
                                                <tr>
                                                    <th>Code</th>
                                                    <th>Product Name</th>
                                                    <th>QTY</th>
                                                    <th>Price</th>
                                                    <th>Total</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                <?php $r = $db->prepare("SELECT * FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID WHERE xfinished='0' AND stat='3' AND invoiceNo='$invoiceNo'  ORDER BY p.id ASC");
                                                $r->execute();
                                                for ($x = 1; $rows = $r->fetch(); $x++) { ?>
                                                    <tr>
                                                        <td><?php echo $rows['code']; ?></td>
                                                        <td><?php echo $rows['name']; ?></td>
                                                        <td><?php echo $rows['xqty']; ?></td>
                                                        <td><?php echo $rows['xprice']; ?></td>
                                                        <td><?php echo $rows['xtotal']; ?></td>
                                                        <td>
                                                            <div class="sparkbar" data-color="#00a65a" data-height="20">
                                                                <canvas width="34" height="20" style="display: inline-block; width: 34px; height: 20px; vertical-align: top;"></canvas>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="timeline-footer">
                                        <a href="modal/statusOrder.php?do=billing&invoiceNo=<?php echo $row['invoiceNo']; ?>" onclick="return  confirm('Finished ?')" class="btn btn-warning btn-xs">Finished</a>
                                        <a target="_BLANK" href="billing-print.php?invoiceNo=<?php echo $row['invoiceNo']; ?>&cat=0" onclick="return  confirm('Print Receipt ?')" class="btn btn-success btn-xs">Print
                                            Receipt</a>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                        <!-- END timeline item -->

                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

<?php include_once('layout/footer.php'); ?>