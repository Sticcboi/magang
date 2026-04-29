<?php
$page_title = 'PKK';
include 'includes/header.php';
require_once 'admin/kelembagaan_model.php';

$page_key = 'pkk';
$content = get_kelembagaan_page($conn, $page_key);
$staff = get_kelembagaan_staff($conn, $page_key);
$units = get_kelembagaan_units($conn, $page_key);
$programs = get_kelembagaan_programs($conn, $page_key);
?>

<div class="container my-5">
  <h1 class="text-center fw-bold text-dark mb-4">Pemberdayaan Kesejahteraan Keluarga (PKK)</h1>

  <div class="row mb-4 g-4">
    <div class="col-md-6">
      <div class="card h-100 shadow-sm border-0 p-3 rounded-4">
        <div class="card-body">
          <h5 class="card-title text-danger fw-bold border-bottom pb-2">Profil PKK</h5>
          <p class="card-text text-secondary mt-3">
            <?= nl2br(htmlspecialchars($content['overview'] ?: 'Belum ada data profil PKK.')) ?>
          </p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100 shadow-sm border-0 p-3 rounded-4">
        <div class="card-body">
          <h5 class="card-title text-danger fw-bold border-bottom pb-2">Visi & Misi</h5>
          <p class="card-text mb-2 mt-3">
            <strong class="text-dark">Visi:</strong> <br>
            <span class="text-secondary"><?= nl2br(htmlspecialchars($content['visi'] ?: '-')) ?></span>
          </p>
          <p class="card-text mb-0">
            <strong class="text-dark">Misi:</strong> <br>
            <span class="text-secondary"><?= nl2br(htmlspecialchars($content['misi'] ?: '-')) ?></span>
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-5 g-4">
    <div class="col-md-7">
        <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
            <h5 class="fw-bold text-dark mb-3">Susunan Kepengurusan</h5>
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-danger text-danger">
                  <tr><th>Jabatan</th><th>Nama</th><th>Kontak</th></tr>
                </thead>
                <tbody class="border-top-0">
                  <?php if(count($staff)): foreach($staff as $row): ?>
                  <tr>
                    <td><span class="badge bg-light text-secondary border px-2 py-1"><?= htmlspecialchars($row['role']) ?></span></td>
                    <td class="fw-medium text-dark"><?= htmlspecialchars($row['name']) ?></td>
                    <td class="text-secondary"><?= htmlspecialchars($row['contact']) ?></td>
                  </tr>
                  <?php endforeach; else: ?>
                  <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada kepengurusan tersimpan.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm border-0 rounded-4 p-3 h-100">
            <h5 class="fw-bold text-dark mb-3">Program Kerja & Pokja</h5>
            <ul class="list-group list-group-flush">
                <?php if(count($programs)): foreach($programs as $prog): ?>
                <li class="list-group-item px-0">
                    <strong class="d-block text-danger"><?= htmlspecialchars($prog['title']) ?></strong>
                    <small class="text-secondary"><?= nl2br(htmlspecialchars($prog['description'])) ?></small>
                </li>
                <?php endforeach; else: ?>
                <li class="list-group-item px-0 text-muted">Belum ada program kerja.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
  </div>

  <div class="mb-4 bg-light p-4 rounded-4 shadow-sm">
    <h4 class="mb-4 text-dark fw-bold border-bottom pb-2">Informasi & Kinerja PKK</h4>
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm p-3 d-flex flex-row align-items-center">
          <div>
            <div class="fs-3 fw-bold text-dark" id="stat-kader"><?= count($staff) ?></div>
            <div class="text-secondary small">Total Pengurus/Kader Terdaftar</div>
          </div>
          <div class="ms-auto text-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z"/></svg>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm p-3 d-flex flex-row align-items-center">
          <div>
            <div class="fs-3 fw-bold text-dark" id="stat-kegiatan"><?= count($programs) ?></div>
            <div class="text-secondary small">Program Aktif (Terjadwal)</div>
          </div>
          <div class="ms-auto text-danger">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-calendar-event" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if($content['notes']): ?>
  <div class="alert alert-info border-0 shadow-sm rounded-4">
      <h6 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Catatan / Informasi Tambahan</h6>
      <?= nl2br(htmlspecialchars($content['notes'])) ?>
  </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>