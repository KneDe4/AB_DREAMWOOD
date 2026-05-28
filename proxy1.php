<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Proxy Checker</title>
<style>
  body {
    background: #eee;
    width: 60vw;
    margin: 10vh auto 6vh;
    font-family: system-ui, sans-serif;
    font-size: 14px;
    color: #222;
  }

  h1 { font-size: 1.5em; margin-bottom: 0.25em; }
  p.sub { opacity: 0.5; font-size: 0.88em; margin-bottom: 1.6em; }

  div { opacity: 0.8; }
  .no-fade { opacity: 1 !important; }

  .add-row { display: flex; gap: 6px; margin-bottom: 0.7em; }

  input[type="text"] {
    flex: 1;
    padding: 6px 10px;
    border: 1px solid #bbb;
    border-radius: 3px;
    background: #f7f7f7;
    font-family: system-ui, sans-serif;
    font-size: 13px;
    color: #222;
    outline: none;
  }
  input[type="text"]:focus { border-color: #348; background: #fff; }

  button {
    padding: 6px 13px;
    border: 1px solid #bbb;
    border-radius: 3px;
    background: #e4e4e4;
    font-family: system-ui, sans-serif;
    font-size: 13px;
    color: #333;
    cursor: pointer;
  }
  button:hover { background: #d8d8d8; }
  button:disabled { opacity: 0.45; cursor: not-allowed; }
  button.primary { background: #dde8f2; border-color: #9ab; color: #234; font-weight: 600; }
  button.primary:hover { background: #cddde9; }

  .act-row { display: flex; gap: 6px; margin-bottom: 1.6em; flex-wrap: wrap; }

  .stats {
    font-size: 0.85em;
    opacity: 0.55;
    margin-bottom: 1em;
    display: none;
  }
  .stats span { margin-right: 1.2em; }
  .s-ok { color: #285; }
  .s-fail { color: #833; }

  .proxy-list { display: flex; flex-direction: column; gap: 5px; }

  .card {
    background: #f5f5f5;
    border: 1px solid #ccc;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 11px;
  }

  .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #bbb;
    flex-shrink: 0;
    opacity: 1;
  }
  .dot.ok       { background: #4a9; }
  .dot.fail     { background: #c55; }
  .dot.checking { background: #68a; animation: bl 0.7s ease infinite; }
  @keyframes bl { 0%,100%{opacity:1} 50%{opacity:0.15} }

  .ip {
    flex: 1;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    color: #222;
    opacity: 1;
  }

  .chkbtn {
    font-size: 11px;
    padding: 2px 8px;
    color: #348;
    border-color: #bbb;
    background: transparent;
    opacity: 1;
  }

  .sep { width: 1px; height: 13px; background: #ccc; flex-shrink: 0; opacity: 1; }

  .st {
    font-size: 12px;
    min-width: 90px;
    text-align: right;
    color: #888;
    opacity: 1;
  }
  .st.ok       { color: #285; }
  .st.fail     { color: #833; }
  .st.checking { color: #348; }

  .pg {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    min-width: 56px;
    text-align: right;
    color: #aaa;
    opacity: 1;
  }
  .pg.fast { color: #3a8; }
  .pg.mid  { color: #964; }
  .pg.slow { color: #c55; }

  .delbtn {
    background: none;
    border: none;
    color: #bbb;
    font-size: 16px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    opacity: 1;
  }
  .delbtn:hover { color: #c55; background: none; }

  .empty { opacity: 0.4; text-align: center; padding: 2em 0; font-size: 0.9em; }

  a:link, a:visited { color: #348; }

  @media (max-width: 900px) { body { width: 90vw; } }
</style>
</head>
<body>

<h1>Proxy Checker</h1>

<div class="add-row">
  <input type="text" id="inp" placeholder="192.168.1.1:8080" />
  <button onclick="addProxy()">Добавить</button>
  <button onclick="addAndCheck()">Добавить + проверить</button>
</div>

<div class="act-row">
  <button class="primary" id="checkAllBtn" onclick="checkAll()">Проверить все</button>
  <button onclick="copyWorking()">Скопировать рабочие</button>
  <button onclick="clearAll()">Очистить</button>
</div>

<div class="stats no-fade" id="stats">
  Всего: <span><b id="s-t">0</b></span>
  <span class="s-ok">Работает: <b id="s-ok">0</b></span>
  <span class="s-fail">Не работает: <b id="s-f">0</b></span>
  Ср. пинг: <span><b id="s-p">—</b></span>
</div>

<div class="proxy-list no-fade" id="list">
  <div class="empty">Нет прокси — добавьте выше</div>
</div>

<script>
let proxies = [{"ip": "208.87.243.199:7878", "ping": 67, "s": "ok"},{"ip": "113.160.132.26:8080", "ping": 67, "s": "ok"},];

function render() {
  const list = document.getElementById('list');
  const stats = document.getElementById('stats');

  if (!proxies.length) {
    list.innerHTML = '<div class="empty">Нет прокси — добавьте выше</div>';
    stats.style.display = 'none';
    return;
  }

  const ok   = proxies.filter(p => p.s === 'ok').length;
  const fail = proxies.filter(p => p.s === 'fail').length;
  const pings = proxies.filter(p => p.ping > 0).map(p => p.ping);
  const avg = pings.length ? Math.round(pings.reduce((a,b) => a+b, 0) / pings.length) : null;

  stats.style.display = 'block';
  document.getElementById('s-t').textContent  = proxies.length;
  document.getElementById('s-ok').textContent = ok;
  document.getElementById('s-f').textContent  = fail;
  document.getElementById('s-p').textContent  = avg ? avg + ' мс' : '—';

  list.innerHTML = proxies.map((p, i) => {
    const sc    = p.s === 'ok' ? 'ok' : p.s === 'fail' ? 'fail' : p.s === 'checking' ? 'checking' : '';
    const stTxt = p.s === 'ok' ? 'Работает' : p.s === 'fail' ? 'Не работает' : p.s === 'checking' ? 'Проверка...' : 'Не проверен';

    let pgCls = '', pgTxt = '0 мс';
    if (p.ping > 0) {
      pgTxt = p.ping + ' мс';
      pgCls = p.ping < 400 ? 'fast' : p.ping < 800 ? 'mid' : 'slow';
    }

    return `<div class="card">
      <div class="dot ${sc}"></div>
      <span class="ip">${p.ip}</span>
      <button class="chkbtn" onclick="checkOne(${i})" ${p.s === 'checking' ? 'disabled' : ''}>проверить</button>
      <div class="sep"></div>
      <span class="st ${sc}">${stTxt}</span>
      <div class="sep"></div>
      <span class="pg ${pgCls}">${pgTxt}</span>
      <button class="delbtn" onclick="del(${i})">×</button>
    </div>`;
  }).join('');
}

async function callPhp(ip) {
  const res = await fetch('proxy.php?ip=' + encodeURIComponent(ip));
  return await res.json();
}

async function checkOne(i) {
  proxies[i].s = 'checking';
  proxies[i].ping = 0;
  render();

  try {
    const data = await callPhp(proxies[i].ip);
    proxies[i].s    = data.proxy ? 'ok' : 'fail';
    proxies[i].ping = data.ping || 0;
  } catch (e) {
    proxies[i].s    = 'fail';
    proxies[i].ping = 0;
  }

  render();
}

async function checkAll() {
  if (!proxies.length) return;
  const btn = document.getElementById('checkAllBtn');
  btn.disabled = true;
  await Promise.all(proxies.map((_, i) => checkOne(i)));
  btn.disabled = false;
}

function addProxy(ip) {
  if (!ip) {
    ip = document.getElementById('inp').value.trim();
    document.getElementById('inp').value = '';
  }
  if (!ip.match(/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d{1,5}$/)) return false;
  if (proxies.find(p => p.ip === ip)) return false;
  proxies.push({ ip, s: 'pending', ping: 0 });
  render();
  return true;
}

async function addAndCheck() {
  const ip = document.getElementById('inp').value.trim();
  if (addProxy(ip)) {
    const i = proxies.findIndex(p => p.ip === ip);
    if (i !== -1) checkOne(i);
  }
}

function del(i) { proxies.splice(i, 1); render(); }

function clearAll() {
  if (confirm('Удалить все прокси?')) { proxies = []; render(); }
}

async function copyWorking() {
  const list = proxies.filter(p => p.s === 'ok').map(p => p.ip).join('\n');
  if (!list) { alert('Нет рабочих прокси'); return; }
  await navigator.clipboard.writeText(list);
  alert('Скопировано ' + list.split('\n').length + ' прокси');
}

document.getElementById('inp').addEventListener('keydown', e => {
  if (e.key === 'Enter') addProxy();
});
checkAll()
</script>
</body>
</html>