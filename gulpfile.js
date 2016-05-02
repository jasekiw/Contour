// var elixir = require('laravel-elixir');
var gulp = require('gulp');
var sass = require('gulp-sass');
var ts = require('gulp-typescript');
var sourcemaps = require("gulp-sourcemaps");
gulp.task('sass', function () {
    return gulp.src('./public/assets/sass/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/assets/sass/'));
});

gulp.task('compile-typescript-front-end', function () {
    var tsProject = ts.createProject('./public/assets/ts/contour/tsconfig.json');
    var tsResult = tsProject.src() // instead of gulp.src(...)
        .pipe(sourcemaps.init())
        .pipe(ts(tsProject));
    return tsResult.js.pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/assets/ts/contour/'));
});

gulp.task('watch', function () {
    gulp.watch('./public/assets/sass/**/*.scss', ['sass']);
    gulp.watch("./public/assets/ts/contour/**/*.ts", ['compile-typescript-front-end'])
});




gulp.task('build', ['compile-typescript-front-end', 'sass']);
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

// elixir(function(mix) {
//     mix.sass('app.scss');
// });
