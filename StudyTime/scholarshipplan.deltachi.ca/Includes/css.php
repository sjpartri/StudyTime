<style>
#overlay{
    position: fixed; 
    width: 100%; 
    height: 100%; 
    top: 0px; 
    left: 0px; 
    background-color: #000; 
    opacity: 0.7;
    filter: alpha(opacity = 70) !important;
    display: none;
    z-index: 100;
    
}

#overlayContent{
    position: fixed; 
    width: 100%;
    top: 100px;
    text-align: center;
    display: none;
    overflow: hidden;
    z-index: 100;
}

#contentGallery{
    margin: 0px auto;
}

#imgBig, .imgSmall{
    cursor: pointer;
}

body{
    background-color:   #330000;
}



.container theme-showcase {
    background-color: #000000;
}


strong {
	font-weight: bold; 
}

em {
	font-style: italic; 
}

.pointTable table {
	background: #f5f5f5;
	border-collapse: separate;
	box-shadow: inset 0 1px 0 #fff;
	font-size: 12px;
	line-height: 24px;
	margin: 30px auto;
	text-align: left;
	width: 800px;
}	

.pointTable th {
	background: url(http://jackrugile.com/images/misc/noise-diagonal.png), linear-gradient(#777, #444);
	border-left: 1px solid #555;
	border-right: 1px solid #777;
	border-top: 1px solid #555;
	border-bottom: 1px solid #333;
	box-shadow: inset 0 1px 0 #999;
	color: #fff;
        font-weight: bold;
	padding: 10px 15px;
	position: relative;
	text-shadow: 0 1px 0 #000;	
}

.pointTable th:after {
	background: linear-gradient(rgba(255,255,255,0), rgba(255,255,255,.08));
	content: '';
	display: block;
	height: 25%;
	left: 0;
	margin: 1px 0 0 0;
	position: absolute;
	top: 25%;
	width: 100%;
}

.pointTable th:first-child {
	border-left: 1px solid #777;	
	box-shadow: inset 1px 1px 0 #999;
}

.pointTable th:last-child {
	box-shadow: inset -1px 1px 0 #999;
}

.pointTable td {
	border-right: 1px solid #fff;
	border-left: 1px solid #e8e8e8;
	border-top: 1px solid #fff;
	border-bottom: 1px solid #e8e8e8;
	padding: 10px 15px;
	position: relative;
	transition: all 300ms;
}

.pointTable td:first-child {
	box-shadow: inset 1px 0 0 #fff;
}	

.pointTable td:last-child {
	border-right: 1px solid #e8e8e8;
	box-shadow: inset -1px 0 0 #fff;
}	

.pointTable tr {
	background: #E8E8E8    url(http://jackrugile.com/images/misc/noise-diagonal.png);	
}

.pointTable tr:nth-child(odd) td {
	background: #f1f1f1 url(http://jackrugile.com/images/misc/noise-diagonal.png);	
}

.pointTable tr:last-of-type td {
	box-shadow: inset 0 -1px 0 #fff; 
}

.pointTable tr:last-of-type td:first-child {
	box-shadow: inset 1px -1px 0 #fff;
}	

.pointTable tr:last-of-type td:last-child {
	box-shadow: inset -1px -1px 0 #fff;
}	

.pointTable tbody:hover td {
	color: transparent;
	text-shadow: 0 0 3px #aaa;
}

.pointTable tbody:hover tr:hover td {
	color: #444;
	text-shadow: 0 1px 0 #fff;
}
</style>
