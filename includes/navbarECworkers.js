// class SpecialNavbar extends HTMLElement {
//     connectedCallback() {
//         // Extract 'id' and 'worker_id' from the current URL
//         const urlParams = new URLSearchParams(window.location.search);
//         const idParam = urlParams.get('id') ? `id=${urlParams.get('id')}` : '';
//         const workerIdParam = urlParams.get('worker_id') ? `worker_id=${urlParams.get('worker_id')}` : '';

//         // Combine the parameters for use in the URLs
//         const queryParams = [idParam, workerIdParam].filter(Boolean).join('&');
//         const queryString = queryParams ? `?${queryParams}` : '';

//         this.innerHTML = `
//             <div class="ecNavbar">
//                 <ul>
//                     <div class="navList">
//                         <li><a href="viewAssignedEC.php${queryString}">Overview</a></li>
//                         <div class="indicator"></div>
//                     </div>
//                     <div class="navList">
//                         <li><a href="evacueesPage.php${queryString}">Evacuees</a></li>
//                         <div class="indicator"></div>
//                     </div>
//                     <div class="navList">
//                         <li><a href="resources.php${queryString}">Resource Management</a></li>
//                         <div class="indicator long"></div>
//                     </div>
//                     <div class="navList">
//                         <li><a href="personnel.php${queryString}">Team</a></li>
//                         <div class="indicator extrasmall"></div>
//                     </div>
//                     <div class="navList">
//                         <li><a href="nearEC.php${queryString}">Transfer</a></li>
//                         <div class="indicator small"></div>
//                     </div>
//                 </ul>
//             </div>
//         `;

//         // Highlight the active link based on the current path
//         const currentPath = window.location.pathname.split('/').pop(); 
//         const navLists = this.querySelectorAll('.navList');

//         navLists.forEach(nav => {
//             const link = nav.querySelector('a');
//             const indicator = nav.querySelector('.indicator');

//             // Check if the link matches the current path
//             if (link.getAttribute('href').includes(currentPath)) {
//                 link.classList.add('active');
//                 indicator.style.display = 'block';
//             } else {
//                 indicator.style.display = 'none';
//             }
//         });
//     }
// }

// customElements.define('special-navbar', SpecialNavbar);
class SpecialNavbar extends HTMLElement {
    connectedCallback() {
        // Extract 'id' and 'worker_id' from the current URL
        const urlParams = new URLSearchParams(window.location.search);
        const idParam = urlParams.get('id') ? `id=${urlParams.get('id')}` : '';
        const workerIdParam = urlParams.get('worker_id') ? `worker_id=${urlParams.get('worker_id')}` : '';

        // Combine the parameters for use in the URLs
        const queryParams = [idParam, workerIdParam].filter(Boolean).join('&');
        const queryString = queryParams ? `?${queryParams}` : '';

        this.innerHTML = `
            <div class="ecNavbar">
                <ul>
                    <div class="navList">
                        <li><a href="viewAssignedEC.php${queryString}" class="restricted-link">Overview</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="evacueesPage.php${queryString}" class="restricted-link">Evacuees</a></li>
                        <div class="indicator"></div>
                    </div>
                    <div class="navList">
                        <li><a href="resources.php${queryString}" class="restricted-link">Resource Management</a></li>
                        <div class="indicator long"></div>
                    </div>
                    <div class="navList">
                        <li><a href="personnel.php${queryString}" class="restricted-link">Team</a></li>
                        <div class="indicator extrasmall"></div>
                    </div>
                    <div class="navList">
                        <li><a href="nearEC.php${queryString}" class="restricted-link">Transfer</a></li>
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
                }
            });
        });
    }
}

customElements.define('special-navbar', SpecialNavbar);
