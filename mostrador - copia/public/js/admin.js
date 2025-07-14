document.addEventListener('DOMContentLoaded', () => {
  //
  // 1) Recarga sólo la sección #content-area
  //
  async function recargar(path) {
    const url = new URL(path, window.location.origin)
    url.searchParams.set('ajax', '1')

    const res  = await fetch(url.href)
    const html = await res.text()
    const doc  = new DOMParser().parseFromString(html, 'text/html')
    const nueva = doc.querySelector('#content-area')

    if (!nueva) {
      console.error('No se encontró #content-area en la respuesta AJAX')
      return
    }

    document.querySelector('#content-area').innerHTML = nueva.innerHTML
  }

  //
  // 2) Extrae la ruta base quitando sufijos
  //
  function getBasePath(fullUrl, suffixRegex) {
    const u = new URL(fullUrl, window.location.origin)
    return u.pathname.replace(suffixRegex, '')
  }

  //
  // 3) Crear / Editar por AJAX sin recarga completa
  //
  document.body.addEventListener('submit', async e => {
    if (!e.target.matches('form[data-ajax]')) return
    e.preventDefault()

    const form = e.target
    const url  = new URL(form.action, window.location.origin)
    url.searchParams.set('ajax', '1')

    console.groupCollapsed('🌐 [AJAX] Enviando formulario')
    console.log('URL:', url.href)
    console.log('Datos:', [...new FormData(form).entries()])
    console.groupEnd()

    const res = await fetch(url.href, {
      method: form.method,
      body: new FormData(form),
      redirect: 'manual'
    })

    // Si viene un redirect (302), recargamos el fragmento
    if (res.status === 302) {
      console.log('🔀 Redirect detectado, recargando sección…')
      const basePath = getBasePath(form.action, /\/(crear|editar)$/)
      return recargar(basePath)
    }

    const text = await res.text()
    if (!res.ok) {
      console.error(`❌ Error ${res.status} al guardar:`, text)
      alert('Error al guardar. Mira la consola.')
      return
    }

    console.log('✅ Guardado OK, recargando sección…')
    const basePath = getBasePath(form.action, /\/(crear|editar)$/)
    recargar(basePath)
  })

  //
  // 4) Eliminar por AJAX sin recarga completa
  //
  document.body.addEventListener('click', async e => {
    if (!e.target.matches('a[data-ajax-delete]')) return
    e.preventDefault()
    if (!confirm('¿Eliminar este producto?')) return

    const url = new URL(e.target.href, window.location.origin)
    url.searchParams.set('ajax', '1')

    console.groupCollapsed('🌐 [AJAX] Eliminando recurso')
    console.log('URL:', url.href)
    console.groupEnd()

    const res  = await fetch(url.href)
    const text = await res.text()

    if (!res.ok) {
      console.error(`❌ Error ${res.status} al eliminar:`, text)
      alert('Error al eliminar. Mira la consola.')
      return
    }

    console.log('✅ Eliminación OK, recargando sección…')
    const basePath = getBasePath(e.target.href, /\/eliminar$/)
    recargar(basePath)
  })
})
