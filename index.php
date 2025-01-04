<?php
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ribbit - Pepecoin Dev Group</title>
    <link rel="icon" type="image/png" href="asset/icon.png">
    <style>
        :root {
            --primary-color: #2BBD75;
            --background-dark: #1C1C1C;
            --text-color: #ffffff;
            --secondary-bg: #2A2A2A;
            --border-color: #333333;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            width: auto;
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
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .hero {
            text-align: center;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .hero p {
            font-size: 1.2rem;
            color: #888;
            max-width: 800px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: var(--secondary-bg);
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: var(--box-shadow);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .stat-card p {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .feature-card {
            background-color: var(--secondary-bg);
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: var(--box-shadow);
            text-align: center;
        }

        .feature-card img {
            width: 80px;
            height: 80px;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #888;
        }

        .cta {
            text-align: center;
            margin-bottom: 3rem;
        }

        .cta-button {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 2rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #229c5e;
        }

        footer {
            background-color: var(--secondary-bg);
            padding: 3rem 2rem;
            border-top: 1px solid var(--border-color);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
        }

        .footer-section h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-family: 'Courier New', monospace;
        }

        .footer-section p {
            color: #888;
            line-height: 1.6;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.5rem;
        }

        .footer-links a {
            color: #888;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary-color);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            color: #888;
            text-decoration: none;
            font-size: 1.5rem;
            transition: color 0.3s;
        }

        .social-links a:hover {
            color: var(--primary-color);
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            color: #888;
            border-top: 1px solid var(--border-color);
            margin-top: 2rem;
            font-family: 'Courier New', monospace;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .content {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="logo">
            <img src="asset/icon.png" alt="Ribbit Logo">
            Ribbit
        </a>
        <div class="nav-links">
            <a href="/" class="active">Home</a>
            <a href="/analytics">Analytics</a>
            <a href="/nodes">Nodes</a>
        </div>
    </nav>

    <div class="content">
        <section class="hero">
            <h1>Welcome to Ribbit Dev</h1>
            <p>We are a group born from the Pepecoin blockchain as developers. We love making tools and helping users and devs, but we don't work legally with the team and are not expecting our names to be on their official site. As we support this blockchain, we love helping it grow and innovate.</p>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Network Hash Rate</h3>
                <p id="networkHash">Loading...</p>
            </div>
            <div class="stat-card">
                <h3>Current Price</h3>
                <p id="currentPrice">Loading...</p>
            </div>
            <div class="stat-card">
                <h3>Network Difficulty</h3>
                <p id="difficulty">Loading...</p>
            </div>
        </div>

        <section class="features">
            <div class="feature-card">
                <img src="asset/icon.png" alt="Secure Network">
                <h3>Secure Network</h3>
                <p>The network is secured using Litecoin and Dogecoin's hashrate.</p>
            </div>
            <div class="feature-card">
                <img src="asset/icon.png" alt="Fast Transactions">
                <h3>Fast Transactions</h3>
                <p>Transactions take just a few minutes to fully confirm.</p>
            </div>
            <div class="feature-card">
                <img src="asset/icon.png" alt="Community Driven">
                <h3>Community Driven</h3>
                <p>Join a vibrant community of developers and enthusiasts shaping the future of Pepecoin.</p>
            </div>
        </section>

        <section class="cta">
            <a href="/analytics" class="cta-button">Explore Analytics</a>
        </section>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>0x00 Dev is a forward-thinking Web3 development group, dedicated to creating innovative blockchain solutions.</p>
                <div class="social-links">
                    <a href="#" target="_blank">Discord</a>
                    <a href="#" target="_blank">GitHub</a>
                    <a href="#" target="_blank">Twitter</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="/">Home</a></li>
                    <li><a href="/analytics">Analytics</a></li>
                    <li><a href="/#coming-soon">ElectrumX Node</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Credits and Thanks</h3>
                <ul class="footer-links">
                    <li><a href="https://0x00dev.com/">0x00 Dev</a></li>
                    <li><a href="https://pepeblocks.com/">PepeBlocks For API</a></li>
                    <li><a href="https://vladhog.ru/">VladHogSecurity</a></li>
                    <li><a href="https://pepecoin.org/">pepecoin official website</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Contact</h3>
                <ul class="footer-links">
                    <li><a href="mailto:ribbit@0x00dev.com">ribbit@0x00dev.com</a></li>
                    <li><a href="https://discord.gg/7ea94KK22Y">Join PepeCoin Discord</a></li>
                    <li><a href="https://discord.gg/TDqkkzQ8SN">Join 0x00 Dev Discord</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            © <?php echo $currentYear; ?> 0x00 Dev. All rights reserved.
        </div>
    </footer>

    <script>
        function formatHash(hash) {
            const units = ['H/s', 'KH/s', 'MH/s', 'GH/s', 'TH/s', 'PH/s'];
            let unitIndex = 0;
            
            while (hash >= 1000 && unitIndex < units.length - 1) {
                hash /= 1000;
                unitIndex++;
            }
            
            return hash.toFixed(2) + ' ' + units[unitIndex];
        }

        function formatPrice(price) {
            return '$' + parseFloat(price).toLocaleString(undefined, {
                minimumFractionDigits: 8,
                maximumFractionDigits: 8
            });
        }

        async function updateStats() {
            try {
                const [priceResponse, hashResponse, difficultyResponse] = await Promise.all([
                    fetch('https://pepeblocks.com/ext/getcurrentprice'),
                    fetch('https://pepeblocks.com/api/getnetworkhashps'),
                    fetch('https://pepeblocks.com/api/getdifficulty')
                ]);

                const priceData = await priceResponse.json();
                const hashData = await hashResponse.json();
                const difficultyData = await difficultyResponse.json();

                document.getElementById('networkHash').textContent = formatHash(hashData);
                document.getElementById('currentPrice').textContent = formatPrice(priceData.last_price_usd);
                document.getElementById('difficulty').textContent = parseFloat(difficultyData).toFixed(8);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Update stats every 10 seconds
        updateStats();
        setInterval(updateStats, 10000);
    </script>
</body>
</html>
