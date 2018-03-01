var gulp = require('gulp'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    concatCss = require('gulp-concat-css'),
    watch = require('gulp-watch'),
    batch = require('gulp-batch');

const zip = require('gulp-zip');

var baseBuildPath = './build',
    backupPath = './backup',
    backupBuildFile = 'backup-build.zip';

var today = new Date(),
    todayStr = today.getMonth() + '-' + today.getDate() + '-' + today.getFullYear(),
    backupEverythingFile = 'backup.zip' + todayStr;

// Global tasks
gulp.task('build', ['bundle', 'copy-config', 'copy-vendor', 'copy-app', 'copy-public', 'copy-images', 'create-misc', 'create-log']);
gulp.task('backup-build', ['build', 'zip']);
gulp.task('backup', ['backup-everything']);
gulp.task('bundle', ['bundle-js', 'copy-css', 'move-tinymce-themes', 'move-tinymce-plugins', 'move-tinymce-skins']);
gulp.task('watch', ['watch-js', 'watch-css']);

// All sub tasks
gulp.task('watch-js', function () {
    watch('/app/assets/**/*.js', batch(function (events, done) {
        gulp.start('bundle-js', done);
    }));
});

gulp.task('watch-css', function () {
    watch('/app/assets/**/*.css', batch(function (events, done) {
        gulp.start('copy-css', done);
    }));
});

gulp.task('backup-everything', function() {
    console.log('> Zip everything');
    gulp.src('./*')
        .pipe(zip(backupEverythingFile))
        .pipe(gulp.dest(backupPath))
});

gulp.task('zip', function() {
    console.log('> Zip build files');
    gulp.src(baseBuildPath + '/*')
        .pipe(zip(backupBuildFile))
        .pipe(gulp.dest(backupPath))
});

gulp.task('copy-config', function() {
    console.log('> Copy config files');
    gulp.src([
        './.htaccess',
        './config.ini',
        './index.php',
        './messages.ini',
        './routes.ini'
    ]).pipe(gulp.dest(baseBuildPath));
});

gulp.task('copy-vendor', function() {
    console.log('> Copy vendor files');
    return gulp.src('./vendor/**/*')
        .pipe(gulp.dest(baseBuildPath + '/vendor/'));
});

gulp.task('copy-app', function() {
    console.log('> Copy application files');
    return gulp.src('./app/**/*')
        .pipe(gulp.dest(baseBuildPath + '/app/'));
});

gulp.task('copy-public', function() {
    console.log('> Copy public files');
    return gulp.src('./public/**/*')
        .pipe(gulp.dest(baseBuildPath + '/public/'));
});

gulp.task('copy-images', function() {
    console.log('> Copy images');
    return gulp.src('./app/assets/img/**/*')
        .pipe(gulp.dest('./public/img'));
});

gulp.task('create-misc', function() {
    console.log('> Create misc directory');
    return gulp.src('./misc/**/')
        .pipe(gulp.dest(baseBuildPath + '/misc/'));
});

gulp.task('create-log', function() {
    console.log('> Create log directory');
    return gulp.src('./logs/')
        .pipe(gulp.dest(baseBuildPath));
});

gulp.task('bundle-js', function() {
    console.log('> Creating bundle for all javascript files');
    gulp.src('./node_modules/tinymce/tinymce.js')
        .pipe(concat('bundle.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./public/js/'));
});

gulp.task('copy-css', function() {
    console.log('> Copy public files');
    return gulp.src([
        './node_modules/pure-css/lib/base.css',
        './node_modules/pure-css/lib/buttons.css',
        './node_modules/pure-css/lib/forms.css',
        './node_modules/pure-css/lib/forms-nr.css',
        './node_modules/pure-css/lib/grids.css',
        './node_modules/pure-css/lib/grids-nr.css',
        './node_modules/pure-css/lib/menus.css',
        './node_modules/pure-css/lib/tables.css',
        './app/assets/css/blog.css',
        './app/assets/css/blog-old-ie.css'
    ])
        .pipe(gulp.dest('./public/css'));
});

gulp.task('move-tinymce-themes', function() {
    console.log('> Move TinyMCE themes');
    return gulp.src('./node_modules/tinymce/themes/**/*')
        .pipe(gulp.dest('./public/js/themes/'));
});

gulp.task('move-tinymce-plugins', function() {
    console.log('> Move TinyMCE plugins');
    return gulp.src('./node_modules/tinymce/plugins/**/*')
        .pipe(gulp.dest('./public/js/plugins/'));
});

gulp.task('move-tinymce-skins', function() {
    console.log('> Move TinyMCE skins');
    return gulp.src('./node_modules/tinymce/skins/**/*')
        .pipe(gulp.dest('./public/js/skins/'));
});