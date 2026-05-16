<?php
include_once('connect.php');

date_default_timezone_set('Asia/Manila');


$invoiceNo = $_GET['invoiceNo'];
$result = $db->prepare("SELECT * FROM tbl_purchases p  WHERE invoiceNo='$invoiceNo'");
$result->execute();
if ($rows = $result->fetch()) { ?>

    <body onload="window.print();"> <!-- onload="window.print();" style="width:100%" -->
    <table>
        
        <!-- <tr>
			<td colspan="2">Invoice # : <?php echo $rows['invoiceNo']; ?></td>
			<td></td>
		</tr> -->
        <?php $invoiceNo = $_GET['invoiceNo'];
        $result = $db->prepare("SELECT * FROM tbl_payment  WHERE invoiceNo='$invoiceNo'");
        $result->execute();
        if ($rowss = $result->fetch()) { ?>

 <tr>
                <td align='center' colspan="2">   <?=$invoiceNo;?></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="2">Date : <?php $dt = $rowss['dt'];
                    echo date("Y-m-d", strtotime($dt)); ?></td>
                <td></td>
            </tr>
            <tr
            >
                <td colspan="2">Time : <?php $dt = $rowss['dt'];
                    echo date("h:i:sa", strtotime($dt)); ?></td>
                <td></td>
            </tr>
        <?php } ?>


        <td colspan="3">
            <hr/>
        </td>
        <tr align="left" style="color:maroon;">
            <th>QTY</th>
            <th align="center" colspan="3">ORDERS</th>
        </tr>

        <?php
        $cat = $_GET['cat'];
        $result = $db->prepare("SELECT *,SUM(xqty) as sumqty FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID WHERE xfinished='$cat' AND invoiceNo='$invoiceNo'   GROUP by menuID ORDER BY p.id DESC");
        $result->execute();
        $xtotal = '0';
        for ($i = 1; $row = $result->fetch(); $i++) {
            $total = $row['price'] * $row['sumqty']; ?>
            <tr>
                <td><?php echo $row['xqty']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo number_format($row['xqty'] * $row['price'], 2); ?></td>
            </tr>


            <?php $xtotal = $xtotal + $total;
            $_SESSION['totalbills'] = $xtotal; ?>
        <?php } ?>
        <td colspan="3">
            <hr/>
        </td>

        <tr>
            <td colspan="2">Sub Total :</td>
            <td><?php echo $_SESSION['subtotal'] = number_format($_SESSION['totalbills'], 2); ?></td>
        </tr>
        <tr>
            <td colspan="2">VAT 12% :</td>
            <td><?php echo $_SESSION['vat12'] = number_format(($_SESSION['totalbills'] / 1.12) * 0.12, 2); ?></td>


        </tr>
        <tr>
            <td colspan="2">VAT Sales :</td>
            <td><?php echo $_SESSION['vatsale'] = number_format($_SESSION['totalbills'] / 1.12, 2); ?></td>

        </tr>
        <?php $result = $db->prepare("SELECT * FROM tbl_payment WHERE invoiceNo='$invoiceNo'");
        $result->execute();
        if ($rows = $result->fetch()) {
            if ($rows['sumDiscount'] <> '0.00') { ?>
                <tr>
                    <td colspan="2">VAT Exempt :
                        <?php echo $rows['cusType']; ?>
                    <td><?php echo $salestax = number_format(($_SESSION['totalbills'] / 1.12) * 0.20, 2); ?></td>

 
                    <td></td>

                </tr>

            <?php }
        } ?>

        <td colspan="3">
            <hr/>
        </td>
        <tr>
            <td colspan="2">Total :
            <td><?php echo $rows['xtotal']; ?></td>
            </td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2">

        </tr>
        <tr>
            <td colspan="2">
        </tr>
        <tr>
            <td colspan="2">
        </tr>
        <tr>
            <td colspan="2">
        </tr>
        <tr>
            <td colspan="2">Cash :
            <td><?php echo number_format($rows['cash'], 2); ?></td>
            </td>
            <td></td>
        </tr>

        <tr>
            <td colspan="2">Change :
            <td><?php echo number_format($rows['cash'] - $rows['xtotal'], 2); ?></td>
            </td>
            <td></td>
        </tr>
    </table>

    </body>


<?php } ?>