<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solémia — POS Restaurante</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:300,400,500,600,700,800i&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=dm-sans:300,400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            /* Verde oliva */
            --o50:  #f4f6f0;
            --o100: #e4e9d8;
            --o200: #c8d3b1;
            --o300: #a8b885;
            --o400: #869a5a;
            --o500: #677c3e;
            --o600: #506030;
            --o700: #3d4a24;
            --o800: #2c3419;
            --o900: #1c210f;
            --o950: #0e1108;
            /* Rojo italiano */
            --r400: #e03030;
            --r500: #c42020;
            --r600: #a01818;
            --r100: #fdecea;
            --r200: #f9c4c4;
            /* Blanco / cream */
            --cream: #fdfcf8;
            --cream2: #f5f3ee;
            --cream3: #ebe8e0;
            /* Gold acento */
            --g300: #f0c96a;
            --g400: #d4a827;
            --g600: #9a7618;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--o900);
            -webkit-font-smoothing: antialiased;
        }
        [x-cloak] { display: none !important; }

        /* ── TOPBAR ──────────────────────────────── */
        #topbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; height: 64px;
            background: rgba(253,252,248,0.92); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(80,96,48,0.1);
            transition: box-shadow 0.3s;
        }
        #topbar.scrolled { box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .topbar-logo {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.6rem; font-weight: 700; color: var(--o800);
            text-decoration: none; display: flex; align-items: center; gap: 0.5rem;
        }
        .logo-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--r500); display: inline-block; }
        .topbar-nav { display: flex; align-items: center; gap: 0.25rem; }
        .topbar-link {
            font-size: 0.85rem; font-weight: 400; color: var(--o700);
            padding: 0.4rem 0.85rem; border-radius: 1.5rem;
            text-decoration: none; transition: all 0.2s;
        }
        .topbar-link:hover { background: var(--o50); color: var(--o900); }
        .topbar-actions { display: flex; align-items: center; gap: 0.6rem; }
        /* Lang switcher */
        .lang-wrap { position: relative; }
        .lang-trigger {
            display: flex; align-items: center; gap: 0.4rem;
            background: none; border: 1px solid var(--o200);
            padding: 0.35rem 0.7rem; border-radius: 1.5rem;
            font-size: 0.75rem; font-weight: 500; color: var(--o700);
            cursor: pointer; transition: all 0.2s;
        }
        .lang-trigger:hover { border-color: var(--o400); background: var(--o50); }
        .lang-menu {
            position: absolute; right: 0; top: calc(100% + 6px);
            background: #fff; border: 1px solid var(--o100);
            border-radius: 0.9rem; box-shadow: 0 12px 36px rgba(0,0,0,0.1);
            padding: 0.4rem; min-width: 8.5rem; z-index: 300;
        }
        .lang-item {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.5rem 0.75rem; border-radius: 0.55rem;
            font-size: 0.82rem; cursor: pointer; transition: background 0.15s;
            color: var(--o700); background: none; border: none; width: 100%;
        }
        .lang-item:hover { background: var(--o50); }
        .lang-item.active { color: var(--o800); font-weight: 600; }
        /* Buttons */
        .btn-login {
            font-size: 0.83rem; font-weight: 500; color: var(--o700);
            padding: 0.45rem 1rem; border-radius: 1.5rem;
            border: 1px solid var(--o200); background: none; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-login:hover { border-color: var(--o500); color: var(--o900); background: var(--o50); }
        .btn-demo {
            font-size: 0.83rem; font-weight: 600; color: #fff;
            padding: 0.5rem 1.1rem; border-radius: 1.5rem;
            background: var(--r500); text-decoration: none;
            transition: all 0.25s; border: none;
            box-shadow: 0 2px 12px rgba(196,32,32,0.25);
        }
        .btn-demo:hover { background: var(--r400); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(196,32,32,0.3); }
        .btn-dash {
            font-size: 0.83rem; font-weight: 600; color: #fff;
            padding: 0.5rem 1.1rem; border-radius: 1.5rem;
            background: var(--o600); text-decoration: none;
            transition: all 0.25s;
        }
        .btn-dash:hover { background: var(--o500); transform: translateY(-1px); }

        /* ── HERO ────────────────────────────────── */
        .hero {
            min-height: 100dvh;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            text-align: center;
            padding: 8rem 1.5rem 5rem;
            position: relative; overflow: hidden;
            background: var(--o950);
        }
        /* Tricolore stripe top */
        .hero::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--o500) 33.33%, #fff 33.33% 66.66%, var(--r500) 66.66%);
        }
        .hero-bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 56px 56px;
        }
        .hero-glow {
            position: absolute; top: -100px; left: 50%; transform: translateX(-50%);
            width: 700px; height: 500px;
            background: radial-gradient(ellipse, rgba(103,124,62,0.18) 0%, rgba(196,32,32,0.06) 50%, transparent 75%);
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            padding: 0.35rem 0.9rem; border-radius: 2rem;
            font-size: 0.75rem; font-weight: 500; color: rgba(255,255,255,0.65);
            letter-spacing: 0.05em; margin-bottom: 1.5rem;
            opacity: 0; animation: upIn 0.7s ease 0.2s forwards;
        }
        .hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--r400); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1}50%{opacity:0.4} }
        .hero-title {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: clamp(4.5rem, 14vw, 10rem);
            font-weight: 800; line-height: 0.88; letter-spacing: -0.02em;
            margin-bottom: 1rem;
            opacity: 0; animation: upIn 0.85s ease 0.35s forwards;
        }
        .hero-title-main { color: #fff; display: block; }
        .hero-title-accent { color: var(--o300); display: block; font-style: italic; font-weight: 400; font-size: 0.5em; margin-top: 0.2em; letter-spacing: 0.02em; }
        .hero-divider {
            display: flex; align-items: center; gap: 0.75rem;
            justify-content: center; margin-bottom: 1.25rem;
            opacity: 0; animation: upIn 0.7s ease 0.5s forwards;
        }
        .hero-divider-line { width: 40px; height: 1px; background: rgba(255,255,255,0.2); }
        .hero-divider-icon { color: var(--r400); font-size: 0.8rem; }
        .hero-desc {
            font-size: clamp(1rem, 2vw, 1.15rem); color: rgba(255,255,255,0.5);
            max-width: 38rem; line-height: 1.75; font-weight: 300;
            margin-bottom: 2.5rem;
            opacity: 0; animation: upIn 0.8s ease 0.6s forwards;
        }
        .hero-desc strong { color: rgba(255,255,255,0.8); font-weight: 500; }
        .hero-ctas {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 0.85rem;
            margin-bottom: 3.5rem;
            opacity: 0; animation: upIn 0.8s ease 0.75s forwards;
        }
        .hero-btn-red {
            display: inline-flex; align-items: center; gap: 0.6rem;
            background: var(--r500); color: #fff;
            padding: 0.9rem 2rem; border-radius: 2rem;
            font-size: 0.92rem; font-weight: 600; text-decoration: none;
            box-shadow: 0 4px 24px rgba(196,32,32,0.35);
            transition: all 0.3s ease;
        }
        .hero-btn-red:hover { background: var(--r400); transform: translateY(-2px); box-shadow: 0 10px 32px rgba(196,32,32,0.4); }
        .hero-btn-outline {
            display: inline-flex; align-items: center; gap: 0.6rem;
            background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.75);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 0.9rem 2rem; border-radius: 2rem;
            font-size: 0.92rem; font-weight: 400; text-decoration: none;
            transition: all 0.3s ease;
        }
        .hero-btn-outline:hover { background: rgba(255,255,255,0.13); border-color: rgba(255,255,255,0.3); transform: translateY(-2px); }
        /* Trust strip */
        .hero-trust {
            opacity: 0; animation: upIn 0.7s ease 0.9s forwards;
            display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 1.5rem;
        }
        .trust-stat { text-align: center; }
        .trust-stat-num {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.8rem; font-weight: 700; color: #fff; line-height: 1;
        }
        .trust-stat-lbl { font-size: 0.7rem; color: rgba(255,255,255,0.35); letter-spacing: 0.08em; text-transform: uppercase; margin-top: 0.15rem; }
        .trust-sep { width: 1px; height: 32px; background: rgba(255,255,255,0.1); }
        .hero-scroll {
            position: absolute; bottom: 1.75rem; left: 50%; transform: translateX(-50%);
            color: rgba(255,255,255,0.2); font-size: 0.85rem;
            display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
            animation: bounce 2.5s ease-in-out infinite;
        }

        /* ── TRICOLORE DIVIDER ───────────────────── */
        .tricolore { display: flex; height: 5px; }
        .tricolore-g { flex: 1; background: var(--o500); }
        .tricolore-w { flex: 1; background: #fff; border-top: 1px solid var(--cream3); border-bottom: 1px solid var(--cream3); }
        .tricolore-r { flex: 1; background: var(--r500); }

        /* ── STATS BAND ──────────────────────────── */
        .stats-band { background: var(--cream2); padding: 2.25rem 1.5rem; border-bottom: 1px solid var(--cream3); }
        .stats-inner { max-width: 72rem; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px,1fr)); }
        .stat-cell { padding: 0.5rem 1.5rem; text-align: center; }
        .stat-cell + .stat-cell { border-left: 1px solid var(--cream3); }
        .stat-n { font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 700; color: var(--o800); line-height: 1; }
        .stat-n.red { color: var(--r500); }
        .stat-l { font-size: 0.72rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--o500); margin-top: 0.3rem; }

        /* ── SECTION COMMONS ─────────────────────── */
        .section { padding: 5.5rem 1.5rem; }
        .section-inner { max-width: 72rem; margin: 0 auto; }
        .tag {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.7rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--o600); background: var(--o50); border: 1px solid var(--o200);
            padding: 0.3rem 0.75rem; border-radius: 2rem; margin-bottom: 1rem;
        }
        .tag-red { color: var(--r500); background: var(--r100); border-color: var(--r200); }
        .sec-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4.5vw, 3.25rem);
            font-weight: 700; line-height: 1.1; color: var(--o900);
        }
        .sec-sub { font-size: 1rem; color: var(--o500); line-height: 1.7; font-weight: 300; margin-top: 0.75rem; }

        /* ── NEGOCIO TYPES ───────────────────────── */
        .biz-section { background: var(--cream); }
        .biz-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1px; background: var(--cream3);
            border: 1px solid var(--cream3); border-radius: 1.25rem; overflow: hidden;
            margin-top: 3rem;
        }
        .biz-card { background: var(--cream); padding: 2rem 1.75rem; transition: background 0.2s; }
        .biz-card:hover { background: #fff; }
        .biz-icon {
            width: 2.75rem; height: 2.75rem; border-radius: 0.7rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem; margin-bottom: 1rem;
            background: var(--o100); color: var(--o600);
            transition: all 0.25s;
        }
        .biz-card:hover .biz-icon { background: var(--o700); color: #fff; }
        .biz-name { font-size: 0.95rem; font-weight: 600; color: var(--o800); margin-bottom: 0.45rem; }
        .biz-desc { font-size: 0.85rem; color: var(--o500); line-height: 1.6; }

        /* ── RENTABILIDAD ────────────────────────── */
        .rent-section { background: var(--o950); }
        .rent-header { text-align: center; max-width: 42rem; margin: 0 auto 3.5rem; }
        .rent-header .sec-title { color: #fff; }
        .rent-header .sec-sub { color: rgba(255,255,255,0.45); }
        .rent-stat-big {
            text-align: center; margin-bottom: 2.5rem;
            padding: 2rem;
            background: rgba(196,32,32,0.08); border: 1px solid rgba(196,32,32,0.2);
            border-radius: 1.25rem;
        }
        .rent-stat-pct {
            font-family: 'Cormorant Garamond', serif;
            font-size: 5rem; font-weight: 800; line-height: 1;
            color: var(--r400); display: block;
        }
        .rent-stat-text { font-size: 1rem; color: rgba(255,255,255,0.6); margin-top: 0.5rem; line-height: 1.5; }
        .rent-list { list-style: none; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap: 0.75rem; }
        .rent-item {
            display: flex; align-items: flex-start; gap: 0.75rem;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
            border-radius: 0.85rem; padding: 1rem 1.25rem;
        }
        .rent-item-icon { color: var(--o400); font-size: 0.9rem; margin-top: 0.1rem; flex-shrink: 0; }
        .rent-item-text { font-size: 0.88rem; color: rgba(255,255,255,0.65); line-height: 1.5; }

        /* ── FEATURES GRID ───────────────────────── */
        .features-section { background: var(--cream2); }
        .features-header {
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end;
            gap: 1.5rem; margin-bottom: 3rem;
        }
        .feat-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(270px,1fr));
            gap: 1px; background: var(--cream3);
            border: 1px solid var(--cream3); border-radius: 1.25rem; overflow: hidden;
        }
        .feat-card { background: var(--cream2); padding: 1.75rem; transition: background 0.2s; }
        .feat-card:hover { background: #fff; }
        .feat-icon-box {
            width: 2.5rem; height: 2.5rem; border-radius: 0.6rem;
            background: var(--o100); display: flex; align-items: center; justify-content: center;
            font-size: 1rem; color: var(--o600); margin-bottom: 1rem;
            transition: all 0.25s;
        }
        .feat-card:hover .feat-icon-box { background: var(--o700); color: #fff; }
        .feat-title { font-size: 0.95rem; font-weight: 600; color: var(--o800); margin-bottom: 0.5rem; }
        .feat-desc { font-size: 0.83rem; color: var(--o500); line-height: 1.6; }

        /* ── DEMO CTA ────────────────────────────── */
        .demo-section {
            background: linear-gradient(135deg, var(--o800) 0%, var(--o950) 60%, #1a0808 100%);
            padding: 5.5rem 1.5rem; text-align: center; position: relative; overflow: hidden;
        }
        .demo-section::before {
            content: ''; position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .demo-section::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--o400) 33.33%, #fff 33.33% 66.66%, var(--r500) 66.66%);
        }
        .demo-inner { position: relative; z-index: 1; max-width: 44rem; margin: 0 auto; }
        .demo-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; color: #fff; line-height: 1.1; margin-bottom: 1rem; }
        .demo-desc { font-size: 1rem; color: rgba(255,255,255,0.45); line-height: 1.75; margin-bottom: 2.25rem; font-weight: 300; }
        .demo-btn {
            display: inline-flex; align-items: center; gap: 0.7rem;
            background: var(--r500); color: #fff;
            padding: 1rem 2.5rem; border-radius: 2rem;
            font-size: 1.05rem; font-weight: 700; text-decoration: none;
            box-shadow: 0 4px 32px rgba(196,32,32,0.4);
            transition: all 0.3s ease;
        }
        .demo-btn:hover { background: var(--r400); transform: translateY(-2px); box-shadow: 0 12px 40px rgba(196,32,32,0.5); }
        .demo-note { font-size: 0.78rem; color: rgba(255,255,255,0.3); margin-top: 1rem; }
        .demo-features {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; margin-top: 2.5rem;
        }
        .demo-feat-badge {
            display: flex; align-items: center; gap: 0.45rem;
            font-size: 0.8rem; color: rgba(255,255,255,0.5);
        }
        .demo-feat-badge i { color: var(--o400); }

        /* ── TESTIMONIALS ────────────────────────── */
        .testi-section { background: var(--cream); }
        .testi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap: 1.25rem; margin-top: 3rem; }
        .testi-card {
            background: #fff; border: 1px solid var(--cream3);
            border-radius: 1.1rem; padding: 1.5rem;
            transition: box-shadow 0.2s;
        }
        .testi-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.07); }
        .testi-stars { display: flex; gap: 3px; margin-bottom: 0.9rem; }
        .testi-stars i { color: var(--g400); font-size: 0.8rem; }
        .testi-quote { font-size: 0.88rem; color: var(--o700); line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
        .testi-quote::before { content: '"'; color: var(--r400); font-size: 1.2rem; font-family: 'Cormorant Garamond', serif; line-height: 0; vertical-align: -0.2rem; }
        .testi-author { display: flex; align-items: center; gap: 0.75rem; }
        .testi-avatar {
            width: 2.25rem; height: 2.25rem; border-radius: 50%;
            background: var(--o100); color: var(--o600);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 600; flex-shrink: 0;
        }
        .testi-name { font-size: 0.85rem; font-weight: 600; color: var(--o800); }
        .testi-role { font-size: 0.75rem; color: var(--o400); }

        /* ── MIGRATION ───────────────────────────── */
        .migration-section { background: var(--cream2); }
        .migration-card {
            max-width: 52rem; margin: 3rem auto 0;
            background: #fff; border: 1px solid var(--cream3);
            border-radius: 1.5rem; padding: 2.5rem;
            display: flex; flex-wrap: wrap; align-items: center; gap: 2rem;
        }
        .migration-left { flex: 1; min-width: 200px; }
        .migration-title { font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; font-weight: 700; color: var(--o900); margin-bottom: 0.75rem; line-height: 1.2; }
        .migration-sub { font-size: 0.9rem; color: var(--o500); line-height: 1.6; }
        .migration-right { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        .mig-item {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.85rem; color: var(--o700);
        }
        .mig-item i { color: var(--o500); font-size: 0.85rem; }
        .migration-cta { margin-top: 1.5rem; }

        /* ── FOOTER ──────────────────────────────── */
        .footer { background: var(--o950); border-top: 1px solid rgba(103,124,62,0.15); padding: 3rem 1.5rem; }
        .footer-inner { max-width: 72rem; margin: 0 auto; }
        .footer-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 2rem; margin-bottom: 2.5rem; }
        .footer-brand-block {}
        .foot-logo { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 0.5rem; }
        .foot-sub { font-size: 0.78rem; color: rgba(255,255,255,0.3); margin-top: 0.3rem; }
        .footer-links { display: flex; flex-wrap: wrap; gap: 3rem; }
        .footer-col-title { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 0.85rem; }
        .footer-col-link { display: block; font-size: 0.82rem; color: rgba(255,255,255,0.45); text-decoration: none; margin-bottom: 0.5rem; transition: color 0.15s; }
        .footer-col-link:hover { color: rgba(255,255,255,0.8); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 1.5rem; display: flex; flex-wrap: wrap;
            justify-content: space-between; align-items: center; gap: 1rem;
        }
        .footer-copy { font-size: 0.75rem; color: rgba(255,255,255,0.22); }
        .footer-socials { display: flex; gap: 0.75rem; }
        .soc-btn {
            width: 32px; height: 32px; border-radius: 50%;
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.4); font-size: 0.8rem;
            text-decoration: none; transition: all 0.2s;
        }
        .soc-btn:hover { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.8); }

        /* ── FLOATING DEMO BTN ───────────────────── */
        .float-demo {
            position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999;
            display: flex; align-items: center; gap: 0.55rem;
            background: var(--r500); color: #fff;
            padding: 0.75rem 1.35rem; border-radius: 2rem;
            font-size: 0.85rem; font-weight: 700; text-decoration: none;
            box-shadow: 0 4px 24px rgba(196,32,32,0.45);
            transition: all 0.3s; animation: upIn 0.8s ease 1.2s both;
        }
        .float-demo:hover { background: var(--r400); transform: translateY(-2px) scale(1.03); box-shadow: 0 8px 32px rgba(196,32,32,0.5); }
        .float-demo .fd-pulse { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.7); animation: pulse 1.8s infinite; flex-shrink: 0; }

        /* ── ANIMATIONS ──────────────────────────── */
        @keyframes upIn {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%,100% { transform: translateX(-50%) translateY(0); }
            50%      { transform: translateX(-50%) translateY(7px); }
        }
        .reveal { opacity: 0; transform: translateY(18px); transition: opacity 0.55s ease, transform 0.55s ease; }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .rd1 { transition-delay: 0.1s; } .rd2 { transition-delay: 0.18s; } .rd3 { transition-delay: 0.26s; }
        .rd4 { transition-delay: 0.34s; } .rd5 { transition-delay: 0.42s; } .rd6 { transition-delay: 0.5s; }

        /* ── RESPONSIVE ──────────────────────────── */
        @media (max-width: 768px) {
            #topbar .topbar-nav { display: none; }
            .footer-links { gap: 1.5rem; }
            .migration-right { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body x-data="langSwitcher()" x-init="init()">

    {{-- ── TOPBAR ─────────────────────────────────────────────── --}}
    @if(Route::has('login'))
    <nav id="topbar" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 40" :class="{ scrolled }">
        <a href="#" class="topbar-logo">
            Solémia <span class="logo-dot"></span>
        </a>
        <div class="topbar-nav">
            <a href="#features" class="topbar-link" x-text="$store.lang.t.nav.modulos"></a>
            <a href="#tipos" class="topbar-link" x-text="$store.lang.t.nav.negocios"></a>
            <a href="#testimonios" class="topbar-link" x-text="$store.lang.t.nav.testimonios"></a>
        </div>
        <div class="topbar-actions">
            <div class="lang-wrap" x-data="{ open: false }">
                <button @click="open = !open" class="lang-trigger">
                    <span x-text="$store.lang.flag"></span>
                    <span x-text="$store.lang.code.toUpperCase()"></span>
                    <i class="fas fa-chevron-down" style="font-size:0.6rem;"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="lang-menu" x-cloak>
                    <template x-for="lang in langs" :key="lang.code">
                        <button @click="switchLang(lang.code); open = false" class="lang-item" :class="currentLang === lang.code ? 'active' : ''">
                            <span x-text="lang.flag"></span>
                            <span x-text="lang.name"></span>
                        </button>
                    </template>
                </div>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-dash">
                    <i class="fas fa-tachometer-alt"></i>
                    <span x-text="$store.lang.t.nav.entrar"></span>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login" x-text="$store.lang.t.nav.accedi"></a>
                @if(Route::has('register'))
                <a href="{{ route('register') }}" class="btn-demo">
                    <i class="fas fa-calendar-check"></i>
                    <span x-text="$store.lang.t.nav.demo"></span>
                </a>
                @endif
            @endauth
        </div>
    </nav>
    @endif

    {{-- ── HERO ────────────────────────────────────────────────── --}}
    <section class="hero">
        <div class="hero-bg-grid"></div>
        <div class="hero-glow"></div>

        <div class="hero-badge">
            <span class="hero-badge-dot"></span>
            <span x-text="$store.lang.t.hero.badge"></span>
        </div>

        <h1 class="hero-title">
            <span class="hero-title-main">Solémia</span>
            <span class="hero-title-accent" x-text="$store.lang.t.hero.subtitle"></span>
        </h1>

        <div class="hero-divider">
            <span class="hero-divider-line"></span>
            <i class="fas fa-utensils hero-divider-icon"></i>
            <span class="hero-divider-line"></span>
        </div>

        <p class="hero-desc" x-html="$store.lang.t.hero.desc"></p>

        <div class="hero-ctas">
            @guest
            <a href="{{ route('register') }}" class="hero-btn-red">
                <i class="fas fa-calendar-check"></i>
                <span x-text="$store.lang.t.hero.cta_demo"></span>
            </a>
            <a href="{{ route('login') }}" class="hero-btn-outline">
                <i class="fas fa-sign-in-alt"></i>
                <span x-text="$store.lang.t.hero.cta_login"></span>
            </a>
            @else
            <a href="{{ route('dashboard') }}" class="hero-btn-red">
                <i class="fas fa-arrow-right"></i>
                <span x-text="$store.lang.t.hero.cta_dash"></span>
            </a>
            @endguest
        </div>

        <div class="hero-trust">
            <div class="trust-stat">
                <div class="trust-stat-num">+500</div>
                <div class="trust-stat-lbl" x-text="$store.lang.t.hero.trust_rest"></div>
            </div>
            <div class="trust-sep"></div>
            <div class="trust-stat">
                <div class="trust-stat-num">SRI</div>
                <div class="trust-stat-lbl" x-text="$store.lang.t.hero.trust_sri"></div>
            </div>
            <div class="trust-sep"></div>
            <div class="trust-stat">
                <div class="trust-stat-num">9</div>
                <div class="trust-stat-lbl" x-text="$store.lang.t.hero.trust_mod"></div>
            </div>
            <div class="trust-sep"></div>
            <div class="trust-stat">
                <div class="trust-stat-num">24/7</div>
                <div class="trust-stat-lbl" x-text="$store.lang.t.hero.trust_sup"></div>
            </div>
        </div>

        <div class="hero-scroll"><i class="fas fa-chevron-down"></i></div>
    </section>

    <div class="tricolore"></div>

    {{-- ── STATS BAND ───────────────────────────────────────────── --}}
    <div class="stats-band">
        <div class="stats-inner">
            <div class="stat-cell reveal"><div class="stat-n">9</div><div class="stat-l" x-text="$store.lang.t.stats.s1"></div></div>
            <div class="stat-cell reveal rd1"><div class="stat-n red">60%</div><div class="stat-l" x-text="$store.lang.t.stats.s2"></div></div>
            <div class="stat-cell reveal rd2"><div class="stat-n">∞</div><div class="stat-l" x-text="$store.lang.t.stats.s3"></div></div>
            <div class="stat-cell reveal rd3"><div class="stat-n">100%</div><div class="stat-l" x-text="$store.lang.t.stats.s4"></div></div>
        </div>
    </div>

    {{-- ── TIPOS DE NEGOCIO ─────────────────────────────────────── --}}
    <section id="tipos" class="section biz-section">
        <div class="section-inner">
            <div class="reveal">
                <span class="tag"><i class="fas fa-store"></i> <span x-text="$store.lang.t.biz.tag"></span></span>
                <h2 class="sec-title" x-text="$store.lang.t.biz.title"></h2>
                <p class="sec-sub" x-text="$store.lang.t.biz.sub"></p>
            </div>
            <div class="biz-grid reveal rd1">
                <template x-for="(b, i) in $store.lang.t.biz.items" :key="i">
                    <div class="biz-card">
                        <div class="biz-icon"><i :class="bizIcons[i]"></i></div>
                        <div class="biz-name" x-text="b.name"></div>
                        <div class="biz-desc" x-text="b.desc"></div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <div class="tricolore"></div>

    {{-- ── RENTABILIDAD ─────────────────────────────────────────── --}}
    <section class="section rent-section">
        <div class="section-inner">
            <div class="rent-header reveal">
                <span class="tag tag-red"><i class="fas fa-chart-line"></i> <span x-text="$store.lang.t.rent.tag"></span></span>
                <h2 class="sec-title" x-text="$store.lang.t.rent.title"></h2>
                <p class="sec-sub" x-text="$store.lang.t.rent.sub"></p>
            </div>
            <div class="rent-stat-big reveal rd1">
                <span class="rent-stat-pct" x-text="$store.lang.t.rent.pct"></span>
                <p class="rent-stat-text" x-text="$store.lang.t.rent.pct_text"></p>
            </div>
            <ul class="rent-list">
                <template x-for="(item, i) in $store.lang.t.rent.items" :key="i">
                    <li class="rent-item reveal" :class="`rd${i+1}`">
                        <i class="fas fa-check rent-item-icon"></i>
                        <span class="rent-item-text" x-text="item"></span>
                    </li>
                </template>
            </ul>
        </div>
    </section>

    {{-- ── FEATURES ─────────────────────────────────────────────── --}}
    <section id="features" class="section features-section">
        <div class="section-inner">
            <div class="features-header reveal">
                <div>
                    <span class="tag"><i class="fas fa-cubes"></i> <span x-text="$store.lang.t.feat.tag"></span></span>
                    <h2 class="sec-title" x-text="$store.lang.t.feat.title"></h2>
                </div>
                <p class="sec-sub" style="max-width:26rem;text-align:right;" x-text="$store.lang.t.feat.sub"></p>
            </div>
            <div class="feat-grid">
                <template x-for="(f, i) in $store.lang.t.feat.items" :key="i">
                    <div class="feat-card reveal" :class="`rd${(i%6)+1}`">
                        <div class="feat-icon-box"><i :class="['fas', featIcons[i]]"></i></div>
                        <div class="feat-title" x-text="f.title"></div>
                        <div class="feat-desc" x-text="f.desc"></div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ── DEMO CTA ─────────────────────────────────────────────── --}}
    <section class="demo-section">
        <div class="demo-inner reveal">
            <span class="tag tag-red" style="margin-bottom:1.25rem;"><i class="fas fa-rocket"></i> <span x-text="$store.lang.t.demo.tag"></span></span>
            <h2 class="demo-title" x-text="$store.lang.t.demo.title"></h2>
            <p class="demo-desc" x-text="$store.lang.t.demo.desc"></p>
            @guest
            <a href="{{ route('register') }}" class="demo-btn">
                <i class="fas fa-calendar-check"></i>
                <span x-text="$store.lang.t.demo.cta"></span>
            </a>
            @else
            <a href="{{ route('dashboard') }}" class="demo-btn">
                <i class="fas fa-arrow-right"></i>
                <span x-text="$store.lang.t.demo.cta_in"></span>
            </a>
            @endguest
            <p class="demo-note" x-text="$store.lang.t.demo.note"></p>
            <div class="demo-features">
                <template x-for="feat in $store.lang.t.demo.perks" :key="feat">
                    <span class="demo-feat-badge"><i class="fas fa-check-circle"></i> <span x-text="feat"></span></span>
                </template>
            </div>
        </div>
    </section>

    {{-- ── TESTIMONIALES ────────────────────────────────────────── --}}
    <section id="testimonios" class="section testi-section">
        <div class="section-inner">
            <div class="reveal" style="text-align:center;max-width:36rem;margin:0 auto 3rem;">
                <span class="tag"><i class="fas fa-star"></i> <span x-text="$store.lang.t.testi.tag"></span></span>
                <h2 class="sec-title" x-text="$store.lang.t.testi.title"></h2>
            </div>
            <div class="testi-grid">
                <template x-for="(t, i) in $store.lang.t.testi.items" :key="i">
                    <div class="testi-card reveal" :class="`rd${(i%6)+1}`">
                        <div class="testi-stars">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testi-quote" x-text="t.quote"></p>
                        <div class="testi-author">
                            <div class="testi-avatar" x-text="t.initials"></div>
                            <div>
                                <div class="testi-name" x-text="t.name"></div>
                                <div class="testi-role" x-text="t.role"></div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ── MIGRACIÓN ────────────────────────────────────────────── --}}
    <section class="section migration-section">
        <div class="section-inner">
            <div class="migration-card reveal">
                <div class="migration-left">
                    <span class="tag"><i class="fas fa-exchange-alt"></i> <span x-text="$store.lang.t.mig.tag"></span></span>
                    <h3 class="migration-title" x-text="$store.lang.t.mig.title"></h3>
                    <p class="migration-sub" x-text="$store.lang.t.mig.sub"></p>
                    <div class="migration-cta">
                        @guest
                        <a href="{{ route('register') }}" class="btn-demo" style="display:inline-flex;align-items:center;gap:0.5rem;">
                            <i class="fas fa-calendar-check"></i>
                            <span x-text="$store.lang.t.mig.cta"></span>
                        </a>
                        @else
                        <a href="{{ route('dashboard') }}" class="btn-dash" style="display:inline-flex;align-items:center;gap:0.5rem;">
                            <i class="fas fa-arrow-right"></i>
                            <span x-text="$store.lang.t.mig.cta_in"></span>
                        </a>
                        @endguest
                    </div>
                </div>
                <div class="migration-right">
                    <template x-for="item in $store.lang.t.mig.perks" :key="item">
                        <div class="mig-item"><i class="fas fa-check-circle"></i> <span x-text="item"></span></div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FOOTER ───────────────────────────────────────────────── --}}
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-top">
                <div class="footer-brand-block">
                    <div class="foot-logo">Solémia <span class="logo-dot"></span></div>
                    <div class="foot-sub" x-text="$store.lang.t.footer.sub"></div>
                </div>
                <div class="footer-links">
                    <div>
                        <div class="footer-col-title" x-text="$store.lang.t.footer.col1.title"></div>
                        <template x-for="lnk in $store.lang.t.footer.col1.links" :key="lnk">
                            <a href="#" class="footer-col-link" x-text="lnk"></a>
                        </template>
                    </div>
                    <div>
                        <div class="footer-col-title" x-text="$store.lang.t.footer.col2.title"></div>
                        <template x-for="lnk in $store.lang.t.footer.col2.links" :key="lnk">
                            <a href="#" class="footer-col-link" x-text="lnk"></a>
                        </template>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-copy">&copy; {{ date('Y') }} Solémia. <span x-text="$store.lang.t.footer.rights"></span></div>
                <div class="footer-socials">
                    <a href="#" class="soc-btn"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="soc-btn"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="soc-btn"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating CTA --}}
    @guest
    <a href="{{ route('register') }}" class="float-demo">
        <span class="fd-pulse"></span>
        <span x-text="$store.lang.t.hero.cta_demo"></span>
    </a>
    @endguest

    @livewireScripts
    <script>
        // Scroll reveal
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); } });
        }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });
        document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

        const trans = {
            es: {
                nav: { modulos: 'Módulos', negocios: 'Negocios', testimonios: 'Testimonios', accedi: 'Acceder', registrati: 'Registrarse', demo: 'Agendar Demo', entrar: 'Panel' },
                hero: {
                    badge: 'Sistema POS para Restaurantes — Ecuador',
                    subtitle: '"El corazón de tu restaurante"',
                    desc: 'Controla costos, gestiona inventarios y aumenta tu <strong>rentabilidad</strong> con el sistema diseñado para restaurantes ecuatorianos.',
                    cta_demo: 'Agendar Demo Gratis', cta_login: 'Acceder al Sistema', cta_dash: 'Ir al Panel',
                    trust_rest: 'Restaurantes activos', trust_sri: 'Facturación cert.', trust_mod: 'Módulos integrados', trust_sup: 'Soporte disponible',
                },
                stats: { s1: 'Módulos integrados', s2: 'Quiebras por mal control', s3: 'Órdenes procesadas', s4: 'On-premise seguro' },
                biz: {
                    tag: 'Tu tipo de negocio',
                    title: 'Cada negocio tiene su esencia',
                    sub: 'Sea cual sea tu especialidad, Solémia se adapta a tu operación.',
                    items: [
                        { name: 'Restaurantes', desc: 'Controla costos, inventarios y mejora la experiencia del comensal.' },
                        { name: 'Fast Food', desc: 'Agiliza pedidos y asegura un servicio impecable en cada transacción.' },
                        { name: 'Bares & Discotecas', desc: 'Gestiona el flujo nocturno con precisión y control absoluto.' },
                        { name: 'Cadenas / Franquicias', desc: 'Centraliza la operación de todos tus locales con facilidad.' },
                        { name: 'Catering & Colegios', desc: 'Administra ventas de alimentos en empresas o instituciones.' },
                        { name: 'Retail / Shops', desc: 'Gestiona tu tienda con eficiencia, desde inventarios hasta ventas.' },
                    ],
                },
                rent: {
                    tag: 'Rentabilidad',
                    title: 'Más del 60% de quiebras\nse dan por mal control de costo',
                    sub: 'Por eso te damos las herramientas para que tu restaurante sea rentable desde el primer mes.',
                    pct: '60%',
                    pct_text: 'de restaurantes quiebran por no controlar sus costos operativos.',
                    items: [
                        'Control de costos y porcentaje de costo en tiempo real',
                        'Módulo de planificación de ingresos y punto de equilibrio',
                        'Gestión de inventarios simplificada con alertas de stock',
                        'Punto de Venta intuitivo con cierre de caja rápido',
                        'Facturación electrónica compatible con SRI Ecuador',
                        'Dashboard con KPIs y reportes estratégicos',
                    ],
                },
                feat: {
                    tag: 'Módulos',
                    title: 'Todo para tu restaurante',
                    sub: 'Un ecosistema completo desde la cocina hasta la cuenta.',
                    items: [
                        { title: 'POS & Sala', desc: 'Comandas en tiempo real, mapa de mesas y flujo ordenado de sala a cocina.' },
                        { title: 'Cocina (KDS)', desc: 'Display en tiempo real. Cada plato, modificación y urgencia bajo control.' },
                        { title: 'Menú & Productos', desc: 'Catálogo digital con categorías, combos y happy hour.' },
                        { title: 'Caja & Pagos', desc: 'Pagos mixtos, división de cuenta, propina y facturación SRI.' },
                        { title: 'Inventario', desc: 'Stock en tiempo real, recetas con costo automático y alertas de mínimo.' },
                        { title: 'Reportes & KPIs', desc: 'Dashboard con ventas por hora, ranking de platos y rendimiento.' },
                        { title: 'Usuarios & Roles', desc: 'Permisos granulares, PIN POS, audit log y autenticación multi-rol.' },
                        { title: 'WhatsApp Marketing', desc: 'Campañas, chatbot automático y CRM con Meta Business.' },
                        { title: 'Notificaciones', desc: 'Alertas in-app, email y WhatsApp para eventos críticos.' },
                    ],
                },
                demo: {
                    tag: 'Sin costo',
                    title: '¿Listo para transformar tu restaurante?',
                    desc: 'Agenda una demo personalizada y descubre cómo Solémia puede reducir tus costos y aumentar tu rentabilidad desde el primer día.',
                    cta: '¡Agendar Demo Gratis!', cta_in: 'Ir al Panel',
                    note: 'Sin tarjeta de crédito · Sin compromiso · En 30 minutos',
                    perks: ['Migración sin costo', 'Soporte 24/7', 'Configuración incluida', 'Facturación SRI'],
                },
                testi: {
                    tag: 'Testimonios',
                    title: 'Lo que dicen nuestros clientes',
                    items: [
                        { quote: 'Antes cerraba el mes sin saber si había ganado o perdido. Con Solémia veo mis costos en tiempo real y por fin tomo decisiones con seguridad.', name: 'Andrés V.', role: 'Propietario, El Rincón Criollo', initials: 'AV' },
                        { quote: 'El módulo de inventarios nos cambió la vida. Eliminamos el desperdicio en cocina y bajamos el costo de alimentos un 12% en el primer trimestre.', name: 'Patricia N.', role: 'Administradora, Marisquería Don Pacífico', initials: 'PN' },
                        { quote: 'Implementamos Solémia en los tres locales al mismo tiempo y fue un proceso limpio y ordenado. El soporte estuvo con nosotros desde el día uno.', name: 'Miguel Á.', role: 'Gerente de Operaciones, Grupo Sabores EC', initials: 'MA' },
                        { quote: 'Nunca pensé que un sistema POS pudiera ayudarme a entender mi punto de equilibrio. Ahora sé cuánto necesito vender cada día para no perder.', name: 'Sofía B.', role: 'Dueña, Café Bambú', initials: 'SB' },
                        { quote: 'La facturación electrónica con SRI fue lo que me convenció. Cero errores, automático y sin papeleo. Eso vale oro para cualquier restaurante en Ecuador.', name: 'Ramón E.', role: 'Gerente General, Parrilla El Gaucho', initials: 'RE' },
                        { quote: 'El KDS en cocina redujo los tiempos de entrega de platos en un 30%. Los meseros ya no van y vienen con papelitos. Todo fluye solo.', name: 'Daniela C.', role: 'Chef & Socia, Fusión Andina', initials: 'DC' },
                    ],
                },
                mig: {
                    tag: 'Migración',
                    title: '¿Ya tienes otro sistema?\nCambia sin riesgos',
                    sub: 'Nos encargamos de que la migración de tu información sea un proceso sin estrés.',
                    cta: 'Hablar con un asesor', cta_in: 'Ir al Panel',
                    perks: ['Sin costo de migración', 'Proceso seguro', 'Sin pérdida de datos', 'Migración rápida'],
                },
                footer: {
                    sub: 'Sistema POS para Restaurantes · Ecuador',
                    rights: 'Todos los derechos reservados.',
                    col1: { title: 'Producto', links: ['POS & Sala', 'Cocina KDS', 'Inventario', 'Facturación SRI', 'Reportes'] },
                    col2: { title: 'Empresa', links: ['Contacto', 'Soporte', 'Blog', 'Precios'] },
                },
            },
            it: {
                nav: { modulos: 'Moduli', negocios: 'Settori', testimonios: 'Testimonianze', accedi: 'Accedi', registrati: 'Registrati', demo: 'Prenota Demo', entrar: 'Pannello' },
                hero: {
                    badge: 'Sistema POS per Ristoranti — Ecuador',
                    subtitle: '"¡Oh, Solémia de mi corazón!"',
                    desc: 'Controlla i costi, gestisci gli inventari e aumenta la tua <strong>redditività</strong> con il sistema pensato per la ristorazione.',
                    cta_demo: 'Prenota Demo Gratuita', cta_login: 'Accedi al Sistema', cta_dash: 'Vai al Pannello',
                    trust_rest: 'Ristoranti attivi', trust_sri: 'Fatturazione cert.', trust_mod: 'Moduli integrati', trust_sup: 'Supporto disponibile',
                },
                stats: { s1: 'Moduli integrati', s2: 'Fallimenti per costi', s3: 'Ordini processati', s4: 'On-premise sicuro' },
                biz: {
                    tag: 'Il tuo tipo di attività',
                    title: 'Ogni attività ha la sua essenza',
                    sub: 'Qualunque sia la tua specialità, Solémia si adatta alla tua operazione.',
                    items: [
                        { name: 'Ristoranti', desc: 'Controlla costi, inventari e migliora l\'esperienza del commensale.' },
                        { name: 'Fast Food', desc: 'Velocizza gli ordini e assicura un servizio impeccabile.' },
                        { name: 'Bar & Discoteche', desc: 'Gestisci il flusso notturno con precisione e controllo assoluto.' },
                        { name: 'Catene / Franchising', desc: 'Centralizza l\'operazione di tutti i tuoi locali con facilità.' },
                        { name: 'Catering & Scuole', desc: 'Gestisci la ristorazione collettiva in aziende e istituti.' },
                        { name: 'Retail / Negozi', desc: 'Gestisci il tuo negozio con efficienza, dagli inventari alle vendite.' },
                    ],
                },
                rent: {
                    tag: 'Redditività',
                    title: 'Il 60% dei fallimenti è causato da un cattivo controllo dei costi',
                    sub: 'Ecco perché ti forniamo gli strumenti per rendere redditizio il tuo ristorante fin dal primo mese.',
                    pct: '60%',
                    pct_text: 'dei ristoranti fallisce per mancato controllo dei costi operativi.',
                    items: [
                        'Controllo dei costi e percentuale di costo in tempo reale',
                        'Modulo di pianificazione dei ricavi e punto di pareggio',
                        'Gestione semplificata degli inventari con alert di scorte',
                        'Punto vendita intuitivo con chiusura cassa rapida',
                        'Fatturazione elettronica compatibile con SRI Ecuador',
                        'Dashboard con KPI e report strategici',
                    ],
                },
                feat: {
                    tag: 'Moduli',
                    title: 'Tutto per il tuo ristorante',
                    sub: 'Un ecosistema completo dalla cucina al conto.',
                    items: [
                        { title: 'POS & Sala', desc: 'Comande in tempo reale, mappa dei tavoli e flusso ordinato dalla sala alla cucina.' },
                        { title: 'Cucina (KDS)', desc: 'Display in tempo reale. Ogni piatto, modifica e urgenza sotto controllo.' },
                        { title: 'Menu & Prodotti', desc: 'Catalogo digitale con categorie, combo e happy hour.' },
                        { title: 'Cassa & Pagamenti', desc: 'Pagamenti misti, divisione del conto, mancia e fatturazione SRI.' },
                        { title: 'Inventario', desc: 'Stock in tempo reale, ricette con costo automatico e alert di minimo.' },
                        { title: 'Report & KPI', desc: 'Dashboard con vendite per ora, ranking piatti e prestazioni.' },
                        { title: 'Utenti & Ruoli', desc: 'Permessi granulari, PIN POS, audit log e autenticazione multi-ruolo.' },
                        { title: 'WhatsApp Marketing', desc: 'Campagne, chatbot automatico e CRM con Meta Business.' },
                        { title: 'Notifiche', desc: 'Alert in-app, email e WhatsApp per eventi critici.' },
                    ],
                },
                demo: {
                    tag: 'Gratuito',
                    title: 'Pronto a trasformare il tuo ristorante?',
                    desc: 'Prenota una demo personalizzata e scopri come Solémia può ridurre i tuoi costi e aumentare la redditività dal primo giorno.',
                    cta: 'Prenota Demo Gratuita!', cta_in: 'Vai al Pannello',
                    note: 'Nessuna carta di credito · Nessun impegno · In 30 minuti',
                    perks: ['Migrazione gratuita', 'Supporto 24/7', 'Configurazione inclusa', 'Fatturazione SRI'],
                },
                testi: {
                    tag: 'Testimonianze',
                    title: 'Cosa dicono i nostri clienti',
                    items: [
                        { quote: 'Prima chiudevo il mese senza sapere se avevo guadagnato o perso. Con Solémia vedo i costi in tempo reale e finalmente decido con sicurezza.', name: 'Andrés V.', role: 'Titolare, El Rincón Criollo', initials: 'AV' },
                        { quote: 'Il modulo inventari ci ha cambiato la vita. Abbiamo eliminato gli sprechi in cucina e ridotto il costo del cibo del 12% nel primo trimestre.', name: 'Patricia N.', role: 'Amministratrice, Marisquería Don Pacífico', initials: 'PN' },
                        { quote: 'Abbiamo implementato Solémia in tre locali contemporaneamente e il processo è stato pulito e ordinato. Il supporto era con noi dal primo giorno.', name: 'Miguel Á.', role: 'Responsabile Operazioni, Grupo Sabores EC', initials: 'MA' },
                        { quote: 'Non avrei mai pensato che un POS potesse aiutarmi a capire il punto di pareggio. Ora so quanto devo vendere ogni giorno per non perdere.', name: 'Sofía B.', role: 'Titolare, Café Bambú', initials: 'SB' },
                        { quote: 'La fatturazione elettronica SRI è stata ciò che mi ha convinto. Zero errori, automatica e senza burocrazia. Vale oro per qualsiasi ristorante.', name: 'Ramón E.', role: 'Direttore Generale, Parrilla El Gaucho', initials: 'RE' },
                        { quote: 'Il KDS in cucina ha ridotto i tempi di consegna dei piatti del 30%. I camerieri non corrono più con foglietti di carta. Tutto scorre da solo.', name: 'Daniela C.', role: 'Chef & Socia, Fusión Andina', initials: 'DC' },
                    ],
                },
                mig: {
                    tag: 'Migrazione',
                    title: 'Hai già un altro sistema?\nCambia senza rischi',
                    sub: 'Ci occupiamo noi della migrazione dei tuoi dati, un processo senza stress.',
                    cta: 'Parla con un consulente', cta_in: 'Vai al Pannello',
                    perks: ['Migrazione gratuita', 'Processo sicuro', 'Nessuna perdita di dati', 'Migrazione rapida'],
                },
                footer: {
                    sub: 'Sistema POS per Ristoranti · Ecuador',
                    rights: 'Tutti i diritti riservati.',
                    col1: { title: 'Prodotto', links: ['POS & Sala', 'Cucina KDS', 'Inventario', 'Fatturazione SRI', 'Report'] },
                    col2: { title: 'Azienda', links: ['Contatti', 'Supporto', 'Blog', 'Prezzi'] },
                },
            },
            en: {
                nav: { modulos: 'Modules', negocios: 'Business Types', testimonios: 'Testimonials', accedi: 'Sign In', registrati: 'Register', demo: 'Book Demo', entrar: 'Dashboard' },
                hero: {
                    badge: 'Restaurant POS System — Ecuador',
                    subtitle: '"The heart of your restaurant"',
                    desc: 'Control costs, manage inventory and boost your <strong>profitability</strong> with the system built for modern restaurants.',
                    cta_demo: 'Book Free Demo', cta_login: 'Access System', cta_dash: 'Go to Dashboard',
                    trust_rest: 'Active restaurants', trust_sri: 'Certified billing', trust_mod: 'Integrated modules', trust_sup: 'Support available',
                },
                stats: { s1: 'Integrated modules', s2: 'Fail due to poor cost control', s3: 'Orders processed', s4: 'Secure on-premise' },
                biz: {
                    tag: 'Your business type',
                    title: 'Every business has its own essence',
                    sub: 'Whatever your specialty, Solémia adapts to your operation.',
                    items: [
                        { name: 'Restaurants', desc: 'Control costs, inventory and improve the dining experience.' },
                        { name: 'Fast Food', desc: 'Speed up orders and ensure flawless service in every transaction.' },
                        { name: 'Bars & Clubs', desc: 'Manage nighttime flow with precision and absolute control.' },
                        { name: 'Chains / Franchises', desc: 'Centralize operations across all your locations with ease.' },
                        { name: 'Catering & Schools', desc: 'Manage food sales in companies or educational institutions.' },
                        { name: 'Retail / Shops', desc: 'Run your store efficiently, from inventory to sales.' },
                    ],
                },
                rent: {
                    tag: 'Profitability',
                    title: 'Over 60% of restaurant failures come from poor cost control',
                    sub: 'That\'s why we give you the tools to make your restaurant profitable from day one.',
                    pct: '60%',
                    pct_text: 'of restaurants fail due to not controlling their operational costs.',
                    items: [
                        'Real-time cost control and food cost percentage',
                        'Revenue planning module and break-even point',
                        'Simplified inventory management with stock alerts',
                        'Intuitive POS with fast cash register closing',
                        'Electronic invoicing compatible with SRI Ecuador',
                        'Dashboard with KPIs and strategic reports',
                    ],
                },
                feat: {
                    tag: 'Modules',
                    title: 'Everything for your restaurant',
                    sub: 'A complete ecosystem from kitchen to checkout.',
                    items: [
                        { title: 'POS & Dining', desc: 'Real-time orders, interactive table map and seamless kitchen flow.' },
                        { title: 'Kitchen (KDS)', desc: 'Real-time kitchen display. Every dish, change and urgency under control.' },
                        { title: 'Menu & Products', desc: 'Digital catalog with categories, combos and happy hour pricing.' },
                        { title: 'Cashier & Payments', desc: 'Split payments, bill division, tips and SRI invoicing.' },
                        { title: 'Inventory', desc: 'Real-time stock, auto-costed recipes and low stock alerts.' },
                        { title: 'Reports & KPIs', desc: 'Dashboard with hourly sales, dish ranking and performance.' },
                        { title: 'Users & Roles', desc: 'Granular permissions, POS PIN, audit log and multi-role auth.' },
                        { title: 'WhatsApp Marketing', desc: 'Campaigns, automated chatbot and Meta Business CRM.' },
                        { title: 'Notifications', desc: 'In-app, email and WhatsApp alerts for critical events.' },
                    ],
                },
                demo: {
                    tag: 'No cost',
                    title: 'Ready to transform your restaurant?',
                    desc: 'Book a personalized demo and see how Solémia can cut your costs and increase profitability from day one.',
                    cta: 'Book Free Demo!', cta_in: 'Go to Dashboard',
                    note: 'No credit card · No commitment · 30 minutes',
                    perks: ['Free migration', '24/7 support', 'Setup included', 'SRI billing'],
                },
                testi: {
                    tag: 'Testimonials',
                    title: 'What our clients say',
                    items: [
                        { quote: 'I used to close the month not knowing if I made or lost money. With Solémia I see my costs in real time and finally make decisions with confidence.', name: 'Andrés V.', role: 'Owner, El Rincón Criollo', initials: 'AV' },
                        { quote: 'The inventory module changed everything. We eliminated kitchen waste and cut food costs by 12% in the very first quarter.', name: 'Patricia N.', role: 'Manager, Marisquería Don Pacífico', initials: 'PN' },
                        { quote: 'We rolled out Solémia across three locations at once and it was smooth. Support was with us from day one — they really know the restaurant world.', name: 'Miguel Á.', role: 'Operations Manager, Grupo Sabores EC', initials: 'MA' },
                        { quote: 'I never thought a POS could help me understand my break-even point. Now I know exactly how much I need to sell each day to stay profitable.', name: 'Sofía B.', role: 'Owner, Café Bambú', initials: 'SB' },
                        { quote: 'The SRI electronic invoicing alone was worth it. Zero errors, fully automatic, no paperwork. That is worth gold for any restaurant in Ecuador.', name: 'Ramón E.', role: 'General Manager, Parrilla El Gaucho', initials: 'RE' },
                        { quote: 'The kitchen KDS cut our dish delivery times by 30%. Waiters stopped running around with paper tickets — everything just flows on its own now.', name: 'Daniela C.', role: 'Chef & Partner, Fusión Andina', initials: 'DC' },
                    ],
                },
                mig: {
                    tag: 'Migration',
                    title: 'Already have another system?\nSwitch without risk',
                    sub: 'We handle your data migration — a completely stress-free process.',
                    cta: 'Talk to an advisor', cta_in: 'Go to Dashboard',
                    perks: ['Free migration', 'Secure process', 'No data loss', 'Fast migration'],
                },
                footer: {
                    sub: 'Restaurant POS System · Ecuador',
                    rights: 'All rights reserved.',
                    col1: { title: 'Product', links: ['POS & Dining', 'Kitchen KDS', 'Inventory', 'SRI Billing', 'Reports'] },
                    col2: { title: 'Company', links: ['Contact', 'Support', 'Blog', 'Pricing'] },
                },
            },
        };

        document.addEventListener('alpine:init', () => {
            const saved = localStorage.getItem('solemia_lang') || 'es';
            Alpine.store('lang', {
                code: saved,
                flag: { es: '🇪🇨', it: '🇮🇹', en: '🇺🇸' }[saved] || '🇪🇨',
                t: trans[saved],
            });
            Alpine.data('langSwitcher', () => ({
                langs: [
                    { code: 'es', name: 'Español', flag: '🇪🇨' },
                    { code: 'it', name: 'Italiano', flag: '🇮🇹' },
                    { code: 'en', name: 'English', flag: '🇺🇸' },
                ],
                featIcons: ['fa-utensils','fa-fire','fa-book-open','fa-cash-register','fa-boxes-stacked','fa-chart-line','fa-users-cog','fab fa-whatsapp','fa-bell'],
                bizIcons: ['fas fa-utensils','fas fa-burger','fas fa-martini-glass','fas fa-network-wired','fas fa-school','fas fa-shop'],
                get currentLang() { return this.$store.lang.code; },
                init() {},
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