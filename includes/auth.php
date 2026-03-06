<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool {
        return isset($_SESSION['user']);
    }
}

if (!function_exists('current_user')) {
    function current_user(): ?array {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_admin')) {
    function is_admin(): bool {
        $u = current_user();
        return strtolower(trim((string)($u['role'] ?? ''))) === 'admin';
    }
}

if (!function_exists('login_user')) {
    function login_user(PDO $pdo, string $username, string $password): bool {
        $st = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ?');
        $st->execute([$username]);
        $u = $st->fetch();
        if (!$u) return false;
        if (!password_verify($password, (string)$u['password_hash'])) return false;

        $_SESSION['user'] = [
            'id' => (int)$u['id'],
            'username' => (string)$u['username'],
            'role' => (string)$u['role'],
        ];
        return true;
    }
}

if (!function_exists('logout_user')) {
    function logout_user(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $p['path'],
                $p['domain'],
                (bool)$p['secure'],
                (bool)$p['httponly']
            );
        }
        session_destroy();
    }
}

if (!function_exists('require_login')) {
    function require_login(): void {
        if (!is_logged_in()) {
            redirect_to('login.php');
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin(): void {
        require_login();
        if (!is_admin()) {
            redirect_to('403.php');
        }
    }
}