let lastServedID = "";
let searchval = "";
var Country = "";
var Carbonation = "";
var Colour = "";
var Sweetness = "";
var Filterstring = "";
var Sort = "";

window.onload = function(){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".website-container").innerHTML = "";
            placeWineElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=GET_WINE&lastcount=0");
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
            placeWineElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=GET_WINE&lastcount=0");
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const searchFor = function() {
    const searchbarval = document.getElementById("searchbar").value;
    searchval = searchbarval;
    switchOnLoader();

    const xhttpObject = new XMLHttpRequest();
    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            document.querySelector(".website-container").innerHTML = "";
            placeWineElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=SEARCH_WINE&lastcount=0&name=" + searchbarval);
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

const placeWineElements = function(res){
    const jsonRes = JSON.parse(res);
    const websiteContainer = document.querySelector(".website-container");

    for(let i = 0; i < jsonRes.data.length; ++i){
        websiteContainer.innerHTML += '<div class="card card-item rounded-2" style="width: 18rem;">'+
                                        '<div class="img-container">'+
                                            '<img class="card-img-top" src="'+ jsonRes.data[i].wine_imageURL +'" alt="Card image cap">'+
                                        '</div>'+
                                        '<div class="card-body">'+
                                        '<h5 class="card-title">'+ jsonRes.data[i].wine_name +'</h5>' + 
                                        '<p class="card-text">'+ jsonRes.data[i].winery_name +'</p>'+
                                        '</div>'+
                                        '<ul class="list-group list-group-flush">'+
                                        '<li class="list-group-item"> <i class="fa-solid fa-circle-notch"></i> &nbsp;'+ jsonRes.data[i].varietal +'</li>'+
                                        '<li class="list-group-item"> <i class="fa-solid fa-palette"></i> &nbsp;'+ jsonRes.data[i].colour  +' </li>'+
                                        '<li class="list-group-item"> <i class="fa-solid fa-cubes-stacked"></i> &nbsp;'+ jsonRes.data[i].carbonation +' •  &nbsp;'+ jsonRes.data[i].sweetness +' </li>'+
                                        '<li class="list-group-item"><i class="fa-regular fa-calendar"></i> &nbsp; year bottled: '+ jsonRes.data[i].year_bottled +'</li>'+
                                        '</ul>'+
                                    '</div>';
    }
    lastServedID = jsonRes.lastcount;
    // On Click
    const divs = document.querySelectorAll('.card');
    // Add click event listener to each div
    divs.forEach(div => {
        div.addEventListener('click', () => {
            // Get the title element within the div
            const titleElement = div.querySelector('.card-title');
            // Get the text of the title element
            const titleText = titleElement.textContent;
            localStorage.setItem('winery_name', titleText);
            window.location.href = "wine-details.php";
        });
    });
}

const loadMoreData = function(){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();
    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            placeWineElements(this.responseText);
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?" + "type=SEARCH_WINE&lastcount=" + lastServedID + "&name=" + searchval);
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const openWine = function(){
    const xhttpObject = new XMLHttpRequest();
    switchOnLoader();

    xhttpObject.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200){
            switchOffLoader();
            window.location.href = "wine-details.php";
        }
    };

    xhttpObject.open("GET", "../../Backend/Api/Api.php?type=OPEN_WINE&id=" + wineID);
    xhttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttpObject.send();
}

const checkValue = function(){
    console.log("here")
    const searchbarval = document.getElementById("searchbar").value;
    if(searchbarval === "")document.querySelector(".cancel-search-btn").hidden = true;
    else document.querySelector(".cancel-search-btn").hidden = false;
}

const filterBy = function(){

}

