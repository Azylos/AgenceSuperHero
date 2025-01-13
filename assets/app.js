import './styles/app.css';
import createGlobe from 'cobe';

console.clear();

document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('cobeglobe-canvas');
    if (!canvas) return;

    // Récupérer les données de missions depuis l'élément "missions-data"
    const missionsDataElement = document.getElementById('missions-data');
    
    // Vérifier si l'élément existe
    if (missionsDataElement) {
        let missionsData;
        try {
            missionsData = JSON.parse(missionsDataElement.textContent);
        } catch (e) {
            console.error('Error parsing missions data:', e);
            missionsData = [];  // Si les données sont mal formées, on utilise un tableau vide
        }

        // Vérifier si missionsData est bien un tableau
        if (Array.isArray(missionsData)) {
            // Filtrer les missions invalides (latitude ou longitude null)
            missionsData = missionsData.filter(mission => mission.latitude && mission.longitude);

            if (missionsData.length === 0) {
                console.warn('No valid mission data available.');
                return;  // Si aucune mission valide, ne pas initialiser le globe
            }

        } else {
            console.error('missionsData is not an array:', missionsData);
            return;  // Si ce n'est pas un tableau, on arrête l'exécution
        }

        let phi = 0;  // Angle de rotation automatique
        let isDragging = false;
        let startX = 0;
        let velocity = 0;  // Vitesse de rotation
        let width = canvas.offsetWidth;

        // Création du globe avec les marqueurs
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
            markers: missionsData.map(mission => ({
                location: [mission.latitude, mission.longitude],
                size: 0.05,  // Taille du marqueur
                label: mission.title  // Nom de la mission
            })),
            onRender: (state) => {
                state.phi = phi;
                phi += 0.005 + velocity;  // Ajouter la vitesse de drag
                phi %= 2 * Math.PI;  // Garder phi entre 0 et 2 PI
                state.width = width * 2;
                state.height = width * 2;
                velocity *= 0.95;  // Réduction progressive de la vitesse
            }
        });

        setTimeout(() => {
            canvas.style.opacity = '1';
        });

        // Fonction pour gérer le redimensionnement du canvas
        const onResize = () => {
            if (canvas) {
                width = canvas.offsetWidth;
                canvas.width = width * 2;
                canvas.height = width * 2;
            }
        };
        window.addEventListener('resize', onResize);
        onResize();

        // Gestion du drag de la souris sur le globe
        const onMouseDown = (event) => {
            isDragging = true;
            startX = event.clientX;
        };

        const onMouseMove = (event) => {
            if (isDragging) {
                const deltaX = event.clientX - startX;
                velocity = -deltaX * 0.002;  // Ajuster la sensibilité
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
    } 
});
