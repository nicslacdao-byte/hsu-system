<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #1553be;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }
        .card {
            background: white;
            color: #333;
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        h1 { color: #1553be; margin-bottom: 10px; }
        p { margin-bottom: 30px; }
        .btn {
            background-color: #1553be;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Verify Your Email</h1>
        <p>Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn">Log Out</button>
        </form>
    </div>
</body>
</html>
