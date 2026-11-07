import './bootstrap';
import Alpine from 'alpinejs';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';

// Initialize Lenis with custom slow/smooth settings
const lenis = new Lenis({
    autoRaf: true,
    duration: 2.0,        // Higher duration makes scrolling slower and more cinematic
    wheelMultiplier: 0.8, // Lower number moves less distance per wheel tick
});

lenis.on('scroll', (e) => {
    // console.log(e);
});

window.Alpine = Alpine;
Alpine.start();
