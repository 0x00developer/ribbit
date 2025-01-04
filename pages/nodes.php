<?php
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nodes - Ribbit</title>
    <link rel="icon" type="image/png" href="../asset/icon.png">
    <style>
        :root {
            --primary-color: #2BBD75;
            --background-dark: #1C1C1C;
            --text-color: #ffffff;
            --secondary-bg: #2A2A2A;
            --border-color: #333333;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --success-color: #2BBD75;
            --maintenance-color: #FFA500;
            --offline-color: #FF4444;
            --toast-bg: rgba(43, 189, 117, 0.9);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            background-color: var(--background-dark);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: var(--secondary-bg);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            color: var(--text-color);
        }

        .logo img {
            height: 40px;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: var(--text-color);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background-color: var(--primary-color);
        }

        .content {
            flex: 1;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .welcome-section h1 {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .welcome-section p {
            color: #888;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .metrics-grid .metric-box:nth-last-child(-n+2) {
            grid-column: span 2;
        }

        .metric-box {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .metric-box h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .metric-box .value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .metric-box .description {
            color: #888;
            font-size: 0.9rem;
        }

        .connection-boxes {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .connection-box {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .connection-box h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .connection-box .url {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 0.75rem;
            border-radius: 4px;
            font-family: monospace;
            margin-bottom: 1rem;
            word-break: break-all;
        }

        .copy-btn {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: opacity 0.3s;
        }

        .copy-btn:hover {
            opacity: 0.9;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-online {
            background-color: var(--success-color);
        }

        .status-maintenance {
            background-color: var(--maintenance-color);
        }

        .status-offline {
            background-color: var(--offline-color);
        }

        .russian-flag {
            width: 30px;
            height: 20px;
            margin-left: 8px;
            display: inline-block;
        }

        .russian-flag div {
            height: 33.33%;
        }

        .russian-flag .white {
            background-color: white;
        }

        .russian-flag .blue {
            background-color: #0039A6;
        }

        .russian-flag .red {
            background-color: #D52B1E;
        }

        .blockchain-logo {
            width: 40px;
            height: 40px;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .blockchain-logo:hover {
            transform: scale(1.1);
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--toast-bg);
            color: white;
            padding: 1rem 2rem;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1000;
        }

        .toast.show {
            opacity: 1;
        }

        .footer {
            background-color: var(--secondary-bg);
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }

        @media (max-width: 1200px) {
            .metrics-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .metrics-grid .metric-box:nth-last-child(-n+2) {
                grid-column: span 1;
            }
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .content {
                padding: 1rem;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .metrics-grid .metric-box:nth-last-child(-n+2) {
                grid-column: span 1;
            }
            
            .connection-boxes {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">
            <img src="../asset/icon.png" alt="Ribbit Logo">
            Ribbit
        </a>
        <div class="nav-links">
            <a href="/">Home</a>
            <a href="/analytics.php">Analytics</a>
            <a href="/nodes.php" class="active">Nodes</a>
        </div>
    </nav>

    <div class="content">
        <div class="welcome-section">
            <h1>Ribbit ElectrumX Node</h1>
            <p>Welcome to our high-performance ElectrumX node for the Pepecoin network. We provide reliable and efficient service for all your Pepecoin transactions.</p>
        </div>

        <div class="metrics-grid">
            <div class="metric-box">
                <h3>Location</h3>
                <div class="value">
                    Russia
                    <span class="russian-flag">
                        <div class="white"></div>
                        <div class="blue"></div>
                        <div class="red"></div>
                    </span>
                </div>
                <div class="description">Hosted in Moscow</div>
            </div>

            <div class="metric-box">
                <h3>Core Version</h3>
                <div class="value">1.1.0</div>
                <div class="description">Latest Pepecoin Core</div>
            </div>

            <div class="metric-box">
                <h3>Datacenter</h3>
                <div class="value">VHS Datacenter</div>
                <div class="description">High-performance hosting</div>
            </div>

            <div class="metric-box">
                <h3>Status</h3>
                <div class="value">
                    <span class="status-indicator status-maintenance"></span>
                    Maintenance
                </div>
                <div class="description">Under scheduled maintenance</div>
            </div>

            <div class="metric-box">
                <h3>Blockchain</h3>
                <a href="https://pepecoin.org/" target="_blank">
                    <img src="../asset/icon.png" alt="Pepecoin" class="blockchain-logo">
                </a>
                <div class="value">Pepecoin</div>
                <div class="description">Click logo to learn more</div>
            </div>

            <div class="metric-box">
                <h3>ElectrumX Version</h3>
                <div class="value">1.16.0</div>
                <div class="description">Latest stable release</div>
            </div>
        </div>

        <div class="connection-boxes">
            <div class="connection-box">
                <h3>TCP Connection</h3>
                <div class="url" id="tcp-url">tcp://95.31.218.67:50001</div>
                <button class="copy-btn" onclick="copyToClipboard('tcp-url')">Copy TCP Address</button>
            </div>

            <div class="connection-box">
                <h3>SSL Connection</h3>
                <div class="url" id="ssl-url">ssl://95.31.218.67:50002</div>
                <button class="copy-btn" onclick="copyToClipboard('ssl-url')">Copy SSL Address</button>
            </div>

            <div class="connection-box">
                <h3>WebSocket Connection</h3>
                <div class="url" id="ws-url">wss://95.31.218.67:50004</div>
                <button class="copy-btn" onclick="copyToClipboard('ws-url')">Copy WebSocket Address</button>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">
        ✓ Copied to clipboard!
    </div>

    <footer class="footer">
        <p>&copy; <?php echo $currentYear; ?> Ribbit. All rights reserved.</p>
    </footer>

    <script>
        function copyToClipboard(elementId) {
            const el = document.getElementById(elementId);
            const text = el.textContent;
            
            navigator.clipboard.writeText(text).then(function() {
                const toast = document.getElementById('toast');
                toast.classList.add('show');
                
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</body>
</html>