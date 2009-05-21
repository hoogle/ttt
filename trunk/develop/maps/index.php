<?
  session_start();
  require_once LIBRARY_PATH . "function.php";
  if (!isset($_SESSION['userid']))
  {
    header("location:/login/?go_url=".$_SERVER['REQUEST_URI']);
    exit;
  }
  $userid = $_SESSION['userid'];
  $func = $_GET['f'];
  switch ($func)
  {
    case 'hot': $inc_file = WEBROOT_PATH . "maps/mod_hot.php";
                $selected_str4 = ' class="selected"';
                $tab = 4;
                break;
    case 'new': $inc_file = WEBROOT_PATH . "maps/mod_new.php";
                $selected_str3 = ' class="selected"';
                $tab = 3;
                break;
    case 'my':  $inc_file = WEBROOT_PATH . "maps/mod_my.php";
                $selected_str2 = ' class="selected"';
                $tab = 2;
                break;
    case 'all':
    default:    $inc_file = WEBROOT_PATH . "maps/mod_all.php";
                $selected_str1 = ' class="selected"';
                $tab = 1;
                break;
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>TravelMap</title>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.0.0pr2/build/cssreset/reset-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.0.0pr2/build/cssfonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.0.0pr2/build/cssgrids/grids.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/tabview/assets/skins/sam/tabview.css" />
<link rel="stylesheet" type="text/css" href="/photos/css/layout.css" />
<style type="text/css">
#msgArea {
  margin:10px 0;
  padding:5px;
  width:900px;
  height:20px;
  border:1px solid gray;
  background-color:#FFFFEE;
}
#msg {
  float:left;
}
#cordination {
  float:right;
}
#map-tab-all .yui-content .tabs {
  height:292px;
}
#nearby {
  width:auto;
  *width:273px;
  font-size:12px;
  overflow:auto;
}
#nearby .nearby-list {
  margin:0 3px 3px 0;
  background-color:#eee;
  border:1px solid #ddd;
}
#nearby .nearby-list div {
  text-align:left;
}
#nearby img {
  border:1px solid #CCCCCC;
  padding:2px;
}
#nearby .n_title {
  margin-bottom:5px;
  font-size:12pt;
}
#nearby .n_desc {
  font-size:13px;
}
.yui-nav {
  text-align:left;
}
.showWindow {
  width:300px;
  height:135px;
  padding:15px;
  overflow:hidden;
}
.windowCaption {
  font-weight:bold;
  margin-bottom:3px;
}
.windowTitle {
  color:Darkgreen;
  font-weight:bold;
}
.windowPic {
  margin:5px;
  border:1px solid gray;
}
.windowContent {
  border:1px solid #99CCFF;
  background-color:#DDEEFF;
  color:#6699CC;
  padding:5px;
  width:98%;
  overflow:hidden;
}
.pushpin {
  border:none;
}
.menuitem {
  text-align:left;
  padding:3px;
  margin:0;
  display:block;
  color:#0000ff;
  cursor:pointer;
  font-size:9pt;
}
.menuitem:hover {
  background-color:#DDEEFF;
}
img {
  border:1px solid white;
}
.myicon {
  text-align:center;
}
.myicon img {
  margin-top:10px;
  padding:1px;
  border:1px solid gray;
}
.yui-content .tabs {
  text-align:left;
}
</style> 
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAACgMwIzz1hxRWf8JW8JfV_xSm_RB7Ggyimh49Ou8AB6bIEyBpGxR8tL4tZRT4WG6q1H-qkZUKQKQ9qg" type="text/javascript"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="/photos/js/glayer.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/combo?2.7.0/build/yahoo-dom-event/yahoo-dom-event.js&2.7.0/build/element/element-min.js&2.7.0/build/tabview/tabview-min.js"></script>
<script type="text/javascript">
  $j = jQuery.noConflict();
  var resizemap = function() {
    var map_width = parseInt($j(window).width()) - 335;
    var map_height = parseInt($j(window).height()) - 135;
    var nearby_height = parseInt($j(window).height()) - 455;
    $j('.yui-main .yui-b').css('width', map_width+'px');
    //$j('.yui-main .yui-b').css('height', map_height+'px');
    $j('#nearby').css('height', nearby_height+'px');
  };
  var resizeTimer = null;
  $j(window).bind('resize', resizemap); 
  jsvar = {
    userid: '<?=$userid?>'
  };
  $j(document).ready(function () {
    //var nearby_height = parseInt($j(window).height()) - 390;
    //$j('#nearby').css('height', nearby_height+'px');
    //$j('#nearby').css('height', '287px');
    var map_height = parseInt($j(window).height()) - 150;
    //$j('.yui-main .yui-b').css('height', map_height+'px');
  });
</script> 
</head> 
<body class="yui-skin-sam" onLoad="initialize()" onunload="GUnload()">
<!--body class="yui-skin-sam"-->
<div id="custom-doc" class="yui-t3" style="width:auto">
  <div id="hd">
<? require WEBROOT_PATH . "include/header.php"; ?>
  </div>

  <div id="bd">
    <div id="map-tab" class="yui-navset">
      <ul class="yui-nav">
        <li class="selected"><a href="#maptab1"><em>大家的旅行地圖</em></a></li>
        <li><a href="#maptab2"><em>我的旅行地圖</em></a></li>
        <li><a href="#maptab3"><em>最新旅行地圖</em></a></li>
        <li><a href="#maptab4"><em>熱門旅行地圖</em></a></li>
      </ul>
      <div class="yui-content">
        <div id="maptab<?=$tab?>" class="clearfix">
          <? require $inc_file; ?>
        </div>
      </div>
    </div>
<script>
(function() { var tabView = new YAHOO.widget.TabView('map-tab'); })();
</script>
  </div>

  <div id="ft">
<? require WEBROOT_PATH."include/footer.php"; ?>
  </div>
</div>
</body> 
</html>
<script type="text/javascript" src="/photos/js/mymap.js"></script>
