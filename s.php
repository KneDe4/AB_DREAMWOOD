<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Minecraft Skin Viewer</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  @import url('https://fonts.googleapis.com/css2?family=VT323&family=Inter:wght@400;500&display=swap');
  :root {
    --bg: #0d0f0e; --surface: #161a18; --border: #2a3028;
    --accent: #5dfc8d; --accent2: #3de870; --text: #e8f0e9; --muted: #5a6b5c;
  }
  html, body { width:100%; height:100%; background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; overflow:hidden; }
  #app { display:flex; flex-direction:column; align-items:center; justify-content:center; height:100vh; padding:16px; gap:10px; }
  #title { font-family:'VT323',monospace; font-size:22px; color:var(--accent); letter-spacing:2px; text-transform:uppercase; }
  #url-bar { display:flex; gap:6px; width:100%; max-width:520px; }
  #url-bar input { flex:1; background:var(--surface); border:1px solid var(--border); border-radius:6px; padding:7px 12px; color:var(--text); font-size:12px; font-family:'Inter',sans-serif; outline:none; transition:border-color .2s; }
  #url-bar input:focus { border-color:var(--accent); }
  #url-bar button { background:var(--accent); border:none; border-radius:6px; padding:7px 16px; color:#0d0f0e; font-size:12px; font-weight:500; cursor:pointer; transition:background .15s,transform .1s; white-space:nowrap; }
  #url-bar button:hover { background:var(--accent2); }
  #url-bar button:active { transform:scale(0.97); }
  #canvas-wrap { width:100%; max-width:520px; height:calc(100vh - 170px); min-height:260px; border:1px solid var(--border); border-radius:10px; overflow:hidden; background:radial-gradient(ellipse at 50% 30%,#1a2b1c 0%,#0d0f0e 70%); cursor:grab; position:relative; }
  #canvas-wrap:active { cursor:grabbing; }
  canvas { display:block; width:100%!important; height:100%!important; }
  #status { font-size:11px; color:var(--muted); min-height:14px; text-align:center; }
  #status.ok { color:var(--accent); }
  #status.err { color:#fc5d5d; }
  #btns { display:flex; gap:6px; flex-wrap:wrap; justify-content:center; }
  #btns button { background:var(--surface); border:1px solid var(--border); border-radius:6px; padding:6px 14px; color:var(--text); font-size:11px; cursor:pointer; transition:border-color .15s,background .15s; }
  #btns button:hover { border-color:var(--accent); background:#1e2720; }
  #hint { font-size:10px; color:var(--muted); text-align:center; }
</style>
</head>
<body>
<div id="app">
  <div id="title">&#x25A0; Skin Viewer</div>
  <div id="url-bar">
    <input type="text" id="skin-url" placeholder="https://example.com/skin.png" />
    <button onclick="loadSkin()">Загрузить</button>
  </div>
  <div id="status">Инициализация...</div>
  <div id="canvas-wrap"><canvas id="c"></canvas></div>
  <div id="btns">
    <button onclick="toggleSpin()" id="btn-spin">⏸ Пауза</button>
    <button onclick="resetCamera()">↺ Камера</button>
    <button onclick="toggleSlim()" id="btn-slim">Slim / Classic</button>
  </div>
  <div id="hint">Перетащи мышкой · колесо = зум · поддержка iframe</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script>
function getParam(name) {
  return new URLSearchParams(window.location.search).get(name);
}

const canvas = document.getElementById('c');
const renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
renderer.setClearColor(0x000000, 0);

const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 500);
camera.position.set(0, 0, 40);

scene.add(new THREE.AmbientLight(0xffffff, 0.9));
const dl = new THREE.DirectionalLight(0xffffff, 0.5);
dl.position.set(5, 10, 7);
scene.add(dl);

let skinGroup = null;
let isSlim = false;
let spinning = true;

function resize() {
  const w = canvas.parentElement.clientWidth;
  const h = canvas.parentElement.clientHeight;
  renderer.setSize(w, h);
  camera.aspect = w / h;
  camera.updateProjectionMatrix();
}
resize();
window.addEventListener('resize', resize);

// Set UV for one face of a BoxGeometry (4 verts per face)
// BoxGeometry face order: 0=+X(right) 1=-X(left) 2=+Y(top) 3=-Y(bottom) 4=+Z(front) 5=-Z(back)
function setFaceUV(uvArr, faceIndex, u0, v0, u1, v1) {
  const i = faceIndex * 8;
  uvArr[i+0]=u0; uvArr[i+1]=v1;
  uvArr[i+2]=u1; uvArr[i+3]=v1;
  uvArr[i+4]=u0; uvArr[i+5]=v0;
  uvArr[i+6]=u1; uvArr[i+7]=v0;
}

