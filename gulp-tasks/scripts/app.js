var gulp       = require('gulp');
var root       = process.cwd();
var config     = require( root + '/gulpConfig.json');
var plugins    = require('gulp-load-plugins')(config.loadOpts);
module.exports = function() {
	return gulp.src( [root + '/assets/js/custom.js', root + '/assets/js/vendor.js'] )
		.pipe( plugins.plumber() )
		.pipe( plugins.concat( 'app.js' ))
		.pipe( gulp.dest( root + '/assets/js/' ))
		.pipe( plugins.rename( {
			basename: 'app',
			suffix: '.min'
		}))
		.pipe( plugins.uglify() )
		.pipe( plugins.plumber.stop() )
		.pipe( gulp.dest( root + '/assets/js/' ) )
		.pipe( plugins.notify({ message: 'Message', onLast: true }));
}
