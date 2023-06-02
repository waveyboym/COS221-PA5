<!-- navbar -->
<?php
    session_start();

    /**
    *@brief This function checks the url of the current page and returns an empty string or "navbar-scroll" which is used to
    *different navbar appearances on the home page and other pages. The navbar looks works differently on the pages thanks to this function
    *Please don't delete or modify this function
    *@param $none
    *@return string
    */
    function currentPage(){
        $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
        $url = end($url_array);  
        return $url == "index.php" || $url == "" ? "" : "navbar-scroll";
    }

    /*@brief This function checks the url of the current page and checks if it's the admin or manager page and returns a boolean
    *Please don't delete or modify this function
    *@param $none
    *@return bool
    */
    function renderNavBarLinks(){
      $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
      $url = end($url_array);  
      return $url == "admin.php" || $url == "manager.php" ? true : false;
  }

    /*@brief This function checks the url of the current page and returns an empty string or the search bar which is used to
    *to search for wines or wineries
    *Please don't delete or modify this function
    *@param none
    *@return string
    */
    function renderSearchBar(){
        $url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
        $url = end($url_array);  
        return $url == "wines.php" || $url == "wineries.php" ? 
            '<input type="search" class="form-control w-25" placeholder="Search for wineries" aria-label="Search" id="searchbar" />'. //<!--check if current page is wineries or wines then render this-->
            '<i class="fa-solid fa-magnifying-glass ms-2" style="color: #414141; font-size: 1.5rem;" onclick="searchFor()"></i>' //<!--check if current page is wineries or wines then render this-->
            : "";
    }
?>
<nav class="navbar navbar-noscroll <?php echo currentPage()?> navbar-expand-lg fixed-top navbar-light">
    <div class="container">
      <a class="navbar-brand d-flex" href="index.php">
        <i class="fa-solid fa-wine-glass pe-3" style="color: #414141; font-size: 2rem;"></i>
        <h3 class="me-5" style="color: #414141;">Winery SA</h3>
    </a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php echo renderSearchBar();?>
        <ul class="navbar-nav ms-auto align-items-center">
          <?php 
            if(!renderNavBarLinks()){
              echo '<li class="nav-item">'.
                      '<a class="nav-link mx-2" href="wines.php"><i class="fa-solid fa-wine-bottle pe-2"></i>wines</a><!--check whether a user is manager and conditionally render-->'.
                    '</li>'.
                    '<li class="nav-item">'.
                      '<a class="nav-link mx-2" href="wineries.php"><i class="fa-solid fa-store pe-2"></i>wineries</a><!--check whether a user is manager and conditionally render-->'.
                    '</li>';
            }
          ?>
          <li class="nav-item ms-3 border rounded-2">
            <?php if(isset($_SESSION['username'])){
              echo '<a href="profile.php"><!--check whether a user is manager and conditionally render-->'.
                        '<div class="btn btn-black btn-rounded">'.
                            '<i class="fa-regular fa-user pe-2"></i>'. $_SESSION['username'] .
                        '</div><!--will only show for logged in users-->'.
                    '</a>';
            }
            else if(isset($_SESSION['adminkey'])) echo '<a class="btn btn-black btn-rounded" href="admin.php">Admin '. $_SESSION['adminkey']. '</a>';
            else echo '<a class="btn btn-black btn-rounded" href="login.php">Login/Signup</a>';

            ?>
          </li>
          <?php
            if(isset($_SESSION['username']) || isset($_SESSION['adminkey'])){
              echo '<li class="nav-item" onclick="logout()">
              <a class="nav-link mx-2" href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Logout"><i class="fa-solid fa-arrow-right-from-bracket pe-2"></i></a><!--will only show for logged in users-->
              </li>';
            }
          
          ?>
        </ul>
      </div>
    </div>
</nav>
<!-- navbar -->
<script>
  //logout script'.
  const logout = function(){
      const xhttpObject = new XMLHttpRequest();
      xhttpObject.onreadystatechange = function() {
          if(this.readyState == 4 && this.status == 200)window.location.href = "index.php";
      };
      xhttpObject.open("POST", '../../Backend/Api/Api.php');
      xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttpObject.send(JSON.stringify({"type": "LOGOUT"}));
  }
</script>

