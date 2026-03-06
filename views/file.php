<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';

require_login();

$u = current_user();
$role = strtolower(trim((string)($u['role'] ?? '')));

$vid = (int)($_GET['vid'] ?? 0);
if ($vid <= 0) { http_response_code(404); exit; }

$st = $pdo->prepare("
  SELECT v.id, v.file_id, v.stored_name, v.original_name
  FROM file_versions v
  WHERE v.id = ?
");
$st->execute([$vid]);
$v = $st->fetch();

if (!$v) { http_response_code(404); exit; }

if ($role !== 'admin') {
  $st = $pdo->prepare("SELECT can_access FROM file_access WHERE user_id = ? AND file_id = ?");
  $st->execute([(int)$u['id'], (int)$v['file_id']]);
  $perm = $st->fetch();

  if (!$perm || (int)$perm['can_access'] !== 1) {
    http_response_code(403);
    header("Location: /portfolio/403.php");
    exit;
  }
}

$fileId = (int)$v['file_id'];
$stored = (string)($v['stored_name'] ?? '');
if ($stored === '' || $fileId <= 0) { http_response_code(404); exit; }

$path = __DIR__ . '/../uploads/' . $fileId . '/' . $stored;
if (!is_file($path)) { http_response_code(404); exit; }

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . basename((string)$v['original_name']) . '"');
header('X-Content-Type-Options: nosniff');
readfile($path);
exit;
