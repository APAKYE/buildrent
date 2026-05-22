// BuildRent — app.js

// Toggle inline price form in admin products table
function togglePriceForm(id) {
  const row = document.getElementById('price-form-' + id);
  if (row) row.classList.toggle('hidden');
}

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.alert').forEach(function (alert) {
    setTimeout(function () {
      alert.style.transition = 'opacity 0.5s';
      alert.style.opacity = '0';
      setTimeout(function () { alert.remove(); }, 500);
    }, 5000);
  });
});
