@extends('layouts.app')
@section('title','Loading...')

@section('content')

<style>
  body {
    background: url('{{ asset('images/3.jpeg') }}') center center / cover no-repeat fixed !important;
  }
  /* Overlay gelap HANYA di belakang konsol — pakai pseudo-element di .wrap */
  .wrap {
    position: relative;
  }
  .wrap::before {
    content: '';
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.55);
    z-index: 0;
    pointer-events: none;
  }
  /* Konsol di atas overlay */
  .console {
    position: relative;
    z-index: 1;
  }
</style>

<div class="loading-screen">
  {{-- Stars background --}}
  <div class="stars" id="stars"></div>

  {{-- Terminal box --}}
  <div class="terminal-box">
    <div class="terminal-title">Wawak Day</div>
    <div class="terminal-prompt">&gt; READY! <span class="cursor">_</span></div>
    <div class="progress-wrap">
      <div class="progress-bar" id="bar"></div>
      <span class="progress-pct" id="pct">0%</span>
    </div>
    <div class="terminal-smile" id="smile">SMILE WAWAK!</div>
  </div>
</div>

<script>
  // Generate random stars
  const starsEl = document.getElementById('stars');
  for (let i = 0; i < 120; i++) {
    const s = document.createElement('div');
    s.className = 'star';
    s.style.cssText = `
      left:${Math.random()*100}%;
      top:${Math.random()*100}%;
      width:${Math.random()*2+1}px;
      height:${Math.random()*2+1}px;
      animation-delay:${Math.random()*4}s;
      animation-duration:${Math.random()*3+2}s;
    `;
    starsEl.appendChild(s);
  }

  // Loading bar
  let p = 0;
  const bar = document.getElementById('bar');
  const pct = document.getElementById('pct');
  const smile = document.getElementById('smile');

  const t = setInterval(() => {
    p += Math.floor(Math.random() * 8) + 3;
    if (p >= 100) p = 100;
    bar.style.width = p + '%';
    pct.textContent = p + '%';
    if (p === 100) {
      clearInterval(t);
      smile.style.opacity = '1';
      setTimeout(() => window.location.href = "{{ route('home') }}", 800);
    }
  }, 140);
</script>
@endsection