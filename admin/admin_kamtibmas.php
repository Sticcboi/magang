<?php
require_once __DIR__ . '/auth.php';

$page_title = 'Manajemen Bidang Kamtibmas';
$current_page = 'admin_kamtibmas';
$msg = '';

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS program_kamtibmas (
  id int(11) NOT NULL AUTO_INCREMENT,
  nama_program varchar(255) NOT NULL,
  penanggung_jawab varchar(150) DEFAULT NULL,
  waktu varchar(100) DEFAULT NULL,
  lokasi varchar(150) DEFAULT NULL,
  keterangan text DEFAULT NULL,
  is_active tinyint(1) DEFAULT 1,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS pengaturan_kamtibmas (
  id int(11) NOT NULL AUTO_INCREMENT,
  nomor_whatsapp varchar(20) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  updated_at timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM pengaturan_kamtibmas");
$row = mysqli_fetch_assoc($result);
if ($row['count'] == 0) {
    mysqli_query($conn, "INSERT INTO pengaturan_kamtibmas (nomor_whatsapp) VALUES ('')");
}

$result = mysqli_query($conn, "SELECT nomor_whatsapp FROM pengaturan_kamtibmas WHERE id=1");
$row = mysqli_fetch_assoc($result);
if (!empty($row['nomor_whatsapp']) && strpos($row['nomor_whatsapp'], '+62') !== 0) {
    $nomor_baru = '+62' . $row['nomor_whatsapp'];
    mysqli_query($conn, "UPDATE pengaturan_kamtibmas SET nomor_whatsapp='$nomor_baru' WHERE id=1");
}

if (isset($_POST['save_program'])) {
    $program_id = (int)($_POST['program_id'] ?? 0);
    $nama_program = mysqli_real_escape_string($conn, $_POST['nama_program']);
    $penanggung_jawab = mysqli_real_escape_string($conn, $_POST['penanggung_jawab']);
    $waktu = mysqli_real_escape_string($conn, $_POST['waktu']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($program_id) {
        mysqli_query($conn, "UPDATE program_kamtibmas SET nama_program='$nama_program', penanggung_jawab='$penanggung_jawab', waktu='$waktu', lokasi='$lokasi', keterangan='$keterangan', is_active=$is_active WHERE id=$program_id");
        $msg = 'Program Kamtibmas berhasil diperbarui.';
    } else {
        mysqli_query($conn, "INSERT INTO program_kamtibmas (nama_program, penanggung_jawab, waktu, lokasi, keterangan, is_active) VALUES ('$nama_program', '$penanggung_jawab', '$waktu', '$lokasi', '$keterangan', $is_active)");
        $msg = 'Program Kamtibmas baru berhasil ditambahkan.';
    }
}

if (isset($_GET['delete_program'])) {
    $id = (int)$_GET['delete_program'];
    mysqli_query($conn, "DELETE FROM program_kamtibmas WHERE id = $id");
    $msg = 'Program Kamtibmas berhasil dihapus.';
}

if (isset($_POST['save_whatsapp'])) {
    $nomor_whatsapp = trim(mysqli_real_escape_string($conn, $_POST['nomor_whatsapp']));
    if (empty($nomor_whatsapp)) {
        $msg = 'Nomor WhatsApp tidak boleh kosong.';
    } elseif (!preg_match('/^[0-9]+$/', $nomor_whatsapp)) {
        $msg = 'Nomor WhatsApp hanya boleh berisi angka.';
    } elseif (strlen($nomor_whatsapp) < 9 || strlen($nomor_whatsapp) > 14) {
        $msg = 'Nomor WhatsApp harus antara 9-14 digit (tanpa kode negara).';
    } else {
        $nomor_lengkap = '+62' . $nomor_whatsapp;
        mysqli_query($conn, "UPDATE pengaturan_kamtibmas SET nomor_whatsapp='$nomor_lengkap' WHERE id=1");
        $msg = 'Nomor WhatsApp berhasil diperbarui.';
    }
}

$programs = [];
$result = mysqli_query($conn, "SELECT * FROM program_kamtibmas ORDER BY is_active DESC, id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $programs[] = $row;
}

$settings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengaturan_kamtibmas WHERE id=1"));

include 'admin_header.php';
?>

<div class="container-fluid mt-4">
    <div class="hero-kamtibmas-admin text-center mb-5">
        <div style="position: relative; z-index: 2;">
            <span class="badge bg-light text-dark mb-3 px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                <i class="bi bi-shield-check text-success me-1"></i> Admin Panel
            </span>
            <h1 class="display-5 fw-bold mb-3 text-white">Manajemen Bidang Kamtibmas</h1>
            <p class="lead mx-auto text-white-50" style="max-width: 700px;">
                Kelola program keamanan dan ketertiban masyarakat di Kelurahan Kedungpane.
            </p>
            <div class="row g-3 mt-4 justify-content-center">
                <div class="col-auto">
                    <div class="status-box">
                        <div class="pulse-indicator"></div>
                        <span class="fw-bold text-success">Program Aktif</span>
                        <div class="fs-4 fw-bold text-dark mt-2">
                            <?= count(array_filter($programs, fn($p) => $p['is_active'])) ?>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="status-box">
                        <i class="bi bi-list-check fs-5 text-primary me-2"></i>
                        <span class="fw-bold text-primary">Total Program</span>
                        <div class="fs-4 fw-bold text-dark mt-2">
                            <?= count($programs) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
            <div>
                <strong>Berhasil!</strong> <?= htmlspecialchars($msg) ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-success text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-whatsapp fs-5 me-3"></i>
                        <div>
                            <h5 class="mb-0">Pengaturan WhatsApp Laporan Cepat</h5>
                            <small class="opacity-75">Kelola nomor WhatsApp untuk sistem laporan cepat di halaman publik</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-telephone-fill text-success me-1"></i>Nomor WhatsApp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white fw-bold">+62</span>
                                <input type="text" name="nomor_whatsapp" class="form-control form-control-lg" 
                                       placeholder="85725589778" value="<?= htmlspecialchars(str_replace('+62', '', $settings['nomor_whatsapp'] ?? '')) ?>" 
                                       pattern="[0-9]+" title="Hanya angka tanpa kode negara +62" required>
                                <span class="input-group-text">
                                    <small class="text-muted">*Wajib diisi</small>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" name="save_whatsapp" class="btn btn-success btn-lg w-100">
                                <i class="bi bi-save-fill me-2"></i>Simpan Nomor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-gradient-danger text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-plus-circle-fill fs-5 me-3"></i>
                        <div>
                            <h5 class="mb-0">Tambah / Edit Program</h5>
                            <small class="opacity-75">Kelola program keamanan dan ketertiban masyarakat</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="program_id" id="program_id" value="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-tag-fill text-danger me-1"></i>Nama Program
                            </label>
                            <input type="text" name="nama_program" id="nama_program" class="form-control form-control-lg" placeholder="Contoh: FKPM, Edukasi Anti Narkoba" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill text-danger me-1"></i>Penanggung Jawab
                            </label>
                            <input type="text" name="penanggung_jawab" id="penanggung_jawab" class="form-control" placeholder="Nama penanggung jawab program">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-calendar-event text-danger me-1"></i>Waktu / Jadwal
                                </label>
                                <input type="text" name="waktu" id="waktu" class="form-control" placeholder="Contoh: Setiap Minggu, 08.00-10.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>Lokasi
                                </label>
                                <input type="text" name="lokasi" id="lokasi" class="form-control" placeholder="Tempat pelaksanaan">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-info-circle-fill text-danger me-1"></i>Keterangan
                            </label>
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control" placeholder="Deskripsi detail program, tujuan, dan informasi lainnya"></textarea>
                        </div>
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="program_active" class="form-check-input" checked>
                                <label class="form-check-label fw-semibold" for="program_active">
                                    <i class="bi bi-toggle-on text-success me-1"></i>Aktifkan Program
                                </label>
                            </div>
                        </div>
                        <button type="submit" name="save_program" class="btn btn-danger btn-lg w-100 text-white">
                            <i class="bi bi-save-fill me-2"></i>Simpan Program
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-danger text-white border-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-list-ul fs-5 me-3"></i>
                            <div>
                                <h5 class="mb-0">Daftar Program Kamtibmas</h5>
                                <small class="opacity-75">Total: <?= count($programs) ?> program</small>
                            </div>
                        </div>
                        <span class="badge bg-white text-danger fs-6 px-3 py-2">
                            <i class="bi bi-shield-check me-1"></i>
                            <?= count($programs) ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (count($programs) > 0): ?>
                    <div class="row g-3">
                        <?php foreach ($programs as $index => $row): ?>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm hover-lift program-admin-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                                <?= $index + 1 ?>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h6 class="mb-1 fw-bold text-dark">
                                                <i class="bi bi-shield-fill text-danger me-2"></i>
                                                <?= htmlspecialchars($row['nama_program']) ?>
                                            </h6>
                                            <?php if (!empty($row['penanggung_jawab'])): ?>
                                            <p class="mb-1 text-muted small">
                                                <i class="bi bi-person-fill me-1"></i>
                                                <?= htmlspecialchars($row['penanggung_jawab']) ?>
                                            </p>
                                            <?php endif; ?>
                                            <div class="row g-2 text-sm">
                                                <?php if (!empty($row['waktu'])): ?>
                                                <div class="col-md-6">
                                                    <i class="bi bi-calendar-event text-muted me-1"></i>
                                                    <?= htmlspecialchars($row['waktu']) ?>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!empty($row['lokasi'])): ?>
                                                <div class="col-md-6">
                                                    <i class="bi bi-geo-alt-fill text-muted me-1"></i>
                                                    <?= htmlspecialchars($row['lokasi']) ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if (!empty($row['keterangan'])): ?>
                                            <p class="mb-0 text-muted small mt-2">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <?= htmlspecialchars(substr($row['keterangan'], 0, 100)) ?><?= strlen($row['keterangan']) > 100 ? '...' : '' ?>
                                            </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-auto">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge fs-6 px-3 py-2 <?= $row['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                                                    <i class="bi bi-<?= $row['is_active'] ? 'check-circle-fill' : 'x-circle-fill' ?> me-1"></i>
                                                    <?= $row['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                                </span>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item edit-program" href="#" data-id="<?= $row['id'] ?>" data-nama_program="<?= htmlspecialchars($row['nama_program'], ENT_QUOTES) ?>" data-penanggung_jawab="<?= htmlspecialchars($row['penanggung_jawab'], ENT_QUOTES) ?>" data-waktu="<?= htmlspecialchars($row['waktu'], ENT_QUOTES) ?>" data-lokasi="<?= htmlspecialchars($row['lokasi'], ENT_QUOTES) ?>" data-keterangan="<?= htmlspecialchars($row['keterangan'], ENT_QUOTES) ?>" data-active="<?= $row['is_active'] ?>">
                                                                <i class="bi bi-pencil-fill me-2"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger btn-hapus" href="?delete_program=<?= $row['id'] ?>">
                                                                <i class="bi bi-trash-fill me-2"></i>Hapus
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-shield-slash fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada program Kamtibmas</h5>
                        <p class="text-muted">Tambahkan program keamanan menggunakan form di sebelah kiri</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-kamtibmas-admin {
    background: linear-gradient(135deg, rgba(20, 30, 48, 0.9), rgba(36, 59, 85, 0.9)), url('https://images.unsplash.com/photo-1574483745281-d10128cb59a1?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
    color: white;
    padding: 60px 20px;
    border-radius: 15px;
    margin-top: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    position: relative;
    overflow: hidden;
}
.hero-kamtibmas-admin::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==') repeat;
    pointer-events: none;
}
.status-box {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    text-align: center;
    border-top: 5px solid #198754;
    transition: transform 0.3s ease;
    min-width: 120px;
}
.status-box:hover { transform: translateY(-5px); }
.pulse-indicator {
    display: inline-block;
    width: 12px; height: 12px; background-color: #198754;
    border-radius: 50%; margin-right: 8px;
    box-shadow: 0 0 0 rgba(25, 135, 84, 0.5);
    animation: pulse 2s infinite;
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.5); }
    70% { box-shadow: 0 0 0 15px rgba(25, 135, 84, 0); }
    100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
}
.program-admin-card {
    border-left: 4px solid #dc3545 !important; transition: all 0.3s ease;
}
.program-admin-card:hover {
    border-left-color: #b02a37 !important;
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.15) !important;
}
.form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.15); border-color: #dc3545;
}
</style>

<script>
    document.querySelectorAll('.edit-program').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('program_id').value = this.dataset.id;
            document.getElementById('nama_program').value = this.dataset.nama_program;
            document.getElementById('penanggung_jawab').value = this.dataset.penanggung_jawab;
            document.getElementById('waktu').value = this.dataset.waktu;
            document.getElementById('lokasi').value = this.dataset.lokasi;
            document.getElementById('keterangan').value = this.dataset.keterangan;
            document.getElementById('program_active').checked = this.dataset.active === '1';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
</script>
<?php include 'admin_footer.php'; ?>