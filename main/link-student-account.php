<?php 
include_once('connect.php'); 
include_once('layout/head.php'); 

if (!isset($_SESSION['userID'])) {
    echo "<script>alert('Please login first');window.location='login.php';</script>";
    exit;
}

$parent_id = $_SESSION['userID'];

if (isset($_POST['link_student'])) {
    $student_id = trim($_POST['student_id']);
    
    // Verify student exists and is not already linked
    $check = $db->prepare("SELECT * FROM tbl_parent_student_links WHERE parent_id = ? AND student_id = ?");
    $check->execute([$parent_id, $student_id]);

    if ($check->rowCount() > 0) {
        $msg = "<div class='alert alert-warning'><i class='fa fa-exclamation-triangle'></i> This student is already linked to your account.</div>";
    } else {
        $insert = $db->prepare("INSERT INTO tbl_parent_student_links (parent_id, student_id) VALUES (?, ?)");
        $insert->execute([$parent_id, $student_id]);
        $msg = "<div class='alert alert-success'><i class='fa fa-check-circle'></i> Student linked successfully!</div>";
    }
}

// Get all students EXCEPT:
// 1. Those already linked to current parent
// 2. The currently logged-in user (if they are a student)
$all_students = $db->prepare("
    SELECT s.id, s.studentNo, s.fname, s.lname 
    FROM tbl_students s
    LEFT JOIN tbl_parent_student_links psl ON s.id = psl.student_id AND psl.parent_id = ?
    WHERE psl.student_id IS NULL
    AND s.id != ?
    ORDER BY s.fname, s.lname
");
$all_students->execute([$parent_id, $parent_id]);
$students_list = $all_students->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .content-wrapper {
            padding: 10px;
        }
        
        .box {
            margin-bottom: 15px;
        }
        
        .box-header h3 {
            font-size: 16px;
        }
        
        .content-header h1 {
            font-size: 20px;
        }
        
        .btn {
            margin-bottom: 10px;
            width: 100%;
        }
        
        .pull-right {
            float: none !important;
        }
    }
    
    @media (max-width: 576px) {
        .col-md-8 {
            padding: 0 5px;
        }
        
        .select2-container {
            width: 100% !important;
        }
    }
    
    /* Enhanced select2 styling */
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #d2d6de;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-link"></i> Link Student Account
            <small>Connect a student to your account</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">

                <?php if (isset($msg)) echo $msg; ?>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-user-plus"></i> Add Student Link</h3>
                    </div>
                    <form method="POST">
                        <div class="box-body">
                            <?php if (count($students_list) > 0): ?>
                                <div class="form-group">
                                    <label for="student_id">Select Student:</label>
                                    <select name="student_id" id="student_id" class="form-control select2" style="width: 100%;" required>
                                        <option value="">-- Select Student --</option>
                                        <?php foreach ($students_list as $student): ?>
                                            <option value="<?php echo $student['id']; ?>">
                                                <?php echo htmlspecialchars($student['studentNo'] . ' - ' . $student['fname'] . ' ' . $student['lname']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p class="help-block"><i class="fa fa-info-circle"></i> Search by student number or name</p>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> No available students to link. You may have already linked all students.
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="box-footer clearfix">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6">
                                    <?php if (count($students_list) > 0): ?>
                                        <button type="submit" name="link_student" class="btn btn-primary btn-block">
                                            <i class="fa fa-link"></i> Link Student
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="col-xs-12 col-sm-6">
                                    <a href="view-linked-student.php" class="btn btn-success btn-block">
                                        <i class="fa fa-users"></i> View Linked Students
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 with enhanced search
    $('.select2').select2({
        placeholder: "Search student by name or student number",
        allowClear: true,
        width: '100%',
        matcher: function(params, data) {
            // If no search term, return all data
            if ($.trim(params.term) === '') {
                return data;
            }
            
            // Search in the text content
            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                return data;
            }
            
            // Return null if no match
            return null;
        }
    });
});
</script>

<?php include_once('layout/footer.php'); ?>