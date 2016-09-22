'use strict';


const concat = require('gulp-concat');


module.exports = function concatProcessor(pipeline, params)
{
    return pipeline.pipe(concat(params.target));
};