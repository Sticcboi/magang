<?php
$page_title = 'UMKM Kedungpane';
include 'includes/header.php';

// Ambil data UMKM dari database (Hanya yang aktif)
$umkm_data = [];
$query_umkm = mysqli_query($conn, "SELECT * FROM umkm WHERE is_active = 1 ORDER BY id DESC");
if ($query_umkm) {
    while ($row = mysqli_fetch_assoc($query_umkm)) {
        $umkm_data[] = $row;
    }
}
?>
<style>
  .table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease-in-out;
  }
  .search-wrapper {
    position: relative;
  }
  .search-wrapper svg {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
  }
  .search-wrapper input {
    padding-left: 40px;
    border-radius: 20px;
  }
  #map {
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
</style>

<div class="container my-5">
  <div class="text-center mb-5">
    <h1 class="fw-bold text-dark mb-2">Direktori UMKM</h1>
    <span class="badge bg-danger px-3 py-2 fs-6 rounded-pill shadow-sm">Kelurahan Kedungpane</span>
  </div>

  <div class="card shadow-sm border-0 mb-4 p-3 bg-light text-center">
    <p class="text-secondary mb-0">
      Temukan berbagai informasi mengenai produk dan jasa dari Usaha Mikro Kecil dan Menengah (UMKM) kebanggaan warga di wilayah Kedungpane.
    </p>
  </div>

  <div class="row mb-4 justify-content-center">
    <div class="col-md-6">
      <div class="search-wrapper shadow-sm rounded-pill">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16"><path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/></svg>
        <input id="filterInput" class="form-control border-0 shadow-none py-2" placeholder="Cari nama usaha, kategori (contoh: makanan, bengkel)..." />
      </div>
    </div>
  </div>

  <div class="mb-5">
    <div class="d-flex justify-content-between align-items-end mb-3 border-bottom pb-2">
        <h4 class="text-dark fw-bold mb-0">Daftar Usaha</h4>
        <small class="text-muted">Klik nama usaha untuk detail</small>
    </div>
    <div class="table-responsive shadow-sm rounded border border-light">
      <table class="table table-hover align-middle mb-0" id="umkmTable">
        <thead class="table-light text-secondary">
          <tr>
            <th class="py-3 px-3">Nama Usaha</th>
            <th class="py-3">Kategori</th>
            <th class="py-3">Pengelola</th>
            <th class="py-3">Kontak</th>
            <th class="py-3 text-center">Status</th>
          </tr>
        </thead>
        <tbody class="border-top-0">
        </tbody>
      </table>
    </div>
  </div>

  <div class="mb-5" id="petaLokasiSection">
    <h4 class="mb-3 border-bottom pb-2 text-dark fw-bold">Peta Lokasi UMKM</h4>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="ratio ratio-21x9 rounded overflow-hidden">
                <iframe id="mapPreview" src="https://maps.google.com/maps?q=Kelurahan+Kedungpane+Semarang&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
  </div>

</div>

<div class="modal fade" id="profilModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-bottom-0">
        <h5 class="modal-title fw-bold text-dark">Profil Usaha</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <h4 class="fw-bold text-danger mb-1" id="pmNama"></h4>
          <span class="badge bg-secondary rounded-pill" id="pmKategori"></span>
        </div>
        
        <div class="d-flex mb-3 align-items-center">
          <div class="bg-light rounded p-2 me-3 text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
          </div>
          <div>
            <small class="text-muted d-block lh-1">Pengelola</small>
            <span class="fw-medium text-dark" id="pmPengelola"></span>
          </div>
        </div>

        <div class="d-flex mb-4 align-items-center">
          <div class="bg-light rounded p-2 me-3 text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-telephone" viewBox="0 0 16 16"><path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.528.528 0 0 0 .146.508l3.294 3.294a.528.528 0 0 0 .508.146l2.19-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/></svg>
          </div>
          <div>
            <small class="text-muted d-block lh-1">Kontak</small>
            <span class="fw-medium text-dark" id="pmKontak"></span>
          </div>
        </div>

        <div class="p-3 bg-light rounded text-secondary" id="pmDeskripsi" style="font-size: 0.95rem;"></div>
      </div>
      <div class="modal-footer border-top-0 d-flex justify-content-between">
        <button type="button" class="btn btn-danger px-4 rounded-pill" id="btnLihatPeta">
          Fokus di Peta
        </button>
        <button type="button" class="btn btn-light px-4 rounded-pill text-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

</div>

