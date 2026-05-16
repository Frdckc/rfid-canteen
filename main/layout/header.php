
<?php
  $homeLink = 'index.php';
  $roleHdr = strtolower(trim($_SESSION['role'] ?? ''));
  if ($roleHdr === 'staff') {
    $homeLink = 'menu.php';
  } elseif ($roleHdr === 'parent') {
    $homeLink = 'balances.php';
  }
?>
  <header class="main-header">
    <!-- Logo -->
    <a href="<?= $homeLink; ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>GFA</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>GFA Canteen</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
<ul class="nav navbar-nav">
          <?php 
          $check_student = $db->prepare("SELECT id FROM tbl_students WHERE id=?");
          $check_student->execute([$_SESSION['userID']]);
          $is_student = $check_student->fetch();
          
          if($is_student): 
            $notif_query = $db->prepare("SELECT COUNT(*) as unread_count FROM tbl_notifications WHERE studentID=? AND is_read=0");
            $notif_query->execute([$_SESSION['userID']]);
            $notif_count = $notif_query->fetch();
            $unread = $notif_count['unread_count'];
          ?>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <?php if($unread > 0): ?>
                <span class="label label-warning"><?php echo $unread; ?></span>
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo $unread; ?> notifications</li>
              <li>
                <ul class="menu">
                  <?php
                  $notif_list = $db->prepare("SELECT * FROM tbl_notifications WHERE studentID=? ORDER BY created_at DESC LIMIT 10");
                  $notif_list->execute([$_SESSION['userID']]);
                  while($notif = $notif_list->fetch()):
                  ?>
                  <li>
                    <a href="mark_notification.php?id=<?php echo $notif['id']; ?>" style="white-space: normal;">
                      <i class="fa fa-<?php echo ($notif['type'] == 'success' ? 'check text-green' : ($notif['type'] == 'danger' ? 'times text-red' : 'info text-blue')); ?>"></i>
                      <strong><?php echo $notif['title']; ?></strong><br>
                      <small><?php echo $notif['message']; ?></small><br>
                      <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo date('M d, Y h:i A', strtotime($notif['created_at'])); ?></small>
                    </a>
                  </li>
                  <?php endwhile; ?>
                  <?php if($unread == 0): ?>
                  <li><a href="#" style="text-align:center;"><i>No new notifications</i></a></li>
                  <?php endif; ?>
                </ul>
              </li>
              <li class="footer"><a href="notifications.php">View all notifications</a></li>
            </ul>
          </li>
          <?php endif; ?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="images/<?=($_SESSION['role'] == 'Admin' ? 'user' : 'student');?>/<?php echo$_SESSION['pic'];?>" class="user-image" alt="User Image">
              <!-- Timer --> 
<script type="text/javascript">
tday=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
tmonth=new Array("January","February","March","April","May","June","July","August","September","October","November","December");

function GetClock(){
var d=new Date();
var nday=d.getDay(),nmonth=d.getMonth(),ndate=d.getDate(),nyear=d.getFullYear();
var nhour=d.getHours(),nmin=d.getMinutes(),nsec=d.getSeconds(),ap;

if(nhour==0){ap=" AM";nhour=12;}
else if(nhour<12){ap=" AM";}
else if(nhour==12){ap=" PM";}
else if(nhour>12){ap=" PM";nhour-=12;}

if(nmin<=9) nmin="0"+nmin;
if(nsec<=9) nsec="0"+nsec;

document.getElementById('clockbox').innerHTML=""+tday[nday]+", "+tmonth[nmonth]+" "+ndate+", "+nyear+" "+nhour+":"+nmin+":"+nsec+ap+"";
}

window.onload=function(){
GetClock();
setInterval(GetClock,1000);
}
</script> 


              <span id="clockbox" class="hidden-xs">  <?php  echo$_SESSION['fullname']; ?> </span>
 
             
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="images/<?=($_SESSION['role'] == 'Admin' ? 'user' : 'student');?>/<?php echo$_SESSION['pic'];?>" class="img-circle" alt="User Image">

                <p>  <?php  echo$_SESSION['fullname']; ?>   </p>
             </li>
             <!-- Menu Body --> 
             <li class="user-footer">
               <div class="pull-left">
                 <?php  $id=$_SESSION['userID']; ?>
                 <a data-toggle="modal" data-target="#updatePassword<?php echo $id;?>" type="submit"   class="btn btn-default btn-flat">Change Password</a>
               </div>

              <div class="pull-right">
               <a href="modal/user.php?do=logout" class="btn btn-default btn-flat logout-link">Log Out</a>
              </div>
            </li>
          </ul>
        </li>

      </ul>
    </div>
  </nav>
</header>

<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutConfirmModal" tabindex="-1" role="dialog" aria-labelledby="logoutConfirmTitle">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-red">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="logoutConfirmTitle"><i class="fa fa-sign-out"></i> Confirm Logout</h4>
      </div>
      <div class="modal-body text-center">
        <p class="lead" style="margin: 0;">Are you sure you want to logout?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="logoutConfirmBtn" class="btn btn-danger"><i class="fa fa-sign-out"></i> Logout</button>
      </div>
    </div>
  </div>
</div>


<?php $result = $db->prepare("SELECT * FROM tbl_users ORDER BY id DESC");
$result->execute();
for($i=0; $row = $result->fetch(); $i++){ 
 $id = $row['id'];  ?> 
 <div class="modal fade" id="updatePassword<?php echo $id; ?>" tabindex="-1" role="dialog"  > 
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Update Password</h4>
      </div>
      <div class="modal-body"> 

        <form class="form-horizontal" action="modal/user.php?do=changePasswordUser&id=<?php echo $id;?>" method="post">  
         <div class="box-body">   <input  value="<?php echo $row['id']; ?>" type="hidden" class="form-control" name="id" >  
          <div class="form-group">  <label class="col-sm-2 control-label">Username</label>  <div class="col-sm-10"> 
           <input  value="<?php echo $row['username']; ?>"  type="text" class="form-control" name="username" placeholder="Username" required readonly></div>
         </div> 
         <div class="form-group">  <label  class="col-sm-2 control-label">Password</label>   <div class="col-sm-10">  
           <input  value="<?php echo $row['password']; ?>"  type="password" class="form-control" name="password" placeholder="Password" required readonly></div>
         </div>   

         <div class="form-group">  <label class="col-sm-2 control-label">Type Current Password</label>  <div class="col-sm-10"> 
           <input    type="text" class="form-control" name="cPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{6,}" title="Must contain at least one special characters, one number and one uppercase and lowercase letter, and at least 6 or more characters"  placeholder="Type Current Password" required ></div>
         </div> 
         <div class="form-group">  <label class="col-sm-2 control-label">New Password</label>  <div class="col-sm-10"> 
           <input   type="text" class="form-control" name="nPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{6,}" title="Must contain at least one special characters, one number and one uppercase and lowercase letter, and at least 6 or more characters"  placeholder="New Password" required ></div>
         </div> 
<div class="form-group">  <label class="col-sm-2 control-label">Re-type Password</label>  <div class="col-sm-10"> 
           <input    type="text" class="form-control" name="rPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$@$!%*#?&]).{6,}" title="Must contain at least one special characters, one number and one uppercase and lowercase letter, and at least 6 or more characters"  placeholder="Re-type Current Password" required ></div>
         </div>
       </div>  
       <div class="modal-footer">
        <button type="button" class="btn btn-default"  data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary pull-right">Save</button>
      </div> 
    </form>
  </div> 
</div>
</div> 
</div>

<?php } ?>