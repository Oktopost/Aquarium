'use strict';


const uglify = require('gulp-uglify');


module.exports = function uglifyProcessor(pipeline, params)
{
    return pipeline.pipe(uglify());
};