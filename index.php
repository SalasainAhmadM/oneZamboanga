<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!--Font Awesome-->
  <link rel="stylesheet" href="assets//fontawesome/all.css">
  <link rel="stylesheet" href="assets/fontawesome/fontawesome.min.css">
  <!--styles-->

  <link rel="icon" href="assets/img/zambo.png">

  <link rel="stylesheet" href="assets/styles/style.css">
  <link rel="stylesheet" href="assets/styles/home.css">

  <title>One Zamboanga: Evacuation Center Management System</title>


</head>

<body>
  <section class="container home" id="home">

    <!--HEADER-->
    <header class="header">
      <nav>
        <ul class="header__menu">
          <li>
            <a class="header__link" href="#home">Home</a>
          </li>

          <li>
            <a class="header__link" href="#about">About Us</a>
          </li>

          <li>
            <a class="header__link" href="#contact">Contact Us</a>
          </li>

          <li>
            <a class="header__login btn" href="login.php">Login</a>
          </li>
        </ul>
        <button class="header__bars">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd"
              d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z"
              clip-rule="evenodd" />
          </svg>
        </button>
      </nav>
    </header>

    <!--HAMBURGER MENU-->
    <div class="mobile-nav">
      <nav>
        <ul class="mobile-nav__menu">
          <li>
            <a href="#home" class="mobile-nav__link">Home</a>
          </li>

          <li>
            <a href="#about" class="mobile-nav__link">About Us</a>
          </li>

          <li>
            <a href="#contact" class="mobile-nav__link">Contact Us</a>
          </li>

          <li>
            <a class="mobile-nav__btn btn" href="login.php">Login</a>
          </li>
        </ul>
      </nav>
    </div>
    <!--END OF HAMBURGER MENU-->

    <!-- ===== HERO ====-->
    <main>
      <section class="hero">
        <!-- <img class="hero__img" src="assets/img/logo5.png" alt="profile picture">  -->
        <h1 class="hero__title">ONE ZAMBOANGA</h1>
        <p class="hero__description">Your Guide to Safety and Shelter in Zamboanga City</p>
        <!-- <a class="hero__btn btn" href="login.php">Get Started</a> -->
      </section>


    </main>
  </section>


  <!-- ===ABOUT SECTION -->
  <section class="container" id="about">

    <div class="about__container">
      <div class="about__left">
        <h2 class="about__title">About us</h2>
        <p class="about__description">
          ONE ZAMBOANGA is an evacuation center management system designed to streamline shelter operations during
          emergencies. Our platform enables efficient organization, tracking, and resource allocation for safer and more
          accessible evacuation centers. With a commitment to supporting communities in times of crisis, ONE ZAMBOANGA
          strives to enhance disaster response and recovery efforts across the city.
        </p>
      </div>


      <div class="about__right">
        <img src="assets/img/aboutImg.png" alt="">
      </div>
    </div>
  </section>


  <section class="container contact" id="contact">
    <div class="contact__wrapper">

      <div class="contact__left">
        <h2 class="contact__title">ONE ZAMBOANGA</h2>
        <p>The evacuation center management system in Zamboanga City.</p>
      </div>

      <div class="contactDetails">

        <div class="contact__mid">
          <h3 class="contact__subtitle">Follow Us</h3>
          <div class="contact__icons">
            <div class="icons">
              <i class="fa-brands fa-facebook"></i>
              <span>Facebook</span>
            </div>

            <div class="icons">
              <i class="fa-solid fa-share"></i>
              <span>Copy link</span>
            </div>
          </div>

        </div>


        <div class="contact__right">
          <h3 class="contact__subtitle">Contact Us</h3>
          <div class="contactIcons-wrapper">
            <div class="contactInfo">
              <i class="fa-solid fa-location-dot"></i>
              <span>Zamboanga City</span>
            </div>

            <div class="contactInfo">
              <i class="fa-regular fa-envelope"></i>
              <span>oneZamboanga@gmail.com</span>
            </div>

            <div class="contactInfo">
              <i class="fa-solid fa-phone"></i>
              <span>+63 900-1111-653 </span>
            </div>
          </div>
        </div>
      </div>

    </div>


    <span class="footer">2024 One Zamboanga. All rights reserved</span>

  </section>

  <!-- <footer class="container"></footer> -->

  <button id="backToTop" onclick="scrollToTop()">↑ Back to Top</button>







  <script>
    const mobileNav = () => {
      const headerBtn = document.querySelector('.header__bars');
      const mobileNav = document.querySelector('.mobile-nav');
      const mobileLinks = document.querySelectorAll('.mobile-nav__link');

      // state
      let isMobileNavOpen = false;

      headerBtn.addEventListener('click', () => {
        isMobileNavOpen = !isMobileNavOpen;
        if (isMobileNavOpen) {
          mobileNav.style.display = 'flex';
          document.body.style.overflowY = 'hidden';
        } else {
          mobileNav.style.display = 'none';
          document.body.style.overflowY = 'auto';
        }
      });

      mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
          isMobileNavOpen = false;
          mobileNav.style.display = 'none';
          document.body.style.overflowY = 'auto';
        });
      });

    };

    mobileNav()
  </script>


  <script>
    // Show the button when scrolling down
    window.onscroll = function () {
      const backToTopButton = document.getElementById("backToTop");
      if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        backToTopButton.style.display = "block";
      } else {
        backToTopButton.style.display = "none";
      }
    };

    // Scroll to top function
    function scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    }
  </script>




</body>

</html>