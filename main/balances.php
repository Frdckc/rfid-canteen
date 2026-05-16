<?php 
include_once('layout/head.php'); 

// Ensure session started and user logged in
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['userID'];

// Check if current user is a parent by checking if they have any linked students
$parent_check = $db->prepare("SELECT COUNT(*) as count FROM tbl_parent_student_links WHERE parent_id = ?");
$parent_check->execute([$user_id]);
$parent_data = $parent_check->fetch(PDO::FETCH_ASSOC);
$is_parent = ($parent_data['count'] > 0);

$linked_students = [];
$selected_student = null;

if ($is_parent) {
    // Get all linked students for this parent
    $linked_query = $db->prepare("
        SELECT 
            s.id,
            s.studentNo, 
            s.fname, 
            s.lname,
            s.pic
        FROM tbl_parent_student_links l
        INNER JOIN tbl_students s ON l.student_id = s.id
        WHERE l.parent_id = ?
        ORDER BY s.fname, s.lname
    ");
    $linked_query->execute([$user_id]);
    $linked_students = $linked_query->fetchAll(PDO::FETCH_ASSOC);
    
    // Determine which student's transactions to show
    if (isset($_GET['student_id'])) {
        $selected_student = $_GET['student_id'];
        // Verify this student is linked to this parent
        $verify = $db->prepare("SELECT student_id FROM tbl_parent_student_links WHERE parent_id = ? AND student_id = ?");
        $verify->execute([$user_id, $selected_student]);
        if (!$verify->fetch()) {
            $selected_student = null; // Invalid selection
        }
    } elseif (count($linked_students) > 0) {
        // Default to first linked student
        $selected_student = $linked_students[0]['id'];
    }
    
    // Use selected student's ID for transactions
    $transaction_user_id = $selected_student ?: null;
} else {
    // Regular student view - show their own transactions
    $transaction_user_id = $user_id;
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money text-green"></i> Balance & Transactions
            <small>View transaction history</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                
                <?php if ($is_parent && count($linked_students) > 0): ?>
                <!-- Student Selector for Parents -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Select Student</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <?php foreach ($linked_students as $student): ?>
                                <div class="col-md-3 col-sm-6">
                                    <a href="balances.php?student_id=<?= $student['id'] ?>" 
                                       class="student-card <?= ($selected_student == $student['id']) ? 'active' : '' ?>"
                                       style="display: block; text-decoration: none; padding: 15px; border: 2px solid <?= ($selected_student == $student['id']) ? '#3c8dbc' : '#ddd' ?>; border-radius: 5px; margin-bottom: 15px; background: <?= ($selected_student == $student['id']) ? '#e3f2fd' : '#fff' ?>; transition: all 0.3s;">
                                        <div class="text-center">
                                            <img src="images/student/<?= htmlspecialchars($student['pic']) ?>" 
                                                 class="img-circle" 
                                                 alt="Student Photo"
                                                 style="width: 80px; height: 80px; object-fit: cover; margin-bottom: 10px;">
                                            <h4 style="margin: 5px 0; color: #333;">
                                                <?= htmlspecialchars($student['fname'] . ' ' . $student['lname']) ?>
                                            </h4>
                                            <p style="margin: 0; color: #777; font-size: 12px;">
                                                <?= htmlspecialchars($student['studentNo']) ?>
                                            </p>
                                            <?php if ($selected_student == $student['id']): ?>
                                                <span class="label label-primary" style="margin-top: 5px;">
                                                    <i class="fa fa-check"></i> Selected
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php elseif ($is_parent && count($linked_students) === 0): ?>
                <!-- No linked students message -->
                <div class="box box-warning">
                    <div class="box-body text-center" style="padding: 40px;">
                        <i class="fa fa-info-circle" style="font-size: 48px; color: #f39c12; margin-bottom: 15px;"></i>
                        <h4>No Linked Students</h4>
                        <p>You don't have any linked students yet.</p>
                        <a href="link-student-account.php" class="btn btn-primary">
                            <i class="fa fa-link"></i> Link a Student Now
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ((!$is_parent) || ($is_parent && $selected_student)): ?>
                <div class="box box-primary">
                    
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-history"></i> Transaction Records</h3>
                    </div>

                    <div class="box-body">
                        <!-- Date filter form -->
                        <form action="balances.php" method="get" class="form-inline text-center" style="margin-bottom:15px;">
                            <?php if ($is_parent && $selected_student): ?>
                                <input type="hidden" name="student_id" value="<?= $selected_student ?>">
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="d1">From:</label>
                                <input type="date" class="form-control" name="d1" value="<?= htmlspecialchars($_GET['d1'] ?? '') ?>"/> 
                            </div>
                            <div class="form-group" style="margin-left:10px;">
                                <label for="d2">To:</label>
                                <input type="date" class="form-control" name="d2" value="<?= htmlspecialchars($_GET['d2'] ?? '') ?>"/>
                            </div>
                            <button type="submit" class="btn btn-primary" style="margin-left:10px;">
                                <i class="fa fa-search"></i> Show
                            </button>
                        </form>

                        <h3 class="text-center text-red" style="margin-bottom:20px;">Transaction History</h3>

                        <table class="table table-bordered table-striped table-hover">
                            <thead class="bg-light-blue">
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:12%">Transaction#</th>
                                    <th style="width:8%">Category</th>
                                    <th style="width:10%">Amount</th>
                                    <th style="width:15%">Staff</th>
                                    <th style="width:15%">Parent</th>
                                    <th style="width:20%">Information</th>
                                    <th style="width:15%">Date/Time</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($transaction_user_id) {
                                if (isset($_GET['d1'], $_GET['d2'])) {
                                    $d1 = $_GET['d1'];
                                    $d2 = $_GET['d2'];
                                    $d2 = date('Y-m-d', strtotime("+1 day", strtotime($d2)));

                            $sql = "SELECT t.*, SUM(t.trans_amount) AS tt,
                                    CONCAT(u.fname, ' ', u.lname) AS staff_name,
                                    u.username AS staff_username,
                                    CONCAT(parent_student.fname, ' ', parent_student.lname) AS parent_name,
                                    parent_student.username AS parent_username
                                    FROM tbl_transactions t
                                    LEFT JOIN tbl_users u ON u.id = t.processed_by AND u.role != 'Student'
                                    LEFT JOIN tbl_students parent_student ON parent_student.id = t.processed_by
                                    WHERE t.studentID = ? 
                                    AND t.created_at BETWEEN ? AND ? 
                                    GROUP BY t.transNo 
                                    ORDER BY t.id DESC";
                                    $result = $db->prepare($sql);
                                    $result->execute([$transaction_user_id, $d1, $d2]);
                                } else {
                            $sql = "SELECT t.*, SUM(t.trans_amount) AS tt,
                                    CONCAT(u.fname, ' ', u.lname) AS staff_name,
                                    u.username AS staff_username,
                                    CONCAT(parent_student.fname, ' ', parent_student.lname) AS parent_name,
                                    parent_student.username AS parent_username
                                    FROM tbl_transactions t
                                    LEFT JOIN tbl_users u ON u.id = t.processed_by AND u.role != 'Student'
                                    LEFT JOIN tbl_students parent_student ON parent_student.id = t.processed_by
                                    WHERE t.studentID = ? 
                                    GROUP BY t.transNo 
                                    ORDER BY t.id DESC";
                                    $result = $db->prepare($sql);
                                    $result->execute([$transaction_user_id]);
                                }

                                $i = 1;
                                while ($row = $result->fetch()) : ?>
                                    <tr>
                                        <td><?= $i++; ?></td>

                                        <td>
                                            <?php if ($row['category'] === 'OUT'): ?>
                                                <a href="balances.php?invoiceNo=<?= urlencode($row['transNo']); ?><?= $is_parent && $selected_student ? '&student_id=' . $selected_student : '' ?>" 
                                                   class="btn btn-link btn-sm">
                                                    <?= htmlspecialchars($row['transNo']); ?>
                                                </a>
                                            <?php else: ?>
                                                <?= htmlspecialchars($row['transNo']); ?>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if ($row['category'] === 'IN'): ?>
                                                <span class="label label-success">IN</span>
                                            <?php else: ?>
                                                <span class="label label-danger">OUT</span>
                                            <?php endif; ?>
                                        </td>

                                        <td><strong>₱ <?= number_format($row['tt'], 2); ?></strong></td>
                                        
<td>
    <?php if (!empty($row['staff_name'])): ?>
        <i class="fa fa-user text-blue"></i> 
        <strong><?= htmlspecialchars($row['staff_name']); ?></strong>
        <br><small class="text-muted"><?= htmlspecialchars($row['staff_username']); ?></small>
    <?php else: ?>
        <span class="text-muted"><em>No staff assigned</em></span>
    <?php endif; ?>
</td>
<td>
    <?php if (!empty($row['parent_name'])): ?>
        <i class="fa fa-users text-green"></i> 
        <strong><?= htmlspecialchars($row['parent_name']); ?></strong>
        <br><small class="text-muted"><?= htmlspecialchars($row['parent_username']); ?></small>
    <?php else: ?>
        <span class="text-muted"><em>No parent linked</em></span>
    <?php endif; ?>
</td>

                                        <td>
                                            <?php if ($row['category'] === 'IN'): ?>
                                                <?php if (!empty($row['trans_img'])): ?>
                                                    <a target="_blank" href="images/trans/<?= htmlspecialchars($row['trans_img']); ?>">
                                                        <img height="50" width="50" 
                                                             src="images/trans/<?= htmlspecialchars($row['trans_img']); ?>" 
                                                             alt="Transaction Image" class="img-thumbnail">
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($row['trans_stat'] === 'Pending'): ?>
                                                    <span class="label label-warning">Pending</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date("M d, Y h:i A", strtotime($row['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile;
                            } else { ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        No transactions found.
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                        <!-- Invoice details -->
                        <?php if (isset($_GET['invoiceNo'])): ?>
                            <hr/>
                            <?php
                            $invoiceNo = $_GET['invoiceNo'];
                            $stmt = $db->prepare("SELECT p.*, 
                                                  CONCAT(u.fname, ' ', u.lname) AS staff_name,
                                                  u.username AS staff_username
                                                  FROM tbl_payment p
                                                  LEFT JOIN tbl_users u ON u.id = p.processed_by
                                                  WHERE p.invoiceNo = ?");
                            $stmt->execute([$invoiceNo]);
                            if ($rowss = $stmt->fetch()):
                            ?>
                                <div class="invoice-summary" style="background: #f9f9f9; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                                    <h4 style="margin-top: 0; color: #333;"><i class="fa fa-file-text-o"></i> Invoice Details</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p>
                                                <strong style="color:red">Invoice No:</strong> <?= htmlspecialchars($invoiceNo); ?><br>
                                                <strong>Date/Time:</strong> <?= date("M d, Y h:i A", strtotime($rowss['dt'])); ?>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <?php if (!empty($rowss['staff_name'])): ?>
                                                <p>
                                                    <strong><i class="fa fa-user text-blue"></i> Processed By:</strong><br>
                                                    <span style="font-size: 16px; color: #3c8dbc;">
                                                        <?= htmlspecialchars($rowss['staff_name']); ?>
                                                    </span><br>
                                                    <small class="text-muted">
                                                        <i class="fa fa-envelope-o"></i> <?= htmlspecialchars($rowss['staff_username']); ?>
                                                    </small>
                                                </p>
                                            <?php else: ?>
                                                <p>
                                                    <strong>Processed By:</strong><br>
                                                    <span class="text-muted"><em>No staff assigned</em></span>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <h4><i class="fa fa-shopping-cart"></i> Order Details</h4>
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light-gray">
                                    <tr>
                                        <th>Orders</th>
                                        <th>Amount</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $db->prepare("SELECT m.name, p.xprice, p.xqty, m.price 
                                                      FROM tbl_purchases p 
                                                      INNER JOIN tbl_menu m ON m.id = p.menuID 
                                                      WHERE p.xfinished = '1' 
                                                        AND p.invoiceNo = ? 
                                                      ORDER BY p.id DESC");
                                $stmt->execute([$invoiceNo]);

                                $xtotal = 0;
                                while ($row = $stmt->fetch()):
                                    $total = $row['price'] * $row['xqty'];
                                    $xtotal += $total;
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']); ?></td>
                                        <td>₱ <?= number_format($row['xprice'], 2); ?></td>
                                        <td><?= (int)$row['xqty']; ?></td>
                                        <td><strong>₱ <?= number_format($total, 2); ?></strong></td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-light-blue">
                                        <th colspan="3" class="text-right">Grand Total</th>
                                        <th>₱ <?= number_format($xtotal, 2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include_once('layout/footer.php'); ?>