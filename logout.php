<?php
// Start session
session_start();

// Destroy all session data
$_SESSION = array();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// CRITICAL: Set a logout flag cookie
setcookie('logout_flag', '1', time() + 10, '/');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signing out...</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(135deg, #DC2626, #991B1B);
            color: white;
            margin: 0;
        }
        .logout-message {
            text-align: center;
        }
        .spinner {
            width: 50px;
            height: 50px;
            margin: 20px auto;
            border: 5px solid rgba(255,255,255,0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h2 {
            margin: 10px 0;
            font-size: 24px;
        }
        p {
            margin: 5px 0;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="logout-message">
        <div class="spinner"></div>
        <h2>Signing out...</h2>
        <p>Please wait</p>
    </div>
    
    <script>
        // CRITICAL: Aggressive cleanup and redirect
        (function() {
            console.log('🔴 LOGOUT: Starting cleanup...');
            
            // 1. Clear ALL storage FIRST
            try {
                localStorage.clear();
                sessionStorage.clear();
                console.log('✅ Storage cleared');
            } catch (e) {
                console.error('❌ Storage clear error:', e);
            }
            
            // 2. Clear ALL cookies
            document.cookie.split(";").forEach(function(c) { 
                document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
            });
            console.log('✅ Cookies cleared');
            
            // 3. Prevent back button
            history.pushState(null, '', location.href);
            window.onpopstate = function() {
                history.pushState(null, '', location.href);
            };
            
            // 4. Remove ALL event listeners
            window.onbeforeunload = null;
            
            // 5. FORCE redirect with location.replace (no history entry)
            setTimeout(function() {
                console.log('🔴 LOGOUT: Redirecting to auth.php...');
                
                // Use multiple methods to ensure redirect works
                window.location.replace('auth.php?logout=1&t=' + Date.now());
                
                // Backup redirect in case replace fails
                setTimeout(function() {
                    window.location.href = 'auth.php?logout=1&t=' + Date.now();
                }, 500);
                
                // Final fallback - hard reload to auth page
                setTimeout(function() {
                    window.location = 'auth.php?logout=1&t=' + Date.now();
                }, 1000);
            }, 300);
            
        })();
    </script>
</body>
</html>