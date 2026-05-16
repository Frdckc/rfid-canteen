<?php include_once('layout/head.php'); ?>

<?php 
$user_id = $_SESSION['userID'] ?? null;

// ================= Handle Balance Limit Update =================
if (isset($_GET['balancelimit'])) {
    $_SESSION['balance_limit'] = $balancelimit = $_GET['balancelimit'];
    $sql = "UPDATE tbl_students SET per_day_balance=? WHERE id=?";
    $q = $db->prepare($sql);
    $q->execute([$balancelimit, $user_id]);

    echo "<script>alert('Balance Limit Successfully Updated');window.location.href='balance-settings.php';</script>";
}

// ================= Handle Add Balance =================
if (isset($_POST['addbalance'])) {
    $temp = $_FILES["pic"]["tmp_name"];
    $pic  = $_FILES["pic"]["name"];
    move_uploaded_file($temp, "images/trans/" . $pic);

    $addbalance = $_POST['addbalance'];
    $transNo    = date('YmdHis');

    $sql = "INSERT INTO tbl_transactions(studentID,trans_amount,trans_img,trans_stat,category,transNo)
            VALUES (?,?,?,?,?,?)";
    $q = $db->prepare($sql);
    $q->execute([$user_id, $addbalance, $pic, 'Pending', 'IN', $transNo]);

    echo "<script>alert('Balance Request Successfully Added');window.location.href='balance-settings.php';</script>";
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money text-green"></i> Balance Management
            <small>Manage balance and daily limits</small>
        </h1>
        <div class="text-right">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewQR">
            <i class="fa fa-qrcode"></i> Top Up QR Code
            </button>
        </div>
    </section>


    <section class="content">
        <div class="row">

            <!-- BALANCE CARD -->
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-credit-card"></i> Current Balance</h3>
                    </div>
                    <div class="box-body text-center">
                        <?php
                        $result = $db->prepare("SELECT balance_amount, per_day_balance FROM tbl_students WHERE id=?");
                        $result->execute([$user_id]);
                        $bal = 0; $perday = 0;
                        if ($row = $result->fetch()) {
                            $bal    = $row['balance_amount'];
                            $perday = $row['per_day_balance'];
                        }
                        ?>
                        <h2 class="text-blue">₱ <?= number_format($bal, 2); ?></h2>
                        <p class="text-red">Daily Balance Limit: <strong>₱ <?= number_format($perday, 2); ?></strong></p>
                    </div>
                </div>
            </div>

            <!-- BALANCE LIMIT UPDATE -->
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-sliders"></i> Update Balance Limit</h3>
                    </div>
                    <form action="balance-settings.php" method="get">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="balancelimit">Balance Limit</label>
                                <input type="number" class="form-control" name="balancelimit" 
                                       value="<?= $_SESSION['balance_limit'] ?? $perday; ?>" required>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ADD BALANCE REQUEST -->
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-circle"></i> Add Balance</h3>
                    </div>
                    <form action="balance-settings.php" method="POST" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group col-md-6">
                                <label for="pic">Upload Proof (Receipt / Screenshot)</label>
                                <input type="file" class="form-control" name="pic" accept="image/*" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="addbalance">Amount</label>
                                <input type="number" class="form-control" name="addbalance" placeholder="Enter Amount" required>
                            </div>
                        </div>
                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div><!-- /.row -->
    </section>
</div>
<!-- View QR Modal -->
<div class="modal fade" id="viewQR" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-qrcode"></i> Top Up QR Code</h4>
            </div>
            <div class="modal-body text-center">
                <?php
                // include DB connection (path may vary depending on your project structure)
                include_once('connect.php');

                $qr = $db->prepare("SELECT filename FROM system_qr ORDER BY id DESC LIMIT 1");
                $qr->execute();

                if ($row = $qr->fetch()) {
                    echo '<img src="uploads/qr/'.$row['filename'].'" class="img-responsive" style="margin:0 auto;max-width:250px;">';
                } else {
                    echo "<p>No QR Code uploaded yet.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>


<?php include_once('layout/footer.php'); ?>
