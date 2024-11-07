class SpecialPersonnel extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <div class="ecNavbar">
                <ul>
                    <div class="navList">
                        <li><a href="personnelPage.html">Community Workers</a></li>
                        <div class="indicator workers"></div>
                    </div>
                    <div class="navList">
                        <li><a href="personnelAssign.html">Assign</a></li>
                        <div class="indicator assign"></div>
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

customElements.define('special-personnel', SpecialPersonnel)