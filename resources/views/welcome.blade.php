{{--
  PORTOFOLIO (Blade, satu file: HTML + CSS + JS). Struktur data mengikuti kode Anda.
  Semua data OPSIONAL: kalau kosong, bagian itu disembunyikan / pakai teks default.

  $c  (pengaturan, diakses $c['key']->value) -- key yang dipakai:
      home_name, home_title, home_description, home_skills (pisah koma), home_photo,
      about_description, about_quote, about_photo, about_education (1 baris per item), about_interests (pisah koma),
      about_organization (1 baris per item), about_plk, about_goal,
      cv_file, contact_email, contact_phone, contact_whatsapp, contact_instagram, contact_github
      (home_photo, about_photo, cv_file = path file, dibaca lewat asset())
  $skills       : icon, category, items (pisah koma), type (opsional: "Non-teknis" untuk soft skill)
  $projects     : title, image, description + opsional: category, problem, goal, technologies, role,
                  features, demo_url, github_url, documentation, detail
  $experiences  : type, title, place, period, description
  $certificates : title, issuer, year, type (Teknis|Non-teknis), category (Akademik|Non-akademik), image
  $posts        : title, category, excerpt, content, image, created_at
  $testimonials : name, role, quote, video_url (YouTube), video_file
  Route opsional: contact.store (POST), admin.login
--}}
@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Route;
  $c = $c ?? [];
  $C  = fn ($k, $d = null) => data_get($c[$k] ?? null, 'value') ?: $d;
  $L  = fn ($t, $s = "\n") => array_values(array_filter(array_map('trim', explode($s, (string) $t)), fn ($x) => $x !== ''));
  $D  = fn ($o, $k) => data_get($o, $k);
  $img = fn ($p) => asset($p);
  $yt = function ($u) {
      return $u && preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|shorts/))([\w-]{11})~', $u, $m)
          ? 'https://www.youtube.com/embed/'.$m[1] : null;
  };
  $ext = fn ($v, $base) => Str::startsWith($v, 'http') ? $v : $base.ltrim($v, '@/');
  $skills = collect($skills ?? []); $projects = collect($projects ?? []);
  $experiences = collect($experiences ?? []); $certificates = collect($certificates ?? []);
  $posts = collect($posts ?? []); $testimonials = collect($testimonials ?? []);

  $name   = $C('home_name', 'Aripin');
  $homePhoto  = $C('home_photo') ? $img($C('home_photo')) : ($C('about_photo') ? $img($C('about_photo')) : ($C('about_image') ? $img($C('about_image')) : '/profilIpin.png'));
  $aboutPhoto = $C('about_photo') ? $img($C('about_photo')) : ($C('about_image') ? $img($C('about_image')) : '/profilIpin.png');
  $cvUrl  = $C('cv_file') ? $img($C('cv_file')) : null;
  $soft   = $skills->filter(fn ($s) => Str::lower((string) $D($s, 'type')) === 'non-teknis');
  $tech   = $skills->reject(fn ($s) => Str::lower((string) $D($s, 'type')) === 'non-teknis');
  $email  = $C('contact_email');
  $phone  = $C('contact_phone');
  $wa     = $C('contact_whatsapp') ?: ($phone ? preg_replace('/^0/', '62', preg_replace('/\D/', '', $phone)) : null);
  $formAction = Route::has('contact.store') ? route('contact.store') : '#';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $name }} · Portfolio</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet" />
  <script>
    try { document.documentElement.dataset.theme = localStorage.getItem('theme') || 'dark'; }
    catch (e) { document.documentElement.dataset.theme = 'dark'; }
  </script>
  <style>
/* ===== TOKEN TEMA (dark = default, light = [data-theme=light]) ===== */
:root{
  --bg:#0f172a; --text:#fff; --muted:#94a3b8; --soft:#cbd5e1;
  --card:rgba(255,255,255,.05); --card-2:rgba(255,255,255,.08);
  --border:rgba(255,255,255,.1); --accent:#22d3ee; --accent-soft:rgba(34,211,238,.12);
  --on-accent:#000; --nav-bg:rgba(255,255,255,.08);
}
:root[data-theme="light"]{
  --bg:#f1f5f9; --text:#0f172a; --muted:#475569; --soft:#334155;
  --card:#fff; --card-2:rgba(15,23,42,.06);
  --border:rgba(15,23,42,.12); --accent:#0e7490; --accent-soft:rgba(14,116,144,.12);
  --on-accent:#fff; --nav-bg:rgba(255,255,255,.85);
}

*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif}
html{scroll-behavior:smooth}
body{background:var(--bg);color:var(--text);overflow-x:hidden}
[hidden]{display:none!important}
a,button,input,textarea,select{outline-offset:3px}
:focus-visible{outline:2px solid var(--accent)}

.pf{background:var(--bg);color:var(--text);min-height:100vh;transition:background .3s,color .3s}
.pf section{padding:100px 8%;border-bottom:1px solid var(--border);scroll-margin-top:100px}
.pf section#home{min-height:100vh}

/* ===== NAVBAR ===== */
nav.pf-nav{width:100%;display:flex;justify-content:center;position:fixed;top:20px;z-index:1000}
.navbar{position:relative;width:95%;max-width:1200px;background:var(--nav-bg);backdrop-filter:blur(10px);
  border:1px solid var(--border);border-radius:50px;padding:14px 28px;display:flex;justify-content:space-between;align-items:center;gap:20px}
.logo{font-size:26px;font-weight:bold;color:var(--text);text-decoration:none;white-space:nowrap}
.logo span{color:var(--accent)}
.menu{display:flex;gap:22px}
.menu a{text-decoration:none;color:var(--text);transition:.3s;font-size:14px;white-space:nowrap}
.menu a:hover{color:var(--accent)}
.nav-actions{display:flex;align-items:center;gap:10px}
.nav-btn{padding:11px 22px;background:var(--accent);border-radius:30px;color:var(--on-accent);text-decoration:none;font-weight:bold;font-size:14px;white-space:nowrap}
.icon-btn{width:42px;height:42px;border-radius:50%;border:1px solid var(--border);background:var(--card-2);color:var(--text);cursor:pointer;font-size:20px;display:flex;align-items:center;justify-content:center}
.icon-btn:hover{border-color:var(--accent);color:var(--accent)}
.hamburger{display:none}

