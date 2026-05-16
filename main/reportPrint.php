<?php
include_once('connect.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
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
    <h2>Sales Report</h2>
    <p>
        From: <?= htmlspecialchars($_GET['d1'] ?? '') ?> 
        To: <?= htmlspecialchars($_GET['d2'] ?? '') ?>
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice #</th>
                <th>QTY</th>
                <th>Total</th>
                <th>Date</th>
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
                    SELECT invoiceNo, SUM(xqty) AS xqty, SUM(xtotal) AS xtotal, p.dt
                    FROM tbl_purchases p
                    INNER JOIN tbl_menu m ON m.id = p.menuID
                    WHERE xfinished = '1' AND p.dt BETWEEN ? AND ?
                    GROUP BY invoiceNo
                    ORDER BY p.id DESC
                ");
                $result->execute([$d1, $d2]);
            } else {
                $result = $db->prepare("
                    SELECT invoiceNo, SUM(xqty) AS xqty, SUM(xtotal) AS xtotal, p.dt
                    FROM tbl_purchases p
                    INNER JOIN tbl_menu m ON m.id = p.menuID
                    WHERE xfinished = '1'
                    GROUP BY invoiceNo
                    ORDER BY p.id DESC
                ");
                $result->execute();
            }

            $i = 1;
            while ($row = $result->fetch()) {
                $xtotal += $row['xtotal'];
                $xqty   += $row['xqty'];
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= htmlspecialchars($row['invoiceNo']); ?></td>
                    <td><?= (int)$row['xqty']; ?></td>
                    <td><?= number_format($row['xtotal'], 2); ?></td>
                    <td><?= htmlspecialchars($row['dt']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td><?= (int)$xqty; ?></td>
                <td><?= number_format($xtotal, 2); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
