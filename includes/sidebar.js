class SpecialSidebar extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <div class="sidebar">
                <div class="item">
                    <a href="dashboard.php">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <a href="dashboard.php" class="dot">Dashboard</a>
                </div>

                <div class="item">
                    <a href="barangayAcc.php">
                        <i class="fa-solid fa-users"></i>
                    </a>
                    <a href="barangayAcc.php" class="dot">Barangay Accounts</a>
                </div>

                <div class="item">
                    <a href="barangayStatus.php">
                        <i class="fa-solid fa-landmark"></i>
                    </a>
                    <a href="barangayStatus.php" class="dot">Barangay</a>
                </div>
                 <div class="item">
                    <a href="requestTransfer.php">
                        <i class="fa-regular fa-paper-plane"></i>
                    </a>
                    <a href="requestTransfer.php" class="dot">Request</a>
                </div>
                
                <div class="item">
                    <a href="reports.php">
                        <i class="fa-solid fa-file-signature"></i>
                    </a>
                    <a href="reports.php" class="dot">Reports</a>
                </div>

                <div class="item">
                <a href="myProfileAdmin.php">
                <!-- <i class="fa-solid fa-gear"></i> -->
                <i class="fa-regular fa-id-card"></i>
                </a>
                <a href="myProfileAdmin.php" class="dot">My Profile</a>
                </div>
                
                
                
            </div>
        `;

        // Get the current path
        const currentPath = window.location.pathname.split('/').pop();

        // Get all the sidebar items
        const items = this.querySelectorAll('.item a');

        // Loop through each item to find a match
        items.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.parentElement.id = 'active';
            }
        });
    }
}

customElements.define('special-sidebar', SpecialSidebar)