<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/kelembagaan_model.php';
ensure_kelembagaan_tables($conn);

$page_key = 'bkm';
$page_title = 'Kelola Kelembagaan BKM';
$current_page = 'admin_kelembagaan_bkm';

// AJAX: Update urutan via drag & drop
if (isset($_POST['action']) && $_POST['action'] === 'update_order') {
    header('Content-Type: application/json');
    $table = $_POST['table'] ?? '';
    $order = json_decode($_POST['order'] ?? '[]', true);
    $allowed = ['kelembagaan_staff','kelembagaan_units','kelembagaan_programs'];
    if (in_array($table, $allowed) && is_array($order)) {
        foreach ($order as $pos => $id) {
            $id = (int)$id;
            $pos = (int)$pos;
            mysqli_query($conn, "UPDATE $table SET order_no=$pos WHERE id=$id AND page='$page_key'");
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// --- PRG: All POST/GET actions redirect to prevent duplicate on refresh ---

if (isset($_POST['update_content'])) {
    $overview = mysqli_real_escape_string($conn, $_POST['overview']);
    $visi = mysqli_real_escape_string($conn, $_POST['visi']);
    $misi = mysqli_real_escape_string($conn, $_POST['misi']);
    $legal_basis = mysqli_real_escape_string($conn, $_POST['legal_basis']);
    $work_area = mysqli_real_escape_string($conn, $_POST['work_area']);

    $exists = mysqli_query($conn, "SELECT id FROM kelembagaan_pages WHERE page = '$page_key' LIMIT 1");
    if ($exists && mysqli_num_rows($exists)) {
        mysqli_query($conn, "UPDATE kelembagaan_pages SET overview='$overview', visi='$visi', misi='$misi', legal_basis='$legal_basis', work_area='$work_area' WHERE page = '$page_key'");
    } else {
        mysqli_query($conn, "INSERT INTO kelembagaan_pages (page, overview, visi, misi, legal_basis, work_area) VALUES ('$page_key', '$overview', '$visi', '$misi', '$legal_basis', '$work_area')");
    }
    $_SESSION['flash_msg'] = 'Konten BKM berhasil disimpan.';
    header("Location: admin_kelembagaan_bkm.php#konten?status=terhapus");
    exit;
}

// Save staff (add or edit)
if (isset($_POST['save_staff'])) {
    $staff_id = (int)($_POST['staff_id'] ?? 0);
    $name = mysqli_real_escape_string($conn, $_POST['staff_name']);
    $role = mysqli_real_escape_string($conn, $_POST['staff_role']);
    $contact = mysqli_real_escape_string($conn, $_POST['staff_contact']);
    if ($staff_id) {
        mysqli_query($conn, "UPDATE kelembagaan_staff SET name='$name', role='$role', contact='$contact' WHERE id=$staff_id AND page='$page_key'");
        $_SESSION['flash_msg'] = 'Data pengurus berhasil diperbarui.';
    } else {
        $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(order_no) as mx FROM kelembagaan_staff WHERE page='$page_key'"));
        $order_no = ($last['mx'] ?? 0) + 1;
        mysqli_query($conn, "INSERT INTO kelembagaan_staff (page, name, role, contact, order_no) VALUES ('$page_key', '$name', '$role', '$contact', $order_no)");
        $_SESSION['flash_msg'] = 'Anggota kepengurusan BKM berhasil ditambahkan.';
    }
    header("Location: admin_kelembagaan_bkm.php#staff?status=terhapus");
    exit;
}

// Save unit (add or edit)
if (isset($_POST['save_unit'])) {
    $unit_id = (int)($_POST['unit_id'] ?? 0);
    $title = mysqli_real_escape_string($conn, $_POST['unit_title']);
    $description = mysqli_real_escape_string($conn, $_POST['unit_description']);
    if ($unit_id) {
        mysqli_query($conn, "UPDATE kelembagaan_units SET title='$title', description='$description' WHERE id=$unit_id AND page='$page_key'");
        $_SESSION['flash_msg'] = 'Unit pengelola berhasil diperbarui.';
    } else {
        $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(order_no) as mx FROM kelembagaan_units WHERE page='$page_key'"));
        $order_no = ($last['mx'] ?? 0) + 1;
        mysqli_query($conn, "INSERT INTO kelembagaan_units (page, title, description, order_no) VALUES ('$page_key', '$title', '$description', $order_no)");
        $_SESSION['flash_msg'] = 'Unit pengelola BKM berhasil ditambahkan.';
    }
    header("Location: admin_kelembagaan_bkm.php#unit-program?status=terhapus");
    exit;
}

// Save program (add or edit)
if (isset($_POST['save_program'])) {
    $program_id = (int)($_POST['program_id'] ?? 0);
    $title = mysqli_real_escape_string($conn, $_POST['program_title']);
    $description = mysqli_real_escape_string($conn, $_POST['program_description']);
    if ($program_id) {
        mysqli_query($conn, "UPDATE kelembagaan_programs SET title='$title', description='$description' WHERE id=$program_id AND page='$page_key'");
        $_SESSION['flash_msg'] = 'Program kerja berhasil diperbarui.';
    } else {
        $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(order_no) as mx FROM kelembagaan_programs WHERE page='$page_key'"));
        $order_no = ($last['mx'] ?? 0) + 1;
        mysqli_query($conn, "INSERT INTO kelembagaan_programs (page, title, description, order_no) VALUES ('$page_key', '$title', '$description', $order_no)");
        $_SESSION['flash_msg'] = 'Program kerja BKM berhasil ditambahkan.';
    }
    header("Location: admin_kelembagaan_bkm.php#unit-program?status=terhapus");
    exit;
}

if (isset($_GET['delete_staff'])) {
    $id = (int)$_GET['delete_staff'];
    mysqli_query($conn, "DELETE FROM kelembagaan_staff WHERE id = $id AND page = '$page_key'");
    $_SESSION['flash_msg'] = 'Anggota berhasil dihapus.';
    header("Location: admin_kelembagaan_bkm.php#staff?status=terhapus");
    exit;
}

if (isset($_GET['delete_unit'])) {
    $id = (int)$_GET['delete_unit'];
    mysqli_query($conn, "DELETE FROM kelembagaan_units WHERE id = $id AND page = '$page_key'");
    $_SESSION['flash_msg'] = 'Unit berhasil dihapus.';
    header("Location: admin_kelembagaan_bkm.php#unit-program?status=terhapus");
    exit;
}

if (isset($_GET['delete_program'])) {
    $id = (int)$_GET['delete_program'];
    mysqli_query($conn, "DELETE FROM kelembagaan_programs WHERE id = $id AND page = '$page_key'");
    $_SESSION['flash_msg'] = 'Program berhasil dihapus.';
    header("Location: admin_kelembagaan_bkm.php#unit-program?status=terhapus");
    exit;
}

// Flash message from redirect
$msg = $_SESSION['flash_msg'] ?? null;
unset($_SESSION['flash_msg']);

$content = get_kelembagaan_page($conn, $page_key);
$staff = get_kelembagaan_staff($conn, $page_key);
$units = get_kelembagaan_units($conn, $page_key);
$programs = get_kelembagaan_programs($conn, $page_key);

include 'admin_header.php';
?>

<!-- Hero Section -->
<div class="container-fluid mt-4">
    <div class="hero-bkm-admin text-center mb-5">
        <div style="position: relative; z-index: 2;">
            <span class="badge bg-light text-dark mb-3 px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.9rem;">
                <i class="fa-solid fa-building-ngo text-primary me-1"></i> Admin Panel
            </span>
            <h1 class="display-5 fw-bold mb-3 text-white">Kelola BKM Kedungpane</h1>
            <p class="lead mx-auto text-white-50" style="max-width: 700px;">
                Badan Keswadayaan Masyarakat — kelola konten, kepengurusan, unit & program kerja.
            </p>
            <div class="row g-3 mt-4 justify-content-center">
                <div class="col-auto">
                    <div class="status-box-bkm">
                        <i class="fa-solid fa-users fs-5 text-primary me-2"></i>
                        <span class="fw-bold text-primary">Pengurus</span>
                        <div class="fs-4 fw-bold text-dark mt-2"><?= count($staff) ?></div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="status-box-bkm">
                        <i class="fa-solid fa-sitemap fs-5 text-info me-2"></i>
                        <span class="fw-bold text-info">Unit</span>
                        <div class="fs-4 fw-bold text-dark mt-2"><?= count($units) ?></div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="status-box-bkm">
                        <i class="fa-solid fa-clipboard-list fs-5 text-success me-2"></i>
                        <span class="fw-bold text-success">Program</span>
                        <div class="fs-4 fw-bold text-dark mt-2"><?= count($programs) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">

<?php if(isset($msg)): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-check text-success fs-4 me-3"></i>
        <div><strong>Berhasil!</strong> <?= htmlspecialchars($msg) ?></div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- === KONTEN HALAMAN === -->
<div class="card shadow-sm border-0 mb-5" id="konten">
    <div class="card-header bg-gradient-bkm-primary text-white border-0">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-file-lines fs-5 me-3"></i>
            <div>
                <h5 class="mb-0">Konten Halaman BKM</h5>
                <small class="opacity-75">Overview, visi misi, dasar hukum, dan wilayah kerja</small>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="fa-solid fa-align-left text-primary me-1"></i>Overview Singkat</label>
                        <textarea name="overview" rows="5" class="form-control" placeholder="Deskripsi singkat tentang BKM..."><?= htmlspecialchars($content['overview']) ?></textarea>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="fa-solid fa-eye text-primary me-1"></i>Visi</label>
                            <textarea name="visi" rows="3" class="form-control" placeholder="Visi BKM..."><?= htmlspecialchars($content['visi']) ?></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="fa-solid fa-bullseye text-primary me-1"></i>Misi</label>
                            <textarea name="misi" rows="3" class="form-control" placeholder="Misi BKM..."><?= htmlspecialchars($content['misi']) ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="fa-solid fa-scale-balanced text-primary me-1"></i>Dasar Hukum</label>
                        <textarea name="legal_basis" rows="4" class="form-control" placeholder="Dasar hukum pembentukan..."><?= htmlspecialchars($content['legal_basis']) ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold"><i class="fa-solid fa-map-location-dot text-primary me-1"></i>Wilayah Kerja</label>
                        <textarea name="work_area" rows="4" class="form-control" placeholder="Wilayah kerja BKM..."><?= htmlspecialchars($content['work_area']) ?></textarea>
                    </div>
                </div>
            </div>
            <button type="submit" name="update_content" class="btn btn-primary btn-lg px-5 fw-bold">
                <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Konten
            </button>
        </form>
    </div>
</div>

<!-- === KEPENGURUSAN === -->
<div class="row g-4 mb-5" id="staff">
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 sticky-top" style="top:20px;">
            <div class="card-header bg-gradient-bkm-primary text-white border-0">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-user-plus fs-5 me-3"></i>
                    <div>
                        <h5 class="mb-0">Tambah Pengurus</h5>
                        <small class="opacity-75">Kepengurusan BKM</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" id="formStaff">
                    <input type="hidden" name="staff_id" id="staff_id" value="">
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Nama Lengkap</label>
                        <input type="text" name="staff_name" id="staff_name" class="form-control bg-light border-0" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold">Jabatan / Peran</label>
                        <input type="text" name="staff_role" id="staff_role" class="form-control bg-light border-0" placeholder="Contoh: Ketua" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold">Kontak (Opsional)</label>
                        <input type="text" name="staff_contact" id="staff_contact" class="form-control bg-light border-0" placeholder="Contoh: 0812...">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" name="save_staff" id="btnSaveStaff" class="btn btn-primary fw-bold py-2">
                            <i class="fa-solid fa-plus me-2"></i>Tambah Anggota
                        </button>
                        <button type="button" id="btnResetStaff" class="btn btn-outline-secondary py-2" style="display:none;" onclick="resetStaffForm()">Batal Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom border-light">
                <h5 class="mb-0 fw-bold text-dark py-2">Daftar Anggota Kepengurusan</h5>
                <small class="text-muted"><i class="fa-solid fa-grip-dots-vertical me-1"></i>Seret untuk mengubah urutan.</small>
            </div>
            <div class="card-body bg-light">
                <?php if(count($staff)): ?>
                <div class="sortable-list" data-table="kelembagaan_staff">
                    <?php foreach($staff as $row): ?>
                    <div class="sortable-item staff-sortable-item" data-id="<?= $row['id'] ?>">
                        <i class="fa-solid fa-grip-vertical drag-handle fs-5"></i>
                        <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width:40px;height:40px;font-weight:bold;font-size:1.2rem;">
                            <?= strtoupper(substr($row['name'], 0, 1)) ?>
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($row['name']) ?></div>
                            <div class="item-addr">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1"><?= htmlspecialchars($row['role']) ?></span>
                                <?php if($row['contact']): ?>
                                <span class="text-muted"><i class="fa-solid fa-phone ms-2 me-1"></i><?= htmlspecialchars($row['contact']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <a href="#" class="btn btn-sm btn-light text-primary edit-staff" 
                               data-id="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['name']) ?>" 
                               data-role="<?= htmlspecialchars($row['role']) ?>" data-contact="<?= htmlspecialchars($row['contact']) ?>">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="?delete_staff=<?= $row['id'] ?>" class="btn btn-sm btn-light text-danger btn-hapus">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-users-slash fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-muted">Belum ada data pengurus</h6>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- === UNIT & PROGRAM === -->
<div class="row g-4 mb-5" id="unit-program">
    <!-- Unit Pengelola -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-gradient-bkm-info text-white border-0">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-sitemap fs-5 me-3"></i>
                    <div>
                        <h5 class="mb-0">Tambah Unit Pengelola</h5>
                        <small class="opacity-75">Bidang-bidang di bawah BKM</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" id="formUnit">
                    <input type="hidden" name="unit_id" id="unit_id" value="">
                    <div class="mb-3">
                        <label class="form-label text-info fw-bold"><i class="fa-solid fa-tag me-1"></i>Judul Unit</label>
                        <input type="text" name="unit_title" id="unit_title" class="form-control" placeholder="Nama unit" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-info fw-bold"><i class="fa-solid fa-circle-info me-1"></i>Deskripsi</label>
                        <textarea name="unit_description" id="unit_description" rows="3" class="form-control" placeholder="Deskripsi tugas unit..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="save_unit" id="btnSaveUnit" class="btn btn-info text-white fw-bold flex-grow-1"><i class="fa-solid fa-plus me-2"></i>Tambah Unit</button>
                        <button type="button" id="btnResetUnit" class="btn btn-outline-secondary" style="display:none;" onclick="resetUnitForm()">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-bkm-info text-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list me-2"></i>Daftar Unit Pengelola</h6>
                <span class="badge bg-white text-info rounded-pill"><?= count($units) ?></span>
            </div>
            <div class="card-body bg-light">
                <small class="text-muted d-block mb-3"><i class="fa-solid fa-grip-dots-vertical me-1"></i>Seret untuk mengubah urutan.</small>
                <?php if(count($units)): ?>
                <div class="sortable-list" data-table="kelembagaan_units">
                    <?php foreach($units as $unit): ?>
                    <div class="sortable-item unit-card" data-id="<?= $unit['id'] ?>">
                        <i class="fa-solid fa-grip-vertical drag-handle fs-5"></i>
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($unit['title']) ?></div>
                            <div class="item-addr text-muted"><?= htmlspecialchars($unit['description']) ?></div>
                        </div>
                        <div>
                            <a href="#" class="btn btn-sm btn-light text-info edit-unit" 
                               data-id="<?= $unit['id'] ?>" data-title="<?= htmlspecialchars($unit['title']) ?>" 
                               data-description="<?= htmlspecialchars($unit['description']) ?>"><i class="fa-solid fa-pen"></i></a>
                            <a href="?delete_unit=<?= $unit['id'] ?>" class="btn btn-sm btn-light text-danger btn-hapus"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fa-solid fa-sitemap fs-1 text-muted mb-2 d-block"></i>
                    <p class="text-muted mb-0">Belum ada unit pengelola</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Program Kerja -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-gradient-bkm-success text-white border-0">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-clipboard-list fs-5 me-3"></i>
                    <div>
                        <h5 class="mb-0">Tambah Program Kerja</h5>
                        <small class="opacity-75">Program kegiatan BKM</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" id="formProgram">
                    <input type="hidden" name="program_id" id="program_id" value="">
                    <div class="mb-3">
                        <label class="form-label text-success fw-bold"><i class="fa-solid fa-tag me-1"></i>Judul Program</label>
                        <input type="text" name="program_title" id="program_title" class="form-control" placeholder="Nama program" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-success fw-bold"><i class="fa-solid fa-circle-info me-1"></i>Deskripsi</label>
                        <textarea name="program_description" id="program_description" rows="3" class="form-control" placeholder="Deskripsi kegiatan program..."></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="save_program" id="btnSaveProgram" class="btn btn-success fw-bold flex-grow-1"><i class="fa-solid fa-plus me-2"></i>Tambah Program</button>
                        <button type="button" id="btnResetProgram" class="btn btn-outline-secondary" style="display:none;" onclick="resetProgramForm()">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-gradient-bkm-success text-white border-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-list-check me-2"></i>Daftar Program Kerja</h6>
                <span class="badge bg-white text-success rounded-pill"><?= count($programs) ?></span>
            </div>
            <div class="card-body bg-light">
                <small class="text-muted d-block mb-3"><i class="fa-solid fa-grip-dots-vertical me-1"></i>Seret untuk mengubah urutan.</small>
                <?php if(count($programs)): ?>
                <div class="sortable-list" data-table="kelembagaan_programs">
                    <?php foreach($programs as $prog): ?>
                    <div class="sortable-item program-card" data-id="<?= $prog['id'] ?>">
                        <i class="fa-solid fa-grip-vertical drag-handle fs-5"></i>
                        <div class="item-info">
                            <div class="item-name"><?= htmlspecialchars($prog['title']) ?></div>
                            <div class="item-addr text-muted"><?= htmlspecialchars($prog['description']) ?></div>
                        </div>
                        <div>
                            <a href="#" class="btn btn-sm btn-light text-success edit-program"
                               data-id="<?= $prog['id'] ?>" data-title="<?= htmlspecialchars($prog['title']) ?>" 
                               data-description="<?= htmlspecialchars($prog['description']) ?>"><i class="fa-solid fa-pen"></i></a>
                            <a href="?delete_program=<?= $prog['id'] ?>" class="btn btn-sm btn-light text-danger btn-hapus"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fa-solid fa-clipboard fs-1 text-muted mb-2 d-block"></i>
                    <p class="text-muted mb-0">Belum ada program kerja</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Toast notifikasi -->
<div class="order-saved" id="orderSavedToast"><i class="fa-solid fa-check-circle me-2"></i>Urutan berhasil disimpan!</div>

<style>
.hero-bkm-admin {
    background: linear-gradient(135deg, rgba(13,110,253,0.92), rgba(11,94,215,0.88)), url('https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
    color: white; padding: 60px 20px; border-radius: 15px; margin-top: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; overflow: hidden;
}
.hero-bkm-admin::after {
    content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
    background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPjxyZWN0IHdpZHRoPSI0IiBoZWlnaHQ9IjQiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==') repeat;
    pointer-events: none;
}
.status-box-bkm {
    background: #fff; border-radius: 15px; padding: 20px 28px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.06); text-align: center;
    border-top: 4px solid var(--bs-primary); transition: transform 0.3s ease; min-width: 120px;
}
.status-box-bkm:hover { transform: translateY(-5px); }
.bg-gradient-bkm-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); }
.bg-gradient-bkm-info { background: linear-gradient(135deg, #0dcaf0 0%, #087990 100%); }
.bg-gradient-bkm-success { background: linear-gradient(135deg, #198754 0%, #146c43 100%); }

/* Sortable items */
.sortable-list { display: grid; gap: 0.6rem; }
.sortable-item {
    display: flex; align-items: center; gap: 12px; padding: 12px 15px;
    background: #fff; border: 1px solid #eee; border-radius: 10px; transition: all 0.2s;
}
.sortable-item:hover { border-color: #ddd; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
.sortable-item .item-info { flex: 1; min-width: 0; }
.sortable-item .item-name { font-weight: 600; font-size: 0.95rem; }
.sortable-item .item-addr { font-size: 0.8rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.drag-handle { cursor: grab; color: #aaa; transition: color 0.2s; }
.drag-handle:hover { color: #333; }
.drag-handle:active { cursor: grabbing; }
.sortable-ghost { opacity: 0.4; background: #e7f1ff !important; }
.sortable-chosen { box-shadow: 0 4px 15px rgba(0,0,0,0.15); }

.unit-card { border-left: 4px solid #0dcaf0 !important; }
.unit-card:hover { border-left-color: #087990 !important; box-shadow: 0 8px 25px rgba(13,202,240,0.15) !important; }
.program-card { border-left: 4px solid #198754 !important; }
.program-card:hover { border-left-color: #146c43 !important; box-shadow: 0 8px 25px rgba(25,135,84,0.15) !important; }
.staff-sortable-item { border-left: 4px solid #0d6efd !important; }
.staff-sortable-item:hover { border-left-color: #0a58ca !important; box-shadow: 0 8px 25px rgba(13,110,253,0.12) !important; }

.order-saved {
    display: none; position: fixed; bottom: 30px; right: 30px; z-index: 9999;
    background: #198754; color: #fff; padding: 12px 24px; border-radius: 50px;
    box-shadow: 0 5px 20px rgba(25,135,84,0.4); font-weight: 600; font-size: 0.9rem;
    animation: slideUp 0.3s ease;
}
@keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
// ====== DRAG & DROP ======
document.querySelectorAll('.sortable-list').forEach(list => {
    new Sortable(list, {
        handle: '.drag-handle',
        animation: 250,
        ghostClass: 'sortable-ghost',
        chosenClass: 'sortable-chosen',
        onEnd: function(evt) {
            if (evt.oldIndex === evt.newIndex) return;
            const ids = Array.from(evt.from.querySelectorAll('.sortable-item')).map(el => el.dataset.id);
            const table = evt.from.dataset.table;
            const formData = new FormData();
            formData.append('action', 'update_order');
            formData.append('table', table);
            formData.append('order', JSON.stringify(ids));
            fetch('admin_kelembagaan_bkm.php', { method: 'POST', body: formData })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const toast = document.getElementById('orderSavedToast');
                        toast.style.display = 'block';
                        setTimeout(() => { toast.style.display = 'none'; }, 2000);
                    }
                })
                .catch(err => console.error('Gagal menyimpan urutan:', err));
        }
    });
});

// ====== EDIT STAFF ======
document.querySelectorAll('.edit-staff').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('staff_id').value = this.dataset.id;
        document.getElementById('staff_name').value = this.dataset.name;
        document.getElementById('staff_role').value = this.dataset.role;
        document.getElementById('staff_contact').value = this.dataset.contact;
        document.getElementById('btnSaveStaff').innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan';
        document.getElementById('btnResetStaff').style.display = 'block';
        document.getElementById('formStaff').scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
});
function resetStaffForm() {
    document.getElementById('formStaff').reset();
    document.getElementById('staff_id').value = '';
    document.getElementById('btnSaveStaff').innerHTML = '<i class="fa-solid fa-plus me-2"></i>Tambah Anggota';
    document.getElementById('btnResetStaff').style.display = 'none';
}

// ====== EDIT UNIT ======
document.querySelectorAll('.edit-unit').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('unit_id').value = this.dataset.id;
        document.getElementById('unit_title').value = this.dataset.title;
        document.getElementById('unit_description').value = this.dataset.description;
        document.getElementById('btnSaveUnit').innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan';
        document.getElementById('btnResetUnit').style.display = 'block';
        document.getElementById('formUnit').scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
});
function resetUnitForm() {
    document.getElementById('formUnit').reset();
    document.getElementById('unit_id').value = '';
    document.getElementById('btnSaveUnit').innerHTML = '<i class="fa-solid fa-plus me-2"></i>Tambah Unit';
    document.getElementById('btnResetUnit').style.display = 'none';
}

// ====== EDIT PROGRAM ======
document.querySelectorAll('.edit-program').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('program_id').value = this.dataset.id;
        document.getElementById('program_title').value = this.dataset.title;
        document.getElementById('program_description').value = this.dataset.description;
        document.getElementById('btnSaveProgram').innerHTML = '<i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan';
        document.getElementById('btnResetProgram').style.display = 'block';
        document.getElementById('formProgram').scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
});
function resetProgramForm() {
    document.getElementById('formProgram').reset();
    document.getElementById('program_id').value = '';
    document.getElementById('btnSaveProgram').innerHTML = '<i class="fa-solid fa-plus me-2"></i>Tambah Program';
    document.getElementById('btnResetProgram').style.display = 'none';
}
</script>

<?php include 'admin_footer.php'; ?>