<?php include_once('layout/head.php'); ?>

<?php 
if (!isset($_SESSION['userID']) || !isset($_GET['student_no'])) {
    echo "<script>alert('Invalid access');window.location='view-linked-student.php';</script>";
    exit;
}

$parent_id = $_SESSION['userID'];
$student_no = $_GET['student_no'];

$verify_link = $db->prepare("
    SELECT s.id, s.studentNo, s.fname, s.lname, s.balance_amount, s.per_day_balance, s.pic
    FROM tbl_parent_student_links l
    INNER JOIN tbl_students s ON l.student_id = s.id
    WHERE l.parent_id = ? AND s.studentNo = ?
");
$verify_link->execute([$parent_id, $student_no]);

if (!$student_data = $verify_link->fetch()) {
    echo "<script>alert('Student not linked to your account');window.location='view-linked-student.php';</script>";
    exit;
}

$student_id = $student_data['id'];

if (isset($_GET['balancelimit'])) {
    $balancelimit = $_GET['balancelimit'];
    $sql = "UPDATE tbl_students SET per_day_balance=? WHERE id=?";
    $q = $db->prepare($sql);
    $q->execute([$balancelimit, $student_id]);

    echo "<script>alert('Balance Limit Successfully Updated');window.location.href='parent-balance-manage.php?student_no=".$student_no."';</script>";
}

if (isset($_POST['addbalance'])) {
    $temp = $_FILES["pic"]["tmp_name"];
    $pic  = $_FILES["pic"]["name"];
    move_uploaded_file($temp, "images/trans/" . $pic);

    $addbalance = $_POST['addbalance'];
    $transNo    = date('YmdHis');

    $sql = "INSERT INTO tbl_transactions(studentID,trans_amount,trans_img,trans_stat,category,transNo,processed_by)
            VALUES (?,?,?,?,?,?,?)";
    $q = $db->prepare($sql);
    $q->execute([$student_id, $addbalance, $pic, 'Pending', 'IN', $transNo, $parent_id]);

    echo "<script>alert('Balance Request Successfully Added');window.location.href='parent-balance-manage.php?student_no=".$student_no."';</script>";
}
?>

<style>
    /* Mobile First Responsive */
    .content-wrapper {
        padding: 15px;
    }
    
    .content-header h1 {
        font-size: 20px;
        margin-bottom: 15px;
    }
    
    .content-header h1 small {
        display: block;
        margin-top: 5px;
        font-size: 14px;
    }
    
    .header-buttons {
        margin-top: 10px;
    }
    
    .header-buttons .btn {
        margin-bottom: 8px;
        width: 100%;
    }
    
    .box {
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .student-info-card .img-circle {
        width: 120px;
        height: 120px;
        object-fit: cover;
        margin: 15px auto;
        border: 3px solid #3c8dbc;
    }
    
    .balance-display h2 {
        font-size: 36px;
        margin: 15px 0;
    }
    
    /* Desktop Responsive */
    @media (min-width: 768px) {
        .content-header h1 {
            font-size: 24px;
        }
        
        .header-buttons {
            float: right;
            margin-top: -50px;
        }
        
        .header-buttons .btn {
            width: auto;
            margin-left: 5px;
            margin-bottom: 0;
        }
        
        .student-info-card .img-circle {
            width: 150px;
            height: 150px;
        }
        
        .balance-display h2 {
            font-size: 48px;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money text-green"></i> Manage Balance for <?php echo htmlspecialchars($student_data['fname'] . ' ' . $student_data['lname']); ?>
            <small>Student No: <?php echo htmlspecialchars($student_data['studentNo']); ?></small>
        </h1>
        <div class="header-buttons">
            <a href="view-linked-student.php" class="btn btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Back to Linked Students
            </a>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewQR">
                <i class="fa fa-qrcode"></i> Top Up QR Code
            </button>
        </div>
    </section>

    <section class="content">
        <!-- Student Info -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4">
                <div class="box box-info student-info-card">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user"></i> Student Info</h3>
                    </div>
                    <div class="box-body text-center">
                        <img src="images/student/<?php echo htmlspecialchars($student_data['pic']); ?>" 
                             class="img-circle" 
                             alt="Student Photo">
                        <h4 class="text-blue"><?php echo htmlspecialchars($student_data['fname'] . ' ' . $student_data['lname']); ?></h4>
                        <p>Student No: <strong><?php echo htmlspecialchars($student_data['studentNo']); ?></strong></p>
                    </div>
                </div>
            </div>

            <!-- Current Balance & Update Limit (Side by Side on Desktop) -->
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="row">
                    <!-- Current Balance -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-credit-card"></i> Current Balance</h3>
                            </div>
                            <div class="box-body text-center balance-display">
                                <h2 class="text-blue">₱ <?php echo number_format($student_data['balance_amount'], 2); ?></h2>
                                <p class="text-red">Daily Balance Limit: <strong>₱ <?php echo number_format($student_data['per_day_balance'], 2); ?></strong></p>
                            </div>
                        </div>
                    </div>

                    <!-- Update Balance Limit -->
                    <div class="col-xs-12 col-sm-6">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-sliders"></i> Update Balance Limit</h3>
                            </div>
                            <form action="parent-balance-manage.php" method="get">
                                <input type="hidden" name="student_no" value="<?php echo htmlspecialchars($student_no); ?>">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="balancelimit">Balance Limit</label>
                                        <input type="number" 
                                               class="form-control" 
                                               name="balancelimit" 
                                               id="balancelimit"
                                               value="<?php echo $student_data['per_day_balance']; ?>" 
                                               min="0"
                                               step="0.01"
                                               required>
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
                </div>
            </div>
        </div>

        <!-- Add Balance (Full Width) -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-circle"></i> Add Balance</h3>
                    </div>
                    <form action="parent-balance-manage.php?student_no=<?php echo htmlspecialchars($student_no); ?>" method="POST" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="pic">Upload Proof (Receipt / Screenshot)</label>
                                        <input type="file" 
                                               class="form-control" 
                                               name="pic" 
                                               id="pic"
                                               accept="image/*" 
                                               required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label for="addbalance">Amount</label>
                                        <input type="number" 
                                               class="form-control" 
                                               name="addbalance" 
                                               id="addbalance"
                                               placeholder="Enter Amount" 
                                               min="1"
                                               step="0.01"
                                               required>
                                    </div>
                                </div>
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
        </div>
    </section>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="viewQR" tabindex="-1" role="dialog" aria-labelledby="viewQRLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="viewQRLabel">
                    <i class="fa fa-qrcode"></i> Top Up QR Code
                </h4>
            </div>
            <div class="modal-body text-center">
                <?php
                $qr = $db->prepare("SELECT filename FROM system_qr ORDER BY id DESC LIMIT 1");
                $qr->execute();

                if ($row = $qr->fetch()) {
                    echo '<img src="uploads/qr/'.$row['filename'].'" class="img-responsive" style="margin:0 auto;max-width:250px;" alt="QR Code">';
                    echo '<p class="text-muted" style="margin-top:15px;"><small><i class="fa fa-info-circle"></i> Scan this QR code to make payment</small></p>';
                } else {
                    echo '<div class="alert alert-warning" style="margin:0;">';
                    echo '<i class="fa fa-exclamation-triangle"></i> No QR Code uploaded yet.';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">
                    <i class="fa fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<?php include_once('layout/footer.php'); ?>