<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DAWN PUBLIC SCHOOL</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(9, 13, 26) 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient background glow */
        body::before {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.15) 0%, rgba(37, 99, 235, 0) 70%);
            top: -100px;
            right: -100px;
            z-index: 0;
        }
        body::after {
            content: "";
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.1) 0%, rgba(56, 189, 248, 0) 70%);
            bottom: -150px;
            left: -150px;
            z-index: 0;
        }
        
        .login-container {
            z-index: 10;
            width: 100%;
            max-width: 480px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 80px rgba(37, 99, 235, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
            position: relative;
            border-bottom: 4px solid #38bdf8;
        }
        
        .logo-seal {
            background: #ffffff;
            border-radius: 50%;
            padding: 5px;
            width: 90px;
            height: 90px;
            margin: 0 auto 15px auto;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #38bdf8;
        }
        
        .login-header h2 {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.45rem;
            margin: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .login-header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 6px 0 0 0;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .login-body {
            padding: 40px 35px;
        }
        
        .form-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.82rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .input-group {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            color: #64748b;
            padding-left: 16px;
            padding-right: 12px;
        }
        
        .form-control {
            padding: 12px 16px;
            font-size: 0.92rem;
            border: 1.5px solid #cbd5e1;
            background-color: #ffffff;
            color: #0f172a;
            font-weight: 500;
            transition: all 0.25s ease;
        }
        
        .form-control:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #2563eb;
            color: #2563eb;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 13px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            width: 100%;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.35);
        }
        
        .btn-login:active {
            transform: translateY(1px);
        }
        
        /* Glassmorphic credentials helper */
        .credentials-box {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            padding: 18px;
            margin-top: 30px;
            font-size: 0.82rem;
        }
        
        .credentials-title {
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .credentials-badge {
            cursor: pointer;
            padding: 3px 8px;
            background: #e2e8f0;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.78rem;
            transition: background 0.2s;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        
        .credentials-badge:hover {
            background: #cbd5e1;
            color: #0f172a;
        }
        
        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .alert {
            border-radius: 10px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card">
            <!-- HEADER -->
            <div class="login-header">
                <div class="logo-seal">
                    <img src="{{ asset('images/logo.jpg') }}" alt="School Logo" style="width: 100%; height: 100%; object-fit: contain; border-radius: 50%;">
                </div>
                <h2>DAWN PUBLIC SCHOOL</h2>
                <p>SUPER DAWN SCHOOL SYSTEM LAKHI</p>
            </div>
            
            <!-- BODY -->
            <div class="login-body">
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm py-2 px-3 mb-4 text-start" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm py-2 px-3 mb-4" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control border-start-0" placeholder="admin@superdawn.com" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label small text-muted">Remember Session</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login"><i class="fa-solid fa-right-to-bracket me-2"></i>Sign In to Portal</button>
                </form>

                <!-- DEMO CREDENTIALS BOX -->
                <div class="credentials-box">
                    <div class="credentials-title">
                        <i class="fa-solid fa-key text-warning"></i>
                        <span>Click to Copy Demo Credentials</span>
                    </div>
                    <table class="w-100 text-muted" style="border-collapse: separate; border-spacing: 0 6px;">
                        <tr>
                            <td><strong>Super Admin:</strong></td>
                            <td class="text-end">
                                <span class="credentials-badge" onclick="fillCreds('superadmin@superdawn.com')">superadmin@superdawn.com</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Admin:</strong></td>
                            <td class="text-end">
                                <span class="credentials-badge" onclick="fillCreds('admin@superdawn.com')">admin@superdawn.com</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Accountant:</strong></td>
                            <td class="text-end">
                                <span class="credentials-badge" onclick="fillCreds('accountant@superdawn.com')">accountant@superdawn.com</span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Teacher:</strong></td>
                            <td class="text-end">
                                <span class="credentials-badge" onclick="fillCreds('teacher@superdawn.com')">teacher@superdawn.com</span>
                            </td>
                        </tr>
                    </table>
                    <div class="text-center text-muted mt-2" style="font-size: 0.72rem;">
                        Default Password: <code class="text-primary font-weight-bold">password</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fillCreds(email) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = 'password';
            
            // Add subtle click highlight animation
            const el = event.currentTarget;
            el.style.backgroundColor = '#2563eb';
            el.style.color = '#ffffff';
            setTimeout(() => {
                el.style.backgroundColor = '';
                el.style.color = '';
            }, 300);
        }
    </script>
</body>
</html>
