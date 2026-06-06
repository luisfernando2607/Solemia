<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solémia — POS Restaurante</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased" x-data="langSwitcher()" x-init="init()">
    @if (Route::has('login'))
        <div class="fixed top-0 right-0 z-50 p-4 md:p-6 flex items-center gap-3">
            {{-- Language Switcher --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 bg-white/90 backdrop-blur-sm text-olive-700 px-3 py-2 rounded-full text-xs font-medium border border-olive-200 hover:border-olive-400 transition-all duration-200 shadow-lg">
                    <span x-text="$store.lang.flag"></span>
                    <span x-text="$store.lang.code.toUpperCase()"></span>
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50" x-cloak>
                    <template x-for="lang in langs" :key="lang.code">
                        <button @click="switchLang(lang.code); open = false" class="flex items-center gap-3 w-full px-4 py-2 text-sm hover:bg-olive-50 transition-colors" :class="currentLang === lang.code ? 'text-olive-700 font-semibold' : 'text-gray-600'">
                            <span x-text="lang.flag"></span>
                            <span x-text="lang.name"></span>
                            <i class="fas fa-check ml-auto text-olive-500" x-show="currentLang === lang.code"></i>
                        </button>
                    </template>
                </div>
            </div>

            @auth
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-olive-600 hover:bg-olive-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 shadow-lg shadow-olive-600/25 hover:shadow-xl hover:shadow-olive-600/30 hover:scale-105">
                    <i class="fas fa-arrow-right"></i>
                    <span x-text="$store.lang.t.nav.entrar"></span>
                </a>
            @else
                <div class="flex gap-2">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white/90 backdrop-blur-sm text-olive-800 hover:text-olive-600 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 border border-olive-200 hover:border-olive-400 shadow-lg hover:shadow-xl hover:scale-105">
                        <i class="fas fa-sign-in-alt"></i>
                        <span x-text="$store.lang.t.nav.accedi"></span>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-olive-600 hover:bg-olive-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 shadow-lg shadow-olive-600/25 hover:shadow-xl hover:shadow-olive-600/30 hover:scale-105">
                            <i class="fas fa-user-plus"></i>
                            <span x-text="$store.lang.t.nav.registrati"></span>
                        </a>
                    @endif
                </div>
            @endauth
        </div>
    @endif

    {{-- Hero Section --}}
    <section class="relative min-h-screen flex items-center bg-gradient-to-br from-olive-900 via-olive-800 to-olive-950 overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03]">
            <div class="absolute top-20 left-10 w-72 h-72 bg-gold-400 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-olive-300 rounded-full blur-3xl"></div>
        </div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PHBhdGggZD0iTTM2IDM0djItSDI0di0yaDEyek0zNiAyNHYySDI0di0yaDEyeiIvPjwvZz48L2c+PC9zdmc+')] opacity-20"></div>

        <div class="relative z-10 w-full px-4 md:px-6 text-center py-20">
            <div class="animate-fade-in max-w-4xl mx-auto">
                <p class="text-gold-400/80 text-sm md:text-lg tracking-widest uppercase font-light mb-3 md:mb-4" x-text="$store.lang.t.hero.badge"></p>
                <h1 class="font-serif text-5xl sm:text-6xl md:text-8xl lg:text-9xl font-black text-white mb-2 md:mb-4 tracking-tight">
                    Solémia
                </h1>
                <p class="text-xl md:text-3xl text-olive-200 font-serif italic font-light mb-4 md:mb-8 px-4" x-text="$store.lang.t.hero.subtitle"></p>
                <p class="text-base md:text-xl text-olive-300/80 max-w-2xl mx-auto mb-8 md:mb-12 leading-relaxed font-light px-4" x-html="$store.lang.t.hero.desc"></p>
                <div class="flex flex-wrap justify-center gap-3 md:gap-4 px-4">
                    <a href="#features" class="inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-white px-6 md:px-8 py-3 md:py-3.5 rounded-full text-sm md:text-base font-semibold transition-all duration-300 shadow-xl shadow-gold-500/30 hover:shadow-2xl hover:shadow-gold-500/40 hover:scale-105">
                        <span x-text="$store.lang.t.hero.cta"></span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white border border-white/20 px-6 md:px-8 py-3 md:py-3.5 rounded-full text-sm md:text-base font-medium transition-all duration-300 hover:scale-105">
                            <i class="fas fa-lock-open"></i>
                            <span x-text="$store.lang.t.hero.login"></span>
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <div class="absolute bottom-4 md:bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-olive-400 text-lg md:text-xl"></i>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-16 md:py-24 bg-cream">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="text-center mb-10 md:mb-16">
                <p class="text-gold-600 font-medium uppercase tracking-widest text-xs md:text-sm mb-3" x-text="$store.lang.t.features.badge"></p>
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-olive-900 px-4" x-text="$store.lang.t.features.title"></h2>
                <p class="text-gray-500 mt-4 max-w-2xl mx-auto px-4" x-text="$store.lang.t.features.desc"></p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
                <template x-for="(item, i) in $store.lang.t.features.items" :key="i">
                    <div class="group bg-white rounded-2xl p-6 md:p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-olive-100 hover:border-olive-300 hover:-translate-y-1">
                        <div class="w-12 h-12 md:w-14 md:h-14 bg-olive-100 rounded-xl flex items-center justify-center mb-4 md:mb-5 group-hover:bg-olive-500 transition-colors duration-300">
                            <i :class="['fas', icons[i]]" class="text-xl md:text-2xl text-olive-600 group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-olive-800 mb-2 md:mb-3" x-text="item.title"></h3>
                        <p class="text-sm md:text-base text-gray-500 leading-relaxed" x-text="item.desc"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 md:py-24 bg-gradient-to-br from-olive-900 via-olive-800 to-olive-950 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.04]">
            <div class="absolute top-1/2 left-1/3 w-80 h-80 bg-gold-400 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 md:px-6 text-center">
            <p class="text-gold-400/80 font-medium uppercase tracking-widest text-xs md:text-sm mb-3 md:mb-4" x-text="$store.lang.t.cta.badge"></p>
            <h2 class="font-serif text-3xl md:text-5xl font-bold text-white mb-4 md:mb-6 px-4" x-text="$store.lang.t.cta.title"></h2>
            <p class="text-olive-200/80 text-base md:text-lg mb-8 md:mb-10 max-w-2xl mx-auto px-4" x-text="$store.lang.t.cta.desc"></p>
            @guest
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-white px-8 md:px-10 py-3.5 md:py-4 rounded-full text-base md:text-lg font-semibold transition-all duration-300 shadow-2xl shadow-gold-500/30 hover:shadow-gold-500/40 hover:scale-105">
                    <i class="fas fa-rocket"></i>
                    <span x-text="$store.lang.t.cta.cta"></span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-gold-500 hover:bg-gold-600 text-white px-8 md:px-10 py-3.5 md:py-4 rounded-full text-base md:text-lg font-semibold transition-all duration-300 shadow-2xl shadow-gold-500/30 hover:shadow-gold-500/40 hover:scale-105">
                    <i class="fas fa-arrow-right"></i>
                    <span x-text="$store.lang.t.cta.cta_in"></span>
                </a>
            @endguest
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-olive-950 py-8 md:py-12 border-t border-olive-800/50">
        <div class="max-w-7xl mx-auto px-4 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 md:gap-6 text-center md:text-left">
                <div>
                    <span class="font-serif text-xl md:text-2xl font-bold text-white">Solemia</span>
                    <p class="text-olive-400 text-xs md:text-sm mt-1" x-text="$store.lang.t.footer.subtitle"></p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 md:gap-6 text-olive-400 text-xs md:text-sm">
                    <span class="flex items-center gap-2"><i class="fas fa-code"></i> <span x-text="$store.lang.t.footer.made"></span></span>
                    <span class="flex items-center gap-2"><i class="fas fa-heart text-red-400"></i> <span x-text="$store.lang.t.footer.culture"></span></span>
                </div>
                <div class="text-olive-500 text-xs">
                    &copy; {{ date('Y') }} Solemia. <span x-text="$store.lang.t.footer.rights"></span>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script>
        const trans = {
            es: {
                nav: { accedi: 'Acceder', registrati: 'Registrarse', entrar: 'Entrar al Sistema' },
                hero: { badge: '🍝 Sistema para Restaurantes', title: 'Solemia', subtitle: '"El corazón de tu restaurante"', desc: 'Un sistema POS que captura la esencia de la gestión de tu restaurante,<br>transformando cada orden en una sinfonía de sabores.', cta: 'Descubrir más', login: 'Acceder al Sistema' },
                features: { badge: '🇪🇨 Nuestra Oferta', title: 'Todo para tu restaurante', desc: 'Un ecosistema completo para la gestión de tu restaurante, desde la cocina hasta la cuenta.', items: [
                    { title: 'POS & Sala', desc: 'Gestión de comandas en tiempo real, mapa de mesas interactivo y flujo ordenado de sala a cocina.' },
                    { title: 'Cocina (KDS)', desc: 'Display en tiempo real para cocina. Cada plato, cada modificación, cada urgencia — todo bajo control.' },
                    { title: 'Menú & Productos', desc: 'Catálogo digital con categorías, modificadores, combos y promociones. Precios especiales para happy hour y delivery.' },
                    { title: 'Caja & Pagos', desc: 'Apertura y cierre de caja, pagos mixtos, división de cuenta, propina y facturación electrónica SRI.' },
                    { title: 'Inventario', desc: 'Control de stock en tiempo real, recetas con costo automático, alertas de stock mínimo y gestión de proveedores.' },
                    { title: 'Reportes & Analytics', desc: 'Dashboard con KPI en tiempo real, ventas por hora, ranking de productos, rendimiento de meseros y más.' },
                    { title: 'Usuarios & Roles', desc: 'Gestión avanzada con permisos granulares, PIN para POS, audit log y autenticación multi-rol.' },
                    { title: 'WhatsApp Marketing', desc: 'Campañas promocionales, catálogo digital, chatbot automático y CRM integrado con Meta Business.' },
                    { title: 'Notificaciones', desc: 'Alertas in-app, email y WhatsApp para stock crítico, comandas listas, errores SRI y cierre de caja.' },
                ]},
                cta: { badge: '🇪🇨 Bienvenido a bordo', title: '¿Listo para transformar tu restaurante?', desc: '"Oohh Solemia de mi corazón" — deja que la magia de la cocina italiana se encuentre con el poder de la tecnología.', cta: 'Comenzar ahora — ¡Es gratis!', cta_in: 'Entrar al sistema' },
                footer: { subtitle: 'Sistema POS para Restaurantes', made: 'Hecho con amor en Ecuador', culture: 'Para la cultura italiana', rights: 'Todos los derechos reservados.' },
            },
            it: {
                nav: { accedi: 'Accedi', registrati: 'Registrati', entrar: 'Entra nel Sistema' },
                hero: { badge: '🍝 Sistema di Ristorante', title: 'Solemia', subtitle: '"¡Oh, Solémia de mi corazón!"', desc: 'Un sistema POS che cattura l\'essenza della ristorazione,<br>trasformando ogni ordine in una sinfonia di sapori.', cta: 'Scopri di più', login: 'Accedi al Sistema' },
                features: { badge: '🇮🇹 La Nostra Offerta', title: 'Tutto per il tuo ristorante', desc: 'Un ecosistema completo per la gestione del tuo ristorante, dalla cucina al conto.', items: [
                    { title: 'POS & Sala', desc: 'Gestione delle comande in tempo reale, mappa dei tavoli interattiva e flusso ordinato dalla sala alla cucina.' },
                    { title: 'Cucina (KDS)', desc: 'Display in tempo reale per la cucina. Ogni piatto, ogni modifica, ogni urgenza — tutto sotto controllo.' },
                    { title: 'Menu & Prodotti', desc: 'Catalogo digitale con categorie, modificatori, combo e promozioni. Prezzi speciali per happy hour e delivery.' },
                    { title: 'Cassa & Pagamenti', desc: 'Apertura e chiusura cassa, pagamenti misti, divisione del conto, mancia e fatturazione elettronica SRI.' },
                    { title: 'Inventario', desc: 'Controllo stock in tempo reale, ricette con costo automatico, alert di scorte minime e gestione fornitori.' },
                    { title: 'Report & Analytics', desc: 'Dashboard con KPI in tempo reale, vendite per ora, ranking prodotti, prestazioni dei camerieri e molto altro.' },
                    { title: 'Utenti & Ruoli', desc: 'Gestione avanzata con permessi granulari, PIN per POS, audit log e autenticazione multi-ruolo.' },
                    { title: 'WhatsApp Marketing', desc: 'Campagne promozionali, catalogo digitale, chatbot automatico e CRM integrato con Meta Business.' },
                    { title: 'Notifiche', desc: 'Alert in-app, email e WhatsApp per scorte critiche, comande pronte, errori SRI e chiusura cassa.' },
                ]},
                cta: { badge: '🇮🇹 Benvenuto a bordo', title: 'Pronto a trasformare il tuo ristorante?', desc: '"Oohh Solemia de mi corazón" — lascia che la magia della cucina italiana incontri la potenza della tecnologia.', cta: 'Inizia ora — È gratis!', cta_in: 'Entra nel sistema' },
                footer: { subtitle: 'Sistema POS per Ristoranti', made: 'Fatto con amore in Ecuador', culture: 'Per la cultura italiana', rights: 'Tutti i diritti riservati.' },
            },
            en: {
                nav: { accedi: 'Sign In', registrati: 'Register', entrar: 'Enter System' },
                hero: { badge: '🍝 Restaurant System', title: 'Solemia', subtitle: '"¡Oh, Solémia de mi corazón!"', desc: 'A POS system that captures the essence of dining,<br>transforming every order into a symphony of flavors.', cta: 'Discover more', login: 'Access the System' },
                features: { badge: '🇺🇸 Our Offering', title: 'Everything for your restaurant', desc: 'A complete ecosystem for managing your restaurant, from kitchen to check.', items: [
                    { title: 'POS & Dining', desc: 'Real-time order management, interactive table map, and seamless flow from dining room to kitchen.' },
                    { title: 'Kitchen (KDS)', desc: 'Real-time kitchen display. Every dish, every modification, every urgency — all under control.' },
                    { title: 'Menu & Products', desc: 'Digital catalog with categories, modifiers, combos and promotions. Special prices for happy hour and delivery.' },
                    { title: 'Cashier & Payments', desc: 'Cash register opening/closing, split payments, bill division, tips and SRI electronic invoicing.' },
                    { title: 'Inventory', desc: 'Real-time stock control, recipes with automatic costing, low stock alerts and supplier management.' },
                    { title: 'Reports & Analytics', desc: 'Dashboard with real-time KPIs, hourly sales, product ranking, waiter performance and more.' },
                    { title: 'Users & Roles', desc: 'Advanced management with granular permissions, POS PIN, audit log and multi-role authentication.' },
                    { title: 'WhatsApp Marketing', desc: 'Promotional campaigns, digital catalog, automated chatbot and CRM integrated with Meta Business.' },
                    { title: 'Notifications', desc: 'In-app, email and WhatsApp alerts for critical stock, ready orders, SRI errors and cash closure.' },
                ]},
                cta: { badge: '🇺🇸 Welcome aboard', title: 'Ready to transform your restaurant?', desc: '"Oohh Solemia de mi corazón" — let the magic of Italian cuisine meet the power of technology.', cta: 'Start now — It\'s free!', cta_in: 'Enter the system' },
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

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fade-in 1s ease-out forwards; }
        html { scroll-behavior: smooth; }
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
