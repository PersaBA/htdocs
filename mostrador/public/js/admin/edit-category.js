document.body.addEventListener('click', e => {
  const btn = e.target.closest('.btn-edit-cat');
  if (!btn) return;
  e.preventDefault();

  const cat  = JSON.parse(btn.dataset.category);
  const form = document.querySelector('form[data-ajax]');
  form.action            = BASE_URL + 'admin/categorias/editar';
  form.id.value          = cat.id;
  form.nombre.value      = cat.nombre;
  form.descripcion.value = cat.descripcion;
  form.parent_id.value   = cat.parent_id || '';
  form.is_active.checked = cat.is_active == 1;
  form.orden.value       = cat.orden;
});
