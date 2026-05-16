<?php
// Handle RFID and errors before any HTML output to avoid blank screens
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('connect.php');

if(!isset($_SESSION['sid']))   $_SESSION['sid'] = null;
if(!isset($_SESSION['sname'])) $_SESSION['sname'] = '';
if(!isset($_SESSION['srfid'])) $_SESSION['srfid'] = '';

$bal = 0;              // default overall balance
$perdaybal = 0;        // default per day balance
$totalperday = 0;      // default used today

// Helper redirect with optional flash and anchor
function cart_redirect($anchor = '', $flash = null) {
    if ($flash) {
        $_SESSION['flash'] = $flash;
    }
    $hash = $anchor ? '#'.preg_replace('/[^a-zA-Z0-9_-]/','', $anchor) : '';
    header("Location: cart.php{$hash}");
    exit();
}

// Handle RFID early and redirect back with flash instead of rendering alerts
if (isset($_GET['rfid'])) {
    $rfid = $_GET['rfid'];
    $result = $db->prepare("SELECT *,CONCAT(fname,' ',lname) name FROM tbl_students WHERE rfidno=? ORDER BY id DESC");
    $result->execute([$rfid]);
    if ($row = $result->fetch()) {
        $_SESSION['sid'] = $row['id'];
        $_SESSION['sname'] = $row['name']; 
        cart_redirect('cart-section', ['type' => 'success', 'msg' => 'Student found']);
    } else {
        cart_redirect('cart-section', ['type' => 'danger', 'msg' => 'Student not found']);
    }
}

// Early handle cart actions before any output (prevents header already sent)
if (!empty($_GET['menuid'])) {
    $pid = $_GET['menuid'];
    $anchor = $_GET['anchor'] ?? 'cart-row-'.$pid;
    $wasFound = false;
    $i = 0;

    $result = $db->prepare("SELECT qty FROM tbl_menu WHERE id=?");
    $result->execute([$pid]);
    if ($row = $result->fetch()) {
        if ($row['qty'] == '0') {
            cart_redirect($anchor, ['type'=>'danger','msg'=>'Not available.']);
        } else {
            $menuQTY = $row['qty'] - 1;

            // Detect per day limit
            $amt = $_GET['amt'] ?? 0;
            $day = ($_SESSION['totalbills'] ?? 0) + ($amt * 1);
            if( ($_SESSION['daybal'] ?? 0) < $day){
                cart_redirect($anchor, ['type'=>'warning','msg'=>'Daily limit reached']);
            }

            $q = $db->prepare("UPDATE tbl_menu SET  qty=?  WHERE id=?");
            $q->execute([$menuQTY, $pid]);
        }
    }

    if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
        $_SESSION["cart_array"] = array(0 => array("menuID" => $pid, "quantity" => 1));
    } else { 
        foreach ($_SESSION["cart_array"] as $each_menu) {
            $i++;
            foreach ($each_menu as $key => $value) { 
                if ($key == "menuID" && $value == $pid) {
                    array_splice($_SESSION["cart_array"], $i - 1, 1, array(array("menuID" => $pid,
                        "quantity" => $each_menu['quantity'] + 1)));
                    $wasFound = true;
                }
            }
        }
        if ($wasFound == false) {
            array_push($_SESSION["cart_array"], array("menuID" => $pid, "quantity" => 1));
        }
    }
    cart_redirect($anchor, ['type'=>'success','msg'=>'Added to cart']);
}

if (!empty($_GET['menusid'])) {
    $pid = $_GET['menusid'];
    $anchor = $_GET['anchor'] ?? 'cart-row-'.$pid;
    $wasFound = false;
    $i = 0;

    $result = $db->prepare("SELECT qty FROM tbl_menu WHERE id=?");
    $result->execute([$pid]);
    if ($row = $result->fetch()) {
        $menuQTY = $row['qty'] + 1;
        $q = $db->prepare("UPDATE tbl_menu SET  qty=?  WHERE id=?");
        $q->execute([$menuQTY, $pid]);
    }

    if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
        $_SESSION["cart_array"] = array(0 => array("menuID" => $pid, "quantity" => 1));
    } else {
       foreach ($_SESSION["cart_array"] as $each_menu) {
            $i++;
            foreach ($each_menu as $key => $value) { 
                if ($key == "menuID" && $value == $pid) {
                    array_splice($_SESSION["cart_array"], $i - 1, 1, array(array("menuID" => $pid,
                        "quantity" => $each_menu['quantity'] - 1)));
                    $wasFound = true;
                }
            }
        }
        if ($wasFound == false) {
            array_push($_SESSION["cart_array"], array("menuID" => $pid, "quantity" => 1));
        }
    }
    cart_redirect($anchor, ['type'=>'success','msg'=>'Updated cart']);
}

