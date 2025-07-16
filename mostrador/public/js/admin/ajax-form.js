/**
 * ajax-form.js
 *
 * Intercepta envíos de formularios marcados con data-ajax,
 * envía la petición por fetch y recarga la sección correspondiente
 * usando AdminAjax.recargar() y AdminAjax.getBasePath().
 */

document.body.addEventListener('submit', async e => {
  const form = e.target;
  if (!form.matches('form[data-ajax]')) return;
  e.preventDefault();

  // Construir URL con ?ajax=1
  const url = new URL(form.action, location.origin);
  url.searchParams.set('ajax', '1');

  try {
    const res = await fetch(url.href, {
      method: form.method,
      body: new FormData(form),
      redirect: 'manual',
      credentials: 'same-origin'
    });

    // Calcular ruta base sin /crear o /editar
    const base = AdminAjax.getBasePath(form.action, /\/(crear|editar)$/);

    // Si hay redirección (302) o OK, recargar y resetear
    if (res.status === 302 || res.ok) {
      await AdminAjax.recargar(base);
      form.reset();
    } else {
      console.error(await res.text());
      alert('❌ Error al guardar. Mirá la consola.');
    }
  } catch (err) {
    console.error(err);
    console.log('Form enviado a:', form.action);
    alert('❌ Error inesperado al enviar el formulario.');
  }
});
