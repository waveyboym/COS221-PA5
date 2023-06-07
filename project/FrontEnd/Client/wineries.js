var Places = [{latitude : "33.9249", longitude : "18.4241"},
{latitude : "33.9608", longitude : "25.6022"},{latitude : "29.8587", longitude : "31.0218"},
{latitude : "26.2041", longitude : "28.0473"},{latitude : "25.7479", longitude : "28.2293"},
{latitude : "33.0198", longitude : "27.9039"},{latitude : "29.6006", longitude : "30.3794"},
{latitude : "29.0852", longitude : "26.1596"}]

window.onload = function(){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".website-container").innerHTML = "";
            placeWineryElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=GET_WINERIES");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const loadDefault = function(){
    document.getElementById("searchbar").value = "";
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".website-container").innerHTML = "";
            placeWineryElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=GET_WINERIES");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const searchFor = function() {
    const searchbarval = document.getElementById("searchbar").value;
    switchOnLoader();

    const xhttpObject = new XMLHttpRequest();
    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".website-container").innerHTML = "";
            placeWineryElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=SEARCH_WINERY&name=" + searchbarval);
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const openWinery = function(wineryID){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            window.location.href = "wineries-details.php";
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=OPEN_WINERY&id=" + wineryID);
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const switchOnLoader = function(){
    const websiteContainer = document.querySelector(".website-container");
    websiteContainer.innerHTML += '<div class="spinner-container">' +
                                    '<div class="spinner-grow text-success" role="status">' +
                                        '<span class="sr-only">Loading...</span>' +
                                    '</div>' +
                                '</div>';
}

const switchOffLoader = function(){
    document.querySelector(".spinner-container").remove();
}

const placeWineryElements = function(res){
    const jsonRes = JSON.parse(res);
    const websiteContainer = document.querySelector(".website-container");

    for(let i = 0; i < jsonRes.data.length; ++i){
        websiteContainer.innerHTML += '<div class="card card-item rounded-2" style="width: 18rem;" onmouseup="openWinery(\''+ jsonRes.data[i].wineryID +'\')">' +
                                '<div class="img-container">' +
                                '<img class="card-img-top" src="'+ jsonRes.data[i].winery_imageURL +'" alt="Card image cap">' +
                                '</div>' +
                                '<div class="card-body">' +
                                '<h5 class="card-title">'+ jsonRes.data[i].winery_name +'</h5>' +
                                '<p class="card-text description-text">'+ jsonRes.data[i].description +'</p>' +
                                '</div>' +
                                '<ul class="list-group list-group-flush">' +
                                '<li class="list-group-item">Location: &nbsp;'+ jsonRes.data[i].address +'</li>' +
                                '<li class="list-group-item">Region: &nbsp;'+ jsonRes.data[i].region_name +'</li>' +
                                '<li class="list-group-item">Verification status: &nbsp;'+ isVerified(jsonRes.data[i].isVerified) +'</li>' +
                                '</ul>' +
                            '</div>';
    }
}

const isVerified = function(verfiedState){return verfiedState == 1 ? '<i class="fa-solid fa-circle-check"></i>' : "N/A"}

const checkValue = function(){
    const searchbarval = document.getElementById("searchbar").value;
    if(searchbarval === "")document.querySelector(".cancel-search-btn").hidden = true;
    else document.querySelector(".cancel-search-btn").hidden = false;
}

const filterBy = function(){

}

$(document).ready(function(){

    $("#opt1").click(()=>{
        FilterSearch("#opt1")
    })
    $("#opt2").click(()=>{
        FilterSearch("#opt2")
    })
    $("#opt3").click(()=>{
        FilterSearch("#opt3")
    })
    $("#opt4").click(()=>{
        FilterSearch("#opt4")
    })
    $("#opt5").click(()=>{
        FilterSearch("#opt5")
    })
        
    
});

function FilterCheck(str){     /////Function for filtering takes in the number to know which option it is
    var location = 0;
    switch (str) {
        case "Cape Town":
            location = 0;
            break;
    
        case "Port Elizabeth":
            location = 1;
            break;
        case "Durban":
            location = 2;
            break;
        case "Johannesburg":
            location = 3;
            break;
        case "Pretoria":
            location = 4;
            break;
        case "East London":
            location = 5;
            break;
        case "Pietermaritzburg":
            location = 6;
            break;
        case "Bloemfontein":
            location = 7;
            break;
    } 
    return location;

}

function FilterSearch(name)
{


    NotFound= false;
    var Country = $(name).html()
    console.log(Country);
    var body = {
        type : 'GET_WINERIES',
        filtercountry : Country
    }
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange= function(){
        if(this.readyState == 4 && this.status == 200)
        {

            document.querySelector(".website-container").innerHTML = "";
            console.log("Before data populated")
            placeWineryElements(this.responseText);
        }
    }

    xhr.open("POST","../../Backend/Api/Api.php");
    xhr.send(JSON.stringify(body));
}

const getAllLocations = function(){
    switchOnLoader();

    const xhttpObject = new XMLHttpRequest();
    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".website-container").innerHTML = "";
            placeWineryElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=SEARCH_WINERY&name=");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}
