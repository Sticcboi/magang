<?php
require_once 'db_connect.php';
$page_title = 'Struktur Organisasi';
include 'includes/header.php';

// Mengambil data pegawai yang aktif urutan terkecil (pimpinan) di atas
$anggota = mysqli_query($conn, "SELECT * FROM pegawai WHERE is_active=1 ORDER BY urutan ASC, id ASC");
$members = [];
while ($row = mysqli_fetch_assoc($anggota)) {
    $members[] = $row;
}

function struktur_foto($path) {
    $default = 'img/default-avatar.png'; // Pastikan ada file default di folder img
    if (empty($path)) {
        return $default;
    }
    // Path disesuaikan dengan lokasi folder img di root project
    return $path; 
}
?>

<style>
  /* Desain Modern White dengan aksen Maroon */
  .org-card {
    border: 1px solid #eee;
    border-radius: 12px;
    background-color: #ffffff; /* Sesuai preferensi Modern White */
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    overflow: hidden;
  }
  .org-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    border-color: #800000;
  }
  .card-role-badge {
    background-color: #800000;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 5px 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: inline-block;
    border-radius: 0 0 10px 10px;
    margin-bottom: 15px;
  }
  .avatar-container {
    width: 110px;
    height: 110px;
    margin: 0 auto;
    border-radius: 50%;
    padding: 5px;
    background: #f8f9fa;
    border: 1px solid #eee;
  }
  .avatar-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
  }
  .org-name {
    color: #333;
    font-weight: 700;
    font-size: 1.1rem;
    margin-top: 15px;
  }
  .org-nip-small {
    font-size: 0.8rem;
    color: #6c757d;
  }
  
  /* Modal Styling */
  .modal-content {
    border-radius: 20px;
    border: none;
  }
  .modal-header-custom {
    background-color: #800000;
    color: white;
    border-radius: 20px 20px 0 0;
  }
  .profile-detail-box {
    background-color: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
  }
  .detail-label {
    color: #6c757d;
    font-size: 0.85rem;
    margin-bottom: 0;
  }
  .detail-value {
    color: #333;
    font-weight: 600;
    margin-bottom: 12px;
  }
</style>

<div class="container my-5">
  <div class="text-center mb-5">
    <h6 class="text-uppercase fw-bold text-danger mb-2" style="letter-spacing: 2px;">Profil Kelurahan</h6>
    <h2 class="fw-bold" style="color: #333;">Struktur Organisasi</h2>
    <hr class="mx-auto" style="width: 60px; height: 3px; background-color: #800000;">
  </div>

  <?php if (count($members) > 0): ?>
    <div class="row g-4 justify-content-center">
      <?php foreach ($members as $member): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="card org-card h-100 text-center p-3" 
             data-bs-toggle="modal" 
             data-bs-target="#memberModal"
             data-nama="<?= htmlspecialchars($member['nama']) ?>"
             data-jabatan="<?= htmlspecialchars($member['jabatan']) ?>"
             data-nip="<?= htmlspecialchars($member['nip'] ?: '-') ?>"
             data-pangkat="<?= htmlspecialchars($member['pangkat_gol'] ?: '-') ?>"
             data-pendidikan="<?= htmlspecialchars($member['pendidikan'] ?: '-') ?>"
             data-foto="<?= htmlspecialchars(struktur_foto($member['foto'])) ?>">
             
          <div class="avatar-container">
            <img src="<?= htmlspecialchars(struktur_foto($member['foto'])) ?>" alt="<?= htmlspecialchars($member['nama']) ?>">
          </div>
          <div class="org-name"><?= htmlspecialchars($member['nama']) ?></div>
          <div class="text-danger fw-semibold small mb-1"><?= htmlspecialchars($member['jabatan']) ?></div>
          <div class="org-nip-small">NIP. <?= htmlspecialchars($member['nip'] ?: '-') ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="alert alert-light border text-center py-5">
      <i class="bi bi-people mb-3 d-block fs-1 text-muted"></i>
      Belum ada data pegawai yang tersedia.
    </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header modal-header-custom p-4">
        <h5 class="modal-title fw-bold">Detail Pegawai</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <img src="" id="modalFoto" class="rounded-3 shadow-sm mb-4" style="width: 150px; height: 180px; object-fit: cover;">
        <h4 id="modalNama" class="fw-bold mb-1" style="color: #800000;"></h4>
        <p id="modalJabatan" class="text-muted fw-semibold mb-4"></p>
        
        <div class="profile-detail-box text-start">
            <div class="row">
                <div class="col-6">
                    <p class="detail-label">NIP</p>
                    <p id="modalNip" class="detail-value"></p>
                </div>
                <div class="col-6">
                    <p class="detail-label">Pangkat/Gol.</p>
                    <p id="modalPangkat" class="detail-value"></p>
                </div>
                <div class="col-12">
                    <p class="detail-label">Pendidikan Terakhir</p>
                    <p id="modalPendidikan" class="detail-value"></p>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-light w-100 py-2 fw-bold" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var memberModal = document.getElementById('memberModal');
    memberModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;
      
      // Sinkronisasi dengan data attributes dari database
      memberModal.querySelector('#modalNama').textContent = button.getAttribute('data-nama');
      memberModal.querySelector('#modalJabatan').textContent = button.getAttribute('data-jabatan');
      memberModal.querySelector('#modalNip').textContent = button.getAttribute('data-nip');
      memberModal.querySelector('#modalPangkat').textContent = button.getAttribute('data-pangkat');
      memberModal.querySelector('#modalPendidikan').textContent = button.getAttribute('data-pendidikan');
      memberModal.querySelector('#modalFoto').src = button.getAttribute('data-foto');
    });
  });
</script>

<?php include 'includes/footer.php'; ?>