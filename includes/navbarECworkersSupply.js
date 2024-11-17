class SpecialNavbar extends HTMLElement {
    connectedCallback() {
        // Extract the 'center_id' and 'worker_id' parameters from the current URL
        const urlParams = new URLSearchParams(window.location.search);
        const centerId = urlParams.get('center_id');
        const workerId = urlParams.get('worker_id');
        
        // Construct query string with the extracted parameters
        const queryString = `?id=${centerId}&worker_id=${workerId}`;

        // Generate the navbar HTML with dynamic links
        this.innerHTML = `
            <div class="ecNavbar">
                <ul>
                    <div class="navList">
                        <li><a href="viewAssignedEC.php${queryString}">Overview</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="evacueesPage.php${queryString}">Evacuees</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="resources.php${queryString}">Resource Management</a></li>
                        <div class="indicator long"></div>
                    </div>
                    <div class="navList">
                        <li><a href="personnel.php${queryString}">Team</a></li>
                        <div class="indicator extrasmall"></div>
                    </div>
                    <div class="navList">
                        <li><a href="nearEC.php${queryString}">Transfer</a></li>
                        <div class="indicator small"></div> 
                    </div>
                </ul>
            </div>
        `;

        // Highlight the active link based on the current path
        const currentPath = window.location.pathname.split('/').pop();
        const navLists = this.querySelectorAll('.navList');

        navLists.forEach(nav => {
            const link = nav.querySelector('a');
            const indicator = nav.querySelector('.indicator');

            // Check if the link matches the current path
            if (link.getAttribute('href').includes(currentPath)) {
                link.classList.add('active');
                indicator.style.display = 'block';
            } else {
                indicator.style.display = 'none';
            }
        });
    }
}

customElements.define('special-navbar', SpecialNavbar);
