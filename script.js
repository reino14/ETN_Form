  // buka modal
  document.querySelectorAll('.openModal').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('tenant_id').value = btn.dataset.tenant;
      document.getElementById('registerModal').classList.remove('hidden');
    });
  });

  // close modal dengan tombol
  document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('registerModal').classList.add('hidden');
  });

  // close modal dengan klik di luar konten
  window.addEventListener('click', (e) => {
    const modal = document.getElementById('registerModal');
    const modalContent = modal.querySelector('div');
    if (e.target === modal) {
      modal.classList.add('hidden');
    }
  });