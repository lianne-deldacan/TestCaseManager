<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - @yield('title')</title>
    <!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #7979b2;
        }

        .container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

      .login-box {
          background-color: #ffffff;
          border-radius: 12px;
          padding: 3rem 4rem;
          width: 550px; 
          text-align: center;
          position: relative;
          box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.15);
      }

        .logo-circle {
            background-color: white;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            position: absolute;
            top: -90px;
            left: calc(50% - 90px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .logo-circle img {
            width: 75%;
        }

        h2 {
            color: #5d5d9e;
            margin-top: 90px;
            margin-bottom: 35px;
            font-size: 28px;
        }

        form {
            text-align: left;
        }

        label {
            font-weight: bold;
            font-size: 18px;
            color: #5d5d9e;
            display: block;
            margin: 15px 0 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 18px;
        }

        .forgot {
            display: block;
            text-align: right;
            font-size: 12px;
            margin-top: 5px;
            color: #5d5d9e;
            text-decoration: none;
        }

        .forgot:hover {
            text-decoration: underline;
        }

        button {
            margin-top: 25px;
            padding: 14px 35px;
            background-color: #7979b2;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #6868a1;
        }

        .button-container {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
