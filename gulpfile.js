var gulp = require('gulp');
var sass = require('gulp-sass');
var min  = require('gulp-minify-css');

gulp.task( 'sass', function() {
    gulp.src( 'assets/sass/style.scss' )
        .pipe( sass().on( 'error', sass.logError ) )
        .pipe( min() )
        .pipe( gulp.dest( 'assets/css/' ) );
} );

gulp.task( 'watch', function() {
    gulp.watch( 'assets/sass/**/*.scss', [ 'sass' ] );
} );

gulp.task( 'default', ['watch'] );