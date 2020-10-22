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



