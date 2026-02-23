<!doctype html>
<html lang="en">
<head>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
    <title>Calculator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/calculator-page.css') : '/assets/css/calculator-page.css'); ?>">
</head>
<body>
<header class="calc-header-container">
    <nav class="calc-header-nav">
        <h1 class="calc-header-logo">Calculator</h1>
        <a href="<?php echo htmlspecialchars(function_exists('site_url') ? site_url('portfolio') : '/portfolio'); ?>" class="calc-header-link">
            <i class="fas fa-arrow-left"></i> Portfolio
        </a>
    </nav>
</header>
<div class="wrap">
    <div class="app" role="application">
        <div class="card" aria-labelledby="calcTitle">
            <h3 id="calcTitle">
                <span class="title-row">
                    <img class="logo" src="/calculator/assets/img/calculator-logo.svg" alt="Calculator logo">
                    <span class="title-text">Calculator</span>
                </span>
            </h3>
            <div id="screen" class="screen" aria-live="polite">
                <div id="expr" class="expr">&nbsp;</div>
                <div id="result" class="result">0</div>
            </div>
            <div class="keys" role="group" aria-label="Calculator keypad">
                <button class="key clear" data-action="clear">C</button>
                <button class="key" data-action="del">⌫</button>
                <button class="key op" data-action="op">%</button>
                <button class="key op" data-action="op">/</button>

                <button class="key" data-num>7</button>
                <button class="key" data-num>8</button>
                <button class="key" data-num>9</button>
                <button class="key op" data-action="op">*</button>

                <button class="key" data-num>4</button>
                <button class="key" data-num>5</button>
                <button class="key" data-num>6</button>
                <button class="key op" data-action="op">-</button>

                <button class="key" data-num>1</button>
                <button class="key" data-num>2</button>
                <button class="key" data-num>3</button>
                <button class="key op" data-action="op">+</button>

                <button class="key" data-num>0</button>
                <button class="key" data-num>00</button>
                <button class="key" data-num>.</button>
                <button class="key equals" data-action="equals">=</button>
            </div>
        </div>

        <aside class="history" aria-label="Calculation history">
            <h4>History</h4>
            <div id="histList" class="hist-list" role="list"></div>
            <div class="hist-actions">
                <button id="clearHist" class="key hist-action-btn">Clear</button>
                <button id="exportHist" class="key hist-action-btn">Export</button>
            </div>
        </aside>
    </div>
</div>

<footer class="calc-footer-container">
    <div class="calc-footer-content">
        <p class="calc-footer-text">&copy; 2026 My Portfolio. All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/calculator.js') : '/assets/js/calculator.js'); ?>"></script>
</body>
</html>
