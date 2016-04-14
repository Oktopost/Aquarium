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


var targetDir = argv.targetDir;
var commands = JSON.parse(argv.commands || '[]');


gulp.task('default', function () {
	return gutil.log('Gulp is running');
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
				pipeline.pipe(sass());
				break;
				
			case 'concatenate':
				pipeline.pipe(concat(target));
				break;
			
			case 'css-minify':
				pipeline.pipe(cssmin());
				break;
			
			case 'js-minify':
				pipeline.pipe(uglify());
				break;
		}
		
		pipeline.pipe(gulp.dest(targetDir));
	}
});