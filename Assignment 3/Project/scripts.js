function getCurrentDate(){
    var d = new Date();
    var days = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
    var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            
        return "" + days[d.getDay()] + ", " + months[d.getMonth()] + " " + d.getDate() + ", " + (1900 + d.getYear()) + " - " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
        }

function setTimeContinuous(){
    for(var t = 0; t>=0;t+=1)
        {
            document.getElementById("time").innerHTML = getCurrentDate();
        }
        
    
}