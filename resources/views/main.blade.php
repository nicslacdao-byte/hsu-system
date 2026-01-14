<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSU System</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- 1. VARIABLES & RESET --- */
        :root { --primary-blue: #1553be; --header-blue: #2b6bd8; --soft-blue: #7ba2e6; --white: #ffffff; --text-dark: #333; --sidebar-width: 260px; --sidebar-collapsed: 80px; --transition: 0.3s; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--white); height: 100vh; display: flex; overflow: hidden; }

        /* SIDEBAR */
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

        /* MAIN CONTENT */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; background-color: var(--white); overflow-y: auto; }

        /* FIXED HEADER STYLE */
        .top-header {
            height: 100px;
            background-color: var(--white);
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .header-logos {
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: none;
        }
        .logo-img { height: 55px; width: auto; }
        .header-text { display: flex; flex-direction: column; color: #002147; margin-left: -2px; }
        .header-title { font-size: 1.4rem; font-weight: 700; font-family: serif; letter-spacing: 0.5px; }
        .header-subtitle { font-size: 0.85rem; font-weight: 600; }
        .content-body { padding: 30px 50px; }

        /* VIEWS */
        .page-section { display: none; animation: fadeIn 0.4s; }
        .page-section.active-section { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* DASHBOARD */
        .welcome-title { font-size: 1.8rem; font-weight: 800; color: #000; margin-bottom: 25px; }
        .action-cards-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .action-card { background-color: var(--soft-blue); border-radius: 20px; padding: 20px; display: flex; align-items: center; color: white; cursor: pointer; transition: transform 0.2s; box-shadow: 0 4px 10px rgba(118, 161, 230, 0.3); }
        .action-card:hover { transform: translateY(-5px); }
        .card-icon-box { background-color: #2b3a55; width: 50px; height: 50px; border-radius: 12px; display: flex; justify-content: center; align-items: center; margin-right: 15px; border: 2px solid white; }
        .card-icon-box i { font-size: 1.5rem; }
        .card-text { display: flex; flex-direction: column; }
        .card-label { font-size: 0.8rem; opacity: 0.9; }
        .card-main-text { font-size: 1.1rem; font-weight: 700; }

        /* UPCOMING APPOINTMENT ALERT */
        .upcoming-alert {
            background: linear-gradient(135deg, #1553be 0%, #2b6bd8 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(21, 83, 190, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            border: 2px solid #6b8cce;
        }
        .upcoming-alert::before {
            content: '\f073';
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: -20px;
            bottom: -30px;
            font-size: 10rem;
            opacity: 0.1;
            transform: rotate(-20deg);
            pointer-events: none;
        }
        .ua-details h3 { margin-bottom: 5px; font-size: 1.6rem; font-weight: 800; }
        .ua-info { font-size: 1.1rem; opacity: 0.95; margin-bottom: 5px; font-weight: 500; }
        .ua-time { font-size: 1rem; font-weight: 700; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 5px; display: inline-block; }
        .ua-btn {
            background: white; color: #1553be; padding: 12px 25px; border-radius: 30px;
            font-weight: 800; text-transform: uppercase; cursor: pointer; text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); transition: 0.2s; z-index: 2;
        }
        .ua-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.3); }

        .page-header { font-size: 2rem; color: var(--primary-blue); margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }

        /* BOOKING & OTHER STYLES */
        .blue-main-card { background-color: var(--soft-blue); border-radius: 20px; padding: 30px; color: white; min-height: 500px; position: relative; }
        .timeline-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 30px 0 50px 0; position: relative; width: 100%; }
        .timeline-container.three-steps { grid-template-columns: repeat(3, 1fr); }
        .timeline-line { position: absolute; top: 50%; left: 12%; right: 12%; height: 3px; background-color: rgba(255, 255, 255, 0.5); z-index: 1; transform: translateY(-50%); }
        .timeline-step { width: 40px; height: 40px; background-color: #1553be; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 1.1rem; border: 3px solid white; z-index: 2; justify-self: center; transition: all 0.3s; }
        .timeline-step.active-step { background-color: #00d64f; border-color: white; box-shadow: 0 0 10px white; }
        .services-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; margin-bottom: 20px; max-width: 800px; margin-left: auto; margin-right: auto; }
        .service-btn { background-color: #0d3c94; border: 2px solid transparent; border-radius: 30px; padding: 20px; text-align: center; color: white; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; min-height: 100px; font-size: 1.1rem; }
        .service-btn:hover { transform: translateY(-3px); background-color: #1553be; border-color: white; }

        /* REQ & FORM Styles */
        .req-section-header { font-weight: 800; font-size: 1.1rem; margin-bottom: 15px; text-transform: uppercase; display: flex; align-items: center; gap: 10px; }
        .req-details-text { font-size: 0.9rem; margin-bottom: 20px; line-height: 1.5; font-weight: 500; }
        .req-lists-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 25px; }
        .toggle-label { display: flex; align-items: flex-start; cursor: pointer; margin-bottom: 12px; font-weight: 600; font-size: 0.95rem; text-transform: uppercase; padding: 8px; border-radius: 8px; transition: background 0.2s; border: 1px solid rgba(255,255,255,0.2); }
        .toggle-label:hover { background-color: rgba(255,255,255,0.1); }
        .toggle-input { display: none; }
        .custom-check { width: 24px; height: 24px; border: 2px solid white; border-radius: 5px; margin-right: 15px; margin-top: -2px; display: flex; justify-content: center; align-items: center; transition: all 0.2s; background: rgba(0,0,0,0.1); }
        .toggle-input:checked + .custom-check { background-color: #00d64f; border-color: #00d64f; }
        .custom-check i { display: none; color: white; font-size: 0.9rem; }
        .toggle-input:checked + .custom-check i { display: block; }
        .warning-box { background-color: rgba(255, 255, 255, 0.2); padding: 15px; border-radius: 10px; display: flex; align-items: flex-start; gap: 15px; margin-top: 20px; }
        .warning-icon { font-size: 2.5rem; color: #ffcc00; }
        .warning-text { font-size: 0.9rem; font-weight: 700; font-style: italic; }

        .form-header { text-align: center; margin-bottom: 30px; }
        .form-title { font-size: 1.8rem; font-weight: 800; text-transform: uppercase; margin-bottom: 5px; }
        .form-subtitle { font-style: italic; font-size: 0.9rem; opacity: 0.9; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full-width { grid-column: span 2; }
        .form-label { font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; }
        .form-input, .form-select { padding: 12px; border-radius: 5px; border: none; font-size: 1rem; color: #333; outline: none; }
        .form-input:focus, .form-select:focus { box-shadow: 0 0 0 3px rgba(255,255,255,0.5); }
        .uppercase-input { text-transform: uppercase; }

        /* CALENDAR */
        .scheduling-layout { display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px; align-items: start; }
        .step-label { font-size: 2rem; font-weight: 800; margin-bottom: 20px; }
        .calendar-box { background: white; border-radius: 15px; overflow: hidden; color: #333; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .calendar-header { background-color: #1553be; color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; font-weight: bold; font-size: 1.2rem; }
        .calendar-controls i { cursor: pointer; padding: 5px 10px; transition: 0.2s; }
        .calendar-controls i:hover { color: #00d64f; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); padding: 15px; text-align: center; gap: 5px; }
        .cal-day-name { font-weight: 700; color: #888; font-size: 0.85rem; margin-bottom: 10px; text-transform: uppercase; }
        .cal-date { height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; cursor: pointer; font-weight: 600; transition: 0.2s; }
        .cal-date:not(.disabled):hover { background-color: #eee; }
        .cal-date.selected-date { background-color: #1553be; color: white; transform: scale(1.1); box-shadow: 0 4px 10px rgba(21, 83, 190, 0.4); }
        .cal-date.disabled { color: #ccc; cursor: default; pointer-events: none; }
        .cal-date.today { border: 2px solid #1553be; }
        .time-slot-box { color: white; background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; }
        .time-select { width: 100%; padding: 15px; border-radius: 10px; border: none; outline: none; font-size: 1.1rem; font-weight: bold; margin-top: 10px; cursor: pointer; color: #333; }
        .btn-action { margin-top: 20px; padding: 12px 30px; border-radius: 10px; border: none; cursor: pointer; font-weight: bold; font-size: 1rem; transition: 0.2s; }
        .btn-action:hover { transform: translateY(-2px); }
        .btn-back { background: white; color: var(--primary-blue); }
        .btn-next { background: #00d64f; color: white; float: right; box-shadow: 0 5px 15px rgba(0, 214, 79, 0.3); }
        .btn-cancel { background: #ff4d4d; color: white; padding: 5px 15px; border-radius: 5px; border: none; cursor: pointer; font-weight: bold; font-size: 0.8rem; }
        .btn-cancel:hover { background: #cc0000; }
        .sub-view { display: none; }
        .sub-view.active-view { display: block; animation: fadeIn 0.3s; }

        body.collapsed .sidebar { width: var(--sidebar-collapsed); }
        body.collapsed .link-text, body.collapsed .user-info, body.collapsed .user-role { display: none; }
        body.collapsed .burger-menu { margin-right: 0; }
        body.collapsed .user-profile { justify-content: center; padding: 20px 0; }
        body.collapsed .header-logos { left: 50%; }

        /* Records Table */
        .records-table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .records-table th { background-color: #1553be; color: white; padding: 15px; text-align: left; font-size: 0.9rem; }
        .records-table td { padding: 15px; border-bottom: 1px solid #eee; color: #333; font-size: 0.9rem; }
        .records-table tr:last-child td { border-bottom: none; }
        .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .status-active { background-color: #d1fae5; color: #065f46; }

        /* Schedule Styles */
        .schedule-container { background: #7aa0e6; padding: 20px; border-radius: 15px; border: 2px solid #5a85d6; color: #333; margin-top: 30px; }
        .schedule-table { width: 100%; border-collapse: separate; border-spacing: 5px; }
        .schedule-table th { background: white; padding: 15px; text-align: center; font-weight: 800; border-radius: 5px; }
        .schedule-row-header { background: white; padding: 15px; font-weight: 800; border-radius: 5px; width: 200px; text-transform: uppercase; }
        .time-slot {
            background: #d1fae5; color: #065f46; padding: 15px 5px; text-align: center; border-radius: 5px;
            font-size: 0.8rem; font-weight: 700; height: 100%; display: flex; align-items: center; justify-content: center;
        }
        .time-slot.is-off { background: #dc2626; color: white; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="burger-menu" id="burgerBtn"><i class="fa-solid fa-bars"></i></div>
        </div>
        <ul class="nav-links">
            <li><div class="nav-link active-tab" onclick="showPage('dashboard', this)"><i class="fa-solid fa-table-cells-large"></i><span class="link-text">DASHBOARD</span></div></li>
            <li><div class="nav-link" onclick="showPage('book-appointment', this)"><i class="fa-solid fa-calendar-plus"></i><span class="link-text">BOOK APPOINTMENT</span></div></li>
            <li><div class="nav-link" onclick="showPage('my-appointments', this)"><i class="fa-solid fa-calendar-check"></i><span class="link-text">MY APPOINTMENTS</span></div></li>
            <li><div class="nav-link" onclick="showPage('medical-records', this)"><i class="fa-solid fa-file-medical"></i><span class="link-text">MEDICAL RECORDS</span></div></li>
        </ul>
        <div class="user-profile">
            <div class="avatar"><i class="fa-solid fa-user"></i></div>
            <div class="user-info">
                <div class="user-name" id="sidebar-name-display">
                    @if(Auth::user()->studentProfile)
                        {{ Auth::user()->studentProfile->firstname }} {{ Auth::user()->studentProfile->lastname }}
                    @endif
                </div>
                <div class="user-role">(STUDENT)</div>
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

            <div id="dashboard" class="page-section active-section">
                <h1 class="welcome-title">Welcome,
                    <span id="dashboard-name-display">
                        @if(Auth::user()->studentProfile)
                            {{ Auth::user()->studentProfile->firstname }}
                        @else
                            Student
                        @endif
                    </span>!
                </h1>

                @if(Auth::user()->has_findings)
                    <div style="background-color: #fff3cd; color: #856404; padding: 20px; border-radius: 15px; border-left: 6px solid #ffc107; margin-bottom: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem;"></i>
                            <h3 style="font-size: 1.2rem; font-weight: 800; margin: 0;">MEDICAL FINDINGS ALERT</h3>
                        </div>
                        <p style="margin-bottom: 10px;">The Health Services Unit has added a note to your record:</p>
                        <div style="background: rgba(255,255,255,0.5); padding: 15px; border-radius: 10px; font-weight: bold; border: 1px dashed #d39e00;">
                            "{{ Auth::user()->staff_notes }}"
                        </div>
                        <small style="display: block; margin-top: 10px; opacity: 0.8;">Please visit the clinic or contact staff for further instructions.</small>
                    </div>
                @else
                    <div style="background-color: #d1fae5; color: #065f46; padding: 15px 20px; border-radius: 15px; border-left: 6px solid #10b981; margin-bottom: 25px; display: flex; align-items: center; gap: 15px;">
                        <i class="fa-solid fa-circle-check" style="font-size: 1.5rem;"></i>
                        <div>
                            <h4 style="font-weight: 800; font-size: 1rem;">All Clear</h4>
                            <p style="font-size: 0.9rem; margin: 0;">You have no pending medical findings.</p>
                        </div>
                    </div>
                @endif
                @if(isset($announcements) && $announcements->count() > 0)
                    <div style="margin-bottom: 25px;">
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: #555; margin-bottom: 15px;"><i class="fa-solid fa-bullhorn"></i> Latest Updates</h3>
                        <div style="display: grid; gap: 15px;">
                            @foreach($announcements as $ann)
                                @php
                                    $color = $ann->type == 'urgent' ? '#fee2e2' : ($ann->type == 'warning' ? '#fef3c7' : '#dbeafe');
                                    $textColor = $ann->type == 'urgent' ? '#991b1b' : ($ann->type == 'warning' ? '#92400e' : '#1e40af');
                                    $icon = $ann->type == 'urgent' ? 'fa-circle-exclamation' : ($ann->type == 'warning' ? 'fa-triangle-exclamation' : 'fa-circle-info');
                                @endphp
                                <div style="background-color: {{ $color }}; color: {{ $textColor }}; padding: 15px 20px; border-radius: 15px; border-left: 6px solid {{ $textColor }}; display: flex; align-items: flex-start; gap: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                                    <i class="fa-solid {{ $icon }}" style="font-size: 1.5rem; margin-top: 2px;"></i>
                                    <div>
                                        <h4 style="font-weight: 800; font-size: 1rem; margin-bottom: 3px;">{{ $ann->title }}</h4>
                                        <p style="font-size: 0.9rem; opacity: 0.9; line-height: 1.4;">{{ $ann->content }}</p>
                                        <div style="font-size: 0.75rem; opacity: 0.7; margin-top: 5px;">{{ $ann->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    // Logic to find the next active appointment
                    $upcoming = $appointments->whereIn('status', ['Pending', 'Approved'])
                                             ->where('appointment_date', '>=', \Carbon\Carbon::today()->format('Y-m-d'))
                                             ->sortBy('appointment_date')
                                             ->first();
                @endphp

                @if($upcoming)
                    <div class="upcoming-alert">
                        <div class="ua-details">
                            <h3><i class="fa-solid fa-calendar-check" style="margin-right: 10px;"></i> Upcoming Appointment</h3>
                            <div class="ua-info">{{ $upcoming->appointment_type }}</div>
                            <div class="ua-time">
                                <i class="fa-solid fa-clock"></i>
                                {{ \Carbon\Carbon::parse($upcoming->appointment_date)->format('F d, Y') }} • {{ $upcoming->time_slot }}
                            </div>
                            <br>
                            @if($upcoming->status == 'Approved')
                                <div class="ua-status" style="color: #065f46; background: #d1fae5;">Approved</div>
                            @else
                                <div class="ua-status" style="color: #b78103; background: #fff7cd;">Pending Approval</div>
                            @endif
                        </div>
                        <a href="javascript:void(0)" onclick="triggerSidebarClick('my-appointments')" class="ua-btn">View Details</a>
                    </div>
                @endif

                <div class="action-cards-container">
                    <div class="action-card" onclick="triggerSidebarClick('book-appointment')"><div class="card-icon-box"><i class="fa-solid fa-calendar-plus"></i></div><div class="card-text"><span class="card-label">Book</span><span class="card-main-text">Appointment</span></div></div>
                    <div class="action-card" onclick="triggerSidebarClick('my-appointments')"><div class="card-icon-box"><i class="fa-solid fa-calendar-days"></i></div><div class="card-text"><span class="card-label">View</span><span class="card-main-text">Appointments</span></div></div>
                    <div class="action-card" onclick="triggerSidebarClick('medical-records')"><div class="card-icon-box"><i class="fa-solid fa-file-medical"></i></div><div class="card-text"><span class="card-label">View</span><span class="card-main-text">Medical Records</span></div></div>
                </div>

                <div class="schedule-container">
                    <div style="background: white; padding: 10px 20px; border-radius: 30px; margin-bottom: 20px; display: inline-block; font-weight: 800; color: #555;">
                        <i class="fa-solid fa-calendar-week"></i> Weekly Staff Availability
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
                                <td class="schedule-row-header">
                                    {{ $staff->name ?? $staff->email }}
                                </td>

                                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri'] as $day)
                                    @php
                                        $sched = $schedules->where('user_id', $staff->id)->where('day', $day)->first();
                                        $isOff = $sched ? $sched->is_off : false;
                                        $text = $sched && !$isOff ? ($sched->start_time . ' - ' . $sched->end_time) : '8:00 AM - 5:00 PM';
                                        $display = $isOff ? 'OFF' : $text;
                                        $class = $isOff ? 'is-off' : '';
                                    @endphp
                                    <td>
                                        <div class="time-slot {{ $class }}">
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

            <div id="book-appointment" class="page-section">
                <h1 class="welcome-title">BOOK APPOINTMENT</h1>
                <div class="blue-main-card">

                    <div id="step-1-select" class="sub-view active-view">
                        <h2 style="margin-bottom: 20px; text-align:center;">Step 1: Select Services</h2>
                        <div class="timeline-container"><div class="timeline-line"></div><div class="timeline-step active-step">1</div><div class="timeline-step">2</div><div class="timeline-step">3</div><div class="timeline-step">4</div></div>
                        <div class="services-grid">
                            <div class="service-btn" onclick="goToRequirements('freshmen')">MEDICAL AND DENTAL<br>CLEARANCE (Freshmen)</div>
                            <div class="service-btn" onclick="goToRequirements('coe')">COE/OJT and Practice<br>Teaching</div>
                        </div>
                    </div>

                    <div id="step-2-freshmen" class="sub-view">
                        <div class="req-section-header">CLEARANCE REQUIREMENTS <span style="background:white; color:black; border-radius:50%; padding:2px 8px; font-size:0.8rem; margin-left:10px;">📝</span> For Freshmen</div>
                        <p class="req-details-text" style="font-style: italic;">Please ensure that ALL of the following requirements are COMPLETE.</p>
                        <div class="req-lists-grid">
                            <div><div style="font-size: 0.9rem; font-weight: 800; margin-bottom: 5px;">REQUIREMENTS NEEDED:</div><ol style="padding-left: 20px; font-size: 0.9rem; line-height: 1.6;"><li>Chest X-ray results</li><li>Drug Test</li><li>Admission form</li></ol></div>
                            <div><div style="font-size: 0.9rem; font-weight: 800; margin-bottom: 5px;">REQUIRED DOCUMENTS:</div><ol style="padding-left: 20px; font-size: 0.9rem; line-height: 1.6;"><li>1x1 ID Picture</li><li>Chest X-ray plates</li><li>Other supporting docs</li></ol></div>
                        </div>
                        <div class="warning-box"><i class="fa-solid fa-triangle-exclamation warning-icon"></i><div class="warning-text"><p>REMINDER: You can get your laboratory requests from the University Clinic.</p></div></div>
                        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.3); margin: 30px 0;">
                        <div class="req-section-header">CONFIRM YOUR REQUIREMENTS:</div>
                        <form id="freshmenForm">
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="req-id"><span class="custom-check"><i class="fa-solid fa-check"></i></span>1X1 ID PICTURE (Mandatory)</label>
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="req-xray"><span class="custom-check"><i class="fa-solid fa-check"></i></span>CHEST X-RAY RESULTS AND PLATE (Mandatory)</label>
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="req-drug"><span class="custom-check"><i class="fa-solid fa-check"></i></span>DRUG TEST RESULT (Mandatory)</label>
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="req-med-clearance"><span class="custom-check"><i class="fa-solid fa-check"></i></span>MEDICAL CLEARANCE (Outside RTU)</label>
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="req-other"><span class="custom-check"><i class="fa-solid fa-check"></i></span>OTHER MEDICAL SUPPORTING DOCUMENTS</label>
                        </form>
                        <div style="margin-top: 20px;"><button class="btn-action btn-back" onclick="goBackToSelect()"><i class="fa-solid fa-arrow-left"></i> Back</button><button class="btn-action btn-next" onclick="validateFreshmenRequirements()">Next Step <i class="fa-solid fa-arrow-right"></i></button></div>
                    </div>

                    <div id="step-3-form" class="sub-view">
                        <div class="form-header"><div class="form-title">STUDENT INFORMATION FORM</div><div class="form-subtitle">Please complete your details. PLEASE TYPE IN ALL CAPS LOCK.</div></div>
                        <div class="timeline-container" style="margin: 20px 0 30px 0;"><div class="timeline-line"></div><div class="timeline-step" style="background:#00d64f; border-color:white;"><i class="fa-solid fa-check"></i></div><div class="timeline-step" style="background:#00d64f; border-color:white;"><i class="fa-solid fa-check"></i></div><div class="timeline-step active-step">3</div><div class="timeline-step">4</div></div>
                        <form id="studentInfoForm">
                            <div class="form-group full-width" style="margin-bottom: 20px;"><label class="form-label">Email Address</label><input type="email" class="form-input" id="form-email" placeholder="example@rtu.edu.ph" value="{{ Auth::user()->email }}"></div>
                            <div class="form-grid"><div class="form-group"><label class="form-label">Last Name / Surname</label><input type="text" class="form-input uppercase-input" id="form-lastname" oninput="this.value = this.value.toUpperCase()"></div><div class="form-group"><label class="form-label">First Name</label><input type="text" class="form-input uppercase-input" id="form-firstname" oninput="this.value = this.value.toUpperCase()"></div></div>
                            <div class="form-grid"><div class="form-group"><label class="form-label">Birthday</label><input type="date" class="form-input" id="form-bday"></div><div class="form-group"><label class="form-label">Middle Name (Type N/A if none)</label><input type="text" class="form-input uppercase-input" id="form-middlename" oninput="this.value = this.value.toUpperCase()"></div></div>
                            <div class="form-grid"><div class="form-group"><label class="form-label">Home Address</label><input type="text" class="form-input uppercase-input" id="form-address" oninput="this.value = this.value.toUpperCase()"></div><div class="form-group"><label class="form-label">Contact Number</label><input type="text" class="form-input uppercase-input" id="form-contact"></div></div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">College</label>
                                    <select class="form-select" id="form-college">
                                        <option value="" disabled selected>Select College</option>
                                        <option value="CEA">College of Engineering and Architecture (CEA)</option>
                                        <option value="CED">College of Education (CED)</option>
                                        <option value="CBEA">College Business Entrepreneur Accountancy (CBEA)</option>
                                        <option value="CAS">College of Arts and Sciences (CAS)</option>
                                        <option value="IHK">Institute of Human Kinetics (IHK)</option>
                                    </select>
                                </div>
                                <div class="form-group"><label class="form-label">Course</label><input type="text" class="form-input uppercase-input" id="form-course" placeholder="e.g. BS CIVIL ENGINEERING" oninput="this.value = this.value.toUpperCase()"></div>
                            </div>
                        </form>
                        <div style="margin-top: 20px;"><button class="btn-action btn-back" onclick="goBackToFreshmenRequirements()"><i class="fa-solid fa-arrow-left"></i> Back</button><button class="btn-action btn-next" onclick="saveStudentForm()">Next Step <i class="fa-solid fa-arrow-right"></i></button></div>
                    </div>

                    <div id="step-4-scheduling" class="sub-view">
                        <div class="timeline-container" style="margin-top: 0; margin-bottom: 20px;"><div class="timeline-line"></div><div class="timeline-step" style="background: #00d64f; border-color:white;"><i class="fa-solid fa-check"></i></div><div class="timeline-step" style="background: #00d64f; border-color:white;"><i class="fa-solid fa-check"></i></div><div class="timeline-step" style="background: #00d64f; border-color:white;"><i class="fa-solid fa-check"></i></div><div class="timeline-step active-step">4</div></div>
                        <div class="scheduling-layout">
                            <div><h2 class="step-label">Select Date:</h2><div class="calendar-box"><div class="calendar-header"><span id="monthYearDisplay">September 2025</span><div class="calendar-controls"><i class="fa-solid fa-chevron-left" onclick="changeMonth(-1)"></i><i class="fa-solid fa-chevron-right" onclick="changeMonth(1)"></i></div></div><div class="calendar-grid"><div class="cal-day-name">Sun</div><div class="cal-day-name">Mon</div><div class="cal-day-name">Tue</div><div class="cal-day-name">Wed</div><div class="cal-day-name">Thu</div><div class="cal-day-name">Fri</div><div class="cal-day-name">Sat</div></div><div class="calendar-grid" id="calendarDays"></div></div></div>
                            <div>
                                <h2 class="step-label">Time Slot:</h2>
                                <div class="time-slot-box">
                                    <p>Date: <span id="selectedDateDisplay" style="font-weight:bold; color:#00d64f;">None</span></p>
                                    <select class="time-select" id="timeSlotSelect">
                                        <option value="" disabled selected>Select time...</option>
                                        <option value="8:00 AM - 12:00 PM">8:00 AM - 12:00 PM</option>
                                        <option value="1:00 PM - 5:00 PM">1:00 PM - 5:00 PM</option>

                                    </select>
                                </div>
                                <div style="margin-top: 150px;">
                                    <button class="btn-action btn-back" onclick="goBackToForm()">Back</button>
                                    <button class="btn-action btn-next" onclick="confirmAppointment()">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="step-2-coe" class="sub-view">
                        <div class="req-section-header">CLEARANCE REQUIREMENTS <span style="background:white; color:black; border-radius:50%; padding:2px 8px; font-size:0.8rem; margin-left:10px;">📝</span> COE/OJT and Practice Teaching</div>
                        <p class="req-details-text" style="font-style: italic;">Please ensure that ALL of the following requirements are COMPLETE.</p>
                        <div class="req-lists-grid">
                            <div><div style="font-size: 0.9rem; font-weight: 800; margin-bottom: 5px;">REQUIREMENTS NEEDED:</div><ol style="padding-left: 20px; font-size: 0.9rem; line-height: 1.6;"><li>Chest X-ray results</li><li>COE Form</li></ol></div>
                            <div><div style="font-size: 0.9rem; font-weight: 800; margin-bottom: 5px;">REQUIRED DOCUMENTS:</div><ol style="padding-left: 20px; font-size: 0.9rem; line-height: 1.6;"><li>Chest X-ray results</li><li>Complete Blood Count & Platelet</li><li>Urinalysis</li><li>Other medical docs</li></ol></div>
                        </div>
                        <div class="warning-box"><i class="fa-solid fa-triangle-exclamation warning-icon"></i><div class="warning-text"><p>REMINDER: You can get your laboratory requests from the University Clinic.</p></div></div>
                        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.3); margin: 30px 0;">
                        <div class="req-section-header">CONFIRM YOUR REQUIREMENTS:</div>
                        <p class="req-details-text">Only Chest X-Ray is mandatory to proceed for COE/OJT.</p>
                        <form id="coeForm">
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="coe-xray"><span class="custom-check"><i class="fa-solid fa-check"></i></span>CHEST X-RAY RESULTS AND PLATE (Mandatory)</label>
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="coe-cbc"><span class="custom-check"><i class="fa-solid fa-check"></i></span>COMPLETE BLOOD COUNT & PLATELET COUNT</label>
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="coe-urinalysis"><span class="custom-check"><i class="fa-solid fa-check"></i></span>URINALYSIS</label>
                            <label class="toggle-label"><input type="checkbox" class="toggle-input" id="coe-other"><span class="custom-check"><i class="fa-solid fa-check"></i></span>OTHER MEDICAL DOCUMENTS</label>
                        </form>
                        <div style="margin-top: 20px;"><button class="btn-action btn-back" onclick="goBackToSelect()"><i class="fa-solid fa-arrow-left"></i> Back</button><button class="btn-action btn-next" onclick="validateCoeRequirements()">Next Step <i class="fa-solid fa-arrow-right"></i></button></div>
                    </div>

                    <div id="step-3-coe-scheduling" class="sub-view">
                         <div class="timeline-container three-steps" style="margin: 0 0 20px 0;"><div class="timeline-line"></div><div class="timeline-step" style="background:#00d64f; border-color:white;"><i class="fa-solid fa-check"></i></div><div class="timeline-step" style="background:#00d64f; border-color:white;"><i class="fa-solid fa-check"></i></div><div class="timeline-step active-step">3</div></div>
                        <div class="scheduling-layout">
                            <div><h2 class="step-label">Select Date:</h2><div class="calendar-box"><div class="calendar-header"><span id="coeMonthDisplay">September 2025</span><div class="calendar-controls"><i class="fa-solid fa-chevron-left" onclick="changeCoeMonth(-1)"></i><i class="fa-solid fa-chevron-right" onclick="changeCoeMonth(1)"></i></div></div><div class="calendar-grid"><div class="cal-day-name">Sun</div><div class="cal-day-name">Mon</div><div class="cal-day-name">Tue</div><div class="cal-day-name">Wed</div><div class="cal-day-name">Thu</div><div class="cal-day-name">Fri</div><div class="cal-day-name">Sat</div></div><div class="calendar-grid" id="coeCalendarDays"></div></div></div>
                            <div>
                                <h2 class="step-label">Time Slot:</h2>
                                <div class="time-slot-box">
                                    <p>Date: <span id="coeSelectedDate" style="font-weight:bold; color:#00d64f;">None</span></p>
                                    <select class="time-select" id="coeTimeSlot">
                                        <option value="" disabled selected>Select time...</option>
                                        <option value="8:00 AM - 9:00 AM">8:00 AM - 9:00 AM</option>
                                        <option value="9:00 AM - 10:00 AM">9:00 AM - 10:00 AM</option>
                                        <option value="10:00 AM - 11:00 AM">10:00 AM - 11:00 AM</option>
                                        <option value="11:00 AM - 12:00 PM">11:00 AM - 12:00 PM</option>
                                        <option value="1:00 PM - 2:00 PM">1:00 PM - 2:00 PM</option>
                                        <option value="2:00 PM - 3:00 PM">2:00 PM - 3:00 PM</option>
                                        <option value="3:00 PM - 4:00 PM">3:00 PM - 4:00 PM</option>
                                        <option value="4:00 PM - 5:00 PM">4:00 PM - 5:00 PM</option>
                                    </select>
                                </div>
                                <div style="margin-top: 150px;"><button class="btn-action btn-back" onclick="goBackToCoeReqs()">Back</button><button class="btn-action btn-next" onclick="confirmCoeAppointment()">Confirm</button></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="my-appointments" class="page-section">
                <h1 class="welcome-title">MY APPOINTMENTS</h1>
                <div class="blue-main-card" style="min-height: auto; background-color: white; color: #333; padding: 0;">

                    {{-- 1. Create a filtered variable for cleaner logic --}}
                    @php
                        $activeAppointments = $appointments->where('appointment_date', '>=', \Carbon\Carbon::today()->format('Y-m-d'));
                    @endphp

                    {{-- 2. Check if the FILTERED list is empty --}}
                    @if($activeAppointments->isEmpty())
                        <div style="padding: 50px; text-align: center;">
                            <i class="fa-solid fa-calendar-xmark" style="font-size: 3rem; color: #ccc; margin-bottom: 20px;"></i>
                            <h3 style="color: #555;">No Active Appointments</h3>
                            <p style="color: #777;">You have no upcoming appointments.</p>
                            <button class="btn-action" style="float: none; margin-top: 20px; background-color: #1553be; color: white;" onclick="triggerSidebarClick('book-appointment')">
                                Book Now
                            </button>
                        </div>
                    @else
                        <table class="records-table" style="margin-top: 0; box-shadow: none;">
                            <thead>
                                <tr style="background-color: #1553be; color: white;">
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- 3. Loop through the filtered list --}}
                                @foreach($activeAppointments as $apt)
                                    <tr>
                                        <td style="font-weight: 600;">
                                            {{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}
                                        </td>
                                        <td>{{ $apt->time_slot }}</td>
                                        <td>{{ $apt->appointment_type }}</td>
                                        <td>
                                            @if($apt->status == 'Cancelled')
                                                <span class="status-badge" style="background-color: #fee2e2; color: #991b1b;">Cancelled</span>
                                            @elseif($apt->status == 'Approved')
                                                <span class="status-badge status-active">Approved</span>
                                            @else
                                                <span class="status-badge" style="background-color: #fff7cd; color: #b78103;">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($apt->status != 'Cancelled' && $apt->status != 'Completed')
                                                <button class="btn-cancel" onclick="cancelAppointment({{ $apt->id }})">Cancel</button>
                                            @else
                                                <span style="color:#ccc; font-size: 0.8rem; font-style: italic;">Closed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>

            <div id="medical-records" class="page-section">
                <h2 class="page-header">Medical Records</h2>
                @if(Auth::user()->studentProfile)
                    <div class="blue-main-card" style="min-height: auto;">
                        <h3 style="margin-bottom: 20px;">Student Profile Information</h3>
                        <table class="records-table">
                            <tr><th>Full Name</th><td>{{ Auth::user()->studentProfile->firstname }} {{ Auth::user()->studentProfile->lastname }}</td></tr>
                            <tr><th>Birthday</th><td>{{ Auth::user()->studentProfile->birthday }}</td></tr>
                            <tr><th>Email</th><td>{{ Auth::user()->studentProfile->email }}</td></tr>
                            <tr><th>College</th><td>{{ Auth::user()->studentProfile->college }}</td></tr>
                            <tr><th>Course</th><td>{{ Auth::user()->studentProfile->course }}</td></tr>

                            <tr><th>Medical Status</th>
                                <td>
                                    @if(Auth::user()->studentProfile->medical_status == 'w/Medical-Complete')
                                        <span class="status-badge" style="background-color: #d1fae5; color: #065f46;">w/Medical-Complete</span>
                                    @elseif(Auth::user()->studentProfile->medical_status == 'w/Medical-inComplete')
                                        <span class="status-badge" style="background-color: #fee2e2; color: #991b1b;">w/Medical-inComplete</span>
                                    @else
                                        <span class="status-badge" style="background-color: #f3f4f6; color: #374151;">Pending Review</span>
                                    @endif
                                </td>
                            </tr>
                            <tr><th>Date Checked</th>
                                <td>
                                    {{ Auth::user()->studentProfile->date_checked ? \Carbon\Carbon::parse(Auth::user()->studentProfile->date_checked)->format('F d, Y') : 'Not yet checked' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                @else
                    <p>No medical record found. Please complete the "Book Appointment" process to create your profile.</p>
                @endif
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
        function triggerSidebarClick(pageId) {
            document.querySelectorAll('.nav-link').forEach(link => { if (link.getAttribute('onclick').includes(pageId)) link.click(); });
        }
        function hideAllSubViews() { document.querySelectorAll('.sub-view').forEach(el => el.classList.remove('active-view')); }

        function goToRequirements(type) {
            hideAllSubViews();
            if (type === 'freshmen') document.getElementById('step-2-freshmen').classList.add('active-view');
            else document.getElementById('step-2-coe').classList.add('active-view');
        }
        function goBackToSelect() { hideAllSubViews(); document.getElementById('step-1-select').classList.add('active-view'); }

        // --- SMART LOGIC: CHECK IF PROFILE EXISTS ---
        let hasProfile = @json(Auth::user()->studentProfile ? true : false);

        function validateFreshmenRequirements() {
            if (document.getElementById('req-id').checked && document.getElementById('req-xray').checked && document.getElementById('req-drug').checked) {
                hideAllSubViews();
                if (hasProfile) {
                    document.getElementById('step-4-scheduling').classList.add('active-view');
                    renderCalendar();
                } else {
                    document.getElementById('step-3-form').classList.add('active-view');
                }
            } else {
                Swal.fire({icon: 'warning', title: 'Requirements', text: 'Check mandatory boxes.'});
            }
        }

        function goBackToFreshmenRequirements() { hideAllSubViews(); document.getElementById('step-2-freshmen').classList.add('active-view'); }
        function goBackToForm() {
            hideAllSubViews();
            if(hasProfile) document.getElementById('step-2-freshmen').classList.add('active-view');
            else document.getElementById('step-3-form').classList.add('active-view');
        }

        function validateCoeRequirements() { if(document.getElementById('coe-xray').checked) { hideAllSubViews(); document.getElementById('step-3-coe-scheduling').classList.add('active-view'); renderCoeCalendar(); } else Swal.fire({icon:'warning', text:'Chest X-Ray is mandatory for COE.'}); }
        function goBackToCoeReqs() { hideAllSubViews(); document.getElementById('step-2-coe').classList.add('active-view'); }

        // --- FIXED SAVE FUNCTION ---
        function saveStudentForm() {
            const formData = new FormData();
            formData.append('email', document.getElementById('form-email').value);
            formData.append('lastname', document.getElementById('form-lastname').value);
            formData.append('firstname', document.getElementById('form-firstname').value);
            formData.append('middlename', document.getElementById('form-middlename').value);
            formData.append('birthday', document.getElementById('form-bday').value);
            formData.append('address', document.getElementById('form-address').value);
            formData.append('contact_number', document.getElementById('form-contact').value);
            formData.append('college', document.getElementById('form-college').value);
            formData.append('course', document.getElementById('form-course').value);

            if(!formData.get('lastname') || !formData.get('firstname') || !formData.get('birthday')) {
                Swal.fire({icon: 'warning', text: 'Please fill out all required fields.'});
                return;
            }

            Swal.fire({title: 'Saving...', didOpen: () => { Swal.showLoading() }});

            fetch('/save-student-info', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if(data.success) {
                    hasProfile = true; // Update local state

                    // Visual Updates without reload
                    const mName = formData.get('middlename') && formData.get('middlename') !== 'N/A' ? ' ' + formData.get('middlename') : '';
                    const fullName = `${formData.get('lastname')}, ${formData.get('firstname')}${mName}`.toUpperCase();

                    const sidebarEl = document.getElementById('sidebar-name-display');
                    if(sidebarEl) sidebarEl.innerText = fullName;

                    const dashEl = document.getElementById('dashboard-name-display');
                    if(dashEl) dashEl.innerText = formData.get('firstname').toUpperCase();

                    hideAllSubViews();
                    document.getElementById('step-4-scheduling').classList.add('active-view');
                    renderCalendar();
                } else {
                    Swal.fire({icon: 'error', title: 'Error', text: 'Failed to save profile.'});
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error:', error);
                Swal.fire({icon: 'error', title: 'System Error', text: 'Check console for details.'});
            });
        }

        // --- CANCEL APPOINTMENT ---
        function cancelAppointment(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/cancel-appointment/${id}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire('Cancelled!', 'Your appointment has been cancelled.', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', 'Could not cancel.', 'error');
                        }
                    });
                }
            })
        }

        // Calendar Logic
        let currentDate=new Date();
        function renderCalendar() { renderGenericCalendar('monthYearDisplay','calendarDays','selectedDateDisplay'); }
        function renderCoeCalendar() { renderGenericCalendar('coeMonthDisplay','coeCalendarDays','coeSelectedDate'); }

        function renderGenericCalendar(hId, gId, dId) {
            const h = document.getElementById(hId);
            const g = document.getElementById(gId);
            const y = currentDate.getFullYear();
            const m = currentDate.getMonth();
            const mn = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            h.innerText = `${mn[m]} ${y}`;
            g.innerHTML = "";

            const fd = new Date(y, m, 1).getDay();
            const dim = new Date(y, m + 1, 0).getDate();
            const t = new Date(); // Current time

            for (let i = 0; i < fd; i++) {
                g.appendChild(document.createElement("div"));
            }

            for (let i = 1; i <= dim; i++) {
                const d = document.createElement("div");
                d.className = "cal-date";
                d.innerText = i;

                let checkDate = new Date(y, m, i);
                let dayOfWeek = checkDate.getDay(); // 0 = Sunday, 6 = Saturday

                // DISABLE: Past dates OR Weekends (0 or 6)
                if (checkDate < new Date(t.setHours(0, 0, 0, 0)) || dayOfWeek === 0 || dayOfWeek === 6) {
                    d.classList.add("disabled");
                    if(dayOfWeek === 0 || dayOfWeek === 6) {
                        d.style.backgroundColor = "#f0f0f0";
                        d.style.color = "#ccc";
                    }
                }

                if (i === t.getDate() && m === t.getMonth() && y === t.getFullYear()) {
                    d.classList.add("today");
                }

                d.onclick = () => {
                    document.querySelectorAll(`#${gId} .cal-date`).forEach(e => e.classList.remove("selected-date"));
                    d.classList.add("selected-date");
                    document.getElementById(dId).innerText = `${mn[m]} ${i}, ${y}`;
                };

                g.appendChild(d);
            }
        }
        function changeMonth(d){currentDate.setMonth(currentDate.getMonth()+d);renderCalendar();}
        function changeCoeMonth(d){currentDate.setMonth(currentDate.getMonth()+d);renderCoeCalendar();}

        function confirmAppointment(){
            const dateStr = document.getElementById('selectedDateDisplay').innerText;
            const timeSlot = document.getElementById('timeSlotSelect').value;
            if(dateStr === 'None' || !timeSlot) { Swal.fire({icon:'error', title:'Oops', text:'Please select a Date and Time'}); return; }
            saveAppointmentToDB('Freshmen Medical', dateStr, timeSlot);
        }
        function confirmCoeAppointment(){
            const dateStr = document.getElementById('coeSelectedDate').innerText;
            const timeSlot = document.getElementById('coeTimeSlot').value;
            if(dateStr === 'None' || !timeSlot) { Swal.fire({icon:'error', title:'Oops', text:'Please select a Date and Time'}); return; }
            saveAppointmentToDB('COE/OJT', dateStr, timeSlot);
        }

        function saveAppointmentToDB(type, date, time) {
            Swal.fire({title: 'Booking...', didOpen: () => { Swal.showLoading() }});
            fetch('/book-appointment-save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ appointment_type: type, appointment_date: date, time_slot: time })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if(data.success) { Swal.fire({icon: 'success', title: 'Booked!', text: 'Your appointment is pending approval.'}).then(() => location.reload()); }
                else { Swal.fire({icon: 'error', title: 'Error', text: data.message}); }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>
