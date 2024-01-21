const options = {
    method: 'GET',
    headers: {
        'X-RapidAPI-Key': '127d8668eamsh690930e3d4cf22ap17bc4ejsnb515a1b39150',
        'X-RapidAPI-Host': 'weather-by-api-ninjas.p.rapidapi.com'
    }
};
let sunRise;
let sunSet;
let cursunRise;
let cursunSet;
const getWeather=(city) =>{
    cityName.innerHTML = city
fetch('https://weather-by-api-ninjas.p.rapidapi.com/v1/weather?city='+city,options)
    .then(response => response.json())
    .then((response) => {
        console.log(response);

        cloud_pct.innerHTML=response.cloud_pct
        temp .innerHTML=response.temp 
        temp2 .innerHTML=response.temp
        //feels_like .innerHTML=response.feels_like 
        humidity .innerHTML=response.humidity 
        humidity2 .innerHTML=response.humidity
        min_temp .innerHTML=response.min_temp 
        max_temp .innerHTML=response.max_temp 
        wind_speed.innerHTML=response.wind_speed
        wind_speed2.innerHTML=response.wind_speed
        wind_degrees.innerHTML=response.wind_degrees
        sunRise=response.sunrise
        cursunRise = response.sunrise
        sunSet=response.sunset
        

        sunRise = sunRise * 1000;
        let date = new Date(sunRise);
        date = date.toLocaleString()
        let sunriseTime = date.slice(10,21)
        sunrise.innerHTML = sunriseTime

        cursunRise = cursunRise * 1000;
        let curDate = new Date(cursunRise);
        curDate = curDate.toLocaleDateString()
        appDate.innerHTML = curDate



        sunSet = sunSet * 1000;
        let setDate = new Date(sunSet);
        setDate = setDate.toLocaleString()
        let sunsetTime = setDate.slice(10,21)
        sunset.innerHTML = sunsetTime

    })
    .catch (err => console.error(err));
    if(sunRise){
        ; 
    }
}
submit.addEventListener("click", (e)=>{
    e.preventDefault();
    getWeather(city.value);
})
getWeather("Mumbai")