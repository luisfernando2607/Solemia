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
            --o50:#f4f6f0; --o100:#e4e9d8; --o200:#c8d3b1; --o300:#a8b885;
            --o400:#869a5a; --o500:#677c3e; --o600:#506030; --o700:#3d4a24;
            --o800:#2c3419; --o900:#1c210f; --o950:#0e1108;
            --r100:#fdecea; --r200:#f9c4c4; --r400:#e03030; --r500:#c42020; --r600:#a01818;
            --cream:#fdfcf8; --cream2:#f5f3ee; --cream3:#ebe8e0;
            --g400:#d4a827;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; background: var(--cream); color: var(--o900); -webkit-font-smoothing: antialiased; }
        [x-cloak] { display: none !important; }

        /* ── TOPBAR ── */
        #topbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 200;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; height: 64px;
            background: rgba(253,252,248,0.94); backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(80,96,48,0.1);
            transition: box-shadow 0.3s;
        }
        #topbar.scrolled { box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .topbar-logo { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 700; color: var(--o800); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .logo-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--r500); display: inline-block; }
        .topbar-nav { display: flex; align-items: center; gap: 0.25rem; }
        .topbar-link { font-size: 0.85rem; font-weight: 400; color: var(--o700); padding: 0.4rem 0.85rem; border-radius: 1.5rem; text-decoration: none; transition: all 0.2s; }
        .topbar-link:hover { background: var(--o50); color: var(--o900); }
        .topbar-actions { display: flex; align-items: center; gap: 0.6rem; }
        .lang-wrap { position: relative; }
        .lang-trigger { display: flex; align-items: center; gap: 0.4rem; background: none; border: 1px solid var(--o200); padding: 0.35rem 0.7rem; border-radius: 1.5rem; font-size: 0.75rem; font-weight: 500; color: var(--o700); cursor: pointer; transition: all 0.2s; }
        .lang-trigger:hover { border-color: var(--o400); background: var(--o50); }
        .lang-menu { position: absolute; right: 0; top: calc(100% + 6px); background: #fff; border: 1px solid var(--o100); border-radius: 0.9rem; box-shadow: 0 12px 36px rgba(0,0,0,0.1); padding: 0.4rem; min-width: 8.5rem; z-index: 300; }
        .lang-item { display: flex; align-items: center; gap: 0.6rem; padding: 0.5rem 0.75rem; border-radius: 0.55rem; font-size: 0.82rem; cursor: pointer; transition: background 0.15s; color: var(--o700); background: none; border: none; width: 100%; }
        .lang-item:hover { background: var(--o50); }
        .lang-item.active { color: var(--o800); font-weight: 600; }
        .btn-login { font-size: 0.83rem; font-weight: 500; color: var(--o700); padding: 0.45rem 1rem; border-radius: 1.5rem; border: 1px solid var(--o200); background: none; text-decoration: none; transition: all 0.2s; }
        .btn-login:hover { border-color: var(--o500); color: var(--o900); background: var(--o50); }
        .btn-demo { font-size: 0.83rem; font-weight: 600; color: #fff; padding: 0.5rem 1.1rem; border-radius: 1.5rem; background: var(--r500); text-decoration: none; transition: all 0.25s; border: none; box-shadow: 0 2px 12px rgba(196,32,32,0.25); display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-demo:hover { background: var(--r400); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(196,32,32,0.3); }
        .btn-dash { font-size: 0.83rem; font-weight: 600; color: #fff; padding: 0.5rem 1.1rem; border-radius: 1.5rem; background: var(--o600); text-decoration: none; transition: all 0.25s; display: inline-flex; align-items: center; gap: 0.4rem; }
        .btn-dash:hover { background: var(--o500); transform: translateY(-1px); }

        /* ── HERO ── */
        .hero { min-height: 100dvh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 8rem 1.5rem 5rem; position: relative; overflow: hidden; background: var(--o950); }
        .hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--o500) 33.33%, #fff 33.33% 66.66%, var(--r500) 66.66%); }
        .hero-bg-grid { position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.03) 1px,transparent 1px), linear-gradient(90deg,rgba(255,255,255,0.03) 1px,transparent 1px); background-size: 56px 56px; }
        .hero-glow { position: absolute; top: -100px; left: 50%; transform: translateX(-50%); width: 700px; height: 500px; background: radial-gradient(ellipse, rgba(103,124,62,0.18) 0%, rgba(196,32,32,0.06) 50%, transparent 75%); pointer-events: none; }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); padding: 0.35rem 0.9rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 500; color: rgba(255,255,255,0.65); letter-spacing: 0.05em; margin-bottom: 1.5rem; opacity: 0; animation: upIn 0.7s ease 0.2s forwards; }
        .hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--r400); animation: blink 2s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.35} }
        .hero-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(4.5rem, 14vw, 10rem); font-weight: 800; line-height: 0.88; letter-spacing: -0.02em; margin-bottom: 1rem; opacity: 0; animation: upIn 0.85s ease 0.35s forwards; }
        .hero-title-main { color: #fff; display: block; }
        .hero-title-accent { color: var(--o300); display: block; font-style: italic; font-weight: 400; font-size: 0.5em; margin-top: 0.25em; letter-spacing: 0.02em; }
        .hero-divider { display: flex; align-items: center; gap: 0.75rem; justify-content: center; margin-bottom: 1.25rem; opacity: 0; animation: upIn 0.7s ease 0.5s forwards; }
        .hero-divider-line { width: 40px; height: 1px; background: rgba(255,255,255,0.2); }
        .hero-divider-icon { color: var(--r400); font-size: 0.8rem; }
        .hero-desc { font-size: clamp(1rem, 2vw, 1.15rem); color: rgba(255,255,255,0.5); max-width: 38rem; line-height: 1.75; font-weight: 300; margin-bottom: 2.5rem; opacity: 0; animation: upIn 0.8s ease 0.6s forwards; }
        .hero-desc strong { color: rgba(255,255,255,0.8); font-weight: 500; }
        .hero-ctas { display: flex; flex-wrap: wrap; justify-content: center; gap: 0.85rem; margin-bottom: 3.5rem; opacity: 0; animation: upIn 0.8s ease 0.75s forwards; }
        .hero-btn-red { display: inline-flex; align-items: center; gap: 0.6rem; background: var(--r500); color: #fff; padding: 0.9rem 2rem; border-radius: 2rem; font-size: 0.92rem; font-weight: 600; text-decoration: none; box-shadow: 0 4px 24px rgba(196,32,32,0.35); transition: all 0.3s ease; }
        .hero-btn-red:hover { background: var(--r400); transform: translateY(-2px); box-shadow: 0 10px 32px rgba(196,32,32,0.4); }
        .hero-btn-outline { display: inline-flex; align-items: center; gap: 0.6rem; background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.75); border: 1px solid rgba(255,255,255,0.15); padding: 0.9rem 2rem; border-radius: 2rem; font-size: 0.92rem; font-weight: 400; text-decoration: none; transition: all 0.3s ease; }
        .hero-btn-outline:hover { background: rgba(255,255,255,0.13); border-color: rgba(255,255,255,0.3); transform: translateY(-2px); }
        .hero-trust { opacity: 0; animation: upIn 0.7s ease 0.9s forwards; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 0; }
        .trust-stat { text-align: center; padding: 0 1.5rem; }
        .trust-stat + .trust-stat { border-left: 1px solid rgba(255,255,255,0.1); }
        .trust-stat-num { font-family: 'Cormorant Garamond', serif; font-size: 1.8rem; font-weight: 700; color: #fff; line-height: 1; }
        .trust-stat-lbl { font-size: 0.7rem; color: rgba(255,255,255,0.35); letter-spacing: 0.08em; text-transform: uppercase; margin-top: 0.15rem; }
        .hero-scroll { position: absolute; bottom: 1.75rem; left: 50%; transform: translateX(-50%); color: rgba(255,255,255,0.2); font-size: 0.85rem; display: flex; flex-direction: column; align-items: center; gap: 0.4rem; animation: bounce 2.5s ease-in-out infinite; }

        /* ── TRICOLORE DIVIDER ── */
        .tricolore { display: flex; height: 5px; }
        .tricolore > div:nth-child(1) { flex: 1; background: var(--o500); }
        .tricolore > div:nth-child(2) { flex: 1; background: #fff; border-top: 1px solid var(--cream3); border-bottom: 1px solid var(--cream3); }
        .tricolore > div:nth-child(3) { flex: 1; background: var(--r500); }

        /* ── STATS BAND ── */
        .stats-band { background: var(--cream2); padding: 2.25rem 1.5rem; border-bottom: 1px solid var(--cream3); }
        .stats-inner { max-width: 72rem; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px,1fr)); }
        .stat-cell { padding: 0.5rem 1.5rem; text-align: center; }
        .stat-cell + .stat-cell { border-left: 1px solid var(--cream3); }
        .stat-n { font-family: 'Cormorant Garamond', serif; font-size: 2.5rem; font-weight: 700; color: var(--o800); line-height: 1; }
        .stat-n.red { color: var(--r500); }
        .stat-l { font-size: 0.72rem; font-weight: 500; letter-spacing: 0.1em; text-transform: uppercase; color: var(--o500); margin-top: 0.3rem; }

        /* ── SECTION COMMONS ── */
        .section { padding: 5.5rem 1.5rem; }
        .section-inner { max-width: 72rem; margin: 0 auto; }
        .tag { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.18em; text-transform: uppercase; color: var(--o600); background: var(--o50); border: 1px solid var(--o200); padding: 0.3rem 0.75rem; border-radius: 2rem; margin-bottom: 1rem; }
        .tag-red { color: var(--r500); background: var(--r100); border-color: var(--r200); }
        .sec-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 4.5vw, 3.25rem); font-weight: 700; line-height: 1.1; color: var(--o900); }
        .sec-title.w { color: #fff; }
        .sec-sub { font-size: 1rem; color: var(--o500); line-height: 1.7; font-weight: 300; margin-top: 0.75rem; }
        .sec-sub.w { color: rgba(255,255,255,0.4); }

        /* ── BIZ TYPES ── */
        .biz-section { background: var(--cream); }
        .biz-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap: 1px; background: var(--cream3); border: 1px solid var(--cream3); border-radius: 1.25rem; overflow: hidden; margin-top: 3rem; }
        .biz-card { background: var(--cream); padding: 2rem 1.75rem; transition: background 0.2s; }
        .biz-card:hover { background: #fff; }
        .biz-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.7rem; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; margin-bottom: 1rem; background: var(--o100); color: var(--o600); transition: all 0.25s; }
        .biz-card:hover .biz-icon { background: var(--o700); color: #fff; }
        .biz-name { font-size: 0.95rem; font-weight: 600; color: var(--o800); margin-bottom: 0.45rem; }
        .biz-desc { font-size: 0.85rem; color: var(--o500); line-height: 1.6; }

        /* ── RENTABILIDAD ── */
        .rent-section { background: var(--o950); }
        .rent-header { text-align: center; max-width: 42rem; margin: 0 auto 3.5rem; }
        .rent-stat-big { text-align: center; margin-bottom: 2.5rem; padding: 2rem; background: rgba(196,32,32,0.08); border: 1px solid rgba(196,32,32,0.2); border-radius: 1.25rem; }
        .rent-stat-pct { font-family: 'Cormorant Garamond', serif; font-size: 5rem; font-weight: 800; line-height: 1; color: var(--r400); display: block; }
        .rent-stat-text { font-size: 1rem; color: rgba(255,255,255,0.6); margin-top: 0.5rem; line-height: 1.5; }
        .rent-list { list-style: none; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px,1fr)); gap: 0.75rem; }
        .rent-item { display: flex; align-items: flex-start; gap: 0.75rem; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07); border-radius: 0.85rem; padding: 1rem 1.25rem; }
        .rent-item-icon { color: var(--o400); font-size: 0.9rem; margin-top: 0.1rem; flex-shrink: 0; }
        .rent-item-text { font-size: 0.88rem; color: rgba(255,255,255,0.65); line-height: 1.5; }

        /* ── FEATURES ── */
        .features-section { background: var(--cream2); }
        .features-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 1.5rem; margin-bottom: 3rem; }
        .feat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(270px,1fr)); gap: 1px; background: var(--cream3); border: 1px solid var(--cream3); border-radius: 1.25rem; overflow: hidden; }
        .feat-card { background: var(--cream2); padding: 1.75rem; transition: background 0.2s; }
        .feat-card:hover { background: #fff; }
        .feat-icon-box { width: 2.5rem; height: 2.5rem; border-radius: 0.6rem; background: var(--o100); display: flex; align-items: center; justify-content: center; font-size: 1rem; color: var(--o600); margin-bottom: 1rem; transition: all 0.25s; }
        .feat-card:hover .feat-icon-box { background: var(--o700); color: #fff; }
        .feat-title { font-size: 0.95rem; font-weight: 600; color: var(--o800); margin-bottom: 0.5rem; }
        .feat-desc { font-size: 0.83rem; color: var(--o500); line-height: 1.6; }

        /* ── DEMO CTA ── */
        .demo-section { background: linear-gradient(135deg, var(--o800) 0%, var(--o950) 60%, #1a0808 100%); padding: 5.5rem 1.5rem; text-align: center; position: relative; overflow: hidden; }
        .demo-section::before { content: ''; position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 24px 24px; }
        .demo-section::after { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--o400) 33.33%, #fff 33.33% 66.66%, var(--r500) 66.66%); }
        .demo-inner { position: relative; z-index: 1; max-width: 44rem; margin: 0 auto; }
        .demo-title { font-family: 'Cormorant Garamond', serif; font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 700; color: #fff; line-height: 1.1; margin-bottom: 1rem; }
        .demo-desc { font-size: 1rem; color: rgba(255,255,255,0.45); line-height: 1.75; margin-bottom: 2.25rem; font-weight: 300; }
        .demo-btn { display: inline-flex; align-items: center; gap: 0.7rem; background: var(--r500); color: #fff; padding: 1rem 2.5rem; border-radius: 2rem; font-size: 1.05rem; font-weight: 700; text-decoration: none; box-shadow: 0 4px 32px rgba(196,32,32,0.4); transition: all 0.3s ease; }
        .demo-btn:hover { background: var(--r400); transform: translateY(-2px); box-shadow: 0 12px 40px rgba(196,32,32,0.5); }
        .demo-note { font-size: 0.78rem; color: rgba(255,255,255,0.3); margin-top: 1rem; }
        .demo-perks { display: flex; flex-wrap: wrap; justify-content: center; gap: 1.25rem; margin-top: 2.5rem; }
        .demo-perk { display: flex; align-items: center; gap: 0.45rem; font-size: 0.82rem; color: rgba(255,255,255,0.5); }
        .demo-perk i { color: var(--o400); }

        /* ── TESTIMONIALS ── */
        .testi-section { background: var(--cream); }
        .testi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px,1fr)); gap: 1.25rem; margin-top: 3rem; }
        .testi-card { background: #fff; border: 1px solid var(--cream3); border-radius: 1.1rem; padding: 1.5rem; transition: box-shadow 0.2s; }
        .testi-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.07); }
        .testi-stars { display: flex; gap: 3px; margin-bottom: 0.9rem; }
        .testi-stars i { color: var(--g400); font-size: 0.8rem; }
        .testi-quote { font-size: 0.88rem; color: var(--o700); line-height: 1.7; margin-bottom: 1.25rem; font-style: italic; }
        .testi-quote::before { content: '"'; color: var(--r400); font-size: 1.5rem; font-family: 'Cormorant Garamond', serif; line-height: 0; vertical-align: -0.3rem; margin-right: 0.1rem; }
        .testi-author { display: flex; align-items: center; gap: 0.75rem; }
        .testi-avatar { width: 2.25rem; height: 2.25rem; border-radius: 50%; background: var(--o100); color: var(--o600); display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 700; flex-shrink: 0; }
        .testi-name { font-size: 0.85rem; font-weight: 600; color: var(--o800); }
        .testi-role { font-size: 0.75rem; color: var(--o400); }

        /* ── MIGRATION ── */
        .migration-section { background: var(--cream2); }
        .migration-card { max-width: 52rem; margin: 3rem auto 0; background: #fff; border: 1px solid var(--cream3); border-radius: 1.5rem; padding: 2.5rem; display: flex; flex-wrap: wrap; align-items: center; gap: 2rem; }
        .migration-left { flex: 1; min-width: 200px; }
        .migration-title { font-family: 'Cormorant Garamond', serif; font-size: 1.75rem; font-weight: 700; color: var(--o900); margin-bottom: 0.75rem; line-height: 1.2; }
        .migration-sub { font-size: 0.9rem; color: var(--o500); line-height: 1.6; }
        .migration-right { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
        .mig-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: var(--o700); }
        .mig-item i { color: var(--o500); }
        .migration-cta { margin-top: 1.5rem; }

        /* ── FOOTER ── */
        .footer { background: var(--o950); border-top: 1px solid rgba(103,124,62,0.15); padding: 3rem 1.5rem; }
        .footer-inner { max-width: 72rem; margin: 0 auto; }
        .footer-top { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 2rem; margin-bottom: 2.5rem; }
        .foot-logo { font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 0.5rem; }
        .foot-sub { font-size: 0.78rem; color: rgba(255,255,255,0.3); margin-top: 0.3rem; }
        .footer-links { display: flex; flex-wrap: wrap; gap: 3rem; }
        .footer-col-title { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 0.85rem; }
        .footer-col-link { display: block; font-size: 0.82rem; color: rgba(255,255,255,0.45); text-decoration: none; margin-bottom: 0.5rem; transition: color 0.15s; }
        .footer-col-link:hover { color: rgba(255,255,255,0.8); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1.5rem; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1rem; }
        .footer-copy { font-size: 0.75rem; color: rgba(255,255,255,0.22); }
        .footer-socials { display: flex; gap: 0.75rem; }
        .soc-btn { width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.4); font-size: 0.8rem; text-decoration: none; transition: all 0.2s; }
        .soc-btn:hover { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.8); }

        /* ── FLOAT DEMO ── */
        .float-demo { position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 999; display: flex; align-items: center; gap: 0.55rem; background: var(--r500); color: #fff; padding: 0.75rem 1.35rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 700; text-decoration: none; box-shadow: 0 4px 24px rgba(196,32,32,0.45); transition: all 0.3s; opacity: 0; animation: upIn 0.8s ease 1.2s forwards; }
        .float-demo:hover { background: var(--r400); transform: translateY(-2px) scale(1.03); box-shadow: 0 8px 32px rgba(196,32,32,0.5); }
        .float-demo .fd-pulse { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.7); animation: blink 1.8s infinite; flex-shrink: 0; }

        /* ── ANIMATIONS ── */
        @keyframes upIn { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bounce { 0%,100% { transform: translateX(-50%) translateY(0); } 50% { transform: translateX(-50%) translateY(7px); } }
        .reveal { opacity: 0; transform: translateY(18px); transition: opacity 0.55s ease, transform 0.55s ease; }
        .reveal.in { opacity: 1; transform: translateY(0); }
        .rd1{transition-delay:0.08s} .rd2{transition-delay:0.16s} .rd3{transition-delay:0.24s}
        .rd4{transition-delay:0.32s} .rd5{transition-delay:0.4s}  .rd6{transition-delay:0.48s}

        @media (max-width: 768px) {
            #topbar .topbar-nav { display: none; }
            .migration-right { grid-template-columns: 1fr; }
            .footer-links { gap: 1.5rem; }
        }
    </style>
</head>
<body x-data="langSwitcher()" x-init="init()">

    {{-- ── TOPBAR ── --}}
    @if(Route::has('login'))
    <nav id="topbar" x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 40" :class="{ scrolled }">
        <a href="#" class="topbar-logo">Solémia <span class="logo-dot"></span></a>
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
                <a href="{{ route('dashboard') }}" class="btn-dash"><i class="fas fa-tachometer-alt"></i> <span x-text="$store.lang.t.nav.entrar"></span></a>
            @else
                <a href="{{ route('login') }}" class="btn-login" x-text="$store.lang.t.nav.accedi"></a>
                @if(Route::has('register'))
                <a href="{{ route('register') }}" class="btn-demo"><i class="fas fa-calendar-check"></i> <span x-text="$store.lang.t.nav.demo"></span></a>
                @endif
            @endauth
        </div>
    </nav>
    @endif

    {{-- ── HERO ── --}}
    <section class="hero">
        <div class="hero-bg-grid"></div>
        <div class="hero-glow"></div>
        <div class="hero-badge"><span class="hero-badge-dot"></span> <span x-text="$store.lang.t.hero.badge"></span></div>
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
            <a href="{{ route('register') }}" class="hero-btn-red"><i class="fas fa-calendar-check"></i> <span x-text="$store.lang.t.hero.cta_demo"></span></a>
            <a href="{{ route('login') }}" class="hero-btn-outline"><i class="fas fa-sign-in-alt"></i> <span x-text="$store.lang.t.hero.cta_login"></span></a>
            @else
            <a href="{{ route('dashboard') }}" class="hero-btn-red"><i class="fas fa-arrow-right"></i> <span x-text="$store.lang.t.hero.cta_dash"></span></a>
            @endguest
        </div>
        <div class="hero-trust">
            <div class="trust-stat"><div class="trust-stat-num">+500</div><div class="trust-stat-lbl" x-text="$store.lang.t.hero.t1"></div></div>
            <div class="trust-stat"><div class="trust-stat-num">SRI</div><div class="trust-stat-lbl" x-text="$store.lang.t.hero.t2"></div></div>
            <div class="trust-stat"><div class="trust-stat-num">9</div><div class="trust-stat-lbl" x-text="$store.lang.t.hero.t3"></div></div>
            <div class="trust-stat"><div class="trust-stat-num">24/7</div><div class="trust-stat-lbl" x-text="$store.lang.t.hero.t4"></div></div>
        </div>
        <div class="hero-scroll"><i class="fas fa-chevron-down"></i></div>
    </section>

    <div class="tricolore"><div></div><div></div><div></div></div>

    {{-- ── STATS BAND ── --}}
    <div class="stats-band">
        <div class="stats-inner">
            <div class="stat-cell reveal"><div class="stat-n">9</div><div class="stat-l" x-text="$store.lang.t.stats.s1"></div></div>
            <div class="stat-cell reveal rd1"><div class="stat-n red">60%</div><div class="stat-l" x-text="$store.lang.t.stats.s2"></div></div>
            <div class="stat-cell reveal rd2"><div class="stat-n">∞</div><div class="stat-l" x-text="$store.lang.t.stats.s3"></div></div>
            <div class="stat-cell reveal rd3"><div class="stat-n">100%</div><div class="stat-l" x-text="$store.lang.t.stats.s4"></div></div>
        </div>
    </div>

    {{-- ── TIPOS DE NEGOCIO ── --}}
    <section id="tipos" class="section biz-section">
        <div class="section-inner">
            <div class="reveal">
                <span class="tag"><i class="fas fa-store"></i> <span x-text="$store.lang.t.biz.tag"></span></span>
                <h2 class="sec-title" x-text="$store.lang.t.biz.title"></h2>
                <p class="sec-sub" x-text="$store.lang.t.biz.sub"></p>
            </div>
            {{-- Hardcoded cards — sin x-for para evitar bugs de observer --}}
            <div class="biz-grid reveal rd1">
                <div class="biz-card"><div class="biz-icon"><i class="fas fa-utensils"></i></div><div class="biz-name" x-text="$store.lang.t.biz.items[0].name"></div><div class="biz-desc" x-text="$store.lang.t.biz.items[0].desc"></div></div>
                <div class="biz-card"><div class="biz-icon"><i class="fas fa-burger"></i></div><div class="biz-name" x-text="$store.lang.t.biz.items[1].name"></div><div class="biz-desc" x-text="$store.lang.t.biz.items[1].desc"></div></div>
                <div class="biz-card"><div class="biz-icon"><i class="fas fa-martini-glass"></i></div><div class="biz-name" x-text="$store.lang.t.biz.items[2].name"></div><div class="biz-desc" x-text="$store.lang.t.biz.items[2].desc"></div></div>
                <div class="biz-card"><div class="biz-icon"><i class="fas fa-network-wired"></i></div><div class="biz-name" x-text="$store.lang.t.biz.items[3].name"></div><div class="biz-desc" x-text="$store.lang.t.biz.items[3].desc"></div></div>
                <div class="biz-card"><div class="biz-icon"><i class="fas fa-school"></i></div><div class="biz-name" x-text="$store.lang.t.biz.items[4].name"></div><div class="biz-desc" x-text="$store.lang.t.biz.items[4].desc"></div></div>
                <div class="biz-card"><div class="biz-icon"><i class="fas fa-shop"></i></div><div class="biz-name" x-text="$store.lang.t.biz.items[5].name"></div><div class="biz-desc" x-text="$store.lang.t.biz.items[5].desc"></div></div>
            </div>
        </div>
    </section>

    <div class="tricolore"><div></div><div></div><div></div></div>

    {{-- ── RENTABILIDAD ── --}}
    <section class="section rent-section">
        <div class="section-inner">
            <div class="rent-header reveal">
                <span class="tag tag-red"><i class="fas fa-chart-line"></i> <span x-text="$store.lang.t.rent.tag"></span></span>
                <h2 class="sec-title w" x-text="$store.lang.t.rent.title"></h2>
                <p class="sec-sub w" x-text="$store.lang.t.rent.sub"></p>
            </div>
            <div class="rent-stat-big reveal rd1">
                <span class="rent-stat-pct" x-text="$store.lang.t.rent.pct"></span>
                <p class="rent-stat-text" x-text="$store.lang.t.rent.pct_text"></p>
            </div>
            <ul class="rent-list">
                <li class="rent-item reveal rd1"><i class="fas fa-check rent-item-icon"></i><span class="rent-item-text" x-text="$store.lang.t.rent.items[0]"></span></li>
                <li class="rent-item reveal rd2"><i class="fas fa-check rent-item-icon"></i><span class="rent-item-text" x-text="$store.lang.t.rent.items[1]"></span></li>
                <li class="rent-item reveal rd3"><i class="fas fa-check rent-item-icon"></i><span class="rent-item-text" x-text="$store.lang.t.rent.items[2]"></span></li>
                <li class="rent-item reveal rd4"><i class="fas fa-check rent-item-icon"></i><span class="rent-item-text" x-text="$store.lang.t.rent.items[3]"></span></li>
                <li class="rent-item reveal rd5"><i class="fas fa-check rent-item-icon"></i><span class="rent-item-text" x-text="$store.lang.t.rent.items[4]"></span></li>
                <li class="rent-item reveal rd6"><i class="fas fa-check rent-item-icon"></i><span class="rent-item-text" x-text="$store.lang.t.rent.items[5]"></span></li>
            </ul>
        </div>
    </section>

    {{-- ── FEATURES ── --}}
    <section id="features" class="section features-section">
        <div class="section-inner">
            <div class="features-header reveal">
                <div>
                    <span class="tag"><i class="fas fa-cubes"></i> <span x-text="$store.lang.t.feat.tag"></span></span>
                    <h2 class="sec-title" x-text="$store.lang.t.feat.title"></h2>
                </div>
                <p class="sec-sub" style="max-width:26rem;text-align:right;" x-text="$store.lang.t.feat.sub"></p>
            </div>
            {{-- Hardcoded cards con iconos inline --}}
            <div class="feat-grid">
                <div class="feat-card reveal rd1"><div class="feat-icon-box"><i class="fas fa-utensils"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[0].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[0].desc"></div></div>
                <div class="feat-card reveal rd2"><div class="feat-icon-box"><i class="fas fa-fire"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[1].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[1].desc"></div></div>
                <div class="feat-card reveal rd3"><div class="feat-icon-box"><i class="fas fa-book-open"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[2].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[2].desc"></div></div>
                <div class="feat-card reveal rd4"><div class="feat-icon-box"><i class="fas fa-cash-register"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[3].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[3].desc"></div></div>
                <div class="feat-card reveal rd5"><div class="feat-icon-box"><i class="fas fa-boxes-stacked"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[4].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[4].desc"></div></div>
                <div class="feat-card reveal rd6"><div class="feat-icon-box"><i class="fas fa-chart-line"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[5].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[5].desc"></div></div>
                <div class="feat-card reveal rd1"><div class="feat-icon-box"><i class="fas fa-users-gear"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[6].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[6].desc"></div></div>
                <div class="feat-card reveal rd2"><div class="feat-icon-box"><i class="fab fa-whatsapp"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[7].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[7].desc"></div></div>
                <div class="feat-card reveal rd3"><div class="feat-icon-box"><i class="fas fa-bell"></i></div><div class="feat-title" x-text="$store.lang.t.feat.items[8].title"></div><div class="feat-desc" x-text="$store.lang.t.feat.items[8].desc"></div></div>
            </div>
        </div>
    </section>

    {{-- ── DEMO CTA ── --}}
    <section class="demo-section">
        <div class="demo-inner reveal">
            <span class="tag tag-red" style="margin-bottom:1.25rem;"><i class="fas fa-rocket"></i> <span x-text="$store.lang.t.demo.tag"></span></span>
            <h2 class="demo-title" x-text="$store.lang.t.demo.title"></h2>
            <p class="demo-desc" x-text="$store.lang.t.demo.desc"></p>
            @guest
            <a href="{{ route('register') }}" class="demo-btn"><i class="fas fa-calendar-check"></i> <span x-text="$store.lang.t.demo.cta"></span></a>
            @else
            <a href="{{ route('dashboard') }}" class="demo-btn"><i class="fas fa-arrow-right"></i> <span x-text="$store.lang.t.demo.cta_in"></span></a>
            @endguest
            <p class="demo-note" x-text="$store.lang.t.demo.note"></p>
            <div class="demo-perks">
                <span class="demo-perk"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.demo.p1"></span></span>
                <span class="demo-perk"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.demo.p2"></span></span>
                <span class="demo-perk"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.demo.p3"></span></span>
                <span class="demo-perk"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.demo.p4"></span></span>
            </div>
        </div>
    </section>

    {{-- ── TESTIMONIALES ── --}}
    <section id="testimonios" class="section testi-section">
        <div class="section-inner">
            <div class="reveal" style="text-align:center;max-width:36rem;margin:0 auto 3rem;">
                <span class="tag"><i class="fas fa-star"></i> <span x-text="$store.lang.t.testi.tag"></span></span>
                <h2 class="sec-title" x-text="$store.lang.t.testi.title"></h2>
            </div>
            <div class="testi-grid">
                <div class="testi-card reveal rd1">
                    <div class="testi-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testi-quote" x-text="$store.lang.t.testi.items[0].quote"></p>
                    <div class="testi-author"><div class="testi-avatar" x-text="$store.lang.t.testi.items[0].initials"></div><div><div class="testi-name" x-text="$store.lang.t.testi.items[0].name"></div><div class="testi-role" x-text="$store.lang.t.testi.items[0].role"></div></div></div>
                </div>
                <div class="testi-card reveal rd2">
                    <div class="testi-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testi-quote" x-text="$store.lang.t.testi.items[1].quote"></p>
                    <div class="testi-author"><div class="testi-avatar" x-text="$store.lang.t.testi.items[1].initials"></div><div><div class="testi-name" x-text="$store.lang.t.testi.items[1].name"></div><div class="testi-role" x-text="$store.lang.t.testi.items[1].role"></div></div></div>
                </div>
                <div class="testi-card reveal rd3">
                    <div class="testi-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testi-quote" x-text="$store.lang.t.testi.items[2].quote"></p>
                    <div class="testi-author"><div class="testi-avatar" x-text="$store.lang.t.testi.items[2].initials"></div><div><div class="testi-name" x-text="$store.lang.t.testi.items[2].name"></div><div class="testi-role" x-text="$store.lang.t.testi.items[2].role"></div></div></div>
                </div>
                <div class="testi-card reveal rd4">
                    <div class="testi-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testi-quote" x-text="$store.lang.t.testi.items[3].quote"></p>
                    <div class="testi-author"><div class="testi-avatar" x-text="$store.lang.t.testi.items[3].initials"></div><div><div class="testi-name" x-text="$store.lang.t.testi.items[3].name"></div><div class="testi-role" x-text="$store.lang.t.testi.items[3].role"></div></div></div>
                </div>
                <div class="testi-card reveal rd5">
                    <div class="testi-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testi-quote" x-text="$store.lang.t.testi.items[4].quote"></p>
                    <div class="testi-author"><div class="testi-avatar" x-text="$store.lang.t.testi.items[4].initials"></div><div><div class="testi-name" x-text="$store.lang.t.testi.items[4].name"></div><div class="testi-role" x-text="$store.lang.t.testi.items[4].role"></div></div></div>
                </div>
                <div class="testi-card reveal rd6">
                    <div class="testi-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="testi-quote" x-text="$store.lang.t.testi.items[5].quote"></p>
                    <div class="testi-author"><div class="testi-avatar" x-text="$store.lang.t.testi.items[5].initials"></div><div><div class="testi-name" x-text="$store.lang.t.testi.items[5].name"></div><div class="testi-role" x-text="$store.lang.t.testi.items[5].role"></div></div></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── MIGRACIÓN ── --}}
    <section class="section migration-section">
        <div class="section-inner">
            <div class="migration-card reveal">
                <div class="migration-left">
                    <span class="tag"><i class="fas fa-right-left"></i> <span x-text="$store.lang.t.mig.tag"></span></span>
                    <h3 class="migration-title" x-text="$store.lang.t.mig.title"></h3>
                    <p class="migration-sub" x-text="$store.lang.t.mig.sub"></p>
                    <div class="migration-cta">
                        @guest
                        <a href="{{ route('register') }}" class="btn-demo"><i class="fas fa-calendar-check"></i> <span x-text="$store.lang.t.mig.cta"></span></a>
                        @else
                        <a href="{{ route('dashboard') }}" class="btn-dash"><i class="fas fa-arrow-right"></i> <span x-text="$store.lang.t.mig.cta_in"></span></a>
                        @endguest
                    </div>
                </div>
                <div class="migration-right">
                    <div class="mig-item"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.mig.p1"></span></div>
                    <div class="mig-item"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.mig.p2"></span></div>
                    <div class="mig-item"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.mig.p3"></span></div>
                    <div class="mig-item"><i class="fas fa-check-circle"></i> <span x-text="$store.lang.t.mig.p4"></span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── FOOTER ── --}}
    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-top">
                <div>
                    <div class="foot-logo">Solémia <span class="logo-dot"></span></div>
                    <div class="foot-sub" x-text="$store.lang.t.footer.sub"></div>
                </div>
                <div class="footer-links">
                    <div>
                        <div class="footer-col-title" x-text="$store.lang.t.footer.c1t"></div>
                        <a href="#features" class="footer-col-link" x-text="$store.lang.t.footer.c1l[0]"></a>
                        <a href="#features" class="footer-col-link" x-text="$store.lang.t.footer.c1l[1]"></a>
                        <a href="#features" class="footer-col-link" x-text="$store.lang.t.footer.c1l[2]"></a>
                        <a href="#features" class="footer-col-link" x-text="$store.lang.t.footer.c1l[3]"></a>
                        <a href="#features" class="footer-col-link" x-text="$store.lang.t.footer.c1l[4]"></a>
                    </div>
                    <div>
                        <div class="footer-col-title" x-text="$store.lang.t.footer.c2t"></div>
                        <a href="#" class="footer-col-link" x-text="$store.lang.t.footer.c2l[0]"></a>
                        <a href="#" class="footer-col-link" x-text="$store.lang.t.footer.c2l[1]"></a>
                        <a href="#" class="footer-col-link" x-text="$store.lang.t.footer.c2l[2]"></a>
                        <a href="#" class="footer-col-link" x-text="$store.lang.t.footer.c2l[3]"></a>
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

    @guest
    <a href="{{ route('register') }}" class="float-demo">
        <span class="fd-pulse"></span>
        <span x-text="$store.lang.t.nav.demo"></span>
    </a>
    @endguest

    @livewireScripts

    <script>
        // ── TRADUCCIONES ─────────────────────────────────────────
        const trans = {
            es: {
                nav: { modulos:'Módulos', negocios:'Negocios', testimonios:'Testimonios', accedi:'Acceder', demo:'Agendar Demo', entrar:'Panel' },
                hero: { badge:'Sistema POS para Restaurantes — Ecuador', subtitle:'"El corazón de tu restaurante"', desc:'Controla costos, gestiona inventarios y aumenta tu <strong>rentabilidad</strong> con el sistema diseñado para restaurantes ecuatorianos.', cta_demo:'Agendar Demo Gratis', cta_login:'Acceder al Sistema', cta_dash:'Ir al Panel', t1:'Restaurantes activos', t2:'Facturación cert.', t3:'Módulos integrados', t4:'Soporte disponible' },
                stats: { s1:'Módulos integrados', s2:'Quiebras por mal control de costo', s3:'Órdenes sin límite', s4:'On-premise seguro' },
                biz: { tag:'Tu tipo de negocio', title:'Cada negocio tiene su esencia', sub:'Sea cual sea tu especialidad, Solémia se adapta a tu operación.', items:[
                    {name:'Restaurantes',desc:'Controla costos, inventarios y mejora la experiencia del comensal.'},
                    {name:'Fast Food',desc:'Agiliza pedidos y asegura un servicio impecable en cada transacción.'},
                    {name:'Bares & Discotecas',desc:'Gestiona el flujo nocturno con precisión y control absoluto.'},
                    {name:'Cadenas / Franquicias',desc:'Centraliza la operación de todos tus locales con facilidad.'},
                    {name:'Catering & Colegios',desc:'Administra ventas de alimentos en empresas o instituciones educativas.'},
                    {name:'Retail / Shops',desc:'Gestiona tu tienda con eficiencia, desde inventarios hasta ventas.'},
                ]},
                rent: { tag:'Rentabilidad', title:'Más del 60% de quiebras se dan por mal control de costo', sub:'Por eso te damos las herramientas para que tu restaurante sea rentable desde el primer mes.', pct:'60%', pct_text:'de restaurantes quiebran por no controlar sus costos operativos.', items:['Control de costos y porcentaje de costo en tiempo real','Módulo de planificación de ingresos y punto de equilibrio','Gestión de inventarios simplificada con alertas de stock','Punto de Venta intuitivo con cierre de caja rápido','Facturación electrónica compatible con SRI Ecuador','Dashboard con KPIs y reportes estratégicos'] },
                feat: { tag:'Módulos', title:'Todo para tu restaurante', sub:'Un ecosistema completo desde la cocina hasta la cuenta.', items:[
                    {title:'POS & Sala',desc:'Comandas en tiempo real, mapa de mesas y flujo ordenado de sala a cocina.'},
                    {title:'Cocina (KDS)',desc:'Display en tiempo real. Cada plato, modificación y urgencia bajo control.'},
                    {title:'Menú & Productos',desc:'Catálogo digital con categorías, combos y precios de happy hour.'},
                    {title:'Caja & Pagos',desc:'Pagos mixtos, división de cuenta, propina y facturación SRI.'},
                    {title:'Inventario',desc:'Stock en tiempo real, recetas con costo automático y alertas de mínimo.'},
                    {title:'Reportes & KPIs',desc:'Dashboard con ventas por hora, ranking de platos y rendimiento de meseros.'},
                    {title:'Usuarios & Roles',desc:'Permisos granulares, PIN POS, audit log y autenticación multi-rol.'},
                    {title:'WhatsApp Marketing',desc:'Campañas, chatbot automático y CRM integrado con Meta Business.'},
                    {title:'Notificaciones',desc:'Alertas in-app, email y WhatsApp para eventos críticos del negocio.'},
                ]},
                demo: { tag:'Sin costo', title:'¿Listo para transformar tu restaurante?', desc:'Agenda una demo personalizada y descubre cómo Solémia puede reducir tus costos y aumentar tu rentabilidad desde el primer día.', cta:'¡Agendar Demo Gratis!', cta_in:'Ir al Panel', note:'Sin tarjeta de crédito · Sin compromiso · En 30 minutos', p1:'Migración sin costo', p2:'Soporte 24/7', p3:'Configuración incluida', p4:'Facturación SRI' },
                testi: { tag:'Testimonios', title:'Lo que dicen nuestros clientes', items:[
                    {quote:'Ahora podemos optimizar inventarios y controlar costos de manera efectiva, lo que ha resultado en un gran ahorro operativo.',name:'Carlos M.',role:'CEO, La Bella Tavola',initials:'CM'},
                    {quote:'Con Solémia dejamos de perder dinero por errores en costos. Sabemos exactamente cuánto debemos vender para ser rentables.',name:'Carlos R.',role:'Administrador, Delicias Gourmet',initials:'CR'},
                    {quote:'Ya no tenemos que esperar a fin de mes para saber cómo va el negocio. Tomamos decisiones rápidas con datos en tiempo real.',name:'Sergio T.',role:'CEO, Discoteca El Club',initials:'ST'},
                    {quote:'Gestionar una franquicia con múltiples ubicaciones es mucho más fácil. Mantenemos los mismos estándares en todos los locales.',name:'Raquel D.',role:'Directora de Expansión, Café Express',initials:'RD'},
                    {quote:'No importa la hora ni el problema, nos han acompañado en cada paso. Se sienten como parte de nuestro equipo.',name:'Lucía P.',role:'Gerente, La Pizzeta',initials:'LP'},
                    {quote:'Estructuramos nuestros procesos y redujimos tiempos operativos. Ahora nos enfocamos en mejorar la experiencia del cliente.',name:'Fernanda O.',role:'Socia, La Trattoria Italiana',initials:'FO'},
                ]},
                mig: { tag:'Migración sin riesgo', title:'¿Ya tienes otro sistema? Cambia sin riesgos', sub:'Nos encargamos de que la migración de tu información sea un proceso rápido, seguro y sin pérdida de datos.', cta:'Hablar con un asesor', cta_in:'Ir al Panel', p1:'Sin costo de migración', p2:'Proceso 100% seguro', p3:'Sin pérdida de datos', p4:'Migración rápida' },
                footer: { sub:'Sistema POS para Restaurantes · Ecuador', rights:'Todos los derechos reservados.', c1t:'Producto', c1l:['POS & Sala','Cocina KDS','Inventario','Facturación SRI','Reportes'], c2t:'Empresa', c2l:['Contacto','Soporte','Blog','Precios'] },
            },
            it: {
                nav: { modulos:'Moduli', negocios:'Settori', testimonios:'Testimonianze', accedi:'Accedi', demo:'Prenota Demo', entrar:'Pannello' },
                hero: { badge:'Sistema POS per Ristoranti — Ecuador', subtitle:'"¡Oh, Solémia de mi corazón!"', desc:'Controlla i costi, gestisci gli inventari e aumenta la tua <strong>redditività</strong> con il sistema pensato per la ristorazione moderna.', cta_demo:'Prenota Demo Gratuita', cta_login:'Accedi al Sistema', cta_dash:'Vai al Pannello', t1:'Ristoranti attivi', t2:'Fatturazione cert.', t3:'Moduli integrati', t4:'Supporto disponibile' },
                stats: { s1:'Moduli integrati', s2:'Fallimenti per cattivo controllo costi', s3:'Ordini illimitati', s4:'On-premise sicuro' },
                biz: { tag:'Il tuo tipo di attività', title:'Ogni attività ha la sua essenza', sub:'Qualunque sia la tua specialità, Solémia si adatta alla tua operazione.', items:[
                    {name:'Ristoranti',desc:'Controlla costi, inventari e migliora l\'esperienza del commensale.'},
                    {name:'Fast Food',desc:'Velocizza gli ordini e assicura un servizio impeccabile in ogni transazione.'},
                    {name:'Bar & Discoteche',desc:'Gestisci il flusso notturno con precisione e controllo assoluto.'},
                    {name:'Catene / Franchising',desc:'Centralizza l\'operazione di tutti i tuoi locali con facilità.'},
                    {name:'Catering & Scuole',desc:'Gestisci la ristorazione collettiva in aziende e istituti educativi.'},
                    {name:'Retail / Negozi',desc:'Gestisci il tuo negozio con efficienza, dagli inventari alle vendite.'},
                ]},
                rent: { tag:'Redditività', title:'Il 60% dei fallimenti è causato da un cattivo controllo dei costi', sub:'Ecco perché ti forniamo gli strumenti per rendere redditizio il tuo ristorante fin dal primo mese.', pct:'60%', pct_text:'dei ristoranti fallisce per mancato controllo dei costi operativi.', items:['Controllo dei costi e percentuale in tempo reale','Modulo di pianificazione dei ricavi e punto di pareggio','Gestione inventari con alert di scorte minime','Punto vendita intuitivo con chiusura cassa rapida','Fatturazione elettronica compatibile con SRI Ecuador','Dashboard con KPI e report strategici'] },
                feat: { tag:'Moduli', title:'Tutto per il tuo ristorante', sub:'Un ecosistema completo dalla cucina al conto.', items:[
                    {title:'POS & Sala',desc:'Comande in tempo reale, mappa dei tavoli e flusso ordinato dalla sala alla cucina.'},
                    {title:'Cucina (KDS)',desc:'Display in tempo reale. Ogni piatto, modifica e urgenza sotto controllo.'},
                    {title:'Menu & Prodotti',desc:'Catalogo digitale con categorie, combo e prezzi happy hour.'},
                    {title:'Cassa & Pagamenti',desc:'Pagamenti misti, divisione del conto, mancia e fatturazione SRI.'},
                    {title:'Inventario',desc:'Stock in tempo reale, ricette con costo automatico e alert di minimo.'},
                    {title:'Report & KPI',desc:'Dashboard con vendite per ora, ranking piatti e prestazioni camerieri.'},
                    {title:'Utenti & Ruoli',desc:'Permessi granulari, PIN POS, audit log e autenticazione multi-ruolo.'},
                    {title:'WhatsApp Marketing',desc:'Campagne, chatbot automatico e CRM integrato con Meta Business.'},
                    {title:'Notifiche',desc:'Alert in-app, email e WhatsApp per eventi critici del business.'},
                ]},
                demo: { tag:'Gratuito', title:'Pronto a trasformare il tuo ristorante?', desc:'Prenota una demo personalizzata e scopri come Solémia può ridurre i tuoi costi e aumentare la redditività dal primo giorno.', cta:'Prenota Demo Gratuita!', cta_in:'Vai al Pannello', note:'Nessuna carta di credito · Nessun impegno · In 30 minuti', p1:'Migrazione gratuita', p2:'Supporto 24/7', p3:'Configurazione inclusa', p4:'Fatturazione SRI' },
                testi: { tag:'Testimonianze', title:'Cosa dicono i nostri clienti', items:[
                    {quote:'Ora possiamo ottimizzare gli inventari e controllare i costi in modo efficace, con un grande risparmio operativo.',name:'Carlos M.',role:'CEO, La Bella Tavola',initials:'CM'},
                    {quote:'Con Solémia abbiamo smesso di perdere soldi per errori nei costi. Sappiamo esattamente quanto dobbiamo vendere per essere redditizi.',name:'Carlos R.',role:'Amministratore, Delicias Gourmet',initials:'CR'},
                    {quote:'Non dobbiamo più aspettare la fine del mese. Con i dati sempre aggiornati prendiamo decisioni rapide.',name:'Sergio T.',role:'CEO, Discoteca El Club',initials:'ST'},
                    {quote:'Gestire un franchising con più sedi è molto più semplice. Manteniamo gli stessi standard ovunque.',name:'Raquel D.',role:'Dir. Espansione, Café Express',initials:'RD'},
                    {quote:'Qualunque sia l\'ora o il problema, ci hanno supportato ad ogni passo. Si sentono parte del team.',name:'Lucía P.',role:'Manager, La Pizzeta',initials:'LP'},
                    {quote:'Abbiamo strutturato i processi e ridotto i tempi operativi. Ora ci concentriamo sull\'esperienza del cliente.',name:'Fernanda O.',role:'Socia, La Trattoria Italiana',initials:'FO'},
                ]},
                mig: { tag:'Migrazione sicura', title:'Hai già un altro sistema? Cambia senza rischi', sub:'Ci occupiamo noi della migrazione dei tuoi dati: un processo rapido, sicuro e senza perdite.', cta:'Parla con un consulente', cta_in:'Vai al Pannello', p1:'Migrazione gratuita', p2:'Processo 100% sicuro', p3:'Nessuna perdita di dati', p4:'Migrazione rapida' },
                footer: { sub:'Sistema POS per Ristoranti · Ecuador', rights:'Tutti i diritti riservati.', c1t:'Prodotto', c1l:['POS & Sala','Cucina KDS','Inventario','Fatturazione SRI','Report'], c2t:'Azienda', c2l:['Contatti','Supporto','Blog','Prezzi'] },
            },
            en: {
                nav: { modulos:'Modules', negocios:'Business Types', testimonios:'Testimonials', accedi:'Sign In', demo:'Book Demo', entrar:'Dashboard' },
                hero: { badge:'Restaurant POS System — Ecuador', subtitle:'"The heart of your restaurant"', desc:'Control costs, manage inventory and boost your <strong>profitability</strong> with the system built for modern restaurants.', cta_demo:'Book Free Demo', cta_login:'Access System', cta_dash:'Go to Dashboard', t1:'Active restaurants', t2:'Certified billing', t3:'Integrated modules', t4:'Support available' },
                stats: { s1:'Integrated modules', s2:'Failures due to poor cost control', s3:'Unlimited orders', s4:'Secure on-premise' },
                biz: { tag:'Your business type', title:'Every business has its own essence', sub:'Whatever your specialty, Solémia adapts to your operation.', items:[
                    {name:'Restaurants',desc:'Control costs, inventory and improve the dining experience.'},
                    {name:'Fast Food',desc:'Speed up orders and ensure flawless service every time.'},
                    {name:'Bars & Clubs',desc:'Manage nighttime flow with precision and absolute control.'},
                    {name:'Chains / Franchises',desc:'Centralize operations across all your locations with ease.'},
                    {name:'Catering & Schools',desc:'Manage food sales in companies or educational institutions.'},
                    {name:'Retail / Shops',desc:'Run your store efficiently, from inventory to sales.'},
                ]},
                rent: { tag:'Profitability', title:'Over 60% of restaurant failures come from poor cost control', sub:'That\'s why we give you the tools to make your restaurant profitable from day one.', pct:'60%', pct_text:'of restaurants fail due to not controlling their operational costs.', items:['Real-time cost control and food cost percentage','Revenue planning module and break-even analysis','Simplified inventory management with stock alerts','Intuitive POS with fast cash register closing','Electronic invoicing compatible with SRI Ecuador','Dashboard with KPIs and strategic reports'] },
                feat: { tag:'Modules', title:'Everything for your restaurant', sub:'A complete ecosystem from kitchen to checkout.', items:[
                    {title:'POS & Dining',desc:'Real-time orders, interactive table map and seamless kitchen flow.'},
                    {title:'Kitchen (KDS)',desc:'Real-time kitchen display. Every dish, change and urgency under control.'},
                    {title:'Menu & Products',desc:'Digital catalog with categories, combos and happy hour pricing.'},
                    {title:'Cashier & Payments',desc:'Split payments, bill division, tips and SRI invoicing.'},
                    {title:'Inventory',desc:'Real-time stock, auto-costed recipes and low stock alerts.'},
                    {title:'Reports & KPIs',desc:'Dashboard with hourly sales, dish ranking and waiter performance.'},
                    {title:'Users & Roles',desc:'Granular permissions, POS PIN, audit log and multi-role auth.'},
                    {title:'WhatsApp Marketing',desc:'Campaigns, automated chatbot and Meta Business CRM.'},
                    {title:'Notifications',desc:'In-app, email and WhatsApp alerts for critical business events.'},
                ]},
                demo: { tag:'No cost', title:'Ready to transform your restaurant?', desc:'Book a personalized demo and see how Solémia can cut your costs and increase profitability from day one.', cta:'Book Free Demo!', cta_in:'Go to Dashboard', note:'No credit card · No commitment · 30 minutes', p1:'Free migration', p2:'24/7 support', p3:'Setup included', p4:'SRI billing' },
                testi: { tag:'Testimonials', title:'What our clients say', items:[
                    {quote:'We can now optimize inventory and control costs effectively, resulting in massive operational savings.',name:'Carlos M.',role:'CEO, La Bella Tavola',initials:'CM'},
                    {quote:'With Solémia we stopped losing money to cost errors. We know exactly how much we need to sell to be profitable.',name:'Carlos R.',role:'Manager, Delicias Gourmet',initials:'CR'},
                    {quote:'We no longer wait until month-end to know how the business is doing. Real-time data means faster, smarter decisions.',name:'Sergio T.',role:'CEO, Discoteca El Club',initials:'ST'},
                    {quote:'Managing a multi-location franchise is so much easier. We keep the same standards everywhere.',name:'Raquel D.',role:'Expansion Director, Café Express',initials:'RD'},
                    {quote:'No matter the time or the issue, they\'ve been with us every step of the way. They feel like part of our team.',name:'Lucía P.',role:'Manager, La Pizzeta',initials:'LP'},
                    {quote:'We structured our processes and cut operational time. Now we focus fully on the customer experience.',name:'Fernanda O.',role:'Partner, La Trattoria Italiana',initials:'FO'},
                ]},
                mig: { tag:'Risk-free migration', title:'Already have another system? Switch without risk', sub:'We handle your data migration — a fast, secure process with zero data loss.', cta:'Talk to an advisor', cta_in:'Go to Dashboard', p1:'Free migration', p2:'100% secure process', p3:'No data loss', p4:'Fast migration' },
                footer: { sub:'Restaurant POS System · Ecuador', rights:'All rights reserved.', c1t:'Product', c1l:['POS & Dining','Kitchen KDS','Inventory','SRI Billing','Reports'], c2t:'Company', c2l:['Contact','Support','Blog','Pricing'] },
            },
        };

        // ── ALPINE INIT ───────────────────────────────────────────
        document.addEventListener('alpine:init', () => {
            const saved = localStorage.getItem('solemia_lang') || 'es';
            Alpine.store('lang', {
                code: saved,
                flag: {es:'🇪🇨',it:'🇮🇹',en:'🇺🇸'}[saved] || '🇪🇨',
                t: trans[saved],
            });
            Alpine.data('langSwitcher', () => ({
                langs: [
                    {code:'es', name:'Español', flag:'🇪🇨'},
                    {code:'it', name:'Italiano', flag:'🇮🇹'},
                    {code:'en', name:'English', flag:'🇺🇸'},
                ],
                get currentLang() { return this.$store.lang.code; },
                init() {},
                switchLang(code) {
                    this.$store.lang.code = code;
                    this.$store.lang.flag = {es:'🇪🇨',it:'🇮🇹',en:'🇺🇸'}[code];
                    this.$store.lang.t = trans[code];
                    localStorage.setItem('solemia_lang', code);
                },
            }));
        });

        // ── SCROLL REVEAL ─────────────────────────────────────────
        // Se ejecuta DESPUÉS de que Alpine haya renderizado
        document.addEventListener('alpine:initialized', () => {
            const revealObs = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) { e.target.classList.add('in'); revealObs.unobserve(e.target); }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -20px 0px' });

            document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
        });
    </script>
</body>
</html>v