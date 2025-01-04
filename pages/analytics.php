<?php
// Create data directory and files if they don't exist
$dataDir = __DIR__ . '/fetch-data';
$priceFile = $dataDir . '/fetch_price.json';
$hashFile = $dataDir . '/fetch_hash.json';
$diffFile = $dataDir . '/fetch_diff.json';

if (!file_exists($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$initialData = [
    'labels' => [],
    'data' => []
];

if (!file_exists($priceFile)) {
    file_put_contents($priceFile, json_encode($initialData));
}

if (!file_exists($hashFile)) {
    file_put_contents($hashFile, json_encode($initialData));
}

if (!file_exists($diffFile)) {
    file_put_contents($diffFile, json_encode($initialData));
}

// Update data files
function updateDataFiles() {
    global $priceFile, $hashFile, $diffFile;
    
    $currentTime = date('Y-m-d H:i:s');
    
    // Fetch and save price data
    $priceData = @file_get_contents('https://pepeblocks.com/ext/getcurrentprice');
    if ($priceData) {
        $priceJson = json_decode($priceData, true);
        $existingPriceData = json_decode(file_get_contents($priceFile), true);
        
        $existingPriceData['labels'][] = $currentTime;
        $existingPriceData['data'][] = [
            'usd' => $priceJson['last_price_usd'],
            'usdt' => $priceJson['last_price_usdt']
        ];
        
        if (count($existingPriceData['labels']) > 100) {
            array_shift($existingPriceData['labels']);
            array_shift($existingPriceData['data']);
        }
        
        file_put_contents($priceFile, json_encode($existingPriceData));
    }
    
    // Fetch and save hash data
    $hashData = @file_get_contents('https://pepeblocks.com/api/getnetworkhashps');
    if ($hashData) {
        $hashValue = json_decode($hashData);
        $existingHashData = json_decode(file_get_contents($hashFile), true);
        
        $existingHashData['labels'][] = $currentTime;
        $existingHashData['data'][] = $hashValue;
        
        if (count($existingHashData['labels']) > 100) {
            array_shift($existingHashData['labels']);
            array_shift($existingHashData['data']);
        }
        
        file_put_contents($hashFile, json_encode($existingHashData));
    }

    // Fetch and save difficulty data
    $diffData = @file_get_contents('https://pepeblocks.com/api/getdifficulty');
    if ($diffData) {
        $diffValue = json_decode($diffData);
        $existingDiffData = json_decode(file_get_contents($diffFile), true);
        
        $existingDiffData['labels'][] = $currentTime;
        $existingDiffData['data'][] = $diffValue;
        
        if (count($existingDiffData['labels']) > 100) {
            array_shift($existingDiffData['labels']);
            array_shift($existingDiffData['data']);
        }
        
        file_put_contents($diffFile, json_encode($existingDiffData));
    }
}

updateDataFiles();
$currentYear = date('Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Ribbit</title>
    <link rel="icon" type="image/png" href="../asset/icon.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        :root {
            --primary-color: #2BBD75;
            --background-dark: #1C1C1C;
            --text-color: #ffffff;
            --secondary-bg: #2A2A2A;
            --border-color: #333333;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --success-color: #4CAF50;
            --danger-color: #f44336;
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

        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(600px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-container {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .chart-header h2 {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .chart-content {
            padding: 1.5rem;
            height: 400px;
        }

        .tools-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .tool-card {
            background-color: var(--secondary-bg);
            border-radius: 8px;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
        }

        .tool-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .tool-input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            background-color: var(--background-dark);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            color: var(--text-color);
            font-size: 1rem;
        }

        .tool-select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            background-color: var(--background-dark);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            color: var(--text-color);
            font-size: 1rem;
        }

        .tool-button {
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .tool-button:hover {
            opacity: 0.9;
        }

        .tool-result {
            margin-top: 1rem;
            padding: 1rem;
            background-color: var(--background-dark);
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .currency-toggle {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: opacity 0.3s;
        }

        .currency-toggle:hover {
            opacity: 0.9;
        }

        .footer {
            background-color: var(--secondary-bg);
            padding: 3rem 2rem;
            margin-top: 2rem;
        }

        .footer-content {
            max-width: 1400px;
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

        .positive-change {
            color: var(--success-color);
        }

        .negative-change {
            color: var(--danger-color);
        }

        .save-chart-btn {
            background-color: var(--primary-color);
            border: none;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: opacity 0.3s;
            margin-left: 1rem;
        }

        .save-chart-btn:hover {
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .content {
                padding: 1rem;
            }
            
            .chart-grid {
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
            <img src="../asset/icon.png" alt="Ribbit Logo">
            Ribbit
        </a>
        <div class="nav-links">
            <a href="/">Home</a>
            <a href="/analytics" class="active">Analytics</a>
            <a href="/nodes">Nodes</a>
        </div>
    </nav>

    <div class="content">
        <div class="chart-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <h2>Price Chart</h2>
                    <div>
                        <button class="currency-toggle" id="currencyToggle">Switch to USDT</button>
                        <button class="save-chart-btn" onclick="saveChart('priceChart')">Save Chart</button>
                    </div>
                </div>
                <div class="chart-content">
                    <canvas id="priceChart"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h2>Network Hash Rate</h2>
                    <button class="save-chart-btn" onclick="saveChart('hashChart')">Save Chart</button>
                </div>
                <div class="chart-content">
                    <canvas id="hashChart"></canvas>
                </div>
            </div>
        </div>

        <div class="tools-grid">
            <div class="tool-card">
                <h3>Price Calculator</h3>
                <input type="number" id="pepeAmount" placeholder="Enter PEPE amount" class="tool-input">
                <select id="priceUnit" class="tool-select">
                    <option value="usd">USD</option>
                    <option value="usdt">USDT</option>
                </select>
                <button onclick="calculatePrice()" class="tool-button">Calculate</button>
                <div id="priceResult" class="tool-result"></div>
            </div>

            <div class="tool-card">
                <h3>Hash Rate Converter</h3>
                <input type="number" id="hashInput" placeholder="Enter hash rate" class="tool-input">
                <select id="hashInputUnit" class="tool-select">
                    <option value="1">H/s</option>
                    <option value="1000">KH/s</option>
                    <option value="1000000">MH/s</option>
                    <option value="1000000000">GH/s</option>
                    <option value="1000000000000">TH/s</option>
                    <option value="1000000000000000">PH/s</option>
                </select>
                <select id="hashOutputUnit" class="tool-select">
                    <option value="1">H/s</option>
                    <option value="1000">KH/s</option>
                    <option value="1000000">MH/s</option>
                    <option value="1000000000">GH/s</option>
                    <option value="1000000000000">TH/s</option>
                    <option value="1000000000000000">PH/s</option>
                </select>
                <button onclick="convertHashRate()" class="tool-button">Convert</button>
                <div id="hashResult" class="tool-result"></div>
            </div>

            <div class="tool-card">
                <h3>Mining Calculator</h3>
                <input type="number" id="miningHashRate" placeholder="Enter your hash rate" class="tool-input">
                <select id="miningHashUnit" class="tool-select">
                    <option value="1">H/s</option>
                    <option value="1000">KH/s</option>
                    <option value="1000000">MH/s</option>
                    <option value="1000000000">GH/s</option>
                    <option value="1000000000000">TH/s</option>
                    <option value="1000000000000000">PH/s</option>
                </select>
                <button onclick="calculateMining()" class="tool-button">Calculate Rewards</button>
                <div id="miningResult" class="tool-result"></div>
            </div>

            <div class="tool-card">
                <h3>Price Change Calculator</h3>
                <input type="number" id="startPrice" placeholder="Enter start price" class="tool-input">
                <input type="number" id="endPrice" placeholder="Enter end price" class="tool-input">
                <button onclick="calculatePriceChange()" class="tool-button">Calculate Change</button>
                <div id="priceChangeResult" class="tool-result"></div>
            </div>

            <div class="tool-card">
                <h3>Network Statistics</h3>
                <div id="networkStats" class="tool-result">
                    <p>Network Difficulty: <span id="networkDifficulty">Loading...</span></p>
                    <p>Current Hash Rate: <span id="currentHashRate">Loading...</span></p>
                    <p>Price (USD): <span id="currentPriceUSD">Loading...</span></p>
                    <p>Price (USDT): <span id="currentPriceUSDT">Loading...</span></p>
                </div>
            </div>
        </div>
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
        // Load saved data
        const savedPriceData = <?php echo file_get_contents($priceFile); ?>;
        const savedHashData = <?php echo file_get_contents($hashFile); ?>;
        const savedDiffData = <?php echo file_get_contents($diffFile); ?>;

        let currentCurrency = 'USD';
        let priceChart, hashChart;

        function formatNumber(number, decimals = 8) {
            return number.toLocaleString(undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
        }

        function formatHashRate(hash, fromUnit = 1) {
            const units = ['H/s', 'KH/s', 'MH/s', 'GH/s', 'TH/s', 'PH/s'];
            let value = hash * fromUnit;
            let unitIndex = 0;

            while (value >= 1000 && unitIndex < units.length - 1) {
                value /= 1000;
                unitIndex++;
            }

            return `${formatNumber(value, 2)} ${units[unitIndex]}`;
        }

        function initCharts() {
            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: '#ffffff',
                            callback: function(value) {
                                return value.toFixed(8);
                            }
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#ffffff'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.4,
                        borderWidth: 2,
                        borderColor: '#2BBD75',
                        fill: 'start',
                        backgroundColor: 'rgba(43, 189, 117, 0.1)'
                    },
                    point: {
                        radius: 0,
                        hitRadius: 10,
                        hoverRadius: 4
                    }
                }
            };

            const priceCtx = document.getElementById('priceChart').getContext('2d');
            const hashCtx = document.getElementById('hashChart').getContext('2d');

            priceChart = new Chart(priceCtx, {
                type: 'line',
                data: {
                    labels: savedPriceData.labels,
                    datasets: [{
                        label: 'Price',
                        data: savedPriceData.data.map(d => d.usd)
                    }]
                },
                options: chartOptions
            });

            hashChart = new Chart(hashCtx, {
                type: 'line',
                data: {
                    labels: savedHashData.labels,
                    datasets: [{
                        label: 'Network Hash Rate',
                        data: savedHashData.data.map(h => h / 1e15)
                    }]
                },
                options: {
                    ...chartOptions,
                    scales: {
                        ...chartOptions.scales,
                        y: {
                            ...chartOptions.scales.y,
                            ticks: {
                                color: '#ffffff',
                                callback: function(value) {
                                    return formatHashRate(value * 1e15);
                                }
                            }
                        }
                    }
                }
            });
        }

        function calculatePrice() {
            const amount = parseFloat(document.getElementById('pepeAmount').value);
            const unit = document.getElementById('priceUnit').value;
            
            fetch('https://pepeblocks.com/ext/getcurrentprice')
                .then(response => response.json())
                .then(data => {
                    const price = unit === 'usd' ? data.last_price_usd : data.last_price_usdt;
                    const value = amount * price;
                    document.getElementById('priceResult').innerHTML = `
                        Value: ${formatNumber(value)} ${unit.toUpperCase()}<br>
                        Price per PEPE: ${formatNumber(price)} ${unit.toUpperCase()}
                    `;
                });
        }

        function convertHashRate() {
            const input = parseFloat(document.getElementById('hashInput').value);
            const inputUnit = parseFloat(document.getElementById('hashInputUnit').value);
            const outputUnit = parseFloat(document.getElementById('hashOutputUnit').value);
            
            const result = (input * inputUnit) / outputUnit;
            document.getElementById('hashResult').textContent = formatNumber(result, 2);
        }

        function calculateMining() {
            const hashRate = parseFloat(document.getElementById('miningHashRate').value);
            const hashUnit = parseFloat(document.getElementById('miningHashUnit').value);
            
            Promise.all([
                fetch('https://pepeblocks.com/api/getnetworkhashps'),
                fetch('https://pepeblocks.com/api/getdifficulty')
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([networkHash, difficulty]) => {
                const userHashRate = hashRate * hashUnit;
                const networkHashRate = networkHash;
                const dailyBlocks = 144; // Average blocks per day
                const blockReward = 50; // PEPE per block
                
                const dailyReward = (userHashRate / networkHashRate) * dailyBlocks * blockReward;
                document.getElementById('miningResult').innerHTML = `
                    Estimated Daily Rewards: ${formatNumber(dailyReward)} PEPE<br>
                    Network Share: ${((userHashRate / networkHashRate) * 100).toFixed(6)}%<br>
                    Current Difficulty: ${formatNumber(difficulty, 8)}
                `;
            });
        }

        function calculatePriceChange() {
            const startPrice = parseFloat(document.getElementById('startPrice').value);
            const endPrice = parseFloat(document.getElementById('endPrice').value);
            
            const change = ((endPrice - startPrice) / startPrice) * 100;
            const changeClass = change >= 0 ? 'positive-change' : 'negative-change';
            
            document.getElementById('priceChangeResult').innerHTML = `
                <span class="${changeClass}">
                    ${change >= 0 ? '+' : ''}${change.toFixed(2)}%
                </span><br>
                Absolute Change: ${formatNumber(endPrice - startPrice)}
            `;
        }

        function updateStats() {
            Promise.all([
                fetch('https://pepeblocks.com/ext/getcurrentprice'),
                fetch('https://pepeblocks.com/api/getnetworkhashps'),
                fetch('https://pepeblocks.com/api/getdifficulty')
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([priceData, hashData, difficultyData]) => {
                document.getElementById('networkDifficulty').textContent = formatNumber(difficultyData, 8);
                document.getElementById('currentHashRate').textContent = formatHashRate(hashData);
                document.getElementById('currentPriceUSD').textContent = formatNumber(priceData.last_price_usd);
                document.getElementById('currentPriceUSDT').textContent = formatNumber(priceData.last_price_usdt);
                
                // Update charts
                if (priceChart.data.datasets[0].data.length > 30) {
                    priceChart.data.labels.shift();
                    priceChart.data.datasets[0].data.shift();
                }
                
                priceChart.data.labels.push(new Date().toLocaleTimeString());
                priceChart.data.datasets[0].data.push(
                    currentCurrency === 'USD' ? priceData.last_price_usd : priceData.last_price_usdt
                );
                priceChart.update();

                if (hashChart.data.datasets[0].data.length > 30) {
                    hashChart.data.labels.shift();
                    hashChart.data.datasets[0].data.shift();
                }
                
                hashChart.data.labels.push(new Date().toLocaleTimeString());
                hashChart.data.datasets[0].data.push(hashData / 1e15);
                hashChart.update();
            });
        }

        function saveChart(chartId) {
            const canvas = document.getElementById(chartId);
            const ctx = canvas.getContext('2d');
            
            // Add watermark
            ctx.save();
            ctx.globalAlpha = 0.5;
            ctx.font = '12px Arial';
            ctx.fillStyle = '#ffffff';
            ctx.fillText('', 10, canvas.height - 10);
            ctx.restore();
            
            // Add logo
            const logo = new Image();
            logo.onload = function() {
                ctx.drawImage(logo, canvas.width - 130, canvas.height - 340, 100, 50);
                
                // Convert chart to image
                const image = canvas.toDataURL('image/png');
                
                // Create download link
                const link = document.createElement('a');
                link.download = `${chartId}_${new Date().toISOString()}.png`;
                link.href = image;
                link.click();
            };
            logo.src = '../asset/white-banner.png';
        }

        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
            updateStats();
            setInterval(updateStats, 10000);

            document.getElementById('currencyToggle').addEventListener('click', () => {
                currentCurrency = currentCurrency === 'USD' ? 'USDT' : 'USD';
                document.getElementById('currencyToggle').textContent = `Switch to ${currentCurrency === 'USD' ? 'USDT' : 'USD'}`;
                updateStats();
            });
        });
    </script>
</body>
</html>

