/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./templates/**/*.html.twig", // Path to all Twig templates
    "./assets/**/*.js",           // Path to your JavaScript files if you are using custom JavaScript
    "./src/**/*.js",              // Include JS files in your source directory if any
    "./templates/**/*.twig",       // Alternative path for any Twig files that might not use `.html.twig`
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        'neon-pink': '#ff00ff',
        'cyber-blue': '#00ffff',
        'dark-gray': '#333333',
      },
      boxShadow: {
        'neon': '0 0 15px 3px rgba(255, 0, 255, 0.5)',  // effet néon rose
        'cyber': '0 0 15px 3px rgba(0, 255, 255, 0.5)',  // effet néon bleu
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
]
}

