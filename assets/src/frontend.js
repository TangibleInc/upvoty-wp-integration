(function() {

  const warn = (message, ...args) => console.log(`Upvoty WP widget: ${message}`, ...args)

  function createUpvotyWidget(props = {}) {

    const $widget = props.element || document.querySelector('[data-upvoty]')

    if (!$widget || $widget.created) return
    $widget.created = true

    const configStr = $widget.dataset && $widget.dataset.upvoty
    if (configStr) {

      const config = JSON.parse(configStr)

      if (config && typeof config==='object') {
        Object.assign(props, config)
      } else {
        warn(' Invalid config', config)
        return
      }
    }

    const {
      widgetData = {},
      embedJsUrl = ''
    } = props

    if (!embedJsUrl) {
      warn('Upvoty WP widget: Empty embed JS URL')
      return
    }

    let { upvoty } = window
    let loaded = false

    const script = document.createElement('script')

    const onError = function() {
      $widget.innerText = 'Upvoty widget could not be loaded.'
      loaded = true // Check once
    }

    const onLoad = function() {
      upvoty = window.upvoty
      if (!upvoty) return onError()
      if (loaded) return
      upvoty.init('render', widgetData)
      loaded = true
    }

    if (upvoty) return onLoad()

    script.onerror = onError
    script.onload = onLoad
    script.onreadystatechange = onLoad
    script.src = embedJsUrl

    document.body.appendChild(script)

    // Fallback if all else fails
    setTimeout(function() {
      if (!loaded) onError()
    }, 3000)
  }

  // Support dynamic initialization
  window.UpvotyWp = {
    create: createUpvotyWidget
  }
})()
