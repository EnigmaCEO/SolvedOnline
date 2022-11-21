<html>
<head>
<title>Solved Online</title>
</head>

<link href="style2.css" rel="stylesheet" type="text/css" />

<!-- You can load the jQuery library from the Google Content Network.
Probably better than from your own server. -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
 
<!-- Load the CloudCarousel JavaScript file -->
<script type="text/JavaScript" src="js/cloud-carousel.1.0.4.js"></script>
 
<script>
function LoadList(){
						   
	// This initialises carousels on the container elements specified, in this case, carousel1.
	$("#carousel1").CloudCarousel(		
		{			
			xPos: 310,
			yPos: 50,
			reflHeight: 50,
			bringToFront: true,
			buttonLeft: $("#left_arrow"),
			buttonRight: $("#right_arrow"),
			altBox: $("#alt-text"),
			titleBox: $("#title-text")
		}
	);
}
   
var mode = 0;
var index = 0;
var angle = new Array();
var pieces = new Array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19);

	
function createRequestObject()
{
	var request_o; //declare the variable to hold the object.
	var browser = navigator.appName; //find the browser name
	if(browser == "Microsoft Internet Explorer"){
		/* Create the object using MSIE's method */
		request_o = new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		/* Create the object using other browser's method */
		request_o = new XMLHttpRequest();
	}
	return request_o; //return the object
}

function CursorUp() {
	var cx = document.getElementById('cursor').style.top;
	//alert(cx);
	if(cx == '50px') document.getElementById('cursor').style.top = '155px';
	if(cx == '85px') document.getElementById('cursor').style.top = '50px';
	if(cx == '120px') document.getElementById('cursor').style.top = '85px';
	if(cx == '155px') document.getElementById('cursor').style.top = '120px';
}

function CursorDown() {
	var cx = document.getElementById('cursor').style.top;
	//alert(cx);
	if(cx == '50px') document.getElementById('cursor').style.top = '85px';
	if(cx == '85px') document.getElementById('cursor').style.top = '120px';
	if(cx == '120px') document.getElementById('cursor').style.top = '155px';
	if(cx == '155px') document.getElementById('cursor').style.top = '50px';
	
}

function CursorEnter() {
	var cx = document.getElementById('cursor').style.top;
	
	if(cx == '50px') ListPuzzles(1);
	if(cx == '85px') ListPuzzles(2);
}

function CycleLeft() {
	if(pieces.length == 0) return;
	
	var id;
	var i = pieces[index];
	
	if(angle[i]) 
		a = angle[i];
	else
		a = 0;
		
	id = document.getElementById('ppiece'+i+'-'+a);
	
	if(id) id.style.display = 'none';
	
	index -= 1;
	if(index < 0) index = pieces.length-1;
	
	i = pieces[index];
	
	if(angle[i]) 
		a = angle[i];
	else
		a = 0;
		
	id = document.getElementById('ppiece'+i+'-'+a);
	if(id) id.style.display = 'inline';	
	
}

function CycleRight() {
	if(pieces.length == 0) return;

	var id;
	var i = pieces[index];
	
	if(angle[i]) 
		a = angle[i];
	else
		a = 0;
	
	id = document.getElementById('ppiece'+i+'-'+a);
	
	if(id) id.style.display = 'none';
	
	index += 1;
	if(index > pieces.length-1) index = 0;
	
	i = pieces[index];
	
	if(angle[i]) 
		a = angle[i];
	else
		a = 0;
	
	id = document.getElementById('ppiece'+i+'-'+a);
	if(id) id.style.display = 'inline';	
}


function RotateUp() {
	if(pieces.length == 0) return;
	
	var id;
	var i = pieces[index];
	
	if(angle[i]) 
		a = angle[i];
	else {
		a = 0;
		angle[i] = 0;
	}
	
	id = document.getElementById('ppiece'+i+'-'+a);
	
	if(id) id.style.display = 'none';
	
	a -= 1;
	if(a < 0) a = 3;
	
	angle[i] = a;
	
	id = document.getElementById('ppiece'+i+'-'+a);
	if(id) id.style.display = 'inline';	

}

function RotateDown() {
	if(pieces.length == 0) return;
	
	var id;
	var i = pieces[index];
	
	if(angle[i]) 
		a = angle[i];
	else {
		a = 0;
		angle[i] = 0;
	}
	
	id = document.getElementById('ppiece'+i+'-'+a);
	
	if(id) id.style.display = 'none';
	
	a += 1;
	if(a > 3) a = 0;
	
	angle[i] = a;
	
	id = document.getElementById('ppiece'+i+'-'+a);
	if(id) id.style.display = 'inline';	

}

function handleArrowKeys(evt) {
    evt = (evt) ? evt : ((window.event) ? event : null);
    if (evt) {
         switch (evt.keyCode) {
            case 37:
                //alert(1);
				if(mode == 1) CycleLeft();
				//if(mode == 2) document.getElementById('left_arrow').click();
                break;    
            case 38:
                 if(mode == 0) CursorUp();
				 if(mode == 1) RotateUp();
                break;    
            case 39:
                 if(mode == 1) CycleRight();
				 //if(mode == 2) document.getElementById('right_arrow').click();
                break;    
            case 40:
                if(mode == 0) CursorDown();
				if(mode == 1) RotateDown();
                break;    
			case 13:
                if(mode == 0) CursorEnter();
                break;
         }
    }
}

