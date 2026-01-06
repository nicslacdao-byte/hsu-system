<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSU Portal - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { --primary-blue: #1553be; --header-blue: #2b6bd8; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { height: 100vh; display: flex; background-color: var(--white); overflow: hidden; }
        .main-content { width: 100%; height: 100%; display: flex; flex-direction: column; position: relative; }
        .top-header { height: 100px; display: flex; align-items: center; justify-content: center; background-color: var(--white); z-index: 10; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .logo-group { display: flex; align-items: center; gap: 15px; }
        .logo-img { height: 65px; width: auto; }
        .header-text { display: flex; flex-direction: column; color: #002147; }
        .header-title { font-size: 1.6rem; font-weight: 700; font-family: serif; letter-spacing: 0.5px; }
        .header-subtitle { font-size: 0.9rem; font-weight: 600; }
        .auth-background { flex-grow: 1; background-size: cover; background-position: center; position: relative; display: flex; align-items: center; justify-content: flex-end; padding-right: 10%; transition: background-image 0.5s ease-in-out; }
        .auth-background::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to right, rgba(255,255,255,0.1), rgba(0,33,71,0.8)); }

        .auth-card { background-color: rgba(21, 83, 190, 0.95); width: 450px; border-radius: 20px; padding: 40px; color: white; position: relative; z-index: 20; box-shadow: 0 10px 30px rgba(0,0,0,0.3); border: 2px solid rgba(255,255,255,0.2); animation: slideIn 0.5s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }

        .user-type-selector { display: flex; justify-content: center; gap: 15px; margin-bottom: 25px; }
        .type-btn { padding: 8px 20px; border-radius: 20px; background-color: #aebcd6; color: #333; font-weight: 800; font-size: 0.85rem; cursor: pointer; border: none; transition: all 0.3s; text-transform: uppercase; }
        .type-btn.active { background-color: white; color: #000; transform: scale(1.05); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
        .auth-title-badge { background-color: #cdd6e5; color: black; font-weight: 900; font-size: 1.2rem; text-align: center; padding: 10px; border-radius: 30px; margin-bottom: 30px; text-transform: uppercase; width: 80%; margin-left: auto; margin-right: auto; }

        .input-group { margin-bottom: 20px; position: relative; }
        .form-input { width: 100%; padding: 15px 20px; padding-right: 45px; border-radius: 30px; border: none; background-color: #f0f4f8; font-size: 1rem; color: #333; outline: none; font-weight: 600; }
        .form-input::placeholder { color: #888; font-weight: 600; }
        .form-input:focus { box-shadow: 0 0 0 3px rgba(255,255,255,0.4); }

        .toggle-password { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #777; cursor: pointer; font-size: 1rem; z-index: 10; padding: 5px; }
        .toggle-password:hover { color: #1553be; }

        .btn-submit { width: 100%; padding: 15px; border-radius: 30px; border: none; background-color: #2b95e3; color: black; font-weight: 900; font-size: 1.2rem; cursor: pointer; transition: 0.3s; text-transform: uppercase; margin-top: 10px; display: flex; justify-content: center; align-items: center; gap: 10px; }
        .btn-submit:hover { background-color: #5bb3f0; }
        .btn-submit:disabled { background-color: #ccc; cursor: not-allowed; }

        .auth-footer { text-align: center; margin-top: 20px; font-size: 0.85rem; font-weight: 700; }
        .auth-footer a { color: white; text-decoration: underline; cursor: pointer; }

        .strength-container { margin-top: -10px; margin-bottom: 20px; padding: 0 10px; }
        .strength-bar { height: 5px; width: 0%; background: #ddd; border-radius: 5px; transition: width 0.3s, background 0.3s; }
        .strength-text { font-size: 0.7rem; font-weight: 700; margin-top: 5px; display: block; text-align: right; }

        .match-text { font-size: 0.75rem; font-weight: 700; position: absolute; right: 20px; top: 55px; opacity: 0; transition: opacity 0.3s; }

        .view-section { display: none; }
        .view-section.active-view { display: block; }

        @media (max-width: 768px) { .main-content { height: auto; min-height: 100vh; } .auth-background { justify-content: center; padding-right: 0; padding: 20px; align-items: center; } .auth-card { width: 100%; max-width: 400px; margin-top: 20px; padding: 30px 20px; } .top-header { height: 80px; padding: 0; justify-content: center; text-align: center; } .header-title { font-size: 1.2rem; } .header-subtitle { font-size: 0.7rem; } .logo-img { height: 50px; } }
    </style>
</head>
<body>

    <div class="main-content">
        <div class="top-header">
            <div class="logo-group">
                <img src="{{ asset('image/rtu_logo.png') }}" alt="RTU" class="logo-img">
                <img src="{{ asset('image/hsu_logo.png') }}" alt="HSU" class="logo-img">
                <div class="header-text">
                    <span class="header-title">HEALTH SERVICES UNIT</span>
                    <span class="header-subtitle">Rizal Technological University - Boni</span>
                </div>
            </div>
        </div>

        <div class="auth-background" id="bg-container">

            <div id="view-login" class="auth-card view-section active-view">
                <div class="user-type-selector">
                    <button type="button" class="type-btn active" onclick="setUserType('student', this)">STUDENT</button>
                    <button type="button" class="type-btn" onclick="setUserType('staff', this)">STAFF</button>
                    <button type="button" class="type-btn" onclick="setUserType('admin', this)">ADMIN</button>
                </div>

                <div class="auth-title-badge" id="login-title">STUDENT LOG IN</div>

                <form action="{{ url('/login') }}" method="POST" onsubmit="showLoading(this)">
                    @csrf
                    <input type="hidden" name="form_source" value="login">

                    <div class="input-group">
                        <input type="text" name="email" class="form-input" placeholder="Email Address or ID" required value="{{ old('email') }}">
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" class="form-input" id="login-pass" placeholder="Password" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('login-pass', this)"></i>
                    </div>

                    <input type="hidden" name="role" id="login-role-input" value="student">

                    <button type="submit" class="btn-submit">LOG IN</button>
                </form>

                <div class="auth-footer">
                    Forgot Password? <a href="#">CLICK HERE</a><br><br>
                    Don't have an account? <a onclick="switchView('register')">REGISTER here!</a>
                </div>
            </div>

            <div id="view-register" class="auth-card view-section">
                <div class="auth-title-badge" style="background-color: #cdd6e5;">REGISTER</div>

                <form action="{{ url('/register') }}" method="POST" onsubmit="showLoading(this)">
                    @csrf
                    <input type="hidden" name="role" id="register-role-input" value="student">

                    <input type="hidden" name="form_source" value="register">

                    <div class="input-group">
                        <input type="email" name="email" class="form-input" placeholder="Email Address" required value="{{ old('email') }}">
                    </div>

                    <div class="input-group">
                        <input type="password" name="password" class="form-input" placeholder="Password" id="reg-pass" onkeyup="checkPasswordStrength()" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('reg-pass', this)"></i>
                    </div>
                    <div class="strength-container">
                        <div class="strength-bar" id="strength-bar"></div>
                        <span class="strength-text" id="strength-text">Password Strength</span>
                    </div>

                    <div class="input-group" style="margin-bottom: 10px;">
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm Password" id="confirm-pass" onkeyup="checkMatch()" required>
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm-pass', this)"></i>
                        <span class="match-text" id="match-msg"></span>
                    </div>

                    <div style="font-size: 0.7rem; margin-bottom: 20px; font-weight: 600; color: #ddd; text-align: center;">
                        Must contain 1 uppercase letter, 1 special character, and be 8+ chars long.
                    </div>

                    <button type="submit" class="btn-submit" id="reg-btn">REGISTER</button>
                </form>

                <div class="auth-footer">
                    Already have an account? <a onclick="switchView('login')">LOG IN here!</a>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const loginBg = "url('{{ asset('image/backgroundlog.png') }}')";
        const registerBg = "url('{{ asset('image/backgroundreg.png') }}')";

        // Initialize Background
        document.getElementById('bg-container').style.backgroundImage = loginBg;

        function switchView(viewName) {
            document.querySelectorAll('.view-section').forEach(el => el.classList.remove('active-view'));
            document.getElementById('view-' + viewName).classList.add('active-view');

            const bgContainer = document.getElementById('bg-container');
            if(viewName === 'register') {
                bgContainer.style.backgroundImage = registerBg;
            } else {
                bgContainer.style.backgroundImage = loginBg;
            }
        }

        // --- NEW: AUTO SWITCH BACK TO REGISTER ON ERROR ---
        // This checks if the previous form submission was from "register"
        // and keeps you on the Register page so the "white box" makes sense.
        document.addEventListener("DOMContentLoaded", function() {
            @if(old('form_source') == 'register')
                switchView('register');
            @endif
        });

        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        let currentUserType = 'student';
        function setUserType(type, btnElement) {
            currentUserType = type;
            document.getElementById('login-title').innerText = type.toUpperCase() + " LOG IN";
            document.querySelectorAll('.type-btn').forEach(btn => btn.classList.remove('active'));
            btnElement.classList.add('active');
            document.getElementById('login-role-input').value = type;
            document.getElementById('register-role-input').value = type;
        }

        function checkPasswordStrength() {
            const password = document.getElementById('reg-pass').value;
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            let strength = 0;

            if (password.length > 7) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            if (strength === 0) { bar.style.width = '0%'; bar.style.background = '#ddd'; text.innerText = 'Password Strength'; }
            else if (strength <= 2) { bar.style.width = '30%'; bar.style.background = '#ff4d4d'; text.innerText = 'Weak'; text.style.color = '#ff9999'; }
            else if (strength === 3) { bar.style.width = '60%'; bar.style.background = '#ffd700'; text.innerText = 'Medium'; text.style.color = '#ffe066'; }
            else { bar.style.width = '100%'; bar.style.background = '#00d64f'; text.innerText = 'Strong'; text.style.color = '#80ffaa'; }
        }

        function checkMatch() {
            const pass = document.getElementById('reg-pass').value;
            const confirm = document.getElementById('confirm-pass').value;
            const msg = document.getElementById('match-msg');

            if (confirm.length > 0) {
                msg.style.opacity = '1';
                if (pass === confirm) {
                    msg.innerHTML = '<i class="fa-solid fa-check" style="color:#00d64f"></i> Match';
                } else {
                    msg.innerHTML = '<i class="fa-solid fa-xmark" style="color:#ff4d4d"></i> Mismatch';
                }
            } else {
                msg.style.opacity = '0';
            }
        }

        function showLoading(form) {
            const btn = form.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> PROCESSING...';
            btn.disabled = true;
            return true;
        }

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ $errors->first() }}',
                confirmButtonColor: '#1553be'
            });
        @endif
    </script>
</body>
</html>
