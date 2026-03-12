@extends('layouts.app')
@section('title','Message')

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
  <div class="screen-label">IKAN HIU MAKAN TOMAT ILOVE SOMAT</div>
  <div class="screen msg-screen">
    <div class="msg-title">Message</div>
    <div id="msg-box" class="msg-body"></div>

    {{-- SKIP button (inside screen) --}}
    <div id="skip-wrap" class="skip-wrap">
      <button id="skip-btn" class="skip-btn">SKIP</button>
    </div>
  </div>
  <div class="battery-row">
    <span class="battery-dot"></span>
    <span class="battery-label">BATTERY</span>
  </div>
</div>

{{-- Bottom action buttons --}}
<div class="action-btns">
  <a id="next-btn" href="{{ route('home') }}" class="action-btn action-next">SELANJUTNYA</a>
  <a href="{{ route('home') }}" class="action-btn action-back">KEMBALI</a>
</div>

<script>
const fullText =
`Hi Cel,

Happy Birthday!

Hari ini adalah hari yang paling spesial, karena seseorang yang paling berharga buat aku lahir ke dunia 🤍

Makasih ya udah jadi temen yang selalu ada, yang kocak-kocak dan gak biasa, karena kamu tuh unik banget! Aku selalu bersyukur bisa ngeliat kamu jadi versi terbaik dari dirimu, yang kadang-kadang lucu banget pas lagi baper, tapi juga selalu bikin aku tersenyum tanpa henti.

Makasih udah jadi temen curhat, partner in crime, dan sumber inspirasi sehari-hari. Semoga tahun ini kamu makin kece, makin banyak momen bahagia, dan makin dicintai, karena kamu emang pantas dapetin semua itu. Jangan lupa, kita bakal terus jalan bareng, ngejar mimpi, dan ngelewatin segala drama hidup dengan tawa.

I love you <3`;

const box     = document.getElementById('msg-box');
const skipBtn = document.getElementById('skip-btn');
const skipWrap= document.getElementById('skip-wrap');
const nextBtn = document.getElementById('next-btn');

let i = 0, done = false;

function renderAll() {
  box.textContent = fullText;
  done = true;
  skipWrap.style.display = 'none';
  clearInterval(timer);
}

skipBtn.addEventListener('click', renderAll);

const timer = setInterval(() => {
  if (done) return;
  box.textContent += fullText[i] ?? '';
  i++;
  if (i >= fullText.length) {
    done = true;
    skipWrap.style.display = 'none';
    clearInterval(timer);
  }
}, 28);
</script>
@endsection