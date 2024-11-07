class SpecialSidebar extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `

            
            <div class="sidebar">
                <div class="item">
                    <a href="dashboard_barangay.html">
                        <i class="fa-solid fa-house"></i>
                    </a>
                    <a href="dashboard_barangay.html" class="dot">Dashboard</a>
                </div>


                <div class="item">
                    <a href="personnelPage.html">
                        <i class="fa-solid fa-users"></i>
                    </a>
                    <a href="personnelPage.html" class="dot">Accounts</a>
                </div>

                <div class="item">
                    <a href="evacuation.html">
                        <i class="fa-solid fa-person-shelter"></i>
                    </a>
                    <a href="evacuation.html" class="dot">Evacuation Center</a>
                </div>

                <div class="item">
                    <a href="requestTransfer.html">
                        <i class="fa-regular fa-paper-plane"></i>
                    </a>
                    <a href="requestTransfer.html" class="dot">Request</a>
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
                    <a href="myProfile.html">
                        <!-- <i class="fa-solid fa-gear"></i> -->
                        <i class="fa-regular fa-id-card"></i>
                    </a>
                    <a href="myProfile.html" class="dot">My Profile</a>
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
