const { wp } = window

const {
  blockEditor: {
    InspectorControls
  },
  components: {
    Panel, PanelBody, PanelRow
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

  render() {

    const {
      className,
      attributes
    } = this.props

    return (
      <div ref={el => this.el = el}>
        <ServerSideRender
          block="tangible/upvoty"
          className={ className }
          attributes={ attributes }
          EmptyResponsePlaceholder={ EmptyLoopBlock }
          LoadingResponsePlaceholder={ EmptyLoopBlock }
        />
        {/* <InspectorControls>
          <PanelBody
            title="Settings"
            initialOpen={ true }
          >
            <PanelRow>

              Settings here

            </PanelRow>
          </PanelBody>
        </InspectorControls> */}
      </div>
    )
  }
}

export default UpvotyBlock
