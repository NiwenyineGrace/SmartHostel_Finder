<?php
include('config.php');


// Handle Mark as Read
if (isset($_GET['mark_read'])) {
    $id = mysqli_real_escape_string($conn, $_GET['mark_read']);
    mysqli_query($conn, "UPDATE notifications SET status='read' WHERE id='$id'");
    header("Location: notification.php"); // Refresh to clear GET parameters
    exit();
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM notifications WHERE id='$id'");
    header("Location: notification.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notifications | SmartHostel</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <style>
        .main-content {
            margin-left: 250px; /* Adjust this based on your sidebar width */
            padding: 20px;
            min-height: 80vh;
        }
        @media (max-width: 768px) {
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="bg-light">

    <?php include('navbar.php'); ?>

    <div class="d-flex">
        <?php include('admin_sidebar.php'); ?>

        <div class="main-content flex-grow-1">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">
                        <i class="fas fa-bell text-primary me-2"></i>Support Tickets
                    </h2>
                    <span class="badge bg-primary px-3 py-2">Admin Panel</span>
                </div>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="ps-4">Received</th>
                                    <th>Sender Details</th>
                                    <th>Inquiry Category</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($conn, "SELECT * FROM notifications ORDER BY created_at DESC");
                                if(mysqli_num_rows($res) > 0) {
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $isUnread = ($row['status'] == 'unread');
                                ?>
                                    <tr class="<?= $isUnread ? 'fw-bold bg-white' : 'text-muted' ?>">
                                        <td class="ps-4 small">
                                            <?= date('M d, Y', strtotime($row['created_at'])) ?><br>
                                            <span class="opacity-50"><?= date('H:i', strtotime($row['created_at'])) ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2 bg-light rounded-circle p-2">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </div>
                                                <div>
                                                    <div><?= htmlspecialchars($row['name']) ?></div>
                                                    <div class="small opacity-75"><?= htmlspecialchars($row['email']) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-primary border border-primary-subtle">
                                                <?= htmlspecialchars($row['subject']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($row['message']) ?>">
                                                <?= htmlspecialchars($row['message']) ?>
                                            </p>
                                        </td>
                                        <td>
                                            <?php if ($isUnread): ?>
                                                <span class="badge bg-danger">NEW</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary opacity-50">READ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <?php if ($isUnread): ?>
                                                    <a href="notification.php?mark_read=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success" title="Mark as Read">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="notification.php?delete=<?= $row['notification_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this ticket?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-5 text-muted'>No support tickets found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-auto">
        <?php include('footer.php'); ?>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>