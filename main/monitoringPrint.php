<?php
include_once('connect.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Monitoring Report</title>
    <style media="print">
        @page {
            size: auto;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        tfoot td {
            font-weight: bold;
        }
    </style>
</head>
<body onload="window.print();">
    <h2>Kakoohs - Monitoring Report</h2>
    <p>
        From: <?= htmlspecialchars($_GET['d1'] ?? '') ?> 
        To: <?= htmlspecialchars($_GET['d2'] ?? '') ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>QTY</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $xtotal = 0;
            $xqty   = 0;

            if (!empty($_GET['d1']) && !empty($_GET['d2'])) {
                $d1 = $_GET['d1'];
                $d2 = date('Y-m-d', strtotime("+1 day", strtotime($_GET['d2']))); // include last day
                $result = $db->prepare("
                    SELECT m.name, p.xprice, 
                           COALESCE(SUM(p.xqty),0) AS ttqty, 
                           COALESCE(SUM(p.xtotal),0) AS tttotal
                    FROM tbl_purchases p
                    INNER JOIN tbl_menu m ON m.id = p.menuID
                    WHERE xfinished = '1' AND p.dt BETWEEN ? AND ?
                    GROUP BY p.menuID
                    ORDER BY p.menuID DESC
                ");
                $result->execute([$d1, $d2]);
            } else {
                $result = $db->prepare("
                    SELECT m.name, p.xprice, 
                           COALESCE(SUM(p.xqty),0) AS ttqty, 
                           COALESCE(SUM(p.xtotal),0) AS tttotal
                    FROM tbl_purchases p
                    INNER JOIN tbl_menu m ON m.id = p.menuID
                    WHERE xfinished = '1'
                    GROUP BY p.menuID
                    ORDER BY p.menuID DESC
                ");
                $result->execute();
            }

            $i = 1;
            while ($row = $result->fetch()) {
                $xtotal += $row['tttotal'];
                $xqty   += $row['ttqty'];
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= number_format($row['xprice'], 2); ?></td>
                    <td><?= (int)$row['ttqty']; ?></td>
                    <td><?= number_format($row['tttotal'], 2); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td><?= (int)$xqty; ?></td>
                <td><?= number_format($xtotal, 2); ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
