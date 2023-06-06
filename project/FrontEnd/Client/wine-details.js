var wineNameLocalStorage = localStorage.getItem('winery_name');


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
            
            document.getElementById('add_wine').innerHTML = `<div class="card mb-3 card-info-container" style="margin-top: 50px; padding: 100px;">
            <div class="row g-0">
                <div class="col-md-4">
                    <img id="image_wine" style="height: 800px; width:fit-content; padding: 20px; padding-left:150px; padding-right:150px;"  class="card-img-top" src="${data.wine_imageURL}" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                    <h5 id="Wine_Name" class="card-title">${data.wine_name}</h5>
                    <p class="card-text">The Under Oaks Chenin Blanc 2016 is a bright and shiny wine, with has a pale straw-yellow colour. The wine is well balanced, full flavoured and displays strong notes of guava and tropical fruit salad on the nose and palate. It has a good acidity and a long fruity aftertaste.  Although it is very full-bodied this Chenin Blanc is soft on the palate and a pleasure to drink.</p>
                    <br>
                    <li id="Winery" class="card-text" onclick="searchFor('${data.winery_name}')" style="cursor: pointer; color: black;" onmouseover="this.style.color='blue';" onmouseout="this.style.color='black';"><i class="fa-solid fa-map-pin"></i> &nbsp; &nbsp; <strong>Winery:</strong> &nbsp; ${data.winery_name}</li>
                    <hr>
                    <li id="Varietal" class="card-text"><i class="fa-solid fa-circle-notch"></i> &nbsp; <strong>Varietal:</strong> &nbsp; ${data.varietal}</li>
                    <hr>
                    <li id="Carbonation" class="card-text"><i class="fa-solid fa-cubes-stacked"> &nbsp;&nbsp; </i><strong>Carbonation:</strong> &nbsp; ${data.carbonation}</li>
                    <hr>
                    <li id="Sweetness" class="card-text">&nbsp;<i class="fa-solid fa-wine-glass"></i>&nbsp; &nbsp; <strong>Sweetness:</strong> &nbsp; ${data.sweetness}</li>
                    <hr>
                    <li id="Colour" class="card-text"> <i class="fa-solid fa-palette"></i> &nbsp; <strong>Colour:</strong> &nbsp; ${data.colour}</li>
                    <hr>
                    <li id="Vintage" class="card-text"><i class="fa-regular fa-calendar"></i> &nbsp; <strong>Vintage:</strong> ${data.vintage}</li>
                    <hr>
                    <li id="Year_Bottled" class="card-text"><i class="fa-regular fa-calendar"></i> &nbsp; <strong>Year Bottled:</strong> &nbsp; ${data.year_bottled}</li>
                    <hr>
                    <li id="Regions" class="card-text"><i class="fa-solid fa-earth-americas"></i> &nbsp; <strong>Region:</strong> &nbsp; ${data.region},&nbsp; ${data.country}</li>
                    <hr>
                    <li id="Price" class="card-text"><i class="fa-solid fa-money-bill"></i> &nbsp; <strong>Price:</strong> &nbsp; ${data.price_amount} ${data.currency}</li>
                    <hr>
                    <li id="Alcohol" class="card-text"><i class="fa-solid fa-percent"></i> &nbsp; &nbsp; <strong>Alcohol:</strong> ${data.alcohol_percentage}%</li>
                    <hr>
                    <li id="Address" class="card-text"><i class="fa-solid fa-location-dot"></i> &nbsp; &nbsp; <strong>Address:</strong> &nbsp; ${data.address}</li>
                    <hr>
                    <li id="Point_Score" class="card-text"><i class="fa-solid fa-star"></i>&nbsp; <strong>Critic Score:</strong> &nbsp; ${data.pointScore}</li>
                    <br>
                    <br>
                    <!-- ----------------------------Beginning Review------------------------------------- -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newReviewModal">
                        Add Review
                    </button>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="newReviewModal" tabindex="-1" role="dialog" aria-labelledby="newReviewModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="newReviewModalLabel">Write a Review</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                        <label for="newReviewText">Review:</label>
                                        <textarea class="form-control" id="newReviewText" rows="3" required></textarea>
                                        </div>
                                        <div class="form-group">
                                        <label for="newPointScore">Point Score (50-100):</label>
                                        <input type="number" class="form-control" id="newPointScore" min="50" max="100" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ----------------------------End Review--------------------------------- -->
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>`;
            populateReviews(data.wineID);
        } 
    };
    xhttpObject.open("POST", "../../Backend/Api/Api.php", true);
    xhttpObject.setRequestHeader("Content-type", "application/json");
    xhttpObject.send(body);
}
// Updating the Wine Details
getWineDetails();

// onSubmit

const onSubmit = function(e) {
    e.preventDefault();
    // Uncomment after implementing the form submission
    // const points = document.getElementById("points").value;
    // const review = document.getElementById("review").value;
    // const username = document.getElementById("username").value;
    // const wineID = localStorage.getItem('wineID')
    // SubmitReview(points, review, username, wineID);
}

// const reviewForm = document.getElementById("reviewForm");
// // Add the onSubmit event listener to the review form
// reviewForm.addEventListener("submit", function(e) {
//     onSubmit(e); // Pass the event object to onSubmit function
// });

const SubmitReview = function(points, review, username, wineID) {
    const xhttpObject = new XMLHttpRequest();
    const body = JSON.stringify({
        "type": `INSERT_REVIEW`,
        "points": `${points}`,
        "review": `${review}`,
        "username": `${username}`,
        "wineID": `${wineID}`
    });
    xhttpObject.onreadystatechange = function() {
        if (xhttpObject.readyState === 4 && xhttpObject.status === 200){
            var response = JSON.parse(xhttpObject.responseText);
            if (response.status === 200) {
                prependReview(data); // Array of review
            }
        } else {
            console.log("Request failed with status: " + xhttpObject.status);
        }
    };
    xhttpObject.open("POST", "../../Backend/Api/Api.php", true);
    xhttpObject.setRequestHeader("Content-type", "application/json");
    xhttpObject.send(body);
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

var addReviewButton = document.querySelector('.btn-primary');
var reviewModal = document.querySelector('#reviewModal');

addReviewButton.addEventListener('click', function() {
    reviewModal.classList.add('show');
});