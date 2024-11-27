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
    extend: {},
  },
  plugins: [
    require('flowbite/plugin')
]
}

