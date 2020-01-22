import UpvotyBlock from './UpvotyBlock'

const { wp } = window

const {
  blocks: {
    registerBlockType
  },
  i18n: { __ },
} = wp

/**
 * Register Gutenberg block
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-registration
 *
 * @param  {string}   name     Block name
 * @param  {Object}   settings Block settings
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`
 */

registerBlockType( 'tangible/upvoty', {
  title: __( 'Upvoty' ),
  description: __( 'Embed Upvoty feedback widget' ),

  /**
   * Icon
   *
   * @see https://developer.wordpress.org/block-editor/developers/block-api/block-registration/#icon-optional
   * @see https://developer.wordpress.org/resource/dashicons/
   */

  // Based on dashicon-image-rotate - TODO: Create SVG from logo
  icon:
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10.25 1.02c5.1 0 8.75 4.04 8.75 9s-3.65 9-8.75 9c-3.2 0-6.02-1.59-7.68-3.99l2.59-1.52c1.1 1.5 2.86 2.51 4.84 2.51 3.3 0 6-2.79 6-6s-2.7-6-6-6c-1.97 0-3.72 1-4.82 2.49L7 8.02l-6 2v-7L2.89 4.6c1.69-2.17 4.36-3.58 7.36-3.58z"/></g></svg>
  ,

  /**
   * Category
   *
   * Core categories are common, formatting, layout, widgets, embed
   *
   * @see https://developer.wordpress.org/block-editor/designers-developers/developers/filters/block-filters/#managing-block-categories
   */
  category: 'embed',
  keywords: [
    __( 'upvoty' ),
  ],

  edit(props) {
    return <UpvotyBlock {...props} />
  },

  save() {
    // Dynamic block
    return null
  }
})
