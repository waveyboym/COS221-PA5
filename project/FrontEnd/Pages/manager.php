<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/global.css">
    <link rel="stylesheet" href="../Styles/manager.css">
    <script src="https://kit.fontawesome.com/d271141ba3.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/svg+xml" href="../Assets/wine-glass-solid.svg" />
    <title>Winery SA | Manager</title>
</head>
<body>
  <!-- COMMENT OUT THE SECOND LINE OF PHP IF YOU CANNOT LOG IN, FOR TESTING PURPOSES
    <?php 
      include "../Components/Navbar.php";
      if(!isset($_SESSION['managerkey']) && !isset($_SESSION['adminkey']))header("Location: index.php");
    ?>
    (login functionality actually taking me to managers.php does not seem to be working for me)-->

  <!-- Hello there! You can feel free to delete this comment if necessary. I figured I'd explain some
  of what is actually *on* this revised page here so I can clarify what needs doing, but hit me up with any questions
  if you need to.

  The backend work necessary is mostly just loading data into the sheet, which is API wizardry I have alas very little
  experience with. I have a template for what you'll need to add for each row below, which might be useful.

  I also have the SQL queries written for loading the data that forms the first simple cards on the page, as well as
  the one used for loading all the wines from a particular user's winery. The latter SQL query is directly below, alongside the template.

  Ideally, the manager will be able to use the trashcan or pen button on each row to delete or edit a wine, or
  use the 'add wine' button to add wines. The wine in question will automatically have a wineryID identical to the winery
  that the user manages. While I could have created cards to pop up when the buttons are clicked, I didn't want to end up
  making something that would take more work to redo properly than to simply do once with the goal of using the API in mind.

  Once again, if you need anything from me, please let me know - I'm going to be working on my portion of the PDF, so I'll be
  awake.
  -->
  <!-- TEMPLATE -->
  <!--
  <tr>
    <td>WINE NAME</td>
    <td>VARIETAL</td>
    <td>CARBONATION</td>
    <td>SWEETNESS</td>
    <td>COLOUR</td>
    <td>VINTAGE</td>
    <th scope="row action-btns">
      <i class="fa-solid fa-trash action-btn"></i>
    </th>
    <th scope="row action-btns">
      <i class = "fa-solid fa-edit action-btn"></i>
    </th>
  </tr>

  SELECT *
  FROM wine
  JOIN winery ON wine.wineryID = winery.wineryID
  JOIN user ON winery.winery_manager = user.userid
  WHERE user.username = 'USERNAME';

  -->

    <nav class="main-admin-container">
      <nav class="at-a-glance-cards">
        <div class="card at-a-glance-card">
          <div class="card-body">
            <h5 class="card-title">Winery name</h5>
            <div class="card-icon-and-count">
              <i class="fa-solid fa-store pe-2" style="font-size: 1.5rem;"></i>
              <!-- This card's text should load in the name of the manager's assigned winery. -->
              <!-- The SQL query that should be used could look something like the below comment. -->
              <!-- 
                SELECT winery_name
                FROM winery
                JOIN user ON winery.winery_manager = user.userID
                WHERE user.username = 'LisaWilbourn'
                LIMIT 1;
              -->
              <h2 class="card-text Winery-name"></h2>
            </div>
          </div>
        </div>
        <div class="card at-a-glance-card">
          <div class="card-body">
            <h5 class="card-title">Total wines</h5>
            <div class="card-icon-and-count">
              <i class="fa-solid fa-wine-glass pe-3" style="font-size: 1.5rem;"></i>
              <!-- This card's text should load in the count of the wines associated with the manager's assigned winery. -->
              <!-- The SQL query that should be used could look something like the below comment. -->
              <!-- SELECT COUNT(*) AS wine_count
                  FROM wine
                  JOIN winery ON wine.wineryid = winery.wineryid
                  JOIN user ON winery.winery_manager = user.userid
                  WHERE user.username = 'USERNAME; 
              -->
              <h2 class="card-text Total-wines"></h2>
            </div>
          </div>
        </div>
        <div class="card at-a-glance-card">
          <div class="card-body">
            <h5 class="card-title">Total reviews</h5>
            <div class="card-icon-and-count">
              <i class="fa-solid fa-star" style="font-size: 1.5rem;"></i>
              <!-- This card's text should load in the count of the reviews associated with wines at the manager's assigned winery. -->
              <!-- The SQL query that should be used could look something like the below comment. -->
              <!-- SELECT COUNT(*) AS wine_count
                  SELECT COUNT(*) AS review_count
                  FROM review
                  JOIN wine ON review.wineID = wine.wineID
                  JOIN winery ON wine.wineryID = winery.wineryID
                  JOIN user ON winery.winery_manager = user.userid
                  WHERE user.username = 'USERNAME';
              -->
              <h2 class="card-text Total-reviews"></h2>
            </div>
          </div>
        </div>
        <div class="card at-a-glance-card">
          <div class="card-body">
            <h5 class="card-title">Average score</h5>
            <div class="card-icon-and-count">
              <i class="fa-solid fa-percent pe-3" style="font-size: 1.5rem;"></i>
              <!-- This card's text should load in the average score of the reviews associated with wines at the manager's assigned winery. -->
              <!-- The SQL query that should be used could look something like the below comment. -->
              <!-- SELECT COUNT(*) AS wine_count
                  SELECT AVG(points) AS review_count
                  FROM review
                  JOIN wine ON review.wineID = wine.wineID
                  JOIN winery ON wine.wineryID = winery.wineryID
                  JOIN user ON winery.winery_manager = user.userid
                  WHERE user.username = 'USERNAME';
              -->
              <h2 class="card-text Average-score"></h2>
            </div>
          </div>
        </div>
      </nav>
      <nav class="list-of-various-elements">
        <nav class="navigation-tabs-for-list">
          <div class="btn btn-primary btns-click" style="margin-left: auto; margin-right: auto;" data-bs-toggle="modal" data-bs-target="#addwine">add wine</div>
        </nav>
        <nav class="container-of-data list-group">
          <table class="table mb-3">
            <thead>
              <tr>
                <th scope="col">id</th>
                <th scope="col">Wine name</th>
                <th scope="col">Varietal</th>
                <th scope="col">Carbonation</th>
                <th scope="col">Sweetness</th>
                <th scope="col">Colour</th>
                <th scope="col">Vintage</th>
                <th scope="col invisible-row-col">#</th>
                <th scope="col invisible-row-col">#</th>
              </tr>
            </thead>
            <tbody></tbody>

          </table>
          <button class="btn btn-primary btns-click" style="width: 150px; margin-left: auto; margin-right: auto;" onmouseup="loadMoreData()">Load More</button>
        </nav>
      </nav>
    </nav>
  
    <!-- add -->
    <div class="modal fade" id="addwine" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark" id="exampleModalLabel">Add new wine</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <div class="mb-3">
              <label for="wine-name-add-input" class="form-label text-dark">Wine name</label>
              <input type="text" class="form-control" id="wine-name-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-varietal-add-input" class="form-label text-dark">Varietal</label>
              <input type="text" class="form-control" id="wine-varietal-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-carbonation-add-input" class="form-label text-dark">Carbonation</label>
              <input type="text" class="form-control" id="wine-carbonation-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-sweetness-add-input" class="form-label text-dark">Sweetness</label>
              <input type="text" class="form-control" id="wine-sweetness-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-colour-add-input" class="form-label text-dark">Colour</label>
              <input type="text" class="form-control" id="wine-colour-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-vintage-add-input" class="form-label text-dark">Vintage</label>
              <input type="text" class="form-control" id="wine-vintage-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-year_bottled-add-input" class="form-label text-dark">Year bottled</label>
              <input type="text" class="form-control" id="wine-year_bottled-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-wine_imageURL-add-input" class="form-label text-dark">Image url</label>
              <input type="text" class="form-control" id="wine-wine_imageURL-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-pointScore-add-input" class="form-label text-dark">Point Score</label>
              <input type="text" class="form-control" id="wine-pointScore-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-currency-add-input" class="form-label text-dark">Currency</label>
              <input type="text" class="form-control" id="wine-currency-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-price_amount-add-input" class="form-label text-dark">Price amount</label>
              <input type="text" class="form-control" id="wine-price_amount-add-input">
            </div>
            <div class="mb-3">
              <label for="wine-alcohol_percentage-add-input" class="form-label text-dark">Alcohol percentage</label>
              <input type="text" class="form-control" id="wine-alcohol_percentage-add-input">
            </div>
            <div class="form-error-container mb-3">
              <label for="text-danger" class="text-danger"></label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btns-click-gray" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btns-click" style="background-color: var(--app-theme-col);" onmouseup="addWine()" data-bs-dismiss="modal">Add a new wine</button>
          </div>
        </div>
      </div>
    </div>
  
    <!-- edit -->
    <div class="modal fade" id="editwine" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark" id="exampleModalLabel">Edit wine</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="wine-name-edit-input" class="form-label text-dark">Wine name</label>
              <input type="text" class="form-control" id="wine-name-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-varietal-edit-input" class="form-label text-dark">Varietal</label>
              <input type="text" class="form-control" id="wine-varietal-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-carbonation-edit-input" class="form-label text-dark">Carbonation</label>
              <input type="text" class="form-control" id="wine-carbonation-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-sweetness-edit-input" class="form-label text-dark">Sweetness</label>
              <input type="text" class="form-control" id="wine-sweetness-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-colour-edit-input" class="form-label text-dark">Colour</label>
              <input type="text" class="form-control" id="wine-colour-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-vintage-edit-input" class="form-label text-dark">Vintage</label>
              <input type="text" class="form-control" id="wine-vintage-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-year_bottled-edit-input" class="form-label text-dark">Year bottled</label>
              <input type="text" class="form-control" id="wine-year_bottled-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-wine_imageURL-edit-input" class="form-label text-dark">Image url</label>
              <input type="text" class="form-control" id="wine-wine_imageURL-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-pointScore-edit-input" class="form-label text-dark">Point Score</label>
              <input type="text" class="form-control" id="wine-pointScore-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-currency-edit-input" class="form-label text-dark">Currency</label>
              <input type="text" class="form-control" id="wine-currency-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-price_amount-edit-input" class="form-label text-dark">Price amount</label>
              <input type="text" class="form-control" id="wine-price_amount-edit-input">
            </div>
            <div class="mb-3">
              <label for="wine-alcohol_percentage-edit-input" class="form-label text-dark">Alcohol percentage</label>
              <input type="text" class="form-control" id="wine-alcohol_percentage-edit-input">
            </div>
            <div class="form-error-container mb-3">
              <label for="text-danger" class="text-danger"></label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btns-click-gray" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btns-click" style="background-color: var(--app-theme-col);" onmouseup="editWine()" data-bs-dismiss="modal">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  

    <!-- delete confirm -->
        <!-- Modal -->
    <div class="modal fade" id="confirmDelete" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-dark" id="exampleModalLabel">Confirm deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="text-dark" class="text-dark">Are you sure you want to delete this wine</label>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btns-click-gray" data-bs-dismiss="modal">No</button>
            <button type="button" class="btn btn-primary btns-click" style="background-color: var(--app-theme-col);" onmouseup="deleteWine()" data-bs-dismiss="modal">Yes</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
  <script src="../Client/manager.js"></script>
</body>
</html>