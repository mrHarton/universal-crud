<?php
class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['is_admin'] = true;
                header('Location: /admin/dashboard');
                exit;
            }

            echo "Invalid credentials";
        }

        require __DIR__ . '/../Views/login.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: /auth/login');
    }
}
