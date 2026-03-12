@extends('layouts.app')
@section('title','Gallery')

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

{{-- Tab switcher --}}
<div class="tab-row">
  <button class="tab-btn active" id="tab-photo" onclick="switchTab('photo')">📸 PHOTOBOX</button>
  <button class="tab-btn" id="tab-clip" onclick="switchTab('clip')">🎬 CLIP DUMP</button>
</div>

{{-- ══ PHOTOBOX SCREEN ══ --}}
<div id="panel-photo">
  <div class="screen-wrap">
    <div class="screen-label">DOT MATRIX WITH STEREO SOUND</div>
    <div class="screen photobox-screen" id="photobox-screen">

      {{-- Idle state --}}
      <div id="photobox-idle" class="photobox-idle">
        <div class="photobox-idle-text">Photobox siap digunakan</div>
        <button class="mulai-btn" onclick="startPhotobox()">MULAI CETAK</button>
      </div>

      {{-- Polaroid viewer --}}
      <div id="photobox-viewer" class="photobox-viewer" style="display:none;">
        <div class="polaroid" id="polaroid">
          <div class="polaroid-img-wrap">
            <img id="polaroid-img" src="" alt="photo" />
          </div>
          <div class="polaroid-date" id="polaroid-date">20/04/25</div>
          <div class="polaroid-dots">
            <span class="pdot"></span><span class="pdot"></span>
          </div>
        </div>
        <div class="photobox-nav">
          <button class="nav-btn" onclick="prevPhoto()">◀</button>
          <span class="nav-count" id="nav-count">1 / 6</span>
          <button class="nav-btn" onclick="nextPhoto()">▶</button>
        </div>
      </div>

    </div>
    <div class="battery-row">
      <span class="battery-dot"></span>
      <span class="battery-label">BATTERY</span>
    </div>
  </div>
</div>

{{-- ══ CLIP DUMP SCREEN ══ --}}
<div id="panel-clip" style="display:none;">
  <div class="screen-wrap clip-screen-wrap">
    <div class="screen-label">DOT MATRIX WITH STEREO SOUND</div>
    <div class="screen clip-screen">
      <div class="clip-title">Clip Dump</div>

      @foreach(['clip1.mp4', 'clip2.mp4'] as $clip)
      <div class="clip-card">
        <video class="clip-video" controls preload="metadata">
          <source src="{{ asset('videos/1.mp4' . $clip) }}" type="videos/1.mp4">
        </video>
        <div class="clip-label">Clip Dump</div>
      </div>
      @endforeach

    </div>
    <div class="battery-row">
      <span class="battery-dot"></span>
      <span class="battery-label">BATTERY</span>
    </div>
  </div>
</div>

{{-- Bottom actions --}}
<div class="action-btns">
  <a href="{{ route('home') }}" class="action-btn action-back">KEMBALI</a>
</div>

<script>
// ── Photos data ──
const photos = [
  { src: "{{ asset('images/1.jpeg') }}", date: '20/04/25' },
  { src: "{{ asset('images/2.jpeg') }}", date: '20/04/25' },
  { src: "{{ asset('images/3.jpeg') }}", date: '14/02/25' },
  { src: "{{ asset('images/4.jpeg') }}", date: '01/01/25' },
  { src: "{{ asset('images/5.jpeg') }}", date: '25/12/24' },
  { src: "{{ asset('images/6.jpeg') }}", date: '17/08/24' },
];

let current = 0;

function startPhotobox() {
  document.getElementById('photobox-idle').style.display = 'none';
  document.getElementById('photobox-viewer').style.display = 'flex';
  showPhoto(0);
}

function showPhoto(idx) {
  current = (idx + photos.length) % photos.length;
  const p = photos[current];

  const polaroid = document.getElementById('polaroid');
  polaroid.classList.remove('print-anim');
  void polaroid.offsetWidth; // reflow
  polaroid.classList.add('print-anim');

  document.getElementById('polaroid-img').src = p.src;
  document.getElementById('polaroid-date').textContent = p.date;
  document.getElementById('nav-count').textContent = `${current + 1} / ${photos.length}`;
}

function nextPhoto() { showPhoto(current + 1); }
function prevPhoto() { showPhoto(current - 1); }

// ── Tab switcher ──
function switchTab(tab) {
  document.getElementById('panel-photo').style.display = tab === 'photo' ? 'block' : 'none';
  document.getElementById('panel-clip').style.display  = tab === 'clip'  ? 'block' : 'none';
  document.getElementById('tab-photo').classList.toggle('active', tab === 'photo');
  document.getElementById('tab-clip').classList.toggle('active',  tab === 'clip');
}
</script>
@endsection