@extends('layouts.app')
@section('title','Music')

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
  <div class="screen music-screen">

    {{-- Album art --}}
<div class="album-art" id="album-art">
  <div class="album-vinyl-wrap">
    <div class="album-vinyl" id="vinyl">
      <div class="vinyl-inner"></div>
    </div>
  </div>
</div>

    {{-- Song info --}}
    <div class="song-info">
      <div class="song-title" id="song-title">On Bended Knee</div>
      <div class="song-artist" id="song-artist">Boyz II Men</div>
    </div>

    {{-- Progress bar --}}
    <div class="progress-row">
      <span class="time-label" id="time-cur">0:00</span>
      <div class="music-progress" id="progress-track" onclick="seekTo(event)">
        <div class="music-bar" id="music-bar"></div>
        <div class="music-thumb" id="music-thumb"></div>
      </div>
      <span class="time-label" id="time-dur">0:00</span>
    </div>

    {{-- Visualizer bars --}}
    <div class="visualizer" id="visualizer">
      @for($i = 0; $i < 20; $i++)
        <div class="vis-bar" style="animation-delay: {{ $i * 0.07 }}s"></div>
      @endfor
    </div>

    {{-- Controls --}}
    <div class="music-controls">
      <button class="ctrl-btn ctrl-prev" onclick="prevSong()">⏮</button>
      <button class="ctrl-btn ctrl-play" id="play-btn" onclick="togglePlay()">▶</button>
      <button class="ctrl-btn ctrl-next" onclick="nextSong()">⏭</button>
    </div>

    {{-- Volume --}}
    <div class="volume-row">
      <button class="vol-btn" id="vol-btn" onclick="toggleMute()">🔊</button>
      <input class="vol-slider" id="vol-slider" type="range" min="0" max="100" value="80"
             oninput="setVolume(this.value)">
    </div>

    {{-- Playlist --}}
    <div class="playlist-section">
      <div class="playlist-title">PLAYLIST:</div>
      <div class="playlist-list" id="playlist-list"></div>
    </div>

  </div>
  <div class="battery-row">
    <span class="battery-dot"></span>
    <span class="battery-label">BATTERY</span>
  </div>
</div>

{{-- Bottom actions --}}
<div class="action-btns">
  <a href="{{ route('home') }}" class="action-btn action-next" style="background:linear-gradient(180deg,#60d060,#28a028);box-shadow:0 5px 0 #0a5000;">SELANJUTNYA</a>
  <a href="{{ route('home') }}" class="action-btn action-back">KEMBALI</a>
</div>

<audio id="audio" preload="auto"></audio>

<script>
const playlist = [
  { 
    title: "Waking Up Together With You",
    artist: "Kahitna",
    src: "{{ asset('lagu/1.mp3') }}",
    cover: "{{ asset('images/album/1.jpg') }}"
  },
  { 
    title: "Last Night On Earth",
    artist: "Green Day",
    src: "{{ asset('lagu/2.mp3') }}",
    cover: "{{ asset('images/album/2.jpg') }}"
  },
  { 
    title: "Pelangi",
    artist: "Hivi",
    src: "{{ asset('lagu/3.mp3') }}",
    cover: "{{ asset('images/album/3.jpg') }}"
  },
  { 
    title: "Soulmate",
    artist: "Kahitna",
    src: "{{ asset('lagu/4.mp3') }}",
    cover: "{{ asset('images/album/1.png') }}"
  }
];
let idx = 0;
let muted = false;
const audio   = document.getElementById('audio');
const playBtn = document.getElementById('play-btn');
const vinyl   = document.getElementById('vinyl');
const visualizer = document.getElementById('visualizer');

// ── Build playlist UI ──
function buildPlaylist() {
  const list = document.getElementById('playlist-list');
  list.innerHTML = playlist.map((s, i) => `
    <div class="pl-item ${i === idx ? 'pl-active' : ''}" id="pl-${i}" onclick="loadSong(${i}, true)">
      <span class="pl-num">${i + 1}.</span>
      <span class="pl-name">${s.title}</span>
      <span class="pl-dur">${s.dur}</span>
    </div>
  `).join('');
}

// ── Load song ──
function loadSong(i, autoplay = false) {
  idx = i;
  const s = playlist[idx];

  audio.src = s.src;

  document.getElementById('song-title').textContent  = s.title;
  document.getElementById('song-artist').textContent = s.artist;

  // 🔥 Tambahkan ini
  document.getElementById('album-art').style.backgroundImage = `url(${s.cover})`;
  document.getElementById('album-art').style.backgroundSize = 'cover';
  document.getElementById('album-art').style.backgroundPosition = 'center';

  document.getElementById('music-bar').style.width   = '0%';
  document.getElementById('music-thumb').style.left  = '0%';
  document.getElementById('time-cur').textContent    = '0:00';

  buildPlaylist();

  if (autoplay) {
    audio.play();
    setPlaying(true);
  }
}

// ── Play / pause ──
function setPlaying(on) {
  playBtn.textContent = on ? '⏸' : '▶';
  if (on) {
    vinyl.classList.add('spinning');
    visualizer.classList.add('active');
  } else {
    vinyl.classList.remove('spinning');
    visualizer.classList.remove('active');
  }
}

function togglePlay() {
  if (audio.paused) { audio.play(); setPlaying(true); }
  else              { audio.pause(); setPlaying(false); }
}

function nextSong() { loadSong((idx + 1) % playlist.length, true); }
function prevSong() { loadSong((idx - 1 + playlist.length) % playlist.length, true); }

// ── Progress ──
function fmt(s) {
  const m = Math.floor(s / 60);
  const sec = Math.floor(s % 60).toString().padStart(2, '0');
  return `${m}:${sec}`;
}

audio.addEventListener('timeupdate', () => {
  if (!audio.duration) return;
  const pct = (audio.currentTime / audio.duration) * 100;
  document.getElementById('music-bar').style.width  = pct + '%';
  document.getElementById('music-thumb').style.left = pct + '%';
  document.getElementById('time-cur').textContent   = fmt(audio.currentTime);
});

audio.addEventListener('loadedmetadata', () => {
  document.getElementById('time-dur').textContent = fmt(audio.duration);
});

audio.addEventListener('ended', nextSong);

function seekTo(e) {
  const track = document.getElementById('progress-track');
  const rect  = track.getBoundingClientRect();
  const pct   = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
  audio.currentTime = pct * audio.duration;
}

// ── Volume ──
function setVolume(v) {
  audio.volume = v / 100;
  document.getElementById('vol-btn').textContent = v == 0 ? '🔇' : '🔊';
}

function toggleMute() {
  muted = !muted;
  audio.muted = muted;
  document.getElementById('vol-btn').textContent = muted ? '🔇' : '🔊';
}

// ── Init ──
loadSong(0);
</script>
@endsection