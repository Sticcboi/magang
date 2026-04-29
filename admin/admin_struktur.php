<?php
require_once __DIR__ . '/auth.php'; 

$status = '';
$pesan  = '';

// --- LOGIKA SIMPAN DATA (TAMBAH) ---
if (isset($_POST['tambah_anggota'])) {
    // Tambahan ?? '' agar kebal terhadap error Undefined Array Key
    $nama        = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $jabatan     = mysqli_real_escape_string($conn, $_POST['jabatan'] ?? '');
    $kategori    = mysqli_real_escape_string($conn, $_POST['kategori'] ?? ''); 
    $nip         = mysqli_real_escape_string($conn, $_POST['nip'] ?? '');
    $pangkat_gol = mysqli_real_escape_string($conn, $_POST['pangkat_gol'] ?? ''); 
    $pendidikan  = mysqli_real_escape_string($conn, $_POST['pendidikan'] ?? ''); 
    $urutan      = (int)($_POST['urutan'] ?? 0);
    $is_active   = isset($_POST['is_active']) ? 1 : 0; 
    
    $foto_path = "";
    $upload_ok = true;

    // Validasi & Upload Foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $img_info = getimagesize($_FILES['foto']['tmp_name']);
        
        // Cek apakah file adalah gambar dan dimensinya 1080x1080
        if ($img_info === false) {
            $upload_ok = false;
            $status = 'error';
            $pesan = 'File yang diunggah bukan gambar yang valid.';
        } elseif ($img_info[0] != 1080 || $img_info[1] != 1080) {
            $upload_ok = false;
            $status = 'error';
            $pesan = 'Resolusi foto tidak sesuai! Wajib tepat berukuran 1080 x 1080 pixels.';
        } else {
            $nama_file = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['foto']['name']);
            $tmp_file  = $_FILES['foto']['tmp_name'];
            $path      = "../img/" . $nama_file; 
            if (move_uploaded_file($tmp_file, $path)) {
                $foto_path = "img/" . $nama_file;
            }
        }
    }

    if ($upload_ok) {
        $sql = "INSERT INTO pegawai (nama, jabatan, kategori, nip, pangkat_gol, pendidikan, foto, urutan, is_active) 
                VALUES ('$nama', '$jabatan', '$kategori', '$nip', '$pangkat_gol', '$pendidikan', '$foto_path', '$urutan', '$is_active')";

        if (mysqli_query($conn, $sql)) {
            $status = 'success';
            $pesan  = 'Anggota berhasil ditambahkan!';
        } else {
            $status = 'error';
            $pesan  = 'Gagal menyimpan ke database: ' . mysqli_error($conn);
        }
    }
}

// --- LOGIKA EDIT DATA ---
if (isset($_POST['edit_anggota'])) {
    $id          = (int)($_POST['id'] ?? 0);
    
    // Tambahan ?? '' agar kebal terhadap error Undefined Array Key
    $nama        = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $jabatan     = mysqli_real_escape_string($conn, $_POST['jabatan'] ?? '');
    $kategori    = mysqli_real_escape_string($conn, $_POST['kategori'] ?? ''); 
    $nip         = mysqli_real_escape_string($conn, $_POST['nip'] ?? '');
    $pangkat_gol = mysqli_real_escape_string($conn, $_POST['pangkat_gol'] ?? ''); 
    $pendidikan  = mysqli_real_escape_string($conn, $_POST['pendidikan'] ?? ''); 
    $urutan      = (int)($_POST['urutan'] ?? 0);
    $is_active   = isset($_POST['is_active']) ? 1 : 0; 
    
    $update_foto = "";
    $upload_ok   = true;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $img_info = getimagesize($_FILES['foto']['tmp_name']);
        
        if ($img_info === false) {
            $upload_ok = false;
            $status = 'error';
            $pesan = 'File yang diunggah bukan gambar yang valid.';
        } elseif ($img_info[0] != 1080 || $img_info[1] != 1080) {
            $upload_ok = false;
            $status = 'error';
            $pesan = 'Resolusi foto tidak sesuai! Wajib tepat berukuran 1080 x 1080 pixels.';
        } else {
            $nama_file = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['foto']['name']);
            $tmp_file  = $_FILES['foto']['tmp_name'];
            $path      = "../img/" . $nama_file; 
            
            if (move_uploaded_file($tmp_file, $path)) {
                $foto_path = "img/" . $nama_file;
                $update_foto = ", foto = '$foto_path'";
                
                // Hapus foto lama
                $cek = mysqli_query($conn, "SELECT foto FROM pegawai WHERE id = $id");
                $data = mysqli_fetch_assoc($cek);
                if ($data && !empty($data['foto']) && file_exists("../" . $data['foto'])) {
                    unlink("../" . $data['foto']);
                }
            }
        }
    }

    if ($upload_ok) {
        $sql = "UPDATE pegawai SET 
                nama = '$nama', 
                jabatan = '$jabatan', 
                kategori = '$kategori',
                nip = '$nip', 
                pangkat_gol = '$pangkat_gol',
                pendidikan = '$pendidikan',
                urutan = '$urutan', 
                is_active = '$is_active' 
                $update_foto 
                WHERE id = $id";

        if (mysqli_query($conn, $sql)) {
            $status = 'success';
            $pesan  = 'Data anggota berhasil diperbarui!';
        } else {
            $status = 'error';
            $pesan  = 'Gagal memperbarui: ' . mysqli_error($conn);
        }
    }
}

