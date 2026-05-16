<?php 
include_once('../connect.php');  

// ✅ Start session only if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize $do safely (null if not passed)
$do = $_POST['do'] ?? $_GET['do'] ?? null;

// ================= CANCEL =================
if ($do === 'cancel') { 
    if (!empty($_SESSION["cart_array"])) {
        foreach($_SESSION["cart_array"] as $each_menu){  
            $menu_id = $each_menu['menuID']; 
            $qty     = $each_menu['quantity'];
            $q = $db->prepare("UPDATE tbl_menu SET qty=qty+? WHERE id=?");
            $q->execute([$qty, $menu_id]);
        }
    }

    // Clear session values
    unset($_SESSION['cart_array'], $_SESSION['checkoutCartTotal'], $_SESSION['change'], 
          $_SESSION['totalbills'], $_SESSION['newCustomer'], $_SESSION['total'], 
          $_SESSION['invoiceNo'], $_SESSION['cusType'], $_SESSION['disNo'], $_SESSION['disName']);

    $_SESSION['sid'] = '';             
    $_SESSION['sname'] = '';
    $_SESSION['srfid'] = '';
    $_SESSION['srfid_balance'] = '';
    $_SESSION['per_day_balance'] = '';

    echo "<script type='text/javascript'>alert('Cancel successfully');window.location.href='../cart.php';</script>";
    exit;
}

// ================= SAVE PAYMENT =================
if ($do === 'savePayment') { 
    $cartTotal = 0;
    $invoiceNo = $_SESSION['invoiceNo'] ?? '';
    $totalbills = $_SESSION['totalbills'] ?? 0;
    $subtotal = number_format($totalbills / 1.12, 2);
    $salestax = number_format(($totalbills / 1.12) * 0.12, 2);

    $zero = '0'; 
    $cusType = $_POST['cusType'] ?? 'Walk-in';   
    if ($cusType == "Walk-in") {
        $sumDiscount = $zero;
    } else {
        $sumDiscount = $_SESSION['vatdis'] ?? 0; 
        $totalbills = $totalbills - $sumDiscount;
    }

    $cash = $_POST['cash'] ?? 0;
    $xtotal = $totalbills; 
    $change = $cash - $xtotal;

    $disCat  = $cusType;
    $staffid = $_SESSION['userID'] ?? null;  // Get logged-in staff ID
    $dt      = date("Y-m-d");
    $dtNow   = date("Y-m-d H:i:s");

    // ✅ Insert payment with processed_by (staffid)
    $sql = "INSERT INTO tbl_payment(invoiceNo, sumTotal, sumDiscount, xtotal, cash, dt, received, subtotal, salestax, cusType, staffid, processed_by, dtNow) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $q = $db->prepare($sql); 
    $q->execute([$invoiceNo, $subtotal, $sumDiscount, $xtotal, $cash, $dt, '1', $subtotal, $salestax, $disCat, $staffid, $staffid, $dtNow]);

    if (!empty($_SESSION["cart_array"])) {
        foreach($_SESSION["cart_array"] as $each_menu){  
            $menu_id = $each_menu['menuID']; 
            $result = $db->prepare("SELECT * FROM tbl_menu WHERE id=?");
            $result->execute([$menu_id]); 
            while($row = $result->fetch()){ 
                $price = $row['price'];
                $total = $price * $each_menu['quantity']; 
                $cartTotal += $total; 

                // ✅ Insert purchase record
                $sql = "INSERT INTO tbl_purchases(studentID, menuID, invoiceNo, xprice, xqty, xtotal, xfinished) 
                        VALUES (?,?,?,?,?,?,?)";
                $q = $db->prepare($sql); 
                $q->execute([$_SESSION['sid'], $row['id'], $invoiceNo, $price, $each_menu['quantity'], $total, '1']);
                        
                $new_balance = ($_POST['balance'] ?? 0) - $total;
                
                // ✅ Insert transaction with processed_by (staffid)
                $sql = "INSERT INTO tbl_transactions(studentID, transNo, category, trans_amount, new_balance, processed_by, created_at) 
                        VALUES (?,?,?,?,?,?,NOW())";
                $q = $db->prepare($sql);
                $q->execute([$_SESSION['sid'], $invoiceNo, 'OUT', $total, $new_balance, $staffid]);

                // Update Balance
                $sid = $_SESSION['sid'] ?? null;
                $q = $db->prepare("UPDATE tbl_students SET balance_amount=balance_amount-? WHERE id=?");
                $q->execute([$total, $sid]);
            }
        }
    }

    // Clear session after payment
    unset($_SESSION['cart_array'], $_SESSION['checkoutCartTotal'], $_SESSION['change'], 
          $_SESSION['totalbills'], $_SESSION['newCustomer'], $_SESSION['total'], 
          $_SESSION['invoiceNo'], $_SESSION['cusType'], $_SESSION['disNo'], $_SESSION['disName']);

    $_SESSION['sid'] = '';             
    $_SESSION['sname'] = '';
    $_SESSION['srfid'] = '';
    $_SESSION['srfid_balance'] = '';
    $_SESSION['per_day_balance'] = '';

    // Use flash message and redirect without blank screens
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Order successfully. Next Transaction is now open.'];
    header("Location: ../cart.php#cart-section");
    exit;
}
?>