<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: index.html"); exit; }
$username = $_SESSION['username'];
$folder = 'users/' . $username . '/';
$msg = $_SESSION['upload_message'] ?? null;
$msgType = (strpos($msg, 'could not') === false) ? 'success' : 'danger';
unset($_SESSION['upload_message']);
if (isset($_GET['delete']) && file_exists($f = $folder . $_GET['delete'])) {
    unlink($f); $msg = "File deleted."; $msgType = 'success';
}
if (isset($_GET['copy'])) { $msg = "Link copied!"; $msgType = 'success'; }
if (isset($_GET['file']) && file_exists($f = $folder . $_GET['file'])) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($f).'"');
    readfile($f); exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FileHub</title>
    <link rel="icon" href="icon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom sticky-top">
        <div class="container">
            <span class="navbar-brand fw-bold text-info">FileHub</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-secondary small">User: <span class="text-white fw-bold"><?= htmlspecialchars($username) ?></span></span>
                <a href="logout.php" class="btn btn-sm btn-outline-danger">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container py-5 flex-grow-1">
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card border-info border-opacity-25 shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25">
                        <h5 class="mb-0 text-info">Upload File</h5>
                    </div>
                    <div class="card-body">
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <input type="file" name="fileToUpload[]" multiple class="form-control mb-3">
                            <button type="submit" class="btn btn-info w-100 fw-bold" onclick="this.innerText='Uploading...'">Upload</button>
                        </form>
                        
                        <?php if($msg): ?>
                            <div class="alert alert-<?= $msgType ?> mt-3 py-2 small text-center"><?= $msg ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card border-secondary border-opacity-25 shadow-sm h-100">
                    <div class="card-header bg-transparent border-bottom border-secondary border-opacity-25">
                        <h5 class="mb-0">Your Files</h5>
                    </div>
                    <div class="card-body bg-body-tertiary">
                        <div class="row g-3">
                            <?php
                            $files = glob($folder . '*');
                            if (empty($files)) {
                                echo "<div class='col-12 text-center text-muted py-5'>No files uploaded yet.</div>";
                            } else {
                                foreach ($files as $file) {
                                    $name = basename($file);
                                    $size = round(filesize($file) / 1024, 2) . " KB";
                                    echo "
                                    <div class='col-sm-6 col-lg-6'>
                                        <div class='card h-100 border border-secondary border-opacity-10 bg-dark'>
                                            <div class='card-body text-center p-3'>
                                                <h6 class='text-truncate text-light mb-1' title='$name'>$name</h6>
                                                <small class='d-block text-secondary mb-3'>$size</small>
                                                <div class='btn-group w-100 btn-group-sm'>
                                                    <a href='dashboard.php?file=$name' class='btn btn-outline-info'>Download</a>
                                                    <button onclick='copyLink(\"$name\")' class='btn btn-outline-secondary'>Link</button>
                                                    <a href='dashboard.php?delete=$name' class='btn btn-outline-danger' onclick=\"return confirm('Delete $name?');\">×</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-3 bg-body-tertiary border-top text-center text-secondary small">
        &copy; 2024 FileHub | <a href="mailto:support@filehub.com" class="text-decoration-none text-info">Support</a>
    </footer>

    <script>
        function copyLink(filename) {
            const url = `${window.location.origin}/users/<?= $username ?>/${filename}`;
            navigator.clipboard.writeText(url);
            window.location.href = `dashboard.php?copy=${filename}`;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>