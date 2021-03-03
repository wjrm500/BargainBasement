var gulp = require('gulp');
var sass = require('gulp-sass');
var concat = require('gulp-concat')

function buildCss() {
  return gulp.src('assets/css/*.scss')
    .pipe(sass())
    .pipe(concat('style.css'))
    .pipe(gulp.dest('public/css/'));
}

function buildJs() {
  return gulp.src('assets/js/*.js')
    // .pipe(concat('script.js'))
    .pipe(gulp.dest('public/js/'));
}

function build(done) {
  buildCss();
  buildJs();
  done();
}

exports.build = build;

exports.watch = function() {
  gulp.watch(
    [
      'assets/css/*.scss',
      'assets/js/*.js'
    ],
    {
      ignoreInitial: false
    },
    build
  );
}