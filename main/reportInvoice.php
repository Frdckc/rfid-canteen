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
<h2 class="page-header">
    <i class="fa fa-globe"></i> CASA MIAMARE
    <small class="pull-right"> Invoice Report <?php echo $_GET['invoiceNo'] ?></small>
    <!-- from&nbsp;<?php echo $_GET['d1'] ?>&nbsp;to&nbsp;<?php echo $_GET['d2'] ?> -->
    </div>
</h2>

<table width="100%">
    <thead>
    <tr align="left">
        <th>#</th>
        <th>Invoice #</th>
        <th>Name</th>
        <th>Price</th>
        <th>QTY</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>


    <tr>
        <?php
        if (isset($_GET['invoiceNo'])) {
            $invoiceNo = $_GET['invoiceNo'];
            $result = $db->prepare("SELECT * FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID WHERE xfinished='1' AND invoiceNo='$invoiceNo' ORDER BY p.menuID DESC");
        }
        $result->execute();
        for ($i = 1;
        $row = $result->fetch();
        $i++){
        $id = $row['id']; ?>
        <td> <?php echo $i; ?></td>
        <td> <?php echo $row['invoiceNo']; ?></td>
        <td> <?php echo $row['name']; ?></td>
        <td> <?php echo $row['xprice']; ?></td>
        <td> <?php echo $row['xqty']; ?></td>
        <td> <?php echo $row['xtotal']; ?></td>
    </tr>
    <?php } ?>
    <tr>
        <td colspan="6">
            <hr/>
        </td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td><?php
            echo $_GET['total']; ?></td>
    </tr>
    </tbody>

</table>


</body>
</html> 