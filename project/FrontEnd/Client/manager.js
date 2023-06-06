let currentlySelectedWinery = "";
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
    const wine_name = document.getElementById("winery-name-input").value;
    const varietal = document.getElementById("winery-imageurl-input").value;
    const carbonation = document.getElementById("winery-websiteurl-input").value;
    const sweetness = document.getElementById("winery-location-input").value;
    const colour = document.getElementById("winery-country-input").value;
    const vintage = document.getElementById("winery-region-input").value;
    const year_bottled = document.getElementById("longitude").value;
    const wine_imageURL = document.getElementById("latitude").value;
    const pointScore = document.getElementById("winery-managerid-input").value;
    const currency = document.getElementById("winery-isVerified-input").checked;
    const price_amount = document.getElementById("floatingTextarea2").value;
    const alcohol_percentage = document.getElementById("floatingTextarea2").value;

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
            lastServedID = 0;
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
    const wine_name = document.getElementById("winery-name-input").value;
    const varietal = document.getElementById("winery-imageurl-input").value;
    const carbonation = document.getElementById("winery-websiteurl-input").value;
    const sweetness = document.getElementById("winery-location-input").value;
    const colour = document.getElementById("winery-country-input").value;
    const vintage = document.getElementById("winery-region-input").value;
    const year_bottled = document.getElementById("longitude").value;
    const wine_imageURL = document.getElementById("latitude").value;
    const pointScore = document.getElementById("winery-managerid-input").value;
    const currency = document.getElementById("winery-isVerified-input").checked;
    const price_amount = document.getElementById("floatingTextarea2").value;
    const alcohol_percentage = document.getElementById("floatingTextarea2").value;

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
            lastServedID = 0;
            loadMoreData();
        }
    };

    const body = JSON.stringify({
        "type": "EDIT_WINE",
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
            if(currentlyOpenTab === "wineries"){
                currentlyOpenTab = "managers";
                viewWineries();
            }
            else{
                currentlyOpenTab = "wineries";
                viewManagers();
            }
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=DELETE_WINERY_ADMIN&wineryID=" + currentlySelectedWinery);
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
    lastServedID = res.data.wines[res.data.wines.length - 1].wineID;
}

const setWineId = function(val){
    currentlySelectedWinery = val;
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