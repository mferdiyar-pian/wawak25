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
  <span class="brand-name">PIANPUNKK</span>
</div>

{{-- Screen --}}
<div class="screen-wrap">
  <div class="screen-label">DOT MATRIX WITH STEREO SOUND</div>

  <div class="screen">

    {{-- bunga / efek lucu --}}
    <div class="flower-layer">
      <span>🌸</span>
      <span>💚</span>
      <span>🌷</span>
      <span>✨</span>
      <span>🌸</span>
    </div>

    <div class="screen-content" id="screenContent">
      <div class="screen-center">
        <div class="bday-title">
          Happy Birthday,<br>
          Najwa Khadijah 💚
        </div>
        <div class="bday-sub">Press START</div>
      </div>
    </div>
  </div>

  <div class="battery-row">
    <span class="battery-dot"></span>
    <span class="battery-label">BATTERY</span>
  </div>
</div>

{{-- Menu Buttons --}}
<div class="btn-grid">
  <a class="btn btn-blue" href="{{ route('message') }}">MESSAGE</a>
  <a class="btn btn-red" href="{{ route('gallery') }}">GALLERY</a>
  <a class="btn btn-purple" href="{{ route('music') }}">MUSIC</a>
  <a class="btn btn-green" href="{{ route('tetris') }}">TETRIS</a>
</div>

{{-- Controls --}}
<div class="controls">
  <div class="dpad">
    <div class="dpad-h"></div>
    <div class="dpad-v"></div>
    <div class="dpad-center"></div>
  </div>

  <div class="ab-wrap">
    <button class="ab-btn btn-b">B</button>
    <button class="ab-btn btn-a">A</button>
  </div>
</div>

{{-- Select / Start --}}
<div class="bottom-btns">
  <div class="small-btn-wrap">
    <button class="small-btn">SELECT</button>
    <button class="small-btn" id="startBtn">START</button>
  </div>
</div>

<script>
document.getElementById('startBtn').addEventListener('click', function () {
    const screen = document.getElementById('screenContent');
    screen.innerHTML = `
        <div class="screen-center">
          <div class="bday-title">
              Let's Begin 💖
          </div>
          <div class="bday-sub">Choose a Menu</div>
        </div>
    `;
});
</script>

@endsection