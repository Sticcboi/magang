<?php
require_once __DIR__ . '/auth.php'; 

$pesan_notifikasi = "";

// ==========================================
// 1. PROSES CRUD DOKUMEN (informasi_publik)
// ==========================================
if (isset($_POST['simpan_dokumen'])) {
    $id                = $_POST['id_dokumen'];
    $tipe_info         = mysqli_real_escape_string($conn, $_POST['tipe_info']);
    $kategori_spesifik = mysqli_real_escape_string($conn, $_POST['kategori_spesifik']);
    $judul             = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi         = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $tanggal           = date('Y-m-d H:i:s');
    
    $file_path = "";
    if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/dokumen/'; 
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $file_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.-_]/", "", basename($_FILES['file_dokumen']['name']));
        if (move_uploaded_file($_FILES['file_dokumen']['tmp_name'], $upload_dir . $file_name)) {
            $file_path = $file_name;
        }
    }

    if (empty($id)) {
        $query = "INSERT INTO informasi_publik (tipe_info, kategori_spesifik, judul, file_dokumen, deskripsi, tanggal) 
                  VALUES ('$tipe_info', '$kategori_spesifik', '$judul', '$file_path', '$deskripsi', '$tanggal')";
    } else {
        $file_query = $file_path ? ", file_dokumen='$file_path'" : "";
        $query = "UPDATE informasi_publik SET tipe_info='$tipe_info', kategori_spesifik='$kategori_spesifik', judul='$judul', deskripsi='$deskripsi' $file_query WHERE id='$id'";
    }
    
    if (mysqli_query($conn, $query)) $pesan_notifikasi = "<div class='alert alert-success'>Data Dokumen Berhasil Disimpan!</div>";
}

// ==========================================
// 2. PROSES CRUD ARTIKEL (artikel_publik)
// ==========================================
if (isset($_POST['simpan_artikel'])) {
    $id           = $_POST['id_artikel'];
    $judul        = mysqli_real_escape_string($conn, $_POST['judul_artikel']);
    $ringkasan    = mysqli_real_escape_string($conn, $_POST['ringkasan']);
    $konten       = mysqli_real_escape_string($conn, $_POST['konten']);
    $tanggal_art  = $_POST['tanggal_artikel'];
    $is_published = $_POST['is_published'];
    $slug         = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    $img_path = "";
    if (isset($_FILES['gambar_artikel']) && $_FILES['gambar_artikel']['error'] === UPLOAD_ERR_OK) {
        $upload_dir_img = __DIR__ . '/../uploads/artikel/';
        if (!is_dir($upload_dir_img)) mkdir($upload_dir_img, 0777, true);
        $img_name = time() . '_' . basename($_FILES['gambar_artikel']['name']);
        if (move_uploaded_file($_FILES['gambar_artikel']['tmp_name'], $upload_dir_img . $img_name)) {
            $img_path = $img_name;
        }
    }

    if (empty($id)) {
        $query = "INSERT INTO artikel_publik (judul, slug, ringkasan, konten, is_published, tanggal, gambar) 
                  VALUES ('$judul', '$slug', '$ringkasan', '$konten', '$is_published', '$tanggal_art', '$img_path')";
    } else {
        $img_query = $img_path ? ", gambar='$img_path'" : "";
        $query = "UPDATE artikel_publik SET judul='$judul', slug='$slug', ringkasan='$ringkasan', konten='$konten', is_published='$is_published', tanggal='$tanggal_art' $img_query WHERE id='$id'";
    }
    
    if (mysqli_query($conn, $query)) $pesan_notifikasi = "<div class='alert alert-success'>Artikel Berhasil Disimpan!</div>";
}

// Proses Hapus
if (isset($_POST['hapus_data'])) {
    $id_hps = $_POST['id_hapus'];
    $tabel = $_POST['tabel_target'];
    mysqli_query($conn, "DELETE FROM $tabel WHERE id='$id_hps'");
    $pesan_notifikasi = "<div class='alert alert-success'>Data berhasil dihapus!</div>";
}

// Load Data
$tot_berkala = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM informasi_publik WHERE tipe_info='berkala'"))['t'] ?? 0;
$tot_artikel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as t FROM artikel_publik"))['t'] ?? 0;

