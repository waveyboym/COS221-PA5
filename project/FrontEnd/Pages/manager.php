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
      
      if(!isset($_SESSION['managerkey']) || !isset($_SESSION['adminkey']))header("Location: index.php");
      
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
              <h2 class="card-text">PLACEHOLDER</h2>
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
              <h2 class="card-text">PLACEHOLDER</h2>
            </div>
          </div>
        </div>
        <div class="card at-a-glance-card">
          <div class="card-body">
            <h5 class="card-title">Total reviews</h5>
            <div class="card-icon-and-count">
              <i class="fa-solid fa-person pe-3" style="font-size: 1.5rem;"></i>
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
              <h2 class="card-text">PLACEHOLDER</h2>
            </div>
          </div>
        </div>
        <div class="card at-a-glance-card">
          <div class="card-body">
            <h5 class="card-title">Average score</h5>
            <div class="card-icon-and-count">
              <i class="fa-solid fa-people-roof pe-3" style="font-size: 1.5rem;"></i>
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
              <h2 class="card-text">PLACEHOLDER</h2>
            </div>
          </div>
        </div>
      </nav>
      <nav class="list-of-various-elements">
        <nav class="navigation-tabs-for-list">
          <div class="btn btn-primary btns-click">add wine</div>
        </nav>
        <nav class="container-of-data list-group">
          <table class="table">
            <thead>
              <tr>
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
            <tbody>
              <tr>
                <td>Montrachet Grand Cru 2010</td>
                <td>Johannisberg</td>
                <td>sparkling</td>
                <td>Medium/off dry</td>
                <td>white</td>
                <td>2010</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Montrachet Grand Cru 2014</td>
                <td>Moscatel</td>
                <td>still</td>
                <td>Very sweet</td>
                <td>white</td>
                <td>2014</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Meursault Les Rougeots 2001</td>
                <td>Fernão Pires</td>
                <td>still</td>
                <td>Dry</td>
                <td>white</td>
                <td>2001</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Corton-Charlemagne Grand Cru N.V.</td>
                <td>Emir</td>
                <td>semi-sparkling</td>
                <td>Medium/off dry</td>
                <td>white</td>
                <td>1968</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Estate Finch Hollow Chardonnay (Cave Fermented) 2014</td>
                <td>Laški Rizling</td>
                <td>still</td>
                <td>Medium/off dry</td>
                <td>white</td>
                <td>2014</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Y 1996</td>
                <td>Códega de Larinho</td>
                <td>semi-sparkling</td>
                <td>Very sweet</td>
                <td>white</td>
                <td>1996</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Bâtard-Montrachet Grand Cru 1996</td>
                <td>Merseguera</td>
                <td>sparkling</td>
                <td>Dry</td>
                <td>white</td>
                <td>1996</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Montrachet Grand Cru Marquis de Laguiche 2004</td>
                <td>Fiano</td>
                <td>still</td>
                <td>Dry</td>
                <td>white</td>
                <td>2004</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
              <tr>
                <td>Meursault Les Rougeots 2005</td>
                <td>Garganega</td>
                <td>semi-sparkling</td>
                <td>Medium/off dry</td>
                <td>white</td>
                <td>2005</td>
                <th scope="row action-btns">
                  <i class="fa-solid fa-trash action-btn"></i>
                </th>
                <th scope="row action-btns">
                  <i class="fa-solid fa-edit action-btn"></i>
                </th>
              </tr>
          
            </tbody>
          </table>
        </nav>
      </nav>
    </nav>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</body>
</html>