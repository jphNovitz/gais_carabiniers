/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      transitionProperty: {
        'spacing': 'margin, padding, display',
        'width': 'width',
      },
      colors: {
        "transparent": "transparent",
        "white": "#F8F9FA",
        "black": "#212529",
        "light-text-base": "#212529",
        "light-text-secondary": "#343A40",
        "dark-text-base": "#212529",
        "dark-text-secondary": "#343A40",
        "light-primary": "#ADB5BD",
        "dark-primary": "#ADB5BD",
        "light-neutral": "#F8F9FA",
        "dark-neutral": "#F8F9FA",
      },
    },
  },
  plugins: [],
}
