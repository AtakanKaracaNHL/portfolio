<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';

require_login();

$vid = get_int('vid');
if ($vid <= 0) {
    http_response_code(404);
    exit;
}

$st = $pdo->prepare('SELECT id, file_id, stored_name, original_name, mime FROM file_versions WHERE id = ?');
$st->execute([$vid]);
$v = $st->fetch();

if (!$v) {
    http_response_code(404);
    exit;
}

if (!is_admin()) {
    $st = $pdo->prepare('SELECT can_view FROM file_access WHERE visitor_id = ? AND file_id = ?');
    $st->execute([(int)current_user()['id'], (int)$v['file_id']]);
    $perm = $st->fetch();

    if (!$perm || (int)$perm['can_view'] !== 1) {
        redirect_to('403.php');
    }
}

$path = __DIR__ . '/../uploads/' . (int)$v['file_id'] . '/' . (string)$v['stored_name'];

if (!is_file($path)) {
    http_response_code(404);
    exit;
}

$mime = trim((string)($v['mime'] ?? ''));
if ($mime === '') {
    $mime = 'application/pdf';
}

header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . basename((string)$v['original_name']) . '"');
header('X-Content-Type-Options: nosniff');
header('Content-Length: ' . filesize($path));

readfile($path);
exit;