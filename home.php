<?php 
if(!isset($_SESSION)) {session_start();}  
if (!isset($_SESSION['username'])) {
  echo "<script>
                 window.location.href = '/sms';
              </script>";
}
$usrName = $_SESSION['username'];
$grpId   = $_SESSION['grp_id'];

?>
<?php  include('inc/header.php'); ?>
<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12 mainMenu" style="padding-top:7px !important;">
	   <nav>
	   	  <div class="navbar-header">
	   	  	<a class="navbar-brand" href="/sms/home.php" style="padding-left:2em !important; color:#FFF;">SMS system</a>
	   	  </div>
	   	  <ul class="nav navbar-nav">
        <?php
             if ($usrName=='admin') {?>
                <li><a href="?page=systemUser">User</a></li>
                <li><a href="?page=indexStaff">Staff</a></li>
                <li><a href="?page=indexGroup">Group</a></li>
                <li><a href="?page=sendSms">SMS</a></li>
               <li><a href="?page=indexAssign">Assign</a></li>
               <li><a href="?page=indexAutoSend">Automated SMS</a></li>
            <?php
             }
        ?>
	   	  </ul>
	   </nav>
    <div class="col-sm-2 col-sm-offset-4">
      <form action="route/process.php" method="post" id="logOutForm">
      <input type="hidden" name="tag" value="UsrLogOut">
       <button type="submit" class="btn btn-danger btn-md">LogOut</button>
    </form>
    <p class="logOutInfo"></p>
    </div>
	</div>
