<?php
// 1. Panggil Koneksi Database
require_once 'db_connect.php'; 

$page_title = 'Bidang Kesehatan - Kelurahan Kedungpane';
include 'includes/header.php';

// 2. Query untuk mengambil berita kesehatan
$query_kesehatan = mysqli_query($conn, "SELECT b.*, u.nama as pengupload 
    FROM berita b 
    LEFT JOIN users u ON b.penulis_id = u.id 
    WHERE b.kategori = 'kesehatan' AND b.is_published = 1 AND b.tanggal <= CURDATE() 
    ORDER BY b.tanggal DESC LIMIT 4");

// 3. Query untuk mengambil Kontak Darurat (Aktif)
$query_kontak = mysqli_query($conn, "SELECT * FROM kontak_darurat WHERE is_active = 1 ORDER BY urutan ASC, id DESC");

// 4. Query untuk mengambil Fasilitas Kesehatan (Aktif)
$query_fasilitas = mysqli_query($conn, "SELECT * FROM fasilitas_kesehatan WHERE is_active = 1 ORDER BY urutan ASC, id DESC");
?>

<style>
    .text-maroon { color: #8b0000; }
    
    .hero-section {
        background: linear-gradient(135deg, rgba(139,0,0,0.85), rgba(90,0,0,0.9)), url('https://placehold.co/1200x400/333/fff?text=Kesehatan+Kedungpane') no-repeat center center;
        background-size: cover;
        color: white;
        border-radius: 20px;
        padding: 50px 30px;
        margin-top: 20px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(139,0,0,0.15);
    }

    .card-custom {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        background: #fff;
        margin-bottom: 24px;
    }
    
    .item-card {
        border-left: 4px solid #8b0000;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fdfdfd;
        transition: 0.3s;
        text-decoration: none;
        display: block;
        color: inherit;
        cursor: pointer;
    }
    .item-card:hover { 
        transform: translateX(6px); 
        box-shadow: 0 5px 15px rgba(0,0,0,0.08); 
        background: #fffafa;
        color: inherit;
    }

    /* Style Berita */
    .news-compact {
        display: flex;
        align-items: flex-start;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .news-compact:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(139,0,0,0.08);
        border-color: #ffcccc;
    }
    .news-compact-img {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .news-compact-body {
        padding-left: 15px;
        flex-grow: 1;
        overflow: hidden;
    }
    .badge-kat {
        font-size: 0.65rem;
        background: #8b0000;
        color: #fff;
        padding: 2px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        font-weight: bold;
        display: inline-block;
        margin-bottom: 5px;
    }
    .news-compact-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.2;
    }
    .news-compact-teaser {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    .news-meta {
        font-size: 0.7rem;
        color: #999;
        display: flex;
        gap: 10px;
    }
    .news-compact:hover .news-compact-title { color: #8b0000; }

    .btn-view-all {
        border: 1px solid #8b0000;
        color: #8b0000;
        border-radius: 50px;
        padding: 8px 15px;
        width: 100%;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        display: block;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-view-all:hover { background-color: #8b0000; color: white; }
</style>

<div class="container my-4">
    <div class="hero-section text-center">
        <h1 class="fw-bold mb-3">Kesehatan Kelurahan Kedungpane</h1>
        <p class="fs-5 opacity-75 mb-0 mx-auto" style="max-width: 700px;">
            Akses informasi fasilitas, layanan gizi, jadwal posyandu, dan kabar kesehatan terbaru di lingkungan Kedungpane.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">

            <!-- Pindahkan: Informasi Program Pemerintah (sekarang di atas) -->
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3"><i class="bi bi-heart-pulse text-maroon me-2"></i>Fasilitas & Layanan Kesehatan</h2>
                    
                    <div class="mb-4">
                        <h3 class="h6 fw-bold text-maroon mb-3">Kontak Darurat</h3>
                        <?php if (mysqli_num_rows($query_kontak) > 0): ?>
                            <?php while($kontak = mysqli_fetch_assoc($query_kontak)): 
                                if (!empty($kontak['maps_url'])) {
                                    $embed_url = $kontak['maps_url'];
                                } else {
                                    $pencarian = $kontak['label'] . " " . $kontak['nama'] . " Kedungpane Semarang";
                                    $embed_url = "https://maps.google.com/maps?q=" . urlencode($pencarian) . "&output=embed";
                                }
                                $mapLabel = trim(($kontak['label'] ?? '') . ' ' . ($kontak['nama'] ?? ''));
                            ?>
                                <a href="javascript:void(0);" onclick="gantiPeta('<?= $embed_url ?>','<?= addslashes(htmlspecialchars($mapLabel, ENT_QUOTES)) ?>')" class="item-card text-decoration-none text-dark">
                                    <h4 class="fs-6 fw-bold mb-1"><?= htmlspecialchars($kontak['label']) ?> (<?= htmlspecialchars($kontak['nama']) ?>)</h4>
                                    <p class="mb-0 text-muted"><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($kontak['nomor']) ?> 
                                    <?php if($kontak['keterangan']): ?> <span class="small opacity-75">- <?= htmlspecialchars($kontak['keterangan']) ?></span> <?php endif; ?>
                                    </p>
                                    <small class="text-primary mt-1 d-inline-block"><i class="bi bi-geo-alt me-1"></i>Tampilkan di Peta</small>
                                </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted small">Belum ada data kontak darurat.</p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-2">
                        <h3 class="h6 fw-bold text-maroon mb-3">Daftar Fasilitas Kesehatan</h3>
                        <?php if (mysqli_num_rows($query_fasilitas) > 0): ?>
                            <?php while($faskes = mysqli_fetch_assoc($query_fasilitas)): 
                                if (!empty($faskes['maps_url']) && strpos($faskes['maps_url'], 'embed') !== false) {
                                    $embed_url = $faskes['maps_url'];
                                } else {
                                    $pencarian = $faskes['nama'] . " " . $faskes['alamat'] . " Kedungpane Semarang";
                                    $embed_url = "https://maps.google.com/maps?q=" . urlencode($pencarian) . "&output=embed";
                                }
                                $mapLabel = trim($faskes['nama'] ?? '');
                            ?>
                                <a href="javascript:void(0);" onclick="gantiPeta('<?= $embed_url ?>','<?= addslashes(htmlspecialchars($mapLabel, ENT_QUOTES)) ?>')" class="item-card text-decoration-none text-dark">
                                    <h4 class="fs-6 fw-bold mb-1"><?= htmlspecialchars($faskes['nama']) ?></h4>
                                    <p class="mb-0 text-muted small"><i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($faskes['alamat']) ?></p>
                                    <?php if($faskes['telepon']): ?>
                                        <p class="mb-0 text-muted small"><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($faskes['telepon']) ?></p>
                                    <?php endif; ?>
                                    <small class="text-primary mt-1 d-inline-block"><i class="bi bi-map me-1"></i>Tampilkan di Peta</small>
                                </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted small">Belum ada data fasilitas kesehatan.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Kembalikan Informasi Program Pemerintah: sekarang ditempatkan di bawah daftar fasilitas kesehatan -->
            <div class="card card-custom">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3"><i class="bi bi-info-circle text-maroon me-2"></i>Informasi Program Pemerintah</h2>
                    <ul class="text-muted mb-0" style="line-height: 1.8;">
                        <li><strong>BPJS Kesehatan:</strong> Pelayanan pengurusan BPJS PBI/Mandiri dapat dilakukan di kantor kelurahan dengan membawa KTP, KK, fotokopi surat pengantar, dan formulir pendaftaran.</li>
                        <li><strong>Surat Pengantar Kesehatan:</strong> Untuk rujukan rumah sakit atau layanan BPJS, persyaratan meliputi KTP, KK, rekomendasi RT/RW, dan bukti kunjungan sebelumnya.</li>
                        <li><strong>Bantuan Jaminan Kesehatan Daerah:</strong> Warga berhak mengajukan bantuan apabila memenuhi kriteria sosial ekonomi; hubungi Satgas Kesehatan Kelurahan untuk verifikasi data dan syarat.</li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="col-lg-5">
            <h2 class="h5 fw-bold mb-4"><i class="bi bi-newspaper me-2 text-maroon"></i>Kabar Kesehatan</h2>

            <?php 
            if($query_kesehatan && mysqli_num_rows($query_kesehatan) > 0) {
                while($berita = mysqli_fetch_assoc($query_kesehatan)):
                    $img_src = !empty($berita['gambar']) ? "uploads/berita/".$berita['gambar'] : "https://placehold.co/150x150/8b0000/FFFFFF?text=Kesehatan";
            ?>
            <a href="detail-berita.php?slug=<?= $berita['slug'] ?>" class="news-compact">
                <img src="<?= $img_src ?>" class="news-compact-img" alt="Foto Berita">
                <div class="news-compact-body">
                    <span class="badge-kat"><?= htmlspecialchars($berita['kategori']) ?></span>
                    <div class="news-compact-title"><?= htmlspecialchars($berita['judul']) ?></div>
                    <div class="news-compact-teaser"><?= htmlspecialchars($berita['ringkasan']) ?></div>
                    <div class="news-meta">
                        <span><i class="bi bi-calendar3 me-1"></i> <?= date('d M Y', strtotime($berita['tanggal'])) ?></span>
                        <span><i class="bi bi-person-fill me-1"></i> <?= htmlspecialchars($berita['pengupload'] ?? 'Admin') ?></span>
                    </div>
                </div>
            </a>
            <?php 
                endwhile; 
                // Tambahkan elemen display label peta di bawah tombol
                echo '<a href="berita.php?kategori=kesehatan" class="btn-view-all mt-3">Cek Berita Selengkapnya <i class="bi bi-arrow-right ms-1"></i></a>';
                echo '<div id="mapLabelDisplay" class="small text-muted mt-2">Peta: Kedungpane Semarang</div>';
            } else { 
            ?>
            <div class="alert alert-light border text-center p-4 rounded-4">
                <h6 class="fw-bold text-secondary">Belum Ada Kabar Kesehatan</h6>
            </div>
            <?php } ?>

            <!-- Tambahkan judul peta di atas iframe -->
            <h6 class="fw-bold text-maroon mt-3 mb-2"><i class="bi bi-geo-alt me-2"></i>Peta Lokasi</h6>

            <div class="card card-custom mt-4 overflow-hidden border-0">
                <div class="card-body p-0">
                    <div class="ratio ratio-4x3">
                        <iframe id="petaKesehatan" src="https://maps.google.com/maps?q=Kelurahan+Kedungpane+Semarang&output=embed" style="border: 0; width: 100%; height: 100%;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>

            <!-- Pindahkan Jadwal Layanan Rutin ke sini (di bawah peta) -->
            <div class="card card-custom mt-4">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3"><i class="bi bi-calendar-check text-maroon me-2"></i>Jadwal Layanan Rutin</h2>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Program</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_jadwal = mysqli_query($conn, "SELECT * FROM jadwal_layanan WHERE is_active = 1 ORDER BY urutan ASC, FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai ASC");
                                if(mysqli_num_rows($query_jadwal) > 0):
                                    while($jdl = mysqli_fetch_assoc($query_jadwal)):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($jdl['program']) ?></td>
                                    <td><?= htmlspecialchars($jdl['hari']) ?></td>
                                    <td><?= date('H:i', strtotime($jdl['jam_mulai'])) ?> - <?= date('H:i', strtotime($jdl['jam_selesai'])) ?></td>
                                    <td><?= htmlspecialchars($jdl['lokasi']) ?></td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="4" class="text-center py-3 text-muted">Belum ada jadwal rutin.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div>

<script>
    // Perbarui fungsi gantiPeta agar juga menerima label dan menampilkan di bawah tombol "Cek Berita Selengkapnya"
    function gantiPeta(url, label) {
        var iframe = document.getElementById('petaKesehatan');
        if(iframe) iframe.src = url;
        var display = document.getElementById('mapLabelDisplay');
        if(display) {
            display.textContent = label ? ('Peta: ' + label) : 'Peta: Kedungpane Semarang';
        }
    }
</script>

<?php include 'includes/footer.php'; ?>