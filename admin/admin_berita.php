<?php
require_once __DIR__ . '/auth.php';

// AUTO-FIX: Perbaiki otomatis kategori yang kosong akibat sinkronisasi database
mysqli_query($conn, "UPDATE berita SET kategori = 'umum' WHERE kategori = '' OR kategori IS NULL");

$query = "SELECT b.*, u.nama as penulis FROM berita b LEFT JOIN users u ON b.penulis_id = u.id ORDER BY b.tanggal DESC, b.id DESC";
$result = mysqli_query($conn, $query);

$page_title = 'Manajemen Berita';
$current_page = 'admin_berita';
include 'admin_header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Gaya Premium untuk Dashboard Berita */
    .page-header { background: linear-gradient(135deg, #750000, #a30000); color: white; padding: 25px 30px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(117, 0, 0, 0.2); }
    .page-header h1 { font-size: 1.8rem; font-weight: 700; margin-bottom: 5px; }
    .page-header p { margin: 0; opacity: 0.85; font-size: 0.95rem; }
    
    .card-table { background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #f0f0f0; }
    .card-table-header { padding: 20px 25px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: #fff; flex-wrap: wrap; gap: 15px; }
    .card-table-header h5 { margin: 0; font-weight: 800; color: #333; font-size: 1.25rem; }
    
    .btn-gradient-maroon { background: linear-gradient(135deg, #750000, #a30000); color: white; border: none; font-weight: 600; transition: all 0.3s ease; }
    .btn-gradient-maroon:hover { background: linear-gradient(135deg, #5a0000, #800000); color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(117,0,0,0.25); }
    
    .btn-action { border-radius: 50%; width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; border: none; font-size: 0.85rem; }
    .btn-action-pin { background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6; }
    .btn-action-pin:hover { background-color: #e2e6ea; color: #495057; transform: translateY(-2px); box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
    .btn-action-pin-active { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .btn-action-pin-active:hover { background-color: #ffe8a1; transform: translateY(-2px); box-shadow: 0 3px 8px rgba(133, 100, 4, 0.15); }
    .btn-action-edit { background-color: #e0f3ff; color: #007bff; }
    .btn-action-edit:hover { background-color: #bfe4ff; transform: translateY(-2px); box-shadow: 0 3px 8px rgba(0, 123, 255, 0.2); }
    .btn-action-delete { background-color: #ffe5e5; color: #dc3545; }
    .btn-action-delete:hover { background-color: #ffcccc; transform: translateY(-2px); box-shadow: 0 3px 8px rgba(220, 53, 69, 0.2); }

    .table > thead { background-color: #f8f9fa; }
    .table > thead > tr > th { font-weight: 700; color: #555; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; border-bottom: 2px solid #eee; padding: 15px 20px; }
    .table > tbody > tr > td { padding: 15px 20px; vertical-align: middle; border-bottom: 1px solid #f8f9fa; color: #444; }
    .table > tbody > tr:hover { background-color: #fcfcfc; }
    
    .badge-status { padding: 6px 15px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
    .badge-published { background-color: #d1e7dd; color: #0f5132; }
    .badge-scheduled { background-color: #fff3cd; color: #664d03; }
    .badge-draft { background-color: #e2e3e5; color: #41464b; }
    .badge-pinned { background-color: #fff3cd; color: #856404; font-size: 0.65rem; padding: 4px 10px; border-radius: 20px; border: 1px solid #ffeeba; display: inline-block; font-weight: 600; margin-top: 5px; }
    
    .modal-backdrop { z-index: 104999 !important; }
    .modal { z-index: 105000 !important; }

    /* FIX: Memaksa SweetAlert tampil di atas segalanya termasuk Modal Bootstrap */
    .swal2-container { z-index: 999999 !important; }

    @media (min-width: 992px) { .modal { padding-left: 260px !important; } }
    .modal-dialog { margin-top: 90px !important; max-width: 850px !important; }
    .modal-body { max-height: calc(100vh - 250px); overflow-y: auto; padding-right: 15px; }
    .modal-body::-webkit-scrollbar { width: 8px; }
    .modal-body::-webkit-scrollbar-track { background: #f8f9fa; border-radius: 10px; }
    .modal-body::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    .modal-body::-webkit-scrollbar-thumb:hover { background: #750000; }

    .ck-editor__editable_inline { min-height: 250px; border-bottom-left-radius: 8px !important; border-bottom-right-radius: 8px !important; }
    .ck-toolbar { border-top-left-radius: 8px !important; border-top-right-radius: 8px !important; }
    .ck-balloon-panel { z-index: 105005 !important; } 
    
    .form-control, .form-select { border-radius: 8px; padding: 10px 15px; border: 1px solid #ddd; }
    .form-control:focus, .form-select:focus { border-color: #750000; box-shadow: 0 0 0 0.25rem rgba(117, 0, 0, 0.15); }
</style>

<div class="page-header">
    <h1><i class="fa-solid fa-newspaper me-2"></i> Data Berita & Informasi</h1>
    <p>Kelola publikasi artikel, pengumuman, dan penjadwalan berita Kelurahan di sini.</p>
</div>

<?php 
// ==========================================
// LOGIKA POP-UP NOTIFIKASI (SWEETALERT2)
// ==========================================
if(isset($_GET['pesan'])): 
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if($_GET['pesan'] == 'berhasil_pin'): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sorotan Diperbarui!',
                text: 'Berita yang dipilih sekarang tampil sebagai sorotan utama.',
                confirmButtonColor: '#750000',
                timer: 3000,
                timerProgressBar: true
            });
        <?php elseif($_GET['pesan'] == 'gagal_ukuran'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Diupload!',
                text: 'Ukuran foto terlalu besar. Maksimal 2MB.',
                confirmButtonColor: '#750000'
            });
        <?php elseif($_GET['pesan'] == 'gagal_format'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Diupload!',
                text: 'Format foto tidak didukung. Harap gunakan JPG atau PNG.',
                confirmButtonColor: '#750000'
            });
        <?php elseif($_GET['pesan'] == 'gagal_resolusi'): ?>
            Swal.fire({
                icon: 'error',
                title: 'Resolusi Salah!',
                text: 'Gambar wajib memiliki ukuran persis 1280x720 pixel.',
                confirmButtonColor: '#750000'
            });
        <?php else: ?>
            // Untuk semua pesan berhasil lainnya (tambah, edit, hapus)
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Aksi pada data berita berhasil dieksekusi dengan baik.',
                confirmButtonColor: '#750000',
                timer: 2500,
                timerProgressBar: true
            });
        <?php endif; ?>
        
        // Membersihkan URL dari parameter '?pesan=...' agar popup tidak muncul terus saat di-refresh
        window.history.replaceState(null, null, window.location.pathname);
    });
</script>
<?php endif; ?>

<div class="card-table">
    <div class="card-table-header">
        <h5>Daftar Artikel</h5>
        <div class="d-flex gap-3 align-items-center">
            <div class="position-relative">
                <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="left: 15px; top: 50%; transform: translateY(-50%);"></i>
                <input type="text" id="searchBerita" class="form-control rounded-pill ps-5" placeholder="Cari judul berita..." onkeyup="cariBerita()" style="width: 250px;">
            </div>
            <button type="button" class="btn btn-gradient-maroon rounded-pill shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa-solid fa-plus me-1"></i> Tulis Berita Baru
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table mb-0" id="tabelBerita">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Judul Berita</th>
                    <th>Kategori</th>
                    <th>Tanggal Tayang</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $tanggal_sekarang = date('Y-m-d');
                $modals_html = ''; 

                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)): 
                        if ($row['is_published'] == 1) {
                            if ($row['tanggal'] > $tanggal_sekarang) {
                                $status_class = 'badge-scheduled';
                                $status_text = '<i class="fa-regular fa-clock"></i> TERJADWAL';
                            } else {
                                $status_class = 'badge-published';
                                $status_text = '<i class="fa-solid fa-globe"></i> PUBLIC';
                            }
                        } else {
                            $status_class = 'badge-draft';
                            $status_text = '<i class="fa-solid fa-lock"></i> PRIVATE';
                        }
                        $kategori_tampil = !empty($row['kategori']) ? $row['kategori'] : 'umum';
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <div class="fw-bold text-dark fs-6">
                            <?= htmlspecialchars($row['judul']); ?>
                            <?php if($row['is_pinned'] == 1): ?>
                                <span class="ms-2 badge-pinned"><i class="fa-solid fa-star"></i> Sorotan</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted"><i class="fa-solid fa-pen-nib me-1"></i> Penulis: <?= htmlspecialchars($row['penulis'] ?? 'Admin'); ?></small>
                    </td>
                    <td><span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;"><?= htmlspecialchars($kategori_tampil); ?></span></td>
                    <td><div class="fw-bold text-secondary"><?= date('d/m/Y', strtotime($row['tanggal'])); ?></div></td>
                    <td><span class="badge-status <?= $status_class ?>"><?= $status_text ?></span></td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center align-items-center">
                            <form action="admin_berita_aksi.php" method="POST" class="m-0">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="pin" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn-action <?= $row['is_pinned'] ? 'btn-action-pin-active' : 'btn-action-pin' ?>" title="Sorotan Utama">
                                    <i class="fa-solid fa-thumbtack"></i>
                                </button>
                            </form>
                            <button type="button" class="btn-action btn-action-edit" title="Edit Berita" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <form action="admin_berita_aksi.php" method="POST" class="m-0" id="formHapus<?= $row['id'] ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="hapus" value="<?= $row['id'] ?>">
                                <button type="button" onclick="konfirmasiHapus(<?= $row['id'] ?>)" class="btn-action btn-action-delete" title="Hapus Berita">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <?php
                    ob_start(); 
                    $opsi_status = '1';
                    $display_tgl = 'none';
                    if($row['is_published'] == 1 && $row['tanggal'] > $tanggal_sekarang) {
                        $opsi_status = 'terjadwal';
                        $display_tgl = 'block';
                    } elseif($row['is_published'] == 0) {
                        $opsi_status = '0';
                    }
                ?>
                <div class="modal fade custom-modal-escape" id="modalEdit<?= $row['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog"> 
                        <div class="modal-content border-0 rounded-4 shadow-lg">
                            <div class="modal-header" style="background: linear-gradient(135deg, #750000, #a30000); color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                <h5 class="modal-title fw-bold"><i class="fa-solid fa-pencil me-2"></i> Edit Berita</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="admin_berita_aksi.php" method="POST" enctype="multipart/form-data">
                                <div class="modal-body p-4">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Judul Berita</label>
                                        <input type="text" name="judul" class="form-control form-control-lg fs-6" value="<?= htmlspecialchars($row['judul']) ?>" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label fw-bold">Kategori</label>
                                            <select name="kategori" class="form-select">
                                                <option value="umum" <?= $kategori_tampil=='umum'?'selected':'' ?>>Umum</option>
                                                <option value="kesehatan" <?= $kategori_tampil=='kesehatan'?'selected':'' ?>>Kesehatan</option>
                                                <option value="pembangunan" <?= $kategori_tampil=='pembangunan'?'selected':'' ?>>Pembangunan</option>
                                                <option value="keamanan" <?= $kategori_tampil=='keamanan'?'selected':'' ?>>Keamanan</option>
                                                <option value="pendidikan" <?= $kategori_tampil=='pendidikan'?'selected':'' ?>>Pendidikan</option>
                                                <option value="ekonomi" <?= $kategori_tampil=='ekonomi'?'selected':'' ?>>Ekonomi</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row bg-light p-3 rounded-4 mb-4 border border-1">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold" style="color: #750000;"><i class="fa-solid fa-eye me-1"></i> Status Publikasi</label>
                                            <select name="status_publikasi" id="statusEdit<?= $row['id'] ?>" class="form-select fw-bold" onchange="toggleTanggal('Edit<?= $row['id'] ?>')">
                                                <option value="1" <?= $opsi_status=='1'?'selected':'' ?>>Langsung Terbit (Public)</option>
                                                <option value="terjadwal" <?= $opsi_status=='terjadwal'?'selected':'' ?>>Terjadwal (Pilih Tanggal)</option>
                                                <option value="0" <?= $opsi_status=='0'?'selected':'' ?>>Simpan Draft (Private)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mt-3 mt-md-0" id="grupTanggalEdit<?= $row['id'] ?>" style="display: <?= $display_tgl ?>;">
                                            <label class="form-label fw-bold" style="color: #750000;"><i class="fa-regular fa-calendar-check me-1"></i> Pilih Tanggal Tayang</label>
                                            <input type="date" name="tanggal" class="form-control" value="<?= $row['tanggal'] ?>">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Isi Berita Lengkap</label>
                                        <textarea name="isi" class="form-control editor-teks"><?= htmlspecialchars($row['isi']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Ganti Foto <span class="text-danger">(Opsi: Biarkan kosong jika tidak ingin diubah)</span></label><br>
                                        <?php if($row['gambar']): ?>
                                            <div class="mb-3">
                                                <img src="../uploads/berita/<?= $row['gambar'] ?>" class="img-thumbnail rounded-3 shadow-sm" width="180">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="gambar" class="form-control" accept="image/jpeg, image/png" onchange="cekResolusi(this)">
                                        <small class="text-danger d-block mt-1 fw-bold"><i class="fa-solid fa-circle-exclamation"></i> Resolusi foto HARUS 1280x720 pixel (Maks 2MB, JPG/PNG).</small>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="edit" class="btn btn-gradient-maroon rounded-pill px-4">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php 
                    $modals_html .= ob_get_clean();
                    endwhile; 
                } else { 
                    echo "<tr><td colspan='6' class='text-center py-5 text-muted'><i class=".'fa-solid fa-folder-open fs-1 opacity-25 mb-3'."></i><br>Belum ada data berita. Mulai tulis berita pertama Anda!</td></tr>"; 
                } 
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php echo $modals_html; ?>

<div class="modal fade custom-modal-escape" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog"> 
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header" style="background: linear-gradient(135deg, #750000, #a30000); color: white; border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus-circle me-2"></i> Tulis Berita Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admin_berita_aksi.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Judul Berita</label>
                        <input type="text" name="judul" class="form-control form-control-lg fs-6" placeholder="Masukkan judul yang menarik..." required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="umum">Umum</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="pembangunan">Pembangunan</option>
                                <option value="keamanan">Keamanan</option>
                                <option value="pendidikan">Pendidikan</option>
                                <option value="ekonomi">Ekonomi</option>
                            </select>
                        </div>
                    </div>

                    <div class="row bg-light p-3 rounded-4 mb-4 border border-1">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: #750000;"><i class="fa-solid fa-eye me-1"></i> Status Publikasi</label>
                            <select name="status_publikasi" id="statusTambah" class="form-select fw-bold" onchange="toggleTanggal('Tambah')">
                                <option value="1">Langsung Terbit (Public)</option>
                                <option value="terjadwal">Terjadwal (Pilih Tanggal)</option>
                                <option value="0">Simpan Draft (Private)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0" id="grupTanggalTambah" style="display: none;">
                            <label class="form-label fw-bold" style="color: #750000;"><i class="fa-regular fa-calendar-check me-1"></i> Pilih Tanggal Tayang</label>
                            <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Isi Berita Lengkap</label>
                        <textarea name="isi" class="form-control editor-teks" placeholder="Tuliskan detail berita di sini..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger">Foto Utama (Wajib)</label>
                        <input type="file" id="inputFotoBaru" name="gambar" class="form-control" accept="image/jpeg, image/png" required onchange="cekResolusi(this)">
                        <small class="text-danger d-block mt-1 fw-bold"><i class="fa-solid fa-circle-exclamation"></i> Wajib diisi. Resolusi foto HARUS 1280x720 pixel (Maks 2MB, JPG/PNG).</small>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-gradient-maroon rounded-pill px-4">Terbitkan Berita</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modals = document.querySelectorAll('.custom-modal-escape');
        modals.forEach(modal => {
            document.body.appendChild(modal);
        });
    });

    function cariBerita() {
        let input = document.getElementById("searchBerita").value.toLowerCase();
        let table = document.getElementById("tabelBerita");
        let tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                let textValue = td.textContent || td.innerText;
                if (textValue.toLowerCase().indexOf(input) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function toggleTanggal(id) {
        var status = document.getElementById('status' + id).value;
        var grupTgl = document.getElementById('grupTanggal' + id);
        if (status === 'terjadwal') { grupTgl.style.display = 'block'; } else { grupTgl.style.display = 'none'; }
    }

    // Pengecekan resolusi foto dengan SweetAlert2
    function cekResolusi(input) {
        if (input.files && input.files[0]) {
            var file = input.files[0];
            var img = new Image();
            var _URL = window.URL || window.webkitURL;
            img.src = _URL.createObjectURL(file);
            img.onload = function() {
                if (this.width !== 1280 || this.height !== 720) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Resolusi Tidak Sesuai!',
                        html: `Foto Anda berukuran <b>${this.width}x${this.height}px</b>.<br>Sistem hanya menerima ukuran <b>1280x720px</b>.`,
                        confirmButtonColor: '#750000'
                    });
                    input.value = ""; // Menghapus file jika salah
                }
            };
        }
    }

    // Konfirmasi Hapus Berita dengan SweetAlert2
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Berita?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formHapus' + id).submit();
            }
        })
    }

    document.querySelectorAll('.editor-teks').forEach((editorElement) => {
        ClassicEditor
            .create(editorElement, { toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ] })
            .catch(error => { console.error(error); });
    });
</script>
<?php include 'admin_footer.php'; ?>