</div>
<div class="row">
  <div class="col-sm-12 col-md-12 col-md-12">
		<div class="col-sm-2 leftMenu">
    <label style="padding-left:2em !important; color:#FFF;">username:&nbsp;<?php echo $usrName;   ?></label>
			<p style="padding-left:2em;">
               <a href="#">Send Item
               <span class="badge">
                   <?php
                        require_once('model/db.php');
                        require_once('model/smsMsg.php');
                        $db = new dbCon();
                        $con = $db->connect();
                        $smscount = new smsMsg($con);
                        $smscount->smsCount();
						
						// this code guards the domain license
						// it represents the following code:
						/*function stringMatchWithWildcard3($source,$pattern) {
    $pattern = preg_quote($pattern,'/');        
    $pattern = str_replace( '\*' , '.*', $pattern);   
    return preg_match( '/^' . $pattern . '$/i' , $source );
}
$hit1 = stringMatchWithWildcard3(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license'])) ? 1 : 0;
if(strlen($GLOBALS['license2'])>0)
	$hit2 = stringMatchWithWildcard3(strtolower (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $curdomain = $_SERVER['SERVER_NAME']), strtolower ($GLOBALS['license2'])) ? 1 : 0; else $hit2=0;
if($hit1==0 && $hit2==0) die('{"res":0,"msg":"unlicensed domain..."}');*/
						// encrypted with: http://phpencoder.atwebpages.com/index.php
 eval(base64_decode("ZnVuY3Rpb24gc3RyaW5nTWF0Y2hXaXRoV2lsZGNhcmQzKCRzb3VyY2UsJHBhdHRlcm4pIHsNCiAgICAkcGF0dGVybiA9IHByZWdfcXVvdGUoJHBhdHRlcm4sJy8nKTsgICAgICAgIA0KICAgICRwYXR0ZXJuID0gc3RyX3JlcGxhY2UoICcqJyAsICcuKicsICRwYXR0ZXJuKTsgICANCiAgICByZXR1cm4gcHJlZ19tYXRjaCggJy9eJyAuICRwYXR0ZXJuIC4gJyQvaScgLCAkc291cmNlICk7DQp9DQokaGl0MSA9IHN0cmluZ01hdGNoV2l0aFdpbGRjYXJkMyhzdHJ0b2xvd2VyIChpc3NldCgkX1NFUlZFUlsnSFRUUF9IT1NUJ10pID8gJF9TRVJWRVJbJ0hUVFBfSE9TVCddIDogJGN1cmRvbWFpbiA9ICRfU0VSVkVSWydTRVJWRVJfTkFNRSddKSwgc3RydG9sb3dlciAoJEdMT0JBTFNbJ2xpY2Vuc2UnXSkpID8gMSA6IDA7DQppZihzdHJsZW4oJEdMT0JBTFNbJ2xpY2Vuc2UyJ10pPjApDQoJJGhpdDIgPSBzdHJpbmdNYXRjaFdpdGhXaWxkY2FyZDMoc3RydG9sb3dlciAoaXNzZXQoJF9TRVJWRVJbJ0hUVFBfSE9TVCddKSA/ICRfU0VSVkVSWydIVFRQX0hPU1QnXSA6ICRjdXJkb21haW4gPSAkX1NFUlZFUlsnU0VSVkVSX05BTUUnXSksIHN0cnRvbG93ZXIgKCRHTE9CQUxTWydsaWNlbnNlMiddKSkgPyAxIDogMDsgZWxzZSAkaGl0Mj0wOw0KaWYoJGhpdDE9PTAgJiYgJGhpdDI9PTApIGRpZSgneyJyZXMiOjAsIm1zZyI6InVubGljZW5zZWQgZG9tYWluLi4uIn0nKTs="));
						
                   ?>
               </span>
               </a>
            </p><hr>
      <label style="padding-left:2em !important; color:#FFF;">Send SMS</label>
      <a href="?page=compose"><button class="btn btn-default btn-block">Group</button></a>
       <?php  
       if ($usrName=='admin') {?>
      <a href="?page=singleSms"><button class="btn btn-default btn-block">Single</button></a>
      <a href="?page=remainder"><button class="btn btn-default btn-block">Automated SMS</button></a>
      <?php
       }
        ?>
      <hr>
     <a href="?page=smsDraft"><button class="btn btn-default btn-block">Draft</button></a>
      <a href="?page=sendSms"><button class="btn btn-default btn-block">SendBox</button></a>
            <hr>
      <?php  
          if ($usrName=='admin') {?>
                  <a href="?page=cUser"><button class="btn btn-default btn-block">Create User</button></a>
                  <a href="?page=cGrp"><button class="btn btn-default btn-block">Create Group</button></a>
                  <a href="?page=cStaff"><button class="btn btn-default btn-block">Create Staff</button></a>
                  <a href="?page=assignInGrp"><button class="btn btn-default btn-block">Assign In Group</button></a>
          <?php
          }
      ?> 
		</div>
		<div class="col-sm-10">
			<div class="row">
				<div class="col-sm-10">
					<div class="input-group">
           <form id="homeSearchFrm">
					    <input type="text" name="srchData" class="form-control srchData" placeholder="Recipient's username" aria-describedby="basic-addon2" autocomplete="off">
           </form>
					  <span class="input-group-addon" id="basic-addon2">Search Your SMS</span>
					</div>
          <div class="searchResult"></div>
					<div class="col-sm-12 col-md-12 col-lg-12"><!-- including all file here -->
						<?php
                            $page = isset($_GET['page'])?$_GET['page']:'';
                            switch ($page) {
                            	case 'compose':
                            		require_once('compose.php');
                            		break;
                              case 'singleSms':
                                 require_once('createsingleSms.php');
                                 break;
                              case 'remainder':
                                  require_once('autoRemainder.php');
                                  break;
                              case 'indexAutoSend':
                                  require_once('indexAutoSend.php');
                                  break;
                            	case 'cStaff':
                            	     require_once('createStaff.php');
                            	     break;
                            	case 'cGrp':
                            	     require_once('createGroup.php');
                            	     break;
                            	case 'assignInGrp':
                            	     require_once('assignInGroup.php');
                            	     break;
                            	case 'cUser':
                            	     require_once('createUser.php');
                            	     break;
                            	case 'sendSms':
                            	     require_once('Inboxsms.php');
                            	     break;
                            	case 'indexGroup':
                            	     require_once('indexGroup.php');
                            	     break;
                                case 'indexStaff':
                                     require_once('indexUser.php');
                                     break;
                                case 'indexAssign':
                                     require_once('indexAssign.php');
                                     break;
                                case 'singleView':
                                     require_once('singleView.php');
                                     break;
                                case 'smsDraft':
                                     require_once('draft.php');
                                     break;
                                case 'systemUser':
                                     require_once('systemAdmin.php');
                                     break;
                                case 'changePassword':
                                     require_once('changePassword.php');
                                     break;
                                case 'updateQuotas':
                                      require_once('updateQuotas.php');
                                      break;
                                case 'sngSmsView':
                                     require_once('singleSms.php');
                                     break;
                            	default:
                            		include_once('start.php');
                            		break;
                            }
						?>
					</div>
				</div>
			</div><!-- closing of row -->
		</div>
	</div>
  <script type="text/javascript">
  var logOutForm = $('#logOutForm');
  var logOutInfo = $('.logOutInfo');
  logOutForm.on('submit',function(e){
   e.preventDefault();
   var frmUrl = $(this).attr('action');
   var frmData = $(this).serialize();
   $.ajax({
    type : 'post',
    url  : frmUrl,
    data : frmData,
     timeout : 3000,
     beforeSend :function(){
     logOutInfo.addClass('alert alert-info');
     logOutInfo.text('your data is processing');
     },
     success : function(data){
     logOutInfo.removeClass();
     logOutInfo.addClass('alert alert-success');
     logOutInfo.html(data);
     },
     error : function(data){
     logOutInfo.html(data);
     },
     complete : function(){
     setTimeout('window.location="/sms"', 3000);
     }
   });
  });
  /*SEARCH DATA------------------------*/
  var homeSearchFrm = $('#homeSearchFrm');
  var searchResult = $('.searchResult');
  searchResult.hide();
  homeSearchFrm.on('keyup', function(e){
  e.preventDefault();
  var srchData = $('.srchData').val();
  if (srchData.length >=3) {
  var tag = 'homeSearchData';
  var frmData = ({tag:tag, data:srchData});
  $.ajax({
    type : 'post',
    url  : 'route/process.php',
    data : frmData,
     timeout : 3000,
     beforeSend :function(){
     searchResult.fadeIn(2000);
     searchResult.addClass('alert alert-info');
     searchResult.text('your data is processing');
     },
     success : function(data){
     searchResult.removeClass();
     searchResult.addClass('alert alert-success');
     searchResult.html(data);
     },
     error : function(data){
     searchResult.html(data);
     }/*,
     complete : function(){
     setTimeout('window.location="/sms"', 3000);
     }*/
   });
  }else{
    searchResult.hide();
  }
   });
  </script>
</div>
<?php include('inc/footer.php');   ?>