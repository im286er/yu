import gulp from 'gulp';
import babel from 'gulp-babel';
import changed from 'gulp-changed';
import less from 'gulp-less';
import uglify from 'gulp-uglify';
import path from 'path';
import plumber from 'gulp-plumber';
import filter from 'gulp-filter';

let LessPluginCleanCSS = require('less-plugin-clean-css');
let LessPluginAutoPrefix = require('less-plugin-autoprefix');
let cleancss = new LessPluginCleanCSS({
      advanced: true
    });
let autoprefix = new LessPluginAutoPrefix({
      browsers: ["last 2 versions"]
    });
// 文件路径
let filePath = {
  less:['./source/less/**/*.less'],
  srcJs:['./source/js/**/*.js'],
  destJs:'./js'
};

gulp.task('less', () => {  
  //页面样式
  gulp.src('./source/less/*.less')
    .pipe(less({
      plugins: [autoprefix, cleancss]
    }))
    .pipe(gulp.dest('./css/'));
}); 

gulp.task('js', () => {
  gulp.src(filePath.srcJs) 
    .pipe(plumber()) 
    .pipe(changed(filePath.destJs))  
    .pipe(babel())    
    .pipe(uglify({
        mangle: {except: ['require' ,'exports' ,'module' ,'$','global']}//排除混淆关键字
    }))
    .pipe(gulp.dest(filePath.destJs));
});

gulp.task('watch', () => {
  gulp.watch(filePath.less, ['less']);
  gulp.watch(filePath.srcJs, ['js']);
});


gulp.task('default', ['less', 'js','watch']);