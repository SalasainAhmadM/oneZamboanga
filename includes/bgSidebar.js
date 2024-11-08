class SpecialSidebar extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `

            
            <div class="sidebar">
                <div class="item">
                    <a href="dashboard_barangay.php">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <a href="dashboard_barangay.php" class="dot">Dashboard</a>
                </div>


                <div class="item">
                    <a href="personnelPage.php">
                        <i class="fa-solid fa-users"></i>
                    </a>
                    <a href="personnelPage.php" class="dot">Accounts</a>
                </div>

                <div class="item">
                    <a href="evacuation.php">
                        <i class="fa-solid fa-person-shelter"></i>
                    </a>
                    <a href="evacuation.php" class="dot">Evacuation Center</a>
                </div>

                <div class="item">
                    <a href="requestTransfer.php">
                        <i class="fa-regular fa-paper-plane"></i>
                    </a>
                    <a href="requestTransfer.php" class="dot">Request</a>
                </div>


                <!-- <div class="item">
                    <a href="#">
                        <i class="fa-solid fa-bell"></i>
                    </a>
                    <a href="#" class="dot">Notifications</a>
                </div> -->

                <div class="item">
                    <a href="#">
                        <i class="fa-solid fa-file-signature"></i>
                    </a>
                    <a href="#" class="dot">Reports</a>
                </div>
                
                <div class="item">
                    <a href="myProfile.php">
                        <!-- <i class="fa-solid fa-gear"></i> -->
                        <i class="fa-regular fa-id-card"></i>
                    </a>
                    <a href="myProfile.php" class="dot">My Profile</a>
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
        })
    }
}


customElements.define('special-sidebar', SpecialSidebar)
