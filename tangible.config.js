module.exports = {
  build: [
    // Frontend
    {
      task: 'js',
      src: 'assets/src/frontend.js',
      dest: 'assets/build/frontend.min.js',
      watch: 'assets/src/frontend.js'
    },
    // Admin
    {
      task: 'sass',
      src: 'assets/src/admin-settings.scss',
      dest: 'assets/build/admin-settings.min.css',
      watch: [
        'assets/src/admin-settings.scss',
        'assets/fonts/dots/style.scss'
      ]
    },
    // Gutenberg
    {
      task: 'js',
      src: 'assets/src/gutenberg-blocks/index.js',
      dest: 'assets/build/gutenberg-blocks.min.js',
      watch: ['assets/src/gutenberg-blocks/**'],
      react: 'wp.element'
    },
  ]
}
