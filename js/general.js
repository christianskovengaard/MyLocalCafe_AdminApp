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
      
    if($("#sMessageHeadline").val() !== "" && $("#sMessengerTextarea").val() !== "" && $('#dMessageEnd').val() !== "" ){
    
    //Check for image, if true the upload the image before saving the message
    if($('#image_preview').attr('src') !== ''){
        //Get image
        var files = document.getElementById('captureimage').files;
        
        //Upload image and save the message from the upload function  
        upload(files, function (file) {});

    }else{  
        
       //Save message without image 
       var aData = {};
       
       aData['dMessageStart'] = $('#dMessageStart').val();
       aData['dMessageEnd'] = $('#dMessageEnd').val();
       aData['sMessageHeadnline'] = $('#sMessageHeadline').val(); 
       aData['sMessageBodyText'] = $('#sMessengerTextarea').val();
       //Get the image Id
       aData['iMessageImageId'] = '';
       
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

function SaveMessageWithImage() {
    
    var aData = {};
       
    aData['dMessageStart'] = $('#dMessageStart').val();
    aData['dMessageEnd'] = $('#dMessageEnd').val();
    aData['sMessageHeadnline'] = $('#sMessageHeadline').val(); 
    aData['sMessageBodyText'] = $('#sMessengerTextarea').val();
    //Get the image Id
    alert('$(#image_preview).attr(data-urlid)'+ $("#image_preview").attr('data-urlid'));
    aData['iMessageImageId'] = $("#image_preview").attr('data-urlid');

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

 function upload(files, done, dataialt, datatilbage, progressFunction) {
     var formobject = new FormData();
     formobject.append('file[]', files[0]);
     formobject.append('sFunction', 'UploadImage');

     var oldfilelist = files;
     files = [];
     for (var i = 1; i < oldfilelist.length; i++) {
         files.push(oldfilelist[i])
     }

     var xhrhttp;
     if (window.XMLHttpRequest)
     {// code for IE7+, Firefox, Chrome, Opera, Safari
         xhrhttp=new XMLHttpRequest();
     }
     else
     {// code for IE6, IE5
         xhrhttp=new ActiveXObject("Microsoft.XMLHTTP");
     }

     xhrhttp.open('POST', "API/api.php", true);


     try {
         xhrhttp.upload.onprogress = function (e) {
             /** @namespace e.lengthComputable */
             if (e.lengthComputable) {
                 var percentComplete = (1 - ((datatilbage - e.loaded) / dataialt)) * 100;
                 if(!progressFunction) {
                     $('#upload_in_progress_bar').css("width", percentComplete + "%");
                 }else{
                     progressFunction(percentComplete);
                 }

             }
         };
     } catch (e) {
     }

     xhrhttp.onload = function () {
         if (this.status == 200) {
             var result = JSON.parse(this.response);
             if (result.result) {
                 // dette bliver kort hvis billede bliver uploadet med succes
                 //addImageOnBibList(result);
                 //Set ID on image_preview
                 $("#image_preview").attr('data-urlid',result.images.id);
                 SaveMessageWithImage();

             } else {
                 // dette bliver koret hvis billede ikke bliver uploaded
                 if (result.toSmall) {
                     alert("Dette billede er ikke stort nok");
                 } else {
                     alert("fejl");
                 }
             }
             var filer = [];
             for (var i = 0; i < files.length; i++) {
                 filer.push(files[i].name);
             }
             if (files.length > 0) {
                 var dataleft = 0;
                 for (var i = 0; i < files.length; i++) {
                     dataleft += files[i].size
                 }



                 $('#upload_in_progress').html('<p>Uploadding ...</p><div id="upload_in_progress_bar_outer"><div id="upload_in_progress_bar" style="width: '+parseFloat((1-(dataleft/dataialt))*100)+'%;"></div></div><p>' + filer.join(', <br> ') + '</p>');
                 // todo skal set timeout fjernes?
                 setTimeout(function () {
                     // koe upload igen hvis der er flere billeder der skal uploades



                     upload(files, function () {}, dataialt, dataleft);
                 }, 250);
             }else {
                 // ryd upload_in_progress diven
                 $('#upload_in_progress').html('');
                 done(result.images.n, result.images.id)
             }
         }
     };
     xhrhttp.send(formobject);


}


function CaptureImage(){
    $('#captureimage').click();
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