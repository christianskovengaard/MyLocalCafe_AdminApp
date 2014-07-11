//SET GLOBALS

//Offline
//var sAPIURL = 'http://localhost/MyLocalMenu/API/api.php';

//Online
var sAPIURL = 'http://mylocalcafe.dk/API/api.php';

window.onload = function(){
    CheckInternetConnection();
    
};

function CheckInternetConnection() {
    //var status = navigator.onLine;
    //if( status === true ){
    if( window.jQuery ){
        //App is online
    }
    else {
        $('#Offline').show();
    }
}   


function GetRestuarentInfo() {
    
    $.ajax({
        type: "POST",
        url: "API/api.php",
        dataType: "json",
        data: {sFunction:"GetRestuarentInfo"}
       }).done(function(result) {
           if(result.result === 'true'){
               $('#cafename').html(result.name);
           }else{
               
           }
       });
}


function SaveMessage() {
    
    if($("#sMessageHeadline").val() !== "" && $("#sMessengerTextarea").val() !== "" && $('#dMessageEnd').val() !== "" ){
        
       var aData = {};
       
       aData['dMessageStart'] = $('#dMessageStart').val();
       aData['dMessageEnd'] = $('#dMessageEnd').val();
       aData['sMessageHeadnline'] = $('#sMessageHeadline').val(); 
       aData['sMessageBodyText'] = $('#sMessengerTextarea').val();
       aData['iMessageImageId'] = $("#MessageImage").attr('data-urlid');
       
       //Workaround with encoding issue in IE8 and JSON.stringify
       for (var i in aData) {
           aData[i] = encodeURIComponent(aData[i]);
       }

       var sJSON = JSON.stringify(aData);
       
       $.ajax({
        type: "POST",
        url: "API/api.php",
        dataType: "json",
        data: {sFunction:"SaveMessage",sJSON:sJSON}
       }).done(function(result) 
       {
           //alert('Besked gemt: '+result.result);
           $('#sMessageHeadline').val(''); 
           $('#sMessengerTextarea').val('');
           //FjernPrewievImage();
           //GetMessages();
       });
    }
    else {
        alert('udfyld felter');
//        $(".Messagepreview").after("<div class='MessageEmpty'>Du skal skrive en besked og en slut dato</div>");
//        $(".MessageEmpty").hide().slideDown(200);
//        $(".MessageEmpty").delay('1000').slideUp(200, function(){
//            $(this).remove();
//        });
    }
}