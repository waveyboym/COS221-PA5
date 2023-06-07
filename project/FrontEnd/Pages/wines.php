<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/global.css">
    <link rel="stylesheet" href="../Styles/wines.css">
    <script src="https://kit.fontawesome.com/d271141ba3.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
      integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
      crossorigin="anonymous"></script>
    <link rel="icon" type="image/svg+xml" href="../Assets/wine-glass-solid.svg" />
    <title>Winery SA | Wines</title>
</head>
<body>
    <?php 
    include "../Components/Navbar.php";
    if(isset($_SESSION['adminkey']))header("Location: admin.php");
    ?>
    <!-- ----------------------------Filter Tab------------------------------------- -->
    <nav style="height: 70px;"></nav><!--buffer for navbar-->
    <nav style="height: 60px;">
      <div class="ms-auto align-items-center d-flex filter-tab" style="height: 60px;">
        <div class="ms-3 btn btn-light btn-rounded rounded-4 border border-dark-subtle filter-buttons" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="fa-solid fa-filter pe-2"></i>filters
        </div>
        <div class="ms-3 btn btn-light btn-rounded rounded-4 border border-dark-subtle filter-buttons" >Red</div>
        <div class="ms-3 btn btn-light btn-rounded rounded-4 border border-dark-subtle filter-buttons" >Bone Dry</div>
        <div class="ms-3 btn btn-light btn-rounded rounded-4 border border-dark-subtle filter-buttons" >White</div>
        <div class="ms-3 btn btn-light btn-rounded rounded-4 border border-dark-subtle filter-buttons" >Sparkling</div>
        <div class="ms-3 btn btn-light btn-rounded rounded-4 border border-dark-subtle filter-buttons" >Still</div>
      </div>
    </nav>
    <!-- ----------------------------Tab END --------------------------------------- -->

    <!-- ------------------------------Beginning-Wines------------------------------- -->
    <nav class="overflow-y-auto content-container">
      <nav class="website-container overflow-y-visible mb-3 pt-3 pb-3">

      </nav>
      <nav class="load-more-btn-container">
        <button class="btn btns-click mb-3 text-light" style="width: 150px;background-color: var(--app-theme-col);" onmouseup="loadMoreData()">Load More</button>
      </nav>
    </nav>
    <!-- </a> -->


    <!-- --------------------------------End-Wines------------------------------ -->
    <div class="modal fade modal-dialog-scrollable" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark" id="exampleModalLabel">Filter Wines</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <br>

            <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="ColourSelect">
              <option selected>Colour</option>
              <option value="Red">Red</option>
              <option value="White">White</option>
              <option value="Rose">Rosé</option>
            </select>
            <br>

            <div class="filter-modal-buffer"></div>
            <h6 class="text-dark">Carbonation:</h6>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="carbonation" id="inlineCheckbox1" value="Sparkling">
              <label class="form-check-label text-dark" for="inlineCheckbox1">Sparkling</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="carbonation" id="inlineCheckbox2" value="Semi-Sparkling">
              <label class="form-check-label text-dark" for="inlineCheckbox2">Semi-sparkling</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="carbonation" id="inlineCheckbox3" value="Still">
              <label class="form-check-label text-dark" for="inlineCheckbox3">Still</label>
            </div>
            <br>
            <br>

            <div class="filter-modal-buffer"></div>
            <h6 class="text-dark">Sweetness:</h6>
            <div class="form-check form-check-inline">
          
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="Sweetness" id="inlineCheckbox2" value="Bone Dry">
              <label class="form-check-label text-dark" for="inlineCheckbox2">Bone Dry</label>
            </div>


            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="Sweetness" id="inlineCheckbox3" value="Dry">
              <label class="form-check-label text-dark" for="inlineCheckbox3">Dry</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="Sweetness" id="inlineCheckbox4" value="Medium/off Dry">
              <label class="form-check-label text-dark" for="inlineCheckbox3">Medium/Off Dry</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="Sweetness" id="inlineCheckbox5" value="Medium Sweet">
              <label class="form-check-label text-dark" for="inlineCheckbox3">Medium Sweet</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="Sweetness" id="inlineCheckbox6" value="Dessert Sweetness">
              <label class="form-check-label text-dark" for="inlineCheckbox3">Dessert Sweetness</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="inlineCheckbox7" value="Very Sweet">
              <label class="form-check-label text-dark" for="inlineCheckbox3">Very Sweet</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="inlineCheckbox8" value="Intensely Sweet">
              <label class="form-check-label text-dark" for="inlineCheckbox3">Intensely Sweet</label>
            </div>
            <br>
            <br>
            <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="CountrySelect">
              <option selected>Country</option>
              <option value="Italy">Italy</option>
              <option value="South Africa">South Africa</option>
              <option value="France">France</option>
              <option value="Spain">Spain</option>
              <option value="United States">United States</option>
            </select>
            <br>
            <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="SortBySelect">
              <option selected>Sort By</option>
              <option value="pointScore">Score</option>
              <option value="alcohol_percentage">Alchol Percentage</option>
              <option value="year_bottled">Year Bottled</option>
              <option value="vintage">Vintage</option>
            </select>

            <br>
            <div class="filter-modal-buffer"></div>
            <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btns-click-gray" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btns-click" style="background-color: var(--app-theme-col);" id="UpdateFilters" >Update filters</button>
          </div>
        </div>
      </div>
    </div>

    <?php include "../Components/Footer.php";?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <script src="../Client/wines.js"></script>
</body>
</html>
