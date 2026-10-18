<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZOV - TERMINATED</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #0a0b0d;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e4e6eb;
            padding: 20px;
            overflow: hidden;
            position: relative;
        }

        /* Background with a radial glow effect */
        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(220, 53, 69, 0.15) 0%, rgba(0,0,0,0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
        }

        .ban-container {
            max-width: 480px;
            width: 100%;
            background: #16181c;
            border: 1px solid #2f3336;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5), 0 0 30px rgba(220, 53, 69, 0.05);
            overflow: hidden;
            position: relative;
            z-index: 2;
            animation: containerSpawn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Red neon line at the top */
        .ban-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #dc3545, #ff4d5e);
            box-shadow: 0 2px 10px rgba(220, 53, 69, 0.5);
        }

        .ban-header {
            padding: 40px 40px 20px 40px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo {
            height: 38px;
            margin-bottom: 25px;
            filter: drop-shadow(0 2px 8px rgba(255,255,255,0.1));
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .ban-icon-wrapper {
            position: relative;
            margin-bottom: 20px;
            animation: pulseGlow 2s infinite ease-in-out;
        }

        .ban-icon {
            width: 80px;
            height: 80px;
        }

        .ban-title {
            font-size: 2rem;
            font-weight: 900;
            color: #ff4d5e;
            letter-spacing: 1px;
            text-shadow: 0 0 15px rgba(255, 77, 94, 0.3);
        }

        .ban-body {
            padding: 0 40px 40px 40px;
            text-align: center;
        }

        .ban-text {
            font-size: 1.05rem;
            margin-bottom: 20px;
            line-height: 1.6;
            color: #98a1b3;
        }

        .highlight {
            color: #ff4d5e;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(255, 77, 94, 0.15);
        }

        .rules-box {
            background: #1e2227;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
            border-left: 3px solid #ff4d5e;
        }

        .rules-box .ban-text {
            margin-bottom: 0;
            font-size: 0.95rem;
            text-align: left;
            color: #cbd5e1;
        }

        .logout-form {
            margin-top: 15px;
        }

        .logout-btn {
            background: #ff4d5e;
            color: #ffffff;
            border: none;
            padding: 14px 28px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            box-shadow: 0 4px 12px rgba(255, 77, 94, 0.2);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logout-btn:hover {
            background: #dc3545;
            box-shadow: 0 6px 20px rgba(255, 77, 94, 0.4);
            transform: translateY(-1px);
        }

        .logout-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 8px rgba(255, 77, 94, 0.3);
        }

        /* Animations */
        @keyframes containerSpawn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes pulseGlow {
            0%, 100% {
                filter: drop-shadow(0 0 5px rgba(255, 77, 94, 0.2));
            }
            50% {
                filter: drop-shadow(0 0 20px rgba(255, 77, 94, 0.5));
            }
        }
    </style>
</head>
<body>
    <div class="ban-container">
        <div class="ban-header">
            <img src="./images/logo.png" alt="ZOV" class="logo">
            <div class="ban-icon-wrapper">
                <svg class="ban-icon" viewBox="0 0 24 24" fill="none" xmlns="http://w3.org">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM4 12C4 7.58 7.58 4 12 4C13.85 4 15.54 4.63 16.9 5.69L5.69 16.9C4.63 15.54 4 13.85 4 12ZM12 20C10.15 20 8.46 19.37 7.1 18.31L18.31 7.1C19.37 8.46 20 10.15 20 12C20 16.42 17.42 20 12 20Z" fill="#ff4d5e"/>
                </svg>
            </div>
            <h1 class="ban-title">403:FORBIDDEN</h1>
        </div>
        <div class="ban-body">
            <p class="ban-text">Your account in the <span class="highlight">ZOV</span> messenger was <span class="highlight">nuked</span> by the administration. Say thank you :)</p>
            
            <div class="rules-box">
                <p class="ban-text">Our moderators found that your behavior in the messenger violates our rules and terms of service.</p>
            </div>
            
            <p class="ban-text" style="font-size: 0.95rem; margin-bottom: 25px;">
                The ban is <span class="highlight">permanent</span> and cannot be appealed.
            </p>
            
            <form action="/logout.php" method="POST" class="logout-form">
                <button type="submit" class="logout-btn">Get the hell out of here</button>
            </form>
        </div>
    </div>
</body>
</html>
