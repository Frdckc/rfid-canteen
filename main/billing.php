<?php include_once('layout/head.php'); ?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                BILLING
            </h1>
        </section>
        <div class="rcol">
            <div id="crumbs">
                <ul>
                    <li>
                        <a href="index.php">Home</a>&nbsp;&raquo;&nbsp;
                    </li>
                    <li>
                        Billing
                    </li>
                </ul>
                <br class="clear"/>
            </div>
            <table style="width:100%">
                <tr>
                    <td colspan="1"></td>
                    <td align="center"> <?php echo $_SESSION['name']; ?>  </td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td align="center">  <?php echo $_SESSION['address']; ?> </td>
                </tr>
                <tr>
                    <td colspan="1"></td>
                    <td align="center">  <?php echo $_SESSION['email']; ?></td>
                </tr>
                <td colspan="3">
                    <hr/>
                </td>
                <tr>
                    <td colspan="2">Cashier : <?php if (isset($_SESSION['fullname'])) {
                            echo $_SESSION['fullname'];
                        } ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Date : <?php echo date('Y-m-d'); ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">Time : <?php echo date('h:i:sa'); ?></td>
                    <td></td>
                </tr>
                <!-- <tr>
      <td colspan="2">Server : <?php echo $_SESSION['fullname']; ?> </td>
      <td></td>
    </tr>  -->
                <td colspan="3">
                    <hr/>
                </td>
                <tr align="center" style="color:maroon;">
                    <th>QTY</th>
                    <th align="center" colspan="3">ORDERS</th>
                </tr>

                <?php
                if (isset($_SESSION['newCustomer'])) {
                    $invoiceNo = $_SESSION['invoiceNo'];
                    $result = $db->prepare("SELECT *,SUM(xqty) as sumqty FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID WHERE xfinished='0' AND invoiceNo='$invoiceNo'   GROUP by menuID,stat ORDER BY p.id DESC");
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
                    <?php }
                } ?>
                <td colspan="3">
                    <hr/>
                </td>

                <tr>
                    <td colspan="2">Sub Total :
                    <td><?php echo $_SESSION['totalbills']; ?></td>
                    </td></tr>
                <tr>
                    <td colspan="2">VAT 12% :</td>
                    <td><?php echo $_SESSION['vat12'] = number_format(($_SESSION['totalbills'] / 1.12) * 0.12, 2); ?></td>
                </tr>
                <tr>

                    <td colspan="2">VAT Sales :</td>
                    <td><?php echo $_SESSION['vatsale'] = number_format($_SESSION['totalbills'] / 1.12, 2); ?></td>
                </tr>

                <td colspan="2">VAT Exempt : <?php echo $_GET['cusType']; ?>
                <td><?php echo $_SESSION['vatdis'] = number_format($_SESSION['totalbills'] / 1.12 * .20, 2); ?></td>
                </td></tr>
                <td colspan="3">
                    <hr/>
                </td>
                <tr>
                    <td colspan="2">Service Charge :
                    <td>25</td>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Total :
                    <td><?php echo $_SESSION['total'] = number_format($_SESSION['totalbills'] - $_SESSION['vat12'] - $_SESSION['vatdis'] + 25, 2); ?></td>
                    </td>
                </tr>

                </tr>
            </table>
            <br/>
            <div align="center">
                <form action="modal/modals.php?do=savePayment" method="post">
                    <input name="cusType" type="hidden" value="<?php echo $_GET['cusType']; ?>" required></br>
                    <input type="hidden" name="cashCat" placeholder="Cash" value="Cash" required>
                    <input type="number" name="cash" step=".01" placeholder="Cash" min="<?php echo $_SESSION['total']; ?>" required>
                    <br/>
                    <input type="submit" name="submit" class="button" value="BILL OUT">
                </form>
                Thank you for dining with us. <br/>
                Please, come again!
            </div>
        </div>
    </div>
    </section>    </div>


<?php include_once('layout/footer.php'); ?>