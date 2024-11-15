

class SpecialNavbar extends HTMLElement {
    connectedCallback() {
        // Extract the 'id' parameter from the current URL
        const urlParams = new URLSearchParams(window.location.search);
        const idParam = evacuationCenterId ? `?id=${evacuationCenterId}` : '';

        this.innerHTML = `
            <div class="ecNavbar">
                <ul>
                    <div class="navList">
                        <li><a href="viewEC.php${idParam}">Overview</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="evacueesPage.php${idParam}">Evacuees</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="resources.php${idParam}">Resource Management</a></li>
                        <div class="indicator long"></div>
                    </div>
                    <div class="navList">
                        <li><a href="personnel.php${idParam}">Team</a></li>
                        <div class="indicator extrasmall"></div>
                    </div>
                    <div class="navList">
                        <li><a href="nearEC.php${idParam}">Transfer</a></li>
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
