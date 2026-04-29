<?php
session_start();
require_once 'db_connect.php';
$page_title = 'Bidang Kamtibmas';
include 'includes/header.php';
?>
<style>
  /* Base & Hero */
  .hero-kamtibmas {
    background: linear-gradient(135deg, rgba(20, 30, 48, 0.9), rgba(36, 59, 85, 0.9)), url('https://images.unsplash.com/photo-1574483745281-d10128cb59a1?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
    color: white;
    padding: 80px 20px;
    border-radius: 15px;
    margin-top: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
  }
  .hero-kamtibmas::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==') repeat;
    pointer-events: none;
  }
  
  /* Status Card */
  .status-box {
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    text-align: center;
    border-top: 5px solid #198754;
    transition: transform 0.3s ease;
  }
  .status-box:hover {
    transform: translateY(-5px);
  }
  .pulse-indicator {
    display: inline-block;
    width: 15px;
    height: 15px;
    background-color: #198754;
    border-radius: 50%;
    margin-right: 10px;
    box-shadow: 0 0 0 rgba(25, 135, 84, 0.5);
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.5); }
    70% { box-shadow: 0 0 0 15px rgba(25, 135, 84, 0); }
    100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
  }

  /* Emergency 112 Banner */
  .emergency-112 {
    background: linear-gradient(90deg, #dc3545, #b02a37);
    color: white;
    border-radius: 15px;
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
    flex-wrap: wrap;
    gap: 20px;
  }
  .emergency-icon {
    font-size: 3.5rem;
    line-height: 1;
  }
  .call-btn {
    background-color: white;
    color: #dc3545;
    font-weight: bold;
    border-radius: 30px;
    padding: 12px 30px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
  }
  .call-btn:hover {
    background-color: #f8f9fa;
    transform: scale(1.05);
    color: #b02a37;
  }

  /* Quick Report Form */
  .lapor-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    border: none;
    overflow: hidden;
  }
  .lapor-header {
    background: #f8f9fa;
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
  }
  .form-control, .form-select {
    border-radius: 10px;
    padding: 12px 15px;
    border-color: #e0e0e0;
  }
  .form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(117, 0, 0, 0.15);
    border-color: #750000;
  }
  
  /* Program Cards */
  .program-card {
    border: none;
    border-radius: 15px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    height: 100%;
    padding: 25px;
  }
  .program-card:hover {
    background: #fff;
    box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    transform: translateY(-5px);
  }
  .program-icon {
    font-size: 2.5rem;
    color: #243b55;
    margin-bottom: 15px;
  }
  
  /* Section title utility */
  .section-title {
    font-weight: 800;
    color: #243b55;
    position: relative;
    padding-bottom: 15px;
    margin-bottom: 30px;
  }
  .section-title::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 60px; height: 4px;
    background: #750000;
    border-radius: 2px;
  }
  
  /* Program details styling */
  .program-details {
    border-top: 1px solid #eee;
    padding-top: 10px;
    margin-top: 10px;
  }
  .program-details p {
    margin-bottom: 5px;
    font-size: 0.85rem;
  }
</style>

