<?php
require 'function.php';

// cek login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // cocokkan dengan database,cari... ada atau gak tuh data
    $cekdatabase = mysqli_query($conn, "SELECT * FROM login where email='$email' and password='$password'");
    // hitung jumlah data
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $ambildata = mysqli_fetch_array($cekdatabase);
        $_SESSION['log'] = 'True';
        $_SESSION['role'] = $ambildata['role'];
        $_SESSION['email'] = $ambildata['email'];
        header('location:index.php');
    } else {
        header('location:login.php');
    }
};

if (!isset($_SESSION['log'])) {
} else {
    header('location:index.php');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - UKK Stok Barang</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/modern-styles.css" rel="stylesheet" />
    <link href="css/lemon-theme.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, #689f38 1px, transparent 1px);
            background-size: 50px 50px;
            animation: backgroundMove 20s linear infinite;
            opacity: 0.1;
        }

        @keyframes backgroundMove {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 0 20px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            border: 1px solid rgba(196, 214, 0, 0.1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #c4d600 0%, #a4c639 100%) !important;
            padding: 45px 30px;
            text-align: center;
            color: #1a1a1a !important;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(196, 214, 0, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .login-header i {
            font-size: 3.5rem;
            margin-bottom: 20px;
            opacity: 0.95;
            display: block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .login-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 2.2rem;
            letter-spacing: -0.5px;
            position: relative;
            z-index: 1;
        }

        .login-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
            font-weight: 400;
            position: relative;
            z-index: 1;
        }

        .login-body {
            padding: 40px 35px;
            background: white;
        }

        .form-group {
            margin-bottom: 28px;
            position: relative;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 10px;
            display: block;
            letter-spacing: 0.5px;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #c4d600;
            z-index: 10;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-control-modern {
            width: 100%;
            padding: 16px 18px 16px 50px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
            font-weight: 500;
        }

        .form-control-modern:focus {
            outline: none;
            border-color: #c4d600;
            background: white;
            box-shadow: 0 0 0 4px rgba(196, 214, 0, 0.25);
            transform: translateY(-2px);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #c4d600 0%, #a4c639 100%) !important;
            border: none;
            border-radius: 12px;
            color: #1a1a1a !important;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 15px;
            box-shadow: 0 8px 25px rgba(196, 214, 0, 0.3);
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(196, 214, 0, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(196, 214, 0, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(196, 214, 0, 0.3);
        }

        .forgot-password {
            text-align: center;
            margin-top: 25px;
        }

        .forgot-password a {
            color: #c4d600;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-password a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #689f38;
            transition: width 0.3s ease;
        }

        .forgot-password a:hover {
            color: #689f38;
        }

        .forgot-password a:hover::after {
            width: 100%;
        }

        .alert-modern {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .alert-danger-modern {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            color: #dc2626;
            border-left: 4px solid #dc2626;
            border: 1px solid #fecaca;
        }

        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
                padding: 0 15px;
            }

            .login-body {
                padding: 30px 25px;
            }

            .login-header {
                padding: 35px 25px;
            }

            .login-header h2 {
                font-size: 1.9rem;
            }

            .login-header i {
                font-size: 3rem;
            }

            .form-control-modern {
                padding: 14px 16px 14px 45px;
            }

            .btn-login {
                padding: 14px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 400px) {
            .login-header h2 {
                font-size: 1.7rem;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-body {
                padding: 25px 20px;
            }
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid transparent;
            border-top: 2px solid #1a1a1a;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-box-open"></i>
                <h2>PT. Bondvast</h2>
                <p>login untuk melanjutkan</p>
            </div>
            <div class="login-body">
                <form method="post">
                    <div class="form-group">
                        <label class="form-label" for="inputEmailAddress">Email Address</label>
                        <div class="input-group-custom">
                            <i class="fas fa-envelope"></i>
                            <input
                                class="form-control-modern"
                                name="email"
                                id="inputEmailAddress"
                                type="email"
                                placeholder="Enter email address"
                                required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="inputPassword">Password</label>
                        <div class="input-group-custom">
                            <i class="fas fa-lock"></i>
                            <input
                                class="form-control-modern"
                                name="password"
                                id="inputPassword"
                                type="password"
                                placeholder="Enter password"
                                required />
                        </div>
                    </div>

                    <button type="submit" class="btn-login" name="login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>