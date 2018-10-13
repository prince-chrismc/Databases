function getCurrentDate(){
    var d = new Date();
    var days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var hours = d.getHours()
    if (hours < 10) hours = '0'+hours
    var minutes = d.getMinutes()
    if (minutes < 10) minutes = '0'+minutes
    var seconds = d.getSeconds()
    if (seconds < 10) seconds = '0'+seconds
    
    
        return "" + days[d.getDay()] + ", " + months[d.getMonth()] + " " + d.getDate() + ", " + (1900 + d.getYear()) + " - " + hours + ":" + minutes + ":" + seconds;
        }

function setTimeContinuous(){
            document.getElementById("time").innerHTML = getCurrentDate();
            setInterval(setTimeContinuous, 990);   
}