$query_info = mysqli_query($conn, "SELECT * FROM informasi_publik ORDER BY tanggal DESC");
$query_artikel = mysqli_query($conn, "SELECT * FROM artikel_publik ORDER BY tanggal DESC");

$page_title = 'Admin Informasi Publik';
include 'admin_header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style>
    /* =========================================
       MATIKAN BACKDROP BOOTSTRAP YANG ERROR
       ========================================= */
    
    /* 1. Hilangkan elemen backdrop bawaan bootstrap secara paksa */
    .modal-backdrop {
        display: none !important;
    }

    /* 2. Pindahkan efek gelapnya langsung ke dalam modal pembungkusnya */
    .modal {
        background: rgba(0, 0, 0, 0.6) !important;
        z-index: 9999 !important; /* Pastikan selalu tampil paling depan menimpa navbar/sidebar */
    }

    /* 3. Perbaikan tambahan untuk Summernote agar tidak ikut error */
    .note-editor.note-frame {
        background: white;
    }
    .note-modal-backdrop {
        display: none !important; 
    }
    .note-modal {
        z-index: 10000 !important; 
    }

    /* Perbaiki modal tidak terpotong sidebar */
    .modal {
        position: fixed !important;
        top: 0;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .modal-dialog {
        max-width: min(900px, calc(100vw - 2rem)) !important;
        margin: 1.75rem auto !important;
        width: auto;
    }
    .modal-dialog.modal-xl {
        max-width: min(1140px, calc(100vw - 2rem)) !important;
    }
    .modal-content {
        box-sizing: border-box;
        overflow: hidden;
    }
    .modal-body {
        max-height: calc(100vh - 180px);
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Desain Tambahan Tabs */
    .bg-maroon { background-color: #8B0000; }
    .nav-tabs .nav-link { color: #666; border: none; border-bottom: 3px solid transparent; }
    .nav-tabs .nav-link.active { color: #8B0000; border-bottom: 3px solid #8B0000; background: none; }
</style>

<div class="container-fluid py-4">
    <?= $pesan_notifikasi; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-maroon text-white p-3 h-100">
                <small class="opacity-75">Total Dokumen</small>
                <h3 class="fw-bold mb-0 mt-1"><?= mysqli_num_rows($query_info); ?></h3>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 bg-dark text-white p-3 h-100">
                <small class="opacity-75">Total Artikel Publikasi</small>
                <h3 class="fw-bold mb-0 mt-1"><?= $tot_artikel; ?></h3>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs border-0 mb-4" id="adminTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold px-4" data-bs-toggle="tab" data-bs-target="#tab-dokumen">Daftar Dokumen</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold px-4" data-bs-toggle="tab" data-bs-target="#tab-artikel">Artikel Publikasi</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-dokumen">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Manajemen Dokumen & Informasi</h5>
                    <button class="btn btn-sm btn-primary" onclick="tambahDokumen()"><i class="fa-solid fa-plus me-1"></i> Tambah Dokumen</button>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Judul Dokumen</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($query_info) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($query_info)): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= $row['tipe_info']; ?></span></td>
                                    <td class="small fw-bold"><?= $row['kategori_spesifik']; ?></td>
                                    <td><?= $row['judul']; ?></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light text-primary shadow-sm" onclick='editDokumen(<?= json_encode($row) ?>)'><i class="fa-solid fa-pencil"></i></button>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="hapus_data" value="1">
                                            <input type="hidden" name="id_hapus" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="tabel_target" value="informasi_publik">
                                            <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-3 text-muted">Belum ada dokumen</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-artikel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Daftar Artikel Publikasi</h5>
                    <button class="btn btn-sm btn-dark" onclick="tambahArtikel()"><i class="fa-solid fa-plus me-1"></i> Tambah Artikel</button>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($query_artikel) > 0): ?>
                                <?php while($art = mysqli_fetch_assoc($query_artikel)): ?>
                                <tr>
                                    <td><img src="../uploads/artikel/<?= $art['gambar'] ?>" width="50" class="rounded shadow-sm"></td>
                                    <td class="fw-bold"><?= $art['judul']; ?></td>
                                    <td><?= $art['is_published'] ? '<span class="badge bg-success">Publish</span>' : '<span class="badge bg-warning text-dark">Draft</span>'; ?></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-light text-primary shadow-sm" onclick='editArtikel(<?= json_encode($art) ?>)'><i class="fa-solid fa-pencil"></i></button>
                                        <form action="" method="POST" class="d-inline">
                                            <input type="hidden" name="hapus_data" value="1">
                                            <input type="hidden" name="id_hapus" value="<?= $art['id'] ?>">
                                            <input type="hidden" name="tabel_target" value="artikel_publik">
                                            <button type="submit" class="btn btn-sm btn-light text-danger shadow-sm"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-3 text-muted">Belum ada artikel publikasi</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDokumen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Form Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_dokumen" id="id_dokumen">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tipe Info</label>
                        <select class="form-select" name="tipe_info" id="tipe_info">
                            <option value="berkala">Berkala</option>
                            <option value="setiap_saat">Setiap Saat</option>
                            <option value="serta_merta">Serta Merta</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label small fw-bold">Kategori Spesifik</label><input type="text" class="form-control" name="kategori_spesifik" id="kategori_spesifik" required></div>
                    <div class="mb-3"><label class="form-label small fw-bold">Judul</label><input type="text" class="form-control" name="judul" id="judul_dok" required></div>
                    <div class="mb-3"><label class="form-label small fw-bold">File (PDF/XLS)</label><input type="file" class="form-control" name="file_dokumen"></div>
                    <div class="mb-3"><label class="form-label small fw-bold">Deskripsi</label><textarea class="form-control" name="deskripsi" id="deskripsi_dok" rows="3"></textarea></div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_dokumen" class="btn btn-primary px-4">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalArtikel" tabindex="-1">
    <div class="modal-dialog modal-xl modal-fullscreen-sm-down modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold">Form Artikel Publikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_artikel" id="id_artikel">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3"><label class="form-label small fw-bold">Judul Artikel</label><input type="text" class="form-control" name="judul_artikel" id="judul_art" required></div>
                            <div class="mb-3"><label class="form-label small fw-bold">Ringkasan</label><textarea class="form-control" name="ringkasan" id="ringkasan_art" rows="2" required></textarea></div>
                            <div class="mb-3"><label class="form-label small fw-bold">Konten</label><textarea name="konten" id="editor_konten"></textarea></div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3"><label class="form-label small fw-bold">Tanggal</label><input type="date" class="form-control" name="tanggal_artikel" id="tgl_art" value="<?= date('Y-m-d') ?>"></div>
                            <div class="mb-3"><label class="form-label small fw-bold">Gambar Utama</label><input type="file" class="form-control" name="gambar_artikel"></div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Status</label>
                                <select class="form-select" name="is_published" id="status_art">
                                    <option value="1">Publish</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan_artikel" class="btn btn-dark px-4">Simpan Artikel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    // Pindahkan modal ke body agar tidak terpotong sidebar
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal').forEach(function(modal) {
            if (modal.parentNode !== document.body) {
                document.body.appendChild(modal);
            }
        });
    });

    $(document).ready(function() {
        $('#editor_konten').summernote({ 
            height: 300,
            dialogsInBody: true 
        });
    });

    // JS DOKUMEN
    function tambahDokumen() {
        $('#id_dokumen').val(''); $('#judul_dok').val(''); $('#deskripsi_dok').val('');
        new bootstrap.Modal(document.getElementById('modalDokumen')).show();
    }
    function editDokumen(d) {
        $('#id_dokumen').val(d.id); $('#tipe_info').val(d.tipe_info); $('#kategori_spesifik').val(d.kategori_spesifik);
        $('#judul_dok').val(d.judul); $('#deskripsi_dok').val(d.deskripsi);
        new bootstrap.Modal(document.getElementById('modalDokumen')).show();
    }

    // JS ARTIKEL
    function tambahArtikel() {
        $('#id_artikel').val(''); $('#judul_art').val(''); $('#ringkasan_art').val('');
        $('#editor_konten').summernote('code', '');
        new bootstrap.Modal(document.getElementById('modalArtikel')).show();
    }
    function editArtikel(a) {
        $('#id_artikel').val(a.id); $('#judul_art').val(a.judul); $('#ringkasan_art').val(a.ringkasan);
        $('#tgl_art').val(a.tanggal); $('#status_art').val(a.is_published);
        $('#editor_konten').summernote('code', a.konten);
        new bootstrap.Modal(document.getElementById('modalArtikel')).show();
    }
</script>