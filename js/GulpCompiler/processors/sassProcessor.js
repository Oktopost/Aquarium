'use strict';


const sass = require('gulp-sass');


module.exports = function sassProcessor(pipeline, params)
{
    return pipeline.pipe(sass());
};