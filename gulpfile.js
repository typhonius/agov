var gulp = require('gulp');
var gutil = require('gulp-util');
var compass = require('gulp-compass');

var base_path = './themes/agov_zen';

var paths = {
  scripts: [base_path + '/js/**/*.js'],
  images: base_path + '/images/**/*',
  sass: base_path + '/sass/**/*.scss',
  css: base_path + '/css'
};

gulp.task('default', ['compass']);

gulp.task('compass', function() {
  return gulp.src(paths.sass)
    .pipe(compass({
      config_file: base_path + '/config.rb',
      sass: paths.sass,
      css: paths.css
    }))
    .pipe(gulp.dest('.'))
    .on('error', gutil.log);
});
