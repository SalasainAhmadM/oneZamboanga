class SpecialLogo extends HTMLElement {
    connectedCallback() {
        this.innerHTML = `
            <div class="logo">
                <button class="menu-btn open" id="menu-close">
                    <i class="fa-regular fa-circle-left"></i>
                </button>
                <!-- <img src="../../assets/img/logo5.png" alt=""> -->
                <!-- <a href="dahsboard_barangay.html">One Zamboanga</a> -->
                <a href="dahsboard_barangay.html" class="logoWord" style="color: #eab308;">ONE ZAMBOANGA</a>
            </div>
        `

    }
}

customElements.define('special-logo', SpecialLogo)