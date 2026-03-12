@extends('layouts.app')
@section('title','Tetris')

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
  <span class="brand-name">PIANPUNKK</span>
</div>

{{-- Screen --}}
<div class="screen-wrap">
  <div class="screen-label">DOT MATRIX WITH STEREO SOUND</div>
  <div class="screen tetris-screen">

    {{-- Header --}}
    <div class="tetris-header">
      <div class="tetris-title">Tetris</div>
      <div class="tetris-stats">
        <span>Score: <b id="score">0</b></span>
        <span>Level: <b id="level">1</b></span>
      </div>
      <div class="tetris-stats">
        <span>Lines: <b id="lines">0</b></span>
      </div>
    </div>

    {{-- Canvas --}}
    <div class="canvas-wrap">
      <canvas id="cv" width="200" height="400"></canvas>
    </div>

    {{-- Mobile controls --}}
    <div class="tetris-controls">
      <button class="tctrl-btn" id="btn-left"   ontouchstart="ctrlLeft()"   onclick="ctrlLeft()">◀</button>
      <button class="tctrl-btn tctrl-rotate" id="btn-rot" ontouchstart="ctrlRotate()" onclick="ctrlRotate()">ROTATE</button>
      <button class="tctrl-btn" id="btn-right"  ontouchstart="ctrlRight()"  onclick="ctrlRight()">▶</button>
    </div>

    {{-- Soft drop hint --}}
    <div class="tetris-hint">Hold ROTATE = drop ▼</div>

  </div>
  <div class="battery-row">
    <span class="battery-dot"></span>
    <span class="battery-label">BATTERY</span>
  </div>
</div>

{{-- Popup overlays --}}
<div class="popup-overlay" id="popup-gameover" style="display:none;">
  <div class="popup-box popup-gameover-box">
    <div class="popup-go-title">GAME<br>OVER</div>
    <button class="popup-btn popup-confirm-btn" onclick="showLoveMsg()">CONFIRM</button>
  </div>
</div>

<div class="popup-overlay" id="popup-love" style="display:none;">
  <div class="popup-box popup-love-box">
    <div class="popup-love-title">INGET YA!</div>
    <div class="popup-love-body">walaupun kamu kalah,<br>tapi kamu selalu menang<br>kok di hati aku, HEHE<br>^_^</div>
    <div class="popup-love-heart">I LOVE YOU &lt;3</div>
    <button class="popup-btn popup-ok-btn" onclick="restartGame()">OK</button>
  </div>
</div>

{{-- Bottom actions --}}
<div class="action-btns">
  <a href="{{ route('home') }}" class="action-btn action-back">KEMBALI</a>
</div>

<script>
const canvas = document.getElementById('cv');
const ctx    = canvas.getContext('2d');

const COLS = 10, ROWS = 20, BS = 20;

const COLORS = [
  null,
  '#ff4444', // I - red
  '#ffaa00', // O - orange
  '#aa44ff', // T - purple
  '#4488ff', // J - blue
  '#ff8844', // L - orange2
  '#44ddff', // S - cyan
  '#ffdd44', // Z - yellow
];

let board, score, lines, level;

function initBoard() {
  board = Array.from({length: ROWS}, () => Array(COLS).fill(0));
  score = 0; lines = 0; level = 1;
  document.getElementById('score').textContent = 0;
  document.getElementById('lines').textContent = 0;
  document.getElementById('level').textContent = 1;
}

const SHAPES = [
  [[1,1,1,1]],              // I
  [[2,2],[2,2]],            // O
  [[0,3,0],[3,3,3]],        // T
  [[4,0,0],[4,4,4]],        // J
  [[0,0,5],[5,5,5]],        // L
  [[0,6,6],[6,6,0]],        // S
  [[7,7,0],[0,7,7]],        // Z
];

function randPiece() {
  const idx = Math.floor(Math.random() * SHAPES.length);
  const shape = SHAPES[idx].map(r => r.slice());
  return { x: Math.floor(COLS/2) - Math.floor(shape[0].length/2), y: 0, m: shape };
}

let piece, dropCounter = 0, lastTime = 0, running = true;

function drawCell(x, y, colorIdx, ghost = false) {
  if (colorIdx === 0) {
    ctx.fillStyle = 'rgba(255,255,255,0.04)';
    ctx.fillRect(x*BS+1, y*BS+1, BS-2, BS-2);
    return;
  }
  const c = COLORS[colorIdx];
  if (ghost) {
    ctx.fillStyle = 'rgba(255,255,255,0.08)';
    ctx.strokeStyle = c;
    ctx.lineWidth = 1;
    ctx.strokeRect(x*BS+1, y*BS+1, BS-2, BS-2);
    ctx.fillRect(x*BS+1, y*BS+1, BS-2, BS-2);
    return;
  }
  // main fill
  ctx.fillStyle = c;
  ctx.fillRect(x*BS+1, y*BS+1, BS-2, BS-2);
  // highlight
  ctx.fillStyle = 'rgba(255,255,255,0.3)';
  ctx.fillRect(x*BS+1, y*BS+1, BS-2, 4);
  ctx.fillRect(x*BS+1, y*BS+1, 4, BS-2);
  // shadow
  ctx.fillStyle = 'rgba(0,0,0,0.25)';
  ctx.fillRect(x*BS+1, y*BS+BS-5, BS-2, 4);
  ctx.fillRect(x*BS+BS-5, y*BS+1, 4, BS-2);
}

