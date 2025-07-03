// FADE‑OUT antes de navegar para a tela de login
const loginLink = document.getElementById('link-login');
if (loginLink) {
    loginLink.addEventListener('click', e => {
        e.preventDefault();
        document.body.classList.add('page-exit');
        setTimeout(() => {
            window.location.href = loginLink.href;
        }, 400);
    });
}

// NAVBAR SCROLL
window.addEventListener('scroll', () => {
    document.querySelector('.nav')
        .classList.toggle('scrolled', window.scrollY > 50);
});

// SMOOTH SCROLL
const smoothScrollToProducts = e => {
    e.preventDefault();
    document.getElementById('products')
        .scrollIntoView({ behavior: 'smooth' });
};
const smoothScrollToCategories = e => {
    e.preventDefault();
    document.getElementById('categories')
        .scrollIntoView({ behavior: 'smooth' });
};
const smoothScrollToHero = e => {
    e.preventDefault();
    document.getElementById('hero')
        .scrollIntoView({ behavior: 'smooth' });
};

document.querySelector('a[href="#products"].btn--primary')
    ?.addEventListener('click', smoothScrollToProducts);
document.querySelector('.nav__list a[href="#products"]')
    ?.addEventListener('click', smoothScrollToProducts);
document.querySelector('.nav__list a[href="#categories"]')
    ?.addEventListener('click', smoothScrollToCategories);
document.querySelector('.nav__list a[href="#hero"]')
    ?.addEventListener('click', smoothScrollToHero);

// LAZY LOAD IMAGES
const lazyImgs = document.querySelectorAll('img.lazy');
const io = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.src = e.target.dataset.src;
            io.unobserve(e.target);
        }
    });
});
lazyImgs.forEach(img => io.observe(img));
