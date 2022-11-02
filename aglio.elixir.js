var gulp = require('gulp');
var aglio = require('gulp-aglio');
var Elixir = require('laravel-elixir');
var defaults = require('lodash.defaults');
var rename = require('gulp-rename');

var Task = Elixir.Task;

Elixir.extend('aglio', function(src, dest, options) {

    var paths = prepGulpPaths(src, dest);

    options = defaults({
        themeVariables: 'streak',
        themeTemplate: 'triple',
        extension: '.blade.php'
    }, options);

    new Task('aglio', function() {
        return gulp.src(paths.src.path)
                   .pipe(aglio(options))
                   .pipe(rename({extname: options.extension}))
                   .pipe(gulp.dest(paths.output.baseDir));
    })
    .watch([paths.src.baseDir + '/**/*.apib', paths.src.baseDir + '/**/*.md'])
    .ignore(paths.output.path);

});

/**
 * Prep the Gulp src and output paths.
 *
 * @param  {string|Array} src
 * @param  {string|null}  output
 * @return {GulpPaths}
 */
var prepGulpPaths = function(src, output) {
    return new Elixir.GulpPaths()
        .src(src, 'resources/blueprint/')
        .output(output || 'resources/views/blueprint/', 'api.blade.php');
};