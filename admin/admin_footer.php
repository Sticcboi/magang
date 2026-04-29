<?php
/**
 * Admin Footer Include
 * Closes the main content container and body
 */
?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function openSidebar() {
            document.body.classList.add('sidebar-open');
            sidebar?.classList.add('show');
        }

        function closeSidebar() {
            document.body.classList.remove('sidebar-open');
            sidebar?.classList.remove('show');
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                if (document.body.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                closeSidebar();
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Tangani Konfirmasi Hapus Global (SweetAlert)
        document.addEventListener('DOMContentLoaded', function() {
            // Untuk link <a> dengan class btn-hapus
            document.querySelectorAll('.btn-hapus').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    const deleteUrl = this.getAttribute('href');
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) { window.location.href = deleteUrl; }
                    });
                });
            });

            // Untuk form <form> dengan class form-hapus
            document.querySelectorAll('.form-hapus').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) { form.submit(); }
                    });
                });
            });
        });
    </script>
</body>
</html>
