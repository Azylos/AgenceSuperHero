const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    // .addStyleEntry('appcss', './assets/styles/app.css') // Point d'entrée du fichier Tailwind
    // .enablePostCssLoader() // Activer PostCSS pour traiter Tailwind
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
