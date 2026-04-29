<?php
$page_title = 'Profil Pariwisata Kelurahan';
include 'includes/header.php';

// Fetch profil pariwisata
$profil = ['deskripsi_singkat' => '', 'sejarah_cerita_unik' => '', 'visi' => '', 'misi' => ''];
$result = mysqli_query($conn, "SELECT * FROM profil_pariwisata LIMIT 1");
if (mysqli_num_rows($result) > 0) {
    $profil = mysqli_fetch_assoc($result);
}

// Fetch destinasi wisata
$destinasi = [];
$result = mysqli_query($conn, "SELECT * FROM destinasi_wisata WHERE is_active = 1 ORDER BY kategori ASC, id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $destinasi[] = $row;
}

// Fetch wisata kuliner
$kuliner = [];
$result = mysqli_query($conn, "SELECT * FROM wisata_kuliner WHERE is_active = 1 ORDER BY urutan ASC, id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $kuliner[] = $row;
}

// Fetch kontak pariwisata
$kontak = ['pengelola_destinasi' => '', 'kontak_pengelola' => '', 'instagram' => '', 'facebook' => '', 'nomor_penting' => '', 'informasi_singkat' => ''];
$result = mysqli_query($conn, "SELECT * FROM kontak_pariwisata LIMIT 1");
if (mysqli_num_rows($result) > 0) {
    $kontak = mysqli_fetch_assoc($result);
}
?>
    <div class="container">
      <!-- MAIN COLUMN -->
      <main>
        <div class="card section">
          <h1>Profil Pariwisata Kelurahan</h1>
          <h3>A. Profil Pariwisata</h3>
          <p>
            <strong>Deskripsi singkat:</strong> <?= nl2br(htmlspecialchars($profil['deskripsi_singkat'] ?? '')) ?>
          </p>
          <p>
            <strong>Sejarah / cerita unik:</strong> <?= nl2br(htmlspecialchars($profil['sejarah_cerita_unik'] ?? '')) ?>
          </p>
          <p><strong>Visi & Misi:</strong></p>
          <ul>
            <li>
              Visi: <?= htmlspecialchars($profil['visi'] ?? '') ?>
            </li>
            <li>
              Misi: <?= htmlspecialchars($profil['misi'] ?? '') ?>
            </li>
          </ul>
        </div>

        <div class="card section">
          <h3>B. Daftar Destinasi Wisata</h3>

          <div class="dest-list">
            <?php if (count($destinasi) > 0): foreach ($destinasi as $dest): ?>
            <div class="dest">
              <img
                src="<?= htmlspecialchars($dest['gambar'] ?? 'https://via.placeholder.com/400x250?text=' . urlencode($dest['nama'])) ?>"
                alt="<?= htmlspecialchars($dest['nama']) ?>"
              />
              <div class="meta">
                <h4><?= htmlspecialchars($dest['nama']) ?></h4>
                <p>
                  <?= nl2br(htmlspecialchars($dest['deskripsi'] ?? '')) ?>
                </p>
                <p class="info">
                  <strong>Lokasi:</strong>
                  <a
                    href="<?= htmlspecialchars($dest['maps_url'] ?? '#') ?>"
                    target="_blank"
                    >Lihat di Maps</a
                  >
                  &nbsp;|&nbsp; <strong>Jam buka:</strong> <?= htmlspecialchars($dest['jam_buka'] ?? '-') ?>
                  &nbsp;|&nbsp; <strong>Harga tiket:</strong> <?= htmlspecialchars($dest['harga_tiket'] ?? '-') ?>
                </p>
              </div>
            </div>
            <?php endforeach; else: ?>
            <p class="text-muted">Belum ada destinasi wisata yang aktif.</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="card section">
          <h3>C. Wisata Kuliner</h3>
          <table class="table">
            <tr>
              <th>Jenis</th>
              <th>Contoh</th>
              <th>Lokasi / Catatan</th>
            </tr>
            <?php if (count($kuliner) > 0): foreach ($kuliner as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['jenis']) ?></td>
              <td><?= htmlspecialchars($item['contoh'] ?? '') ?></td>
              <td><?= nl2br(htmlspecialchars($item['lokasi_catatan'] ?? '')) ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada data wisata kuliner.</td></tr>
            <?php endif; ?>
          </table>
        </div>
      </main>

      <!-- SIDEBAR -->
      <aside class="sidebar">
        <div class="card">
          <h3>H. Kontak & Informasi</h3>
          <table class="table">
            <tr>
              <td class="label">Pengelola Destinasi</td>
              <td><?= htmlspecialchars($kontak['pengelola_destinasi'] ?? '') ?></td>
            </tr>
            <tr>
              <td class="label">Kontak Pengelola</td>
              <td><?= htmlspecialchars($kontak['kontak_pengelola'] ?? '') ?></td>
            </tr>
            <tr>
              <td class="label">Sosial Media</td>
              <td>
                <?php if (!empty($kontak['instagram'])): ?>
                Instagram:
                <a href="https://instagram.com/<?= htmlspecialchars(str_replace('@', '', $kontak['instagram'])) ?>" target="_blank"
                  ><?= htmlspecialchars($kontak['instagram']) ?></a><br />
                <?php endif; ?>
                <?php if (!empty($kontak['facebook'])): ?>
                Facebook: <?= htmlspecialchars($kontak['facebook']) ?>
                <?php endif; ?>
              </td>
            </tr>
            <tr>
              <td class="label">Nomor Penting</td>
              <td><?= htmlspecialchars($kontak['nomor_penting'] ?? '') ?></td>
            </tr>
          </table>
        </div>

        <div class="card">
          <h3>Informasi Singkat</h3>
          <p>
            <?= nl2br(htmlspecialchars($kontak['informasi_singkat'] ?? '')) ?>
          </p>
          <p>
            <a class="btn" href="mailto:info@kelurahan.example"
              >Hubungi via Email</a
            >
          </p>
        </div>

        <div class="card">
          <h3>Event & Pengumuman</h3>
          <p>
            <strong>Festival Panen</strong><br />Setiap bulan Agustus ada
            festival panen dan lomba kuliner lokal.
          </p>
        </div>
      </aside>
    </div>
