'use strict';


const cssmin = require('gulp-minify-css');


module.exports = function cssminProcessor(pipeline, params)
{
    return pipeline.pipe(cssmin());
};