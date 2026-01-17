<?php

declare(strict_types=1);

function upload_sponsor_logo(array $file, int $maxBytes = 2097152): array
{
    if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['error' => 'Sponsor logo is required.'];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['error' => 'Upload failed. Please try again.'];
    }

    if (($file['size'] ?? 0) > $maxBytes) {
        return ['error' => 'Logo must be smaller than 2 MB.'];
    }

    if (!is_uploaded_file($file['tmp_name'] ?? '')) {
        return ['error' => 'Invalid file upload.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        return ['error' => 'Only PNG, JPG, or WEBP files are allowed.'];
    }

    $root = dirname(__DIR__);
    $targetDir = $root . '/public/uploads/sponsors';
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        return ['error' => 'Unable to create upload directory.'];
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $destination = $targetDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['error' => 'Unable to save uploaded file.'];
    }

    return ['path' => '/public/uploads/sponsors/' . $filename];
}
