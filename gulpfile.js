var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('hello', function(done) {
  console.log('Hello William');
  done();
});

gulp.task('buildCss', function() {
    return gulp.src('assets/css/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('public/css/'));
});

gulp.task('buildJs', function() {
  return gulp.src('assets/js/*.js')
      .pipe(gulp.dest('public/js/'));
});

gulp.task('build', gulp.series('buildCss', 'buildJs'));