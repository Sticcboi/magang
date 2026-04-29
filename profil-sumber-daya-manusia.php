<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$page_title = 'Sumber Daya Manusia - Kelurahan Kedungpane';

// Koneksi Database
if (file_exists('includes/koneksi.php')) { include 'includes/koneksi.php'; } 
else if (file_exists('../koneksi.php')) { include '../koneksi.php'; }

include 'includes/header.php';

// --- LOGIKA AMBIL DATA CHART ---
$data_usia = ['labels' => [], 'data' => []];
$data_pendidikan = ['labels' => [], 'data' => []];
$data_pekerjaan = ['labels' => [], 'data' => []];

if (isset($conn)) {
    $q_stat = mysqli_query($conn, "SELECT * FROM statistik_kelurahan ORDER BY urutan ASC");
    while($row = mysqli_fetch_assoc($q_stat)) {
        if ($row['kategori'] == 'usia') {
            $data_usia['labels'][] = $row['label'];
            $data_usia['data'][] = (int)$row['nilai'];
        } elseif ($row['kategori'] == 'pendidikan') {
            $data_pendidikan['labels'][] = $row['label'];
            $data_pendidikan['data'][] = (int)$row['nilai'];
        } elseif ($row['kategori'] == 'pekerjaan') {
            $data_pekerjaan['labels'][] = $row['label'];
            $data_pekerjaan['data'][] = (int)$row['nilai'];
        }
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    /* Style tetap sama seperti sebelumnya */
    .hero-banner { background-color: #7b0000; color: white; padding: 60px 20px; border-radius: 20px; text-align: center; margin-bottom: 40px; }
    .section-title { border-left: 5px solid #7b0000; padding-left: 15px; margin-bottom: 25px; font-weight: bold; }
    .stat-card { border-top: 4px solid #7b0000; transition: 0.3s; background: #fff; }
    .chart-container { position: relative; height: 300px; width: 100%; }
</style>

<div class="container my-5">
    <div class="hero-banner">
        <h1 class="fw-bold">Sumber Daya Manusia</h1>
        <p>Data statistik kependudukan dan profil lembaga Kelurahan Kedungpane.</p>
    </div>

    <h3 class="section-title">A. Data Statistik Kependudukan</h3>
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-4">
            <ul class="nav nav-pills mb-4" id="statistikTab">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#usia">Klasifikasi Usia</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pendidikan">Pendidikan</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pekerjaan">Pekerjaan</button></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="usia">
                    <div class="row align-items-center">
                        <div class="col-md-6"><div class="chart-container"><canvas id="chartUsia"></canvas></div></div>
                        <div class="col-md-6"><h5>Distribusi Usia</h5><p class="text-muted">Berdasarkan data kependudukan terbaru di database.</p></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pendidikan">
                    <div class="row align-items-center">
                        <div class="col-md-6"><div class="chart-container"><canvas id="chartPendidikan"></canvas></div></div>
                        <div class="col-md-6"><h5>Tingkat Pendidikan</h5><p class="text-muted">Data pendidikan formal terakhir penduduk.</p></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pekerjaan">
                    <div class="row align-items-center">
                        <div class="col-md-6"><div class="chart-container"><canvas id="chartPekerjaan"></canvas></div></div>
                        <div class="col-md-6"><h5>Mata Pencaharian</h5><p class="text-muted">Sektor ekonomi utama warga Kedungpane.</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="section-title">B. Lembaga Kemasyarakatan (LKK)</h3>
    <div class="row g-4 mb-5">
        <?php 
        $query_lkk = mysqli_query($conn, "SELECT * FROM sdm_kelurahan WHERE tipe='lkk' ORDER BY id ASC");
        while($item = mysqli_fetch_assoc($query_lkk)): 
        ?>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 stat-card h-100">
                <i class="<?= htmlspecialchars($item['ikon']) ?> fs-2 mb-3" style="color: #7b0000;"></i>
                <h6 class="fw-bold text-uppercase" style="color: #7b0000;"><?= htmlspecialchars($item['judul']) ?></h6>
                <span class="badge bg-danger mb-2 d-inline-block w-auto"><?= htmlspecialchars($item['nilai']) ?></span>
                <p class="small text-muted mb-0"><?= htmlspecialchars($item['deskripsi']) ?></p>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const colors = ['#7b0000', '#a01515', '#c73030', '#e35d5d', '#ff8a8a', '#ffbaba'];

        // Chart Usia
        new Chart(document.getElementById('chartUsia'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($data_usia['labels']) ?>,
                datasets: [{ data: <?= json_encode($data_usia['data']) ?>, backgroundColor: colors }]
            },
            options: { maintainAspectRatio: false }
        });

        // Chart Pendidikan
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($data_pendidikan['labels']) ?>,
                datasets: [{ label: 'Jiwa', data: <?= json_encode($data_pendidikan['data']) ?>, backgroundColor: '#7b0000' }]
            },
            options: { maintainAspectRatio: false }
        });

        // Chart Pekerjaan
        new Chart(document.getElementById('chartPekerjaan'), {
            type: 'pie',
            data: {
                labels: <?= json_encode($data_pekerjaan['labels']) ?>,
                datasets: [{ data: <?= json_encode($data_pekerjaan['data']) ?>, backgroundColor: colors }]
            },
            options: { maintainAspectRatio: false }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>