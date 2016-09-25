'use strict';


var gulp		= require('gulp');

var gutil 		= require('gulp-util');
var uglify 		= require('gulp-uglify');
var concat 		= require('gulp-concat');
var cssmin 		= require('gulp-minify-css');
var rename 		= require('gulp-rename');
var sass 		= require('gulp-sass');
var clean 		= require('gulp-clean');
var declare 	= require('gulp-declare');
var gulpHb	 	= require('gulp-handlebars');
var handlebars	= require('handlebars');
var wrap 		= require('gulp-wrap');
var argv		= require('yargs').argv;

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
		pipeline,
		targetFileName = false;
	
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
		
		if (target) {
			var pathParts = target.split('/');
			
			targetFileName = pathParts[pathParts.length - 1];
			pathParts.pop();
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

			case 'handle-bar':
				pipeline = pipeline
					.pipe(gulpHb({ handlebars: require('handlebars') }))
					.pipe(wrap('Handlebars.template(<%= contents %>)'))
					.pipe(declare({
						namespace: 'Okt.views',
						
						// Avoid duplicate declarations
						noRedeclare: true,

						processName: function(filePath) {
							return filePath.match(/.*\/views\/(.*)\.hbs\.js/)[1].replace('/', '.');
						}
					}))
					.pipe(concat(targetFileName))
					.pipe(uglify());
				
				break;
		}
		
		pipeline.pipe(gulp.dest(targetDir));
	}
});