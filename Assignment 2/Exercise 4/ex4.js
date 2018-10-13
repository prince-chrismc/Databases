var pb1 = 19.99, pb2 = 86.00, pb3 = 55.00;

function calcOrder(){
    var qb1 = document.getElementById("book_1").value,
    qb2 = document.getElementById("book_2").value,
    qb3 = document.getElementById("book_3").value;
    
    if(qb1 == null || qb2 == null || qb3 == null){
        alert("must fill in all forms");
    }
    else if(isNaN(qb1) || isNaN(qb2) || isNaN(qb3)){
        if(isNaN(qb1)){
            document.getElementById("book_1").style.backgroundColor = "rgba(255,0,0,0.5)";
        }
        else{
             document.getElementById("book_1").style.backgroundColor = "white";
        }
        if(isNaN(qb2)){
            document.getElementById("book_2").style.backgroundColor = "rgba(255,0,0,0.5)";
        }
        else{
             document.getElementById("book_2").style.backgroundColor = "white";
        }
        if(isNaN(qb3)){
            document.getElementById("book_3").style.backgroundColor = "rgba(255,0,0,0.5)";
        }
        else{
             document.getElementById("book_2").style.backgroundColor = "white";
        }
        alert("must be numeric quantities");
    }
    else if(qb1 < 0 || qb2 < 0 || qb3 < 0){
        if(qb1 < 0){
            document.getElementById("book_1").style.backgroundColor = "rgba(255,0,0,0.5)";
        }
        else{
             document.getElementById("book_1").style.backgroundColor = "white";
        }
        if(qb2 < 0){
            document.getElementById("book_2").style.backgroundColor = "rgba(255,0,0,0.5)";
        }
        else{
             document.getElementById("book_2").style.backgroundColor = "white";
        }
        if(qb3 < 0){
            document.getElementById("book_3").style.backgroundColor = "rgba(255,0,0,0.5)";
        }
        else{
             document.getElementById("book_3").style.backgroundColor = "white";
        }
        alert("must be greater than 0 quantities");
    }
    else {
        document.getElementById("ans").innerHTML = "Total: $"+ ((pb1*qb1) + (pb2*qb2) + (pb3*qb3)).toFixed(2);
        document.getElementById("book_1").style.backgroundColor = "white";
        document.getElementById("book_2").style.backgroundColor = "white";
        document.getElementById("book_2").style.backgroundColor = "white";
    }
}