<?php
session_start();
require_once "../config/db.php";

// Handle approval/rejection
if (isset($_POST['action']) && isset($_POST['clinic_id'])) {
    $clinic_id = intval($_POST['clinic_id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    $stmt = $conn->prepare("UPDATE clinics SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $action, $clinic_id);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all clinics
$query = "SELECT * FROM clinics ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Clinic Approvals | Vet Clinics</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    body { background: #f6f7fb; padding: 20px 0; }
    .clinic-card { margin-bottom: 20px; font-size: 0.9rem; }
    .clinic-card .card-body { padding: 10px 12px; }
    .clinic-card h5 { font-size: 1rem; margin-bottom: 5px; }
    .clinic-card p { margin-bottom: 3px; }
    .map-container { height: 150px; border-radius: 5px; margin-top: 5px; }
    .badge { font-size: 0.8rem; }
    form.d-flex button { font-size: 0.8rem; padding: 3px 6px; }
</style>
</head>
<body>

<div class="container">
    <h2 class="mb-3 text-center" style="font-size:1.5rem;">Clinic Registration Approvals</h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="row">
            <?php while($clinic = $result->fetch_assoc()): 
                $status = $clinic['status'] ?? 'pending';
            ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card clinic-card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($clinic['clinic_name']); ?></h5>
                            <p><strong>Admin:</strong> <?php echo htmlspecialchars($clinic['first_name'] . ' ' . $clinic['last_name']); ?></p>
                            <p><strong>Contact:</strong> <?php echo htmlspecialchars($clinic['contact']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($clinic['admin_email']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($clinic['address']); ?></p>

                            <div>
                                <strong>Documents:</strong><br>
                                <?php if($clinic['verification_file']): ?>
                                    <a href="<?php echo htmlspecialchars($clinic['verification_file']); ?>" target="_blank">Verification</a><br>
                                <?php endif; ?>
                                <?php if($clinic['face_auth_file']): ?>
                                    <a href="<?php echo htmlspecialchars($clinic['face_auth_file']); ?>" target="_blank">Face Auth</a><br>
                                <?php endif; ?>
                                <?php if($clinic['id_validation_file']): ?>
                                    <a href="<?php echo htmlspecialchars($clinic['id_validation_file']); ?>" target="_blank">ID Validation</a>
                                <?php endif; ?>
                            </div>

                            <?php if($clinic['lat'] && $clinic['lng']): ?>
                                <div id="map-<?php echo $clinic['id']; ?>" class="map-container"></div>
                            <?php endif; ?>

                            <p class="text-muted small mt-1">Registered: <?php echo $clinic['created_at']; ?></p>

                            <?php if($status === 'pending'): ?>
                                <form method="POST" class="d-flex gap-1 mt-1">
                                    <input type="hidden" name="clinic_id" value="<?php echo $clinic['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            <?php else: ?>
                                <span class="badge <?php echo $status === 'approved' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo ucfirst($status); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">No clinics registered yet.</div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="assets/js/bootstrap.bundle.min.js"></script>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
<?php
$result->data_seek(0);
while($clinic = $result->fetch_assoc()):
    if($clinic['lat'] && $clinic['lng']):
?>
    var map<?php echo $clinic['id']; ?> = L.map('map-<?php echo $clinic['id']; ?>').setView([<?php echo $clinic['lat']; ?>, <?php echo $clinic['lng']; ?>], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map<?php echo $clinic['id']; ?>);

    L.marker([<?php echo $clinic['lat']; ?>, <?php echo $clinic['lng']; ?>]).addTo(map<?php echo $clinic['id']; ?>);
<?php
    endif;
endwhile;
?>
</script>

</body>
</html>