<div class="container mb-5">
  
  <div class="hero-kamtibmas text-center mb-5 mt-4">
    <div style="position: relative; z-index: 2;">
      <span class="badge bg-light text-dark mb-3 px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
        <i class="bi bi-shield-check text-success me-1"></i> Keamanan Wilayah
      </span>
      <h1 class="display-5 fw-bold mb-3">Bidang Kamtibmas</h1>
      <p class="lead mx-auto" style="max-width: 700px; opacity: 0.9;">
        Menciptakan lingkungan yang aman, tertib, dan harmonis melalui partisipasi aktif warga serta kemitraan yang kuat dengan aparat penegak hukum.
      </p>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-lg-6">
      <div class="emergency-112 h-100 p-4 p-md-5 d-flex flex-column justify-content-center text-center">
        <div class="emergency-icon mb-3" style="font-size: 4.5rem;">
          <i class="bi bi-telephone-inbound-fill"></i>
        </div>
        <div>
          <span class="badge bg-white text-danger mb-3 rounded-pill shadow-sm px-3 py-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Kontak Utama Khusus Darurat</span>
          <h2 class="fw-bold mb-2 display-6">Layanan 112</h2>
          <p class="mb-4" style="opacity: 0.9; font-size: 1.1rem;">Panggilan Bebas Pulsa Nasional<br>(Polisi, Medis, Damkar)</p>
        </div>
        <div>
          <a href="tel:112" class="btn call-btn text-decoration-none shadow px-4 py-3 fw-bold fs-5">
            <i class="bi bi-telephone-fill me-2"></i>Hubungi 112
          </a>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card lapor-card h-100">
        <div class="lapor-header">
          <h5 class="fw-bold mb-1"><i class="bi bi-whatsapp text-success me-2"></i>Aduan Cepat (WA)</h5>
          <p class="small text-muted mb-0">Bukan darurat darurat medis? Laporkan aduan via WhatsApp ke Kelurahan.</p>
        </div>
        <div class="card-body p-4">
          <form id="cepatForm">
            <div class="mb-3">
              <label class="form-label small fw-bold">Jenis Laporan</label>
              <select class="form-select" id="laporJenis">
                <option value="Gangguan Keamanan">Gangguan Keamanan / Pencurian</option>
                <option value="Tawuran / Keributan">Tawuran / Keributan</option>
                <option value="Tamu Tak Lapor 1x24 Jam">Tamu Tak Lapor (1x24 Jam)</option>
                <option value="Aktivitas Mencurigakan">Aktivitas Mencurigakan</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold">Lokasi (RT / RW / Jalan)</label>
              <input type="text" class="form-control" id="laporLokasi" placeholder="Cth: RT 02 / RW 05" required>
            </div>
            <div class="mb-4">
              <label class="form-label small fw-bold">Keterangan Singkat</label>
              <textarea class="form-control" id="laporDetail" rows="2" placeholder="Jelaskan secara ringkas..." required></textarea>
            </div>
            <div class="mb-4">
              <label class="form-label small fw-bold"><i class="bi bi-shield-lock me-1"></i>Verifikasi Keamanan</label>
              <div class="d-flex align-items-center gap-2">
                <img src="captcha_image.php" id="captchaImg" alt="CAPTCHA" class="rounded-3" style="height: 50px; width: 200px; border: 2px solid #e0e0e0; background: #141e30;">
                <input type="number" class="form-control" id="captchaAnswer" placeholder="Jawaban" required style="max-width: 110px;">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-circle" onclick="refreshCaptcha()" title="Ganti soal">
                  <i class="bi bi-arrow-clockwise"></i>
                </button>
              </div>
              <div class="mt-2">
                <small class="text-muted"><i class="bi bi-clock me-1"></i>Kode kadaluarsa dalam <span id="captchaTimer" class="fw-bold text-danger">60</span> detik</small>
              </div>
              <!-- Honeypot: bot trap -->
              <input type="text" name="website" id="honeypot" style="display:none;" tabindex="-1" autocomplete="off">
            </div>

            <button class="btn btn-success w-100 fw-bold py-2 rounded-pill" type="submit">
              <i class="bi bi-send-fill me-2"></i>Kirim WA Laporan
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <h3 class="section-title mt-5">Program Kamtibmas</h3>
  <div class="row g-4 mb-4">
    <?php
    $programs_query = mysqli_query($conn, "SELECT * FROM program_kamtibmas WHERE is_active = 1 ORDER BY id ASC");
    $programs_count = mysqli_num_rows($programs_query);
    
    if ($programs_count > 0) {
      while ($program = mysqli_fetch_assoc($programs_query)) {
        $icon_class = 'bi-shield-fill';
        if (stripos($program['nama_program'], 'FKPM') !== false || stripos($program['nama_program'], 'Polisi') !== false) {
          $icon_class = 'bi-people-fill';
        } elseif (stripos($program['nama_program'], 'Narkoba') !== false || stripos($program['nama_program'], 'Edukasi') !== false) {
          $icon_class = 'bi-shield-slash';
        } elseif (stripos($program['nama_program'], 'Tamu') !== false || stripos($program['nama_program'], 'Penduduk') !== false) {
          $icon_class = 'bi-house-door-fill';
        }
    ?>
    <div class="col-md-4">
      <div class="program-card">
        <i class="bi <?php echo $icon_class; ?> program-icon"></i>
        <h5 class="fw-bold"><?php echo htmlspecialchars($program['nama_program']); ?></h5>
        <div class="program-details mb-2">
          <?php if (!empty($program['penanggung_jawab'])): ?>
          <p class="mb-1 small text-primary">
            <i class="bi bi-person-fill me-1"></i><?php echo htmlspecialchars($program['penanggung_jawab']); ?>
          </p>
          <?php endif; ?>
          <?php if (!empty($program['waktu'])): ?>
          <p class="mb-1 small text-muted">
            <i class="bi bi-calendar-event me-1"></i><?php echo htmlspecialchars($program['waktu']); ?>
          </p>
          <?php endif; ?>
          <?php if (!empty($program['lokasi'])): ?>
          <p class="mb-1 small text-muted">
            <i class="bi bi-geo-alt-fill me-1"></i><?php echo htmlspecialchars($program['lokasi']); ?>
          </p>
          <?php endif; ?>
        </div>
        <?php if (!empty($program['keterangan'])): ?>
        <p class="text-muted small mb-0"><?php echo htmlspecialchars($program['keterangan']); ?></p>
        <?php endif; ?>
      </div>
    </div>
    <?php
      }
    } else {
    ?>
    <div class="col-12"><p class="text-center text-muted">Belum ada program Kamtibmas yang tersedia saat ini.</p></div>
    <?php } ?>
  </div>

