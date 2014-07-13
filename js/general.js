//SET GLOBALS

//Offline
//var sAPIURL = 'http://localhost/MyLocalMenu/API/api.php';

//Online
var sAPIURL = 'http://mylocalcafe.dk/API/api.php';

$(document).ready(function() {
    CheckInternetConnection();
    
});

function CheckInternetConnection() {
    var status = navigator.onLine;
    if( status === true ){
    //if( window.jQuery ){
        //App is online
        $('#Offline').hide();
    }
    else {
        $('#Offline').show();
        $('#login').hide();
    }
}   


function GetRestuarentInfo() {
    
    $.ajax({
        type: "GET",
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
    
    //Check for image, if true the upload the image before saving the message
    
    
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
           $('#dMessageEnd').val('');
           //FjernPrewievImage();
           GetMessages();
           
           //TODO: Remove ui-state-hover on SaveMessage btn
       });
    }
    else {
        //alert('udfyld felter');
        $(".newmessage").after("<div class='MessageEmpty'>Du skal skrive en besked og en slut dato</div>");
        $(".MessageEmpty").hide().slideDown(200);
        $(".MessageEmpty").delay('1000').slideUp(200, function(){
            $(this).remove();
        });
    }
}

function GetMessages() {
    
   $.ajax({
        type: "GET",
        url: "API/api.php",
        dataType: "json",
        data: {sFunction:"GetMessages"}
       }).done(function(result) 
       {

          $('#oldMessages').html('');
          $('#currentMessages').html('');

          $.each(result.Messages, function(key,value){
              var date = value.dtMessageDate;
              var yy = date.substring(0,4);
              var mm = date.substring(5,7);
              var dd = date.substring(8,10);
              var h = date.substring(11,13);
              var m = date.substring(14,16);
              var date = dd+"-"+mm+"-"+yy+" "+h+":"+m;

              if( key === 0){
                  if (value.sMessageImage == "") {
                      $('#currentMessages').append('<div><h1>' + value.sMessageHeadline + '</h1><h3>' + date + '</h3><h2>' + value.sMessageBodyText + '</h2></div>');
                  } else {
                      $('#currentMessages').append('<img src="imgmsg_sendt/' + value.sMessageImage + '"><div><h1>' + value.sMessageHeadline + '</h1><h3>' + date + '</h3><h2>' + value.sMessageBodyText + '</h2></div>');
                  }
              }
              else {
                  if (value.sMessageImage == "") {
                      $('#oldMessages').append('<div><h1>' + value.sMessageHeadline + '</h1><h3>' + date + '</h3><h2>' + value.sMessageBodyText + '</h2></div>');
                  } else {
                      $('#oldMessages').append('<img src="imgmsg_sendt/' + value.sMessageImage + '"><div><h1>' + value.sMessageHeadline + '</h1><h3>' + date + '</h3><h2>' + value.sMessageBodyText + '</h2></div>');
                  }
              }
          });
           
       });
   
}



//Capture Image function
$(function() {

  $("#captureimage").change(function(ev) {

    var reader = new FileReader();
    reader.onload = (function(ev) {
      $("#image_preview").attr("src", ev.target.result).fadeIn();
    });

    var file = this.files[0];
    $("#image_preview").data("name", file.name);
    reader.readAsDataURL(file);
    
    $('#image_preview').show();
    
  });
});

function CaptureImage(){
    $('#captureimage').click();
}