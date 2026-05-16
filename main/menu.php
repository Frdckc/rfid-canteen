<?php include_once('layout/head.php'); ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Menu (<?php $dtNow;

                $d = date('Y-m-d');
                $result = $db->prepare("SELECT COALESCE(COUNT(*),0)c  FROM tbl_payment WHERE dtNow='$dtNow'  GROUP by dtNow");
                $result->execute();
                if ($row = $result->fetch()) {
                    echo $count = $row['c'] + 1;
                } else {
                    $count = '1';
                }

                $invoiceNo = $d . $count;
                echo $_SESSION['invoiceNo'] = $invoiceNo;

                ?>)
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">


                    <div class="box box-primary">
                        <div class="box-header">
                            <!-- /result -->
                            <?php if (!empty($_SESSION['flash'])): ?>
                                <?php $f = $_SESSION['flash']; unset($_SESSION['flash']); ?>
                                <div class="alert alert-<?= htmlspecialchars($f['type'] ?? 'info'); ?>">
                                    <?= htmlspecialchars($f['msg'] ?? ''); ?>
                                </div>
                            <?php elseif (isset($_GET['r'])): ?>
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
                                <div class="alert alert-<?php echo $classs ?> <?php echo $classs; ?>">
                                    <strong>Successfully <?php echo $r; ?>!</strong>
                                </div>
                            <?php endif; ?>

<!-- DITO NAKA COMMENT LANG -->
                            <a mycart="" data-toggle="modal" data-target="#addcat" type="submit" class="btn btn-success pull-right btn-m "><i class="fa fa-plus"> </i>
                                Category</a>
                            <a mycart="" data-toggle="modal" data-target="#add" type="submit" class="btn btn-primary pull-right btn-m "><i class="fa fa-plus"> </i>
                                Item</a>

                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>QTY</th>
                                    <th>Exp. Date</th> 
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $result = $db->prepare("SELECT * FROM tbl_menu ORDER BY id DESC");
                                $result->execute();
                                for ($i = 1; $row = $result->fetch(); $i++) {
                                    $id = $row['id'];

                                    // Minimum stock threshold (default 5 if column not in DB)
                                    $min_stock = isset($row['min_stock']) ? $row['min_stock'] : 10;

                                    // Expiration check (highlight if within 7 days)
                                    $today = date('Y-m-d');
                                    $expDate = $row['expDate'];
                                    $expTimestamp = strtotime($expDate);
                                    $highlight_exp = ($expTimestamp - strtotime($today)) <= (7 * 24 * 60 * 60); // 7 days

                                    // Low stock check
                                    $highlight_stock = $row['qty'] <= $min_stock;

                                    // Determine row class
                                    $rowClass = ($highlight_exp || $highlight_stock) ? 'danger' : '';
                                ?>
                                <tr class="<?php echo $rowClass; ?>">
                                    <td> <?php echo $i; ?></td>
                                    <td><img height="50" width="50" src="images/menu/<?= $row['imgUrl']; ?>"></td>
                                    <td> <?= $row['code']; ?></td>
                                    <td> <?= $row['name']; ?></td>
                                    <td> <?= $row['category']; ?></td>
                                    <td> <?= $row['price']; ?></td>
                                    <td><?= $row['qty']; ?></td>
                                    <td> <?= $row['expDate']; ?></td> 
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                <i class="fa fa-fw fa-gear"> </i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#edit<?php echo $id; ?>" data-toggle="modal"><i class="fa fa-fw fa-pencil"> Edit</i></a>
                                                </li>
                                                <?php if ($row['qty'] <> 0) { ?>
                                                <li>
                                                    <a href="modal/statusOrder.php?do=notAvailable&id=<?php echo $id; ?>"><i class="fa fa-fw fa-pencil"> Not Available</i></a>
                                                </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <br/>
                                    </td>
                                </tr>
                                <?php } ?>

                            </table>
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
    <!-- /.Add -->
    <div class="modal fade" id="add" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Add</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="modal/menu.php?do=add" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group"><label class="col-sm-2 control-label">Code</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="code" placeholder="Code" autofocus required>
                                </div>

                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="pname" placeholder="Name" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="description" placeholder="Description" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10">
                                    <input type="number" step="0.01" class="form-control" name="price" placeholder="Price" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">QTY</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="qty" placeholder="QTY" required>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-10">
                                    <select name="category" class="form-control" required>
                                        <?php
                                        $result = $db->prepare("SELECT * FROM tbl_category");
                                        $result->execute();
                                        for ($i = 0; $rows = $result->fetch(); $i++) { ?>
                                            <option value="<?php echo $rows['category']; ?>"><?php echo $rows['category']; ?></option>
                                        <?php } ?>
                                    </select><br>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Expiration Date</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" name="expDate" placeholder="Expiration Date" required>
                                </div>
                            </div>
                       
                            <div class="form-group"><label class="col-sm-2 control-label">Upload Image</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="imgUrl" placeholder="Choose Image" required>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $result = $db->prepare("SELECT * FROM tbl_menu ORDER BY id DESC");
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $id = $row['id']; ?>
    <!-- /.Edit -->
    <div class="modal fade" id="edit<?php echo $id; ?>" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Edit</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="modal/menu.php?do=edit&id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group"><label class="col-sm-2 control-label">Code</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['code']; ?>" type="text" class="form-control" name="code" placeholder="Code" autofocus required readonly>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['name']; ?>" type="text" class="form-control" name="pname" placeholder="Name" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['description']; ?>" type="text" class="form-control" name="description" placeholder="Description" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['price']; ?>" type="number" step="0.01" class="form-control" name="price" placeholder="Price" required>
                                </div>
                            </div>
                            <div class="form-group"><label class="col-sm-2 control-label">QTY</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['qty']; ?>" type="number" class="form-control" name="qty" placeholder="QTY" required>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-10">
                                    <select name="category" class="form-control" required>
                                        <option selected><?php echo $row['category']; ?></option>

                                        <?php
                                        $r = $db->prepare("SELECT * FROM tbl_category");
                                        $r->execute();
                                        for ($i = 0; $rows = $r->fetch(); $i++) { ?>
                                            <option><?php echo $rows['category']; ?></option>
                                        <?php } ?>
                                    </select><br>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">Expiration Date</label>
                                <div class="col-sm-10">
                                    <input value="<?php echo $row['expDate']; ?>" type="date" class="form-control" name="expDate" placeholder="Expiration Date" required>
                                </div>
                            </div>
                     
                            <div class="form-group"><label class="col-sm-2 control-label">Upload Image</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" name="imgUrl" placeholder="Choose Image">
                                    <input value="<?php echo $row['imgUrl']; ?>" type="hidden" class="form-control" name="imgUrl1">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php } ?>


    <!-- /.Add Talbe-->
    <div class="modal fade" id="addcat" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Add Category</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" action="modal/menu.php?do=addcategory" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group"><label class="col-sm-2 control-label">Your Category</label>
                                <div class="col-sm-10">
                                    <select name="category" class="form-control" required>
                                        <?php
                                        $result = $db->prepare("SELECT * FROM tbl_category");
                                        $result->execute();
                                        for ($i = 0; $rows = $result->fetch(); $i++) { ?>
                                            <option><?php echo $rows['category']; ?></option>
                                        <?php } ?>
                                    </select><br>
                                </div>
                            </div>

                            <div class="form-group"><label class="col-sm-2 control-label">New Category</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="category" placeholder="New Category" autofocus required>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php include_once('layout/footer.php'); ?>