// r = [x0,y0,x1,y1] in pixels; TW/TH = texture size
function applyPartUV(geo, TW, TH, right, left, top, bottom, front, back) {
  const arr = geo.attributes.uv.array;
  function u(x) { return x/TW; }
  function v(y) { return 1-y/TH; }
  // face 0 = +X = right side of model
  setFaceUV(arr, 0, u(right[0]),  v(right[3]),  u(right[2]),  v(right[1]));
  // face 1 = -X = left side of model
  setFaceUV(arr, 1, u(left[0]),   v(left[3]),   u(left[2]),   v(left[1]));
  // face 2 = +Y = top
  setFaceUV(arr, 2, u(top[0]),    v(top[3]),    u(top[2]),    v(top[1]));
  // face 3 = -Y = bottom
  setFaceUV(arr, 3, u(bottom[0]), v(bottom[3]), u(bottom[2]), v(bottom[1]));
  // face 4 = +Z = front
  setFaceUV(arr, 4, u(front[0]),  v(front[3]),  u(front[2]),  v(front[1]));
  // face 5 = -Z = back
  setFaceUV(arr, 5, u(back[0]),   v(back[3]),   u(back[2]),   v(back[1]));
  geo.attributes.uv.needsUpdate = true;
}

function makePart(mat, w, h, d, right, left, top, bottom, front, back) {
  const geo = new THREE.BoxGeometry(w, h, d);
  applyPartUV(geo, 64, 64, right, left, top, bottom, front, back);
  return new THREE.Mesh(geo, mat);
}

function buildModel(texture, slim) {
  if (skinGroup) {
    scene.remove(skinGroup);
    skinGroup.traverse(o => { if (o.geometry) o.geometry.dispose(); });
  }
  skinGroup = new THREE.Group();

  texture.magFilter = THREE.NearestFilter;
  texture.minFilter = THREE.NearestFilter;
  const mat = new THREE.MeshLambertMaterial({ map: texture, transparent: true, alphaTest: 0.05 });

  const aw = slim ? 3 : 4;

  // HEAD 8x8x8
  // Layout: top(8,0)-(16,8), bottom(16,0)-(24,8)
  //         right(0,8)-(8,16), front(8,8)-(16,16), left(16,8)-(24,16), back(24,8)-(32,16)
  const head = makePart(mat, 8,8,8,
    [0,8,8,16],   // right  (+X face → MC right)
    [16,8,24,16], // left   (-X face → MC left)
    [8,0,16,8],   // top
    [16,0,24,8],  // bottom
    [8,8,16,16],  // front
    [24,8,32,16]  // back
  );
  head.position.set(0, 10, 0);

  // BODY 8x12x4
  const body = makePart(mat, 8,12,4,
    [16,20,20,32], // right
    [28,20,32,32], // left
    [20,16,28,20], // top
    [28,16,36,20], // bottom
    [20,20,28,32], // front
    [32,20,40,32]  // back
  );
  body.position.set(0, 0, 0);

  // RIGHT ARM
  const rArm = slim
    ? makePart(mat, 3,12,4,
        [40,20,44,32],[43,20,46,32],
        [43,16,46,20],[46,16,49,20],
        [44,20,47,32],[47,20,50,32])
    : makePart(mat, 4,12,4,
        [40,20,44,32],[48,20,52,32],
        [44,16,48,20],[48,16,52,20],
        [44,20,48,32],[52,20,56,32]);
  rArm.position.set(-(4 + aw/2), 0, 0);

  // LEFT ARM (1.8+ format, lower half of texture)
  const lArm = slim
    ? makePart(mat, 3,12,4,
        [32,52,36,64],[35,52,38,64],
        [35,48,38,52],[38,48,41,52],
        [36,52,39,64],[39,52,42,64])
    : makePart(mat, 4,12,4,
        [32,52,36,64],[40,52,44,64],
        [36,48,40,52],[40,48,44,52],
        [36,52,40,64],[44,52,48,64]);
  lArm.position.set(4 + aw/2, 0, 0);

  // RIGHT LEG 4x12x4
  const rLeg = makePart(mat, 4,12,4,
    [0,20,4,32],   // right
    [8,20,12,32],  // left
    [4,16,8,20],   // top
    [8,16,12,20],  // bottom
    [4,20,8,32],   // front
    [12,20,16,32]  // back
  );
  rLeg.position.set(-2, -12, 0);

  // LEFT LEG (1.8+ format)
  const lLeg = makePart(mat, 4,12,4,
    [16,52,20,64], // right
    [24,52,28,64], // left
    [20,48,24,52], // top
    [24,48,28,52], // bottom
    [20,52,24,64], // front
    [28,52,32,64]  // back
  );
  lLeg.position.set(2, -12, 0);

  skinGroup.add(head, body, rArm, lArm, rLeg, lLeg);
  skinGroup.position.y = 2;
  scene.add(skinGroup);
}

