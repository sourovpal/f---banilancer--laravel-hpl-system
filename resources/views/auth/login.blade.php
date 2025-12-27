<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- <link href="https://db.onlinewebfonts.com/c/7c6661efce01eac269383bac79303c1b?family=Arial+Narrow" rel="stylesheet"> --}}

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            {{-- font-family: "Arial Narrow", sans-serif; --}}
            {{-- font-weight:600; --}}
        }

        body {
            min-height: 100vh;
            background: oklch(96.8% 0.007 247.896);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 480px;
            text-align: center;

            @media screen and (max-width: 768px) {
                max-width: 85%;
            }
        }

        /* Logo */
        .logo {
            margin-bottom: 12px;
        }

        .logo img {
            width: 60px;
            height: auto;
        }

        .brand {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 30px;
        }

        /* Card */
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 32px;
            border: 1px solid #e5e7eb;
        }

        .card h2 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 24px;
        }

        /* Form */
        .form-group {
            text-align: left;
            margin-bottom: 25px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
            margin-bottom: 6px;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            width: 100%;
            height: 44px;
            padding: 0 14px 0 42px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            outline: none;

            &.is-invalid {
                border-color: red !important;
            }
        }

        .input-wrapper input:focus {
            border-color: #fb7c12;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            font-size: 16px;
            color: #94a3b8;

            svg {
                width: 22px;
                margin-top: 4px;
                color: #fb7c12;
            }
        }

        /* Button */
        .btn {
            width: 100%;
            height: 46px;
            background: #fb7c12;
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn:hover {
            background: #f97316;
        }

        .demo-text {
            font-size: 13px;
            color: #64748b;
            margin-top: 16px;
        }

        .link {
            color: #64748b;

            &:hover {
                color: #1348c2ff;
            }
        }

        .red-text {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Logo -->
        <div class="logo">
            {{-- <img src="https://i.imgur.com/6xKZJxk.png" alt="HR Hub Logo"> --}}
        </div>
        <div class="brand">Sign In</div>

        <!-- Card -->
        <div class="card">
            <h2>Welcome back</h2>
            <p class="subtitle">Enter your credentials to access your account</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label>User ID</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                viewBox="0 0 24 24"><!-- Icon from Basil by Craftwork - https://creativecommons.org/licenses/by/4.0/ -->
                                <path fill="currentColor"
                                    d="M12 3.75a3.75 3.75 0 1 0 0 7.5a3.75 3.75 0 0 0 0-7.5m-4 9.5A3.75 3.75 0 0 0 4.25 17v1.188c0 .754.546 1.396 1.29 1.517c4.278.699 8.642.699 12.92 0a1.54 1.54 0 0 0 1.29-1.517V17A3.75 3.75 0 0 0 16 13.25h-.34q-.28.001-.544.086l-.866.283a7.25 7.25 0 0 1-4.5 0l-.866-.283a1.8 1.8 0 0 0-.543-.086z" />
                            </svg>
                        </span>
                        <input id="userid" type="text" class=" @error('userid') is-invalid @enderror"
                            name="userid" value="{{ old('userid') }}" required autocomplete="userid" autofocus>
                    </div>
                    @error('userid')
                        <small class="red-text ml-10" role="alert">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                viewBox="0 0 24 24"><!-- Icon from Material Symbols by Google - https://github.com/google/material-design-icons/blob/master/LICENSE -->
                                <path fill="currentColor"
                                    d="M6 22q-.825 0-1.412-.587T4 20V10q0-.825.588-1.412T6 8h1V6q0-2.075 1.463-3.537T12 1t3.538 1.463T17 6v2h1q.825 0 1.413.588T20 10v10q0 .825-.587 1.413T18 22zm6-5q.825 0 1.413-.587T14 15t-.587-1.412T12 13t-1.412.588T10 15t.588 1.413T12 17M9 8h6V6q0-1.25-.875-2.125T12 3t-2.125.875T9 6z" />
                            </svg>
                        </span>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password">
                    </div>
                    @error('password')
                        <small class="red-text ml-10" role="alert">
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                <button class="btn">Sign In</button>
            </form>

            <div class="demo-text">
                <a class="link" href="/password/reset">Forgot password?</a>
            </div>
        </div>
    </div>

</body>

</html>
