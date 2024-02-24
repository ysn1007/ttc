// important packages
const gulp = require('gulp');
const plumber = require('gulp-plumber');
const rename = require('gulp-rename');
const browserSync = require('browser-sync');
const gutil = require('gulp-util');
const sourcemaps = require('gulp-sourcemaps');
const connect = require('gulp-connect-php');
let service = new connect();

// For Sass => css
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const sassLint = require('gulp-sass-lint');

// javaScript
const browserify = require('gulp-browserify');
//const babel = require('gulp-babel');
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const jshint = require('gulp-jshint');

const src = './src';
const dest = './dest';

// refreshes browser after changes 
const reload = (done) => {
    browserSync.reload();
    done();
};

// server streams on localhost
const serve = (done) => {
        browserSync.init({
           proxy: 'http://localhost/local/ttc/src/',
           port: 3000,
        });
    
    done();
}

//***************//
//* build mode **//
//***************//

// PHP handling
function buildPhp(){
    return gulp.src("./src/*.php")
        .pipe(gulp.dest("./dest"));
}

// PHP Include handling
function buildPhpIncludes(){
    return gulp.src("./src/includes/*.php")
        .pipe(gulp.dest("./dest/includes"));
}

// SASS => CSS Handling 
const buildCss = () => {
    return gulp.src(`${src}/styles/*.scss`)
        .pipe( plumber() )
        //lint SASS
        .pipe( sassLint({
            options: {
                formatter: 'stylish',
            },
            rules: {
                'no-ids': 1,
                'final-newline': 0,
                //'no-mergable-selectors': 1,
                'indentation': 0
            }
        }) )
        // Format SASS
        .pipe(sassLint.format())
        // init source map
        .pipe(sourcemaps.init())
        // compile SASS to CSS
        .pipe(sass.sync({ outputStyle: "compressed" })).on( 'error', sass.logError)
        // add suffix 
        .pipe(rename({ basename: 'style', suffix: ".min" }))
        // add autoprefixer 
        //.pipe(postcss([autoprefixer(), cssnano()]))
        // writes sourcemap
        .pipe(sourcemaps.write(``))
        // export everything to destination folder
        .pipe(gulp.dest(`${dest}/css`))
        // update browser
        .pipe(browserSync.stream());
};

const buildScript = () => {
    return gulp.src(`${src}/js/*.js`)
        // init plumber for js files
        .pipe(plumber(((error) => {
            gutil.log( error.message );
        })))
         // init source map
        .pipe(sourcemaps.init())
        // concat js flies
        .pipe(concat('concat.js'))
        // js hint
        //.pipe(jshint())
        //.pipe(jshint.reporter('jshint-stylish'))
        // add browser support
        .pipe(browserify({
            insertGlobals: true
        }))
        // minify js
        .pipe(uglify())
        // add suffix 
        .pipe(rename({ basename: 'global', suffix: ".min" }))
        // writes sourcemap
        .pipe(sourcemaps.write(``))
        // export everything to destination folder
        .pipe(gulp.dest(`${dest}/js`))
        // update Browser
        .pipe(browserSync.stream());
}

//*************// 
// dev mode    //
//*************//
function php(){
    return gulp.src(`${src}/*.php`);
}

// PHP Admin
function phpAdminComponents() {
    return gulp.src(`${src}/admin/components/*.php`);
}

function phpAdmin() {
    return gulp.src(`${src}/admin/*.php`);
}

// PHP Include handling
function phpIncludes(){
    return gulp.src(`${src}/includes/*.php`);
}

// SASS => CSS Handling 
const css = () => {
    return gulp.src(`${src}/styles/*.scss`)
        /*.pipe( plumber() )
        //lint SASS
        .pipe( sassLint({
            options: {
                formatter: 'stylish',
            },
            rules: {
                'no-ids': 1,
                'final-newline': 0,
                //'no-mergable-selectors': 1,
                'indentation': 0
            }
        }) )*/
        // compile SASS to CSS
        .pipe(sass.sync({ outputStyle: "compressed" })).on( 'error', sass.logError)
        // add suffix 
        .pipe(rename({ basename: 'style', suffix: ".min" }))
         // export everything to destination folder
        .pipe(gulp.dest(`${src}/styles`))
        // update browser
        .pipe(browserSync.stream());
};

const script = () => {
    return gulp.src(`${src}/js/*.js`)
        // init plumber for js files
        .pipe(plumber(((error) => {
            gutil.log( error.message );
        })))

        // concat js flies
        //.pipe(concat('concat.js'))
        // add browser support
        .pipe(browserify({
            insertGlobals: true
        }))
        // update Browser
        .pipe(browserSync.stream());
}

// watch for changes and refreshes page
const watch = () => gulp.watch([`${src}/styles/*scss`, `${src}/*php`, `${src}/includes/*php`, `${src}/admin/components/*php`, `${src}/admin/*php`, `${src}/js/*js`], gulp.series(php, css, script, phpIncludes,phpAdminComponents,phpAdmin, reload));

// start all Tasks for this project
const dev = gulp.series(serve, watch );

// just building project
const build = gulp.series(buildScript, buildPhp, buildPhpIncludes, buildCss);

// default function
exports.dev = dev;
exports.build = build;
exports.default = dev;