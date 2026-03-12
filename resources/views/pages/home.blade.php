@extends('layouts.app')
@section('title','Happy Birthday!')

@section('content')

<body style="
  min-height:100vh;
  background:url('{{ asset('images/3.jpeg') }}') center center / cover no-repeat fixed;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:30px;
"></body>

{{-- Top bar --}}
<div class="topbar">
  <div class="power-dot"></div>
  <span class="power-label">POWER</span>
  <span class="brand-name">HEYTML-BOY</span>
</div>

{{-- Screen --}}
<div class="screen-wrap">
  <div class="screen-label">DOT MATRIX WITH STEREO SOUND</div>
  <div class="screen">
    <div class="screen-content">
      <div class="bday-title">Happy<br>Birthday!</div>
      <div class="bday-sub">Press Start Button</div>
    </div>
  </div>
  <div class="battery-row">
    <span class="battery-dot"></span>
    <span class="battery-label">BATTERY</span>
  </div>
</div>

{{-- Menu Buttons --}}
<div class="btn-grid">
  <a class="btn btn-blue"   href="{{ route('message') }}">MESSAGE</a>
  <a class="btn btn-red"    href="{{ route('gallery') }}">GALLERY</a>
  <a class="btn btn-purple" href="{{ route('music') }}">MUSIC</a>
  <a class="btn btn-green"  href="{{ route('tetris') }}">TETRIS</a>
</div>

{{-- D-pad + A/B --}}
<div class="controls">
  <div class="dpad" id="dpad">
    <div class="dpad-h"></div>
    <div class="dpad-v"></div>
    <div class="dpad-center"></div>
    <div class="dpad-arrow dpad-arrow-up">▲</div>
    <div class="dpad-arrow dpad-arrow-down">▼</div>
    <div class="dpad-arrow dpad-arrow-left">◀</div>
    <div class="dpad-arrow dpad-arrow-right">▶</div>
  </div>

  <div class="ab-buttons">
    <div class="ab-wrap">
      <button class="ab-btn btn-b" id="btn-b">B</button>
      <button class="ab-btn btn-a" id="btn-a">A</button>
    </div>
    <div class="ab-labels">
      <span class="ab-label">back</span>
      <span class="ab-label">select</span>
    </div>
  </div>
</div>

{{-- Select / Start --}}
<div class="bottom-btns">
  <div class="small-btn-wrap">
    <button class="small-btn">SELECT</button>
    <button class="small-btn" id="start-btn">START</button>
  </div>
</div>

<script>
const grid = [[0,1],[2,3]];
let curRow = 0, curCol = 0;

function getBtns() { return Array.from(document.querySelectorAll('.btn-grid .btn')); }
function getIdx()  { return grid[curRow][curCol]; }

function updateFocus() {
  getBtns().forEach(b => b.classList.remove('dpad-focus'));
  const btn = getBtns()[getIdx()];
  if (btn) btn.classList.add('dpad-focus');
}

function dpadUp()    { if (curRow > 0) { curRow--; updateFocus(); vibrate(); spawnRipple('up'); } }
function dpadDown()  { if (curRow < grid.length-1) { curRow++; updateFocus(); vibrate(); spawnRipple('down'); } }
function dpadLeft()  { if (curCol > 0) { curCol--; updateFocus(); vibrate(); spawnRipple('left'); } }
function dpadRight() { if (curCol < grid[0].length-1) { curCol++; updateFocus(); vibrate(); spawnRipple('right'); } }

function btnA() {
  const btn = getBtns()[getIdx()];
  if (!btn) return;
  vibrate(60);
  btn.classList.add('dpad-pressed');
  setTimeout(() => { btn.classList.remove('dpad-pressed'); window.location.href = btn.getAttribute('href'); }, 160);
}

function btnB() {
  vibrate(30);
  const btn = getBtns()[getIdx()];
  if (btn) { btn.classList.add('dpad-pressed'); setTimeout(() => btn.classList.remove('dpad-pressed'), 120); }
}

function vibrate(ms = 40) { if (navigator.vibrate) navigator.vibrate(ms); }

function spawnRipple(dir) {
  const dpad = document.getElementById('dpad');
  const r    = document.createElement('div');
  r.className = 'dpad-ripple';
  const w = dpad.offsetWidth, h = dpad.offsetHeight;
  const pos = { up:[w/2,h/6], down:[w/2,h*5/6], left:[w/6,h/2], right:[w*5/6,h/2] }[dir];
  r.style.left = pos[0] + 'px';
  r.style.top  = pos[1] + 'px';
  dpad.appendChild(r);
  r.addEventListener('animationend', () => r.remove());
}

// D-pad click zones
document.getElementById('dpad').addEventListener('click', e => {
  const rect = e.currentTarget.getBoundingClientRect();
  const x = e.clientX - rect.left, y = e.clientY - rect.top;
  const col = x < rect.width/3 ? 0 : x < 2*rect.width/3 ? 1 : 2;
  const row = y < rect.height/3 ? 0 : y < 2*rect.height/3 ? 1 : 2;
  if      (row===0&&col===1) dpadUp();
  else if (row===2&&col===1) dpadDown();
  else if (row===1&&col===0) dpadLeft();
  else if (row===1&&col===2) dpadRight();
});

// Swipe on d-pad
let tx=0, ty=0;
document.getElementById('dpad').addEventListener('touchstart', e => { tx=e.touches[0].clientX; ty=e.touches[0].clientY; }, {passive:true});
document.getElementById('dpad').addEventListener('touchend',   e => {
  const dx=e.changedTouches[0].clientX-tx, dy=e.changedTouches[0].clientY-ty;
  if (Math.abs(dx)<5&&Math.abs(dy)<5) return;
  Math.abs(dx)>Math.abs(dy) ? (dx>0?dpadRight():dpadLeft()) : (dy>0?dpadDown():dpadUp());
}, {passive:true});

document.getElementById('btn-a').addEventListener('click', btnA);
document.getElementById('btn-b').addEventListener('click', btnB);
document.getElementById('start-btn').addEventListener('click', btnA);

document.addEventListener('keydown', e => {
  const map = { ArrowUp:dpadUp, ArrowDown:dpadDown, ArrowLeft:dpadLeft, ArrowRight:dpadRight, Enter:btnA, z:btnA, Z:btnA, x:btnB, X:btnB };
  if (map[e.key]) { e.preventDefault(); map[e.key](); }
});

updateFocus();
</script>
@endsection