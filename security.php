<?php
// security.php: Centralized session security check.

// This function should be called at the beginning of any admin-only page.
function verify_session_security() {
    // 1. Check if user is logged in at all.
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit;
    }

    // 2. Check for session hijacking: Compare current user info with info stored at login.
    $is_secure = true;
    if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
        $is_secure = false;
    }
    if (isset($_SESSION['user_ip']) && $_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
        $is_secure = false;
    }

    // 3. If the session is not secure, destroy it and force re-login.
    if (!$is_secure) {
        // Destroy all session data.
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        // Redirect to login page with an error message.
        header('Location: login.php?error=session_compromised');
        exit;
    }
}

// Immediately call the verification function when this file is included.
verify_session_security();
?>