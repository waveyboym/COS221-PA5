<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../Styles/global.css">
    <link rel="stylesheet" href="../Styles/wine-details.css">
    <link rel="stylesheet" href="../Styles/profile.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css"> -->
    <script src="https://kit.fontawesome.com/d271141ba3.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/svg+xml" href="../Assets/wine-glass-solid.svg" />
    <title>Winery SA | Wines</title>
</head>
<body>
    <?php
    include "../Components/Navbar.php";
    if(isset($_SESSION['adminkey']))header("Location: admin.php");
    ?>
    <div id="add_wine" class="row w-100" style="margin-top: 100px; margin-bottom: 40px; height: calc(100vh-70px);">
      <div class="col-sm-4 d-flex align-items-center flex-column">
        <div class="card-item card card-info-container d-flex justify-content-center align-items-center rounded-3 pe-3 mb-5 me-1" style="height: 60vh; width: 18rem;">
          <img src="{url of wine image goes here}" class="img-fluid" style="height: 50vh;" alt="wine-img">
        </div>
        <div class="card-item mini-card-cont card card-info-container d-flex justify-content-center align-items-center rounded-3 me-1">
          <h6>
              <i class="fa-solid fa-star"></i>&nbsp; <strong>Critic Score:</strong> &nbsp; {pointscore of wine goes here}
            </h6>
      </div>
      </div>
      <div class="col-sm-8 d-flex justify-content-center align-items-center">
        <div class="card card-info-container" style="width: 50rem;">
          <div class="card-body">
            <h1 class="card-title">{Name of wine image goes here}</h1>
            <h3 class="card-subtitle mb-2 text-muted">{Winery name of wine goes here}</h3>
            <p class="card-text">{description of wine goes here}</p>
            <div class="mb-4"></div>
            <h6>
              <i class="fa-solid fa-droplet">&nbsp;&nbsp; </i><strong>Carbonation:</strong> &nbsp; {Carbonation of wine goes here}
            </h6>
            <h6>
              <i class="fa-solid fa-cubes-stacked"> &nbsp;&nbsp; </i><strong>Sweetness:</strong> &nbsp; {Sweetness of wine goes here}
            </h6>
            <div class="mb-2"></div>
            <h6>
              <i class="fa-solid fa-circle-notch"></i> &nbsp; <strong>Varietal:</strong> &nbsp; {Varietal of wine goes here}
            </h6>
            <div class="mb-2"></div>
            <h6>
              <i class="fa-solid fa-palette"></i> &nbsp; <strong>Colour:</strong> &nbsp; {Colour of wine goes here}
            </h6>
            <div class="mb-2"></div>
            <h6 >
              <i class="fa-regular fa-calendar"></i> &nbsp; <strong>Year Bottled:</strong> &nbsp; {year_bottled of wine goes here}
            </h6>
            <div class="mb-2"></div>
            <h6>
              <i class="fa-solid fa-earth-americas"></i> &nbsp; <strong>Region:</strong> &nbsp; {region of wine goes here},&nbsp; {country of wine goes here}
            </h6>
            <div class="mb-2"></div>
            <h6>
              <i class="fa-solid fa-money-bill"></i> &nbsp; <strong>Price:</strong> &nbsp; {price of wine goes here} {currency of wine goes here}
            </h6>
            <div class="mb-2"></div>
            <h6>
              <i class="fa-solid fa-percent"></i> &nbsp; &nbsp; <strong>Alcohol:</strong> {alchol percentage of wine goes here}%
            </h6>
            <div class="mb-2"></div>
          
            <div class="mb-5"></div>
              
            <a href="#" class="card-link">
              <div class="btn btn-primary btns-click" data-bs-toggle="modal" data-bs-target="#newReviewModal">Write Review</div>
            </a>
            <a href="#" class="card-link">Open winery</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="newReviewModal" tabindex="-1" role="dialog" aria-labelledby="newReviewModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-dark" id="newReviewModalLabel">Write a Review</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="form-group">
                                            <label for="newReviewText" class="text-dark">Review:</label>
                                            <textarea class="form-control" id="newReviewText" rows="3" required></textarea>
                                            </div>
                                            <div class="form-group">
                                            <label for="newPointScore" class=" text-dark">Point Score (50-100):</label>
                                            <input type="number" class="form-control" id="newPointScore" min="50" max="100" required>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary btns-click" onmouseup="insertReview()">Submit</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

    <nav class="list-of-various-elements">
        <nav class="container-of-data list-group">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">id</th>
                <th scope="col">Review description</th>
                <th scope="col">Stars</th>
                <th scope="col">Reviewer</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </nav>
      </nav>
    </nav>
    <!-- --------------------------------End-Wine-Details------------------------------ -->

    

    <?php include "../Components/Footer.php";?>
  <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify18QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-xxxxxxxxxxxxxxxxxxxx" crossorigin="anonymous"></script> -->
  <script src="../Client/wine-details.js" type="text/javascript"></script>
  <!-- <script src="../Client/wineries.js"></script> -->
</body>
</html>