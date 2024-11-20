/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            fontFamily: {
                poppins: ['Poppins', 'sans-serif'],
                grotesk: ['Grotesk', 'sans-serif'],
                headland: ['Headland', 'sans-serif'],
            },
            backgroundImage: {
                'footer': "url('/images/logo-gais-carabiniers-bernissart.webp')",
            },
            transitionProperty: {
                'spacing': 'margin, padding, display',
                'width': 'width',
            },
            transitionDuration: {
                '2000': '2000ms',
                '5000': '5000ms',
            },
            colors: {
                "transparent": "transparent",
                "white": "#F8F9FA",
                "base": {
                    "light": "#EDE1B8", // Beige légèrement plus clair pour la base
                    "dark": "#1B1B1B",  // Proche du noir pour une teinte de base sobre en mode sombre
                },
                "surface": {
                    "light": "#eee9db", // Beige plus prononcé pour les sections et cartes en mode clair
                    "dark": "#3E3E3E",  // Une teinte foncée pour le mode sombre
                    "secondary": "#A53A1A", // Rouge atténué pour un contraste esthétique
                },
                "content": {
                    "primary": {
                        "light": "#1B1B1B",  // Vert pour les titres et textes principaux
                        "dark": "#eee9db",   // Version plus sombre du vert pour le mode sombre
                        // "light": "#3B7C45",  // Vert pour les titres et textes principaux
                        // "dark": "#2A5A30",   // Version plus sombre du vert pour le mode sombre
                    },
                    "secondary": "#3B7C45", // Jaune vif pour les éléments de mise en avant
                    "highlight":{
                        "light": "#3B7C45",
                        "dark": "#F2C029"
                    }, // Jaune vif pour les éléments de mise en avant
                    // "secondary": "#F2C029", // Jaune vif pour les éléments de mise en avant
                },
                "secondary": "#3B7C45", // Jaune vif pour les éléments de mise en avant

            },
        },
    },
    plugins: [],
}
