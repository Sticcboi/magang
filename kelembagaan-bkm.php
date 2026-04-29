<?php
$page_title = 'BKM';
include 'includes/header.php';
require_once 'admin/kelembagaan_model.php';

$page_key = 'bkm';
$content = get_kelembagaan_page($conn, $page_key);
$staff = get_kelembagaan_staff($conn, $page_key);
$units = get_kelembagaan_units($conn, $page_key);
$programs = get_kelembagaan_programs($conn, $page_key);
?>

<section class="container my-5">
  <div class="text-center mb-5">
    <h1 class="fw-bold text-dark mb-2">Badan Keswadayaan Masyarakat (BKM)</h1>
    <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill">BKM Kelurahan Kedungpane</span>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-lg-6">
      <div class="card h-100 border-0 shadow-sm hover-card rounded-4 p-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="icon-box bg-danger text-white me-3 p-2 rounded">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
            </div>
            <h5 class="card-title fw-bold text-dark mb-0">Profil Singkat</h5>
          </div>
          <p class="text-secondary lh-lg mb-0"><?= nl2br(htmlspecialchars($content['overview'] ?: 'Belum ada data profil BKM.')) ?></p>
        </div>
      </div>
    </div>
    
    <div class="col-lg-6">
      <div class="card h-100 border-0 shadow-sm hover-card rounded-4 p-3">
        <div class="card-body">
          <div class="d-flex align-items-center mb-3">
            <div class="icon-box bg-danger text-white me-3 p-2 rounded">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
            </div>
            <h5 class="card-title fw-bold text-dark mb-0">Visi & Misi</h5>
          </div>
          <div class="mb-3">
            <strong class="text-dark">Visi:</strong>
            <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($content['visi'] ?: '-')) ?></p>
          </div>
          <div>
            <strong class="text-dark">Misi:</strong>
            <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($content['misi'] ?: '-')) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-light h-100">
        <h6 class="fw-bold text-danger border-bottom border-danger pb-2 mb-3">Dasar Hukum</h6>
        <div class="text-secondary mb-0">
          <?= nl2br(htmlspecialchars($content['legal_basis'] ?: 'Belum ada data dasar hukum.')) ?>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-light h-100">
        <h6 class="fw-bold text-danger border-bottom border-danger pb-2 mb-3">Wilayah Kerja</h6>
        <div class="text-secondary mb-0">
          <?= nl2br(htmlspecialchars($content['work_area'] ?: 'Belum ada data wilayah kerja.')) ?>
        </div>
      </div>
    </div>
  </div>

  <div class="mb-5 bg-white p-4 rounded-4 shadow-sm border-0">
    <h4 class="mb-4 fw-bold text-dark">Struktur Kepengurusan</h4>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-danger text-danger">
          <tr>
            <th class="py-3 rounded-start">Jabatan</th>
            <th class="py-3">Nama</th>
            <th class="py-3 rounded-end">Kontak</th>
          </tr>
        </thead>
        <tbody class="border-top-0">
          <?php if(count($staff)): foreach($staff as $row): ?>
          <tr>
            <td><span class="badge bg-light text-secondary border px-2 py-1"><?= htmlspecialchars($row['role']) ?></span></td>
            <td class="fw-medium text-dark"><?= htmlspecialchars($row['name']) ?></td>
            <td class="text-secondary"><?= htmlspecialchars($row['contact']) ?></td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada data pengurus.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mb-5">
    <h4 class="mb-4 fw-bold text-dark">Unit Pengelola & Program</h4>
    <div class="row g-4">
        <div class="col-md-6">
            <h5 class="fw-bold mb-3 text-secondary">Unit Pengelola Inti</h5>
            <div class="accordion shadow-sm rounded-4 overflow-hidden" id="unitsAccordion">
              <?php if(count($units)): foreach($units as $index => $unit): ?>
              <div class="accordion-item border-0 border-bottom">
                <h2 class="accordion-header" id="headingU<?= $index ?>">
                  <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?> fw-medium text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseU<?= $index ?>">
                    <?= htmlspecialchars($unit['title']) ?>
                  </button>
                </h2>
                <div id="collapseU<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#unitsAccordion">
                  <div class="accordion-body text-secondary">
                    <?= nl2br(htmlspecialchars($unit['description'])) ?>
                  </div>
                </div>
              </div>
              <?php endforeach; else: ?>
              <div class="alert alert-light">Belum ada unit pengelola.</div>
              <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <h5 class="fw-bold mb-3 text-secondary">Program Kerja Utama</h5>
            <ul class="list-group shadow-sm">
                <?php if(count($programs)): foreach($programs as $prog): ?>
                <li class="list-group-item p-3 border-0 border-bottom">
                    <strong><?= htmlspecialchars($prog['title']) ?></strong>
                    <p class="mb-0 text-secondary small mt-1"><?= nl2br(htmlspecialchars($prog['description'])) ?></p>
                </li>
                <?php endforeach; else: ?>
                <li class="list-group-item text-muted">Belum ada program kerja.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>