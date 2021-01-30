var gulp = require('gulp');
var sass = require('gulp-sass');

function buildCss() {
  return gulp.src('assets/css/*.scss')
    .pipe(sass())
    .pipe(gulp.dest('public/css/'));
}

function buildJs() {
  return gulp.src('assets/js/*.js')
    .pipe(gulp.dest('public/js/'));
}

function build(done) {
  buildCss();
  buildJs();
  done();
}

exports.watch = function() {
  gulp.watch(
    [
      'assets/css/*.scss',
      'assets/js/*.js'
    ],
    build
  );
}