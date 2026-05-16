<?php include_once('layout/head.php'); ?>
<?php
if(!isset($_SESSION['sid']))   $_SESSION['sid'] = null;
if(!isset($_SESSION['sname'])) $_SESSION['sname'] = '';
if(!isset($_SESSION['srfid'])) $_SESSION['srfid'] = '';
$bal = 0;
$perdaybal = 0;
$totalperday = 0;

// Fetch student details if logged in
if(isset($_SESSION['sid'])){
    $studid= $_SESSION['sid'];
    $dtNow = date('Y-m-d'); // current date

    // total spent per day
    $result = $db->prepare("SELECT SUM(trans_amount) AS s 
                            FROM tbl_transactions 
                            WHERE studentID=? AND category='OUT' AND created_at LIKE ?");
    $result->execute([$studid, "%$dtNow%"]);
    if($row = $result->fetch()){ $totalperday = $row['s']; }

    // student balance info
    $result = $db->prepare("SELECT * FROM tbl_students WHERE id=?");
    $result->execute([$studid]);
    if($row = $result->fetch()){
        $_SESSION['srfid_balance']=$bal=$row['balance_amount'];
        $perdaybal=$row['per_day_balance'];
        $_SESSION['daybal'] = $perdaybal - $totalperday;
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1 style="color:green;">Customer Mini Cart</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <!-- Customer Details -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Customer Details</h3>
                    </div>
                    <div class="box-body">
                        <p><strong>Name:</strong> <?= $_SESSION['sname']; ?></p>
                        <p><strong>Balance:</strong> <?= number_format($bal,2); ?></p>
                        <p><strong>Per Day Balance:</strong> <?= number_format($perdaybal,2); ?></p>
                        <p><strong>Balance Used Today:</strong> <?= number_format($totalperday,2); ?></p>
                        <p><strong>Remaining Day Balance:</strong> <?= $_SESSION['daybal'] ?? 0; ?></p>
                    </div>
                </div>

                <!-- Cart Table -->
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Your Cart</h3>
                    </div>
                    <div class="box-body">
                        <?php
                        if(!isset($_SESSION['cart_array']) || count($_SESSION['cart_array']) < 1){
                            echo "<h3 align='center'>Your cart is empty</h3>";
                            $_SESSION['totalbills'] = 0;
                        } else {
                            $cartTotal = 0;
                            $orNo = 0;
                        ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Item Name</th>
                                    <th>Price</th>
                                    <th>QTY</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($_SESSION['cart_array'] as $each_menu){
                                    $menu_id = $each_menu['menuID'];
                                    $qty     = $each_menu['quantity'];

                                    $result = $db->prepare("SELECT * FROM tbl_menu WHERE id=?");
                                    $result->execute([$menu_id]);
                                    if($row = $result->fetch()){
                                        $price = $row['price'];
                                        $total = $price * $qty;
                                        $cartTotal += $total;
                                        $orNo++;
                                ?>
                                <tr>
                                    <td><?= $orNo; ?></td>
                                    <td><img src="images/menu/<?= $row['imgUrl']; ?>" width="60" height="60"></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= number_format($price, 2); ?></td>
                                    <td><?= $qty; ?></td>
                                    <td><?= number_format($total, 2); ?></td>
                                </tr>
                                <?php
                                    }
                                }
                                $_SESSION['checkoutCartTotal'] = $cartTotal;
                                $_SESSION['totalbills'] = $cartTotal;
                                ?>
                            </tbody>
                        </table>

                        <h3 align="right">Total: <strong style="color:red;"><?= number_format($cartTotal, 2); ?></strong></h3>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?php include_once('layout/footer.php'); ?>
