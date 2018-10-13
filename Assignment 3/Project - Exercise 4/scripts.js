
function clockStart() { 
    var timerId;
    if (timerId) return
    timerId = setInterval(update, 1000)
    update()
}

function update() {
  var date = new Date()
  var hours = date.getHours()
  if (hours < 10) hours = '0'+hours
  document.getElementById('hour').innerHTML = hours
  var minutes = date.getMinutes()
  if (minutes < 10) minutes = '0'+minutes
  document.getElementById('min').innerHTML = minutes
  var seconds = date.getSeconds()
  if (seconds < 10) seconds = '0'+seconds
  document.getElementById('sec').innerHTML = seconds
}


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


function AddtoCart(item)
{
    return;
}

function checkRegister()
{
	if(document.getElementById("firstname").value == "")
	{
		alert("Please Enter Your First Name!");
	}
	else if(document.getElementById("lastname").value == "")
	{
		alert("Please Enter Your Last Name!");
	}
	else if(document.getElementById("email").value == "")
	{
		alert("Please Enter Your Email!");
	}
	else if(document.getElementById("phone").value == "")
	{
		alert("Please Enter Your Phone Number!");
	}
	else if(document.getElementById("password").value == "")
	{
		alert("Please Enter A Password!");
	}
	else if(document.getElementById("confirm").value == "")
	{
		alert("Please Enter A Confirmation Password!");
	}
	else
	{
		if(document.getElementById("pass").value != (document.getElementById("confirm").value))
		{
			alert("The Passwords Don't Match!");
		}
	}
	return false;
}