var wineNameLocalStorage = localStorage.getItem('winery_name');
var wineID;
var username;


// Send a request to the API for the wine with name: wineNameLocalStorage
type = "SEARCH_WINE";


const populateReviews = function(wineID){
    const req = new XMLHttpRequest;

    req.onreadystatechange = function() {
        if (req.readyState === 4 && req.status === 200){
            var reviewOutput = '';
            const res = req.responseText;
            var jRes = JSON.parse(res);

            if(jRes.status = "success"){
                if(jRes.data[0] == undefined){
                    return;
                }
                for(let i in jRes.data){
                    reviewOutput += '<tr>' +
                    '<th scope="row">' + jRes.data[i].reviewID + '</th>'+
                    '<td>' + jRes.data[i].review_description + '</td>'+
                    '<td>' + starGeneration(jRes.data[i].points) + '</td>'+
                    '<td>' + jRes.data[i].username + '</td>'+
                    '</tr>';
                }
    
                document.querySelector('tbody').innerHTML = reviewOutput;
            }
        }
    };

    req.open("GET", '../../Backend/Api/Api.php?type=GET_WINE_REVIEWS&&wineID=' + wineID);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send();
}

const getWineDetails = function() { // MUST BE POST with type->SEARCH_WINE
    const xhttpObject = new XMLHttpRequest();
    const body = JSON.stringify({
        "type": type,
        "name": wineNameLocalStorage
    });

    xhttpObject.onreadystatechange = function() {
        if (xhttpObject.readyState === 4 && xhttpObject.status === 200){
            var response = JSON.parse(xhttpObject.responseText);
            var data = response.data[0];
            
            document.getElementById('add_wine').innerHTML = `<div class="col-sm-4 d-flex align-items-center flex-column">
            <div class="card-item card card-info-container d-flex justify-content-center align-items-center rounded-3 pe-3 mb-5 me-1" style="height: 60vh; width: 18rem;">
              <img src="${data.wine_imageURL}" class="img-fluid" style="height: 50vh;" alt="wine-img">
            </div>
            <div class="card-item mini-card-cont card card-info-container d-flex justify-content-center align-items-center rounded-3 me-1">
              <h6>
                  <i class="fa-solid fa-star"></i>&nbsp; <strong>Critic Score:</strong> &nbsp; ${data.pointScore}
                </h6>
            </div>
            </div>
            <div class="col-sm-8 d-flex justify-content-center align-items-center">
            <div class="card card-info-container" style="width: 50rem;">
              <div class="card-body">
                <h1 class="card-title">${data.wine_name}</h1>
                <h3 class="card-subtitle mb-2 text-muted">${data.winery_name}</h3>
                <p class="card-text"></p>
                <div class="mb-4"></div>
                <h6>
                  <i class="fa-solid fa-droplet">&nbsp;&nbsp; </i><strong>Carbonation:</strong> &nbsp; ${data.carbonation}
                </h6>
                <h6>
                  <i class="fa-solid fa-cubes-stacked"> &nbsp;&nbsp; </i><strong>Sweetness:</strong> &nbsp; ${data.sweetness}
                </h6>
                <div class="mb-2"></div>
                <h6>
                  <i class="fa-solid fa-circle-notch"></i> &nbsp; <strong>Varietal:</strong> &nbsp; ${data.varietal}
                </h6>
                <div class="mb-2"></div>
                <h6>
                  <i class="fa-solid fa-palette"></i> &nbsp; <strong>Colour:</strong> &nbsp; ${data.colour}
                </h6>
                <div class="mb-2"></div>
                <h6 >
                  <i class="fa-regular fa-calendar"></i> &nbsp; <strong>Year Bottled:</strong> &nbsp; ${data.year_bottled}
                </h6>
                <div class="mb-2"></div>
                <h6>
                  <i class="fa-solid fa-earth-americas"></i> &nbsp; <strong>Region:</strong> &nbsp; ${data.region},&nbsp; ${data.country}
                </h6>
                <div class="mb-2"></div>
                <h6>
                  <i class="fa-solid fa-money-bill"></i> &nbsp; <strong>Price:</strong> &nbsp; ${data.price_amount} ${data.currency}
                </h6>
                <div class="mb-2"></div>
                <h6>
                  <i class="fa-solid fa-percent"></i> &nbsp; &nbsp; <strong>Alcohol:</strong> ${data.alcohol_percentage}%
                </h6>
                <div class="mb-2"></div>
              
                <div class="mb-5"></div>
                  
                <a href="#" class="card-link">
                  <div class="btn btn-primary btns-click" data-bs-toggle="modal" data-bs-target="#newReviewModal">Write Review</div>
                </a>
                <a href="#" class="card-link">Open winery</a>
              </div>
            </div>
            </div>`;

            wineID = data.wineID;
            populateReviews(data.wineID);
        } 
    };
    xhttpObject.open("POST", "../../Backend/Api/Api.php", true);
    xhttpObject.setRequestHeader("Content-type", "application/json");
    xhttpObject.send(body);
}

