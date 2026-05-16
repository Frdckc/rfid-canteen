<?php 
include_once('connect.php'); 
include_once('layout/head.php'); 

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('Please login first');window.location='login.php';</script>";
    exit;
}

$parent_id = $_SESSION['userID'];

// Handle unlink request
if (isset($_POST['unlink_student'])) {
    $student_no = $_POST['student_no'];
    
    // Get student ID from student number
    $get_student = $db->prepare("SELECT id FROM tbl_students WHERE studentNo = ?");
    $get_student->execute([$student_no]);
    $student = $get_student->fetch(PDO::FETCH_ASSOC);
    
    if ($student) {
        $delete_link = $db->prepare("DELETE FROM tbl_parent_student_links WHERE parent_id = ? AND student_id = ?");
        if ($delete_link->execute([$parent_id, $student['id']])) {
            echo "<script>alert('Student unlinked successfully');window.location='view-linked-student.php';</script>";
        } else {
            echo "<script>alert('Failed to unlink student');</script>";
        }
    }
}

// Fetch linked students
$query = $db->prepare("
    SELECT 
        s.studentNo, 
        s.fname, 
        s.lname, 
        s.email, 
        s.contact, 
        s.pic, 
        l.linked_at
    FROM tbl_parent_student_links l
    INNER JOIN tbl_students s ON l.student_id = s.id
    WHERE l.parent_id = ?
    ORDER BY l.linked_at DESC
");
$query->execute([$parent_id]);
$linked_students = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>View Linked Students</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Linked Students List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Student No</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Date Linked</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($linked_students) > 0): ?>
                                    <?php foreach ($linked_students as $row): ?>
                                        <tr>
                                            <td>
                                                <img src="images/student/<?php echo htmlspecialchars($row['pic']); ?>" 
                                                     width="50" height="50" 
                                                     class="img-circle" 
                                                     alt="student photo">
                                            </td>
                                            <td><?php echo htmlspecialchars($row['studentNo']); ?></td>
                                            <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                            <td><?php echo date('M d, Y g:i A', strtotime($row['linked_at'])); ?></td>
                                            <td>
                                                <a href="parent-balance-manage.php?student_no=<?php echo htmlspecialchars($row['studentNo']); ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="fa fa-money"></i> Manage Balance
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="confirmUnlink('<?php echo htmlspecialchars($row['studentNo']); ?>', '<?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?>')">
                                                    <i class="fa fa-unlink"></i> Unlink
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">
                                            No linked students found.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-center">
                    <a href="link-student-account.php" class="btn btn-primary">
                        <i class="fa fa-link"></i> Go to Link Student Page
                    </a>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- Unlink Confirmation Form -->
<form id="unlinkForm" method="POST" style="display: none;">
    <input type="hidden" name="unlink_student" value="1">
    <input type="hidden" name="student_no" id="unlinkStudentNo">
</form>

<script>
function confirmUnlink(studentNo, studentName) {
    if (confirm('Are you sure you want to unlink ' + studentName + '?\n\nThis will remove your access to manage their balance and view their information.')) {
        document.getElementById('unlinkStudentNo').value = studentNo;
        document.getElementById('unlinkForm').submit();
    }
}
</script>

<?php include_once('layout/footer.php'); ?>