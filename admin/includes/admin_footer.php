    </div><!-- End admin-content -->
</main><!-- End admin-main -->

<script>
    // Toggle sidebar collapse (desktop)
    function toggleSidebarCollapse() {
        const sidebar = document.getElementById('adminSidebar');
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('adminSidebarCollapsed', sidebar.classList.contains('collapsed'));
    }
    
    // Toggle sidebar for mobile
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('mobile-open');
        overlay.classList.toggle('active');
    }
    
    // Restore sidebar state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebar');
        if (localStorage.getItem('adminSidebarCollapsed') === 'true' && window.innerWidth > 992) {
            sidebar.classList.add('collapsed');
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (window.innerWidth > 992) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }
    });
    
    // Close sidebar when clicking a link on mobile
    document.querySelectorAll('.nav-item').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                const sidebar = document.getElementById('adminSidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });
    });
</script>
