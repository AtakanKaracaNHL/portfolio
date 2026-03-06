<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

function can_view_file(PDO $pdo, int $fileId): bool {
  $u = current_user();
  if (!$u) return false;
  if (is_admin()) return true;

  $st = $pdo->prepare('SELECT 1 FROM file_access WHERE visitor_id = ? AND file_id = ? AND can_view = 1');
  $st->execute([(int)$u['id'], $fileId]);
  return (bool)$st->fetch();
}

function require_file_access(PDO $pdo, int $fileId): void {
  if (!can_view_file($pdo, $fileId)) {
    redirect_to('403.php');
  }
}

function require_version_access(PDO $pdo, int $versionId): array {
  require_login();

  $st = $pdo->prepare('SELECT fv.id, fv.file_id, fv.version_number, fv.stored_name, fv.original_name, fv.mime, f.title, f.description, f.status
                       FROM file_versions fv
                       JOIN files f ON f.id = fv.file_id
                       WHERE fv.id = ?');
  $st->execute([$versionId]);
  $v = $st->fetch();

  if (!$v) {
    redirect_to('403.php');
  }

  require_file_access($pdo, (int)$v['file_id']);
  return $v;
}
