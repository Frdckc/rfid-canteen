<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="images/<?=($_SESSION['role'] == 'Admin' ? 'user' : 'student');?>/<?php echo$_SESSION['pic'];?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p> <?php echo $_SESSION['fullname']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i>Online</a>
            </div>
        </div>
        <hr/>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <?php $_SESSION['a'] = 0;
            $_SESSION['b'] = 0;
            $_SESSION['c'] = 0;
            $_SESSION['d'] = 0; ?>


            <?php if ($_SESSION['role'] == 'Admin') { ?>
                <li><a href="index.php"><i class="fa fa-dashboard"></i> <span>Dashboard  <?=$_SESSION['role'];?></span></a></li>
                <li><a href="students.php"><i class="fa fa-group"></i> <span>Students</span></a></li>
                <li><a href="balance.php"><i class="fa fa-paypal"></i><span>RFID Balance   </span></a></li>
                <li><a href="user.php"><i class="fa fa-user"></i> <span>Users</span></a></li>
                <li><a href="menu.php"><i class="fa fa-file"></i> <span>Food Item</span></a></li>

                <li><a href="cart.php"><i class="fa fa-shopping-cart"></i><span>Cart   </span></a></li>


                
        <li class="treeview">
                    <a href="#">
                        <i class="fa fa-history"></i>
                        <span>User Logs</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right">

              </i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="ualt.php?role=Parent"><span>Parents</span></a></li>  
                        <li><a href="ualt.php?role=Staff"><span>Staff</span></a></li>
                        <li><a href="ualt.php?role=Admin"><span>Admin</span></a></li>

                    </ul>
                </li>



                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-print"></i>
                        <span>Report</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right">

              </i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="report.php"><span>Sales</span></a></li>
                        <li><a href="monitoring.php"><span>Monitoring</span></a></li> 
                        <li><a href="transactions.php"><span>Transactions</span></a></li>

                    </ul>
                </li>


            <?php }elseif ($_SESSION['role'] == 'Staff') { ?>
                         <li><a href="menu.php"><i class="fa fa-file-text"></i> <span>Menu</span></a></li>
                <li><a href="cart.php"><i class="fa fa-paypal"></i><span>Cart   </span></a></li>
                <li><a href="report.php"><i class="fa fa-paypal"></i><span>Sales   </span></a></li>
            <?php } else { ?>
   
 
                <li><a href="profile-settings.php"><i class="fa fa-user"></i><span>Profile   </span></a></li>
                <li><a href="balance-settings.php"><i class="fa fa-cogs"></i><span>Settings   </span></a></li>
                <li><a href="balances.php"><i class="fa fa-paypal"></i><span>Transaction Histories   </span></a></li>
                                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-print"></i>
                        <span>Link Account</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right">

              </i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="link-student-account.php"><span>Link Student Account</span></a></li>
                        <li><a href="view-linked-student.php"><span>Vew Link Student</span></a></li>

                    </ul>
                </li>
            <?php } ?>


            <li>
                <a href="modal/user.php?do=logout" class="logout-link"><i class="fa fa-unlock"></i>
                    <span>Log Out</span></a></li>

        </ul>


    </section>
    <!-- /.sidebar -->
</aside>