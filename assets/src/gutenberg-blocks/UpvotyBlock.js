
const { wp } = window

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

    console.log('UpvotyWp1111 - ',UpvotyWp)

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

      const $widget = this.el.querySelector('[data-upvoty]')
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

    const recreate = () => {
    const $widget = document.querySelector('[data-upvoty]')
    if ($widget) $widget.remove()
      this.createWidget()
    }

    recreate()
  }

  onChangeSpecificBoard = ( specific_board ) => {

    if(specific_board.val){
      this.props.setAttributes( { specific_board: 'yes' } )
    } else{
      this.props.setAttributes( { specific_board: '' } )
      this.props.setAttributes( { board_hash: '' } )
      this.props.setAttributes( {  start_page: '' } )
    }

    this.recreateWidget()
   }

  onChangeBoardHash = ( board_hash ) => {

     if(this.props.attributes.specific_board) {
       this.props.setAttributes( { board_hash: board_hash.val } )
     }

     this.recreateWidget()
  }

  onChangeStartPage = ( start_page ) => {

    if(this.props.attributes.specific_board) {
      this.props.setAttributes({start_page: start_page.val})
    }

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
    } = attributes;

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
                label={UGBLocalized.specific_board_label}
                help={UGBLocalized.specific_board_help}
                checked={specific_board}
                onChange={( val ) => this.onChangeSpecificBoard({ val })}
              />
            </PanelRow>
            <PanelRow>
              <TextControl
                label={UGBLocalized.board_hash_label}
                help={UGBLocalized.board_hash_help}
                value={ board_hash }
                onChange={ ( val ) => this.onChangeBoardHash( { val } ) }
              />
            </PanelRow>
            <PanelRow>
              <SelectControl
                label={UGBLocalized.start_page_label}
                help={UGBLocalized.start_page_help}
                options = {[
                      { label: 'Roadmap Start Page', value: 'roadmap' },
                      { label: 'Default Board Page', value: '' },
            <div style={{
              visibility: specific_board ? 'visible' : 'hidden'
            }}>
                  ]}
                onChange={ ( val ) => this.onChangeStartPage( { val } ) }
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
