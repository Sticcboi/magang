<?php
require_once __DIR__ . '/auth.php';

// --- LOGIKA CRUD (TAMBAH, EDIT, HAPUS) ---

if (isset($_POST['save_user'])) {
    $id = mysqli_real_escape_string($conn, $_POST['user_id'] ?? '');
    $nama = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $role = mysqli_real_escape_string($conn, $_POST['role'] ?? '');
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan'] ?? '');
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir'] ?? '');
    
    // Kelola Password
    $password_query = "";
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $password_query = ", password = '$password'";
    }

    // Kelola Upload Foto
    $foto_name = $_POST['existing_foto'] ?? '';
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = "admin_" . time() . "." . $ext;
        if (!is_dir('../assets/img/uploads/')) {
            mkdir('../assets/img/uploads/', 0777, true);
        }
        move_uploaded_file($_FILES['foto']['tmp_name'], '../assets/img/uploads/' . $foto_name);
    }

    if (empty($id)) {
        // INSERT USER BARU
        $pass_default = password_hash(!empty($_POST['password']) ? $_POST['password'] : '12345', PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nama, username, password, role, jabatan, alamat, tanggal_lahir, foto_profil) 
                VALUES ('$nama', '$username', '$pass_default', '$role', '$jabatan', '$alamat', '$tanggal_lahir', '$foto_name')";
    } else {
        // UPDATE USER (Sudah dibersihkan, murni pakai id)
        $sql = "UPDATE users SET 
                nama = '$nama', username = '$username', role = '$role', 
                jabatan = '$jabatan', alamat = '$alamat', tanggal_lahir = '$tanggal_lahir', 
                foto_profil = '$foto_name' $password_query 
                WHERE id = '$id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>window.location='admin_users.php?status=success';</script>";
        exit;
    } else {
        // Tampilkan error jika query gagal
        die("Error SQL: " . mysqli_error($conn));
    }
}

// Hapus User (Sudah dibersihkan, murni pakai id)
if (isset($_GET['delete'])) {
    $id_del = mysqli_real_escape_string($conn, $_GET['delete']);
    $logged_id = $_SESSION['id'] ?? 0;
    if ($id_del != $logged_id) {
        mysqli_query($conn, "DELETE FROM users WHERE id = '$id_del'");
        echo "<script>window.location='admin_users.php?status=deleted';</script>";
        exit;
    }
}

$query_users = mysqli_query($conn, "SELECT * FROM users ORDER BY role ASC, nama ASC");
$page_title = 'Manajemen Admin';
$current_page = 'admin_users';
include 'admin_header.php';
?>

