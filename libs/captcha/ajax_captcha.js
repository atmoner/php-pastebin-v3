   var http_request = false;
   function makeRequest(url, parameters) {
      http_request = false;
      if (window.XMLHttpRequest) { // Mozilla, Safari,...
         http_request = new XMLHttpRequest();
         if (http_request.overrideMimeType) {
         	// set type accordingly to anticipated content type
            //http_request.overrideMimeType('text/xml');
            http_request.overrideMimeType('text/html');
         }
      } else if (window.ActiveXObject) { // IE
         try {
            http_request = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
            try {
               http_request = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {}
         }
      }
      if (!http_request) {
         alert('Cannot create XMLHTTP instance');
         return false;
      }
      http_request.onreadystatechange = alertContents;
      http_request.open('GET', url + parameters, true);
      http_request.send(null);
   }

   function alertContents() {
      if (http_request.readyState == 4) {
         if (http_request.status == 200) {
            //alert(http_request.responseText);
            result = http_request.responseText;
            document.getElementById('myspan').innerHTML = result; 
			//Get a reference to CAPTCHA image
			img = document.getElementById('imgCaptcha'); 
			//Change the image
			img.src = 'captcha/create_image.php?' + Math.random(); // Search for new image
			document.getElementById('txtCaptcha').value=''; //Reset input Captcha  after succes return 			
         } else {
            alert('There was a problem with the request.');
         }
      }
   }
   
   function get(obj) {
      var getstr = "?" +
			"&mytextarea=" + encodeURI( document.getElementById("mytextarea").value) +
			"&txtCaptcha=" + encodeURI( document.getElementById("txtCaptcha").value) ;
      makeRequest('libs/captcha/get.php', getstr);
   }

// IMAGE REFRESHING
function refreshimg()
{
	//Get a reference to CAPTCHA image
	img = document.getElementById('imgCaptcha'); 
	//Change the image
	img.src = 'libs/captcha/create_image.php?' + Math.random();
}
