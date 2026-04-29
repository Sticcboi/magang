<?php
require_once __DIR__ . '/auth.php'; 

// 1. PROSES UPDATE DATA PROFIL
if (isset($_POST['btn_simpan_profil'])) {
    $nama_lurah    = mysqli_real_escape_string($conn, $_POST['nama_lurah']);
    $jabatan_lurah = mysqli_real_escape_string($conn, $_POST['jabatan_lurah']);
    $teks_sambutan = mysqli_real_escape_string($conn, $_POST['teks_sambutan']);
    $jml_penduduk  = (int)$_POST['jml_penduduk'];
    $jml_rw        = (int)$_POST['jml_rw'];
    $jml_rt        = (int)$_POST['jml_rt'];
    $luas_wilayah  = (int)$_POST['luas_wilayah'];
    $iframe_map    = mysqli_real_escape_string($conn, $_POST['iframe_map']);
    $batas_utara   = mysqli_real_escape_string($conn, $_POST['batas_utara']);
    $batas_selatan = mysqli_real_escape_string($conn, $_POST['batas_selatan']);
    $batas_timur   = mysqli_real_escape_string($conn, $_POST['batas_timur']);
    $batas_barat   = mysqli_real_escape_string($conn, $_POST['batas_barat']);

    $foto_query = "";
    if ($_FILES['foto_lurah']['name'] != '') {
        $foto_nama = time() . '_' . $_FILES['foto_lurah']['name'];
        $foto_tmp  = $_FILES['foto_lurah']['tmp_name'];
        $path_db   = "img/" . $foto_nama;
        $path_upload = "../img/" . $foto_nama; 
        
        if (move_uploaded_file($foto_tmp, $path_upload)) {
            $foto_query = ", foto_lurah = '$path_db'";
        }
    }

    $query = "UPDATE profil_kelurahan SET 
                nama_lurah = '$nama_lurah', 
                jabatan_lurah = '$jabatan_lurah', 
                teks_sambutan = '$teks_sambutan',
                jml_penduduk = $jml_penduduk, 
                jml_rw = $jml_rw, 
                jml_rt = $jml_rt, 
                luas_wilayah = $luas_wilayah,
                iframe_map = '$iframe_map', 
                batas_utara = '$batas_utara', 
                batas_selatan = '$batas_selatan',
                batas_timur = '$batas_timur', 
                batas_barat = '$batas_barat' 
                $foto_query
              WHERE id = 1";

    if (mysqli_query($conn, $query)) {
        $msg = "Konten beranda berhasil diperbarui!";
    }
}

// Tambah kolom urutan pada banner jika belum ada
$col_check = mysqli_query($conn, "SHOW COLUMNS FROM banner_beranda LIKE 'urutan'");
if (mysqli_num_rows($col_check) == 0) {
    mysqli_query($conn, "ALTER TABLE banner_beranda ADD COLUMN urutan int(11) DEFAULT 0 AFTER is_active");
    // Set urutan awal berdasarkan ID
    $banners_init = mysqli_query($conn, "SELECT id FROM banner_beranda ORDER BY id ASC");
    $pos = 0;
    while ($b = mysqli_fetch_assoc($banners_init)) {
        mysqli_query($conn, "UPDATE banner_beranda SET urutan=$pos WHERE id={$b['id']}");
        $pos++;
    }
}

