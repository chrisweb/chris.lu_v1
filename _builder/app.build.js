// to build run in node:
// node r.js -o app.build.js
// https://github.com/jrburke/r.js/blob/master/build/example.build.js

({

    // optimize code minified by UglifyJS
    optimize: "uglify2",
	
    //If using UglifyJS2 for script optimization, these config options can be
    //used to pass configuration values to UglifyJS2.
    //For possible `output` values see:
    //https://github.com/mishoo/UglifyJS2#beautifier-options
    //For possible `compress` values see:
    //https://github.com/mishoo/UglifyJS2#compressor-options
    uglify2: {
        //Example of a specialized config. If you are fine
        //with the default options, no need to specify
        //any of these properties.
        output: {
            beautify: false
        },
        compress: {
            sequences: false,
            global_defs: {
                DEBUG: false
            }
        },
        warnings: false,
        mangle: false
    },
	
	// or jquery mobile won't be included into the optimized file
	findNestedDependencies: true,

    baseUrl: "../public/dev",
    mainConfigFile: "../public/dev/js/main-2.1.6.js",
    include: "js/main-2.1.6",
    out: "../static/js/main-2.1.6.js",
    // almond js
    name: '../../_builder/almond',
    wrap: true

})
