// var elixir = require('laravel-elixir');
var gulp = require('gulp');
var sass = require('gulp-sass');
var ts = require('gulp-typescript');
var sourcemaps = require("gulp-sourcemaps");
var autoprefixer = require('gulp-autoprefixer');
var gulpTypings = require("gulp-typings");
var bower = require('gulp-bower');
var clean = require('gulp-clean');
var deleteEmpty = require('delete-empty');

gulp.task('sass', function () {
    return gulp.src('./public/assets/sass/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/assets/sass/'));
});

gulp.task('compile-typescript-front-end', ['installTypings','cleanFolders'], function () {
    var tsProject = ts.createProject('./public/assets/ts/contour/tsconfig.json');
    var tsResult = tsProject.src() // instead of gulp.src(...)
        .pipe(sourcemaps.init())
        .pipe(ts(tsProject));
    return tsResult.js.pipe(sourcemaps.write())
        .pipe(gulp.dest('./public/assets/ts/contour/'));
});

gulp.task("installTypings",function(){
    return gulp.src("./public/assets/ts/typings.json")
        .pipe(gulpTypings()); //will install all typingsfiles in pipeline.
});

gulp.task('watch', function () {
    gulp.watch('./public/assets/sass/**/*.scss', ['sass']);
    gulp.watch("./public/assets/ts/contour/**/*.ts", ['compile-typescript-front-end'])
});
gulp.task('watch-sass', function() {
    gulp.watch('./public/assets/sass/**/*.scss', ['sass']);
});


gulp.task('bower', function() {
    return bower({ directory: './bower_components', cwd: './public/assets' })
        .pipe(gulp.dest('./public/assets/bower_components'))
});


gulp.task('clean-maps', function () {
    return gulp.src('./public/assets/ts/contour/**/*.js.map', {read: false})
        .pipe(clean());
});
gulp.task('clean-scripts', function () {
    return gulp.src('./public/assets/ts/contour/**/*.js', {read: false})
        .pipe(clean());
});

gulp.task('cleanFolders', ['clean-scripts', 'clean-maps'], function() {
    deleteEmpty.sync('./public/assets/ts/contour/');
});

gulp.task('build', ['sass', 'compile-typescript-front-end', 'bower']);
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