<style>
    /* Styling Table */
    .user-avatar-list { width: 45px; height: 45px; object-fit: cover; border-radius: 10px; }
    .btn-maroon { background-color: #8B0000; color: white; border-radius: 10px; border: none; }
    .btn-maroon:hover { background-color: #660000; color: white; }
    .table-card { border: none; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff; }
    
    /* Z-Index di atas segalanya */
    .modal { z-index: 1055 !important; }
    .modal-backdrop { z-index: 1050 !important; }

    /* Menggeser modal di layar besar ke area putih agar tidak tabrakan dengan sidebar merah */
    @media (min-width: 992px) {
        .modal {
            padding-left: 260px !important; 
        }
    }
</style>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Manajemen Pengguna</h3>
            <p class="text-muted mb-0">Atur admin, role, dan informasi perangkat kelurahan.</p>
        </div>
        <button type="button" class="btn btn-maroon px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUser" onclick="resetForm()">
            <i class="fa-solid fa-user-plus me-2"></i> Tambah Admin
        </button>
    </div>

    <?php if(isset($_GET['status'])): ?>
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
            Proses berhasil dilakukan.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Profil</th>
                        <th>Jabatan & Role</th>
                        <th>Alamat & Tanggal Lahir</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($user = mysqli_fetch_assoc($query_users)): 
                        $u_id = $user['id'] ?? 0;
                        $my_id = $_SESSION['id'] ?? 0;

                        // Avatar otomatis
                        $nama_url = urlencode($user['nama'] ?? 'User');
                        $avatar_otomatis = "https://ui-avatars.com/api/?name={$nama_url}&background=8B0000&color=fff&rounded=true&bold=true";
                        $foto_profil = !empty($user['foto_profil']) ? '../assets/img/uploads/' . $user['foto_profil'] : $avatar_otomatis;
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="<?= $foto_profil; ?>" 
                                     class="user-avatar-list me-3 shadow-sm" 
                                     style="border: none;"
                                     onerror="this.onerror=null; this.src='<?= $avatar_otomatis; ?>';">
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($user['nama'] ?? '-'); ?></div>
                                    <small class="text-muted">@<?= htmlspecialchars($user['username'] ?? '-'); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-danger bg-opacity-10 text-danger mb-1"><?= htmlspecialchars($user['role'] ?? 'Admin'); ?></span>
                            <div class="small fw-semibold"><?= htmlspecialchars($user['jabatan'] ?? '-'); ?></div>
                        </td>
                        <td>
                            <div class="small mb-1"><i class="fa-solid fa-map-marker-alt me-1 text-muted"></i> <?= htmlspecialchars($user['alamat'] ?? '-'); ?></div>
                            <div class="small text-muted"><i class="fa-solid fa-calendar me-1"></i> <?= (!empty($user['tanggal_lahir']) && $user['tanggal_lahir'] != '0000-00-00') ? date('d M Y', strtotime($user['tanggal_lahir'])) : '-'; ?></div>
                        </td>
                        <td class="text-end pe-4">
                            
                            <button type="button" class="btn btn-sm btn-light rounded-circle shadow-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalUser"
                                    data-id="<?= htmlspecialchars($u_id); ?>"
                                    data-nama="<?= htmlspecialchars($user['nama'] ?? ''); ?>"
                                    data-username="<?= htmlspecialchars($user['username'] ?? ''); ?>"
                                    data-role="<?= htmlspecialchars($user['role'] ?? 'Admin'); ?>"
                                    data-jabatan="<?= htmlspecialchars($user['jabatan'] ?? ''); ?>"
                                    data-alamat="<?= htmlspecialchars($user['alamat'] ?? ''); ?>"
                                    data-tgl="<?= htmlspecialchars($user['tanggal_lahir'] ?? ''); ?>"
                                    data-foto="<?= htmlspecialchars($user['foto_profil'] ?? ''); ?>"
                                    onclick="editUser(this)">
                                <i class="fa-solid fa-pencil text-primary"></i>
                            </button>
                            
                            <?php if($u_id != $my_id && $u_id != 0): ?>
                            <a href="?delete=<?= $u_id; ?>" class="btn btn-sm btn-light rounded-circle shadow-sm btn-hapus" title="Hapus">
                                <i class="fa-solid fa-trash text-danger"></i>
                            </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        
        <form action="" method="POST" enctype="multipart/form-data" class="w-100">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                
                <div class="modal-header border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold mb-0" id="modalTitle">Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body px-4 py-3">
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="existing_foto" id="existing_foto">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" id="form_nama" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" id="form_username" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Role Akses</label>
                            <select name="role" id="form_role" class="form-select">
                                <option value="Admin">Admin</option>
                                <option value="Super Admin">Super Admin</option>
                                <option value="Editor">Editor</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Jabatan</label>
                            <input type="text" name="jabatan" id="form_jabatan" class="form-control" placeholder="Contoh: Sekretaris">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" id="form_tgl" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Password <small class="text-muted fw-normal">(Kosongkan jika tak diubah)</small></label>
                            <input type="password" name="password" id="form_password" class="form-control" placeholder="Ketik sandi baru...">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Alamat</label>
                            <textarea name="alamat" id="form_alamat" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Foto Profil</label>
                            <input type="file" name="foto" id="form_foto" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pb-4 px-4 pt-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="save_user" class="btn btn-maroon px-4 shadow">Simpan Data</button>
                </div>

            </div>
        </form>
        
    </div>
</div>

<script>
// Menghindari modal tertimpa div flex bawaan template
document.addEventListener("DOMContentLoaded", function() {
    const modalEl = document.getElementById('modalUser');
    if (modalEl && modalEl.parentNode !== document.body) {
        document.body.appendChild(modalEl);
    }
});

// Membersihkan form saat menambah data baru
function resetForm() {
    document.getElementById('modalTitle').innerText = "Tambah Admin Baru";
    document.getElementById('user_id').value = "";
    document.getElementById('existing_foto').value = "";
    document.getElementById('form_nama').value = "";
    document.getElementById('form_username').value = "";
    document.getElementById('form_jabatan').value = "";
    document.getElementById('form_alamat').value = "";
    document.getElementById('form_tgl').value = "";
    document.getElementById('form_role').value = "Admin";
    document.getElementById('form_password').value = ""; 
    document.getElementById('form_foto').value = ""; 
}

// Menempelkan data saat tombol edit ditekan
function editUser(btn) {
    document.getElementById('modalTitle').innerText = "Edit Data Pengguna";
    
    document.getElementById('user_id').value = btn.getAttribute('data-id');
    document.getElementById('form_nama').value = btn.getAttribute('data-nama');
    document.getElementById('form_username').value = btn.getAttribute('data-username');
    document.getElementById('form_role').value = btn.getAttribute('data-role');
    document.getElementById('form_jabatan').value = btn.getAttribute('data-jabatan');
    document.getElementById('form_alamat').value = btn.getAttribute('data-alamat');
    
    let tgl = btn.getAttribute('data-tgl');
    document.getElementById('form_tgl').value = (tgl !== '0000-00-00' && tgl !== '') ? tgl : '';
    
    document.getElementById('existing_foto').value = btn.getAttribute('data-foto');
    document.getElementById('form_password').value = ""; 
    document.getElementById('form_foto').value = ""; 
}
</script>

<?php include 'admin_footer.php'; ?>