// AJAX: Update urutan banner via drag & drop
if (isset($_POST['action']) && $_POST['action'] === 'update_banner_order') {
    header('Content-Type: application/json');
    $order = json_decode($_POST['order'] ?? '[]', true);
    if (is_array($order)) {
        foreach ($order as $pos => $id) {
            $id = (int)$id;
            $urutan = (int)$pos;
            mysqli_query($conn, "UPDATE banner_beranda SET urutan=$urutan WHERE id=$id");
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// 2. PROSES TAMBAH BANNER
if (isset($_POST['btn_tambah_banner'])) {
    if ($_FILES['gambar_banner']['name'] != '') {
        $foto_tmp  = $_FILES['gambar_banner']['tmp_name'];
        
        $image_info = getimagesize($foto_tmp);
        if ($image_info !== false) {
            $width = $image_info[0];
            $height = $image_info[1];
            
            if ($width == 4000 && $height == 1250) {
                $foto_nama = time() . '_banner_' . $_FILES['gambar_banner']['name'];
                $path_db   = "img/" . $foto_nama;
                $path_upload = "../img/" . $foto_nama; 
                
                if (move_uploaded_file($foto_tmp, $path_upload)) {
                    // Urutan = posisi terakhir + 1
                    $last = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(urutan) as mx FROM banner_beranda"));
                    $new_urutan = ($last['mx'] ?? 0) + 1;
                    mysqli_query($conn, "INSERT INTO banner_beranda (gambar, urutan) VALUES ('$path_db', $new_urutan)");
                    $msg = "Foto Banner Slider berhasil ditambahkan!";
                } else {
                    $msg = "GAGAL: Terjadi kesalahan saat mengunggah file.";
                }
            } else {
                $msg = "GAGAL: Ukuran resolusi banner harus tepat 4000 x 1250 piksel! (Gambar Anda: $width x $height)";
            }
        } else {
            $msg = "GAGAL: File yang diunggah bukan format gambar yang valid.";
        }
    }
}

// 3. PROSES HAPUS BANNER
if (isset($_POST['btn_hapus_banner'])) {
    $id_banner = (int)$_POST['id_banner'];
    
    // Hapus file fisik
    $get_b = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM banner_beranda WHERE id = $id_banner"));
    if($get_b && file_exists("../" . $get_b['gambar'])) {
        unlink("../" . $get_b['gambar']);
    }
    
    // Hapus dari database
    mysqli_query($conn, "DELETE FROM banner_beranda WHERE id = $id_banner");
    $msg = "Banner berhasil dihapus!";
}

// 4. PROSES EDIT LAYANAN (Hanya Edit)
if (isset($_POST['btn_edit_layanan'])) {
    $id_layanan   = (int)$_POST['id_layanan'];
    $nama_layanan = mysqli_real_escape_string($conn, $_POST['nama_layanan']);
    $link_url     = mysqli_real_escape_string($conn, $_POST['link_url']);

    mysqli_query($conn, "UPDATE layanan_administrasi SET nama_layanan = '$nama_layanan', link_url = '$link_url' WHERE id = $id_layanan");
    $msg = "Layanan berhasil diperbarui!";
}

$profil = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil_kelurahan WHERE id = 1"));

// Ambil data layanan
$query_layanan = mysqli_query($conn, "SELECT * FROM layanan_administrasi");
$layanan_list = [];
while($row = mysqli_fetch_assoc($query_layanan)) {
    $layanan_list[] = $row;
}

// Ambil data Banner (diurutkan berdasarkan urutan)
$query_banner = mysqli_query($conn, "SELECT * FROM banner_beranda ORDER BY urutan ASC, id ASC");
$banner_list = [];
while($row = mysqli_fetch_assoc($query_banner)) {
    $banner_list[] = $row;
}

$page_title = 'Kelola Halaman Utama';
$current_page = 'admin_halaman';
include 'admin_header.php';
?>

<style>
    .ck-editor__editable { min-height: 200px; border-bottom-left-radius: 10px !important; border-bottom-right-radius: 10px !important; }
    .ck-toolbar { border-top-left-radius: 10px !important; border-top-right-radius: 10px !important; background-color: #f8f9fa !important; }
    
    .table-layanan { border-collapse: separate !important; border-spacing: 0 8px !important; }
    .table-layanan th { color: #2c3e50; font-size: 0.9rem; padding-bottom: 8px; border: none !important; border-bottom: 2px solid #e9ecef !important; }
    .table-layanan td { vertical-align: middle !important; border-top: 1px solid #f8f9fa !important; border-bottom: 1px solid #f8f9fa !important; padding: 12px 10px !important; background: transparent; }

    /* BUG FIX MODAL */
    .modal-backdrop { display: none !important; }
    .modal { background: rgba(0, 0, 0, 0.6) !important; z-index: 105000 !important; }

    /* BANNER DRAG & DROP */
    .banner-sortable { display: flex; flex-wrap: wrap; gap: 16px; }
    .banner-item { position: relative; width: calc(33.333% - 11px); transition: all 0.2s; }
    .banner-item .drag-handle-banner { 
        position: absolute; top: 8px; left: 8px; z-index: 2;
        background: rgba(0,0,0,0.6); color: #fff; border: none; border-radius: 8px;
        padding: 4px 8px; cursor: grab; font-size: 0.8rem; backdrop-filter: blur(4px);
    }
    .banner-item .drag-handle-banner:active { cursor: grabbing; }
    .banner-item .banner-order-badge {
        position: absolute; top: 8px; right: 8px; z-index: 2;
        background: rgba(0,0,0,0.6); color: #fff; border-radius: 50%;
        width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem; backdrop-filter: blur(4px);
    }
    .sortable-ghost .card { opacity: 0.4; border: 2px dashed #dc3545 !important; }
    .sortable-chosen .card { box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important; }
    .banner-toast {
        display: none; position: fixed; bottom: 30px; right: 30px; z-index: 9999;
        background: #198754; color: #fff; padding: 12px 24px; border-radius: 50px;
        box-shadow: 0 5px 20px rgba(25,135,84,0.4); font-weight: 600; font-size: 0.9rem;
        animation: bannerSlideUp 0.3s ease;
    }
    @keyframes bannerSlideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>

<div class="page-header mb-4">
    <h2 class="fw-bold text-dark">Pengaturan Halaman Utama</h2>
    <p class="text-muted">Kelola konten dinamis beranda (index.php) dalam satu panel kendali.</p>
</div>

<?php if(isset($msg)): ?>
    <div class="alert <?= strpos($msg, 'GAGAL') !== false ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
        <i class="fa-solid <?= strpos($msg, 'GAGAL') !== false ? 'fa-circle-xmark' : 'fa-circle-check' ?> me-2"></i> <?= htmlspecialchars($msg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-danger border-bottom pb-2">
                    <i class="fa-solid fa-images me-2"></i>Kelola Banner (Slider Gambar)
                </h5>
                <form action="" method="POST" enctype="multipart/form-data" class="mb-3 d-flex gap-2">
                    <input type="file" class="form-control" name="gambar_banner" accept="image/*" required>
                    <button type="submit" name="btn_tambah_banner" class="btn btn-primary fw-bold text-nowrap rounded-3">
                        <i class="fa-solid fa-upload me-1"></i> Upload Banner
                    </button>
                </form>
                <div class="d-flex flex-column gap-1 mb-3">
                    <p class="text-danger small m-0"><i class="fa-solid fa-circle-exclamation me-1"></i> Resolusi gambar wajib persis <strong>4000 x 1250</strong> piksel.</p>
                   </div>

                <div class="banner-sortable" id="bannerSortable">
                    <?php foreach($banner_list as $i => $ban): ?>
                    <div class="banner-item" data-id="<?= $ban['id'] ?>">
                        <button type="button" class="drag-handle-banner" title="Seret untuk ubah urutan">
                            <i class="fa-solid fa-grip-vertical"></i>
                        </button>
                        <span class="banner-order-badge"><?= $i + 1 ?></span>
                        <div class="card rounded-3 shadow-sm border-0 overflow-hidden">
                            <img src="../<?= htmlspecialchars($ban['gambar']) ?>" class="card-img-top" style="height: 120px; object-fit: cover;">
                            <div class="p-2 text-center bg-light">
                                <form action="" method="POST" class="m-0 form-hapus">
                                    <input type="hidden" name="id_banner" value="<?= $ban['id'] ?>">
                                    <button type="submit" name="btn_hapus_banner" class="btn btn-sm btn-outline-danger w-100 rounded-pill">
                                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if(empty($banner_list)): ?>
                        <div class="col-12 text-center text-muted small py-3">Belum ada banner terunggah.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <form action="" method="POST" enctype="multipart/form-data">
                    <h5 class="fw-bold mb-4 text-danger border-bottom pb-2">
                        <i class="fa-solid fa-user-tie me-2"></i>Sambutan & Profil Kelurahan
                    </h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Nama Lurah</label>
                            <input type="text" class="form-control" name="nama_lurah" value="<?= htmlspecialchars($profil['nama_lurah']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Jabatan</label>
                            <input type="text" class="form-control" name="jabatan_lurah" value="<?= htmlspecialchars($profil['jabatan_lurah']) ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Teks Sambutan</label>
                        <textarea class="form-control" id="editor_sambutan" name="teks_sambutan" rows="6"><?= htmlspecialchars($profil['teks_sambutan']) ?></textarea>
                    </div>

                    <div class="row mb-4 align-items-center bg-light p-3 rounded-3 mx-0">
                        <div class="col-md-2 text-center">
                            <img src="../<?= htmlspecialchars($profil['foto_lurah']) ?>" class="rounded-circle border border-3 border-white shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <div class="col-md-10">
                            <label class="form-label fw-semibold small">Ganti Foto Lurah</label>
                            <input type="file" class="form-control form-control-sm" name="foto_lurah" accept="image/*">
                        </div>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5 text-danger border-bottom pb-2">
                        <i class="fa-solid fa-chart-line me-2"></i>Statistik (Sekilas Kedungpane)
                    </h5>
                    <div class="row g-3 mb-4 text-center">
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold text-muted">Jml Penduduk</label>
                            <input type="number" class="form-control text-center fw-bold" name="jml_penduduk" value="<?= $profil['jml_penduduk'] ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold text-muted">Jml RW</label>
                            <input type="number" class="form-control text-center fw-bold" name="jml_rw" value="<?= $profil['jml_rw'] ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold text-muted">Jml RT</label>
                            <input type="number" class="form-control text-center fw-bold" name="jml_rt" value="<?= $profil['jml_rt'] ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small fw-bold text-muted">Luas Wilayah (Ha)</label>
                            <input type="number" class="form-control text-center fw-bold" name="luas_wilayah" value="<?= $profil['luas_wilayah'] ?>">
                        </div>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5 text-danger border-bottom pb-2">
                        <i class="fa-solid fa-map-location-dot me-2"></i>Peta & Batas Wilayah
                    </h5>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Iframe Google Maps</label>
                        <textarea class="form-control" name="iframe_map" rows="3" style="font-family: monospace; font-size: 12px;"><?= htmlspecialchars($profil['iframe_map']) ?></textarea>
                    </div>
                    <div class="row g-2 mb-4">
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" name="batas_utara" value="<?= htmlspecialchars($profil['batas_utara']) ?>" placeholder="Batas Utara">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" name="batas_selatan" value="<?= htmlspecialchars($profil['batas_selatan']) ?>" placeholder="Batas Selatan">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" name="batas_timur" value="<?= htmlspecialchars($profil['batas_timur']) ?>" placeholder="Batas Timur">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control form-control-sm" name="batas_barat" value="<?= htmlspecialchars($profil['batas_barat']) ?>" placeholder="Batas Barat">
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" name="btn_simpan_profil" class="btn btn-danger py-3 fw-bold shadow-sm rounded-pill">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan Beranda
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 20px;">
            <div class="card-body p-4">
                <div class="mb-3 border-bottom pb-3">
                    <h5 class="fw-bold text-danger mb-0">
                        <i class="fa-solid fa-link me-2"></i>Link Layanan
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-layanan w-100 m-0 align-middle">
                        <thead>
                            <tr>
                                <th style="width: 75%;">Nama Layanan</th>
                                <th class="text-end" style="width: 25%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($layanan_list as $layanan): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;"><?= htmlspecialchars($layanan['nama_layanan']) ?></div>
                                    <div class="text-truncate text-muted" style="max-width: 170px; font-size: 0.7rem;" title="<?= htmlspecialchars($layanan['link_url']) ?>">
                                        <?= htmlspecialchars($layanan['link_url']) ?>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $layanan['id'] ?>" title="Edit">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php foreach($layanan_list as $layanan): ?>
    <div class="modal fade" id="modalEdit<?= $layanan['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="id_layanan" value="<?= $layanan['id'] ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Layanan</label>
                            <input type="text" class="form-control" name="nama_layanan" value="<?= htmlspecialchars($layanan['nama_layanan']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">URL Link / Tujuan</label>
                            <input type="text" class="form-control" name="link_url" value="<?= htmlspecialchars($layanan['link_url']) ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="btn_edit_layanan" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Toast notifikasi banner -->
<div class="banner-toast" id="bannerToast">
    <i class="fa-solid fa-check-circle me-2"></i>Urutan banner berhasil disimpan!
</div>

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // CKEditor
        ClassicEditor
            .create(document.querySelector('#editor_sambutan'), {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
            })
            .catch(error => { console.error(error); });

        // Banner Drag & Drop
        const bannerList = document.getElementById('bannerSortable');
        if (bannerList && bannerList.children.length > 0) {
            new Sortable(bannerList, {
                handle: '.drag-handle-banner',
                animation: 250,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function(evt) {
                    // Abaikan jika posisi tidak berubah
                    if (evt.oldIndex === evt.newIndex) return;

                    // Update nomor urut visual
                    const items = bannerList.querySelectorAll('.banner-item');
                    items.forEach((item, i) => {
                        const badge = item.querySelector('.banner-order-badge');
                        if (badge) badge.textContent = i + 1;
                    });

                    // Kirim urutan baru ke server
                    const ids = Array.from(items).map(el => el.dataset.id);
                    const formData = new FormData();
                    formData.append('action', 'update_banner_order');
                    formData.append('order', JSON.stringify(ids));

                    fetch('admin_halaman.php', { method: 'POST', body: formData })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const toast = document.getElementById('bannerToast');
                                toast.style.display = 'block';
                                setTimeout(() => { toast.style.display = 'none'; }, 2000);
                            }
                        })
                        .catch(err => console.error('Gagal menyimpan urutan:', err));
                }
            });
        }
    });
</script>

<?php include 'admin_footer.php'; ?>