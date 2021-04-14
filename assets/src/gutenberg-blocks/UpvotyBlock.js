
const {
  wp,
  UGBLocalized
} = window

const {
  blockEditor: {
    InspectorControls
  },
  components: {
    Panel,
    PanelBody,
    PanelRow,
    TextControl,
    CheckboxControl,
    RadioControl,
    SelectControl,
    FormToggle,
    ToggleControl
  },
  element: {
    Component
  },
  i18n: { __ },
  serverSideRender: ServerSideRender
} = wp

const EmptyLoopBlock = () => <div className="tangible-upvoty-block">&nbsp;</div>

class UpvotyBlock extends Component {

  componentDidMount() {
    this.createWidget()
  }

  componentDidUnmount() {
    if (this.unsubscribe) this.unsubscribe()
  }

  createWidget = () => {

    const { UpvotyWp } = window

    if (!UpvotyWp || !UpvotyWp.create) return

    /**
     * The workaround below is due to <ServerSideRender> not having a hook
     * when content is fetched and rendered.
     *
     * @see https://github.com/WordPress/gutenberg/tree/master/packages/server-side-render
     *
     * We check at regular intervals until the element (with data attribute) is ready.
     */

    if (this.unsubscribe) this.unsubscribe()

    const check = () => {

      const $widget = this.el && this.el.querySelector('[data-upvoty]')
      if (!$widget) return

      UpvotyWp.create({
        element: $widget
      })

      this.unsubscribe()
    }

    const timer = setInterval(check, 1000)

    this.unsubscribe = () => {
      clearInterval(timer)
      this.unsubscribe = null
    }

    check()
  }

  recreateWidget = () => {

    const $widget = this.el && this.el.querySelector('[data-upvoty]')
    if ($widget) $widget.remove()

    this.createWidget()
  }

  onChangeSpecificBoard = ( value ) => {

    if ( value ) {
      this.props.setAttributes({ specific_board: 'yes' })
    } else {
      this.props.setAttributes({
        specific_board: '',
        board_hash: '',
        start_page: ''
      })
    }

    this.recreateWidget()
  }

  onChangeBoardAttribute = ( name, value ) => {

    // board_hash, start_page needs specific board

    if ( ! this.props.attributes.specific_board ) return

    this.props.setAttributes({
      [name]: value
    })

    this.recreateWidget()
  }

  render() {

    const {
      className,
      attributes
    } = this.props

    const {
      specific_board,
      board_hash,
      start_page
    } = attributes

    return (
      <div ref={el => this.el = el}>
        <ServerSideRender
          block="tangible/upvoty"
          className={ className }
          attributes={ attributes }
          EmptyResponsePlaceholder={ EmptyLoopBlock }
          LoadingResponsePlaceholder={ EmptyLoopBlock }
        />
        { <InspectorControls>
          <PanelBody
            title="Settings"
            initialOpen={ true }
          >
            <PanelRow>
              <ToggleControl
                label={ UGBLocalized.specific_board_label }
                help={ UGBLocalized.specific_board_help }
                checked={specific_board}
                onChange={( val ) => this.onChangeSpecificBoard(val)}
              />
            </PanelRow>
            <div style={{
              visibility: specific_board ? 'visible' : 'hidden'
            }}>
              <PanelRow>
                <TextControl
                  label={ UGBLocalized.board_hash_label }
                  help={ UGBLocalized.board_hash_help }
                  onChange={ ( val ) => this.onChangeBoardAttribute('board_hash', val) }
                  value={ board_hash }
                />
              </PanelRow>
              <PanelRow>
                <SelectControl
                  label={ UGBLocalized.start_page_label }
                  help={ UGBLocalized.start_page_help }
                  options = {[
                    { label: 'Roadmap Start Page', value: 'roadmap' },
                    { label: 'Default Board Page', value: '' },
                  ]}
                  onChange={ ( val ) => this.onChangeBoardAttribute('start_page', val) }
                  value={ start_page }
                />
              </PanelRow>
            </div>
          </PanelBody>
        </InspectorControls> }
      </div>
    )
  }
}

export default UpvotyBlock