// Updating the Wine Details
getWineDetails();

const getUsername = function(){
    const req = new XMLHttpRequest;

    req.onreadystatechange = function() {
        if (req.readyState == 4 && req.status == 200) {
            const res = req.responseText;
            username = res;
        }
    }

    req.open('GET', '../Components/SessionHandler.php', false);
    req.send();
};

const insertReview = function() {
    getUsername();
    var points = document.getElementById("newPointScore").value;
    var review = document.getElementById("newReviewText").value;

    console.log("points: " + points + "\nreview: " + review);

    var req = new XMLHttpRequest();
    const body = JSON.stringify({
        "type": `INSERT_REVIEW`,
        "points": points,
        "review": review,
        "username": username,
        "wineID": wineID
    });

    req.onreadystatechange = function() {
        if (req.readyState === 4 && req.status === 200){
            
            var res = req.responseText;
            var jRes = JSON.parse(res);

            if (jRes.status = "Success") {
                location.reload();
            }
        }
    };
    req.open("POST", "../../Backend/Api/Api.php", true);
    req.setRequestHeader("Content-type", "application/json");
    req.send(body);
}

const openWinery = function(wineryID){
    const xhttpObject = new XMLHttpRequest();
    // switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            // switchOffLoader();
            window.location.href = "wineries-details.php";
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=OPEN_WINERY&id=" + wineryID);
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const searchFor = function(winery_name) {
    const xhttpObject = new XMLHttpRequest();
    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            // placeWineryElements(this.responseText);
            var response = JSON.parse(this.response);
            var data = response.data;
            data.forEach(element => {
                if(element.winery_name == winery_name) {
                    openWinery(element.wineryID);
                }
            });
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=GET_WINERIES");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}


//Conversion based on the following method: https://appetiteforwine.blog/2016/02/29/how-to-rate-wine/#:~:text=Over%20the%20years%2C%20I%27ve,-94%20%3D%204.5%20Stars%2FHearts
const starGeneration = function(points){
    if (points >= 95) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
    } else if (points >= 92) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half"></i>';
    } else if (points >= 88) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
    } else if (points >= 85) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half"></i>';
    } else if (points >= 82) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
    } else if (points >= 80) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half"></i>';
    } else if (points >= 77) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>';
    } else if (points >= 74) {
        return '<i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half"></i>';
    } else if (points >= 71) {
        return '<i class="fa-solid fa-star"></i>';
    } else {
        return '<i class="fa-solid fa-star-half"></i>';
    }
};

// var addReviewButton = document.querySelector('.btn-primary');
// var reviewModal = document.querySelector('#reviewModal');

// addReviewButton.addEventListener('click', function() {
//     reviewModal.classList.add('show');
// });