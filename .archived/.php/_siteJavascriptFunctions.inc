//sustainability site javascript
//supports ajax calls and toggling dsplay vsibiliyy
function toggle(targetId) {
    var element = document.getElementById(targetId);
    //console.log(element);
    if( element.style.display == 'none' ) {
        element.style.display = 'block';
    } else { 
        element.style.display = 'none';
    }
    return element;
}
// Function to get/send project, type, task from activity list page
function ajaxRefresh(callType, selContext, targetElement)
{
if (callType=="")
  {
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById(targetElement).outerHTML=xmlhttp.responseText;
    }
  }

var selValue = selContext.options[selContext.selectedIndex].value;
  
xmlhttp.open("GET","_ajaxHandler.php?ajaxFunction=" + callType + "&ajaxId=" + selValue,true);
xmlhttp.send();
}
function set_element(element_id, inner_html)
{
	document.getElementById(element_id).innerHTML = inner_html;
}

function set_input(element_id, input_value)
{
	document.getElementById(element_id).value = input_value;
}
function zeroPad(in_value){
	var x=in_value;
	if (in_value<10){
		x = "0" + in_value;
	}
	return x;
}
function displayClientTime(){
	var d = new Date();
	var tzo = d.getTimezoneOffset();
	var tzmod = "-";
	if (tzo < 0){
		tzmod = "+";
	}
	tzo = Math.abs(tzo);
	var tzh = Math.floor(tzo / 60);
	var tzm = tzo - (tzh * 60);
	var tz = tzmod + zeroPad(tzh) + ":" + zeroPad(tzm);
	set_input('client-time-zone', tz);	
}
