<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Voedselbank') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:700,900|dm-sans:300,400,500" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; }
    </style>
</head>

<body class="bg-white text-gray-800 antialiased">

    <!-- NAVBAR -->
    <header class="bg-green-900 sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-amber-400 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-white">Voedselbank</span>
            </a>

            <ul class="hidden md:flex items-center gap-8 text-sm text-white/80">
                <li><a href="#over-ons" class="hover:text-white transition">Over ons</a></li>
                <li><a href="#hoe-werkt-het" class="hover:text-white transition">Hoe werkt het</a></li>
                <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
            </ul>

            <div class="flex items-center gap-3">
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-white/70 hover:text-white transition">
                            Uitloggen
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-white/70 hover:text-white transition">
                        Inloggen
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm border border-white/30 hover:border-white/50 text-white px-4 py-2 rounded-full transition">
                            Registreren
                        </a>
                    @endif
                @endauth

                <a href="#doneer" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-full transition">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    Doneer
                </a>
            </div>
        </nav>
    </header>

    <!-- HERO SECTION -->
    <section class="bg-gradient-to-br from-green-900 to-green-800 text-white py-20">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-6">
                    Samen zorgen<br>
                    <span class="text-amber-400">voor elkaar</span>
                </h1>
                <p class="text-white/80 text-lg mb-8 leading-relaxed">
                    De Voedselbank helpt gezinnen die in financiële moeilijkheden zitten door wekelijks voedselpakketten uit te delen. Iedereen verdient genoeg te eten.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#hoe-werkt-het" class="bg-amber-500 hover:bg-amber-600 text-white font-medium px-6 py-3 rounded-lg transition">
                        Hulp aanvragen
                    </a>
                    <a href="#contact" class="border border-white/50 hover:border-white text-white font-medium px-6 py-3 rounded-lg transition">
                        Meer informatie
                    </a>
                </div>
            </div>
            <div class="hidden md:grid grid-cols-2 gap-4">
                <div class="bg-white/10 rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-amber-400 mb-2">50.000+</p>
                    <p class="text-sm text-white/80">Gezinnen per week</p>
                </div>
                <div class="bg-white/10 rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-amber-400 mb-2">180</p>
                    <p class="text-sm text-white/80">Locaties Nederland</p>
                </div>
                <div class="bg-white/10 rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-amber-400 mb-2">10.000+</p>
                    <p class="text-sm text-white/80">Vrijwilligers</p>
                </div>
                <div class="bg-white/10 rounded-lg p-6 text-center">
                    <p class="text-3xl font-bold text-amber-400 mb-2">30+</p>
                    <p class="text-sm text-white/80">Jaar actief</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOE WERKT HET -->
    <section id="hoe-werkt-het" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-green-900 mb-12 text-center">Hoe werkt het?</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white rounded-lg p-8 border border-gray-200">
                    <div class="w-12 h-12 bg-green-900 text-white rounded-lg flex items-center justify-center font-bold mb-4">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-green-900 mb-3">Aanmelden</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Meld jezelf aan via een erkende hulpverlener zoals de gemeente of schuldhulpverlening.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-lg p-8 border border-gray-200">
                    <div class="w-12 h-12 bg-green-900 text-white rounded-lg flex items-center justify-center font-bold mb-4">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-green-900 mb-3">Pakket ontvangen</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Onze vrijwilligers stellen een gevarieerd pakket samen op basis van je gezinssamenstelling.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-lg p-8 border border-gray-200">
                    <div class="w-12 h-12 bg-green-900 text-white rounded-lg flex items-center justify-center font-bold mb-4">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-green-900 mb-3">Wekelijks afhalen</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Haal je pakket wekelijks op bij een van onze locaties of laat het thuisbezorgen.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- OVER ONS -->
    <section id="over-ons" class="py-20 bg-green-900 text-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Onze missie</h2>
                <p class="text-white/80 mb-4 leading-relaxed">
                    Voedsel is een recht, geen privilege. Wij geloven dat iedereen recht heeft op voldoende en gezond eten. De Voedselbank werkt samen met supermarkten, voedselproducenten en particulieren om voedseloverschotten te verwerken tot pakketten voor mensen in nood.
                </p>
                <p class="text-white/80 leading-relaxed">
                    Naast het verstrekken van pakketten werken wij aan structurele oplossingen voor armoede en helpen wij mensen weer zelfredzaam te worden.
                </p>
            </div>
            <div class="bg-white/10 rounded-lg p-8 border border-white/20">
                <p class="text-xl italic text-white/90 mb-6">
                    "Dankzij de voedselbank kon ik mijn kinderen iedere dag een warme maaltijd geven terwijl ik mijn leven weer op de rit kreeg."
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-400 rounded-full flex items-center justify-center text-green-900 font-bold">
                        M
                    </div>
                    <div>
                        <p class="font-medium">Maria, 34 jaar</p>
                        <p class="text-sm text-white/60">Amsterdam</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- DONEER -->
    <section id="doneer" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-green-900 mb-12 text-center">Jouw bijdrage maakt het verschil</h2>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg p-8 border border-gray-200 text-center">
                    <p class="text-4xl font-bold text-green-900 mb-3">€5</p>
                    <p class="text-gray-600 text-sm mb-6">Vult een ontbijt aan voor een heel gezin</p>
                    <button class="w-full bg-green-900 hover:bg-green-800 text-white font-medium py-2 rounded-lg transition">
                        Doneer nu
                    </button>
                </div>

                <div class="bg-green-900 text-white rounded-lg p-8 text-center ring-2 ring-amber-400">
                    <p class="inline-block bg-amber-400 text-green-900 text-xs font-bold px-3 py-1 rounded mb-4">Populair</p>
                    <p class="text-4xl font-bold mb-3">€25</p>
                    <p class="text-white/80 text-sm mb-6">Voorziet een gezin een week van verse groenten</p>
                    <button class="w-full bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 rounded-lg transition">
                        Doneer nu
                    </button>
                </div>

                <div class="bg-white rounded-lg p-8 border border-gray-200 text-center">
                    <p class="text-4xl font-bold text-green-900 mb-3">€50</p>
                    <p class="text-gray-600 text-sm mb-6">Helpt drie families met een compleet pakket</p>
                    <button class="w-full bg-green-900 hover:bg-green-800 text-white font-medium py-2 rounded-lg transition">
                        Doneer nu
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-green-900 mb-6">Heb je vragen?</h2>
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                Neem contact met ons op voor meer informatie over hulp, vrijwilligerswerk of donaties.
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="tel:+31123456789" class="inline-block bg-green-900 hover:bg-green-800 text-white font-medium px-8 py-3 rounded-lg transition">
                    Bel ons: +31 (0) 12 345 6789
                </a>
                <a href="mailto:info@voedselbank.nl" class="inline-block border border-green-900 hover:bg-green-50 text-green-900 font-medium px-8 py-3 rounded-lg transition">
                    Email: info@voedselbank.nl
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-green-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="font-bold mb-4">Voedselbank</h4>
                    <p class="text-white/60 text-sm">Samen zorgen voor elkaar.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Links</h4>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="#over-ons" class="hover:text-white transition">Over ons</a></li>
                        <li><a href="#hoe-werkt-het" class="hover:text-white transition">Hoe werkt het</a></li>
                        <li><a href="#doneer" class="hover:text-white transition">Doneer</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="tel:+31123456789" class="hover:text-white transition">+31 (0) 12 345 6789</a></li>
                        <li><a href="mailto:info@voedselbank.nl" class="hover:text-white transition">info@voedselbank.nl</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Volg ons</h4>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="#" class="hover:text-white transition">Facebook</a></li>
                        <li><a href="#" class="hover:text-white transition">Instagram</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/20 pt-8 text-center text-sm text-white/60">
                <p>&copy; 2026 Voedselbank. Alle rechten voorbehouden.</p>
            </div>
        </div>
    </footer>

</body>
</html>