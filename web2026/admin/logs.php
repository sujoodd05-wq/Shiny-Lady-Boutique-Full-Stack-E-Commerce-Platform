<?php
global $conn;
require_once '../config/db.php';
$logs = mysqli_query($conn, "SELECT * FROM activity_log ORDER BY id DESC LIMIT 50");
?>
<table class="table">
    <thead>
    <tr><th>Admin</th><th>Action</th><th>Details</th><th>Time</th></tr>
    </thead>
    <tbody>
    <?php while($log = mysqli_fetch_assoc($logs)): ?>
        <tr>
            <td><?php echo $log['admin_name']; ?></td>
            <td><span class="badge bg-info"><?php echo $log['action']; ?></span></td>
            <td><?php echo $log['details']; ?></td>
            <td class="small"><?php echo $log['created_at']; ?></td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
