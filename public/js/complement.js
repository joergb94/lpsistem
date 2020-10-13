dataC();
function dataC(){
setInterval(function(){ 
    axios.get('/get_data')
    .then(function (response) {  
        $('#coins-data').html(response.data.coins);
    })
    .catch(function (error) {
    });

 }, 8000);
}

function menuMove(){
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }
