<?php
?>
<script>
  /* Navbar scroll */
  const header = document.getElementById('main-header');
  const brandMark = document.getElementById('brand-mark');

  function syncNavState(){
    if (!header) {
      return;
    }

    const isScrolled = window.scrollY > 120;
    header.classList.toggle('scrolled', isScrolled);

    if (brandMark) {
      brandMark.classList.toggle('brand-tight', isScrolled);
    }
  }

  window.addEventListener('scroll', syncNavState);
  syncNavState();

  /* Hero slideshow */
  let curSlide = 0;
  const slides = document.querySelectorAll('.slide-img');
  const indicators = document.querySelectorAll('.dot-indicator');

  function setSlide(index){
    if (!slides.length || !indicators.length) {
      return;
    }

    slides[curSlide].style.opacity = '0';
    indicators[curSlide].classList.remove('w-8');
    indicators[curSlide].classList.add('bg-slate-300');

    curSlide = index;

    slides[curSlide].style.opacity = '0.55';
    indicators[curSlide].classList.add('w-8');
    indicators[curSlide].classList.remove('bg-slate-300');
    indicators[curSlide].classList.add('bg-primary');
  }

  if (slides.length) {
    setInterval(() => setSlide((curSlide + 1) % slides.length), 5200);
  }

  function scrollProj(dir){
    const c = document.getElementById('proj-carousel');
    if (!c) {
      return;
    }
    c.scrollBy({ left: dir === 'left' ? -360 : 360, behavior: 'smooth' });
  }

  /* Modal */
  const modal = document.getElementById('proj-modal');
  const box = document.getElementById('modal-box');

  function openProjModal(p){
    if (!modal || !box) {
      return;
    }

    document.getElementById('m-img').src = p.image || '';
    document.getElementById('m-img').alt = p.title || '';
    document.getElementById('m-title').innerText = p.title || '';
    document.getElementById('m-sub').innerText = p.desc || 'Excellence in regional architectural design in Erode.';
    document.getElementById('m-budget').innerText = p.budget || '-';
    document.getElementById('m-size').innerText = p.size || '-';
    document.getElementById('m-year').innerText = p.year || '-';
    document.getElementById('m-loc').innerText = p.location || '-';

    modal.style.display = 'flex';
    setTimeout(() => {
      box.classList.remove('scale-95','opacity-0');
      box.classList.add('scale-100','opacity-100');
    }, 10);
    document.body.style.overflow = 'hidden';
  }

  function closeProjModal(){
    if (!modal || !box) {
      return;
    }

    box.classList.add('scale-95','opacity-0');
    setTimeout(() => {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
    }, 250);
  }

  /* Reveal */
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('active'); });
  }, { threshold: 0.1 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
