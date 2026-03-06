<?php
declare(strict_types=1);

if (!function_exists('project_base')) {
    function project_base(): string {
        $script = $_SERVER['SCRIPT_NAME'] ?? '/';
        $script = str_replace('\\', '/', $script);
        $dir = rtrim(str_replace('\\', '/', dirname($script)), '/');

        if (strpos($script, '/admin/') !== false || $dir === '/admin') {
            $dir = rtrim(str_replace('\\', '/', dirname($dir)), '/');
        }
        if (strpos($script, '/views/') !== false || $dir === '/views') {
            $dir = rtrim(str_replace('\\', '/', dirname($dir)), '/');
        }

        return $dir === '' ? '' : $dir;
    }
}

if (!function_exists('h')) {
    function h(string $s): string {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('redirect_to')) {
    function redirect_to(string $pathFromBase): void {
        $base = project_base();
        if ($pathFromBase !== '' && $pathFromBase[0] !== '/') {
            $pathFromBase = '/' . $pathFromBase;
        }
        header('Location: ' . $base . $pathFromBase);
        exit;
    }
}

if (!function_exists('post_str')) {
    function post_str(string $key): string {
        return trim((string)($_POST[$key] ?? ''));
    }
}

if (!function_exists('post_int')) {
    function post_int(string $key): int {
        return (int)($_POST[$key] ?? 0);
    }
}

if (!function_exists('get_int')) {
    function get_int(string $key): int {
        return (int)($_GET[$key] ?? 0);
    }
}

if (!function_exists('get_str')) {
    function get_str(string $key): string {
        return trim((string)($_GET[$key] ?? ''));
    }
}