var gulp       = require('gulp');
var root       = process.cwd();
var config     = require( root + '/gulpConfig.json');
var plugins    = require('gulp-load-plugins')(config.loadOpts);
module.exports = function() {
	return gulp.src( config.js.custom.files )
		.pipe( plugins.eslint({
			configFile: root + '/assets/js/.eslintrc.js'
		}))
		.pipe( plugins.eslint.format());
}