/* ===== HOME ===== */
#home{display:flex;align-items:center;justify-content:center;text-align:center}
.home-content{max-width:850px}
.home-photo{width:140px;height:140px;border-radius:50%;object-fit:cover;border:3px solid var(--accent);margin-bottom:25px}
.home-content h1{font-size:80px;line-height:1.1;margin-bottom:25px;color:var(--text)}
.home-content p{font-size:21px;line-height:1.8;color:var(--muted);margin-bottom:28px}
.tag-row{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-bottom:38px}
.tag{background:var(--accent-soft);color:var(--accent);padding:8px 16px;border-radius:20px;font-size:14px;font-weight:bold}
.buttons{display:flex;justify-content:center;gap:16px;flex-wrap:wrap}
.btn-primary,.btn-secondary,.btn-outline{padding:16px 32px;border-radius:40px;text-decoration:none;font-weight:bold;transition:.3s;border:2px solid transparent;cursor:pointer;font-size:16px;display:inline-flex;align-items:center;gap:8px}
.btn-primary{background:var(--accent);color:var(--on-accent)}
.btn-secondary{background:var(--text);color:var(--bg)}
.btn-outline{border-color:var(--accent);color:var(--accent);background:transparent}
.btn-primary:hover,.btn-secondary:hover,.btn-outline:hover{transform:translateY(-4px)}
.btn-sm{padding:10px 18px;font-size:14px}

/* ===== ABOUT ===== */
.about-container{display:grid;grid-template-columns:repeat(2,1fr);gap:60px;align-items:center}
.about-image{display:flex;justify-content:center}
.about-image img{width:320px;height:320px;max-width:100%;object-fit:cover;border-radius:50%;background:var(--card)}
.about-content h1{font-size:60px;margin-bottom:25px}
.about-content p{color:var(--muted);line-height:1.9;font-size:17px;margin-bottom:20px}
.about-line{border-left:4px solid var(--accent);padding-left:20px}
.about-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:24px;margin-top:60px}
.info-card{background:var(--card);border:1px solid var(--border);border-radius:25px;padding:30px}
.info-card h3{margin-bottom:16px;display:flex;align-items:center;gap:10px}
.info-card h3 i{color:var(--accent);font-size:22px}
.info-card p{color:var(--muted);line-height:1.8}
.info-card ul{list-style:none;display:grid;gap:12px}
.info-card li{color:var(--muted);line-height:1.6;padding-left:18px;position:relative}
.info-card li::before{content:"";position:absolute;left:0;top:.6em;width:7px;height:7px;border-radius:50%;background:var(--accent)}

/* ===== JUDUL SECTION ===== */
.section-title{text-align:center;margin-bottom:50px}
.section-title h1{font-size:52px;margin-bottom:15px}
.section-title p{color:var(--muted)}

/* ===== SKILLS ===== */
.skill-container{display:grid;grid-template-columns:repeat(3,1fr);gap:30px}
.skill-box{background:var(--card);border:1px solid var(--border);border-radius:25px;padding:35px;transition:.4s}
.skill-box:hover{transform:translateY(-8px)}
.skill-icon{width:70px;height:70px;background:var(--accent);border-radius:20px;display:flex;align-items:center;justify-content:center;margin-bottom:25px}
.skill-icon i{font-size:35px;color:var(--on-accent)}
.skill-box h2{margin-bottom:20px}
.skill-list{display:flex;flex-wrap:wrap;gap:12px}
.skill-list span{background:var(--card-2);padding:10px 18px;border-radius:12px;color:var(--soft)}
.sub-title{font-size:26px;margin:0 0 24px}
.sub-title.spaced{margin-top:60px}

/* ===== FILTER ===== */
.filter-bar{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:center;margin-bottom:36px}
.chip{background:var(--card-2);color:var(--soft);border:1px solid var(--border);padding:9px 18px;border-radius:30px;cursor:pointer;font-size:14px}
.chip:hover{border-color:var(--accent)}
.chip.active{background:var(--accent);color:var(--on-accent);border-color:var(--accent);font-weight:bold}
.search-input{padding:11px 18px;border-radius:30px;border:1px solid var(--border);background:var(--card-2);color:var(--text);min-width:240px}
.empty-state{text-align:center;color:var(--muted);padding:40px 0}

/* ===== PROJECT ===== */
.project-container{display:grid;grid-template-columns:repeat(3,1fr);gap:30px}
.project-card{background:var(--card);border:1px solid var(--border);border-radius:25px;overflow:hidden;transition:.4s;display:flex;flex-direction:column}
.project-card:hover{transform:translateY(-8px)}
.project-card img{width:100%;height:220px;object-fit:cover;display:block;cursor:zoom-in}
.project-content{padding:25px;display:flex;flex-direction:column;gap:14px;flex:1}
.project-content h2{font-size:22px}
.project-content h2 a{color:var(--text);text-decoration:none}
.project-content h2 a:hover{color:var(--accent)}
.project-content p{color:var(--muted);line-height:1.7}
.project-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:auto}
.tech-list{display:flex;flex-wrap:wrap;gap:8px}
.tech-list span{background:var(--card-2);color:var(--soft);padding:5px 12px;border-radius:10px;font-size:13px}

/* ===== PENGALAMAN ===== */
.timeline{display:grid;gap:20px;max-width:900px;margin:0 auto}
.exp-card{background:var(--card);border:1px solid var(--border);border-left:4px solid var(--accent);border-radius:20px;padding:26px}
.exp-card .meta{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-bottom:10px}
.exp-card h3{font-size:20px;margin-bottom:6px}
.exp-card .place{color:var(--soft);margin-bottom:10px}
.exp-card p{color:var(--muted);line-height:1.7;white-space:pre-line}
.period{color:var(--muted);font-size:14px}

