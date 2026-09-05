/**
 * YUHLEZ App JS — Pure Blade, no Vite needed.
 * Uses CDN globals: axios, Trix, Chart
 */

// ── Axios Setup ─────────────────────────────────────────
if (typeof axios !== 'undefined') {
    window.axios = axios;
    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}

// ── Trix Configuration ──────────────────────────────────
if (typeof Trix !== 'undefined') {
    Trix.config.attachments.caption.enabled = false;

    Trix.config.lang = {
        text: 'Teks', bold: 'Tebal', italic: 'Miring',
        underline: 'Garis bawah', strike: 'Coret', href: 'Tautan',
        heading1: 'Judul Besar', bullet_list: 'Daftar',
        numbering_list: 'Daftar Berurut', quote: 'Kutipan',
        code: 'Kode', decreaseIndent: 'Kurangi Indentasi',
        increaseIndent: 'Tambah Indentasi', undo: 'Urungkan', redo: 'Ulangi',
    };
}

// ── Scroll Reveal Animation ──────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const revealElements = document.querySelectorAll('.reveal');
    if (revealElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        revealElements.forEach(el => observer.observe(el));
    }
});

// ── Tilt Effect on Cards ─────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tilt-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
});

// ── Parallax scroll effect for hero section ─────────────
document.addEventListener('DOMContentLoaded', () => {
    const heroSection = document.querySelector('.bg-zinc-950.text-white.relative');
    if (!heroSection) return;

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        if (scrollY > 600) return;

        const particles = heroSection.querySelectorAll('.particle');
        particles.forEach((p, i) => {
            p.style.transform = `translateY(${100 - scrollY * (0.3 + i * 0.05)}vh) scale(${0.5 + scrollY * 0.001})`;
        });

        const heroCard = heroSection.querySelector('.hidden.lg\\:block');
        if (heroCard) {
            heroCard.style.transform = `translateY(${scrollY * 0.08}px)`;
        }
    });
});

// ── Smooth magnetic button hover ────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.magnetic-btn').forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.15}px, ${y * 0.15}px) scale(1.02)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = '';
        });
    });
});

// ── Counter animation for stats ─────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.count-up').forEach(el => {
        const target = parseInt(el.dataset.target || el.textContent, 10);
        if (isNaN(target) || target <= 0) return;
        el.textContent = '0';

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    let current = 0;
                    const step = Math.ceil(target / 40);
                    const interval = setInterval(() => {
                        current += step;
                        if (current >= target) {
                            current = target;
                            clearInterval(interval);
                        }
                        el.textContent = current;
                    }, 30);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        observer.observe(el);
    });
});

// ── Auto-init WYSIWYG Trix for data-wysiwyg elements ────
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Trix === 'undefined') return;

    document.querySelectorAll('[data-wysiwyg]').forEach(el => {
        const hiddenInput = document.getElementById(el.dataset.wysiwyg);
        if (!hiddenInput) return;

        // Create Trix input
        const trixInput = document.createElement('input');
        trixInput.id = el.id + '-trix';
        trixInput.setAttribute('type', 'hidden');
        trixInput.setAttribute('value', hiddenInput.value || '');

        // Create trix-editor element
        const editor = document.createElement('trix-editor');
        editor.setAttribute('input', trixInput.id);
        editor.setAttribute('placeholder', el.dataset.placeholder || 'Tulis konten di sini...');
        editor.classList.add(
            'w-full', 'min-h-[160px]', 'rounded-b-xl', 'border', 'border-zinc-300',
            'border-t-0', 'bg-white', 'px-4', 'py-3', 'text-sm',
            'focus:ring-2', 'focus:ring-yellow-400', 'focus:border-transparent', 'outline-none'
        );

        // Container styling
        el.classList.add('wysiwyg-trix');
        el.appendChild(trixInput);
        el.appendChild(editor);

        // Sync Trix → hidden input on change
        editor.addEventListener('trix-change', () => {
            hiddenInput.value = trixInput.value;
        });

        // Load initial content if exists
        if (hiddenInput.value) {
            trixInput.value = hiddenInput.value;
            editor.loadHTML(hiddenInput.value);
        }
    });
});