function getGhostY() {
  let gy = piece.y;
  while (!collide(piece.x, gy+1, piece.m)) gy++;
  return gy;
}

function draw() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  // grid bg
  for (let y=0; y<ROWS; y++)
    for (let x=0; x<COLS; x++)
      drawCell(x, y, board[y][x]);
  // ghost
  const gy = getGhostY();
  if (gy !== piece.y) {
    piece.m.forEach((row, dy) =>
      row.forEach((v, dx) => { if (v) drawCell(piece.x+dx, gy+dy, v, true); })
    );
  }
  // active piece
  piece.m.forEach((row, dy) =>
    row.forEach((v, dx) => { if (v && piece.y+dy >= 0) drawCell(piece.x+dx, piece.y+dy, v); })
  );
}

function collide(px, py, mat) {
  for (let y=0; y<mat.length; y++)
    for (let x=0; x<mat[y].length; x++) {
      if (!mat[y][x]) continue;
      const nx = px+x, ny = py+y;
      if (nx < 0 || nx >= COLS || ny >= ROWS) return true;
      if (ny >= 0 && board[ny][nx]) return true;
    }
  return false;
}

function merge() {
  piece.m.forEach((row, dy) =>
    row.forEach((v, dx) => {
      if (v && piece.y+dy >= 0) board[piece.y+dy][piece.x+dx] = v;
    })
  );
}

function rotate(mat) {
  return mat[0].map((_, i) => mat.map(r => r[i]).reverse());
}

function clearLines() {
  let cleared = 0;
  for (let y=ROWS-1; y>=0;) {
    if (board[y].every(v => v !== 0)) {
      board.splice(y, 1);
      board.unshift(Array(COLS).fill(0));
      cleared++;
    } else y--;
  }
  if (cleared) {
    lines += cleared;
    score += [0, 100, 300, 500, 800][cleared] * level;
    level = Math.floor(lines / 10) + 1;
    document.getElementById('score').textContent = score;
    document.getElementById('lines').textContent = lines;
    document.getElementById('level').textContent = level;
  }
}

function drop() {
  if (!collide(piece.x, piece.y+1, piece.m)) {
    piece.y++;
  } else {
    merge();
    clearLines();
    piece = randPiece();
    if (collide(piece.x, piece.y, piece.m)) {
      gameOver();
      return;
    }
  }
  dropCounter = 0;
}

function gameOver() {
  running = false;
  document.getElementById('popup-gameover').style.display = 'flex';
}

function showLoveMsg() {
  document.getElementById('popup-gameover').style.display = 'none';
  document.getElementById('popup-love').style.display = 'flex';
}

function restartGame() {
  document.getElementById('popup-love').style.display = 'none';
  initBoard();
  piece = randPiece();
  running = true;
  dropCounter = 0;
  lastTime = 0;
  requestAnimationFrame(update);
}

function update(t = 0) {
  if (!running) return;
  const dt = t - lastTime;
  lastTime = t;
  dropCounter += dt;
  const speed = Math.max(80, 500 - (level-1)*40);
  if (dropCounter > speed) drop();
  draw();
  requestAnimationFrame(update);
}

// ── Controls ──
function ctrlLeft()   { if (!collide(piece.x-1, piece.y, piece.m)) { piece.x--; draw(); } }
function ctrlRight()  { if (!collide(piece.x+1, piece.y, piece.m)) { piece.x++; draw(); } }
function ctrlRotate() { const r = rotate(piece.m); if (!collide(piece.x, piece.y, r)) { piece.m = r; draw(); } }
function ctrlDown()   { drop(); draw(); }

document.addEventListener('keydown', e => {
  if (e.key === 'ArrowLeft')  ctrlLeft();
  if (e.key === 'ArrowRight') ctrlRight();
  if (e.key === 'ArrowDown')  ctrlDown();
  if (e.code === 'Space')     ctrlRotate();
});

// Long-press ROTATE = soft drop
let rotateInterval = null;
document.getElementById('btn-rot').addEventListener('touchstart', e => {
  e.preventDefault();
  rotateInterval = setInterval(ctrlDown, 80);
});
document.getElementById('btn-rot').addEventListener('touchend', () => {
  clearInterval(rotateInterval);
  rotateInterval = null;
  ctrlRotate();
});

// ── Init ──
initBoard();
piece = randPiece();
requestAnimationFrame(update);
</script>
@endsection