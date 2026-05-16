<?php include_once('layout/head.php');

$result = $db->prepare("SELECT 
 (SELECT COUNT(*) FROM tbl_menu) AS totalMenu, 
 (SELECT COUNT(*) FROM tbl_users) AS totalUser,
 (SELECT COUNT(*) FROM tbl_transactions) AS totalTrans,
 (SELECT COUNT(*) FROM tbl_payment) AS totalPayment,
 (SELECT COUNT(*) FROM tbl_menu WHERE qty <= 10) AS lowStock,
 (SELECT COUNT(*) FROM tbl_menu WHERE expDate <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)) AS nearExpired
");
$result->execute();
if ($row = $result->fetch()) { ?>


    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
    <div class="col-xs-12">

    <div class="box box-primary">
    <div class="box-header">

    </div>
    <!-- /.box-header -->
    <div class="box-body">

    <!-- USERS -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo $row['totalUser']; ?></h3>
                <p>Users</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="user.php" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- MENU -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo $row['totalMenu']; ?></h3>
                <p>Menu</p>
            </div>
            <div class="icon">
                <i class="fa fa-cutlery"></i>
            </div>
            <a href="menu.php" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- PAYMENTS -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo $row['totalPayment']; ?></h3>
                <p>Payments</p>
            </div>
            <div class="icon">
                <i class="fa fa-credit-card"></i>
            </div>
            <a href="report.php" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- TRANSACTIONS -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3><?php echo $row['totalTrans']; ?></h3>
                <p>Transactions</p>
            </div>
            <div class="icon">
                <i class="fa fa-exchange"></i>
            </div>
            <a href="report.php" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- LOW STOCK -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-orange">
            <div class="inner">
                <h3><?php echo $row['lowStock']; ?></h3>
                <p>Low Stock</p>
            </div>
            <div class="icon">
                <i class="fa fa-exclamation-triangle"></i>
            </div>
            <a href="lowstock.php" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- EXPIRATION -->
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3><?php echo $row['nearExpired']; ?></h3>
                <p>Expiration</p>
            </div>
            <div class="icon">
                <i class="fa fa-calendar-times-o"></i>
            </div>
            <a href="expire.php" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
<?php } ?>
    <!-- /.Slide-header -->
    <!--             <div class="box-body">
                  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                      <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
                      <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
                      <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                    </ol>
                    <div class="carousel-inner">
                      <div class="item">
                        <img width="100%" src="../../images/slide01.jpg" alt="First slide">

                        <div class="carousel-caption">
                          First Slide
                        </div>
                      </div>
                      <div class="item active">
                        <img width="100%" src="../../images/slide02.jpg" alt="Second slide">

                        <div class="carousel-caption">
                          Second Slide
                        </div>
                      </div>
                      <div class="item">
                        <img width="100%" src="../../images/slide03.jpg" alt="Third slide">

                        <div class="carousel-caption">
                          Third Slide
                        </div>
                      </div>
                    </div>
                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                      <span class="fa fa-angle-left"></span>
                    </a>
                    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                      <span class="fa fa-angle-right"></span>
                    </a>
                  </div>
                </div> -->
    <!-- /.Slide-body -->


    </div>
    <!-- /.box-body -->
    </div>
    <!-- /.box -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
    </section>
    <!-- /.content -->

    </div>

<?php include_once('layout/footer.php'); ?>