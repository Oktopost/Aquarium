'use strict';

var gulp 	= require('gulp');
var gutil 	= require('gulp-util');
var uglify 	= require('gulp-uglify');
var concat 	= require('gulp-concat');
var cssmin 	= require('gulp-minify-css');
var rename 	= require('gulp-rename');
var sass 	= require('gulp-sass');
var clean 	= require('gulp-clean');

var argv	= require('yargs').argv;


    
module.exports = function test(pipeline) {

	pipeline = pipeline.pipe(sass());
	pipeline = pipeline.pipe(concat("c"));
	
	pipeline.pipe(gulp.dest("./target"));
	
}