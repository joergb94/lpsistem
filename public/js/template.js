function menuMove(){
    var x = document.getElementById("main-menu");
    if (x.style.display === "none") {
    x.style.display = "block";
    } else {
    x.style.display = "none";
    }
}

setTimeout(function(){  
    if($('#loading-wrapper').hide()){
        $('#appwraper').show(); 
    }
    
  }, 1000);
 