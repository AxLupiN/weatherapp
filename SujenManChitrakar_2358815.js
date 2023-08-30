search_check("Sevenoaks")//keeping the initial name of the designated city
document.getElementById("button").addEventListener("click",search_checker);

function search_checker(){//making a function to show the table of data 
    input=document.getElementById("search-bar").value
    if(input != "Sevenoaks"){
        document.getElementsByTagName("table")[0].style.display="none"
    }else{
        document.getElementsByTagName("table")[0].style.display="block"
    }
    search_check(input)

}

function search_check(input){//making function to search desired city all around the world
    fetch('https://api.openweathermap.org/data/2.5/weather?q='+input+'&units=metric&appid=78538f2a77f2eda10a309627f735ae14')
    .then(response=>response.json())
    .then(d=> data_fetcher(d))
    .catch(error=>{
        document.getElementById("search-bar").value=""
        document.getElementById("search-bar").placeholder="Invalid name. Please enter again."
    })
}

function data_fetcher(d){//making a function to fetch the data from api

    console.log(d)
    let lat=d["coord"]["lat"]
    let lon=d["coord"]["lon"]
    fetch('https://api.openweathermap.org/data/2.5/weather?lat='+lat+'&lon='+lon+'&appid=78538f2a77f2eda10a309627f735ae14')//code to fetch the data from the api
    .then(res=>res.json())
    .then(dat=>{
        console.log(dat)
        let rainq=dat["rain"]["1h"];//code to fetch the data from the api
        document.getElementsByClassName("rain")[0].innerHTML="Rain: "+rainq+"mm";
    })
    .catch(error=>document.getElementsByClassName("rain")[0].innerHTML="No rain")

    let cloudData=d["weather"][0]["description"];
    let iconValue=d["weather"][0]["icon"]
    let temp=d["main"]["temp"]
    let humidity=d["main"]["humidity"]
    let name=d["name"]
    let wind=d["wind"]["speed"]
    let max1=d["main"]["temp_max"]
    let max2=d["main"]["temp_min"]
    let currentDate = new Date().toJSON().slice(0, 10);
    const weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    const da = new Date();
    let day = weekday[da.getDay()];
    document.getElementsByClassName("date")[0].innerHTML=day+", "+currentDate//showing or printing the data fetched from the api in the html
    document.getElementsByClassName("city")[0].innerHTML="Weather in "+name
    document.getElementsByClassName("temp")[0].innerHTML=temp+"°C"
    document.getElementsByClassName("description")[0].innerHTML=cloudData;
    document.getElementsByClassName("humidity")[0].innerHTML="Humidity: "+humidity+"%";
    document.getElementsByClassName("wind")[0].innerHTML="Wind speed: "+wind+"Km/h"
    document.getElementById("search-bar").placeholder="Search for a country/city."
    document.getElementsByClassName("max")[0].innerHTML="Max-Temperature: "+max1+"°C"
    document.getElementsByClassName("min")[0].innerHTML="Min-Temperature: "+max2+"°C"
    document.getElementById("icon").src="https://openweathermap.org/img/wn/"+iconValue+".png"
   
}
