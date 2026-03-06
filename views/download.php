<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/guard.php';

$vid = get_int('vid');
$v = require_version_access($pdo, $vid);

$path = __DIR__ . '/../uploads/' . (int)$v['file_id'] . '/' . (string)$v['stored_name'];
if (!is_file($path)) { http_response_code(404); exit; }

header('Content-Type: ' . (string)$v['mime']);
header('Content-Length: ' . (string)filesize($path));
header('Content-Disposition: attachment; filename="' . basename((string)$v['original_name']) . '"');
readfile($path);
exit;