<?php
include 'includes/footer.php';
?>

              <td>Gudeg Sari, Soto Rempah Kelurahan</td>
              <td>Disajikan di warung tradisional dan pasar pagi.</td>
            </tr>
            <tr>
              <td>UMKM Lokal</td>
              <td>Keripik Pisang Bu Ani, Sambal Buatan Warga</td>
              <td>Produk tersedia di pusat oleh-oleh kelurahan dan online.</td>
            </tr>
            <tr>
              <td>Tempat Makan Populer</td>
              <td>Kedai Bukit, Warung Sungai</td>
              <td>
                Kedai dengan pemandangan taman; warung sungai terkenal dengan
                ikan bakarnya.
              </td>
            </tr>
          </table>
        </div>
      </main>

      <!-- SIDEBAR -->
      <aside class="sidebar">
        <div class="card">
          <h3>H. Kontak & Informasi</h3>
          <table class="table">
            <tr>
              <td class="label">Pengelola Destinasi</td>
              <td>Kelompok Sadar Wisata (Pokdarwis) Kelurahan</td>
            </tr>
            <tr>
              <td class="label">Kontak Pengelola</td>
              <td>Rahmat - 0812-3456-7890</td>
            </tr>
            <tr>
              <td class="label">Sosial Media</td>
              <td>
                Instagram:
                <a href="https://instagram.com/example" target="_blank"
                  >@pariwisata_kelurahan</a
                ><br />Facebook: KelurahanWisata
              </td>
            </tr>
            <tr>
              <td class="label">Nomor Penting</td>
              <td>Darurat: 112 / Puskesmas: 024-778-1234</td>
            </tr>
          </table>
        </div>

        <div class="card">
          <h3>Informasi Singkat</h3>
          <p>
            Untuk kunjungan kelompok, silakan hubungi pengelola untuk reservasi
            dan panduan lokal. Kami mendorong wisata yang bertanggung jawab dan
            ramah lingkungan.
          </p>
          <p>
            <a class="btn" href="mailto:info@kelurahan.example"
              >Hubungi via Email</a
            >
          </p>
        </div>

        <div class="card">
          <h3>Event & Pengumuman</h3>
          <p>
            <strong>Festival Panen</strong><br />Setiap bulan Agustus ada
            festival panen dan lomba kuliner lokal.
          </p>
        </div>
      </aside>
    </div>
<?php
include 'includes/footer.php';
?>