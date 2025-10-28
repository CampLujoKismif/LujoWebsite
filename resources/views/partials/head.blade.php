<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite('resources/css/app.css')

<!-- Set default to 'light' if no preference (but allow system if explicitly chosen) -->
<script>
    const fluxTheme = localStorage.getItem('flux.appearance');
    if (!fluxTheme) {
        localStorage.setItem('flux.appearance', 'light');
    }
    
    // CRITICAL: Apply theme immediately to prevent flash and ensure persistence
    function applyThemeNow(theme) {
        if (theme === 'dark') {
            document.body.classList.add('dark');
        } else if (theme === 'light') {
            document.body.classList.remove('dark');
        } else if (theme === 'system') {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.body.classList.toggle('dark', isDark);
        }
    }
    
    // Apply on page load
    if (document.body) {
        applyThemeNow(fluxTheme || 'light');
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            applyThemeNow(localStorage.getItem('flux.appearance') || 'light');
        });
    }
</script>

@fluxAppearance

<!-- Theme management functions using Flux's native API -->
<script>
    // Toggle between light/dark (skips system)
    window.toggleTheme = function() {
        const current = localStorage.getItem('flux.appearance');
        const newTheme = (current === 'dark') ? 'light' : 'dark';
        
        if (window.Flux && window.Flux.applyAppearance) {
            window.Flux.applyAppearance(newTheme);
        } else {
            // Fallback: manually apply
            applyThemeManually(newTheme);
        }
        
        return newTheme;
    };

    // Set specific theme (light, dark, or system)
    window.setTheme = function(theme) {
        console.log('setTheme called with:', theme);
        
        if (window.Flux && window.Flux.applyAppearance) {
            console.log('Using Flux.applyAppearance');
            window.Flux.applyAppearance(theme);
        } else {
            console.log('Using manual fallback');
            applyThemeManually(theme);
        }
        
        return theme;
    };

    window.getTheme = function() {
        return localStorage.getItem('flux.appearance') || 'light';
    };
    
    // Manual theme application fallback
    function applyThemeManually(theme) {
        localStorage.setItem('flux.appearance', theme);
        
        if (theme === 'dark') {
            document.body.classList.add('dark');
        } else if (theme === 'light') {
            document.body.classList.remove('dark');
        } else if (theme === 'system') {
            const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (isDark) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        }
    }
</script>
