'use strict';


var gulp 	= require('gulp');
var gutil 	= require('gulp-util');
var uglify 	= require('gulp-uglify');
var concat 	= require('gulp-concat');
var cssmin 	= require('gulp-minify-css');
var rename 	= require('gulp-rename');
var sass 	= require('gulp-sass');
var clean 	= require('gulp-clean');
const test	= require('./test');

var argv	= require('yargs').argv;

var targetDir = argv.targetDir;
var commands = JSON.parse(argv.commands || '[]');


gulp.task('default', function () {
	return gutil.log('Gulp is running');
});


gulp.task('test', function () {
	var pipeline = gulp.src(["a", "b"]);
	
	test(pipeline);
});


gulp.task('build', function () {
	var commandsCount = commands.length,
		currCommand,
		target,
		action,
		source,
		pipeline;
	
	if (commands.length === 0) {
		return;
	}
	
	for (var currCommandIndex = 0; currCommandIndex < commandsCount; currCommandIndex++) {
		currCommand = commands[currCommandIndex];
		
		action = currCommand.action;
		target = currCommand.target || false;
		source = currCommand.source || false;
		
		if (currCommand.source === false) {
			continue;
		}
		
		pipeline = gulp.src(source);
		
		switch (action) {
			case 'scss-process':
				pipeline = pipeline.pipe(sass());
				break;
				
			case 'concatenate':
				target = target.substr(target.lastIndexOf('/') + 1);
				pipeline = pipeline.pipe(concat(target));
				break;
			
			case 'css-minify':
				pipeline = pipeline.pipe(cssmin());
				break;
			
			case 'js-minify':
				pipeline = pipeline.pipe(uglify());
				break;
		}
		
		pipeline.pipe(gulp.dest(targetDir));
	}
});