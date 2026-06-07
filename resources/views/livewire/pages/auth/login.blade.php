<?php
use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

{{-- ── SINGLE ROOT ELEMENT (Livewire requiere un solo elemento raíz) ── --}}
<div>
<style>
    @import url('https://fonts.bunny.net/css?family=cormorant-garamond:300,400,500,600,700i&display=swap');
    @import url('https://fonts.bunny.net/css?family=dm-sans:300,400,500,600&display=swap');

    :root {
        --o50:  #f4f6f0;
        --o100: #e4e9d8;
        --o200: #c8d3b1;
        --o400: #869a5a;
        --o500: #677c3e;
        --o600: #506030;
        --o700: #3d4a24;
        --o800: #2c3419;
        --o900: #1c210f;
        --o950: #0e1108;
        --r400: #e03030;
        --r500: #c42020;
        --r100: #fdecea;
        --cream: #fdfcf8;
        --cream2: #f5f3ee;
        --cream3: #ebe8e0;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    /* ── LAYOUT GUEST OVERRIDE ────────────────────────── */
    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--cream);
        min-height: 100dvh;
        -webkit-font-smoothing: antialiased;
    }

    .login-wrap {
        min-height: 100dvh;
        display: flex;
    }

    /* ── LEFT PANEL (brand) ───────────────────────────── */
    .login-left {
        display: none;
        position: relative;
        flex: 1;
        background: var(--o950);
        overflow: hidden;
        flex-direction: column;
        justify-content: space-between;
        padding: 2.5rem;
    }
    @media (min-width: 900px) {
        .login-left { display: flex; }
    }

    /* Tricolore top stripe */
    .login-left::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: linear-gradient(90deg, var(--o500) 33.33%, #fff 33.33% 66.66%, var(--r500) 66.66%);
    }
    /* Grid texture */
    .left-grid {
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 52px 52px;
    }
    /* Glow */
    .left-glow {
        position: absolute;
        bottom: -80px; left: -80px;
        width: 480px; height: 480px;
        background: radial-gradient(circle, rgba(103,124,62,0.18) 0%, rgba(196,32,32,0.06) 50%, transparent 70%);
        pointer-events: none;
    }
    .left-top {
        position: relative; z-index: 1;
    }
    .left-logo {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.75rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: 0.5rem;
        text-decoration: none;
    }
    .logo-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--r500); flex-shrink: 0; }

    .left-bottom { position: relative; z-index: 1; }
    .left-quote {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: clamp(2rem, 3.5vw, 2.75rem);
        font-weight: 400; font-style: italic;
        line-height: 1.25; color: rgba(255,255,255,0.88);
        margin-bottom: 1.25rem;
    }
    .left-quote span {
        color: var(--o300);
        font-style: italic;
    }
    .left-quote-line {
        display: flex; align-items: center; gap: 0.75rem;
    }
    .left-quote-bar { width: 32px; height: 2px; background: var(--r500); flex-shrink: 0; }
    .left-quote-attr {
        font-size: 0.78rem; font-weight: 500;
        letter-spacing: 0.08em; text-transform: uppercase;
        color: rgba(255,255,255,0.35);
    }
    /* Floating badges */
    .left-badges {
        display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 2rem;
    }
    .left-badge {
        display: flex; align-items: center; gap: 0.4rem;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);
        padding: 0.4rem 0.85rem; border-radius: 2rem;
        font-size: 0.75rem; color: rgba(255,255,255,0.5);
    }
    .left-badge i { font-size: 0.7rem; color: var(--o400); }

    /* ── RIGHT PANEL (form) ───────────────────────────── */
    .login-right {
        flex: 0 0 100%;
        display: flex; flex-direction: column;
        justify-content: center; align-items: center;
        padding: 2rem 1.5rem;
        background: var(--cream);
        position: relative;
    }
    @media (min-width: 900px) {
        .login-right { flex: 0 0 480px; }
    }

    /* Top tricolore on mobile */
    .right-tricolore {
        position: absolute; top: 0; left: 0; right: 0; height: 4px;
        display: flex;
    }
    .tri-g { flex: 1; background: var(--o500); }
    .tri-w { flex: 1; background: #e0ddd4; }
    .tri-r { flex: 1; background: var(--r500); }
    @media (min-width: 900px) { .right-tricolore { display: none; } }

    /* Mobile logo */
    .right-mobile-logo {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 1.5rem; font-weight: 700; color: var(--o800);
        display: flex; align-items: center; gap: 0.5rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 900px) { .right-mobile-logo { display: none; } }

    .login-card {
        width: 100%; max-width: 400px;
    }

    /* Header */
    .lc-header { margin-bottom: 2rem; }
    .lc-eyebrow {
        font-size: 0.7rem; font-weight: 600;
        letter-spacing: 0.18em; text-transform: uppercase;
        color: var(--o500); margin-bottom: 0.6rem;
    }
    .lc-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 2.25rem; font-weight: 700;
        color: var(--o900); line-height: 1.1;
    }
    .lc-sub {
        font-size: 0.88rem; color: var(--o500);
        margin-top: 0.4rem; font-weight: 300;
    }

    /* Session status */
    .session-status {
        background: var(--o50); border: 1px solid var(--o200);
        border-radius: 0.75rem; padding: 0.75rem 1rem;
        font-size: 0.85rem; color: var(--o700);
        margin-bottom: 1.25rem;
    }

    /* Form fields */
    .lc-field { margin-bottom: 1.25rem; }
    .lc-label {
        display: block; font-size: 0.8rem; font-weight: 500;
        color: var(--o800); margin-bottom: 0.4rem;
        letter-spacing: 0.01em;
    }
    .lc-input-wrap { position: relative; }
    .lc-input-icon {
        position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
        color: var(--o400); font-size: 0.85rem; pointer-events: none;
        transition: color 0.2s;
    }
    .lc-input {
        width: 100%; padding: 0.75rem 1rem 0.75rem 2.5rem;
        background: #fff; border: 1.5px solid var(--cream3);
        border-radius: 0.75rem; font-size: 0.9rem; color: var(--o900);
        font-family: 'DM Sans', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none; appearance: none;
    }
    .lc-input:focus {
        border-color: var(--o500);
        box-shadow: 0 0 0 3px rgba(103,124,62,0.12);
    }
    .lc-input:focus ~ .lc-input-icon { color: var(--o600); }
    .lc-input::placeholder { color: var(--cream3); }

    /* Password toggle */
    .lc-pw-toggle {
        position: absolute; right: 0.9rem; top: 50%; transform: translateY(-50%);
        color: var(--o400); font-size: 0.85rem; cursor: pointer;
        background: none; border: none; padding: 0; line-height: 1;
        transition: color 0.2s;
    }
    .lc-pw-toggle:hover { color: var(--o700); }

    /* Error message */
    .lc-error {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.78rem; color: var(--r500);
        margin-top: 0.4rem;
    }
    .lc-error i { font-size: 0.72rem; flex-shrink: 0; }

    /* Row: remember + forgot */
    .lc-row {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .lc-remember {
        display: flex; align-items: center; gap: 0.5rem;
        cursor: pointer;
    }
    .lc-checkbox {
        width: 16px; height: 16px; border-radius: 4px;
        border: 1.5px solid var(--o300); background: #fff;
        cursor: pointer; accent-color: var(--o600);
        flex-shrink: 0;
    }
    .lc-remember-label {
        font-size: 0.82rem; color: var(--o600);
        user-select: none;
    }
    .lc-forgot {
        font-size: 0.82rem; color: var(--o500);
        text-decoration: none; font-weight: 400;
        transition: color 0.2s; border-bottom: 1px solid transparent;
    }
    .lc-forgot:hover { color: var(--o800); border-bottom-color: var(--o300); }

    /* Submit button */
    .lc-btn {
        width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.6rem;
        background: var(--o800); color: #fff;
        padding: 0.85rem 1.5rem; border-radius: 0.75rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.92rem; font-weight: 600;
        border: none; cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 12px rgba(44,52,25,0.2);
        letter-spacing: 0.01em;
    }
    .lc-btn:hover {
        background: var(--o700);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(44,52,25,0.28);
    }
    .lc-btn:active { transform: translateY(0); }
    .lc-btn[wire\:loading] { opacity: 0.75; pointer-events: none; }

    /* Divider */
    .lc-divider {
        display: flex; align-items: center; gap: 0.75rem;
        margin: 1.5rem 0;
    }
    .lc-divider-line { flex: 1; height: 1px; background: var(--cream3); }
    .lc-divider-text { font-size: 0.75rem; color: var(--o400); white-space: nowrap; }

    /* Register link */
    .lc-register {
        text-align: center;
        font-size: 0.85rem; color: var(--o500);
    }
    .lc-register a {
        color: var(--r500); font-weight: 600;
        text-decoration: none; transition: color 0.2s;
    }
    .lc-register a:hover { color: var(--r400); }

    /* Footer note */
    .lc-footer {
        margin-top: 2rem; text-align: center;
        font-size: 0.72rem; color: var(--o400);
        line-height: 1.5;
    }

    /* Loading spinner */
    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner { animation: spin 0.7s linear infinite; }

    /* Entrance animations */
    .login-left  { animation: fadeSlideRight 0.7s ease both; }
    .login-right { animation: fadeSlideLeft  0.6s ease 0.1s both; }
    @keyframes fadeSlideRight {
        from { opacity: 0; transform: translateX(-20px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeSlideLeft {
        from { opacity: 0; transform: translateX(20px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    .lc-header  { animation: upIn 0.6s ease 0.2s both; }
    .lc-field   { animation: upIn 0.6s ease 0.3s both; }
    .lc-field:nth-child(3) { animation-delay: 0.38s; }
    .lc-row     { animation: upIn 0.6s ease 0.45s both; }
    .lc-btn     { animation: upIn 0.6s ease 0.52s both; }
    @keyframes upIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="login-wrap">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    {{-- ── LEFT PANEL ──────────────────────────────────────── --}}
    <div class="login-left">
        <div class="left-grid"></div>
        <div class="left-glow"></div>

        <div class="left-top">
            <a href="{{ url('/') }}" class="left-logo">
                Solémia <span class="logo-dot"></span>
            </a>
        </div>

        <div class="left-bottom">
            <p class="left-quote">
                "La <span>rentabilidad</span><br>
                de tu restaurante<br>
                comienza aquí."
            </p>
            <div class="left-quote-line">
                <span class="left-quote-bar"></span>
                <span class="left-quote-attr">Sistema POS · Ecuador</span>
            </div>
            <div class="left-badges">
                <span class="left-badge"><i class="fas fa-shield-halved"></i> Datos seguros</span>
                <span class="left-badge"><i class="fas fa-file-invoice"></i> SRI compatible</span>
                <span class="left-badge"><i class="fas fa-headset"></i> Soporte 24/7</span>
            </div>
        </div>
    </div>

    {{-- ── RIGHT PANEL ─────────────────────────────────────── --}}
    <div class="login-right">
        <div class="right-tricolore">
            <div class="tri-g"></div>
            <div class="tri-w"></div>
            <div class="tri-r"></div>
        </div>

        <div class="right-mobile-logo">
            Solémia <span class="logo-dot"></span>
        </div>

        <div class="login-card">

            {{-- Header --}}
            <div class="lc-header">
                <p class="lc-eyebrow">Bienvenido de nuevo</p>
                <h1 class="lc-title">Iniciar sesión</h1>
                <p class="lc-sub">Ingresa tus credenciales para acceder al sistema.</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
            <div class="session-status">
                <i class="fas fa-circle-info" style="color:var(--o500);margin-right:0.4rem;"></i>
                {{ session('status') }}
            </div>
            @endif

            <form wire:submit="login" x-data="{ showPass: false, loading: false }" @submit="loading = true">

                {{-- Email --}}
                <div class="lc-field">
                    <label for="email" class="lc-label">Correo electrónico</label>
                    <div class="lc-input-wrap">
                        <i class="fas fa-envelope lc-input-icon"></i>
                        <input
                            wire:model="form.email"
                            id="email" type="email" name="email"
                            class="lc-input"
                            placeholder="tu@restaurante.com"
                            required autofocus autocomplete="username"
                        />
                    </div>
                    @error('form.email')
                    <div class="lc-error">
                        <i class="fas fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="lc-field" style="animation-delay:0.38s;">
                    <label for="password" class="lc-label">Contraseña</label>
                    <div class="lc-input-wrap">
                        <i class="fas fa-lock lc-input-icon"></i>
                        <input
                            wire:model="form.password"
                            id="password"
                            :type="showPass ? 'text' : 'password'"
                            name="password"
                            class="lc-input"
                            placeholder="••••••••"
                            required autocomplete="current-password"
                        />
                        <button type="button" class="lc-pw-toggle" @click="showPass = !showPass" tabindex="-1" aria-label="Mostrar contraseña">
                            <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    @error('form.password')
                    <div class="lc-error">
                        <i class="fas fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="lc-row">
                    <label class="lc-remember">
                        <input wire:model="form.remember" id="remember" type="checkbox" class="lc-checkbox" name="remember" />
                        <span class="lc-remember-label">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                    <a class="lc-forgot" href="{{ route('password.request') }}" wire:navigate>
                        ¿Olvidaste tu contraseña?
                    </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" class="lc-btn" :disabled="loading">
                    <template x-if="!loading">
                        <span style="display:flex;align-items:center;gap:0.5rem;">
                            <i class="fas fa-arrow-right-to-bracket"></i>
                            Entrar al sistema
                        </span>
                    </template>
                    <template x-if="loading">
                        <span style="display:flex;align-items:center;gap:0.5rem;">
                            <i class="fas fa-circle-notch spinner"></i>
                            Verificando...
                        </span>
                    </template>
                </button>

            </form>

            {{-- Register link --}}
            @if (Route::has('register'))
            <div class="lc-divider">
                <span class="lc-divider-line"></span>
                <span class="lc-divider-text">¿Aún no tienes cuenta?</span>
                <span class="lc-divider-line"></span>
            </div>
            <div class="lc-register">
                <a href="{{ route('register') }}" wire:navigate>Crear cuenta gratis →</a>
            </div>
            @endif

            <div class="lc-footer">
                Al ingresar aceptas los <a href="#" style="color:var(--o500);text-decoration:underline;">Términos de uso</a>
                y la <a href="#" style="color:var(--o500);text-decoration:underline;">Política de privacidad</a>.
            </div>

        </div>{{-- /.login-card --}}
    </div>{{-- /.login-right --}}

</div>{{-- /.login-wrap --}}
</div>{{-- /.root --}}