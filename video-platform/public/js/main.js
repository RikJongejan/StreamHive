// =====================================================================
// main.js - Interactie en animaties voor StreamHive (bijen-thema)
// Alles is "progressive enhancement": de site werkt ook zonder JS,
// dit voegt alleen vloeiende animaties en gemak toe.
//   1. Navbar: schaduw bij scrollen + mobiel menu
//   2. Scroll-reveal voor videokaarten (IntersectionObserver)
//   3. Like-knop: buzz-animatie bij klikken
//   4. Bestandskiezers: gekozen bestandsnaam tonen
//   5. Profiel-tabs wisselen
//   6. Verwijder-acties: kort om bevestiging vragen
// =====================================================================

document.addEventListener('DOMContentLoaded', () => {

    /* ---------- 1. Navbar ---------- */
    const nav = document.querySelector('.nav');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 10);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // mobiel menu openen/sluiten
    const toggle = document.querySelector('.nav-toggle');
    const links  = document.querySelector('.nav-links');
    const search = document.querySelector('.nav-search');
    if (toggle && links) {
        toggle.addEventListener('click', () => {
            links.classList.toggle('open');
            if (search) search.classList.toggle('open');
        });
    }

    /* ---------- 2. Scroll-reveal videokaarten ---------- */
    const cards = document.querySelectorAll('.video-card');
    if ('IntersectionObserver' in window && cards.length) {
        const io = new IntersectionObserver((entries, obs) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    // kleine vertraging per kaart geeft een mooi cascade-effect
                    setTimeout(() => entry.target.classList.add('in'), (i % 6) * 70);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        cards.forEach(card => io.observe(card));
    } else {
        // geen ondersteuning? meteen tonen
        cards.forEach(card => card.classList.add('in'));
    }

    /* ---------- 3. Like-knop buzz ---------- */
    const likeForm = document.querySelector('.like-form');
    if (likeForm) {
        const btn = likeForm.querySelector('.like-btn');
        likeForm.addEventListener('submit', () => {
            btn.classList.add('buzz');
            // de echte toggle gebeurt server-side via de form-submit
        });
    }

    /* ---------- 4. Bestandsnaam tonen bij file-inputs ---------- */
    document.querySelectorAll('.filefield input[type=file]').forEach(input => {
        input.addEventListener('change', () => {
            const label = input.closest('.filefield').querySelector('.file-name');
            if (label) {
                label.textContent = input.files.length
                    ? input.files[0].name
                    : label.dataset.placeholder || 'Geen bestand gekozen';
            }
        });
    });

    /* ---------- 5. Profiel-tabs ---------- */
    const tabs = document.querySelectorAll('.tab');
    if (tabs.length) {
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.tab;
                tabs.forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById(target)?.classList.add('active');
            });
        });
    }

    /* ---------- 6. Bevestiging bij verwijderen ---------- */
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm(form.dataset.confirm)) e.preventDefault();
        });
    });

});
