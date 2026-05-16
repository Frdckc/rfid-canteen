<?php
include_once('layout/head.php');

if (isset($_GET['c'])) {
    $tid = $_GET['tid'];

    if ($_GET['c'] == 'approved') {
        $sid = $_GET['sid'];

        $result = $db->prepare("SELECT * FROM tbl_students WHERE id=?");
        $result->execute([$sid]);
        if ($row = $result->fetch()) {
            $old = $row['balance_amount'];
            $newBalance = $old + $_GET['add'];
        }

        $sql = "UPDATE tbl_students SET balance_amount=? WHERE id=?";
        $q = $db->prepare($sql);
        $q->execute([$newBalance, $sid]);

        $sql = "UPDATE tbl_transactions SET trans_stat='Approved', new_balance=? WHERE id=?";
        $q = $db->prepare($sql);
        $q->execute([$newBalance, $tid]);

        $notif_insert = $db->prepare("INSERT INTO tbl_notifications (studentID, title, message, type) VALUES (?, ?, ?, ?)");
        $notif_insert->execute([
            $sid,
            'Payment Approved',
            'Your payment of ₱' . number_format($_GET['add'], 2) . ' has been approved. New balance: ₱' . number_format($newBalance, 2),
            'success'
        ]);
    } else {
        $sql = "UPDATE tbl_transactions SET trans_stat='Declined', decline_reason=? WHERE id=?";
        $q = $db->prepare($sql);
        $q->execute([$_GET['reason'], $tid]);

        $trans_data = $db->prepare("SELECT studentID, trans_amount FROM tbl_transactions WHERE id=?");
        $trans_data->execute([$tid]);
        $trans_info = $trans_data->fetch();

        $notif_insert = $db->prepare("INSERT INTO tbl_notifications (studentID, title, message, type) VALUES (?, ?, ?, ?)");
        $notif_insert->execute([
            $trans_info['studentID'],
            'Payment Declined',
            'Your payment of ₱' . number_format($trans_info['trans_amount'], 2) . ' has been declined. Reason: ' . $_GET['reason'],
            'danger'
        ]);
    }

    $message = "Successfully Updated Status to " . strtoupper($_GET['c']);
    echo "<script type='text/javascript'>alert('$message');window.location.href='balance.php';</script>";
}
?>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <h1><i class="fa fa-credit-card text-green"></i> Student RFID Balance</h1>

        <!-- ✅ Button for Admin: Upload QR -->
        <div class="text-right">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#uploadQR">
                <i class="fa fa-upload"></i> Upload QR Code
            </button>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">


                    <!-- Alert Messages -->
                    <div class="box-header">
                        <?php if (isset($_GET['r'])): ?>
                            <?php
                            $r = $_GET['r'];
                            if ($r == 'added') {
                                $classs = 'success';
                            } else if ($r == 'updated') {
                                $classs = 'info';
                            } else if ($r == 'deleted') {
                                $classs = 'danger';
                            } else {
                                $classs = 'hide';
                            }
                            ?>
                            <div class="alert alert-<?= $classs ?> alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Successfully <?= ucfirst($r); ?>!</strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Students Table -->
                    <div class="box-body">
    
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Student ID</th>
                                    <th>RFID No</th>
                                    <th>Lastname</th>
                                    <th>Firstname</th>
                                    <th>Balance Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $db->prepare("SELECT * FROM tbl_students ORDER BY id DESC");
                                $result->execute();
                                for ($i = 1; $row = $result->fetch(); $i++) {
                                    $id = $row['id']; ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td><img height="50" width="50" class="img-circle" src="images/student/<?= $row['pic']; ?>"></td>
                                        <td><?= $row['studentNo']; ?></td>
                                        <td><?= $row['rfidno']; ?></td>
                                        <td><?= $row['lname']; ?></td>
                                        <td><?= $row['fname']; ?></td>
                                        <td><span class="label label-success">₱ <?= number_format($row['balance_amount'], 2); ?></span></td>
                                        <td>
                                            <a href="#addBalance<?= $id; ?>" class="btn btn-xs btn-success" data-toggle="modal">
                                                <i class="fa fa-plus"></i> Add Balance
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <hr/>
                        <!-- Pending Transactions -->
                        <h3 class="text-red"><i class="fa fa-clock-o"></i> Pending Transactions</h3>
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-red">
                                <tr>
                                    <th>#</th>
                                    <th>Transaction#</th>
                                    <th>Category</th>
                                    <th>Amount</th>
                                    <th>Information</th>
                                    <th>Date/Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $db->prepare("SELECT *, trans_amount FROM tbl_transactions WHERE trans_stat='Pending' ORDER BY id DESC");
                                $result->execute();
                                for ($i = 1; $row = $result->fetch(); $i++) {
                                    $tid = $row['id']; 
                                    $sid = $row['studentID'];
                                    $amt = $row['trans_amount']; ?>
                                    <tr>
                                        <td><?= $i; ?></td>
                                        <td>
                                            <?php if ($row['category'] == 'OUT') { ?>
                                                <a href="balances.php?invoiceNo=<?= $row['transNo']; ?>"><?= $row['transNo']; ?></a>
                                            <?php } else { ?>
                                                <?= $row['transNo']; ?>
                                            <?php } ?>
                                        </td>
                                        <td><?= $row['category']; ?></td>
                                        <td><span class="label label-info">₱ <?= number_format($amt, 2); ?></span></td>
                                        <td>
                                            <?php if ($row['category'] == 'IN' && !empty($row['trans_img'])) { ?>
                                                <a target="_blank" href="images/trans/<?= $row['trans_img']; ?>">
                                                    <img height="50" width="50" class="img-thumbnail" src="images/trans/<?= $row['trans_img']; ?>">
                                                </a>
                                            <?php } ?>
                                        </td>
                                        <td><?= $row['created_at']; ?></td>
                                        <td>
                                            <a href="balance.php?c=approved&tid=<?= $tid; ?>&sid=<?= $sid; ?>&add=<?= $amt; ?>"
                                               class="btn btn-xs btn-success"
                                               onclick="return confirm('Are you sure you want to approve this transaction?')">
                                                <i class="fa fa-check"></i> Approve
                                            </a>
                                            <a href="#declineModal<?= $tid; ?>"
                                               class="btn btn-xs btn-danger"
                                               data-toggle="modal">
                                                <i class="fa fa-times"></i> Decline
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div> <!-- /.box-body -->
                </div> <!-- /.box -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </section>
</div>

<div class="modal fade" id="declineModal<?= $tid; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form method="GET" action="balance.php">
                    <input type="hidden" name="c" value="declined">
                    <input type="hidden" name="tid" value="<?= $tid; ?>">
                    <div class="modal-header bg-danger">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-times"></i> Decline Transaction</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Reason for Declining:</label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason for declining this transaction" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-check"></i> Confirm Decline</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Add Balance Modals -->
<?php
$result = $db->prepare("SELECT * FROM tbl_students ORDER BY id DESC");
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $id = $row['id']; ?>
    <div class="modal fade" id="addBalance<?= $id; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form class="form-horizontal" action="modal/student.php?do=addBalance&id=<?= $id; ?>" method="post">
                    <div class="modal-header bg-primary">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Balance</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Current Balance</label>
                            <div class="col-sm-8">
                                <input value="<?= $row['balance_amount']; ?>" type="number" class="form-control" name="old" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-4 control-label">Add Amount</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" name="addBalance" placeholder="Enter Amount" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ✅ Upload QR Modal (Admin Side) -->
<div class="modal fade" id="uploadQR" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <form id="uploadQRForm" method="POST" action="upload_qr.php" enctype="multipart/form-data">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-upload"></i> Upload QR Code</h4>
                </div>
                <div class="modal-body">
                    <div id="uploadQRAlert" class="alert" style="display:none;"></div>
                    <div class="form-group">
                        <label>Select QR Code Image:</label>
                        <input type="file" name="qr_file" id="qr_file" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var isSubmitting = false; // Flag to prevent multiple submissions
    
    // Remove any existing handlers first to prevent multiple submissions
    $('#uploadQRForm').off('submit').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        var $alertBox = $('#uploadQRAlert');

        // Prevent multiple simultaneous submissions
        if (isSubmitting) {
            return false;
        }
        
        isSubmitting = true;
        
        // Disable submit button to prevent multiple clicks
        var $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: 'upload_qr.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                isSubmitting = false; // Reset flag
                $alertBox
                    .removeClass('alert-success alert-danger')
                    .addClass(response.success ? 'alert-success' : 'alert-danger')
                    .text(response.message)
                    .show();

                if (response.success) {
                    $('#uploadQRForm')[0].reset(); // Reset the form
                }

                // Re-enable submit button
                $submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Upload');
            },
            error: function(xhr, status, error) {
                isSubmitting = false; // Reset flag
                $alertBox
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .text('An error occurred. Please try again.')
                    .show();
                console.error('Error:', error);
                // Re-enable submit button
                $submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Upload');
            }
        });
        
        return false; // Additional prevention
    });
});
</script>


<?php } ?>

<?php include_once('layout/footer.php'); ?>
