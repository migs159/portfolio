<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
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

<script>
(() => {
    const exprEl = document.getElementById('expr');
    const resEl = document.getElementById('result');
    const histList = document.getElementById('histList');
    const keys = document.querySelector('.keys');
    const clearHistBtn = document.getElementById('clearHist');
    const exportHistBtn = document.getElementById('exportHist');

    let expr = '';
    let result = '';
    let history = [];

    const saveHistory = () => localStorage.setItem('calc_history_v1', JSON.stringify(history));
    const loadHistory = () => {
        try{ history = JSON.parse(localStorage.getItem('calc_history_v1')) || []; }catch(e){ history = []; }
    };

    function renderHistory(){
        histList.innerHTML = '';
        if(history.length === 0){ histList.innerHTML = '<div class="hist-empty">No history yet</div>'; return; }
        history.slice().reverse().forEach(item => {
            const div = document.createElement('div'); div.className='hist-item';
            const left = document.createElement('div'); left.textContent = item.expr; left.className='hist-item-expr';
            const right = document.createElement('small'); right.textContent = item.res;
            div.appendChild(left); div.appendChild(right);
            div.setAttribute('role','listitem');
            div.addEventListener('click', () => { expr = item.expr; result = item.res; update(); });
            histList.appendChild(div);
        });
    }

    function update(){
        exprEl.textContent = expr || '\u00A0';
        resEl.textContent = (result === '' ? '0' : result);
    }

    function sanitize(s){ return s.replace(/[^0-9+\-*/.%()\s]/g,''); }

    function compute(input){
        try{
            const safe = sanitize(input).replace(/%/g, '/100');
            // use Function in a controlled way
            // eslint-disable-next-line no-new-func
            const val = Function('return ('+safe+')')();
            return (typeof val === 'number' && isFinite(val)) ? String(val) : 'Error';
        }catch(e){ return 'Error'; }
    }

    keys.addEventListener('click', (e) => {
        const t = e.target.closest('button'); if(!t) return;
        if(t.hasAttribute('data-num')){ expr += t.textContent.trim(); result=''; update(); return; }
        const act = t.getAttribute('data-action');
        if(act === 'clear'){ expr=''; result=''; update(); return; }
        if(act === 'del'){ expr = expr.slice(0,-1); update(); return; }
        if(act === 'op'){ expr += ' ' + t.textContent.trim() + ' '; update(); return; }
        if(act === 'equals'){
            const res = compute(expr);
            if(res !== 'Error' && expr.trim() !== ''){ history.push({expr:expr.trim(), res}); saveHistory(); renderHistory(); }
            result = res; expr = '';
            update(); return;
        }
    });

    // keyboard support
    function flashKeyLabel(label){
        const buttons = document.querySelectorAll('.key');
        for(const b of buttons){
            if(b.textContent.trim() === label){
                b.classList.add('key--active');
                setTimeout(() => b.classList.remove('key--active'), 150);
                break;
            }
        }
    }

    window.addEventListener('keydown', (e) => {
        if(e.ctrlKey || e.metaKey) return;
        const key = e.key;

        // support numpad operators
        const numpadMap = {
            'NumpadAdd': '+', 'NumpadSubtract': '-', 'NumpadMultiply': '*', 'NumpadDivide': '/', 'NumpadDecimal': '.'
        };
        if(e.code && e.code.startsWith('Numpad') && numpadMap[e.code]){
            const val = numpadMap[e.code];
            if(['+','-','*','/'].includes(val)){ expr += ' ' + val + ' '; }
            else { expr += val; }
            flashKeyLabel(val);
            update();
            return;
        }

        if(/^[0-9]$/.test(key)) { expr += key; flashKeyLabel(key); update(); return; }

        if(key === 'Enter' || key === '=') {
            e.preventDefault();
            const res = compute(expr);
            if(res !== 'Error' && expr.trim() !== ''){ history.push({expr:expr.trim(), res}); saveHistory(); renderHistory(); }
            result=res; expr=''; flashKeyLabel('='); update(); return;
        }

        if(key === 'Backspace'){ expr = expr.slice(0,-1); flashKeyLabel('⌫'); update(); return; }
        if(key === 'Delete'){ expr=''; result=''; flashKeyLabel('C'); update(); return; }
        if(key === 'Escape'){ expr=''; result=''; flashKeyLabel('C'); update(); return; }
        if(key === '.') { expr += '.'; flashKeyLabel('.'); update(); return; }
        if(['+','-','*','/','%','(',')'].includes(key)){ expr += ' ' + key + ' '; flashKeyLabel(key); update(); return; }
    });

    clearHistBtn.addEventListener('click', () => { history = []; saveHistory(); renderHistory(); });
    exportHistBtn.addEventListener('click', () => {
        const blob = new Blob([JSON.stringify(history, null, 2)], {type:'application/json'});
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = 'calculator-history.json'; a.click(); URL.revokeObjectURL(url);
    });

    // init
    loadHistory(); renderHistory(); update();
})();
</script>
</body>
</html>
