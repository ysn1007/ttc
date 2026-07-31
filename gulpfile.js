// Important packages
const gulp = require('gulp');
const plumber = require('gulp-plumber');
const rename = require('gulp-rename');
const browserSync = require('browser-sync').create();

// Sass => CSS
const sass = require('gulp-sass'); // Kompatibel mit neuem gulp-sass
const sourcemaps = require('gulp-sourcemaps');

// JavaScript
const browserify = require('gulp-browserify');
const uglify = require('gulp-uglify');

const src = './src';
const dest = './dest';

// Refreshes browser after changes 
const reload = (done) => {
    browserSync.reload();
    done();
};

// Server streams on localhost
const serve = (done) => {
    browserSync.init({
        proxy: 'http://localhost/local/ttc/src/',
        port: 3000,
    });
    done();
};

//*****************//
//* Dev Mode Tasks *//
//*****************//

function php() {
    return gulp.src(`${src}/*.php`);
}

function phpAdminComponents() {
    return gulp.src(`${src}/admin/components/*.php`);
}

function phpAdmin() {
    return gulp.src(`${src}/admin/*.php`);
}

function phpIncludes() {
    return gulp.src(`${src}/includes/*.php`);
}

// SASS => CSS Handling (Dev)
const css = () => {
    return gulp.src(`${src}/styles/*.scss`)
        .pipe(plumber())
        .pipe(sass({ outputStyle: "compressed" }).on('error', sass.logError))
        .pipe(rename({ basename: 'style', suffix: ".min" }))
        .pipe(gulp.dest(`${src}/styles`))
        .pipe(browserSync.stream());
};

// JavaScript Handling (Dev) 
const script = () => {
    // Ignoriere *.min.js, damit sich Gulp nicht selbst im Kreis dreht!
    return gulp.src([`${src}/js/*.js`, `!${src}/js/*.min.js`])
        .pipe(plumber((error) => {
            console.error('JS Error:', error.message);
        }))
        .pipe(browserify({
            insertGlobals: true
        }))
        .pipe(uglify()) 
        .pipe(rename({ suffix: ".min" })) 
        .pipe(gulp.dest(`${src}/js`)) 
        .pipe(browserSync.stream());
};

//*******************//
//* Build Mode Tasks *//
//*******************//

function buildPhp() {
    return gulp.src(`${src}/*.php`)
        .pipe(gulp.dest(`${dest}`));
}

function buildAdmin() {
    return gulp.src(`${src}/admin/**/*.php`)
        .pipe(gulp.dest(`${dest}/admin`));
}

function buildPhpIncludes() {
    return gulp.src(`${src}/includes/*.php`)
        .pipe(gulp.dest(`${dest}/includes`));
}

const buildCss = () => {
    return gulp.src(`${src}/styles/*.scss`)
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({ outputStyle: "compressed" }).on('error', sass.logError))
        .pipe(rename({ basename: 'style', suffix: ".min" }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(`${dest}/css`));
};

const buildScript = () => {
    return gulp.src([`${src}/js/*.js`, `!${src}/js/*.min.js`])
        .pipe(plumber((error) => {
            console.error('JS Build Error:', error.message);
        }))
        .pipe(sourcemaps.init())
        .pipe(browserify({
            insertGlobals: true
        }))
        .pipe(uglify())
        .pipe(rename({ suffix: ".min" }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(`${dest}/js`));
};

// Watcher
const watch = () => {
    // SCSS -> nur CSS neu kompilieren & streamen
    gulp.watch(`${src}/styles/**/*.scss`, css);

    // JS -> nur Script kompilieren & reloaden
    gulp.watch([`${src}/js/*.js`, `!${src}/js/*.min.js`], script);

    // PHP -> direkt Browser neu laden (keine Build-Tasks nötig)
    gulp.watch([
        `${src}/*.php`, 
        `${src}/includes/*.php`, 
        `${src}/admin/**/*.php`
    ]).on('change', browserSync.reload);
};

// Start Tasks
const dev = gulp.series(serve, watch);
const build = gulp.series(buildScript, buildPhp, buildAdmin, buildPhpIncludes, buildCss);

exports.dev = dev;
exports.build = build;
exports.default = dev;