</div>

<script>
let captchaSeconds = 60;
let captchaTimer = null;

function startCaptchaTimer() {
  captchaSeconds = 60;
  updateTimerDisplay();
  if (captchaTimer) clearInterval(captchaTimer);
  captchaTimer = setInterval(() => {
    captchaSeconds--;
    updateTimerDisplay();
    if (captchaSeconds <= 0) {
      refreshCaptcha();
    }
  }, 1000);
}

function updateTimerDisplay() {
  const el = document.getElementById('captchaTimer');
  el.textContent = captchaSeconds;
  if (captchaSeconds <= 10) {
    el.classList.add('text-danger');
    el.classList.remove('text-warning');
  } else if (captchaSeconds <= 20) {
    el.classList.add('text-warning');
    el.classList.remove('text-danger');
  } else {
    el.classList.remove('text-danger', 'text-warning');
  }
}

function refreshCaptcha() {
  document.getElementById('captchaImg').src = 'captcha_image.php?' + Date.now();
  document.getElementById('captchaAnswer').value = '';
  startCaptchaTimer();
}

// Mulai timer saat halaman load
startCaptchaTimer();

document.getElementById('cepatForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  // Honeypot check
  if (document.getElementById('honeypot').value !== '') {
    alert('Verifikasi gagal.');
    return;
  }

  const answer = document.getElementById('captchaAnswer').value.trim();
  if (!answer) {
    alert('Silakan jawab soal verifikasi keamanan.');
    return;
  }

  const btn = document.querySelector('#cepatForm button[type="submit"]');
  btn.disabled = true;
  btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Memverifikasi...';

  try {
    // Verifikasi jawaban di server
    const response = await fetch('verify_recaptcha.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ answer: answer })
    });
    const result = await response.json();

    if (result.success) {
      sendWA();
    } else {
      alert('Jawaban verifikasi salah. Silakan coba lagi.');
      refreshCaptcha();
      document.getElementById('captchaAnswer').focus();
    }
  } catch (error) {
    alert('Terjadi kesalahan. Silakan coba lagi.');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-send-fill me-2"></i>Kirim WA Laporan';
  }
});

function sendWA() {
  const jenis = document.getElementById('laporJenis').value;
  const lokasi = document.getElementById('laporLokasi').value;
  const detail = document.getElementById('laporDetail').value;
  
  const text = `*-- PENGADUAN WARGA --*%0A%0A*Kategori:* ${jenis}%0A*Lokasi Kejadian:* ${lokasi}%0A*Keterangan:* ${detail}%0A%0A_Aduan ini dikirim melalui sistem Lapor Cepat Kelurahan_`;
  
  const phone = "<?php 
    $result = mysqli_query($conn, 'SELECT nomor_whatsapp FROM pengaturan_kamtibmas WHERE id=1');
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo !empty($row['nomor_whatsapp']) ? str_replace('+', '', $row['nomor_whatsapp']) : '';
    }
  ?>";
  
  if (!phone || phone.trim() === '') {
    alert('Maaf, layanan WhatsApp sementara tidak tersedia. Silakan hubungi kantor kelurahan langsung.');
    return;
  }
  
  window.open(`https://wa.me/${phone}?text=${text}`, '_blank');
  
  document.getElementById('cepatForm').reset();
  refreshCaptcha();
}
</script>
<?php include 'includes/footer.php'; ?>