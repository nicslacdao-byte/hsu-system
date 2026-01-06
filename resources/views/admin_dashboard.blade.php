<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSU Admin Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* --- BRANDING & RESET --- */
        :root { --primary-blue: #1553be; --header-blue: #2b6bd8; --soft-blue: #7ba2e6; --white: #ffffff; --text-dark: #333; --sidebar-width: 260px; --sidebar-collapsed: 80px; --transition: 0.3s; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--white); height: 100vh; display: flex; overflow: hidden; }

        /* SIDEBAR (Matches Student Portal) */
        .sidebar { width: var(--sidebar-width); background-color: var(--primary-blue); color: var(--white); display: flex; flex-direction: column; justify-content: space-between; transition: width var(--transition) ease; position: relative; z-index: 100; }
        .sidebar-header { height: 100px; display: flex; align-items: center; justify-content: center; background-color: var(--header-blue); border-top-right-radius: 50px; }
        .burger-menu { font-size: 1.8rem; cursor: pointer; }
        .nav-links { list-style: none; padding-top: 20px; flex-grow: 1; overflow-y: auto; }
        .nav-links li { margin-bottom: 10px; }
        .nav-link { text-decoration: none; color: var(--white); display: flex; align-items: center; padding: 12px 25px; font-weight: 600; font-size: 0.9rem; white-space: nowrap; transition: background 0.2s; cursor: pointer; }
        .nav-link:hover, .nav-link.active-tab { background-color: rgba(255, 255, 255, 0.2); border-left: 5px solid white; }
        .nav-links i { font-size: 1.4rem; width: 40px; text-align: center; }
        .link-text { margin-left: 10px; transition: opacity 0.2s; }
        .user-profile { padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; background-color: var(--primary-blue); }
        .avatar { width: 45px; height: 45px; background-color: #ddd; border-radius: 50%; margin-right: 15px; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; color: #555; overflow: hidden; }
        .user-info { overflow: hidden; }
        .user-name { font-weight: 800; font-size: 0.85rem; text-transform: uppercase; line-height: 1.2; margin-bottom: 2px; }
        .user-role { font-size: 0.7rem; opacity: 0.8; }
        .logout-btn { margin-left: auto; font-size: 1.2rem; cursor: pointer; }

        /* MAIN CONTENT & HEADER */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; background-color: #f4f6f9; overflow-y: auto; }
        .top-header { height: 100px; background-color: var(--white); position: relative; flex-shrink: 0; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .header-logos { position: fixed; top: 0; left: 55%; transform: translateX(-50%); height: 100px; display: flex; align-items: center; gap: 5px; z-index: 90; pointer-events: none; }
        .logo-img { height: 55px; width: auto; }
        .header-text { display: flex; flex-direction: column; color: #002147; margin-left: -2px; }
        .header-title { font-size: 1.4rem; font-weight: 700; font-family: serif; letter-spacing: 0.5px; }
        .header-subtitle { font-size: 0.85rem; font-weight: 600; }

        .content-body { padding: 30px 50px; }
        .welcome-title { font-size: 1.8rem; font-weight: 800; color: #000; margin-bottom: 25px; }

        .page-section { display: none; animation: fadeIn 0.4s; }
        .page-section.active-section { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* EXISTING STYLES */
        .grid-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 25px; }
        .card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: fit-content; }
        .card-header { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; color: #1553be; }
        .form-group { margin-bottom: 15px; }
        .form-label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #555; }
        .form-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; outline: none; }
        .form-input:focus { border-color: #1553be; box-shadow: 0 0 0 2px rgba(21, 83, 190, 0.1); }
        .btn-create { width: 100%; padding: 12px; background-color: #1553be; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-create:hover { background-color: #0d3c94; }
        .table-container { margin-bottom: 30px; }
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .admin-table th { text-align: left; padding: 12px; background-color: #f8f9fa; color: #555; font-size: 0.85rem; font-weight: 700; border-bottom: 2px solid #eee; }
        .admin-table td { padding: 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; color: #333; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
        .badge-staff { background: #dbeafe; color: #1e40af; }
        .badge-student { background: #dcfce7; color: #166534; }
        .btn-delete { color: #dc2626; font-weight: 600; text-decoration: none; font-size: 0.85rem; border: 1px solid #fee2e2; padding: 4px 10px; border-radius: 5px; background: #fef2f2; }
        .btn-delete:hover { background: #dc2626; color: white; border-color: #dc2626; }

        /* --- CLINIC MANAGEMENT STYLES --- */
        .schedule-container { background: #7aa0e6; padding: 20px; border-radius: 15px; border: 2px solid #5a85d6; color: #333; }
        .schedule-table { width: 100%; border-collapse: separate; border-spacing: 5px; }
        .schedule-table th { background: white; padding: 15px; text-align: center; font-weight: 800; border-radius: 5px; }
        .schedule-row-header { background: white; padding: 15px; font-weight: 800; border-radius: 5px; width: 200px; text-transform: uppercase; cursor: pointer; }
        .schedule-row-header:hover { background: #f0f0f0; color: #1553be; }

        .time-slot {
            background: #d1fae5; color: #065f46; padding: 15px 5px; text-align: center; border-radius: 5px;
            font-size: 0.8rem; font-weight: 700; cursor: pointer; transition: 0.2s; height: 100%; display: flex; align-items: center; justify-content: center;
        }
        .time-slot:hover { transform: scale(1.05); box-shadow: 0 2px 5px rgba(0,0,0,0.2); border: 2px solid #1553be; }
        .time-slot.is-off { background: #dc2626; color: white; }

        body.collapsed .sidebar { width: var(--sidebar-collapsed); }
        body.collapsed .link-text, body.collapsed .user-info, body.collapsed .user-role { display: none; }
        body.collapsed .burger-menu { margin-right: 0; }
        body.collapsed .user-profile { justify-content: center; padding: 20px 0; }
        body.collapsed .header-logos { left: 50%; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="burger-menu" id="burgerBtn"><i class="fa-solid fa-bars"></i></div>
        </div>
        <ul class="nav-links">
            <li><div class="nav-link active-tab" onclick="showPage('user-management', this)"><i class="fa-solid fa-users-gear"></i><span class="link-text">USER MANAGEMENT</span></div></li>
            <li><div class="nav-link" onclick="showPage('clinic-management', this)"><i class="fa-solid fa-house-medical"></i><span class="link-text">CLINIC MANAGEMENT</span></div></li>
        </ul>

        <div class="user-profile">
            <div class="avatar"><i class="fa-solid fa-user-shield"></i></div>
            <div class="user-info">
                <div class="user-name">Administrator</div>
                <div class="user-role">(SYSTEM ADMIN)</div>
            </div>

            <form action="{{ route('logout') }}" method="POST" id="logout-form">
                @csrf
                <div class="logout-btn" onclick="document.getElementById('logout-form').submit()"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="top-header">
            <div class="header-logos">
                <img src="{{ asset('image/rtu_logo.png') }}" alt="RTU Logo" class="logo-img">
                <img src="{{ asset('image/hsu_logo.png') }}" alt="HSU Logo" class="logo-img">
                <div class="header-text">
                    <span class="header-title">HEALTH SERVICES UNIT</span>
                    <span class="header-subtitle">Rizal Technological University - Boni</span>
                </div>
            </div>
        </div>

        <div class="content-body">

            <div id="user-management" class="page-section active-section">
                <h1 class="welcome-title">User Management Portal</h1>

                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="grid-layout">
                    <div>
                        <div class="card">
                            <div class="card-header"><i class="fa-solid fa-user-plus"></i> Create Staff Account</div>
                            <form action="{{ url('/admin/create-staff') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Username / Email</label>
                                    <input type="text" name="email" class="form-input" placeholder="e.g. nurse.joy" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input type="text" name="password" class="form-input" placeholder="Enter password" required>
                                </div>
                                <button type="submit" class="btn-create">Create Account</button>
                            </form>
                        </div>
                    </div>

                    <div>
                        <div class="card table-container">
                            <div class="card-header" style="color: #1e40af; border-color: #dbeafe;">
                                <i class="fa-solid fa-user-nurse"></i> Staff Accounts
                            </div>
                            <table class="admin-table">
                                <thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Created</th><th>Action</th></tr></thead>
                                <tbody>
                                    @forelse($staffMembers as $staff)
                                    <tr>
                                        <td>{{ $staff->id }}</td>
                                        <td style="font-weight: 600;">{{ $staff->email }}</td>
                                        <td><span class="badge badge-staff">Staff</span></td>
                                        <td>{{ $staff->created_at->format('M d, Y') }}</td>
                                        <td><a href="{{ url('/admin/delete-user/'.$staff->id) }}" class="btn-delete" onclick="return confirm('Delete this staff?')">Delete</a></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" style="text-align:center; color:#999;">No staff accounts found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card table-container">
                            <div class="card-header" style="color: #166534; border-color: #dcfce7;">
                                <i class="fa-solid fa-graduation-cap"></i> Student Accounts
                            </div>
                            <table class="admin-table">
                                <thead><tr><th>ID</th><th>Email</th><th>Role</th><th>Created</th><th>Action</th></tr></thead>
                                <tbody>
                                    @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->id }}</td>
                                        <td style="font-weight: 600;">{{ $student->email }}</td>
                                        <td><span class="badge badge-student">Student</span></td>
                                        <td>{{ $student->created_at->format('M d, Y') }}</td>
                                        <td><a href="{{ url('/admin/delete-user/'.$student->id) }}" class="btn-delete" onclick="return confirm('Delete this student?')">Delete</a></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" style="text-align:center; color:#999;">No student accounts found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="clinic-management" class="page-section">
                <h1 class="welcome-title">CLINIC MANAGEMENT</h1>

                <div class="schedule-container">
                    <div style="background: white; padding: 10px 20px; border-radius: 30px; margin-bottom: 20px; display: inline-block; font-weight: 800; color: #555;">
                        <i class="fa-solid fa-pen-to-square"></i> Click name to edit Staff Name | Click slot to edit Schedule
                    </div>

                    <table class="schedule-table">
                        <thead>
                            <tr>
                                <th>STAFF NAME</th>
                                <th>MON</th>
                                <th>TUE</th>
                                <th>WED</th>
                                <th>THU</th>
                                <th>FRI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffMembers as $staff)
                            <tr>
                                <td class="schedule-row-header" onclick="editStaffName({{ $staff->id }}, '{{ $staff->name ?? $staff->email }}')">
                                    {{ $staff->name ?? $staff->email }} <i class="fa-solid fa-pen" style="font-size: 0.8rem; margin-left: 5px; opacity: 0.5;"></i>
                                </td>

                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $day)
                                    @php
                                        // Retrieve schedule for this user on this day
                                        $sched = $schedules->where('user_id', $staff->id)->where('day', $day)->first();
                                        $isOff = $sched ? $sched->is_off : false;
                                        $text = ($sched && !$isOff) ? ($sched->start_time . ' - ' . $sched->end_time) : '8:00 AM - 5:00 PM';
                                        $display = $isOff ? 'OFF' : $text;
                                        $class = $isOff ? 'is-off' : '';
                                    @endphp
                                    <td>
                                        <div class="time-slot {{ $class }}"
                                             onclick="editSchedule({{ $staff->id }}, '{{ $day }}', '{{ $sched->start_time ?? '08:00' }}', '{{ $sched->end_time ?? '17:00' }}', {{ $isOff ? 'true' : 'false' }})">
                                            {{ $display }}
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const burgerBtn = document.getElementById('burgerBtn');
        const body = document.body;
        burgerBtn.addEventListener('click', () => { body.classList.toggle('collapsed'); });

        function showPage(pageId, clickedLink) {
            document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active-section'));
            document.getElementById(pageId).classList.add('active-section');
            if (clickedLink) {
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active-tab'));
                clickedLink.classList.add('active-tab');
            }
        }

        // --- NEW CODE ADDED HERE: SCHEDULE EDIT LOGIC ---
        function editSchedule(userId, day, start, end, isOff) {
            Swal.fire({
                title: `Edit ${day} Schedule`,
                html: `
                    <div style="text-align: left;">
                        <label>Status:</label>
                        <select id="swal-status" class="swal2-input" onchange="toggleInputs(this.value)">
                            <option value="active" ${!isOff ? 'selected' : ''}>Active</option>
                            <option value="off" ${isOff ? 'selected' : ''}>OFF</option>
                        </select>
                        <div id="time-box" style="display: ${isOff ? 'none' : 'block'}">
                            <label>Start (e.g. 8:00 AM):</label>
                            <input id="swal-start" class="swal2-input" value="${start}">
                            <label>End (e.g. 5:00 PM):</label>
                            <input id="swal-end" class="swal2-input" value="${end}">
                        </div>
                    </div>
                `,
                confirmButtonText: 'Save Schedule',
                showCancelButton: true,
                preConfirm: () => {
                    return {
                        is_off: document.getElementById('swal-status').value === 'off',
                        start: document.getElementById('swal-start').value,
                        end: document.getElementById('swal-end').value
                    }
                }
            }).then((result) => {
                if(result.isConfirmed) {
                    fetch('/admin/update-schedule', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            user_id: userId,
                            day: day,
                            start_time: result.value.start,
                            end_time: result.value.end,
                            is_off: result.value.is_off
                        })
                    }).then(res => res.json())
                      .then(() => { Swal.fire('Saved!', '', 'success').then(() => location.reload()); });
                }
            });
        }

        function toggleInputs(val) { document.getElementById('time-box').style.display = val === 'off' ? 'none' : 'block'; }

       function editStaffName(userId, currentName) {
        Swal.fire({
            title: 'Edit Staff Name',
            input: 'text',
            inputValue: currentName,
            showCancelButton: true,
            confirmButtonText: 'Save Name',
            inputPlaceholder: 'Enter full name (e.g. Dr. Joy)',
            showLoaderOnConfirm: true,
            preConfirm: (newName) => {
                if (!newName) {
                    Swal.showValidationMessage('Name cannot be empty');
                    return false;
                }
                return fetch('/admin/update-staff-name', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json' // Forces JSON response
                    },
                    body: JSON.stringify({ user_id: userId, name: newName })
                })
                .then(async response => {
                    if (!response.ok) {
                        // If server error, read the text to see what happened
                        const text = await response.text();
                        console.error("Server Error:", text); // Check console (F12) for details
                        throw new Error("Server Error (Check Console)");
                    }
                    return response.json();
                })
                .catch(error => Swal.showValidationMessage(`Request failed: ${error}`));
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Updated!', 'Staff name has been changed.', 'success')
                .then(() => location.reload());
            }
        });
    }
    </script>
</body>
</html>
