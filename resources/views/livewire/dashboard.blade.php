<div>
<style>
    @import url('https://fonts.bunny.net/css?family=cormorant-garamond:400,600,700&display=swap');
    @import url('https://fonts.bunny.net/css?family=dm-sans:300,400,500,600&display=swap');

    :root {
        --o50:  #f4f6f0; --o100: #e4e9d8; --o200: #c8d3b1; --o300: #a8b885;
        --o400: #869a5a; --o500: #677c3e; --o600: #506030; --o700: #3d4a24;
        --o800: #2c3419; --o900: #1c210f; --o950: #0e1108;
        --r400: #e03030; --r500: #c42020; --r100: #fdecea;
        --g300: #f0c96a; --g400: #d4a827; --g50: #fdf8ec;
        --cream: #fdfcf8; --cream2: #f5f3ee; --cream3: #ebe8e0;
    }
    .db-root { font-family: 'DM Sans', sans-serif; color: var(--o900); }

    /* ── KPI CARDS ─────────────────────────────────────── */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1px;
        background: var(--cream3);
        border: 1px solid var(--cream3);
        border-radius: 1.25rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .kpi-card {
        background: var(--cream);
        padding: 1.5rem 1.75rem 1.25rem;
        position: relative;
        transition: background 0.2s;
        overflow: hidden;
    }
    .kpi-card:hover { background: #fff; }
    /* Tricolore accent bar on top */
    .kpi-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
    }
    .kpi-card.olive::before  { background: var(--o500); }
    .kpi-card.gold::before   { background: var(--g400); }
    .kpi-card.emerald::before{ background: #10b981; }
    .kpi-card.red::before    { background: var(--r500); }

    .kpi-top {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 1.25rem;
    }
    .kpi-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.65rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
    }
    .kpi-icon.olive   { background: var(--o100); color: var(--o600); }
    .kpi-icon.gold    { background: var(--g50);  color: var(--g400); }
    .kpi-icon.emerald { background: #d1fae5;     color: #059669; }
    .kpi-icon.red     { background: var(--r100); color: var(--r500); }

    .kpi-badge {
        font-size: 0.68rem; font-weight: 600;
        padding: 0.2rem 0.55rem; border-radius: 1rem;
        letter-spacing: 0.04em;
    }
    .kpi-badge.olive   { background: var(--o50);  color: var(--o600); }
    .kpi-badge.gold    { background: var(--g50);  color: var(--g400); }
    .kpi-badge.emerald { background: #d1fae5;     color: #059669; }
    .kpi-badge.red     { background: var(--r100); color: var(--r500); }

    .kpi-label {
        font-size: 0.77rem; font-weight: 400; color: var(--o500);
        text-transform: uppercase; letter-spacing: 0.08em;
        margin-bottom: 0.25rem;
    }
    .kpi-value {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 2.75rem; font-weight: 700; line-height: 1;
        color: var(--o900);
    }
    .kpi-value.money::before { content: '$'; font-size: 1.4rem; vertical-align: 0.35em; font-weight: 400; color: var(--o400); margin-right: 1px; }
    .kpi-meta {
        font-size: 0.75rem; color: var(--o400); margin-top: 0.5rem;
        display: flex; align-items: center; gap: 0.35rem;
    }
    .kpi-meta i { font-size: 0.65rem; }
    .kpi-meta .up   { color: #059669; }
    .kpi-meta .down { color: var(--r500); }
    /* Mini sparkline bar */
    .kpi-bar { margin-top: 1rem; height: 3px; background: var(--cream3); border-radius: 2px; overflow: hidden; }
    .kpi-bar-fill { height: 100%; border-radius: 2px; }
    .kpi-bar-fill.olive   { background: var(--o400); }
    .kpi-bar-fill.gold    { background: var(--g400); }
    .kpi-bar-fill.emerald { background: #10b981; }
    .kpi-bar-fill.red     { background: var(--r400); }

    /* ── MAIN CONTENT GRID ────────────────────────────── */
    .dash-body {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    @media (min-width: 1024px) {
        .dash-body { grid-template-columns: 1fr 320px; }
    }

    /* ── PANEL BASE ───────────────────────────────────── */
    .panel {
        background: var(--cream);
        border: 1px solid var(--cream3);
        border-radius: 1.1rem;
        overflow: hidden;
    }
    .panel-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid var(--cream3);
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .panel-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.25rem; font-weight: 700; color: var(--o900);
        display: flex; align-items: center; gap: 0.6rem;
    }
    .panel-title-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--r500); flex-shrink: 0; }
    .panel-body { padding: 1.5rem; }

    /* ── WELCOME CARD ─────────────────────────────────── */
    .welcome-banner {
        display: flex; align-items: flex-start; gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: var(--o950);
        position: relative; overflow: hidden;
    }
    .welcome-banner::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--o500) 33.33%, #fff 33.33% 66.66%, var(--r500) 66.66%);
    }
    .welcome-banner::after {
        content: ''; position: absolute; inset: 0;
        background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }
    .welcome-emoji {
        font-size: 2rem; line-height: 1; flex-shrink: 0;
        position: relative; z-index: 1;
    }
    .welcome-text { position: relative; z-index: 1; }
    .welcome-quote {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1rem; font-style: italic; font-weight: 400;
        color: rgba(255,255,255,0.6); margin-bottom: 0.3rem;
    }
    .welcome-name {
        font-size: 0.9rem; font-weight: 600; color: #fff;
    }
    .welcome-name span { color: var(--o300); }

    /* ── MINI STATS ROW ───────────────────────────────── */
    .mini-stats {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 1px; background: var(--cream3);
        border-top: 1px solid var(--cream3);
    }
    .mini-stat {
        padding: 1rem; text-align: center; background: var(--cream);
        transition: background 0.2s;
    }
    .mini-stat:hover { background: var(--cream2); }
    .mini-stat-n {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.6rem; font-weight: 700; line-height: 1; color: var(--o800);
    }
    .mini-stat-l { font-size: 0.7rem; color: var(--o500); margin-top: 0.2rem; text-transform: uppercase; letter-spacing: 0.07em; }

    /* ── SALES CHART ──────────────────────────────────── */
    .chart-container {
        position: relative; margin-top: 0.5rem;
    }
    .chart-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;
    }
    .chart-legend {
        display: flex; gap: 1rem;
    }
    .chart-legend-item {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.75rem; color: var(--o500);
    }
    .chart-legend-dot { width: 8px; height: 8px; border-radius: 50%; }
    .chart-svg { width: 100%; overflow: visible; }
    .chart-yaxis text { font-size: 10px; fill: var(--o400); font-family: 'DM Sans', sans-serif; }
    .chart-xaxis text { font-size: 10px; fill: var(--o400); font-family: 'DM Sans', sans-serif; }
    .chart-gridline { stroke: var(--cream3); stroke-width: 1; }
    .chart-area { fill: url(#areaGrad); }
    .chart-line { fill: none; stroke: var(--o500); stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
    .chart-dot { fill: #fff; stroke: var(--o500); stroke-width: 2; }
    .chart-dot:hover { fill: var(--o500); r: 5; }
    .chart-tabs { display: flex; gap: 0.4rem; }
    .chart-tab {
        font-size: 0.75rem; font-weight: 500; padding: 0.3rem 0.75rem;
        border-radius: 1.5rem; border: 1px solid var(--cream3);
        background: none; cursor: pointer; color: var(--o500);
        transition: all 0.15s;
    }
    .chart-tab.active {
        background: var(--o800); color: #fff; border-color: var(--o800);
    }

    /* ── ACTIVITY LIST ────────────────────────────────── */
    .activity-list { display: flex; flex-direction: column; gap: 0.1rem; }
    .activity-item {
        display: flex; align-items: center; gap: 0.85rem;
        padding: 0.75rem 0; border-bottom: 1px solid var(--cream3);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon {
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; flex-shrink: 0;
    }
    .activity-body { flex: 1; min-width: 0; }
    .activity-title { font-size: 0.83rem; font-weight: 500; color: var(--o800); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .activity-sub { font-size: 0.73rem; color: var(--o400); margin-top: 0.1rem; }
    .activity-time { font-size: 0.7rem; color: var(--o400); white-space: nowrap; }

    /* ── USER PANEL ───────────────────────────────────── */
    .user-avatar {
        width: 3rem; height: 3rem; border-radius: 50%;
        background: var(--o800); color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.25rem; font-weight: 700; flex-shrink: 0;
    }
    .user-info-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.65rem 0; border-bottom: 1px solid var(--cream3);
        font-size: 0.82rem;
    }
    .user-info-row:last-child { border-bottom: none; }
    .user-info-label { color: var(--o500); }
    .user-info-value { font-weight: 500; color: var(--o800); }
    .badge-active {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.6rem;
        background: #d1fae5; color: #059669; border-radius: 1rem;
    }
    .badge-active::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: #059669; }
    .edit-profile-btn {
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        width: 100%; padding: 0.65rem;
        background: var(--o50); border: 1px solid var(--o200);
        border-radius: 0.65rem; text-decoration: none;
        font-size: 0.83rem; font-weight: 500; color: var(--o700);
        transition: all 0.2s;
    }
    .edit-profile-btn:hover { background: var(--o100); border-color: var(--o400); color: var(--o900); }

    /* ── QUICK ACTIONS ────────────────────────────────── */
    .quick-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
    .qa-btn {
        display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
        padding: 0.9rem 0.5rem; border-radius: 0.75rem;
        background: var(--cream2); border: 1px solid var(--cream3);
        text-decoration: none; font-size: 0.75rem; font-weight: 500;
        color: var(--o700); transition: all 0.2s; text-align: center;
    }
    .qa-btn:hover { background: #fff; border-color: var(--o300); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .qa-btn i { font-size: 1.1rem; color: var(--o500); }

    /* ── POLL INDICATOR ───────────────────────────────── */
    .poll-badge {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.68rem; color: var(--o400); padding: 0.25rem 0.6rem;
        background: var(--o50); border: 1px solid var(--o100); border-radius: 1rem;
    }
    .poll-dot {
        width: 5px; height: 5px; border-radius: 50%; background: #10b981;
        animation: pollPulse 2s ease-in-out infinite;
    }
    @keyframes pollPulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.5;transform:scale(0.8)} }

    /* ── ENTRANCE ANIMATIONS ──────────────────────────── */
    .db-root .kpi-card { animation: upIn 0.5s ease both; }
    .db-root .kpi-card:nth-child(1) { animation-delay: 0.05s; }
    .db-root .kpi-card:nth-child(2) { animation-delay: 0.12s; }
    .db-root .kpi-card:nth-child(3) { animation-delay: 0.19s; }
    .db-root .kpi-card:nth-child(4) { animation-delay: 0.26s; }
    .db-root .panel { animation: upIn 0.55s ease 0.3s both; }

    @keyframes upIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<div class="db-root" wire:poll.15s="refreshStats">

    {{-- ── KPI GRID ────────────────────────────────────────────── --}}
    <div class="kpi-grid">
        {{-- Mesas ocupadas --}}
        <div class="kpi-card olive">
            <div class="kpi-top">
                <div class="kpi-icon olive"><i class="fas fa-utensils"></i></div>
                <span class="kpi-badge olive">Hoy</span>
            </div>
            <p class="kpi-label">Mesas ocupadas</p>
            <p class="kpi-value">{{ $occupiedTables }}</p>
            <p class="kpi-meta">
                <i class="fas fa-table-cells-large"></i>
                De las mesas disponibles en sala
            </p>
            <div class="kpi-bar"><div class="kpi-bar-fill olive" style="width:65%;"></div></div>
        </div>

        {{-- Comandas en cocina --}}
        <div class="kpi-card gold">
            <div class="kpi-top">
                <div class="kpi-icon gold"><i class="fas fa-fire-flame-curved"></i></div>
                <span class="kpi-badge gold">En cocina</span>
            </div>
            <p class="kpi-label">Comandas en preparación</p>
            <p class="kpi-value">{{ $kitchenOrders }}</p>
            <p class="kpi-meta">
                <i class="fas fa-clock"></i>
                Tiempo promedio: <strong style="color:var(--o700);">12 min</strong>
            </p>
            <div class="kpi-bar"><div class="kpi-bar-fill gold" style="width:40%;"></div></div>
        </div>

        {{-- Ventas del día --}}
        <div class="kpi-card emerald">
            <div class="kpi-top">
                <div class="kpi-icon emerald"><i class="fas fa-dollar-sign"></i></div>
                <span class="kpi-badge emerald">Ventas</span>
            </div>
            <p class="kpi-label">Ventas del día</p>
            <p class="kpi-value money">{{ number_format($dailySales, 2) }}</p>
            <p class="kpi-meta">
                <i class="fas fa-arrow-trend-up up"></i>
                <span class="up">+18%</span> vs. ayer
            </p>
            <div class="kpi-bar"><div class="kpi-bar-fill emerald" style="width:78%;"></div></div>
        </div>

        {{-- Usuarios activos --}}
        <div class="kpi-card red">
            <div class="kpi-top">
                <div class="kpi-icon red"><i class="fas fa-users"></i></div>
                <span class="kpi-badge red">Sistema</span>
            </div>
            <p class="kpi-label">Usuarios activos</p>
            <p class="kpi-value">{{ $activeUsers }}</p>
            <p class="kpi-meta">
                <i class="fas fa-circle" style="font-size:0.45rem;color:#10b981;"></i>
                Conectados ahora mismo
            </p>
            <div class="kpi-bar"><div class="kpi-bar-fill red" style="width:30%;"></div></div>
        </div>
    </div>

    {{-- ── BODY ─────────────────────────────────────────────────── --}}
    <div class="dash-body">

        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Welcome banner --}}
            <div class="panel" style="overflow:hidden;">
                <div class="welcome-banner">
                    <div class="welcome-emoji">🍝</div>
                    <div class="welcome-text">
                        <p class="welcome-quote">"¡Oh, Solémia de mi corazón!" — benvenuto!</p>
                        <p class="welcome-name">
                            Iniciaste sesión como <span>{{ auth()->user()->name }}</span>.
                            Explora los módulos desde la barra lateral.
                        </p>
                    </div>
                </div>
                <div class="mini-stats">
                    <div class="mini-stat">
                        <div class="mini-stat-n" style="color:var(--o700);">10</div>
                        <div class="mini-stat-l">Módulos</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-n" style="color:var(--g400);">5</div>
                        <div class="mini-stat-l">Roles</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-n" style="color:#059669;">32</div>
                        <div class="mini-stat-l">Permisos</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-n" style="color:var(--r500);">{{ $totalUsers }}</div>
                        <div class="mini-stat-l">Usuarios</div>
                    </div>
                </div>
            </div>

            {{-- Sales Chart --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <span class="panel-title-dot"></span>
                        Ventas de la semana
                    </div>
                    <div class="chart-tabs">
                        <button class="chart-tab active">Semana</button>
                        <button class="chart-tab">Mes</button>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="chart-header">
                        <div style="font-size:0.78rem;color:var(--o500);">Ingresos en USD</div>
                        <div class="chart-legend">
                            <div class="chart-legend-item">
                                <div class="chart-legend-dot" style="background:var(--o500);"></div>
                                Esta semana
                            </div>
                            <div class="chart-legend-item">
                                <div class="chart-legend-dot" style="background:var(--cream3);"></div>
                                Semana pasada
                            </div>
                        </div>
                    </div>
                    <div class="chart-container">
                        <svg class="chart-svg" viewBox="0 0 560 180" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="areaGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%"   stop-color="#677c3e" stop-opacity="0.15"/>
                                    <stop offset="100%" stop-color="#677c3e" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            <!-- Grid lines -->
                            <line x1="48" y1="20"  x2="548" y2="20"  class="chart-gridline"/>
                            <line x1="48" y1="60"  x2="548" y2="60"  class="chart-gridline"/>
                            <line x1="48" y1="100" x2="548" y2="100" class="chart-gridline"/>
                            <line x1="48" y1="140" x2="548" y2="140" class="chart-gridline"/>
                            <!-- Y axis labels -->
                            <g class="chart-yaxis">
                                <text x="40" y="24"  text-anchor="end">600</text>
                                <text x="40" y="64"  text-anchor="end">450</text>
                                <text x="40" y="104" text-anchor="end">300</text>
                                <text x="40" y="144" text-anchor="end">150</text>
                            </g>
                            <!-- Previous week (gray) -->
                            <polyline
                                points="48,120  130,110  212,130  294,95  376,115  458,90  548,105"
                                fill="none" stroke="#c8d3b1" stroke-width="1.5"
                                stroke-dasharray="4,3" stroke-linecap="round" stroke-linejoin="round"
                            />
                            <!-- This week area -->
                            <path
                                d="M48,110 L130,90 L212,115 L294,55 L376,80 L458,40 L548,65 L548,155 L48,155 Z"
                                class="chart-area"
                            />
                            <!-- This week line -->
                            <polyline
                                class="chart-line"
                                points="48,110  130,90  212,115  294,55  376,80  458,40  548,65"
                            />
                            <!-- Dots -->
                            <circle class="chart-dot" cx="48"  cy="110" r="4"/>
                            <circle class="chart-dot" cx="130" cy="90"  r="4"/>
                            <circle class="chart-dot" cx="212" cy="115" r="4"/>
                            <circle class="chart-dot" cx="294" cy="55"  r="4" style="fill:var(--o500);"/>
                            <circle class="chart-dot" cx="376" cy="80"  r="4"/>
                            <circle class="chart-dot" cx="458" cy="40"  r="4"/>
                            <circle class="chart-dot" cx="548" cy="65"  r="4"/>
                            <!-- X axis labels -->
                            <g class="chart-xaxis">
                                <text x="48"  y="170" text-anchor="middle">Lun</text>
                                <text x="130" y="170" text-anchor="middle">Mar</text>
                                <text x="212" y="170" text-anchor="middle">Mié</text>
                                <text x="294" y="170" text-anchor="middle">Jue</text>
                                <text x="376" y="170" text-anchor="middle">Vie</text>
                                <text x="458" y="170" text-anchor="middle">Sáb</text>
                                <text x="548" y="170" text-anchor="middle">Dom</text>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Recent activity --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <span class="panel-title-dot"></span>
                        Actividad reciente
                    </div>
                    <span class="poll-badge">
                        <span class="poll-dot"></span>
                        En vivo
                    </span>
                </div>
                <div class="panel-body" style="padding-top:0.25rem;padding-bottom:0.25rem;">
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon" style="background:var(--o100);color:var(--o600);">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="activity-body">
                                <div class="activity-title">Mesa 4 — Comanda #0214 abierta</div>
                                <div class="activity-sub">4 productos · Mozo: Carlos R.</div>
                            </div>
                            <div class="activity-time">hace 2 min</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background:#d1fae5;color:#059669;">
                                <i class="fas fa-circle-check"></i>
                            </div>
                            <div class="activity-body">
                                <div class="activity-title">Pago recibido — $48.50</div>
                                <div class="activity-sub">Mesa 7 · Efectivo + Transferencia</div>
                            </div>
                            <div class="activity-time">hace 8 min</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background:var(--g50);color:var(--g400);">
                                <i class="fas fa-fire-flame-curved"></i>
                            </div>
                            <div class="activity-body">
                                <div class="activity-title">Cocina — Plato listo: Tagliatelle</div>
                                <div class="activity-sub">Comanda #0211 · Mesa 2</div>
                            </div>
                            <div class="activity-time">hace 11 min</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background:var(--r100);color:var(--r500);">
                                <i class="fas fa-triangle-exclamation"></i>
                            </div>
                            <div class="activity-body">
                                <div class="activity-title">Inventario — Stock bajo: Mozzarella</div>
                                <div class="activity-sub">Quedan 0.4 kg · Mínimo: 1 kg</div>
                            </div>
                            <div class="activity-time">hace 24 min</div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background:var(--o50);color:var(--o500);">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="activity-body">
                                <div class="activity-title">Factura SRI emitida — #001-001-000412</div>
                                <div class="activity-sub">$122.80 · Cliente: Juan Pérez</div>
                            </div>
                            <div class="activity-time">hace 35 min</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /left --}}

        {{-- RIGHT COLUMN --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- User card --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <span class="panel-title-dot"></span>
                        Mi perfil
                    </div>
                </div>
                <div class="panel-body">
                    <div style="display:flex;align-items:center;gap:0.85rem;margin-bottom:1.25rem;">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:0.95rem;font-weight:600;color:var(--o900);">{{ auth()->user()->name }}</div>
                            <div style="font-size:0.75rem;color:var(--o500);margin-top:0.15rem;">{{ auth()->user()->email }}</div>
                        </div>
                    </div>

                    <div class="user-info-row">
                        <span class="user-info-label">Rol</span>
                        <span class="user-info-value">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
                    </div>
                    <div class="user-info-row">
                        <span class="user-info-label">Estado</span>
                        @if(auth()->user()->is_active)
                            <span class="badge-active">Activo</span>
                        @else
                            <span style="font-size:0.75rem;color:var(--o400);">Inactivo</span>
                        @endif
                    </div>
                    <div class="user-info-row" style="margin-bottom:1rem;">
                        <span class="user-info-label">Miembro desde</span>
                        <span class="user-info-value">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
                    </div>

                    <a href="{{ route('profile') }}" wire:navigate class="edit-profile-btn">
                        <i class="fas fa-user-pen"></i>
                        Editar perfil
                    </a>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <span class="panel-title-dot"></span>
                        Acciones rápidas
                    </div>
                </div>
                <div class="panel-body">
                    <div class="quick-actions">
                        <a href="#" class="qa-btn">
                            <i class="fas fa-plus-circle"></i>
                            Nueva comanda
                        </a>
                        <a href="#" class="qa-btn">
                            <i class="fas fa-cash-register"></i>
                            Abrir caja
                        </a>
                        <a href="#" class="qa-btn">
                            <i class="fas fa-boxes-stacked"></i>
                            Inventario
                        </a>
                        <a href="#" class="qa-btn">
                            <i class="fas fa-file-invoice"></i>
                            Facturar
                        </a>
                        <a href="#" class="qa-btn">
                            <i class="fas fa-chart-line"></i>
                            Reportes
                        </a>
                        <a href="#" class="qa-btn">
                            <i class="fas fa-users-cog"></i>
                            Usuarios
                        </a>
                    </div>
                </div>
            </div>

            {{-- System status --}}
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <span class="panel-title-dot"></span>
                        Estado del sistema
                    </div>
                </div>
                <div class="panel-body" style="padding-top:0.5rem;">
                    @foreach([
                        ['SRI / Facturación', '#10b981', 'Operativo'],
                        ['Base de datos',     '#10b981', 'Operativo'],
                        ['WhatsApp API',      '#10b981', 'Conectado'],
                        ['Impresora cocina',  '#f59e0b', 'Revisar'],
                    ] as [$svc, $color, $status])
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:0.55rem 0;border-bottom:1px solid var(--cream3);">
                        <span style="font-size:0.8rem;color:var(--o700);">{{ $svc }}</span>
                        <span style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.73rem;font-weight:600;color:{{ $color }};">
                            <span style="width:5px;height:5px;border-radius:50%;background:{{ $color }};display:inline-block;"></span>
                            {{ $status }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>{{-- /right --}}
    </div>{{-- /.dash-body --}}
</div>{{-- /.db-root --}}
</div>