function LoadPuzzle(val) {
	var http;
	http = createRequestObject();
	http.open('get', 'action/1.php', false);
	ready = false;	
	http.send(null);
	
	document.getElementById('container').innerHTML = http.responseText;
	
	delete http;
	
	PlayPuzzle(val);
	
}

function PlayPuzzle(val) {
	var http;
	http = createRequestObject();
	http.open('get', 'action/2.php', false);
	ready = false;	
	http.send(null);
	document.getElementById('container').innerHTML = http.responseText;
	delete http;
	makeDraggable(document.getElementById('puzzle_active'));
	mode = 1;
}

function ListPuzzles(val) {
	var http;
	http = createRequestObject();
	http.open('get', 'action/screens.php', false);
	ready = false;	
	http.send(null);
	
	document.getElementById('container').innerHTML = http.responseText;
	
	delete http;
	LoadList();
	mode = 2;
}


document.onmousemove = mouseMove;
document.onmouseup   = mouseUp;

var dragObject  = null;
var mouseOffset = null;

function mouseCoords(ev){
	if(ev.pageX || ev.pageY){
		return {x:ev.pageX, y:ev.pageY};
	}
	return {
		x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
		y:ev.clientY + document.body.scrollTop  - document.body.clientTop
	};
}


function getMouseOffset(target, ev){
	ev = ev || window.event;

	var docPos    = getPosition(target);
	var mousePos  = mouseCoords(ev);
	return {x:mousePos.x - docPos.x, y:mousePos.y - docPos.y};
}

function getPosition(e){
	var left = 0;
	var top  = 0;

	while (e.offsetParent){
		left += e.offsetLeft;
		top  += e.offsetTop;
		e     = e.offsetParent;
	}

	left += e.offsetLeft;
	top  += e.offsetTop;

	return {x:left, y:top};
}

function mouseMove(ev){
	ev           = ev || window.event;
	var mousePos = mouseCoords(ev);

	if(dragObject){
		dragObject.style.position = 'absolute';
		dragObject.style.cursor = 'move';
		dragObject.style.top      = mousePos.y- mouseOffset.y-document.getElementById("container").offsetTop; // 240
		dragObject.style.left     = mousePos.x- mouseOffset.x-document.getElementById("container").offsetLeft; // 350

		return false;
	}
}
function mouseUp(ev){
	
	if(dragObject){
	Grid(ev);
	
	dragObject.style.top      = 380;
	dragObject.style.left     = 270; 
	dragObject.style.cursor = 'default';
	dragObject = null;
	} else {
		if(mode == 1) {
			
			Grid(ev);
		}
	}
	
}

function makeDraggable(item){
	//alert(item.id);
	if(!item) return;
	item.onmousedown = function(ev){
		dragObject  = this;
		mouseOffset = getMouseOffset(this, ev);
		return false;
	}
}

function Grid(ev) {
	if(pieces.length == 0 || mode == 0 || document.getElementById("picture").style.display == "inline") return;
	
	ev           = ev || window.event;
	var mousePos = mouseCoords(ev);
	var px = mousePos.x-document.getElementById("puzzle_image").offsetLeft-document.getElementById("container").offsetLeft;
	var py = mousePos.y-document.getElementById("puzzle_image").offsetTop-document.getElementById("container").offsetTop;
	
	if(px < 0 || px > 516 || py < 0 || py > 388) return 0;
	
	var c = parseInt(px/(516/5));
	var r = parseInt(py/(388/4));
	var i = pieces[index];
	
	if(angle[i]) 
		a = angle[i];
	else {
		a = 0;
		angle[i] = 0;
	}
	
	var http;
	http = createRequestObject();
	http.open('get', 'action/3.php?grid='+c+'-'+r+'&index='+i+'&angle='+a, false);
	ready = false;	
	http.send(null);
	
	var res=http.responseText.split("|");
	document.getElementById('remaining_text').innerHTML = res[1];
	
	if(res[0] == 1) {
		document.getElementById("solved"+i).src = document.getElementById('ppiece'+i+'-0').src;
		
		id = document.getElementById('ppiece'+i+'-'+a);
	
		if(id) id.style.display = 'none';
		
		pieces.splice(index, 1);
		
		if(pieces.length == 0) return;
		
		i = pieces[index];
		angle[i] = a;
	
		id = document.getElementById('ppiece'+i+'-'+a);
		if(id) 
			id.style.display = 'inline';	
		else
			CycleRight();
	} else {
		
		alert('no');
	}
	
	delete http;
	
	return c+'-'+r;
}

function TogglePicture() {
	if(pieces.length == 0 || mode == 0) return;
	
	if(document.getElementById("picture").style.display == "none") {
		document.getElementById("picture").style.display = "inline";
		document.getElementById("button_show").style.backgroundImage  = 'url(images/button_hide.png)';
	} else {
		document.getElementById("picture").style.display = "none";
		document.getElementById("button_show").style.backgroundImage  = 'url(images/button_show.png)';
	}
}

  
//document.onkeyup = handleArrowKeys;


</script>

<body onLoad="ListPuzzles(1);">
<div id="container">


</div>

</body>
</html>