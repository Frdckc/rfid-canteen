<?php include_once('layout/head.php'); ?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            Low Stock Items
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Items that need restocking</h3>
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
                                    $threshold = 10;

                                    $stmt = $db->prepare("SELECT * FROM tbl_menu WHERE qty <= ?");
                                    $stmt->execute([$threshold]);

                                    $i = 1;
                                    while ($row = $stmt->fetch()) { 
                                        $expiredStyle = ($row['qty'] <= $threshold) ? "style='background:#ffcccc; font-weight:bold;'" : "";
                                        
                                        // image path
                                        $img = !empty($row['image']) ? "uploads/".$row['image'] : "assets/no-image.png";
                                ?>
                                <tr <?= $expiredStyle ?>>

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