/* ===== SERTIFIKAT ===== */
.cert-container{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.cert-card{background:var(--card);border:1px solid var(--border);border-radius:20px;overflow:hidden}
.cert-card img{width:100%;height:190px;object-fit:cover;display:block;cursor:zoom-in}
.cert-body{padding:20px;display:grid;gap:8px}
.cert-body h3{font-size:18px}
.cert-body .issuer{color:var(--muted);font-size:14px}
.badges{display:flex;gap:8px;flex-wrap:wrap}
.badge{background:var(--accent-soft);color:var(--accent);padding:4px 12px;border-radius:20px;font-size:12px;font-weight:bold}

/* ===== CV ===== */
.cv-frame{width:100%;height:640px;border:1px solid var(--border);border-radius:20px;background:#fff}
.cv-actions{display:flex;gap:14px;justify-content:center;flex-wrap:wrap;margin-top:24px}

/* ===== LOG ===== */
.post-container{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
.post-card{background:var(--card);border:1px solid var(--border);border-radius:20px;overflow:hidden;display:flex;flex-direction:column}
.post-card img{width:100%;height:170px;object-fit:cover;display:block}
.post-body{padding:22px;display:grid;gap:10px}
.post-body h3 a{color:var(--text);text-decoration:none}
.post-body h3 a:hover{color:var(--accent)}
.post-body p{color:var(--muted);line-height:1.7}
.post-body time{color:var(--muted);font-size:13px}
.center-row{text-align:center;margin-top:36px}

/* ===== TESTIMONI ===== */
.testi-container{display:grid;grid-template-columns:repeat(2,1fr);gap:28px}
.testi-card{background:var(--card);border:1px solid var(--border);border-radius:22px;overflow:hidden}
.testi-card iframe,.testi-card video{width:100%;aspect-ratio:16/9;border:0;display:block;background:#000}
.testi-body{padding:22px;display:grid;gap:8px}
.testi-body p{color:var(--muted);line-height:1.7;font-style:italic}
.testi-body strong{font-size:17px}

/* ===== CONTACT ===== */
.contact-container{display:grid;grid-template-columns:repeat(2,1fr);gap:60px;align-items:start}
.contact-left h2{font-size:50px;margin-bottom:20px}
.contact-left>p{color:var(--muted);line-height:1.8;margin-bottom:35px}
.info-box{display:flex;align-items:center;gap:15px;margin-bottom:22px}
.info-box h3{margin-bottom:4px}
.info-box p{margin:0;line-height:1.5;word-break:break-word;color:var(--muted)}
.info-box p a{color:var(--muted);text-decoration:none}
.info-box p a:hover{color:var(--accent)}
.info-box .grow{flex:1;min-width:0}
.icon{width:58px;height:58px;flex-shrink:0;background:var(--accent-soft);border-radius:18px;display:flex;align-items:center;justify-content:center}
.icon i{font-size:26px;color:var(--accent)}
.copy-btn{background:var(--card-2);border:1px solid var(--border);color:var(--text);padding:8px 14px;border-radius:20px;cursor:pointer;font-size:13px;white-space:nowrap}
.copy-btn:hover{border-color:var(--accent);color:var(--accent)}
.contact-form{background:var(--card);border:1px solid var(--border);padding:35px;border-radius:25px}
.input-box{margin-bottom:20px}
.input-box label{display:block;margin-bottom:10px;font-size:14px;font-weight:bold;color:var(--text)}
.input-box input,.input-box textarea{width:100%;padding:15px;border:1px solid transparent;border-radius:12px;background:var(--card-2);color:var(--text)}
.input-box textarea{height:150px;resize:vertical}
.input-box .err{color:#f87171;font-size:13px;margin-top:6px;display:block}
.contact-btn{width:100%;padding:16px;border:none;border-radius:12px;background:var(--accent);color:var(--on-accent);font-weight:bold;cursor:pointer;font-size:16px}
.alert-ok{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.5);color:var(--text);padding:14px 18px;border-radius:12px;margin-bottom:20px}
.hp{position:absolute;left:-9999px;opacity:0}

.pf-footer{text-align:center;color:var(--muted);padding:30px 8%;font-size:14px}

/* ===== HALAMAN DETAIL (project / log) ===== */
.page{padding:130px 8% 80px;max-width:1000px;margin:0 auto}
.page .back{color:var(--accent);text-decoration:none;display:inline-flex;gap:6px;align-items:center;margin-bottom:28px}
.page h1{font-size:46px;line-height:1.15;margin-bottom:18px}
.page .lead{color:var(--muted);line-height:1.8;font-size:18px;margin-bottom:26px}
.page .cover{width:100%;max-height:480px;object-fit:cover;border-radius:22px;margin-bottom:34px;cursor:zoom-in;background:var(--card)}
.detail-block{margin-top:36px}
.detail-block h2{font-size:26px;margin-bottom:14px}
.detail-block p,.detail-block li{color:var(--soft);line-height:1.9}
.detail-block ul{padding-left:22px;display:grid;gap:6px}
.prose{white-space:pre-line}
.meta-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:18px;margin-bottom:10px}
.meta-grid .info-card{padding:20px}
.meta-grid h3{font-size:15px;color:var(--muted);margin-bottom:8px}

/* ===== FITUR INTERAKTIF ===== */
html.js .reveal{opacity:0;transform:translateY(18px);transition:opacity .6s ease,transform .6s ease}
html.js .reveal.in{opacity:1;transform:none}
#toTop{position:fixed;right:22px;bottom:22px;z-index:900;opacity:0;pointer-events:none;transition:.3s;background:var(--accent);color:var(--on-accent);border-color:var(--accent)}
#toTop.show{opacity:1;pointer-events:auto}
#lightbox{position:fixed;inset:0;background:rgba(0,0,0,.88);display:flex;align-items:center;justify-content:center;z-index:2000;padding:24px;cursor:zoom-out}
#lightbox img{max-width:100%;max-height:100%;border-radius:12px}
#lightbox button{position:absolute;top:18px;right:18px;color:#fff;background:rgba(255,255,255,.15);border:0;width:44px;height:44px;border-radius:50%;font-size:22px;cursor:pointer}
@media (prefers-reduced-motion:reduce){
  html{scroll-behavior:auto}
  html.js .reveal{opacity:1;transform:none;transition:none}
  *{transition:none!important}
}

/* ===== RESPONSIVE ===== */
@media(max-width:1180px){
  .nav-btn{display:none}
  .menu{position:absolute;top:calc(100% + 10px);right:0;background:var(--bg);border:1px solid var(--border);flex-direction:column;gap:18px;width:210px;padding:22px;border-radius:15px;display:none}
  .menu.active{display:flex}
  .hamburger{display:flex}
}
@media(max-width:992px){
  .home-content h1{font-size:58px}
  .skill-container,.project-container,.about-container,.contact-container,.cert-container,.post-container,.testi-container,.about-grid{grid-template-columns:1fr}
  .about-content{text-align:center}
  .contact-left{text-align:left}
}
@media(max-width:768px){
  .pf section{padding:80px 6%}
  .navbar{width:95%;padding:12px 18px;border-radius:25px}
  .logo{font-size:21px}
  .home-content h1{font-size:42px}
  .home-content p{font-size:16px}
  .section-title h1,.contact-left h2,.about-content h1,.page h1{font-size:36px}
  .about-image img{width:260px;height:260px}
  .contact-form{padding:25px 20px}
  .cv-frame{height:420px}
  .meta-grid{grid-template-columns:1fr}
  .page{padding:120px 6% 60px}
  .buttons{flex-direction:column;align-items:stretch}
  .buttons a{justify-content:center}
}

/* ===== MODAL DETAIL (proyek & log) ===== */
#modal{position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:1500;display:flex;align-items:center;justify-content:center;padding:20px}
.modal-box{background:var(--bg);color:var(--text);border:1px solid var(--border);border-radius:24px;width:100%;max-width:760px;max-height:90vh;overflow-y:auto;padding:34px;position:relative}
.modal-close{position:absolute;top:14px;right:14px;width:42px;height:42px;border-radius:50%;border:1px solid var(--border);background:var(--card-2);color:var(--text);font-size:22px;cursor:pointer}
.modal-body .m-title{font-size:30px;margin:0 40px 12px 0}
.modal-body .m-cover{width:100%;max-height:340px;object-fit:cover;border-radius:16px;margin:18px 0;cursor:zoom-in}
.modal-body h3{margin:24px 0 8px;font-size:18px;color:var(--accent)}
.modal-body .m-text{color:var(--soft);line-height:1.85;white-space:pre-line;margin-top:10px}
.modal-body .m-list{padding-left:22px;display:grid;gap:6px;color:var(--soft);line-height:1.7}
body.no-scroll{overflow:hidden}
@media(max-width:768px){.modal-box{padding:24px 20px}.modal-body .m-title{font-size:24px}}

.admin-fab{position:fixed;bottom:22px;left:22px;background:var(--accent);color:var(--on-accent);padding:12px 20px;border-radius:50px;text-decoration:none;font-weight:bold;font-size:14px;z-index:900;box-shadow:0 4px 20px rgba(34,211,238,.3);display:inline-flex;gap:6px;align-items:center}

  </style>
</head>
<body>
<div class="pf">

  {{-- ============ NAVBAR ============ --}}
  <nav class="pf-nav">
    <div class="navbar">
      <a href="#home" class="logo">{{ $name }}'s <span>Portfolio</span>.</a>
      <div class="menu">
        <a href="#home">Home</a><a href="#about">About</a><a href="#skills">Skills</a>
        <a href="#projects">Projects</a><a href="#experience">Pengalaman</a><a href="#certificates">Sertifikat</a>
        <a href="#log">Log</a><a href="#testimonials">Testimoni</a><a href="#contact">Contact</a>
      </div>
      <div class="nav-actions">
        <button type="button" id="themeToggle" class="icon-btn" aria-label="Ganti mode terang/gelap"><i class="ri-sun-line"></i></button>
        <a href="#contact" class="nav-btn">Hubungi Saya</a>
        <button type="button" class="icon-btn hamburger" aria-label="Buka menu" aria-expanded="false"><i class="ri-menu-3-line"></i></button>
      </div>
    </div>
  </nav>

  {{-- ============ HOME ============ --}}
  <section id="home">
    <div class="home-content">
      <img class="home-photo" src="{{ $homePhoto }}" alt="Foto {{ $name }}">
      <h1>Hi. I'm {{ $name }} <br> {{ $C('home_title', 'A Design UI/UX.') }}</h1>
      <p>{{ $C('home_description') }}</p>
      @if($C('home_skills'))
        <div class="tag-row">@foreach($L($C('home_skills'), ',') as $s)<span class="tag">{{ $s }}</span>@endforeach</div>
      @endif
      <div class="buttons">
        <a href="#projects" class="btn-primary"><i class="ri-folder-open-line"></i> Lihat Portofolio</a>
        @if($cvUrl)<a href="{{ $cvUrl }}" download class="btn-secondary"><i class="ri-download-2-line"></i> Download CV</a>@endif
        <a href="#contact" class="btn-outline"><i class="ri-mail-send-line"></i> Contact</a>
      </div>
    </div>
  </section>

  {{-- ============ ABOUT ============ --}}
  <section id="about">
    <div class="about-container reveal">
      <div class="about-image"><img src="{{ $aboutPhoto }}" alt="Foto {{ $name }}"></div>
      <div class="about-content">
        <h1>Siapa Saya?</h1>
        <p>{{ $C('about_description') }}</p>
        @if($C('about_quote'))<p class="about-line">{{ $C('about_quote') }}</p>@endif
      </div>
    </div>
    <div class="about-grid reveal">
      @if($C('about_education'))<div class="info-card"><h3><i class="ri-graduation-cap-line"></i> Riwayat Pendidikan</h3><ul>@foreach($L($C('about_education')) as $l)<li>{{ $l }}</li>@endforeach</ul></div>@endif
      @if($C('about_interests'))<div class="info-card"><h3><i class="ri-lightbulb-line"></i> Minat & Bidang IT</h3><div class="skill-list">@foreach($L($C('about_interests'), ',') as $l)<span>{{ $l }}</span>@endforeach</div></div>@endif
      @if($C('about_organization'))<div class="info-card"><h3><i class="ri-team-line"></i> Organisasi & Kepanitiaan</h3><ul>@foreach($L($C('about_organization')) as $l)<li>{{ $l }}</li>@endforeach</ul></div>@endif
      @if($C('about_plk'))<div class="info-card"><h3><i class="ri-building-line"></i> Pengalaman PLK</h3><p>{{ $C('about_plk') }}</p></div>@endif
      @if($C('about_goal'))<div class="info-card"><h3><i class="ri-flag-line"></i> Cita-cita & Tujuan Karier</h3><p>{{ $C('about_goal') }}</p></div>@endif
    </div>
  </section>

  {{-- ============ SKILLS ============ --}}
  <section id="skills">
    <div class="section-title reveal"><h1>Skills & Tools</h1><p>Inilah skill teknis dan non-teknis yang saya pelajari dan kuasai.</p></div>
    @if($soft->count())<h2 class="sub-title">Kemampuan Teknis</h2>@endif
    <div class="skill-container reveal">
      @foreach($tech as $skill)
        <div class="skill-box">
          <div class="skill-icon"><i class="{{ $D($skill, 'icon') ?: 'ri-code-s-slash-line' }}"></i></div>
          <h2>{{ $D($skill, 'category') }}</h2>
          <div class="skill-list">@foreach($L($D($skill, 'items'), ',') as $item)<span>{{ $item }}</span>@endforeach</div>
        </div>
      @endforeach
    </div>
    @if($soft->count())
      <h2 class="sub-title spaced">Soft Skill</h2>
      <div class="skill-container reveal">
        @foreach($soft as $skill)
          <div class="skill-box">
            <div class="skill-icon"><i class="{{ $D($skill, 'icon') ?: 'ri-team-line' }}"></i></div>
            <h2>{{ $D($skill, 'category') }}</h2>
            <div class="skill-list">@foreach($L($D($skill, 'items'), ',') as $item)<span>{{ $item }}</span>@endforeach</div>
          </div>
        @endforeach
      </div>
    @endif
  </section>

  {{-- ============ PROJECTS ============ --}}
  <section id="projects">
    <div class="section-title reveal"><h1>My Projects</h1><p>Inilah beberapa project yang pernah saya buat.</p></div>

    <div class="filter-bar" data-filter-for="projectGrid">
      <input type="search" class="search-input" placeholder="Cari proyek atau teknologi..." aria-label="Cari proyek">
      <button type="button" class="chip active" data-filter="all">Semua</button>
      @foreach($projects->map(fn ($p) => $D($p, 'category'))->filter()->unique() as $cat)
        <button type="button" class="chip" data-filter="{{ Str::slug($cat) }}">{{ $cat }}</button>
      @endforeach
    </div>

    <div class="project-container" id="projectGrid" data-filterable>
      @foreach($projects as $i => $project)
        @php
          $techs = $L($D($project, 'technologies'), ','); $feats = $L($D($project, 'features'));
          $demo = $D($project, 'demo_url'); $git = $D($project, 'github_url');
        @endphp
        <div class="project-card" data-item data-tags="{{ Str::slug($D($project, 'category')) }}"
             data-text="{{ Str::lower($project->title.' '.$project->description.' '.$D($project, 'technologies').' '.$D($project, 'category')) }}">
          @if($project->image)
            <img src="{{ $img($project->image) }}" alt="{{ $project->title }}" data-lightbox="{{ $img($project->image) }}" loading="lazy">
          @endif
          <div class="project-content">
            <h2>{{ $project->title }}</h2>
            @if($techs)<div class="tech-list">@foreach($techs as $t)<span>{{ $t }}</span>@endforeach</div>@endif
            <p>{{ Str::limit($project->description, 140) }}</p>
            <div class="project-actions">
              <button type="button" class="btn-primary btn-sm" data-modal="pm-{{ $i }}">Detail</button>
              @if($demo)<a href="{{ $demo }}" target="_blank" rel="noopener" class="btn-outline btn-sm">Demo</a>@endif
              @if($git)<a href="{{ $git }}" target="_blank" rel="noopener" class="btn-outline btn-sm"><i class="ri-github-line"></i> Kode</a>@endif
            </div>
          </div>
        </div>

        <template id="pm-{{ $i }}">
          <h2 class="m-title">{{ $project->title }}</h2>
          @if($D($project, 'category'))<span class="badge">{{ $D($project, 'category') }}</span>@endif
          @if($project->image)<img class="m-cover" src="{{ $img($project->image) }}" alt="{{ $project->title }}" data-lightbox="{{ $img($project->image) }}">@endif
          <p class="m-text">{{ $project->description }}</p>
          @if($D($project, 'role'))<h3>Peran saya</h3><p class="m-text">{{ $D($project, 'role') }}</p>@endif
          @if($techs)<h3>Teknologi</h3><div class="tech-list">@foreach($techs as $t)<span>{{ $t }}</span>@endforeach</div>@endif
          @if($D($project, 'problem'))<h3>Masalah yang diselesaikan</h3><p class="m-text">{{ $D($project, 'problem') }}</p>@endif
          @if($D($project, 'goal'))<h3>Tujuan proyek</h3><p class="m-text">{{ $D($project, 'goal') }}</p>@endif
          @if($feats)<h3>Fitur</h3><ul class="m-list">@foreach($feats as $f)<li>{{ $f }}</li>@endforeach</ul>@endif
          @if($D($project, 'documentation'))<h3>Dokumentasi proses pengerjaan</h3><p class="m-text">{{ $D($project, 'documentation') }}</p>@endif
          @if($D($project, 'detail'))<h3>Detail proyek</h3><p class="m-text">{{ $D($project, 'detail') }}</p>@endif
          <div class="project-actions" style="margin-top:22px">
            @if($demo)<a href="{{ $demo }}" target="_blank" rel="noopener" class="btn-primary btn-sm"><i class="ri-external-link-line"></i> Lihat demo</a>@endif
            @if($git)<a href="{{ $git }}" target="_blank" rel="noopener" class="btn-outline btn-sm"><i class="ri-github-line"></i> Source code</a>@endif
          </div>
        </template>
      @endforeach
    </div>
    <p class="empty-state" data-empty-for="projectGrid" @if($projects->count()) hidden @endif>{{ $projects->count() ? 'Tidak ada proyek yang cocok.' : 'Belum ada proyek.' }}</p>
  </section>

  {{-- ============ PENGALAMAN ============ --}}
  <section id="experience">
    <div class="section-title reveal"><h1>Pengalaman</h1><p>PLK, proyek kelompok, freelance, lomba, dan organisasi.</p></div>
    <div class="filter-bar" data-filter-for="expList">
      <button type="button" class="chip active" data-filter="all">Semua</button>
      @foreach($experiences->pluck('type')->filter()->unique() as $t)<button type="button" class="chip" data-filter="{{ Str::slug($t) }}">{{ $t }}</button>@endforeach
    </div>
    <div class="timeline" id="expList" data-filterable>
      @foreach($experiences as $e)
        <article class="exp-card" data-item data-tags="{{ Str::slug($e->type) }}" data-text="{{ Str::lower($e->title.' '.$e->place) }}">
          <div class="meta"><span class="badge">{{ $e->type }}</span><span class="period">{{ $e->period }}</span></div>
          <h3>{{ $e->title }}</h3>
          @if($e->place)<div class="place">{{ $e->place }}</div>@endif
          @if($e->description)<p>{{ $e->description }}</p>@endif
        </article>
      @endforeach
    </div>
    @if($experiences->isEmpty())<p class="empty-state">Belum ada pengalaman.</p>@endif
  </section>

  {{-- ============ SERTIFIKAT & PRESTASI ============ --}}
  <section id="certificates">
    <div class="section-title reveal"><h1>Sertifikat & Prestasi</h1><p>Teknis maupun non-teknis, akademik maupun non-akademik.</p></div>
    <div class="filter-bar" data-filter-for="certGrid">
      @foreach(['all'=>'Semua','teknis'=>'Teknis','non-teknis'=>'Non-teknis','akademik'=>'Akademik','non-akademik'=>'Non-akademik'] as $k => $v)
        <button type="button" class="chip {{ $k === 'all' ? 'active' : '' }}" data-filter="{{ $k }}">{{ $v }}</button>
      @endforeach
    </div>
    <div class="cert-container" id="certGrid" data-filterable>
      @foreach($certificates as $ct)
        <article class="cert-card" data-item data-tags="{{ Str::slug($ct->type) }}|{{ Str::slug($ct->category) }}" data-text="{{ Str::lower($ct->title.' '.$ct->issuer) }}">
          @if($ct->image)<img src="{{ $img($ct->image) }}" alt="{{ $ct->title }}" data-lightbox="{{ $img($ct->image) }}" loading="lazy">@endif
          <div class="cert-body">
            <h3>{{ $ct->title }}</h3>
            <div class="issuer">{{ $ct->issuer }} {{ $ct->year ? '· '.$ct->year : '' }}</div>
            <div class="badges"><span class="badge">{{ $ct->type }}</span><span class="badge">{{ $ct->category }}</span></div>
          </div>
        </article>
      @endforeach
    </div>
    <p class="empty-state" data-empty-for="certGrid" @if($certificates->count()) hidden @endif>{{ $certificates->count() ? 'Tidak ada sertifikat di kategori ini.' : 'Belum ada sertifikat.' }}</p>
  </section>

  {{-- ============ CV DIGITAL ============ --}}
  @if($cvUrl)
  <section id="cv">
    <div class="section-title reveal"><h1>CV Digital</h1><p>Lihat langsung di sini atau unduh dalam format PDF.</p></div>
    <iframe class="cv-frame" src="{{ $cvUrl }}" title="CV {{ $name }}"></iframe>
    <div class="cv-actions">
      <a href="{{ $cvUrl }}" target="_blank" rel="noopener" class="btn-outline">Buka di tab baru</a>
      <a href="{{ $cvUrl }}" download class="btn-primary"><i class="ri-download-2-line"></i> Download CV</a>
    </div>
  </section>
  @endif

  {{-- ============ LOG ============ --}}
  <section id="log">
    <div class="section-title reveal"><h1>Log Belajar</h1><p>Artikel coding, tutorial singkat, cerita PLK, dan catatan menyelesaikan masalah.</p></div>
    <div class="filter-bar" data-filter-for="postGrid">
      <input type="search" class="search-input" placeholder="Cari tulisan..." aria-label="Cari tulisan">
      <button type="button" class="chip active" data-filter="all">Semua</button>
      @foreach($posts->pluck('category')->filter()->unique() as $pc)<button type="button" class="chip" data-filter="{{ Str::slug($pc) }}">{{ $pc }}</button>@endforeach
    </div>
    <div class="post-container" id="postGrid" data-filterable>
      @foreach($posts as $i => $post)
        <article class="post-card" data-item data-tags="{{ Str::slug($post->category) }}" data-text="{{ Str::lower($post->title.' '.$post->excerpt) }}">
          @if($post->image)<img src="{{ $img($post->image) }}" alt="{{ $post->title }}" loading="lazy">@endif
          <div class="post-body">
            <span class="badge" style="justify-self:start">{{ $post->category }}</span>
            <h3>{{ $post->title }}</h3>
            <p>{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}</p>
            <time>{{ optional($post->created_at)->translatedFormat('d F Y') }}</time>
            <button type="button" class="btn-outline btn-sm" style="justify-self:start" data-modal="po-{{ $i }}">Baca selengkapnya</button>
          </div>
        </article>
        <template id="po-{{ $i }}">
          <h2 class="m-title">{{ $post->title }}</h2>
          <span class="badge">{{ $post->category }}</span>
          @if($post->image)<img class="m-cover" src="{{ $img($post->image) }}" alt="{{ $post->title }}" data-lightbox="{{ $img($post->image) }}">@endif
          <p class="m-text">{{ $post->content }}</p>
        </template>
      @endforeach
    </div>
    <p class="empty-state" data-empty-for="postGrid" @if($posts->count()) hidden @endif>{{ $posts->count() ? 'Tidak ada tulisan yang cocok.' : 'Belum ada tulisan.' }}</p>
  </section>

  {{-- ============ TESTIMONI ============ --}}
  <section id="testimonials">
    <div class="section-title reveal"><h1>Testimoni</h1><p>Kata mereka yang pernah bekerja atau belajar bersama saya.</p></div>
    <div class="testi-container">
      @forelse($testimonials as $t)
        <article class="testi-card">
          @if($yt($t->video_url))
            <iframe src="{{ $yt($t->video_url) }}" title="Testimoni {{ $t->name }}" loading="lazy" allowfullscreen></iframe>
          @elseif($D($t, 'video_file'))
            <video controls preload="metadata" src="{{ $img($t->video_file) }}"></video>
          @endif
          <div class="testi-body">
            <strong>{{ $t->name }}</strong><span class="badge" style="justify-self:start">{{ $t->role }}</span>
            @if($t->quote)<p>"{{ $t->quote }}"</p>@endif
          </div>
        </article>
      @empty
        <p class="empty-state" style="grid-column:1/-1">Belum ada testimoni.</p>
      @endforelse
    </div>
  </section>

  {{-- ============ CONTACT ============ --}}
  <section id="contact">
    <div class="section-title reveal"><h1>Contact Me</h1><p>Mau bikin Project yang menarik? Jangan ragu untuk menghubungi saya.</p></div>
    <div class="contact-container">
      <div class="contact-left">
        <h2>Bingung Desain Apa??</h2>
        <p>Apakah kamu perlu bantuan membuat website, UI design, atau mengatur layout? Saya siap membantu project kamu.</p>
        @if($email)
        <div class="info-box"><div class="icon"><i class="ri-mail-line"></i></div>
          <div class="grow"><h3>Email</h3><p><a href="mailto:{{ $email }}">{{ $email }}</a></p></div>
          <button type="button" class="copy-btn" data-copy="{{ $email }}">Salin</button></div>
        @endif
        @if($phone)
        <div class="info-box"><div class="icon"><i class="ri-phone-line"></i></div>
          <div class="grow"><h3>Phone</h3><p>{{ $phone }}</p></div>
          <button type="button" class="copy-btn" data-copy="{{ $phone }}">Salin</button></div>
        @endif
        @if($wa)
        <div class="info-box"><div class="icon"><i class="ri-whatsapp-line"></i></div>
          <div class="grow"><h3>WhatsApp</h3><p><a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">Chat via WhatsApp</a></p></div>
          <button type="button" class="copy-btn" data-copy="+{{ $wa }}">Salin</button></div>
        @endif
        @if($C('contact_instagram'))<div class="info-box"><div class="icon"><i class="ri-instagram-line"></i></div>
          <div class="grow"><h3>Instagram</h3><p><a href="{{ $ext($C('contact_instagram'), 'https://instagram.com/') }}" target="_blank" rel="noopener">{{ $C('contact_instagram') }}</a></p></div></div>@endif
        @if($C('contact_github'))<div class="info-box"><div class="icon"><i class="ri-github-line"></i></div>
          <div class="grow"><h3>GitHub</h3><p><a href="{{ $ext($C('contact_github'), 'https://github.com/') }}" target="_blank" rel="noopener">{{ $C('contact_github') }}</a></p></div></div>@endif
      </div>

      <form class="contact-form" method="POST" action="{{ $formAction }}" @if($formAction === '#') data-mailto="{{ $email }}" @endif>
        @csrf
        @if(session('success'))<div class="alert-ok" role="status">{{ session('success') }}</div>@endif
        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div class="input-box"><label for="c-name">Nama</label>
          <input id="c-name" name="name" type="text" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap Anda" required></div>
        <div class="input-box"><label for="c-email">Email</label>
          <input id="c-email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukkan Email Anda" required></div>
        <div class="input-box"><label for="c-msg">Pesan</label>
          <textarea id="c-msg" name="message" placeholder="Tulis Pesan Anda..." required>{{ old('message') }}</textarea></div>
        <button class="contact-btn" type="submit">Kirim Pesan</button>
      </form>
    </div>
  </section>

  <footer class="pf-footer">&copy; {{ date('Y') }} {{ $name }}</footer>

  @if(Route::has('admin.login'))<a href="{{ route('admin.login') }}" class="admin-fab"><i class="ri-settings-3-line"></i> Admin</a>@endif

  <button type="button" id="toTop" class="icon-btn" aria-label="Kembali ke atas"><i class="ri-arrow-up-line"></i></button>
  <div id="modal" hidden role="dialog" aria-modal="true">
    <div class="modal-box"><button type="button" class="modal-close" aria-label="Tutup">&times;</button><div class="modal-body"></div></div>
  </div>
  <div id="lightbox" hidden role="dialog" aria-label="Pratinjau gambar"><button type="button" aria-label="Tutup">&times;</button><img src="" alt=""></div>
</div>

<script>
(function () {
  var root = document.documentElement;
  root.classList.add('js');

  /* ---- Dark / light mode ---- */
  var themeBtn = document.getElementById('themeToggle');
  function paintThemeIcon() {
    if (!themeBtn) return;
    themeBtn.querySelector('i').className = root.dataset.theme === 'light' ? 'ri-moon-line' : 'ri-sun-line';
  }
  if (themeBtn) {
    themeBtn.addEventListener('click', function () {
      root.dataset.theme = root.dataset.theme === 'light' ? 'dark' : 'light';
      try { localStorage.setItem('theme', root.dataset.theme); } catch (e) {}
      paintThemeIcon();
    });
    paintThemeIcon();
  }

  /* ---- Menu hamburger ---- */
  var hamburger = document.querySelector('.hamburger');
  var menu = document.querySelector('.menu');
  if (hamburger && menu) {
    var icon = hamburger.querySelector('i');
    var setMenu = function (open) {
      menu.classList.toggle('active', open);
      icon.className = open ? 'ri-close-line' : 'ri-menu-3-line';
      hamburger.setAttribute('aria-expanded', open);
    };
    hamburger.addEventListener('click', function (e) { e.stopPropagation(); setMenu(!menu.classList.contains('active')); });
    menu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
    document.addEventListener('click', function (e) { if (!menu.contains(e.target)) setMenu(false); });
  }

  /* ---- Animasi masuk (sekali saat muncul) ---- */
  var reveals = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
    }, { threshold: 0.12 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('in'); });
  }

  /* ---- Tombol kembali ke atas ---- */
  var toTop = document.getElementById('toTop');
  if (toTop) {
    window.addEventListener('scroll', function () { toTop.classList.toggle('show', window.scrollY > 500); }, { passive: true });
    toTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
  }

  /* ---- Lightbox (pratinjau gambar proyek / sertifikat) ---- */
  var lb = document.getElementById('lightbox');
  if (lb) {
    var lbImg = lb.querySelector('img');
    var closeLb = function () { lb.hidden = true; lbImg.src = ''; };
    document.addEventListener('click', function (e) {
      var t = e.target.closest('[data-lightbox]');
      if (t) { lbImg.src = t.dataset.lightbox || t.src; lbImg.alt = t.alt || ''; lb.hidden = false; }
    });
    lb.addEventListener('click', closeLb);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !lb.hidden) closeLb(); });
  }

  /* ---- Salin email / kontak ---- */
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-copy]');
    if (!b) return;
    var text = b.dataset.copy, old = b.textContent;
    var done = function () { b.textContent = 'Tersalin'; setTimeout(function () { b.textContent = old; }, 1600); };
    if (navigator.clipboard) { navigator.clipboard.writeText(text).then(done); }
    else { var ta = document.createElement('textarea'); ta.value = text; document.body.appendChild(ta); ta.select(); document.execCommand('copy'); ta.remove(); done(); }
  });

  /* ---- Filter + pencarian (proyek, pengalaman, sertifikat) ---- */
  document.querySelectorAll('[data-filterable]').forEach(function (box) {
    var bar = document.querySelector('[data-filter-for="' + box.id + '"]');
    var items = Array.prototype.slice.call(box.querySelectorAll('[data-item]'));
    var empty = document.querySelector('[data-empty-for="' + box.id + '"]');
    var chips = bar ? Array.prototype.slice.call(bar.querySelectorAll('.chip')) : [];
    var input = bar ? bar.querySelector('input') : null;
    var tag = 'all', q = '';
    function apply() {
      var n = 0;
      items.forEach(function (it) {
        var okTag = tag === 'all' || it.dataset.tags.split('|').indexOf(tag) > -1;
        var okQ = !q || it.dataset.text.indexOf(q) > -1;
        it.hidden = !(okTag && okQ);
        if (!it.hidden) n++;
      });
      if (empty) empty.hidden = n > 0;
    }
    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        chips.forEach(function (x) { x.classList.remove('active'); });
        c.classList.add('active'); tag = c.dataset.filter; apply();
      });
    });
    if (input) input.addEventListener('input', function () { q = input.value.trim().toLowerCase(); apply(); });
  });
})();

