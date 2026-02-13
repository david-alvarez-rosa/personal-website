let _speculationRulesType
  , _preloadedList = new Set()

init()

function init() {
  const supportChecksRelList = document.createElement('link').relList

  const supportsPrefetch = supportChecksRelList.supports('prefetch')
  if (!supportsPrefetch) {
    return
  }

  const chromium100Check = 'throwIfAborted' in AbortSignal.prototype
  const firefox115AndSafari17_0Check = supportChecksRelList.supports('modulepreload')
  const safari15_4AndFirefox116Check = Intl.PluralRules && 'selectRange' in Intl.PluralRules.prototype
  const firefox115AndSafari15_4Check = firefox115AndSafari17_0Check || safari15_4AndFirefox116Check
  const isBrowserSupported = chromium100Check && firefox115AndSafari15_4Check
  if (!isBrowserSupported) {
    return
  }

  _speculationRulesType = 'none'
  if (HTMLScriptElement.supports && HTMLScriptElement.supports('speculationrules')) {
    _speculationRulesType = 'prerender'
  }

  let requestIdleCallbackOrFallback = window.requestIdleCallback
  if (!requestIdleCallbackOrFallback) {
    requestIdleCallbackOrFallback = (callback) => { callback() }
  }

  requestIdleCallbackOrFallback(function observeIntersection() {
    const intersectionObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const anchorElement = entry.target
          intersectionObserver.unobserve(anchorElement)
          preload(anchorElement.href)
        }
      })
    })

    document.querySelectorAll('a').forEach((anchorElement) => {
      if (isPreloadable(anchorElement)) {
        intersectionObserver.observe(anchorElement)
      }
    })
  }, {
    timeout: 1500,
  })
}

function isPreloadable(anchorElement) {
  if (!anchorElement || !anchorElement.href) {
    return
  }

  if (anchorElement.origin != location.origin) {
    return
  }

  if (anchorElement.search) {
    return
  }

  if (anchorElement.hash && anchorElement.pathname == location.pathname) {
    return
  }

  if ('noInstant' in anchorElement.dataset) {
    return
  }

  return true
}

function preload(url) {
  if (_preloadedList.has(url)) {
    return
  }

  if (_speculationRulesType != 'none') {
    const scriptElement = document.createElement('script')
    scriptElement.type = 'speculationrules'
    scriptElement.textContent = JSON.stringify({
      [_speculationRulesType]: [{
        source: 'list',
        urls: [url]
      }]
    })
    document.head.appendChild(scriptElement)
  } else {
    const linkElement = document.createElement('link')
    linkElement.rel = 'prefetch'
    linkElement.href = url
    linkElement.as = 'document'
    document.head.appendChild(linkElement)
  }

  _preloadedList.add(url)
}
