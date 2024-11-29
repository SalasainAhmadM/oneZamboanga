class SpecialNavbar extends HTMLElement {
    connectedCallback() {
        // Extract the 'id' parameter from the current URL
        const urlParams = new URLSearchParams(window.location.search);
        const idParam = urlParams.get('id') ? `?id=${urlParams.get('id')}` : '';

        this.innerHTML = `
            <div class="ecNavbar">
                <ul>
                    <div class="navList">
                        <li><a href="viewEC.php${idParam}" class="restricted-link">Overview</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="evacueesPage.php${idParam}" class="restricted-link">Evacuees</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="resources.php${idParam}" class="restricted-link">Resource Management</a></li>
                        <div class="indicator long"></div>
                    </div>
                    <div class="navList">
                        <li><a href="personnel.php${idParam}" class="restricted-link">Team</a></li>
                        <div class="indicator extrasmall"></div>
                    </div>
                    <div class="navList">
                        <li><a href="nearEC.php${idParam}" class="restricted-link">Transfer</a></li>
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

        // Attach event listeners to restricted links
        const restrictedLinks = this.querySelectorAll('.restricted-link');
        restrictedLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                const url = new URL(link.href); // Parse the link's URL
                const id = url.searchParams.get('id'); // Extract the 'id' parameter

                if (id === 'All') {
                    e.preventDefault(); // Prevent navigation
                    Swal.fire({
                        icon: 'info',
                        text: 'Please select a specific evacuation center first.',
                        confirmButtonText: 'OK'
                    });
                    console.log('Navigation prevented for id=All'); // Debug log
                }
            });
        });
    }
}

customElements.define('special-navbar', SpecialNavbar);
