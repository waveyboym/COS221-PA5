let currentlySelectedWine = "";
let lastServedID = "";
let lastcount = 1;

window.onload = function(){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            populateData(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?" + "type=LOAD_MANAGER_DATA&last_id=0");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const switchOnLoader = function(){
    const websiteContainer = document.querySelector(".main-admin-container");
    websiteContainer.innerHTML += '<div class="spinner-container">' +
                                    '<div class="spinner-grow text-success" role="status">' +
                                        '<span class="sr-only">Loading...</span>' +
                                    '</div>' +
                                '</div>';
}

const switchOffLoader = function(){document.querySelector(".spinner-container").remove();}

const addWine = function(){
    const wine_name = "Montrachet Grand Cru 1805";//document.getElementById("wine-name-add-input").value;
    const varietal =  "Johannisberg"; //document.getElementById("wine-varietal-add-input").value;
    const carbonation = "sparkling";//document.getElementById("wine-carbonation-add-input").value;
    const sweetness = "Medium/off dry";//document.getElementById("wine-sweetness-add-input").value;
    const colour = "white";//document.getElementById("wine-colour-add-input").value;
    const vintage = "2010"; //document.getElementById("wine-vintage-add-input").value;
    const year_bottled = "2011";// document.getElementById("wine-year_bottled-add-input").value;
    const wine_imageURL = "https://images.vivino.com/thumbs/rORmihtxSrKG7SfuI0bD6w_pb_x300.png"; //document.getElementById("wine-wine_imageURL-add-input").value;
    const pointScore = "98"; // document.getElementById("wine-pointScore-add-input").value;
    const currency = "ZAR"; //document.getElementById("wine-currency-add-input").checked;
    const price_amount = "179";// document.getElementById("wine-price_amount-add-input").value;
    const alcohol_percentage = "17.10";// document.getElementById("wine-alcohol_percentage-add-input").value;

    if(wine_name === "" || varietal === "" || carbonation === "" || sweetness === ""
    || colour === "" || vintage === "" || year_bottled === "" || wine_imageURL === "" 
    || pointScore === "" || currency === "" || price_amount === "" || alcohol_percentage === ""){
        document.querySelector(".form-error-container label").innerHTML = "Form cannot be empty";
        return;
    }

    switchOnLoader();

    const xhttpObject = new XMLHttpRequest();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".container-of-data .table tbody").innerHTML = "";
            lastServedID = 0;
            lastcount = 1;
            loadMoreData();
        }
    };

    const body = JSON.stringify({
        "type": "ADD_WINE",
        "wine_name": wine_name ,
        "varietal": varietal ,
        "carbonation": carbonation ,
        "sweetness": sweetness ,
        "colour": colour ,
        "vintage": vintage ,
        "year_bottled": year_bottled ,
        "wine_imageURL": wine_imageURL ,
        "pointScore": pointScore ,
        "currency": currency,
        "price_amount": price_amount ,
        "alcohol_percentage": alcohol_percentage
    });

    xhttpObject.open("POST", "../../Backend/Api/Api.php");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send(body);
}