<script>
  // Mengambil data langsung dari PHP/Database
  const dbUmkm = <?= json_encode($umkm_data) ?>;

  let currentActiveUmkm = null;

  document.addEventListener("DOMContentLoaded", () => {
    renderAll();
  });

  function renderAll() {
    const tbody = document.querySelector("#umkmTable tbody");
    tbody.innerHTML = "";
    
    if(dbUmkm.length === 0) {
        tbody.innerHTML = "<tr><td colspan='5' class='text-center py-3'>Belum ada data UMKM.</td></tr>";
        return;
    }

    dbUmkm.forEach((item) => {
      const tr = document.createElement("tr");
      const verifiedBadge = parseInt(item.is_verified) === 1 
        ? '<span class="badge bg-success bg-opacity-10 text-success border border-success">Terverifikasi</span>' 
        : '<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary">Baru</span>';

      tr.innerHTML = `
        <td class="px-3 fw-medium">
            <a href="#" class="view-link text-decoration-none text-danger" data-id="${item.id}">${escapeHtml(item.nama)}</a>
        </td>
        <td class="text-secondary">${escapeHtml(item.kategori || "-")}</td>
        <td class="text-secondary">${escapeHtml(item.pengelola || "-")}</td>
        <td class="text-secondary">${escapeHtml(item.kontak || "-")}</td>
        <td class="text-center">${verifiedBadge}</td>`;
      tbody.appendChild(tr);
    });

    attachTableEvents();
  }

  function attachTableEvents() {
    document.querySelectorAll(".view-link").forEach((a) => {
      a.onclick = function (e) {
        e.preventDefault();
        const id = this.dataset.id;
        const u = dbUmkm.find((x) => x.id == id);
        if (u) {
          currentActiveUmkm = u;
          document.getElementById("pmNama").textContent = u.nama;
          document.getElementById("pmKategori").textContent = u.kategori || "Umum";
          document.getElementById("pmPengelola").textContent = u.pengelola || "-";
          document.getElementById("pmKontak").textContent = u.kontak || "-";
          document.getElementById("pmDeskripsi").textContent = u.deskripsi || "Informasi detail belum ditambahkan.";
          
          if (u.gmaps_url && u.gmaps_url.trim() !== '') {
              document.getElementById("btnLihatPeta").style.display = "block";
          } else {
              document.getElementById("btnLihatPeta").style.display = "none";
          }

          const modal = new bootstrap.Modal(document.getElementById("profilModal"));
          modal.show();
        }
      };
    });
  }

  document.getElementById("btnLihatPeta").onclick = function() {
    if(currentActiveUmkm) {
        let rawUrl = currentActiveUmkm.gmaps_url ? currentActiveUmkm.gmaps_url.trim() : "";
        let finalUrl = "";

        if (rawUrl !== '') {
            // Jika admin memasukkan kode <iframe> utuh, ambil isi atribut src-nya
            if (rawUrl.toLowerCase().includes('<iframe') && rawUrl.includes('src=')) {
                const match = rawUrl.match(/src=["'](.*?)["']/);
                if (match && match[1]) {
                    finalUrl = match[1];
                }
            } else {
                // Jika sudah berupa URL embed
                if (rawUrl.includes('embed') || rawUrl.includes('output=embed')) {
                    finalUrl = rawUrl;
                } else if (rawUrl.includes('goo.gl')) {
                    // PENTING: Karena Anda secara eksplisit menginginkan URL dari admin digunakan,
                    // kami mencabut semua logika pencarian otomatis.
                    // Namun perhatikan bahwa Google Maps memblokir link pendek di dalam iframe.
                    // Jika URL ini menyebabkan error, Anda HARUS menggantinya di admin panel.
                    finalUrl = rawUrl;
                } else {
                    // Jika berupa link biasa, nama tempat, atau titik koordinat, jadikan parameter query agar bisa di-embed
                    finalUrl = "https://maps.google.com/maps?q=" + encodeURIComponent(rawUrl) + "&output=embed";
                }
            }
        } else {
            // Jika kosong, gunakan auto-generate berdasarkan nama dan alamat
            let query = (currentActiveUmkm.nama + ' ' + (currentActiveUmkm.alamat || 'Kedungpane Semarang')).trim();
            finalUrl = "https://maps.google.com/maps?q=" + encodeURIComponent(query) + "&output=embed";
        }
        
        document.getElementById('mapPreview').src = finalUrl;
        
        // Tutup modal dan scroll ke peta
        const modalEl = document.getElementById('profilModal');
        bootstrap.Modal.getInstance(modalEl).hide();
        document.getElementById("petaLokasiSection").scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  };

  document.getElementById("filterInput").addEventListener("input", function (e) {
      const q = e.target.value.toLowerCase();
      document.querySelectorAll("#umkmTable tbody tr").forEach((row) => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none";
      });
  });

  function escapeHtml(s) {
    if (!s) return "";
    return String(s).replace(/[&<>"']/g, function (m) {
      return { "&": "&", "<": "<", ">": ">", '"': "&quot;", "'": "&#39;" }[m];
    });
  }
</script>
<?php include 'includes/footer.php'; ?>