$(document).ready(function(){
    var FilterBC = 'div.ms-3.btn.btn-light.btn-rounded.rounded-4.border.border-dark-subtle.filter-buttons';
    $(FilterBC).click(function(){
        console.log("Insideee");
        var typeWine = $(this).html();
        console.log(typeWine);
        if(typeWine == "Red" || typeWine == "White" || typeWine == "Bone Dry" || typeWine == "Sparkling"|| typeWine == "Still" || typeWine == "Champagne")
        {
            var body = KnowFilter(typeWine);
            console.log(JSON.stringify(body));
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function()
            {
                if(this.readyState== 4 && this.status == 200)
                {
                    document.querySelector(".website-container").innerHTML = "";
                    placeWineElements(this.responseText);
                }
            }

            xhr.open("POST","../../Backend/Api/Api.php")
            xhr.send(JSON.stringify(body));
        }

    })
 ////////////////Country Select flter
    $("#CountrySelect").on("change",function(){
        Country = $(this).val();
        console.log("Country : " + Country);
        if(Filterstring == "")
        Filterstring = '{country : ' + Country + ' ';
        else Filterstring = Filterstring + ' , country : ' + Country + ' ';
        var Sbody = {
            type : "GET_WINE",
            filters : { country : Country}
        }
        // var xhr = new XMLHttpRequest();
        // xhr.onreadystatechange = function()
        // {
        //     if(this.readyState== 4 && this.status == 200)
        //     {
        //         document.querySelector(".website-container").innerHTML = "";
        //         placeWineElements(this.responseText);
        //     }
        // }

        // xhr.open("POST","../../Backend/Api/Api.php")
        // xhr.send(JSON.stringify(Sbody));

    })
 ///////////////////////////////////Carbonation filter
    const carbonationRadios = document.querySelectorAll('input[name="carbonation"]');
  
    carbonationRadios.forEach(function(radioButton) {
        radioButton.addEventListener('change', function() {
            Carbonation = this.value;
            console.log("Carbonation : " + Carbonation)
            if(Filterstring == "")
            Filterstring = '{carbonation : ' + Carbonation + '';
            else Filterstring = Filterstring + ' , carbonation : ' + Carbonation + ' ';
        });
    })

  ////////////////////////////////Sweetness
    const SweetRadioB = document.querySelectorAll('input[name="Sweetness"]');
  
    SweetRadioB.forEach(function(radioButton) {
        radioButton.addEventListener('change', function() {
            Sweetness = this.value;
            console.log("Sweetness : " + Sweetness);
            if(Filterstring == "")
            Filterstring = '{sweetness : ' + Sweetness + ' ';
            else Filterstring = Filterstring + ' , sweetness : ' + Sweetness + ' ';
        });
    })


 //////////////////////////////////Colour Select
    $("#ColourSelect").on("change",function(){
        Colour = $(this).val();
        console.log("Colour : " + Colour);
        if(Filterstring == "")
        Filterstring = '{colour : ' + Colour + ' ';
        else Filterstring = Filterstring + ' , colour : ' + Colour + ' ';
    })


 
    $("#SortBySelect").on("change",function(){
        Sort = $(this).val();
        console.log("Sort : " + Colour);
        if(Filterstring == "")
        Filterstring = '{colour : ' + Colour + ' ';
        else Filterstring = Filterstring + ' , colour : ' + Colour + ' ';
    })
    
    $("#UpdateFilters").click(function(){
        
        var Sbody;
        console.log("Sort: " + Sort);
        if(Sort == "")
        {
            Sbody = {
                type : "GET_WINE",
                filters : {}
            }
        }
        else 
        {
            Sbody = {
                type : "GET_WINE",
                sort : Sort,
                filters : {}
            }
        }




        BodyFilter(Sbody.filters);

        console.log(Sbody);
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function()
        {
            if(this.readyState== 4 && this.status == 200)
            {
                console.log("Inside post");
                document.querySelector(".website-container").innerHTML = "";
                placeWineElements(this.responseText);
            }
        }

        xhr.open("POST","../../Backend/Api/Api.php")
        xhr.send(JSON.stringify(Sbody));

        

    })

})

function KnowFilter(str)
{
    var Pbody;
    if(str == "Red" || str== "White" )
    {
        Pbody = {
            type : "GET_WINE",
            filters : {colour : str}
        }
    }
    else if(str == "Bone Dry")
    {
        Pbody = {
            type : "GET_WINE",
            filters : {sweetness : str}
        }
    }
    else if(str == "Bordeaux" || str== "Champagne")
    {
        Pbody = {
            type : "GET_WINE",
            filters : {varietal : str}
        }
    }
    else if(str == "Sparkling" || str == "Still")
    {
        Pbody = {
            type : "GET_WINE",
            filters : {carbonation : str}
        }
    }

    return Pbody;

}

function BodyFilter(filter)
{
    if(Country != "")
    {
        filter.country = Country;
    }
    if(Carbonation != "")
    {
        filter.carbonation = Carbonation;
    }
    if(Sweetness != "")
    {
        filter.sweetness = Sweetness;
    }
    if(Colour)
    {
        filter.colour = Colour;
    }
}
