<?php
$page_title = 'LPMK';
include 'includes/header.php';
require_once 'admin/kelembagaan_model.php';

$page_key = 'lpmk';
$content = get_kelembagaan_page($conn, $page_key);
$staff = get_kelembagaan_staff($conn, $page_key);
$units = get_kelembagaan_units($conn, $page_key);
$programs = get_kelembagaan_programs($conn, $page_key);
?>
<style>
  .hover-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
  .hover-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; }
  .accordion-button:focus, .accordion-button:not(.collapsed) { box-shadow: none !important; }
</style>

<div class="container my-5">
  <div class="text-center mb-5">
    <h1 class="fw-bold text-dark mb-2">Lembaga Pemberdayaan Masyarakat Kelurahan</h1>
    <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill">LPMK Kedungpane</span>
  </div>

  <div class="row mb-5 g-4">
    <div class="col-md-6">
      <div class="card h-100 shadow-sm border-0 hover-card">
        <div class="card-body p-4">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-danger rounded p-2 me-3 text-white">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.629V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/><path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z"/></svg>
            </div>
            <h5 class="card-title mb-0 fw-bold text-dark">Profil Kelembagaan</h5>
          </div>
          <p class="card-text text-secondary lh-lg"><?= nl2br(htmlspecialchars($content['overview'] ?: 'LPMK adalah wadah yang dibentuk atas prakarsa masyarakat sebagai mitra Pemerintah Kelurahan Kedungpane dalam menampung aspirasi dan kebutuhan pembangunan.')) ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100 shadow-sm border-0 hover-card">
        <div class="card-body p-4">
          <div class="d-flex align-items-center mb-3">
            <div class="bg-danger rounded p-2 me-3 text-white">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list-check" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/></svg>
            </div>
            <h5 class="card-title mb-0 fw-bold text-dark">Visi & Tugas</h5>
          </div>
          <div class="mb-3">
            <strong class="text-dark">Visi:</strong>
            <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($content['visi'] ?: 'Menjadi institusi partisipatif yang menguatkan swadaya masyarakat di wilayah Kelurahan Kedungpane.')) ?></p>
          </div>
          <div>
            <strong class="text-dark">Misi:</strong>
            <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($content['misi'] ?: 'Mengoptimalkan peran masyarakat dalam merencanakan dan melakukan pembangunan kelurahan secara inklusif.')) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="mb-5 bg-white p-4 rounded shadow-sm border-0">
    <div class="border-bottom pb-3 mb-4">
      <h3 class="mb-0 fw-bold text-dark">Struktur Kepengurusan</h3>
    </div>
    <?php if(count($staff)): ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach($staff as $member): ?>
      <div class="col">
        <div class="card h-100 border border-light bg-light hover-card align-items-center text-center pt-4 pb-3 rounded-4">
          <div class="position-relative mb-3">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($member['name']) ?>&background=dc3545&color=fff&size=120" class="rounded-circle shadow-sm border border-3 border-white" alt="<?= htmlspecialchars($member['name']) ?>">
          </div>
          <div class="card-body p-2">
            <h6 class="card-title mb-1 fw-bold text-dark"><?= htmlspecialchars($member['name']) ?></h6>
            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1"><?= htmlspecialchars($member['role'] ?: 'Anggota') ?></span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-secondary">Belum ada data kepengurusan LPMK. Silakan tambahkan lewat panel admin.</p>
    <?php endif; ?>
  </div>

  <div class="mb-5">
    <h3 class="mb-4 border-bottom pb-2 fw-bold text-dark">Unit Pengelola & Program Kerja</h3>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm border-0 p-4 h-100">
          <h5 class="fw-bold mb-3 text-dark">Unit Pengelola</h5>
          <?php if(count($units)): ?>
            <ul class="list-group list-group-flush">
              <?php foreach($units as $unit): ?>
              <li class="list-group-item px-0 py-3 border-0 border-bottom">
                <strong class="d-block mb-1"><?= htmlspecialchars($unit['title']) ?></strong>
                <span class="text-secondary"><?= nl2br(htmlspecialchars($unit['description'])) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-secondary mb-0">Belum ada unit pengelola yang dipublikasikan.</p>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm border-0 p-4 h-100">
          <h5 class="fw-bold mb-3 text-dark">Program Kerja</h5>
          <?php if(count($programs)): ?>
            <ul class="list-group list-group-flush">
              <?php foreach($programs as $program): ?>
              <li class="list-group-item px-0 py-3 border-0 border-bottom">
                <strong class="d-block mb-1"><?= htmlspecialchars($program['title']) ?></strong>
                <span class="text-secondary"><?= nl2br(htmlspecialchars($program['description'])) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <p class="text-secondary mb-0">Belum ada program kerja yang ditambahkan.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="mb-5 bg-light p-4 rounded shadow-sm border-0">
    <h4 class="mb-3 fw-bold text-dark">Dasar Hukum & Wilayah Kerja</h4>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card border-0 bg-white p-4 h-100 shadow-sm">
          <h6 class="mb-3 text-danger">Dasar Hukum</h6>
          <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($content['legal_basis'] ?: 'Dasar hukum LPMK ditetapkan oleh peraturan kelurahan dan kesepakatan masyarakat setempat.')) ?></p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border-0 bg-white p-4 h-100 shadow-sm">
          <h6 class="mb-3 text-danger">Wilayah Kerja</h6>
          <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($content['work_area'] ?: 'Wilayah kerja LPMK adalah seluruh RW dan RT di Kelurahan Kedungpane sesuai mandat kelurahan.')) ?></p>
        </div>
      </div>
    </div>
  </div>

  <?php if($content['notes']): ?>
  <div class="mb-5">
    <div class="alert alert-info"><?= nl2br(htmlspecialchars($content['notes'])) ?></div>
  </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>