// --- LOGIKA HAPUS DATA ---
if (isset($_POST['hapus_anggota'])) {
    $id = (int)($_POST['id'] ?? 0);
    $cek = mysqli_query($conn, "SELECT foto FROM pegawai WHERE id = $id");
    $data = mysqli_fetch_assoc($cek);
    if ($data && !empty($data['foto']) && file_exists("../" . $data['foto'])) {
        unlink("../" . $data['foto']);
    }

    $sql = "DELETE FROM pegawai WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $status = 'success';
        $pesan  = 'Anggota berhasil dihapus!';
    } else {
        $status = 'error';
        $pesan  = 'Gagal menghapus: ' . mysqli_error($conn);
    }
}

$page_title = 'Struktur Organisasi';
include 'admin_header.php';
?>

<style>
    /* Mengikuti preferensi warna putih bersih modern */
    .member-box {
        border: none;
        border-radius: 12px;
        background: #ffffff;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }
    .member-box:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        transform: translateY(-5px);
        border-color: #800000;
    }
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
    .kategori-title {
        position: relative;
        padding-left: 15px;
    }
    .kategori-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 10%;
        height: 80%;
        width: 4px;
        background-color: #800000;
        border-radius: 4px;
    }
    .modal { z-index: 9999 !important; }
    .modal-backdrop { z-index: 9998 !important; }
    .history-img-wrapper {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        border: 2px dashed #ccc;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        overflow: hidden;
    }
    .history-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }
</style>

<div class="container-fluid py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bold m-0 text-dark">Manajemen Struktur Organisasi</h3>
            <p class="text-muted">Atur susunan kepengurusan dengan tampilan kartu modern.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button class="btn btn-primary px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah" style="background-color: #800000; border: none;">
                <i class="fas fa-plus me-2"></i> Tambah Anggota
            </button>
        </div>
    </div>

    <?php
    $kategori_list = ['Top Management', 'Administrasi & Keuangan', 'Bidang / Divisi', 'Anggota / Staf', 'Lainnya'];
    
    foreach ($kategori_list as $kat):
        if ($kat === 'Lainnya') {
            $sql_kondisi = "kategori IS NULL OR kategori = '' OR kategori NOT IN ('Top Management', 'Administrasi & Keuangan', 'Bidang / Divisi', 'Anggota / Staf')";
        } else {
            $sql_kondisi = "kategori = '$kat'";
        }
        
        $query = mysqli_query($conn, "SELECT * FROM pegawai WHERE $sql_kondisi ORDER BY urutan ASC, id ASC");
        
        if (mysqli_num_rows($query) > 0):
    ?>
    
    <div class="mb-5">
        <h5 class="fw-bold text-dark mb-4 kategori-title"><?= htmlspecialchars($kat) ?></h5>
        <div class="row g-4">
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="col-md-3">
                <div class="card member-box h-100 shadow-sm <?= $row['is_active'] ? '' : 'opacity-50' ?>">
                    <div class="card-body p-4 text-center">
                        <img src="../<?= htmlspecialchars($row['foto'] ?: 'img/default-avatar.png') ?>" 
                             class="rounded-circle mb-3 shadow-sm" 
                             style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #f8f9fa;">
                        
                        <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($row['nama']) ?></h6>
                        <p class="text-danger small fw-semibold mb-2"><?= htmlspecialchars($row['jabatan']) ?></p>
                        
                        <div class="bg-light p-2 rounded mb-3 text-start">
                            <div class="small text-muted mb-1"><i class="fas fa-id-card me-1"></i> <?= htmlspecialchars($row['nip'] ?: '-') ?></div>
                            <div class="small text-muted"><i class="fas fa-graduation-cap me-1"></i> <?= htmlspecialchars($row['pendidikan'] ?: '-') ?></div>
                        </div>

                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-light btn-action text-primary border" title="Edit" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id'] ?>">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form action="" method="POST" class="d-inline form-hapus">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" name="hapus_anggota" class="btn btn-light btn-action text-danger border" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEdit<?= $row['id'] ?>" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header border-bottom-0 pt-4 px-4">
                            <h5 class="modal-title fw-bold">Edit Anggota Struktur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">NAMA</label>
                                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">KATEGORI / LEVEL</label>
                                        <select name="kategori" class="form-select" required>
                                            <option value="Top Management" <?= $row['kategori'] == 'Top Management' ? 'selected' : '' ?>>Top Management (Ketua, Wakil)</option>
                                            <option value="Administrasi & Keuangan" <?= $row['kategori'] == 'Administrasi & Keuangan' ? 'selected' : '' ?>>Administrasi & Keuangan (Sekretaris, Bendahara)</option>
                                            <option value="Bidang / Divisi" <?= $row['kategori'] == 'Bidang / Divisi' ? 'selected' : '' ?>>Bidang / Divisi (Ketua Bidang)</option>
                                            <option value="Anggota / Staf" <?= $row['kategori'] == 'Anggota / Staf' ? 'selected' : '' ?>>Anggota / Staf</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">JABATAN SPESIFIK</label>
                                        <input type="text" name="jabatan" class="form-control" value="<?= htmlspecialchars($row['jabatan']) ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">NIP</label>
                                        <input type="text" name="nip" class="form-control" value="<?= htmlspecialchars($row['nip'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">PANGKAT/GOLONGAN</label>
                                        <input type="text" name="pangkat_gol" class="form-control" value="<?= htmlspecialchars($row['pangkat_gol'] ?? '') ?>" placeholder="Cth: Penata Tk. I (III/d)">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">PENDIDIKAN TERAKHIR</label>
                                        <input type="text" name="pendidikan" class="form-control" value="<?= htmlspecialchars($row['pendidikan'] ?? '') ?>" placeholder="Cth: S1 Ilmu Pemerintahan">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">URUTAN</label>
                                        <input type="number" name="urutan" class="form-control" value="<?= $row['urutan'] ?>">
                                    </div>
                                    
                                    <div class="col-md-9 mt-4">
                                        <label class="form-label small fw-bold">FOTO SAAT INI & GANTI FOTO</label>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="history-img-wrapper flex-shrink-0">
                                                <img src="../<?= htmlspecialchars($row['foto'] ?: 'img/default-avatar.png') ?>" alt="Foto Aktif">
                                            </div>
                                            <div class="flex-grow-1">
                                                <input type="file" name="foto" class="form-control" accept="image/*">
                                                <small class="text-danger mt-1 d-block"><i class="fas fa-exclamation-circle"></i> Resolusi wajib <b>1080x1080 px</b>. Kosongkan jika tidak ganti.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3 pt-2 border-top">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="swActiveEdit<?= $row['id'] ?>" <?= $row['is_active'] ? 'checked' : '' ?>>
                                            <label class="form-check-label ms-2 small fw-bold" for="swActiveEdit<?= $row['id'] ?>">Status Tampil di Website</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-4">
                                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" name="edit_anggota" class="btn btn-primary px-4 fw-bold shadow" style="background-color: #800000; border: none;">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <?php 
        endif;
    endforeach; 
    ?>
