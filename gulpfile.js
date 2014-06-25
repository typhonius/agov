var gulp = require('gulp');
var sass = require('gulp-ruby-sass');

var base_path = './themes/agov_zen';

var paths = {
  scripts: [base_path + '/js/**/*.js'],
  images: base_path + '/images/**/*',
  sass: [base_path + '/.agov_styles.scss'],
  css: base_path + '/css'
};

gulp.task('default', ['compass']);

gulp.task('compass', function() {
  return gulp.src(paths.sass)
    .pipe(sass({
      sourcemap: true,
      compass: true,
      bundleExec: true
    }))
    .pipe(gulp.dest(paths.css));
});
