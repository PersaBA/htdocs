// public/js/admin.js

document.addEventListener('DOMContentLoaded', () => {
  //
  // 0) Mostrar/ocultar campos de oferta
  //
  const chkOferta    = document.getElementById('chkOferta');
  const ofertaFields = document.getElementById('ofertaFields');
  if (chkOferta && ofertaFields) {
    ofertaFields.style.display = chkOferta.checked ? 'block' : 'none';
    chkOferta.addEventListener('change', () => {
      ofertaFields.style.display = chkOferta.checked ? 'block' : 'none';
    });
  }

  //
  // 1) Inicializa drag & drop + preview en el formulario
  //
  initFormDragDrop();

  //
  // 2) Recarga parcial de .main vía AJAX
  //
  async function recargar(path) {
    const url = new URL(path, window.location.origin);
    url.searchParams.set('ajax', '1');
    const res = await fetch(url.href);
    if (!res.ok) throw new Error(await res.text());

    const html = await res.text();
    const cont = document.querySelector('.main');
    cont.innerHTML = html;
    initFormDragDrop();
  }

  //
  // 3) Helper para limpiar sufijos de URL
  //
  function getBasePath(fullUrl, re) {
    return new URL(fullUrl, window.location.origin)
      .pathname.replace(re, '');
  }

  //
  // 4) Crear / Editar por AJAX
  //
  document.body.addEventListener('submit', async e => {
    const form = e.target;
    if (!form.matches('form[data-ajax]')) return;
    e.preventDefault();

    const url = new URL(form.action, window.location.origin);
    url.searchParams.set('ajax', '1');

    const res = await fetch(url.href, {
      method: form.method,
      body: new FormData(form),
      redirect: 'manual'
    });

    if (res.status === 302) {
      return recargar(getBasePath(form.action, /\/(crear|editar)$/));
    }
    if (!res.ok) {
      console.error(await res.text());
      alert('Error al guardar. Mirá la consola.');
      return;
    }
    recargar(getBasePath(form.action, /\/(crear|editar)$/));
  });

  //
  // 5) Rellenar form al clicar “✏️” en categorías
  //
  document.body.addEventListener('click', e => {
    const btn = e.target.closest('.btn-edit-cat');
    if (!btn) return;
    e.preventDefault();

    const cat = JSON.parse(btn.dataset.category);
    const form = document.querySelector('form[data-ajax]');
    form.action              = BASE_URL + 'admin/categorias/editar';
    form.id.value            = cat.id;
    form.nombre.value        = cat.nombre;
    form.descripcion.value   = cat.descripcion;
    form.parent_id.value     = cat.parent_id || '';
    form.is_active.checked   = cat.is_active == 1;
    form.orden.value         = cat.orden;
  });

  //
  // 6) Eliminar por AJAX con POST y confirm genérico
  //
  document.body.addEventListener('click', async e => {
    const del = e.target.closest('a[data-ajax-delete]');
    if (!del) return;

    e.preventDefault();
    if (del.dataset.confirm && !confirm(del.dataset.confirm)) return;

    // Construyo la URL de POST con ajax=1
    const postUrl = new URL(del.dataset.url, window.location.origin);
    postUrl.searchParams.set('ajax', '1');

    // Armo el FormData con el ID
    const fd = new FormData();
    fd.append('id', del.dataset.id);

    const res = await fetch(postUrl.href, {
      method: 'POST',
      body: fd,
      credentials: 'same-origin'
    });

    if (!res.ok) {
      console.error(await res.text());
      alert('Error al eliminar. Mirá la consola.');
      return;
    }

    // Vuelvo a recargar la tabla
    recargar(getBasePath(del.dataset.url + `?id=${del.dataset.id}`, /\/delete$/));
  });
});

// 0) Función global para drag & drop + preview de imagen
function initFormDragDrop() {
  const dropZone = document.getElementById('drop-zone');
  const imgInput = document.getElementById('imgInput');
  const preview  = document.getElementById('preview');
  if (!dropZone || !imgInput || !preview) return;

  dropZone.onclick = () => imgInput.click();
  imgInput.onchange = () => {
    const file = imgInput.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    const reader = new FileReader();
    reader.onload = e => preview.src = e.target.result;
    reader.readAsDataURL(file);
  };

  function handleDrag(e) {
    e.preventDefault();
    dropZone.classList.toggle('drag-over', e.type === 'dragover');
    if (e.type === 'drop' && e.dataTransfer.files.length) {
      imgInput.files = e.dataTransfer.files;
      imgInput.dispatchEvent(new Event('change'));
    }
  }

  dropZone.addEventListener('dragover', handleDrag);
  dropZone.addEventListener('dragleave', handleDrag);
  dropZone.addEventListener('drop', handleDrag);
}
