<?php
include_once('layout/head.php');
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bell"></i> My Notifications</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Type</th>
                                    <th width="20%">Title</th>
                                    <th width="40%">Message</th>
                                    <th width="20%">Date/Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $notifications = $db->prepare("SELECT * FROM tbl_notifications WHERE studentID=? ORDER BY created_at DESC");
                                $notifications->execute([$_SESSION['userID']]);
                                $i = 1;
                                while($notif = $notifications->fetch()):
                                ?>
                                <tr class="<?php echo ($notif['is_read'] == 0 ? 'bg-warning' : ''); ?>">
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <?php 
                                        $type_label = $notif['type'];
                                        $type_text = ucfirst($notif['type']);
                                        if($notif['type'] == 'success') {
                                            $type_text = 'Approved';
                                        } else if($notif['type'] == 'danger') {
                                            $type_text = 'Declined';
                                        }
                                        ?>
                                        <span class="label label-<?php echo $type_label; ?>">
                                            <?php echo $type_text; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $notif['title']; ?></td>
                                    <td><?php echo $notif['message']; ?></td>
                                    <td><?php echo date('M d, Y h:i A', strtotime($notif['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include_once('layout/footer.php'); ?>