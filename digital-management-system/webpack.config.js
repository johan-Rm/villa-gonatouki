var Encore = require('@symfony/webpack-encore');
var CopyWebpackPlugin = require('copy-webpack-plugin'); // this line tell to webpack to use the plugin
var dotenv = require('dotenv');
const env = dotenv.config();

Encore

    // define the environment variables in your frontend code
    .configureDefinePlugin(options => {
        const env = dotenv.config();

        if (env.error) {
            throw env.error;
        }

        options['process.env'].URL_CDN = JSON.stringify(env.parsed.URL_CDN);
    })

    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    // .setManifestKeyPrefix('build/')

    .copyFiles({
        from: './assets/images',
       
        // optional target path, relative to the output dir
        to: 'images/[path][name].[ext]',
       
        // if versioning is enabled, add the file hash too
        //to: 'images/[path][name].[hash:8].[ext]',
       
        // only copy files matching this pattern
        //pattern: /\.(png|jpg|jpeg)$/
    })
    
    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('cms', './assets/js/cms.js')
    .addEntry('login', './assets/js/login.js')
    // .addEntry('dropzone', './assets/js/dropzone.js')
    // .addEntry('jquery-menu-editor', './assets/js/jquery-menu-editor.js')

    .enableBuildNotifications()

    // .addPlugin(new CopyWebpackPlugin([
    //     { from: './assets/images', to: 'images' }
    // ]))

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    // .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning()

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    // .enablgit clone https://github.com/javiereguiluz/easy-admin-demoeTypeScriptLoader()

    // uncomment if you're having problems with a jQuery plugin
    .autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')


;

// if (Encore.isProduction()) {
//     Encore.setPublicPath(env.parsed.URL_CDN + '/build');
//     Encore.setManifestKeyPrefix('build/');
// }

module.exports = Encore.getWebpackConfig();