// Initialize invoice number
if(!isset($_SESSION['invoiceNo'])){
    $_SESSION['invoiceNo'] = date('YmdHis');
}

// Only now include layout that outputs HTML
include_once('layout/head.php');
?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 style="color:red;">
                SCAN RFID :
                <form action="" method="get">
                    <input id="rfid-input" placeholder="Student Input RFID" name="rfid" autofocus="" required>
                </form>
            </h1>
            <?php if (!empty($_SESSION['flash'])): ?>
                <?php
                  $f = $_SESSION['flash']; unset($_SESSION['flash']);
                  $cls = 'info';
                  if ($f['type'] === 'success') $cls = 'success';
                  else if ($f['type'] === 'warning') $cls = 'warning';
                  else if ($f['type'] === 'danger') $cls = 'danger';
                  $msg = htmlspecialchars($f['msg']);
                ?>
                <div id="flashBox" data-type="<?= $f['type'] ?? ''; ?>" class="alert alert-<?= $cls; ?>" style="max-width:400px;">
                    <?= $msg; ?>
                </div>
                <script>
                  (function() {
                    var input = document.getElementById('rfid-input');
                    if (input) {
                      input.focus();
                      input.scrollIntoView({behavior: 'smooth', block: 'center'});
                    }
                  })();
                </script>
            <?php endif; ?>
        </section>

        <?php
        if(isset($_SESSION['sid'])){
                  $studid= $_SESSION['sid']; 
               ($dtNow);
              $_SESSION['daybal']=0;
            $result = $db->prepare("SELECT SUM(trans_amount)s from tbl_transactions WHERE studentID= '$studid' AND category='OUT' AND created_at LIKE '%$dtNow%' ");
            $result->execute(); 
            if($row = $result->fetch()) { 
                $totalperday=$row['s'];  
            }

              $result = $db->prepare("SELECT * from tbl_students WHERE id= '$studid'");
                $result->execute(); 
               if($row = $result->fetch()) { 
                   $_SESSION['srfid_balance']=$bal=$row['balance_amount'];
                   $perdaybal=$row['per_day_balance'];
                   $_SESSION['daybal'] = $perdaybal-$totalperday;
                }
        }

        ?>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <!--                            <a mycart="" data-toggle="modal" data-target="#addDis" type="submit" class="btn btn-primary pull-right btn-m "><i class="fa fa-plus"> </i>-->
                            <!--                                Discount</a>-->

                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">

                            <?php

                            //Remove
                            if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1) {
                                echo $cartOutput = "<h2 align='center'> Your  cart is empty. </h2>";
                            }
                            //-------------------------------------------------------------------------------------------------
                            if (!empty($_GET['cid']) || isset($_GET['cid'])) {
                                $removeKey = $_GET['cid'];  error_reporting(0);
                                // $mid=$_GET['mid'];
                                // $qty=$_GET['qty'];
                                //  $q = $db->prepare("UPDATE tbl_menu SET  qty=qty+'$qty'  WHERE id='$mid'");
                                // $q->execute(array());
                                
                                
                               
                                    unset($_SESSION["cart_array"]["$removeKey"]);
                                    sort($_SESSION["cart_array"]);
                                    unset($_SESSION['checkoutcartQTY']);
                                    unset($_SESSION["checkoutCartTotal"]);
                                 
                                   echo "<script type='text/javascript'>window.location.href='cart.php';</script>";
                                        exit();
                            }
                            
                            ?>

                            <?php
                            $cartTotal = '0';
                            $x = '0';
                            $orNo = '0'; ?>

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
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <?php
                                        $result = $db->prepare("SELECT * FROM tbl_menu ORDER BY id DESC");
                                        $result->execute();
                                        for ($i = 1;
                                        $row = $result->fetch();
                                        $i++){
                                        $id = $row['id']; ?>
                                        <td> <?=$i; ?></td>
                                        <td><img height="50" width="50" src="images/menu/<?= $row['imgUrl']; ?>"></td>

                                        <td> <?= $row['code']; ?></td>
                                        <td> <?= $row['name']; ?></td>
                                        <td> <?= $row['category']; ?></td>
                                        <td> <?= $row['price']; ?></td>

                                        <td><?=$qty= $row['qty']; ?></td>

                                        <td>
                                    <?php if ($qty>1 && $_SESSION['sname']<>''){ ?>
                                            <form action="modal/pos.php" method="GET">
                                                <input type="hidden" name="menuid" value="<?= $row['id']; ?>">
                                                <input type="hidden" name="amt" value="<?= $row['price']; ?>"  >
                                                <input type="number" name="qty" min="1" class="form-control" max="<?= $row['qty']; ?>" required>
                                                <input type="submit" name="add" value="Order" class="btn btn-warning">
                                            </form>
<?php } ?>
                                            <br/>
                                        </td>
                                    </tr>

                                    <?php } ?>
                                </table>
                                <h2 id="cart-section" align='center' style="color:red;">CART  
                                    <a target="_blank" href="mini-cart.php" class="btn btn-info btn-xs">
                                    <i class="fa fa-shopping-cart"></i> Show
                                    </a>
                                </h2>
                                
                                <table id="" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>QTY</th>
                                        <th>Sub Total</th> 
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <?php if (!isset($_SESSION["cart_array"]) || count($_SESSION["cart_array"]) < 1){
                                            $cartOutput = "<h2 align='center'> Your cart is empty </h2>";
                                             $_SESSION['totalbills']=0;
                                        }else{
                                        $x = 0;
                                    foreach ($_SESSION["cart_array"] as $each_menu){
                                        $menu_id = $each_menu['menuID'];
                                        $result = $db->prepare("select * from tbl_menu where id='$menu_id'");
                                        $result->execute();
                                        for ($i = 0;
                                        $row = $result->fetch();
                                        $i++){
                                        $price = $row['price'];
                                       $qty= $each_menu['quantity'];
                                        $total = $price * $each_menu['quantity'];
                                        
                                        $cartTotal = $cartTotal + $total; ?>
                                        <td id="cart-row-<?= $menu_id; ?>"> <?= $orNo += 1; ?></td>
                                        <td><img height="50" width="50" src="images/menu/<?= $row['imgUrl']; ?>">
                                        </td>
                                        <td><?= $row['name']; ?></td>
                                        <td><?= $p=$row['price']; ?></td>
                                        <td>
                                            
                                            <div id="item-add-3424329723" class="item-add">
                                                <div style="left: -50%;">
                                                     <a title="Remove"  href="cart.php?cid=<?=$x;
                                                    $x = $x + 1; ?>#cart-row-<?= $menu_id; ?>" class="btn btn-danger rounded cart-action" >REMOVE </a><br/>  
                                                    <a href="cart.php?menuid=<?= $row['id'].'&amt='.$p; ?>#cart-row-<?= $menu_id; ?>" title="Add" class="btn btn-xs cart-action">
                                                        <img width="20px" height="20px" src="images/plus.png"/> 
                                                    </a>
<?=$each_menu['quantity']; ?>
                                                    <?php if ($each_menu['quantity'] == '1') { ?>
                                                    <?php } else { ?>
                                                        <a title="Minus" href="cart.php?menusid=<?= $row['id']; ?>#cart-row-<?= $menu_id; ?>" class="btn btn-xs cart-action">
                                                            <img width="20px" height="20px" src="images/minus.png"/></a>
                                                        <!--        fa fa-minus-square    -->
                                                    <?php } ?>

                                                </div>

                                            </div> 
                                            
                                            
                                            <?php  $qty=$each_menu['quantity']; ?>
                                            </td>
                                        <td><?= number_format($total,2); ?></td>
                                       
                                    </tr>
                                    <?php }

                                    }
                                    $_SESSION['checkoutCartTotal'] = $cartTotal;
                                    $_SESSION['totalbills'] = $_SESSION['checkoutCartTotal'];
                                    $_SESSION['checkoutcartQTY'] = $i;
                                    } ?>

                                </table>
                                <form action="modal/modals.php"   method="POST">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th># Item(s) : <?= $orNo; ?></th>
                                            <th> Account Details : <?= $_SESSION['sname']; ?>
                                                ,<br/>
                                                Balance :   <?= number_format($bal,2); ?> <br/>
                                                Per Day  Balance :   <?= number_format($perdaybal,2); ?> <br/>
                                                Balance Used : <?= number_format($totalperday ?? 0, 2); ?> <br/>
                                                Day Balance  : <?= $_SESSION['daybal'] ?? 0; ?>

                                                <?php if (isset($_SESSION['disName'])) { ?>
                                                    <a href="modal/menu.php?do=removeDis" class="btn button-warning">Remove</a>

                                                <?php } ?>

                                                <br/>
                                                <?php $_SESSION['cusType'] = 'Walk-in'; ?>

                                            </th>

                                            <table style="width:100%">
                                                <tbody>
                                                <?php
                                                if (isset($_SESSION['newCustomer'])) {
                                                    $invoiceNo = $_SESSION['invoiceNo'];
                                                    $result = $db->prepare("SELECT *,SUM(xqty) as sumqty FROM tbl_purchases p INNER JOIN tbl_menu m ON m.id=p.menuID WHERE xfinished='0' AND invoiceNo='$invoiceNo'   GROUP by menuID,stat ORDER BY p.id DESC");
                                                    $result->execute();
                                                    $xtotal = '0';
                                                    for ($i = 1; $row = $result->fetch(); $i++) {
                                                         $total = $row['price'] * $row['xqty']; ?>
                                                        <tr>
                                                            <td><?=$row['xqty']; ?></td>
                                                            <td><?=$row['name']; ?></td>
                                                            <td><?=number_format($row['xqty'] * $row['price'], 2); ?></td>
                                                        </tr>
                                                        <?php $xtotal = $xtotal + $total;
                                                        $_SESSION['totalbills'] = $xtotal; ?>
                                                    <?php }
                                                } ?>
                                                <td colspan="3">
                                                    <hr/>
                                                </td>
                                                <?php if (!isset($_SESSION['totalbills'])) {
                                                    $_SESSION['totalbills'] = '0';
                                                } ?>


                                                <tr>
                                                    <td colspan="2">Total :
                                                    <td><?=$_SESSION['total'] = number_format($_SESSION['totalbills'], 2); ?></td>
                                                <tr>
                                                </tbody>
                                            </table>


                                            <br/> 
                                    
     <?php if ( $_SESSION['totalbills'] >0){ ?>
                                            <th>Payment :
                                                <input type="hidden" name="cusType" value="<?php if (isset($_SESSION['cusType'])) {
                                                    echo $_SESSION['cusType'];
                                                } else echo 'Walk-in'; ?>">
                                                <input type="hidden" name="disNo" value="<?php if (isset($_SESSION['disNo'])) {
                                                    echo $_SESSION['disNo'];
                                                } ?>">
                                                <input type="hidden" name="disName" value="<?php if (isset($_SESSION['disName'])) {
                                                    echo $_SESSION['disName'];
                                                } ?>">
                                                
                                                
                                                <input type="hidden" name="" value="<?= $_SESSION['invoiceNo']; ?>" >
                                                <input type="hidden" name="balance" value="<?= $_SESSION['srfid_balance']; ?>" >

                                                <input type="number" name="cash" min="<?= $_SESSION['total']; ?>" max="<?= $_SESSION['srfid_balance']; ?>" value="<?= $_SESSION['total']; ?>" >

                                                 <input type="hidden" name="do" value="savePayment"> 
                                                <input type="submit" class="btn btn-success" name="submit">
                                                <a href="modal/modals.php?do=cancel" onclick="return  confirm('Are you sure ?')"   class="btn btn-danger" name="cancel"> Cancel</a>

                                            </th>
                                            <?php }?>
                                        </tr>
                                        </thead>

                                    </table>
                                </form>
                                
                                
                                
                            </div>

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


    <div class="modal fade" id="addDis" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="exampleModalLabel">Add Discount</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="modal/modals.php" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <input type="hidden" name="do" value="addDiscount">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Type</label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="cusType" placeholder="Code" autofocus required>
                                        <option>Senior</option>
                                        <option>PWD</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">ID No.</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="disNo" placeholder="Code" autofocus required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="disName" placeholder="Name" required>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Restore scroll position if saved
    const saved = sessionStorage.getItem('cartScrollY');
    if (saved) {
      window.scrollTo(0, parseInt(saved, 10));
      sessionStorage.removeItem('cartScrollY');
    } else if (window.location.hash === '#cart-section') {
      const el = document.getElementById('cart-section');
      if (el && typeof el.scrollIntoView === 'function') {
        el.scrollIntoView({ behavior: 'auto', block: 'start' });
      }
    }

    // Save scroll position before navigating cart actions
    document.querySelectorAll('.cart-action').forEach(function (link) {
      link.addEventListener('click', function () {
        sessionStorage.setItem('cartScrollY', window.scrollY);
      });
    });

  });
</script>

<?php include_once('layout/footer.php'); ?>