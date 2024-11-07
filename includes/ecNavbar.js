class SpecialNavbar extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <div class="ecNavbar">
                <ul>
                    <div class="navList">
                        <li><a href="viewEC.html">Overview</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="evacueesPage.html">Evacuees</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="resources.html">Resource Management</a></li>
                        <div class="indicator long"></div>
                    </div>

                    <div class="navList">
                        <li><a href="personnel.html">Team</a></li>
                        <div class="indicator extrasmall"></div>
                    </div>

                    <div class="navList">
                        <li><a href="nearEC.html">Transfer</a></li>
                        <div class="indicator small"></div> 
                    </div>
                </ul>
            </div>
        `;

        // Get current path
        const currentPath = window.location.pathname.split('/').pop();

        // select nav links and indicators
        const navLinks = this.querySelectorAll('.navList');

        navLinks.forEach(nav => {
            const link = nav.querySelector('a');
            const indicator = nav.querySelector('.indicator');

            //check if the link matches the current path
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
                indicator.style.display = 'block';

            } else {
                indicator.style.display = 'none';
            }
        });

        
    }
}

customElements.define('special-navbar', SpecialNavbar)