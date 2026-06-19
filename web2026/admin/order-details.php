<?php
require_once '../config/db.php';
global $conn;

if(!isset($_GET['id'])) { header("Location: orders.php"); exit(); }
$id = (int)$_GET['id'];

$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = $id");
$order = mysqli_fetch_assoc($order_query);

if (!$order) { die("Order not found!"); }

$items = json_decode($order['order_items'], true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $id; ?> Invoice | Admin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin-style.css">

    <style>
        .btn-print {
            background-color: var(--plum-wine);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-print:hover {
            background-color: var(--copper-rose);
            color: white;
        }

        @media print {
            .sidebar, .btn-print, .bi-arrow-left, .btn-light, header, nav {
                display: none !important;
            }
            body {
                background-color: white !important;
                padding: 0;
                margin: 0;
            }
            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .card {
                border: 1px solid #eee !important;
                box-shadow: none !important;
                margin-bottom: 20px !important;
            }
            main {
                margin: 0 !important;
                padding: 0 !important;
            }
            .row {
                display: block !important;
            }
            .col-md-8, .col-md-4 {
                width: 100% !important;
            }
            .fw-bold {
                color: black !important;
            }
            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 30px;
                border-bottom: 2px solid #754B4D;
                padding-bottom: 10px;
            }
        }

        .print-header {
            display: none;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row flex-column flex-md-row">

    <main class="col-md-12 px-md-4 py-4">

            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <div class="d-flex align-items-center">
                    <a href="orders.php" class="btn btn-sm btn-light me-3 shadow-sm text-plum-wine border"><i class="bi bi-arrow-left"></i> Back</a>
                    <h2 class="fw-bold mb-0 text-plum-wine">Order #<?php echo $id; ?></h2>
                </div>
                <button onclick="window.print()" class="btn-print shadow-sm">
                    <i class="bi bi-printer me-2"></i> Print Invoice
                </button>
            </div>

            <div class="print-header">
                <h1 style="color: #754B4D; letter-spacing: 5px;">SHINY LADY BOUTIQUE</h1>
                <p>Official Purchase Invoice | Order #<?php echo $id; ?></p>
                <p style="font-size: 0.8rem;"><?php echo date("F d, Y h:i A"); ?></p>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; overflow: hidden;">
                        <div class="p-4 border-bottom bg-light">
                            <h5 class="fw-bold mb-0 text-plum-wine">Purchased Items</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                <tr class="small text-muted">
                                    <th class="ps-4">Item Name</th>
                                    <th>Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($items): foreach($items as $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-plum-wine"><?php echo htmlspecialchars($item['name']); ?></div>
                                        </td>
                                        <td><?php echo number_format($item['price'], 2); ?> NIS</td>
                                        <td class="text-center"><?php echo $item['qty']; ?></td>
                                        <td class="text-end pe-4 fw-bold"><?php echo number_format($item['price'] * $item['qty'], 2); ?> NIS</td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 15px;">
                        <h6 class="fw-bold text-muted mb-3 text-uppercase small">Customer & Shipping</h6>
                        <p class="mb-1 fw-bold text-plum-wine" style="font-size: 1.1rem;"><?php echo htmlspecialchars($order['customer_name']); ?></p>
                        <p class="mb-1 small"><i class="bi bi-telephone me-2"></i><?php echo $order['customer_phone']; ?></p>
                        <hr class="text-rosewater">
                        <p class="small text-muted">
                            <i class="bi bi-geo-alt me-1"></i>
                            <?php echo $order['location']; ?>, <?php echo $order['city']; ?><br>
                            <?php echo htmlspecialchars($order['address']); ?>
                        </p>
                    </div>

                    <div class="card p-4 border-0 shadow-sm" style="background: var(--china-doll); border-radius: 15px;">
                        <h5 class="fw-bold text-plum-wine mb-2">Order Grand Total</h5>
                        <h2 class="fw-bold text-plum-wine"><?php echo number_format($order['total_amount'], 2); ?> NIS</h2>
                        <small class="text-muted">Includes items subtotal + delivery fees.</small>
                    </div>

                    <div class="mt-4 text-center d-none d-print-block" style="font-size: 0.8rem; color: #aaa;">
                        <p>Thank you for shopping with Shiny Lady Boutique! ✨</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>