document.body.addEventListener('click', async e => {
  const del = e.target.closest('a[data-ajax-delete]');
  if (!del) return;
  e.preventDefault();

  if (del.dataset.confirm && !confirm(del.dataset.confirm)) return;

  // Construyo URL POST con ajax=1
  const postUrl = new URL(del.dataset.url, location.origin);
  postUrl.searchParams.set('ajax', '1');

  // FormData con el ID
  const fd = new FormData();
  fd.append('id', del.dataset.id);

  const res = await fetch(postUrl.href, {
    method: 'POST',
    body: fd,
    credentials: 'same-origin'
  });

  if (!res.ok) {
    console.error(await res.text());
    return alert('Error al eliminar. Mirá la consola.');
  }

  // Recarga la tabla
  AdminAjax.recargar(
    AdminAjax.getBasePath(del.dataset.url + `?id=${del.dataset.id}`, /\/delete$/)
  );
});
