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