function setStatus(msg, type) {
  const el = document.getElementById('status');
  el.textContent = msg;
  el.className = type || '';
}

function loadSkinFromURL(url) {
  if (!url) return;
  document.getElementById('skin-url').value = url;
  setStatus('Загрузка...');
  const loader = new THREE.TextureLoader();
  loader.crossOrigin = 'anonymous';
  loader.load(url,
    tex => {
      buildModel(tex, isSlim);
      setStatus('Скин загружен: ' + url.split('/').pop(), 'ok');
    },
    undefined,
    () => setStatus('Ошибка загрузки. Проверь URL и CORS.', 'err')
  );
}

function loadSkin() {
  loadSkinFromURL(document.getElementById('skin-url').value.trim());
}

function toggleSpin() {
  spinning = !spinning;
  document.getElementById('btn-spin').textContent = spinning ? '⏸ Пауза' : '▶ Играть';
}

function resetCamera() { camera.position.set(0, 0, 40); }

function toggleSlim() {
  isSlim = !isSlim;
  if (skinGroup) {
    let tex = null;
    skinGroup.traverse(o => { if (o.material && o.material.map) tex = o.material.map; });
    if (tex) buildModel(tex, isSlim);
  }
}

let drag = false, lx = 0, ly = 0;
const wrap = document.getElementById('canvas-wrap');
wrap.addEventListener('mousedown', e => { drag=true; lx=e.clientX; ly=e.clientY; });
window.addEventListener('mouseup', () => drag=false);
window.addEventListener('mousemove', e => {
  if (!drag || !skinGroup) return;
  skinGroup.rotation.y += (e.clientX-lx)*0.01;
  skinGroup.rotation.x += (e.clientY-ly)*0.01;
  lx=e.clientX; ly=e.clientY;
});
wrap.addEventListener('touchstart', e => { drag=true; lx=e.touches[0].clientX; ly=e.touches[0].clientY; });
wrap.addEventListener('touchend', () => drag=false);
wrap.addEventListener('touchmove', e => {
  if (!drag || !skinGroup) return;
  skinGroup.rotation.y += (e.touches[0].clientX-lx)*0.015;
  skinGroup.rotation.x += (e.touches[0].clientY-ly)*0.015;
  lx=e.touches[0].clientX; ly=e.touches[0].clientY;
});
wrap.addEventListener('wheel', e => {
  camera.position.z = Math.max(15, Math.min(80, camera.position.z + e.deltaY*0.05));
});

function animate() {
  requestAnimationFrame(animate);
  if (spinning && skinGroup) skinGroup.rotation.y += 0.008;
  renderer.render(scene, camera);
}
animate();

const skinParam = getParam('skin');
const iframeMode = getParam('iframe') === '1';

if (iframeMode) {
  document.getElementById('title').style.display = 'none';
  document.getElementById('url-bar').style.display = 'none';
  document.getElementById('status').style.display = 'none';
  document.getElementById('btns').style.display = 'none';
  document.getElementById('hint').style.display = 'none';
  document.getElementById('app').style.gap = '0';
  document.getElementById('app').style.padding = '0';
  const cw = document.getElementById('canvas-wrap');
  cw.style.maxWidth = '100%';
  cw.style.height = '100vh';
  cw.style.borderRadius = '0';
  cw.style.border = 'none';
  cw.style.background = 'transparent';
  document.body.style.background = 'transparent';
  document.documentElement.style.background = 'transparent';
  renderer.setClearColor(0x000000, 0);
}

if (skinParam) {
  loadSkinFromURL(decodeURIComponent(skinParam));
} else {
  loadSkinFromURL('https://minotar.net/skin/Steve');
}
</script>
</body>
</html>
