<?php
// AdminAuth helper class
// Provides authorization checks for admin-only routes and actions.

class AdminAuth {

    /**
     * Require admin privileges
     * Checks if user is authenticated and has admin flag set to 1.
     * If not authenticated or not admin, flashes error and redirects.
     */
    public static function requireAdmin() {
        // First check if user is logged in at all
        if (!Auth::check()) {
            Session::flash('error', 'Please login to access admin area');
            header('Location: /Ecommerce_final_project/public/login.php');
            exit();
        }

        // Get database connection to check user's admin status
        $db = getDatabaseConnection();
        $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([Auth::id()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify user exists and has admin privileges (is_admin = 1)
        if (!$user || $user['is_admin'] != 1) {
            Session::flash('error', 'Access denied. Admin privileges required.');
            header('Location: /Ecommerce_final_project/public/index.php');
            exit();
        }
    }

    /**
     * Check if current user is admin
     * Returns true if authenticated user has admin flag, false otherwise.
     * Used for conditional rendering of admin UI elements.
     * @return bool True if user is admin, false otherwise
     */
    public static function isAdmin() {
        // Return false immediately if user is not authenticated
        if (!Auth::check()) {
            return false;
        }

        // Query database for user's admin status
        $db = getDatabaseConnection();
        $stmt = $db->prepare("SELECT is_admin FROM users WHERE id = ?");
        $stmt->execute([Auth::id()]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return true only if user exists and has admin privileges (is_admin = 1)
        return $user && $user['is_admin'] == 1;
    }
}
