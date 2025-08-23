
<header class="header">
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2c3e50;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .header {
            background: linear-gradient(135deg, var(--secondary) 0%, var(--dark) 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 100;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .header h1 {
            margin: 0;
            font-size: 2.2rem;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            transform: translateZ(30px);
            transition: var(--transition);
            background: linear-gradient(90deg, #00c9ff 0%, #92fe9d 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: inline-block;
            position: relative;
            left: 50%;
            transform: translateX(-50%) translateZ(30px);
        }

        .header nav {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .header nav a {
            color: var(--light);
            text-decoration: none;
            font-weight: 500;
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            transition: var(--transition);
            transform: translateZ(20px);
            position: relative;
        }

        .header nav a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 80%;
            height: 2px;
            background: var(--primary);
            transition: var(--transition);
            transform-origin: center;
        }

        .header nav a:hover {
            color: white;
            transform: translateY(-3px) translateZ(30px);
        }

        .header nav a:hover::before {
            transform: translateX(-50%) scaleX(1);
        }

        .header nav button {
            padding: 0.7rem 1.5rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            transform: translateZ(20px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        #loginButton {
            background: var(--primary);
            color: white;
        }

        #registerButton {
            background: var(--accent);
            color: white;
        }

        #dashboardButton {
            background: #2ecc71;
            color: white;
        }

        .header nav button:hover {
            transform: translateY(-3px) translateZ(30px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        #loginButton:hover {
            background: #2980b9;
        }

        #registerButton:hover {
            background: #c0392b;
        }

        #dashboardButton:hover {
            background: #27ae60;
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .header nav {
                gap: 0.8rem;
            }
            
            .header nav a, 
            .header nav button {
                font-size: 0.9rem;
                padding: 0.4rem 0.8rem;
            }
        }
    </style>

    <h1>Welcome to Our Hotel</h1>
    <nav>
        <a href="home.php">Home</a>
        <a href="morerooms.php">Rooms</a>
        <a href="facility.php">Facilities</a>
        <a href="contact.php">Contact</a>
        <a href="about.php">About</a>
        <button id="loginButton" onclick="window.location.href='login.php'">Login</button>
        <button id="registerButton" onclick="window.location.href='registration.php'">Register</button>
        <button id="dashboardButton" onclick="window.location.href='dashboard.php'" style="display: none;">Dashboard</button>
    </nav>
</header>