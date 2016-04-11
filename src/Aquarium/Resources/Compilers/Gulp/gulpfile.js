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


var source = {
	js:		JSON.parse(argv.js || '[]'),
	css:	JSON.parse(argv.css || '[]')
};

var target = {
	js:		argv["js-target"] || '',
	css:	argv["css-target"] || '',
	dir:	argv.dir || ''
};


gulp.task('default', function () {
	return gutil.log('Gulp is running');
});


gulp.task('test', function () {

	gulp.src(source.css)
		.pipe(sass())
		//.pipe(cssmin())
		.pipe(rename('test.css'))
		.pipe(gulp.dest('./'));
});

gulp.task('build', function () {

	// Compile css.
	if (source.css.length > 0) {
		gulp.src(source.css)
			.pipe(concat(target.css))
			.pipe(cssmin())
			.pipe(gulp.dest(target.dir));
	}
	
	// Compile scripts.
	if (source.js.length > 0) {
		gulp.src(source.js)
			.pipe(concat(target.js))
			.pipe(uglify())
			.pipe(gulp.dest(target.dir));
	}
});