const editWine = function(){
    const wine_name = document.getElementById("wine-name-edit-input").value;
    const varietal = document.getElementById("wine-varietal-edit-input").value;
    const carbonation = document.getElementById("wine-carbonation-edit-input").value;
    const sweetness = document.getElementById("wine-sweetness-edit-input").value;
    const colour = document.getElementById("wine-colour-edit-input").value;
    const vintage = document.getElementById("wine-vintage-edit-input").value;
    const year_bottled = document.getElementById("wine-year_bottled-edit-input").value;
    const wine_imageURL = document.getElementById("wine-wine_imageURL-edit-input").value;
    const pointScore = document.getElementById("wine-pointScore-edit-input").value;
    const currency = document.getElementById("wine-currency-edit-input").checked;
    const price_amount = document.getElementById("wine-price_amount-edit-input").value;
    const alcohol_percentage = document.getElementById("wine-alcohol_percentage-edit-input").value;

    if(wine_name === "" && varietal === "" && carbonation === "" && sweetness === ""
    && colour === "" && vintage === "" && year_bottled === "" && wine_imageURL === "" 
    && pointScore === "" && currency === "" && price_amount === "" && alcohol_percentage === ""){
        document.querySelector(".form-error-container label").innerHTML = "Form cannot be empty";
        return;
    }

    switchOnLoader();

    const xhttpObject = new XMLHttpRequest();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".container-of-data .table tbody").innerHTML = "";
            lastServedID = 0;
            lastcount = 1;
            loadMoreData();
        }
    };

    const body = JSON.stringify({
        "type": "EDIT_WINE",
        "wineID": currentlySelectedWine,
        "wine_name": wine_name === "" ? null : wine_name,
        "varietal": varietal === "" ? null : varietal ,
        "carbonation": carbonation === "" ? null : carbonation ,
        "sweetness": sweetness === "" ? null : sweetness ,
        "colour": colour === "" ? null : colour ,
        "vintage": vintage === "" ? null : vintage ,
        "year_bottled": year_bottled === "" ? null : year_bottled ,
        "wine_imageURL": wine_imageURL === "" ? null : wine_imageURL ,
        "pointScore": pointScore === "" ? null : pointScore ,
        "currency": currency === "" ? null : currency,
        "price_amount": price_amount === "" ? null : price_amount ,
        "alcohol_percentage": alcohol_percentage === "" ? null : alcohol_percentage
    });

    xhttpObject.open("POST", "../../Backend/Api/Api.php");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send(body);
}

const deleteWine = function(){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".container-of-data .table tbody").innerHTML = "";
            lastServedID = 0;
            lastcount = 1;
            loadMoreData();
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=DELETE_WINE&wineID=" + currentlySelectedWine);
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const populateData = function(jsonData){
    const res = JSON.parse(jsonData);

    document.querySelector(".Winery-name").innerHTML = res.data.wineryname;
    document.querySelector(".Total-wines").innerHTML = res.data.wineCount;
    document.querySelector(".Total-reviews").innerHTML = res.data.reviewcount;
    document.querySelector(".Average-score").innerHTML = res.data.avgpoints;

    const table = document.querySelector(".container-of-data .table tbody");

    for(let i = 0; i < res.data.wines.length; ++i, ++lastcount){
        table.innerHTML += '<tr>' +
                                '<th scope="row">'+ lastcount +'</th>' + 
                                '<td>'+ res.data.wines[i].wine_name +'</td>' +
                                '<td>'+ res.data.wines[i].varietal +'</td>' +
                                '<td>'+ res.data.wines[i].carbonation +'</td>' +
                                '<td>'+ res.data.wines[i].sweetness +'</td>' +
                                '<td>'+ res.data.wines[i].colour +'</td>' +
                                '<td>'+ res.data.wines[i].vintage +'</td>' +
                                '<th scope="row action-btns">' +
                                    '<div data-bs-toggle="modal" data-bs-target="#editwine" onmouseup="setWineId(\''+ res.data.wines[i].wineID +'\')">' +
                                        '<i class="fa-solid fa-pen-to-square action-btn"></i>' +
                                    '</div>' +
                                '</th>' +
                                '<th scope="row action-btns">' +
                                    '<div data-bs-toggle="modal" data-bs-target="#confirmDelete" onmouseup="setWineId(\''+ res.data.wines[i].wineID +'\')">' +
                                        '<i class="fa-solid fa-trash action-btn"></i>' +
                                    '</div>' +
                                '</th>' +
                            '</tr>';
    }
    if(res.data.wines.length > 0)
        lastServedID = res.data.wines[res.data.wines.length - 1].wineID;
}

const setWineId = function(val){
    currentlySelectedWine = val;
} 

const loadMoreData = function(){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();
    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            populateData(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?" + "type=LOAD_MANAGER_DATA&last_id=" + lastServedID);
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}