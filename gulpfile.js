const gulp         = require('gulp');
const concat       = require('gulp-concat');
const watch        = require('gulp-watch');
const plumber      = require('gulp-plumber');
const stylus       = require('gulp-stylus');
const cleanCSS     = require('gulp-clean-css');
const gcmq         = require('gulp-group-css-media-queries')
const autoprefixer = require('gulp-autoprefixer');
const rjs          = require('gulp-requirejs-optimize');
const glob         = require('glob');
const path         = require('path');
// const sass         = require('gulp-sass');
// var base64         = require('gulp-base64');
// const twig         = require('gulp-twig');
const prettify     = require('gulp-jsbeautifier');

/*
gulp.task('styleCompile', function () {
  gulp.src('src/styles/main.scss')
    .pipe(plumber())
    .pipe(sass({errLogToConsole: true}))
    .pipe(autoprefixer({
      "browsers": ["last 2 versions"],
      "cascade": true
    }))
    .pipe(base64({
      baseDir: 'src/',
      extensions: ['svg', 'png', 'jpg'],
      maxImageSize: 400*1024,
      deleteAfterEncoding: false,
      debug: false
    }))
    .pipe(gcmq())
    .pipe(cleanCSS())
    .pipe(gulp.dest('web/frontend/css'))
});
*/
// стили для нового макета
gulp.task('newCss', function() {
  gulp.src('src/new-css/new.styl')
  .pipe(plumber())
  .pipe(stylus({ errLogToConsole: true, compress: true }))
  .pipe(gcmq())
  .pipe(cleanCSS({ compatibility: 'ie9' }))
  .pipe(gulp.dest('web/frontend/css'))
})

// скрипты для нового макета
gulp.task('newScripts', function() {
  return gulp.src('src/new-js/**/*.js')
  // .pipe(order([
  //   'src/new-js/plugin/*.js',
  //   'src/new-js/*.js'
  //   ]))
   .pipe(concat('new.js'))
   .pipe(gulp.dest('web/frontend/js'));
});
/*
const css = entry => {
  return () => {
    gulp.src(entry)
      .pipe(plumber())
      .pipe(stylus({ errLogToConsole: true }))
      .pipe(gcmq())
      .pipe(cleanCSS({ compatibility: 'ie9' }))
      .pipe(gulp.dest('web/frontend/css'))
  }
}
*/

// const js = entry => {
//   return () => {
//     const paths = { lib:  '../app/lib' }
//     paths[entry] = '../app/' + entry
//
//     const include = glob.sync('src/js/app/' + entry + '/**/*.js')
//       .map( p => p.replace('src/js/app/', '').replace(/\.js$/, ''))
//
//     gulp.src('src/js/app/' + entry + '.js')
//       .pipe(rjs({
//           baseUrl: 'src/js/lib',
//           name: entry,
//           preserveLicenseComments: false,
//           paths,
//           include,
//           optimize: 'none' //TODO: remove it after a development process
//       }))
//       .pipe(gulp.dest('web/frontend/js/app'))
//   }
// }

gulp
  .task('default', [ /*'build', */'watch', 'newCss', 'newScripts' ])
  // .task('build', [ 'build.css', 'build.js' ])
  // .task('build.css', css('src/css/site.styl'))
  // .task('build.js', js('site'))
  .task('watch', () => {
      // watch('src/js/app/**/*.js',      e => gulp.start('build.js'))
      // watch('src/css/**/*.styl',       e => gulp.start('build.css'))
      watch('src/new-css/**/*.styl',  e => gulp.start('newCss'));
      watch('src/new-js/**/*.js',  e => gulp.start('newScripts'));
  })

require('fs').chmod = (a, b, cb) => { cb(0) }