</div>

<div class="modal fade" id="modalTambah" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Tambah Anggota Struktur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">NAMA</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">KATEGORI / LEVEL</label>
                            <select name="kategori" class="form-select" required>
                                <option value="" disabled selected>Pilih Level...</option>
                                <option value="Top Management">Top Management (Ketua, Wakil)</option>
                                <option value="Administrasi & Keuangan">Administrasi & Keuangan (Sekretaris, Bendahara)</option>
                                <option value="Bidang / Divisi">Bidang / Divisi (Ketua Bidang)</option>
                                <option value="Anggota / Staf">Anggota / Staf</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">JABATAN SPESIFIK</label>
                            <input type="text" name="jabatan" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">NIP</label>
                            <input type="text" name="nip" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">PANGKAT/GOLONGAN</label>
                            <input type="text" name="pangkat_gol" class="form-control" placeholder="Cth: Penata Tk. I (III/d)" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">PENDIDIKAN TERAKHIR</label>
                            <input type="text" name="pendidikan" class="form-control" placeholder="Cth: S1 Ilmu Pemerintahan" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">URUTAN TAMPIL</label>
                            <input type="number" name="urutan" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-9">
                            <label class="form-label small fw-bold">FOTO</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" required>
                            <small class="text-danger mt-1 d-block"><i class="fas fa-exclamation-circle"></i> Resolusi wajib <b>1080x1080 px</b>.</small>
                        </div>
                        <div class="col-md-12 mt-3 pt-2 border-top">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="swActive" checked>
                                <label class="form-check-label ms-2 small fw-bold" for="swActive">Status Tampil di Website</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_anggota" class="btn btn-primary px-4 fw-bold shadow" style="background-color: #800000; border: none;">Tambah Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Memastikan modal tidak tertutup overlay
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.modal').forEach(function(modal) {
            document.body.appendChild(modal);
        });
    });

    // Alert Handling
    <?php if ($status == 'success'): ?>
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= $pesan ?>', confirmButtonColor: '#800000' });
    <?php elseif ($status == 'error'): ?>
        Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= $pesan ?>', confirmButtonColor: '#800000' });
    <?php endif; ?>
</script>

<?php include 'admin_footer.php'; ?>