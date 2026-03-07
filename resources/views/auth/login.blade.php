<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Codeingil IoT Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* RESET */
        *{box-sizing:border-box;margin:0;padding:0}
        html,body{
            height:100%;
            overflow:hidden; /* 🔥 NO SCROLL */
            font-family:Inter,system-ui,sans-serif;
        }

        :root{
            --yellow:#facc15;
            --yellow-dark:#f59e0b;
            --black:#020617;
            --bg:#f8fafc;
            --card:#ffffff;
            --border:#e5e7eb;
            --text:#0f172a;
            --muted:#64748b;
            --shadow:0 30px 60px rgba(2,6,23,.18);
        }

        body{
            background:var(--bg);
            color:var(--text);
        }

        /* ================= LAYOUT ================= */
        .login-wrapper{
            height:100vh;
            display:flex;
        }

        /* ================= LEFT ================= */
        .login-left{
            width:55%;
            padding:70px;
            background:linear-gradient(135deg,#fde047,#facc15,#f59e0b);
            display:flex;
            flex-direction:column;
            justify-content:center;
            gap:34px;
            
        }

        .brand{
            font-size:22px;
            font-weight:900;
            letter-spacing:.5px;
        }

        .hero-title{
            font-size:42px;
            line-height:1.15;
            font-weight:900;
            max-width:520px;
        }

        .hero-desc{
            font-size:17px;
            max-width:480px;
            line-height:1.6;
        }

        .stats{
            display:flex;
            gap:20px;
        }

        .stat-box{
            background:linear-gradient(180deg,#020617,#020617ee);
            color:#fff;
            padding:22px 26px;
            border-radius:18px;
            min-width:200px;
            box-shadow:var(--shadow);
        }

        .stat-box span{
            font-size:13px;
            color:#cbd5f5;
        }

        .stat-box h3{
            margin-top:6px;
            font-size:28px;
            font-weight:800;
        }

        .trust{
            background:#020617;
            color:#fff;
            padding:26px;
            border-radius:20px;
            max-width:420px;
            box-shadow:var(--shadow);
        }

        .trust small{
            color:#cbd5f5;
        }

        .trust h4{
            margin-top:6px;
            font-size:20px;
        }

        /* ================= RIGHT ================= */
        .login-right{
            width:45%;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .login-card{
            width:420px;
            background:var(--card);
            padding:38px;
            border-radius:22px;
            box-shadow:var(--shadow);
            animation:fadeUp .6s ease;
        }

        @keyframes fadeUp{
            from{opacity:0;transform:translateY(20px)}
            to{opacity:1;transform:none}
        }

        .login-card h2{
            font-size:28px;
            font-weight:800;
            margin-bottom:6px;
        }

        .login-card p{
            color:var(--muted);
            margin-bottom:28px;
        }

        label{
            font-size:14px;
            font-weight:600;
            display:block;
            margin-bottom:6px;
        }

        .input{
            width:100%;
            padding:14px 16px;
            border-radius:12px;
            border:1px solid var(--border);
            margin-bottom:16px;
            font-size:14px;
        }

        .input:focus{
            outline:none;
            border-color:var(--yellow-dark);
            box-shadow:0 0 0 3px rgba(250,204,21,.35);
        }

        .btn{
            width:100%;
            padding:15px;
            border-radius:999px;
            border:none;
            background:linear-gradient(135deg,#020617,#000);
            color:#fff;
            font-weight:700;
            cursor:pointer;
            transition:.25s;
        }

        .btn:hover{
            transform:translateY(-1px);
            box-shadow:0 12px 26px rgba(2,6,23,.35);
        }

        .error{
            margin-top:14px;
            color:#dc2626;
            font-size:14px;
        }

        /* ================= RESPONSIVE ================= */
        @media(max-width:1000px){
            .login-left{display:none}
            .login-right{width:100%}
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <!-- LEFT -->
    <div class="login-left">
        <div class="brand">codeingil.</div>

        <h1 class="hero-title">
            Supercharge Your IoT Platform
        </h1>

        <p class="hero-desc">
            Secure, scalable and real-time IoT dashboards to manage devices,
            sensors and users efficiently.
        </p>

        <div class="stats">
            <div class="stat-box">
                <span>Devices Connected</span>
                <h3>250+</h3>
            </div>
            <div class="stat-box">
                <span>System Uptime</span>
                <h3>99.9%</h3>
            </div>
        </div>

        <div class="trust">
            <small>Trusted by</small>
            <h4>Industries & Smart Systems</h4>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="login-right">
        <div class="login-card">

            <h2>Welcome Back</h2>
            <p>Sign in to your IoT admin dashboard</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label>Email</label>
                <input type="email" name="email" class="input" required>

                <label>Password</label>
                <input type="password" name="password" class="input" required>

                <button class="btn">Login</button>
            </form>

            @if($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

        </div>
    </div>

</div>

</body>
</html>
