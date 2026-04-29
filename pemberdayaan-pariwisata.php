<?php
$page_title = 'Pariwisata Kelurahan Kedungpane';
include 'includes/header.php';

// --- TAMBAHKAN: Ambil data dinamis dari database ---
/* Pastikan 'includes/header.php' menyediakan koneksi $conn.
   Jika tidak, sesuaikan dengan koneksi database Anda. */
$profil_q = mysqli_query($conn, "SELECT * FROM profil_pariwisata WHERE id=1");
$profil = mysqli_fetch_assoc($profil_q);

$kontak_q = mysqli_query($conn, "SELECT * FROM kontak_pariwisata WHERE id=1");
$kontak = mysqli_fetch_assoc($kontak_q);

$query_destinasi = mysqli_query($conn, "SELECT * FROM destinasi_wisata ORDER BY id DESC");
$query_kuliner = mysqli_query($conn, "SELECT * FROM wisata_kuliner ORDER BY id DESC");
// --- END TAMBAHKAN ---
?>

<style>
    /* Styling Dasar Mengadaptasi Page Pendidikan */
    .text-maroon { color: #8b0000; }
    
    .hero-section {
        background: linear-gradient(135deg, rgba(139,0,0,0.85), rgba(90,0,0,0.9)), url('img/waduk.jpeg') no-repeat center center;
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
    
    .list-card {
        border-left: 4px solid #8b0000;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fdfdfd;
        transition: 0.3s;
        cursor: pointer; /* Tambahan: agar kursor berubah menjadi bentuk tangan (pointer) saat di-hover */
    }
    .list-card:hover { transform: translateX(6px); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }

    /* Style Destinasi (Adaptasi dari Kabar Pendidikan) */
    .dest-compact {
        display: flex;
        align-items: flex-start;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 12px;
        margin-bottom: 15px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .dest-compact:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(139,0,0,0.08);
        border-color: #ffcccc;
    }
    .dest-compact-img {
        width: 170px;
        height: 125px;
        border-radius: 16px;
        object-fit: cover;
        flex-shrink: 0;
    }
    .dest-compact-body {
        padding-left: 18px;
        flex-grow: 1;
        min-width: 0;
    }
    .badge-kat {
        font-size: 0.75rem;
        background: #8b0000;
        color: #fff;
        padding: 6px 12px;
        border-radius: 999px;
        text-transform: uppercase;
        font-weight: 700;
        display: inline-flex;
        margin-bottom: 10px;
    }
    .dest-compact.kuliner-card {
        border-color: rgba(139, 0, 0, 0.12);
        background: #ffffff;
    }
    .dest-compact-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
    }
    .dest-compact-desc {
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dest-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        font-size: 0.9rem;
        color: #666;
    }
    .dest-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .dest-compact-desc {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .dest-meta {
        font-size: 0.75rem;
        color: #999;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .list-card {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        flex-wrap: nowrap;
    }

    .list-card-img {
        width: 140px;
        height: 120px;
        object-fit: cover;
        border-radius: 16px;
        flex-shrink: 0;
        border: 1px solid #f0f0f0;
        background: #fff;
    }

    .list-card-body {
        flex: 1;
        min-width: 0;
    }

    .list-card-body p {
        margin-bottom: 0.65rem;
    }

    .list-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 8px;
        color: #666;
        font-size: 0.8rem;
    }

    .list-card-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Search Bar Styling */
    .search-container {
        display: flex;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border-radius: 50px;
        overflow: hidden;
        margin-bottom: 30px;
        background: white;
    }
    .search-input {
        border: none;
        padding: 15px 25px;
        width: 100%;
        outline: none;
    }
    .search-btn {
        background: #8b0000;
        color: white;
        border: none;
        padding: 0 30px;
        font-weight: bold;
        transition: 0.3s;
    }
    .search-btn:hover { background: #660000; }
</style>

<div class="container my-4">
    <div class="hero-section text-center">
        <h1 class="fw-bold mb-3">Pariwisata Kelurahan Kedungpane</h1>
        <p class="fs-5 opacity-75 mb-0 mx-auto" style="max-width: 700px;">
            <?php
            // gunakan slogan / informasi singkat jika ada, fallback teks statis
            echo htmlspecialchars($profil['informasi_singkat'] ?? $kontak['informasi_singkat'] ?? 'Menjelajahi harmoni alam dan kekayaan budaya lokal di jantung Kedungpane.');
            ?>
        </p>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            
            <div class="search-container">
                <input type="text" id="searchWisata" class="search-input" placeholder="Cari destinasi wisata (contoh: Masjid Kapal, Waduk Jatibarang)...">
                <button id="btnSearch" class="search-btn"><i class="bi bi-search me-2"></i>Cari</button>
            </div>

            <div class="card card-custom">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3 border-bottom pb-3"><i class="bi bi-info-circle text-maroon me-2"></i>Profil Pariwisata</h2>
                    <p class="text-muted" style="font-size: 0.95rem; text-align: justify;">
                        <?= nl2br(htmlspecialchars($profil['deskripsi_singkat'] ?? 'Kelurahan Kedungpane memiliki kombinasi wisata alam di sepanjang waduk, situs budaya tradisional, dan berbagai spot edukatif yang populer bagi pelancong lokal.')) ?>
                    </p>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-maroon">Visi</h6>
                            <p class="small text-muted"><?= nl2br(htmlspecialchars($profil['visi'] ?? 'Mewujudkan pariwisata berbasis komunitas yang mandiri dan berkelanjutan.')) ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-maroon">Misi</h6>
                            <p class="small text-muted"><?= nl2br(htmlspecialchars($profil['misi'] ?? 'Pelestarian aset budaya, pengembangan infrastruktur wisata terpadu, dan pemberdayaan ekonomi kreatif UMKM.')) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4"><i class="bi bi-geo-alt text-maroon me-2"></i>Daftar Destinasi Wisata</h2>
                    
                    <?php if(mysqli_num_rows($query_destinasi) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query_destinasi)): ?>
                            <?php
                                $img = !empty($row['gambar']) ? $row['gambar'] : "https://placehold.co/150x150/8b0000/FFFFFF?text=Wisata";
                                $kategori = !empty($row['kategori']) ? $row['kategori'] : "Lainnya";
                                $maps_data = $row['maps_url'] ?? '';
                                $nama = $row['nama'] ?? 'Destinasi';
                            ?>
                            <div class="dest-compact" data-maps="<?= htmlspecialchars($maps_data) ?>" onclick="setMapFromData(this.dataset.maps, '<?= htmlspecialchars(addslashes($nama)) ?>')">
                                <img src="<?= htmlspecialchars($img) ?>" class="dest-compact-img" alt="<?= htmlspecialchars($nama) ?>" onerror="this.src='https://placehold.co/150x150/8b0000/FFFFFF?text=Wisata'">
                                <div class="dest-compact-body">
                                    <span class="badge-kat"><?= htmlspecialchars($kategori) ?></span>
                                    <div class="dest-compact-title"><?= htmlspecialchars($nama) ?></div>
                                    <div class="dest-compact-desc"><?= htmlspecialchars($row['deskripsi'] ?? '') ?></div>
                                    <div class="dest-meta">
                                        <span>📍 <?= htmlspecialchars(trim($row['alamat'] ?? '') !== '' ? $row['alamat'] : 'Alamat belum diisi') ?></span>
                                        <span>⏰ <?= htmlspecialchars($row['jam_buka'] ?? '-') ?> | 🎟️ <?= htmlspecialchars($row['harga_tiket'] ?? '-') ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">Belum ada destinasi wisata.</div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="card card-custom">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-4 border-bottom pb-3"><i class="bi bi-shop text-maroon me-2"></i>Wisata Kuliner</h2>
                    <div class="row">
                        <?php if(mysqli_num_rows($query_kuliner) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_kuliner)): ?>
                                <?php
                                    $kulinerNama = htmlspecialchars($row['nama'] ?? 'Wisata Kuliner');
                                    $kulinerDesc = htmlspecialchars($row['deskripsi'] ?? '');
                                    $kulinerAlamat = trim($row['alamat'] ?? '');
                                    $kulinerJam = trim($row['jam_buka'] ?? '');
                                    $kulinerHarga = trim($row['harga_tiket'] ?? '');
                                    $kulinerMaps = trim($row['maps_url'] ?? '');
                                    $kulinerImage = trim($row['gambar'] ?? '');
                                    if ($kulinerImage === '') {
                                        $kulinerImage = 'https://placehold.co/170x125/8b0000/FFFFFF?text=Kuliner';
                                    }
                                ?>
                                <div class="col-12">
                                    <div class="dest-compact kuliner-card" data-maps="<?= htmlspecialchars($kulinerMaps) ?>" data-label="<?= htmlspecialchars(addslashes($kulinerNama), ENT_QUOTES) ?>" onclick="setMapFromData(this.dataset.maps, this.dataset.label)">
                                        <img src="<?= htmlspecialchars($kulinerImage) ?>" class="dest-compact-img" alt="<?= $kulinerNama ?>" onerror="this.src='https://placehold.co/170x125/8b0000/FFFFFF?text=Kuliner'">
                                        <div class="dest-compact-body">
                                            <span class="badge-kat">Kuliner</span>
                                            <div class="dest-compact-title"><?= $kulinerNama ?></div>
                                            <div class="dest-compact-desc"><?= $kulinerDesc ?></div>
                                            <div class="dest-meta">
                                                <?php if ($kulinerAlamat !== ''): ?>
                                                    <span>📍 <?= htmlspecialchars($kulinerAlamat) ?></span>
                                                <?php endif; ?>
                                                <span>⏰ <?= htmlspecialchars($kulinerJam !== '' ? $kulinerJam : 'Belum mulai') ?></span>
                                                <span>🎟️ <?= htmlspecialchars($kulinerHarga !== '' ? $kulinerHarga : 'Belum mulai') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="text-center text-muted py-4">Belum ada data wisata kuliner.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-5">
            <div>
                <h2 class="h5 fw-bold mb-3"><i class="bi bi-map text-maroon me-2"></i>Peta Lokasi Wisata</h2>
                <div class="card card-custom overflow-hidden border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="ratio ratio-4x3">
                            <!-- Awal: peta default, akan diupdate oleh setMap / setMapFromData -->
                            <iframe id="wisataMap" src="https://maps.google.com/maps?q=Kedungpane+Semarang&t=&z=14&ie=UTF8&iwloc=&output=embed" style="border: 0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 text-center pb-3">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Klik destinasi di samping atau gunakan pencarian untuk melihat rute peta.</small>
                    </div>
                </div>

                <div class="card card-custom bg-dark text-white mt-4">
                    <div class="card-body p-4">
                        <h2 class="h5 fw-bold text-white mb-4 border-bottom border-secondary pb-3"><i class="bi bi-telephone me-2"></i>Kontak & Informasi</h2>
                        
                        <h6 class="text-warning">Kantor Pengelola</h6>
                        <p class="small mb-1"><i class="bi bi-building me-2"></i><?= htmlspecialchars($kontak['pengelola_destinasi'] ?? 'Jl. Mawar No.12, Kedungpane') ?></p>
                        <p class="small mb-1"><i class="bi bi-telephone-fill me-2"></i><?= htmlspecialchars($kontak['kontak_pengelola'] ?? '(021) 555-0123') ?></p>
                        <p class="small mb-4"><i class="bi bi-envelope-fill me-2"></i><?= htmlspecialchars($kontak['email'] ?? 'pariwisata@kedungpane.go.id') ?></p>

                        <h6 class="text-warning">Media Sosial</h6>
                        <p class="small mb-1"><i class="bi bi-instagram me-2"></i><?= htmlspecialchars($kontak['instagram'] ?? '@kedungpane_pariwisata') ?></p>
                        <p class="small mb-1"><i class="bi bi-facebook me-2"></i><?= htmlspecialchars($kontak['facebook'] ?? 'Kelurahan Kedungpane') ?></p>
                        <p class="small mb-0 text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Darurat: <?= htmlspecialchars($kontak['nomor_penting'] ?? '110 / 118') ?></p>
                    </div>
                </div>
            </div>
        </div> 

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const searchInput = document.getElementById('searchWisata');
        const btnSearch = document.getElementById('btnSearch');
        const wisataMap = document.getElementById('wisataMap');

        // Fungsi utama: menerima query teks (dicari via google) => gunakan embed query
        window.setMap = function(query) {
            const formattedQuery = encodeURIComponent(query);
            wisataMap.src = `https://maps.google.com/maps?q=${formattedQuery}&z=15&output=embed`;
            if(window.innerWidth < 992) {
                wisataMap.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        };

        // Fungsi membantu: menerima data maps (bisa URL penuh atau kosong) dan label
        window.setMapFromData = function(mapData, label) {
            const fallbackQuery = (label || 'Wisata') + " Kedungpane Semarang";
            if (!mapData || mapData.trim() === '') {
                setMap(fallbackQuery);
                return;
            }
            const val = mapData.trim();
            if (val.startsWith('http')) {
                try {
                    const url = new URL(val);
                    if (val.includes('/maps/embed')) {
                        wisataMap.src = val;
                        scrollToMap();
                        return;
                    }
                    if (url.hostname.includes('google')) {
                        const qParam = url.searchParams.get('q') || url.searchParams.get('query');
                        if (qParam && qParam.trim() !== '') {
                            setMap(qParam);
                            return;
                        }

                        const path = url.pathname;
                        const placeMatch = path.match(/\/place\/([^/]+)/);
                        if (placeMatch) {
                            const place = decodeURIComponent(placeMatch[1]).replace(/\+/g, ' ');
                            setMap(place);
                            return;
                        }
                        const searchMatch = path.match(/\/search\/([^/]+)/);
                        if (searchMatch) {
                            const search = decodeURIComponent(searchMatch[1]).replace(/\+/g, ' ');
                            setMap(search);
                            return;
                        }

                        const coordMatch = val.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                        if (coordMatch) {
                            setMap(coordMatch[1] + ',' + coordMatch[2]);
                            return;
                        }
                    }
                } catch (e) {
                    // ignore and fallback to query parsing
                }
                setMap(fallbackQuery);
            } else {
                setMap(val + " Kedungpane Semarang");
            }
        };

        function scrollToMap() {
            if(window.innerWidth < 992) {
                wisataMap.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        // Event Tombol Pencarian
        btnSearch.addEventListener('click', () => {
            const val = searchInput.value.trim();
            if(val !== "") {
                const mapQuery = val.toLowerCase().includes('kedungpane') || val.toLowerCase().includes('semarang') 
                                 ? val : val + " Kedungpane Semarang";
                setMap(mapQuery);
            }
        });

        // Event Tombol Enter pada Input
        searchInput.addEventListener('keypress', (e) => {
            if(e.key === 'Enter') {
                btnSearch.click();
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>