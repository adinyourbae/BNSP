<?php
if (isset($_GET['filename'])) {
    $filename = basename($_GET['filename']);
    $back_dir = "uploads/";
    $file = realpath($back_dir . $filename);

    if (strpos($file, $back_dir) === 0 && file_exists($file)) {
        if (!headers_sent()) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file);
            finfo_close($finfo);

            header('Content-Description: File Transfer');
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: private');
            header('Pragma: private');
            header('Content-Length: ' . filesize($file));

            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: DENY');

            ob_end_clean(); // Clear output buffer
            flush();
            readfile($file);
            exit;
        } else {
            die("Headers have already been sent. File download failed.");
        }
    } else {
        die("File Not Found");
    }
}
?>
