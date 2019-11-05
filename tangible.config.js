module.exports = {
  build: [
    /*{
      task: 'js',
      src: 'assets/src/index.js',
      dest: 'build/app.min.js',
*///      watch: 'src/**/*.js'
/*    },*/
    {
      task: 'sass',
      src: 'assets/src/admin-settings.scss',
      dest: 'assets/build/admin-settings.min.css',
      watch: 'assets/src/admin-settings.scss'
    },
  ]
}
