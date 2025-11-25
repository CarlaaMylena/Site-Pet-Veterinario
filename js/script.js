const API_URL = 'backend/';

const menu = document.getElementById('menuToggle');
const nav = document.getElementById('navbar');

if (menu && nav) {
    menu.addEventListener('click', () => {
        nav.classList.toggle('active');
        const icon = menu.querySelector('i');
        if (icon) {
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        }
    });
}

document.querySelectorAll('.navbar a').forEach(link => {
    link.addEventListener('click', (e) => {
        if (nav) {
            nav.classList.remove('active');
            const icon = menu?.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    });
});

document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href && href !== '#') {
            e.preventDefault();
            const id = href.substring(1);
            const el = document.getElementById(id);
            
            if (el) {
                const y = el.getBoundingClientRect().top + window.pageYOffset - 80;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        }
    });
});

window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('section[id]');
    const pos = window.scrollY + 100;

    sections.forEach(sec => {
        const top = sec.offsetTop;
        const h = sec.offsetHeight;
        const id = sec.getAttribute('id');
        
        if (pos >= top && pos < top + h) {
            document.querySelectorAll('.navbar a').forEach(l => {
                l.classList.remove('active');
                if (l.getAttribute('href') === `#${id}`) {
                    l.classList.add('active');
                }
            });
        }
    });
});

const pets = [
    { name: 'Rex', age: '3 anos', breed: 'Labrador', gender: 'Macho', size: 'Grande', image: 'images/pet-rex.jpg', traits: ['Dócil', 'Brincalhão'] },
    { name: 'Luna', age: '2 anos', breed: 'Vira-lata', gender: 'Fêmea', size: 'Médio', image: 'images/pet-luna.jpg', traits: ['Carinhosa', 'Calma'] },
    { name: 'Thor', age: '4 anos', breed: 'Pastor Alemão', gender: 'Macho', size: 'Grande', image: 'images/pet-thor.jpg', traits: ['Protetor', 'Leal'] },
    { name: 'Mel', age: '1 ano', breed: 'Poodle', gender: 'Fêmea', size: 'Pequeno', image: 'images/pet-mel.jpg', traits: ['Alegre', 'Inteligente'] }
];

function loadPets() {
    const grid = document.getElementById('adoptionGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    pets.forEach(p => {
        const card = document.createElement('div');
        card.className = 'adoption-card';
        card.innerHTML = `
            <img src="${p.image}" alt="${p.name}">
            <div class="adoption-info">
                <h3>${p.name}</h3>
                <p><strong>Idade:</strong> ${p.age}</p>
                <p><strong>Raça:</strong> ${p.breed}</p>
                <p><strong>Gênero:</strong> ${p.gender}</p>
                <p><strong>Porte:</strong> ${p.size}</p>
                <div class="pet-traits">
                    ${p.traits.map(t => `<span class="trait">${t}</span>`).join('')}
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

function showLoginModal() {
    const m = document.getElementById('loginModal');
    if (m) m.classList.add('active');
}

function closeLoginModal() {
    const m = document.getElementById('loginModal');
    if (m) m.classList.remove('active');
}

function showRegisterModal() {
    closeLoginModal();
    alert('Cadastro em breve!');
}

window.addEventListener('click', (e) => {
    const m = document.getElementById('loginModal');
    if (m && e.target === m) closeLoginModal();
});

const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = loginForm.querySelector('button[type="submit"]');
        if (!btn) return;
        
        btn.disabled = true;
        btn.textContent = 'ENTRANDO...';
        
        try {
            await new Promise(r => setTimeout(r, 1000));
            alert('Login realizado!');
            closeLoginModal();
        } catch (err) {
            alert('Erro ao fazer login');
        } finally {
            btn.disabled = false;
            btn.textContent = 'ENTRAR';
        }
    });
}

const appointForm = document.getElementById('appointmentForm');
if (appointForm) {
    appointForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = Object.fromEntries(new FormData(appointForm));
        
        if (!data.petName || !data.ownerName || !data.phone || !data.email || !data.service || !data.date) {
            alert('Preencha todos os campos!');
            return;
        }
        
        const d = new Date(data.date);
        const now = new Date();
        now.setHours(0, 0, 0, 0);
        
        if (d < now) {
            alert('Escolha uma data futura!');
            return;
        }
        
        const btn = appointForm.querySelector('button[type="submit"]');
        if (!btn) return;
        
        btn.disabled = true;
        btn.textContent = 'AGENDANDO...';
        
        try {
            const res = await fetch(API_URL + 'agendar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await res.json();
            alert(result.message || 'Agendamento realizado!');
            if (result.success) appointForm.reset();
        } catch (err) {
            alert('Erro ao agendar');
        } finally {
            btn.disabled = false;
            btn.textContent = 'AGENDAR CONSULTA';
        }
    });
}

const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const data = Object.fromEntries(new FormData(contactForm));
        
        if (!data.name || !data.email || !data.phone || !data.message) {
            alert('Preencha todos os campos!');
            return;
        }
        
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) {
            alert('E-mail inválido!');
            return;
        }
        
        const btn = contactForm.querySelector('button[type="submit"]');
        if (!btn) return;
        
        btn.disabled = true;
        btn.textContent = 'ENVIANDO...';
        
        try {
            const res = await fetch(API_URL + 'contato.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await res.json();
            alert(result.message || 'Mensagem enviada!');
            if (result.success) contactForm.reset();
        } catch (err) {
            alert('Erro ao enviar');
        } finally {
            btn.disabled = false;
            btn.textContent = 'ENVIAR MENSAGEM';
        }
    });
}

function maskPhone(input) {
    let v = input.value.replace(/\D/g, '');
    if (v.length <= 10) {
        v = v.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
    } else {
        v = v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    }
    input.value = v;
}

document.querySelectorAll('input[type="tel"]').forEach(inp => {
    inp.addEventListener('input', (e) => maskPhone(e.target));
});

const dateInp = document.getElementById('date');
if (dateInp) {
    const t = new Date();
    const tmr = new Date(t);
    tmr.setDate(tmr.getDate() + 1);
    dateInp.setAttribute('min', tmr.toISOString().split('T')[0]);
}

function initAnim() {
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) e.target.classList.add('animated');
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -100px 0px' });

    document.querySelectorAll('.section-title, .service-card, .adoption-card, .contact-item, .about-content, .find-content').forEach(el => {
        el.classList.add('animate-on-scroll');
        obs.observe(el);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadPets();
    initAnim();
});