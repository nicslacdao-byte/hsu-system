<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HSU Staff Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* SHARED STYLES */
        :root { --primary-blue: #1553be; --header-blue: #2b6bd8; --soft-blue: #7ba2e6; --white: #ffffff; --text-dark: #333; --sidebar-width: 260px; --sidebar-collapsed: 80px; --transition: 0.3s; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: var(--white); height: 100vh; display: flex; overflow: hidden; }

        .sidebar { width: var(--sidebar-width); background-color: var(--primary-blue); color: var(--white); display: flex; flex-direction: column; justify-content: space-between; transition: width var(--transition) ease; position: relative; z-index: 100; }
        .sidebar-header { height: 100px; display: flex; align-items: center; justify-content: center; background-color: var(--header-blue); border-top-right-radius: 50px; }
        .burger-menu { font-size: 1.8rem; cursor: pointer; }
        .nav-links { list-style: none; padding-top: 20px; flex-grow: 1; overflow-y: auto; }
        .nav-link { text-decoration: none; color: var(--white); display: flex; align-items: center; padding: 12px 25px; font-weight: 600; font-size: 0.9rem; white-space: nowrap; transition: background 0.2s; cursor: pointer; }
        .nav-link:hover, .nav-link.active-tab { background-color: rgba(255, 255, 255, 0.2); border-left: 5px solid white; }
        .nav-links i { font-size: 1.4rem; width: 40px; text-align: center; }
        .link-text { margin-left: 10px; transition: opacity 0.2s; }
        .user-profile { padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.3); display: flex; align-items: center; background-color: var(--primary-blue); }
        .avatar { width: 45px; height: 45px; background-color: #ddd; border-radius: 50%; margin-right: 15px; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; color: #555; overflow: hidden; }
        .user-info { overflow: hidden; }
        .user-name { font-weight: 800; font-size: 0.85rem; text-transform: uppercase; line-height: 1.2; }
        .logout-btn { margin-left: auto; font-size: 1.2rem; cursor: pointer; }

        .main-content { flex-grow: 1; display: flex; flex-direction: column; background-color: #f4f6f9; overflow-y: auto; }

        .top-header {
            height: 100px;
            background-color: var(--white);
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex; align-items: center; justify-content: center; z-index: 10;
        }
        .header-logos { display: flex; align-items: center; gap: 10px; pointer-events: none; }
        .logo-img { height: 55px; width: auto; }
        .header-text { display: flex; flex-direction: column; color: #002147; margin-left: -2px; }
        .header-title { font-size: 1.4rem; font-weight: 700; font-family: serif; letter-spacing: 0.5px; }
        .header-subtitle { font-size: 0.85rem; font-weight: 600; }
        .content-body { padding: 30px 50px; }

        .page-section { display: none; animation: fadeIn 0.4s; }
        .page-section.active-section { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .card { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

        /* ANALYTICS STYLES */
        .analytics-title { text-align: center; font-weight: 800; font-size: 1.8rem; margin-bottom: 25px; text-transform: uppercase; color: #000; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px; }
        .stat-card { background-color: #7aa0e6; color: white; padding: 20px 15px; text-align: center; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); position: relative; overflow: hidden; }
        .stat-card.blue-1 { background-color: #6b8cce; }
        .stat-card.blue-2 { background-color: #5a7bbf; }
        .stat-card h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 2px; }
        .stat-card p { font-size: 0.85rem; font-weight: 600; text-transform: uppercase; opacity: 0.9; margin: 0; }

        .charts-container { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .chart-box { background: white; padding: 15px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #dce4f2; }
        .chart-header { text-align: center; font-weight: 700; margin-bottom: 10px; text-transform: uppercase; color: #555; font-size: 0.85rem; }
        .chart-wrapper { position: relative; height: 250px; width: 100%; display: flex; justify-content: center; }

        /* CALENDAR & TABLE STYLES */
        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 1.5rem; font-weight: 800; color: #1553be; }
        .calendar-controls button { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #1553be; }
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
        .cal-head { text-align: center; font-weight: bold; padding: 10px; color: #777; }
        .cal-day { height: 100px; background: white; border: 1px solid #eee; border-radius: 10px; padding: 10px; cursor: pointer; position: relative; transition: 0.2s; }
        .cal-day:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-color: #1553be; }
        .cal-day.disabled { background: #f9f9f9; color: #ccc; pointer-events: none; }
        .cal-date-num { font-weight: 800; font-size: 1.1rem; }
        .limit-badge { font-size: 0.75rem; font-weight: bold; padding: 4px 8px; border-radius: 5px; text-align: center; }
        .status-open { background: #d1fae5; color: #065f46; }
        .status-full { background: #fee2e2; color: #991b1b; }
        .status-limited { background: #fef3c7; color: #92400e; }

        .filter-container { margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
        .filter-btn { padding: 8px 16px; border: 2px solid #1553be; background: white; color: #1553be; border-radius: 20px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 0.9rem; }
        .filter-btn:hover, .filter-btn.active-filter { background: #1553be; color: white; }
        tr.row-complete { background-color: #d1fae5 !important; }

        .staff-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .staff-table th { text-align: left; padding: 15px; background: #f8f9fa; color: #555; font-weight: 700; border-bottom: 2px solid #eee; }
        .staff-table td { padding: 15px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .search-container { display: flex; margin-bottom: 20px; gap: 10px; }
        .search-input { padding: 12px; border-radius: 8px; border: 1px solid #ddd; width: 300px; outline: none; }
        .btn-search { padding: 12px 20px; background-color: #1553be; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .status-select { padding: 8px; border-radius: 5px; border: 1px solid #ddd; }
        .date-input { padding: 8px; border-radius: 5px; border: 1px solid #ddd; }
        .btn-save { background: #1553be; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }

        .form-input { padding: 10px; border-radius: 5px; border: 1px solid #ddd; outline: none; }
        .form-input:focus { border-color: #1553be; }

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
            <li><div class="nav-link active-tab" onclick="showPage('dashboard', this)"><i class="fa-solid fa-chart-pie"></i><span class="link-text">DASHBOARD</span></div></li>
            <li><div class="nav-link" onclick="showPage('records', this)"><i class="fa-solid fa-users"></i><span class="link-text">STUDENT RECORDS</span></div></li>
            <li><div class="nav-link" onclick="showPage('appointments', this)"><i class="fa-solid fa-calendar-check"></i><span class="link-text">APPOINTMENTS</span></div></li>
            <li><div class="nav-link" onclick="showPage('availability', this)"><i class="fa-solid fa-calendar-days"></i><span class="link-text">MANAGE AVAILABILITY</span></div></li>
        </ul>
        <div class="user-profile">
            <div class="avatar"><i class="fa-solid fa-user-nurse"></i></div>
            <div class="user-info">
                <div class="user-name">Staff Portal</div>
                <div class="user-role">(MEDICAL STAFF)</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" id="logout-form">@csrf
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
                <div class="analytics-title">Dashboard Overview</div>

                <div class="stats-grid">
                    <div class="stat-card"><h2>{{ number_format($totalApps) }}</h2><p>Total Appointments</p></div>
                    <div class="stat-card blue-1"><h2>{{ number_format($freshmenApps) }}</h2><p>Freshmen</p></div>
                    <div class="stat-card blue-2"><h2>{{ number_format($ojtApps) }}</h2><p>COE / OJT</p></div>
                </div>

                <div class="charts-container">
                    <div class="chart-box">
                        <div class="chart-header">Monthly Trend</div>
                        <div class="chart-wrapper"><canvas id="monthlyChart"></canvas></div>
                    </div>
                    <div class="chart-box">
                        <div class="chart-header">Breakdown</div>
                        <div class="chart-wrapper"><canvas id="typeChart"></canvas></div>
                    </div>
                </div>

                <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #1553be;">
                    <h2 style="margin-bottom: 20px; color: #1553be; font-size: 1.3rem;"><i class="fa-solid fa-bullhorn"></i> Clinic Announcements</h2>

                    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">

                        <div style="padding-right: 20px; border-right: 1px solid #eee;">
                            <h4 style="margin-bottom: 15px; color: #555;">Post New Update</h4>
                            <form action="{{ url('/staff/post-announcement') }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 15px;">
                                    <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:5px;">Title</label>
                                    <input type="text" name="title" class="form-input" style="width: 100%;" required placeholder="e.g. Clinic Closed on Friday">
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:5px;">Priority Level</label>
                                    <select name="type" class="form-input" style="width: 100%;">
                                        <option value="info">General Info (Blue)</option>
                                        <option value="warning">Important (Orange)</option>
                                        <option value="urgent">Urgent / Closed (Red)</option>
                                    </select>
                                </div>
                                <div style="margin-bottom: 15px;">
                                    <label style="display:block; font-weight:600; font-size:0.85rem; margin-bottom:5px;">Message</label>
                                    <textarea name="content" class="form-input" style="width: 100%; height: 80px; resize:none;" required placeholder="Enter details..."></textarea>
                                </div>
                                <button type="submit" class="btn-save" style="width: 100%; padding: 10px;">POST UPDATE</button>
                            </form>
                        </div>

                        <div>
                            <h4 style="margin-bottom: 15px; color: #555;">Active Announcements</h4>
                            <div style="max-height: 350px; overflow-y: auto;">
                                @if($announcements->isEmpty())
                                    <div style="text-align: center; padding: 30px; color: #ccc; border: 2px dashed #eee; border-radius: 10px;">
                                        <i class="fa-regular fa-folder-open" style="font-size: 2rem; margin-bottom: 10px;"></i><br>
                                        No active announcements
                                    </div>
                                @else
                                    @foreach($announcements as $ann)
                                        <div style="border-left: 4px solid {{ $ann->type == 'urgent' ? '#dc2626' : ($ann->type == 'warning' ? '#f59e0b' : '#3b82f6') }}; background: #f8f9fa; padding: 15px; margin-bottom: 10px; border-radius: 6px; position: relative;">
                                            <a href="{{ url('/staff/delete-announcement/'.$ann->id) }}" style="position: absolute; top: 10px; right: 10px; color: #dc2626; opacity: 0.6; transition:0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.6" onclick="return confirm('Delete this announcement?')"><i class="fa-solid fa-trash"></i></a>
                                            <h5 style="margin-bottom: 5px; font-weight: 800; font-size: 0.95rem;">{{ $ann->title }}</h5>
                                            <p style="font-size: 0.85rem; color: #555; line-height: 1.4; margin-bottom: 5px;">{{ $ann->content }}</p>
                                            <small style="color: #999; font-size: 0.75rem;">Posted: {{ $ann->created_at->format('M d, h:i A') }}</small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div id="records" class="page-section">
                <h1 style="margin-bottom: 20px;">Student Medical Records</h1>

                <div class="filter-container">
                    <button class="filter-btn active-filter" onclick="filterByCollege('all', this)">ALL</button>
                    <button class="filter-btn" onclick="filterByCollege('CEA', this)">CEA</button>
                    <button class="filter-btn" onclick="filterByCollege('CED', this)">CED</button>
                    <button class="filter-btn" onclick="filterByCollege('CBEA', this)">CBEA</button>
                    <button class="filter-btn" onclick="filterByCollege('CAS', this)">CAS</button>
                    <button class="filter-btn" onclick="filterByCollege('IHK', this)">IHK</button>
                </div>

                <form action="{{ url('/') }}" method="GET" class="search-container">
                    <input type="text" name="search" class="search-input" placeholder="Search by Name or Email..." value="{{ request('search') }}">
                    <button type="submit" class="btn-search"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                    @if(request('search')) <a href="{{ url('/') }}" style="padding: 12px; background: #666; color: white; border-radius: 8px; text-decoration: none;">Clear</a> @endif
                </form>

                <div class="card">
                    <table class="staff-table" id="studentTable">
                        <thead>
                            <tr><th>Student Name</th><th>College / Course</th><th>Status</th><th>Date Checked</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr class="student-row {{ $student->medical_status == 'w/Medical-Complete' ? 'row-complete' : '' }}" data-college="{{ $student->college }}">
                                <td style="font-weight: 700; color: #1553be;">
                                    {{ $student->formatted_name }}
                                    @if($student->medical_status == 'w/Medical-Complete') <i class="fa-solid fa-circle-check" style="color: #00d64f; margin-left: 5px;"></i> @endif
                                </td>
                                <td>{{ $student->college }} <br> <small style="color:#777">{{ $student->course }}</small></td>
                                <td>{{ $student->medical_status }}</td>
                                <td>{{ $student->date_checked }}</td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <select id="status-{{ $student->id }}" class="status-select">
                                            <option value="w/Medical-Complete" {{ $student->medical_status == 'w/Medical-Complete' ? 'selected' : '' }}>Complete</option>
                                            <option value="w/Medical-inComplete" {{ $student->medical_status == 'w/Medical-inComplete' ? 'selected' : '' }}>Incomplete</option>
                                        </select>
                                        <input type="date" id="date-{{ $student->id }}" class="date-input" value="{{ $student->date_checked }}">
                                        <button class="btn-save" onclick="updateStatus({{ $student->id }})">Save</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center; padding: 20px;">No student records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="appointments" class="page-section">
                <h1 style="margin-bottom: 20px;">Appointment Schedule</h1>
                <div class="card">
                    <table class="staff-table">
                        <thead><tr><th>Date</th><th>Time Slot</th><th>Student Name</th><th>Purpose</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($appointments as $apt)
                            <tr>
                                <td style="font-weight: bold;">{{ \Carbon\Carbon::parse($apt->appointment_date)->format('M d, Y') }}</td>
                                <td>{{ $apt->time_slot }}</td>
                                <td>{{ $apt->user->studentProfile->formatted_name ?? $apt->user->email }}</td>
                                <td>{{ $apt->appointment_type }}</td>
                                <td>{{ $apt->status }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center;">No active appointments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="availability" class="page-section">
                <h1 style="margin-bottom: 20px;">Manage Daily Limits</h1>
                <div class="card">
                    <div class="calendar-header">
                        <span id="calMonthYear"></span>
                        <div class="calendar-controls">
                            <button onclick="changeMonth(-1)"><i class="fa-solid fa-chevron-left"></i></button>
                            <button onclick="changeMonth(1)"><i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="calendar-grid">
                        <div class="cal-head">SUN</div><div class="cal-head">MON</div><div class="cal-head">TUE</div><div class="cal-head">WED</div><div class="cal-head">THU</div><div class="cal-head">FRI</div><div class="cal-head">SAT</div>
                    </div>
                    <div class="calendar-grid" id="staffCalendarDays"></div>
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
            if(pageId === 'availability') fetchCalendarData();
        }

        function filterByCollege(college, btnElement) {
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active-filter'));
            btnElement.classList.add('active-filter');
            const rows = document.querySelectorAll('.student-row');
            rows.forEach(row => {
                if (college === 'all' || row.getAttribute('data-college') === college) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // --- CHARTS LOGIC ---
        document.addEventListener("DOMContentLoaded", function() {
            const completedData = @json(array_values($completedData));
            const pendingData = @json(array_values($pendingData));
            const freshmenCount = {{ $freshmenApps }};
            const ojtCount = {{ $ojtApps }};

            // 1. Monthly Line Chart
            const ctx1 = document.getElementById('monthlyChart').getContext('2d');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Completed',
                            data: completedData,
                            borderColor: '#00d64f',
                            backgroundColor: 'rgba(0, 214, 79, 0.1)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Pending',
                            data: pendingData,
                            borderColor: '#ffcc00',
                            backgroundColor: 'rgba(255, 204, 0, 0.1)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // 2. Breakdown Pie Chart
            const ctx2 = document.getElementById('typeChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Freshmen', 'COE / OJT'],
                    datasets: [{
                        data: [freshmenCount, ojtCount],
                        backgroundColor: ['#63ccca', '#4aa3df']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });

        // --- CALENDAR & UPDATE LOGIC ---
        let currentDate = new Date();
        let globalBookings = {};
        let globalLimits = {};

        function fetchCalendarData() {
            fetch('/staff/calendar-data')
            .then(res => res.json())
            .then(data => {
                globalBookings = data.bookings;
                globalLimits = data.limits;
                renderStaffCalendar();
            });
        }

        function renderStaffCalendar() {
            const h = document.getElementById('calMonthYear');
            const g = document.getElementById('staffCalendarDays');
            const y = currentDate.getFullYear();
            const m = currentDate.getMonth();
            const mn = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            h.innerText = `${mn[m]} ${y}`;
            g.innerHTML = "";

            const fd = new Date(y, m, 1).getDay();
            const dim = new Date(y, m + 1, 0).getDate();

            for (let i = 0; i < fd; i++) g.appendChild(document.createElement("div"));

            for (let i = 1; i <= dim; i++) {
                const d = document.createElement("div");
                d.className = "cal-day";

                let monthStr = (m + 1).toString().padStart(2, '0');
                let dayStr = i.toString().padStart(2, '0');
                let dateKey = `${y}-${monthStr}-${dayStr}`;

                let limit = globalLimits[dateKey] ? parseInt(globalLimits[dateKey]) : 50;
                let bookings = globalBookings[dateKey] ? parseInt(globalBookings[dateKey]) : 0;

                let badgeClass = "status-open";
                let statusText = "Open";

                if (bookings >= limit) {
                    badgeClass = "status-full";
                    statusText = "FULL";
                } else if (bookings > (limit * 0.8)) {
                    badgeClass = "status-limited";
                    statusText = "Busy";
                }

                d.innerHTML = `
                    <div class="cal-date-num">${i}</div>
                    <div class="limit-badge ${badgeClass}">
                        ${statusText}<br>
                        <small>${bookings}/${limit}</small>
                    </div>
                `;

                let checkDate = new Date(y, m, i);
                if (checkDate.getDay() === 0 || checkDate.getDay() === 6) d.classList.add("disabled");

                d.onclick = () => setLimit(dateKey, limit);
                g.appendChild(d);
            }
        }

        function changeMonth(d) {
            currentDate.setMonth(currentDate.getMonth() + d);
            renderStaffCalendar();
        }

        function setLimit(dateStr, currentLimit) {
            Swal.fire({
                title: `Set limit for ${dateStr}`,
                input: 'number',
                inputValue: currentLimit,
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                preConfirm: (newLimit) => {
                    return fetch('/staff/set-limit', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ date: dateStr, limit: newLimit })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(response.statusText);
                        return response.json();
                    })
                    .catch(error => Swal.showValidationMessage(`Request failed: ${error}`));
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Saved!', 'Daily limit updated.', 'success');
                    fetchCalendarData();
                }
            });
        }

        function updateStatus(profileId) {
            const status = document.getElementById(`status-${profileId}`).value;
            const date = document.getElementById(`date-${profileId}`).value;
            if(!date) { Swal.fire('Error', 'Please select a date checked', 'error'); return; }

            fetch('/staff/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: JSON.stringify({ profile_id: profileId, medical_status: status, date_checked: date })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({ icon: 'success', title: 'Updated!', text: 'Student record updated', timer: 1500, showConfirmButton: false }).then(() => location.reload());
                }
            });
        }
    </script>
</body>
</html>
