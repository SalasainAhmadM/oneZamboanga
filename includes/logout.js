class SpecialLogout extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <div class="logout">
                <div class="link">
                    <a href="#" id="logout-link">
                        <p>Click to <b>Logout</b></p>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </div>
            <a class="logout logout-icon" href="#" id="logout-icon">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        `;

        // Select the elements
        const logoutLink = this.querySelector("#logout-link");
        const logoutIcon = this.querySelector("#logout-icon");

        // Add click event listener for both logout link and logout icon
        logoutLink.addEventListener("click", (e) => this.confirmLogout(e));
        logoutIcon.addEventListener("click", (e) => this.confirmLogout(e));
    }

    confirmLogout(e) {
        e.preventDefault(); // Prevent the default action (redirect)
        
        // Trigger SweetAlert confirmation
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of the session.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout.php to handle the session destruction
                window.location.href = './endpoints/logout.php';
            }
        });
    }
}

customElements.define('special-logout', SpecialLogout);
