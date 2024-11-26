/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

import createGlobe from 'cobe';
/*
 * NavBar
 */
console.clear();
const list = document.querySelectorAll('.list');
const nav = document.querySelector('.navigation');
list.forEach(item => item.addEventListener('click', function(e){
    list.forEach(li => li.classList.remove('active'));
    e.currentTarget.classList.add('active');
}));

/*
 * Globe
 */
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('cobeglobe-canvas');
    if (!canvas) return;

    let phi = 0; // Angle de rotation automatique
    let isDragging = false;
    let startX = 0;
    let velocity = 0; // Vitesse de rotation suite au drag
    let width = canvas.offsetWidth;

    // Création du globe
    const globe = createGlobe(canvas, {
        devicePixelRatio: 2,
        width: width * 2,
        height: width * 2,
        phi: 0,
        theta: 0.3,
        dark: 1,
        diffuse: 3,
        mapSamples: 16000,
        mapBrightness: 1.2,
        baseColor: [1, 1, 1],
        markerColor: [251 / 255, 100 / 255, 21 / 255],
        glowColor: [1.2, 1.2, 1.2],
        markers: [],
        onRender: (state) => {
            // Ajouter la vitesse obtenue par le drag à la rotation automatique
            state.phi = phi;
            phi += 0.005 + velocity; // Ajouter la vitesse de drag
            phi %= 2 * Math.PI; // Garder phi entre 0 et 2 PI
            state.width = width * 2;
            state.height = width * 2;

            // Décélérer la rotation obtenue par le drag
            velocity *= 0.95; // Réduction progressive de la vitesse
        }
    });

    setTimeout(() => {
        canvas.style.opacity = '1';
    });

    // Gestion du redimensionnement du canvas
    const onResize = () => {
        if (canvas) {
            width = canvas.offsetWidth;
            canvas.width = width * 2;
            canvas.height = width * 2;
        }
    };
    window.addEventListener('resize', onResize);
    onResize();

    // Gestion du drag de la souris
    const onMouseDown = (event) => {
        isDragging = true;
        startX = event.clientX;
    };

    const onMouseMove = (event) => {
        if (isDragging) {
            const deltaX = event.clientX - startX;
            velocity = -deltaX * 0.002; // Ajuster la sensibilité
            startX = event.clientX;
        }
    };

    const onMouseUp = () => {
        isDragging = false;
    };

    // Ajouter des écouteurs pour les événements de souris
    canvas.addEventListener('mousedown', onMouseDown);
    window.addEventListener('mousemove', onMouseMove);
    window.addEventListener('mouseup', onMouseUp);

    // Cleanup lors de la fermeture de la page
    window.addEventListener('beforeunload', () => {
        globe.destroy();
        window.removeEventListener('resize', onResize);
        canvas.removeEventListener('mousedown', onMouseDown);
        window.removeEventListener('mousemove', onMouseMove);
        window.removeEventListener('mouseup', onMouseUp);
    });
});