/* ---- Modal detail ---- */
(function () {
  var modal = document.getElementById('modal');
  if (!modal) return;
  var body = modal.querySelector('.modal-body');
  function close() { modal.hidden = true; body.innerHTML = ''; document.body.classList.remove('no-scroll'); }
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-modal]');
    if (b) {
      var tpl = document.getElementById(b.dataset.modal);
      if (!tpl) return;
      body.innerHTML = tpl.innerHTML; modal.hidden = false; document.body.classList.add('no-scroll');
      modal.querySelector('.modal-box').scrollTop = 0; return;
    }
    if (e.target === modal || e.target.closest('.modal-close')) close();
  });
  document.addEventListener('keydown', function (e) {
    var lb = document.getElementById('lightbox');
    if (e.key === 'Escape' && !modal.hidden && (!lb || lb.hidden)) close();
  });
})();

/* ---- Form kontak tanpa route: buka aplikasi email dengan isi terisi ---- */
(function () {
  var f = document.querySelector('form[data-mailto]');
  if (!f) return;
  f.addEventListener('submit', function (e) {
    e.preventDefault();
    var to = f.dataset.mailto;
    if (!to) { alert('Alamat email tujuan belum diatur.'); return; }
    var n = f.elements.name.value, m = f.elements.email.value, p = f.elements.message.value;
    location.href = 'mailto:' + to + '?subject=' + encodeURIComponent('Pesan dari ' + n) +
      '&body=' + encodeURIComponent(p + '\n\n— ' + n + ' (' + m + ')');
  });
})();

</script>
</body>
</html>
