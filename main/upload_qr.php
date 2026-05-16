<?php
include_once('connect.php');

// Set content type to JSON (must be before any output)
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['qr_file'])) {
    $uploadDir = __DIR__ . '/uploads/qr/';

    // Make sure folder exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES['qr_file']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['qr_file']['tmp_name'], $targetPath)) {
        // Save to DB
        $stmt = $db->prepare("INSERT INTO system_qr (filename) VALUES (?)");
        $stmt->execute([$fileName]);

        // Return success JSON response
        echo json_encode([
            'success' => true,
            'message' => 'QR Code uploaded successfully!'
        ]);
    } else {
        // Return error JSON response
        echo json_encode([
            'success' => false,
            'message' => 'Upload failed. Please try again.'
        ]);
    }
} else {
    // Return error JSON response
    echo json_encode([
        'success' => false,
        'message' => 'No file selected.'
    ]);
}
exit; // Stop script execution after sending JSON
?>
