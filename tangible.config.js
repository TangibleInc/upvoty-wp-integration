module.exports = {
  build: [
    // Unused
    // {
    //   task: 'js',
    //   src: 'assets/src/admin-settings.js',
    //   dest: 'assets/build/admin-settings.min.js',
    //   watch: 'assets/src/admin-settings.js'
    // },
    {
      task: 'sass',
      src: 'assets/src/admin-settings.scss',
      dest: 'assets/build/admin-settings.min.css',
      watch: [
        'assets/src/admin-settings.scss',
        'assets/fonts/dots/style.scss'
      ]
    },
  ]
}
