var gulp       = require('gulp');
var root       = process.cwd();
var config     = require( root + '/gulpConfig.json');
var plugins    = require('gulp-load-plugins')(config.loadOpts);
module.exports = function() {
	return gulp.src( config.scss.files )
		.pipe( plugins.stylelint({
			configFile: root + '/assets/scss/.stylelintrc',
			reporters: [
				{formatter: 'string', console: true}
			]
		}));
}
