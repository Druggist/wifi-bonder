//////////////////////////////////////////
// Required
//////////////////////////////////////////
var gulp = require("gulp"),
	concat = require("gulp-concat"),
	watch = require("gulp-watch"),
	plumber = require("gulp-plumber"),
	rename = require("gulp-rename"),
	uglify = require("gulp-uglify"),
	autoprefixer = require("gulp-autoprefixer"),
	sass = require("gulp-sass"),
	cleancss = require("gulp-clean-css");
	imageop = require('gulp-image'),
	imageResize = require('gulp-image-resize'),
	pug = require('gulp-pug'),
	pugPHPFilter = require('pug-php-filter'),
	del = require("del"),
	replace = require('gulp-replace'),
	browserSync = require('browser-sync').create();

//////////////////////////////////////////
// Script Tasks
//////////////////////////////////////////
gulp.task('mergeJs', function (cb) {
	gulp.src([""])
		.pipe(plumber())
		.pipe(concat({
			path: "main.js",
			stat: {
				mode: 0666
			}
		}))
		.pipe(gulp.dest("app/static/js"), cb);
});

gulp.task("minifyJs", function () {
	gulp.src(['app/static/js/*.js', '!app/static/js/*.min.js'])
		.pipe(plumber())
		.pipe(rename({
			suffix: ".min"
		}))
		.pipe(uglify())
		.pipe(gulp.dest("app/static/js"));
});

gulp.task("watchJs", ["minifyJs"], function(done) {
	browserSync.reload();
	done();
});

//////////////////////////////////////////
// CSS Tasks
//////////////////////////////////////////
gulp.task("compileSass", function () {
	gulp.src(["app/src/sass/main.sass"])
		.pipe(plumber())
		.pipe(sass())
		.pipe(autoprefixer("last 2 versions"))
		.pipe(gulp.dest("app/static/css"));
});

gulp.task("minifyCss", function() {
	gulp.src(['app/static/css/*.css', '!app/static/css/*.min.css'])
		.pipe(plumber())
		.pipe(rename({
			suffix: ".min"
		}))
		.pipe(cleancss({compatibility: 'ie8'}))
		.pipe(gulp.dest("app/static/css"))
		.pipe(browserSync.stream());
});

//////////////////////////////////////////
// Image Optization Task
//////////////////////////////////////////
gulp.task('opImages', function () {
	gulp.src(['app/static/img/**/*.jpg'])
		.pipe(plumber())
		.pipe(imageop({
			pngquant: true,
			optipng: true,
			zopflipng: true,
			jpegRecompress: true,
			jpegoptim: true,
			mozjpeg: true,
			gifsicle: true,
			svgo: true,
			concurrent: 10
		}))
		.pipe(imageResize({
			width : 640,
			height : 640,
			flatten: true,
			imageMagick: true
		}))
		.pipe(gulp.dest('app/static/img'));
});

//////////////////////////////////////////
// Html Tasks
//////////////////////////////////////////
gulp.task("compilePug", function (){
	gulp.src(['app/src/pug/**/*.pug', '!app/src/pug/**/_*.pug'])
		.pipe(plumber())
		.pipe(pug({
			pretty: true,
			filters: {	
				php: pugPHPFilter
			}
		}))
		.pipe(replace('&lt;', '<'))
		.pipe(replace('&gt;', '>'))
		.pipe(rename({
			extname: ".php"
		}))
		.pipe(gulp.dest('app/'));
});

gulp.task("watchHtml", function(done) {
	browserSync.reload();
	done();
});

//////////////////////////////////////////
// Build Tasks
//////////////////////////////////////////
// task to clear out all files and folders from build folder
gulp.task("build:clean", function (cb) {
	del(["build/**"], cb);
});

// task to create build directory for all files
gulp.task("build:copy", ["build:clean"], function () {
	return gulp.src("app/**/*")
		.pipe(gulp.dest("build"));
});

// task to remove unwanted build files
gulp.task("build:remove", ["build:copy"], function (cb) {
	del([
		"build/bower_components/",
		"build/src/",
		"build/static/js/!(*.min.js)",
		"build/static/css/!(*.min.css)",
	], cb);
});

gulp.task("build", ["build:copy", "build:remove"]);

//////////////////////////////////////////
// BrowserSync Tasks
//////////////////////////////////////////
gulp.task("browser-sync", function () {
	browserSync.init({
		proxy: "http://localhost/wifi-bonder/app/"
	});
});

gulp.task("build:serve", function () {
	browserSync.init({
		server: "./build/"
	});
});

//////////////////////////////////////////
//Watch Task
//////////////////////////////////////////
gulp.task("watch", function () {
	gulp.watch(['app/static/js/*.js', '!app/static/js/*.min.js'], ["watchJs"]);
	gulp.watch("app/src/sass/*.sass", ["compileSass"]);
	gulp.watch(["app/static/css/*.css", "!app/static/css/*.min.css"], ["minifyCss"]);
	gulp.watch("app/src/pug/**/*.pug", ["compilePug"]);
	gulp.watch(["app/*.html", "app/*.php"], ["watchHtml"]);
});

//////////////////////////////////////////
//Default Task
//////////////////////////////////////////
gulp.task("default", ["minifyJs", "minifyCss", "compilePug", "browser-sync", "watch"]);