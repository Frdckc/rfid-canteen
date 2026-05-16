<?php include_once('layout/head.php'); ?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Nearly Expired Items
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Items nearing expiration</h3>
                    </div>

                    <div class="box-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Expiration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    include_once('connect.php');

                                    $today = date('Y-m-d');
                                    $nextWeek = date('Y-m-d', strtotime('+7 days'));

                                    $stmt = $db->prepare("SELECT * FROM tbl_menu WHERE expDate BETWEEN ? AND ?");
                                    $stmt->execute([$today, $nextWeek]);

                                    $i = 1;
                                    while ($row = $stmt->fetch()) { 
                                        $nearExpireStyle = "style='background:#fff3cd; font-weight:bold;'";

                                        // image path
                                        $img = !empty($row['image']) ? "uploads/".$row['image'] : "assets/no-image.png";
                                ?>
                                <tr <?= $nearExpireStyle ?>>

                                    <!-- Row Number -->
                                    <td><?= $i++ ?></td>

                                    <!-- Product Image -->
                                    <td>
                                        <img height="50" width="50" src="images/menu/<?= $row['imgUrl']; ?>">
                                    </td>

                                    <!-- Product Name -->
                                    <td><?= $row['name'] ?></td>

                                    <!-- Category -->
                                    <td><?= $row['category'] ?></td>

                                    <!-- Stock -->
                                    <td><?= $row['qty'] ?></td>

                                    <!-- Expiration Date -->
                                    <td><?= $row['expDate'] ?></td>

                                </tr>

                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </section>

</div>

<?php include_once('layout/footer.php'); ?>
