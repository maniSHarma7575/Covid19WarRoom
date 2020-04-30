<!-- HEADER MOBILE -->

<header class="header header-mobile-2 d-block d-lg-none bg-light text-primary">
    <div class="header-mobile__bar ">
    <div class="container-fluid">
        <div class="header-mobile-inner ">
            <a class="logo" href="<?php echo URLROOT;?>Patient">
                <img src="<?php echo URLROOT;?>images/icon/covidlogo.jpg" alt="Logo" />
            </a>
        </div>
    </div>
</div>
<nav class="navbar" >
    <div class="container-fluid">
        <ul class="navbar-mobile__list list-styled text-white">

            <li class="has-sub">
                <a  class = "nav-link text-secondary"href="<?php echo URLROOT;?>Patient">
                  <i class="fas fa-address-card"></i> All Patients</a>
            </li>
            <li class="has-sub">
                <a  class = "nav-link text-secondary"href="<?php echo URLROOT;?>Notices">
                  <i class="fa fa-plus-circle"></i> Notices</a>
            </li>
            <li class="has-sub">
                <a  class = "nav-link text-secondary"href="<?php echo URLROOT;?>Gatherings">
                  <i class="fas fa-flag-checkered"></i> Reported Gatherings</a>
            </li>
            <li class="has-sub">
              <a  class = "nav-link text-secondary"href="<?php echo URLROOT;?>Logout">
               <i class="fas fa-sign-out-alt"></i>  Logout</a>
          </li>
        </ul>
    </div>
</nav>
    </header>