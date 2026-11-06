import '../../css/pages/index.css';

document.addEventListener('DOMContentLoaded', () => {
    if (typeof Splide !== 'undefined') {
        document.querySelectorAll('.splide').forEach(element => {
            new Splide(element, { perPage: 4, gap: '1rem', pagination: false,
                breakpoints: { 1024: { perPage: 3 }, 640: { perPage: 2 } } }).mount();
        });
    }
});
