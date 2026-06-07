<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solémia — POS Restaurante</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=dm-sans:300,400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --olive-50:  #f7f6f0;
            --olive-100: #edeade;
            --olive-200: #d8d3bf;
            --olive-300: #bfb89c;
            --olive-400: #a09775;
            --olive-500: #857857;
            --olive-600: #6b5f43;
            --olive-700: #554a33;
            --olive-800: #3e3523;
            --olive-900: #2a2316;
            --olive-950: #171309;
            --gold-300: #f0c96a;
            --gold-400: #e6af3a;
            --gold-500: #c8921e;
            --gold-600: #a07018;
            --cream:     #faf8f3;
            --cream-dark:#f0ede4;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; font-size: 16px; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--olive-900);
            -webkit-font-smoothing: antialiased;
        }

        /* ── TYPOGRAPHY ─────────────────────── */
        .font-serif { font-family: 'Cormorant Garamond', Georgia, serif; }

        /* ── NAV ─────────────────────────────── */
        .nav-bar {
            position: fixed; top: 0; right: 0; z-index: 100;
            padding: 1.25rem 1.75rem;
            display: flex; align-items: center; gap: 0.75rem;
        }
        .lang-btn {
            display: flex; align-items: center; gap: 0.4rem;
            background: rgba(255,255,255,0.92); backdrop-filter: blur(8px);
            border: 1px solid rgba(107,95,67,0.25);
            padding: 0.45rem 0.85rem; border-radius: 2rem;
            font-size: 0.75rem; font-weight: 500; color: var(--olive-700);
            cursor: pointer; transition: all 0.2s ease;
        }
        .lang-btn:hover { border-color: var(--olive-500); box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .lang-dropdown {
            position: absolute; right: 0; top: calc(100% + 0.5rem);
            background: #fff; border: 1px solid rgba(107,95,67,0.15);
            border-radius: 1rem; box-shadow: 0 16px 40px rgba(0,0,0,0.12);
            padding: 0.5rem; min-width: 9rem; z-index: 200;
        }
        .lang-item {
            display: flex; align-items: center; gap: 0.65rem;
            width: 100%; padding: 0.55rem 0.85rem; border-radius: 0.6rem;
            font-size: 0.85rem; background: none; border: none;
            cursor: pointer; transition: background 0.15s;
            color: var(--olive-700);
        }
        .lang-item:hover { background: var(--olive-50); }
        .lang-item.active { color: var(--olive-800); font-weight: 600; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--olive-800); color: #fff;
            padding: 0.55rem 1.25rem; border-radius: 2rem;
            font-size: 0.82rem; font-weight: 500; text-decoration: none;
            transition: all 0.25s ease; border: 1px solid transparent;
            letter-spacing: 0.02em;
        }
        .btn-primary:hover { background: var(--olive-700); transform: translateY(-1px); box-shadow: 0 8px 24px rgba(42,35,22,0.22); }
        .btn-ghost {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(255,255,255,0.88); backdrop-filter: blur(8px);
            color: var(--olive-800); border: 1px solid rgba(107,95,67,0.3);
            padding: 0.55rem 1.25rem; border-radius: 2rem;
            font-size: 0.82rem; font-weight: 500; text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-ghost:hover { background: #fff; border-color: var(--olive-500); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }

        /* ── HERO ─────────────────────────────── */
        .hero {
            position: relative; min-height: 100dvh;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            background-color: var(--olive-950);
            overflow: hidden;
            text-align: center;
            padding: 7rem 1.5rem 5rem;
        }
        .hero-bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 64px 64px;
        }
        .hero-glow-top {
            position: absolute; top: -120px; left: 50%; transform: translateX(-50%);
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(200,146,30,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-glow-bottom {
            position: absolute; bottom: -80px; right: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(107,95,67,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .hero-eyebrow {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.72rem; font-weight: 500;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: var(--gold-400); margin-bottom: 1.5rem;
            opacity: 0; animation: slideUp 0.8s ease 0.2s forwards;
        }
        .hero-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(5rem, 15vw, 11rem);
            font-weight: 700; line-height: 0.9;
            color: #fff; letter-spacing: -0.02em;
            margin-bottom: 1.25rem;
            opacity: 0; animation: slideUp 0.9s ease 0.35s forwards;
        }
        .hero-title span {
            display: inline-block;
            background: linear-gradient(135deg, #fff 40%, var(--gold-300) 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero-accent-line {
            width: 60px; height: 1px; background: var(--gold-400);
            margin: 0 auto 1.5rem;
            opacity: 0; animation: fadeIn 0.6s ease 0.55s forwards;
        }
        .hero-subtitle {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(1.25rem, 3vw, 1.75rem);
            font-weight: 400; font-style: italic;
            color: rgba(255,255,255,0.65); margin-bottom: 1rem;
            opacity: 0; animation: slideUp 0.8s ease 0.6s forwards;
        }
        .hero-desc {
            font-size: 1rem; color: rgba(255,255,255,0.45);
            max-width: 36rem; margin: 0 auto 2.75rem;
            line-height: 1.75; font-weight: 300;
            opacity: 0; animation: slideUp 0.8s ease 0.75s forwards;
        }
        .hero-ctas {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 0.85rem;
            opacity: 0; animation: slideUp 0.8s ease 0.9s forwards;
        }
        .hero-btn-main {
            display: inline-flex; align-items: center; gap: 0.6rem;
            background: var(--gold-400); color: var(--olive-950);
            padding: 0.85rem 2rem; border-radius: 2rem;
            font-size: 0.9rem; font-weight: 600; text-decoration: none;
            letter-spacing: 0.01em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 24px rgba(200,146,30,0.3);
        }
        .hero-btn-main:hover { background: var(--gold-300); transform: translateY(-2px); box-shadow: 0 10px 36px rgba(200,146,30,0.4); }
        .hero-btn-secondary {
            display: inline-flex; align-items: center; gap: 0.6rem;
            background: rgba(255,255,255,0.07); backdrop-filter: blur(8px);
            color: rgba(255,255,255,0.8); border: 1px solid rgba(255,255,255,0.15);
            padding: 0.85rem 2rem; border-radius: 2rem;
            font-size: 0.9rem; font-weight: 400; text-decoration: none;
            transition: all 0.3s ease;
        }
        .hero-btn-secondary:hover { background: rgba(255,255,255,0.13); border-color: rgba(255,255,255,0.3); transform: translateY(-2px); }
        .hero-scroll {
            position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
            color: rgba(255,255,255,0.25); font-size: 0.85rem;
            display: flex; flex-direction: column; align-items: center; gap: 0.5rem;
            animation: bounce 2s ease-in-out infinite;
        }
        .hero-scroll i { font-size: 1.1rem; }

        /* ── STATS BAND ──────────────────────── */
        .stats-band {
            background: var(--cream-dark); border-top: 1px solid rgba(107,95,67,0.12);
            border-bottom: 1px solid rgba(107,95,67,0.12);
            padding: 2.5rem 1.5rem;
        }
        .stats-grid {
            max-width: 72rem; margin: 0 auto;
            display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 2rem; text-align: center;
        }
        .stat-item {}
        .stat-num {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 2.75rem; font-weight: 700; line-height: 1;
            color: var(--olive-800); margin-bottom: 0.35rem;
        }
        .stat-label {
            font-size: 0.78rem; font-weight: 500;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--olive-500);
        }
        .stat-divider {
            display: none;
        }
        @media (min-width: 768px) {
            .stats-grid { gap: 0; }
            .stat-item { border-right: 1px solid rgba(107,95,67,0.15); padding: 0 2rem; }
            .stat-item:last-child { border-right: none; }
        }

        /* ── FEATURES ────────────────────────── */
        .features-section {
            padding: 6rem 1.5rem;
            background: var(--cream);
        }
        .section-eyebrow {
            font-size: 0.7rem; font-weight: 600;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: var(--gold-600); margin-bottom: 1rem;
        }
        .section-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(2.25rem, 5vw, 3.5rem);
            font-weight: 700; line-height: 1.1; color: var(--olive-900);
        }
        .section-desc {
            font-size: 1.05rem; color: var(--olive-500);
            max-width: 38rem; margin: 1rem 0 0; line-height: 1.7; font-weight: 300;
        }
        .features-header {
            display: flex; flex-direction: column; align-items: flex-start;
            max-width: 72rem; margin: 0 auto 3.5rem;
        }
        @media (min-width: 768px) {
            .features-header { flex-direction: row; align-items: flex-end; justify-content: space-between; gap: 2rem; }
            .features-header .section-desc { margin: 0; text-align: right; max-width: 28rem; }
        }
        .features-grid {
            max-width: 72rem; margin: 0 auto;
            display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1px; background: rgba(107,95,67,0.12);
            border: 1px solid rgba(107,95,67,0.12);
            border-radius: 1.25rem; overflow: hidden;
        }
        .feature-card {
            background: var(--cream);
            padding: 2rem 1.75rem;
            transition: background 0.25s ease;
            cursor: default;
        }
        .feature-card:hover { background: #fff; }
        .feature-icon-wrap {
            width: 2.75rem; height: 2.75rem;
            border-radius: 0.6rem;
            background: var(--olive-100);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
            transition: all 0.25s ease;
        }
        .feature-card:hover .feature-icon-wrap { background: var(--olive-800); }
        .feature-icon-wrap i {
            font-size: 1.1rem; color: var(--olive-600);
            transition: color 0.25s ease;
        }
        .feature-card:hover .feature-icon-wrap i { color: #fff; }
        .feature-title {
            font-size: 1rem; font-weight: 600; color: var(--olive-800);
            margin-bottom: 0.6rem; letter-spacing: -0.01em;
        }
        .feature-desc {
            font-size: 0.88rem; color: var(--olive-500);
            line-height: 1.65; font-weight: 400;
        }

        /* ── CTA ─────────────────────────────── */
        .cta-section {
            background: var(--olive-950);
            padding: 6rem 1.5rem;
            position: relative; overflow: hidden;
            text-align: center;
        }
        .cta-texture {
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(200,146,30,0.06) 1px, transparent 1px);
            background-size: 28px 28px;
        }
        .cta-glow {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 500px; height: 300px;
            background: radial-gradient(ellipse, rgba(200,146,30,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-inner { position: relative; z-index: 1; max-width: 42rem; margin: 0 auto; }
        .cta-eyebrow {
            font-size: 0.7rem; font-weight: 600;
            letter-spacing: 0.22em; text-transform: uppercase;
            color: var(--gold-400); margin-bottom: 1.25rem;
        }
        .cta-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(2rem, 5vw, 3.25rem);
            font-weight: 700; line-height: 1.1; color: #fff;
            margin-bottom: 1.25rem;
        }
        .cta-desc {
            font-size: 1rem; color: rgba(255,255,255,0.45);
            line-height: 1.75; font-weight: 300; margin-bottom: 2.5rem;
        }
        .cta-btn {
            display: inline-flex; align-items: center; gap: 0.65rem;
            background: var(--gold-400); color: var(--olive-950);
            padding: 1rem 2.5rem; border-radius: 2rem;
            font-size: 1rem; font-weight: 600; text-decoration: none;
            letter-spacing: 0.01em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 32px rgba(200,146,30,0.3);
        }
        .cta-btn:hover { background: var(--gold-300); transform: translateY(-2px); box-shadow: 0 12px 40px rgba(200,146,30,0.4); }

        /* ── FOOTER ──────────────────────────── */
        .footer {
            background: #0e0c07;
            padding: 2.75rem 1.5rem;
            border-top: 1px solid rgba(107,95,67,0.2);
        }
        .footer-inner {
            max-width: 72rem; margin: 0 auto;
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;
            gap: 1.5rem;
        }
        .footer-brand { font-family: 'Cormorant Garamond', Georgia, serif; font-size: 1.5rem; font-weight: 700; color: #fff; }
        .footer-sub { font-size: 0.78rem; color: rgba(255,255,255,0.28); margin-top: 0.2rem; }
        .footer-meta { display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; }
        .footer-badge {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem; color: rgba(255,255,255,0.3);
        }
        .footer-badge i { font-size: 0.85rem; }
        .footer-copy { font-size: 0.75rem; color: rgba(255,255,255,0.2); }

        /* ── ANIMATIONS ──────────────────────── */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes bounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50%       { transform: translateX(-50%) translateY(6px); }
        }

        /* Intersection observer animations */
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="langSwitcher()" x-init="init()">

    {{-- ── NAV ────────────────────────────────────────────────────── --}}
    @if (Route::has('login'))
    <div class="nav-bar">

        {{-- Language Switcher --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="lang-btn">
                <span x-text="$store.lang.flag"></span>
                <span x-text="$store.lang.code.toUpperCase()"></span>
                <i class="fas fa-chevron-down" style="font-size:0.65rem;"></i>
            </button>
            <div x-show="open" @click.away="open = false" class="lang-dropdown" x-cloak>
                <template x-for="lang in langs" :key="lang.code">
                    <button @click="switchLang(lang.code); open = false" class="lang-item"
                        :class="currentLang === lang.code ? 'active' : ''">
                        <span x-text="lang.flag"></span>
                        <span x-text="lang.name"></span>
                        <i class="fas fa-check ml-auto" style="font-size:0.7rem; color:var(--olive-600);" x-show="currentLang === lang.code"></i>
                    </button>
                </template>
            </div>
        </div>

        @auth
            <a href="{{ route('dashboard') }}" class="btn-primary">
                <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i>
                <span x-text="$store.lang.t.nav.entrar"></span>
            </a>
        @else
            <a href="{{ route('login') }}" class="btn-ghost">
                <i class="fas fa-sign-in-alt" style="font-size:0.8rem;"></i>
                <span x-text="$store.lang.t.nav.accedi"></span>
            </a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn-primary">
                <i class="fas fa-user-plus" style="font-size:0.75rem;"></i>
                <span x-text="$store.lang.t.nav.registrati"></span>
            </a>
            @endif
        @endauth
    </div>
    @endif

    {{-- ── HERO ────────────────────────────────────────────────────── --}}
    <section class="hero">
        <div class="hero-bg-grid"></div>
        <div class="hero-glow-top"></div>
        <div class="hero-glow-bottom"></div>

        <p class="hero-eyebrow" x-text="$store.lang.t.hero.badge"></p>

        <h1 class="hero-title">
            <span>Solémia</span>
        </h1>

        <div class="hero-accent-line"></div>

        <p class="hero-subtitle" x-text="$store.lang.t.hero.subtitle"></p>

        <p class="hero-desc" x-html="$store.lang.t.hero.desc"></p>

        <div class="hero-ctas">
            <a href="#features" class="hero-btn-main">
                <span x-text="$store.lang.t.hero.cta"></span>
                <i class="fas fa-arrow-down" style="font-size:0.8rem;"></i>
            </a>
            @guest
            <a href="{{ route('login') }}" class="hero-btn-secondary">
                <i class="fas fa-lock-open" style="font-size:0.8rem;"></i>
                <span x-text="$store.lang.t.hero.login"></span>
            </a>
            @endguest
        </div>

        <div class="hero-scroll">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    {{-- ── STATS ───────────────────────────────────────────────────── --}}
    <div class="stats-band">
        <div class="stats-grid">
            <div class="stat-item reveal">
                <div class="stat-num">9</div>
                <div class="stat-label" x-text="$store.lang.t.stats.modules"></div>
            </div>
            <div class="stat-item reveal reveal-delay-1">
                <div class="stat-num">∞</div>
                <div class="stat-label" x-text="$store.lang.t.stats.orders"></div>
            </div>
            <div class="stat-item reveal reveal-delay-2">
                <div class="stat-num">100%</div>
                <div class="stat-label" x-text="$store.lang.t.stats.cloud"></div>
            </div>
            <div class="stat-item reveal reveal-delay-3">
                <div class="stat-num">SRI</div>
                <div class="stat-label" x-text="$store.lang.t.stats.sri"></div>
            </div>
        </div>
    </div>

    {{-- ── FEATURES ─────────────────────────────────────────────────── --}}
    <section id="features" class="features-section">
        <div class="features-header reveal">
            <div>
                <p class="section-eyebrow" x-text="$store.lang.t.features.badge"></p>
                <h2 class="section-title" x-text="$store.lang.t.features.title"></h2>
            </div>
            <p class="section-desc" x-text="$store.lang.t.features.desc"></p>
        </div>

        <div class="features-grid">
            <template x-for="(item, i) in $store.lang.t.features.items" :key="i">
                <div class="feature-card reveal" :style="`transition-delay: ${i * 0.07}s`">
                    <div class="feature-icon-wrap">
                        <i :class="['fas', icons[i]]"></i>
                    </div>
                    <div class="feature-title" x-text="item.title"></div>
                    <div class="feature-desc" x-text="item.desc"></div>
                </div>
            </template>
        </div>
    </section>

    {{-- ── CTA ────────────────────────────────────────────────────── --}}
    <section class="cta-section">
        <div class="cta-texture"></div>
        <div class="cta-glow"></div>
        <div class="cta-inner">
            <p class="cta-eyebrow reveal" x-text="$store.lang.t.cta.badge"></p>
            <h2 class="cta-title reveal reveal-delay-1" x-text="$store.lang.t.cta.title"></h2>
            <p class="cta-desc reveal reveal-delay-2" x-text="$store.lang.t.cta.desc"></p>
            @guest
                <a href="{{ route('register') }}" class="cta-btn reveal reveal-delay-3">
                    <i class="fas fa-rocket"></i>
                    <span x-text="$store.lang.t.cta.cta"></span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="cta-btn reveal reveal-delay-3">
                    <i class="fas fa-arrow-right"></i>
                    <span x-text="$store.lang.t.cta.cta_in"></span>
                </a>
            @endguest
        </div>
    </section>

    {{-- ── FOOTER ──────────────────────────────────────────────────── --}}
    <footer class="footer">
        <div class="footer-inner">
            <div>
                <div class="footer-brand">Solémia</div>
                <div class="footer-sub" x-text="$store.lang.t.footer.subtitle"></div>
            </div>
            <div class="footer-meta">
                <span class="footer-badge"><i class="fas fa-code"></i> <span x-text="$store.lang.t.footer.made"></span></span>
                <span class="footer-badge"><i class="fas fa-heart" style="color:#f87171;"></i> <span x-text="$store.lang.t.footer.culture"></span></span>
            </div>
            <div class="footer-copy">&copy; {{ date('Y') }} Solémia. <span x-text="$store.lang.t.footer.rights"></span></div>
        </div>
    </footer>

    @livewireScripts

    <script>
        // Intersection Observer for scroll reveals
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(el => {
                if (el.isIntersecting) {
                    el.target.classList.add('visible');
                    revealObserver.unobserve(el.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

        const trans = {
            es: {
                nav: { accedi: 'Acceder', registrati: 'Registrarse', entrar: 'Entrar al Sistema' },
                hero: { badge: '🍝 Sistema para Restaurantes', subtitle: '"El corazón de tu restaurante"', desc: 'Un sistema POS que transforma cada orden en una sinfonía de sabores, llevando el arte de la cocina italiana a la tecnología.', cta: 'Descubrir más', login: 'Acceder al Sistema' },
                stats: { modules: 'Módulos integrados', orders: 'Órdenes sin límite', cloud: 'On-premise', sri: 'Facturación electrónica' },
                features: { badge: 'Nuestra Oferta', title: 'Todo para tu restaurante', desc: 'Un ecosistema completo para la gestión de tu restaurante, desde la cocina hasta la cuenta.', items: [
                    { title: 'POS & Sala', desc: 'Gestión de comandas en tiempo real, mapa de mesas interactivo y flujo ordenado de sala a cocina.' },
                    { title: 'Cocina (KDS)', desc: 'Display en tiempo real para cocina. Cada plato, cada modificación, cada urgencia — todo bajo control.' },
                    { title: 'Menú & Productos', desc: 'Catálogo digital con categorías, modificadores, combos y promociones. Precios especiales para happy hour.' },
                    { title: 'Caja & Pagos', desc: 'Apertura y cierre de caja, pagos mixtos, división de cuenta, propina y facturación electrónica SRI.' },
                    { title: 'Inventario', desc: 'Control de stock en tiempo real, recetas con costo automático, alertas de mínimo y gestión de proveedores.' },
                    { title: 'Reportes & Analytics', desc: 'Dashboard con KPI en tiempo real, ventas por hora, ranking de productos y rendimiento de meseros.' },
                    { title: 'Usuarios & Roles', desc: 'Gestión avanzada con permisos granulares, PIN para POS, audit log y autenticación multi-rol.' },
                    { title: 'WhatsApp Marketing', desc: 'Campañas promocionales, catálogo digital, chatbot automático y CRM integrado con Meta Business.' },
                    { title: 'Notificaciones', desc: 'Alertas in-app, email y WhatsApp para stock crítico, comandas listas, errores SRI y cierre de caja.' },
                ]},
                cta: { badge: 'Bienvenido a bordo', title: '¿Listo para transformar tu restaurante?', desc: '"Oohh Solemia de mi corazón" — deja que la magia de la cocina italiana se encuentre con el poder de la tecnología.', cta: 'Comenzar ahora — ¡Es gratis!', cta_in: 'Entrar al sistema' },
                footer: { subtitle: 'Sistema POS para Restaurantes', made: 'Hecho con amor en Ecuador', culture: 'Para la cultura italiana', rights: 'Todos los derechos reservados.' },
            },
            it: {
                nav: { accedi: 'Accedi', registrati: 'Registrati', entrar: 'Entra nel Sistema' },
                hero: { badge: '🍝 Sistema di Ristorante', subtitle: '"¡Oh, Solémia de mi corazón!"', desc: 'Un sistema POS che cattura l\'essenza della ristorazione, trasformando ogni ordine in una sinfonia di sapori.', cta: 'Scopri di più', login: 'Accedi al Sistema' },
                stats: { modules: 'Moduli integrati', orders: 'Ordini illimitati', cloud: 'On-premise', sri: 'Fatturazione elettronica' },
                features: { badge: 'La Nostra Offerta', title: 'Tutto per il tuo ristorante', desc: 'Un ecosistema completo per la gestione del tuo ristorante, dalla cucina al conto.', items: [
                    { title: 'POS & Sala', desc: 'Gestione delle comande in tempo reale, mappa dei tavoli interattiva e flusso ordinato dalla sala alla cucina.' },
                    { title: 'Cucina (KDS)', desc: 'Display in tempo reale per la cucina. Ogni piatto, ogni modifica, ogni urgenza — tutto sotto controllo.' },
                    { title: 'Menu & Prodotti', desc: 'Catalogo digitale con categorie, modificatori, combo e promozioni. Prezzi speciali per happy hour.' },
                    { title: 'Cassa & Pagamenti', desc: 'Apertura e chiusura cassa, pagamenti misti, divisione del conto, mancia e fatturazione elettronica SRI.' },
                    { title: 'Inventario', desc: 'Controllo stock in tempo reale, ricette con costo automatico, alert di scorte minime e gestione fornitori.' },
                    { title: 'Report & Analytics', desc: 'Dashboard con KPI in tempo reale, vendite per ora, ranking prodotti e prestazioni dei camerieri.' },
                    { title: 'Utenti & Ruoli', desc: 'Gestione avanzata con permessi granulari, PIN per POS, audit log e autenticazione multi-ruolo.' },
                    { title: 'WhatsApp Marketing', desc: 'Campagne promozionali, catalogo digitale, chatbot automatico e CRM integrato con Meta Business.' },
                    { title: 'Notifiche', desc: 'Alert in-app, email e WhatsApp per scorte critiche, comande pronte, errori SRI e chiusura cassa.' },
                ]},
                cta: { badge: 'Benvenuto a bordo', title: 'Pronto a trasformare il tuo ristorante?', desc: '"Oohh Solemia de mi corazón" — lascia che la magia della cucina italiana incontri la potenza della tecnologia.', cta: 'Inizia ora — È gratis!', cta_in: 'Entra nel sistema' },
                footer: { subtitle: 'Sistema POS per Ristoranti', made: 'Fatto con amore in Ecuador', culture: 'Per la cultura italiana', rights: 'Tutti i diritti riservati.' },
            },
            en: {
                nav: { accedi: 'Sign In', registrati: 'Register', entrar: 'Enter System' },
                hero: { badge: '🍝 Restaurant System', subtitle: '"¡Oh, Solémia de mi corazón!"', desc: 'A POS system that captures the essence of dining, transforming every order into a symphony of flavors.', cta: 'Discover more', login: 'Access the System' },
                stats: { modules: 'Integrated modules', orders: 'Unlimited orders', cloud: 'On-premise', sri: 'Electronic invoicing' },
                features: { badge: 'Our Offering', title: 'Everything for your restaurant', desc: 'A complete ecosystem for managing your restaurant, from kitchen to check.', items: [
                    { title: 'POS & Dining', desc: 'Real-time order management, interactive table map, and seamless flow from dining room to kitchen.' },
                    { title: 'Kitchen (KDS)', desc: 'Real-time kitchen display. Every dish, every modification, every urgency — all under control.' },
                    { title: 'Menu & Products', desc: 'Digital catalog with categories, modifiers, combos and promotions. Special prices for happy hour.' },
                    { title: 'Cashier & Payments', desc: 'Cash register opening/closing, split payments, bill division, tips and SRI electronic invoicing.' },
                    { title: 'Inventory', desc: 'Real-time stock control, recipes with automatic costing, low stock alerts and supplier management.' },
                    { title: 'Reports & Analytics', desc: 'Dashboard with real-time KPIs, hourly sales, product ranking and waiter performance.' },
                    { title: 'Users & Roles', desc: 'Advanced management with granular permissions, POS PIN, audit log and multi-role authentication.' },
                    { title: 'WhatsApp Marketing', desc: 'Promotional campaigns, digital catalog, automated chatbot and CRM integrated with Meta Business.' },
                    { title: 'Notifications', desc: 'In-app, email and WhatsApp alerts for critical stock, ready orders, SRI errors and cash closure.' },
                ]},
                cta: { badge: 'Welcome aboard', title: 'Ready to transform your restaurant?', desc: '"Oohh Solemia de mi corazón" — let the magic of Italian cuisine meet the power of technology.', cta: 'Start now — It\'s free!', cta_in: 'Enter the system' },
                footer: { subtitle: 'POS System for Restaurants', made: 'Made with love in Ecuador', culture: 'For Italian culture', rights: 'All rights reserved.' },
            },
        };

        document.addEventListener('alpine:init', () => {
            const savedLang = localStorage.getItem('solemia_lang') || 'es';
            Alpine.store('lang', {
                code: savedLang,
                flag: { es: '🇪🇨', it: '🇮🇹', en: '🇺🇸' }[savedLang] || '🇪🇨',
                t: trans[savedLang],
            });

            Alpine.data('langSwitcher', () => ({
                langs: [
                    { code: 'es', name: 'Español', flag: '🇪🇨' },
                    { code: 'it', name: 'Italiano', flag: '🇮🇹' },
                    { code: 'en', name: 'English', flag: '🇺🇸' },
                ],
                icons: ['fa-utensils','fa-fire','fa-book-open','fa-cash-register','fa-boxes-stacked','fa-chart-line','fa-users-cog','fab fa-whatsapp','fa-bell'],
                get currentLang() { return this.$store.lang.code; },
                init() {
                    // Re-run reveal observer after Alpine renders
                    this.$nextTick(() => {
                        document.querySelectorAll('.reveal:not(.observed)').forEach(el => {
                            el.classList.add('observed');
                            revealObserver.observe(el);
                        });
                    });
                },
                switchLang(code) {
                    this.$store.lang.code = code;
                    this.$store.lang.flag = { es: '🇪🇨', it: '🇮🇹', en: '🇺🇸' }[code];
                    this.$store.lang.t = trans[code];
                    localStorage.setItem('solemia_lang', code);
                },
            }));
        });
    </script>
</body>
</html>