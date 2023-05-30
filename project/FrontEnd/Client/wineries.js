const searchFor = function() {
    const searchbarval = document.getElementById("searchbar").value;
    switchOnLoader();

    if(searchbarval === "")return;

    const xhttpObject = new XMLHttpRequest();
    const body = JSON.stringify({
        "type": "GET_WINERIES",
    });

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200)placeWineryElements(this.responseText);
    };

    xhttpObject.open("GET", "../../Api/Api.php");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send(body);
}

const switchOnLoader = function(){

}

const placeWineryElements = function(res){
    const jsonRes = JSON.parse(res);
}