<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title><?= $title ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />

<!-- BEGIN PLUGIN CSS -->
<link rel="shortcut icon" href="<?= base_url('assets/img/favicon.png') ?>" />
<link href="<?= base_url('assets/plugins/pace/pace-theme-flash.css') ?>" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?= base_url('assets/plugins/jquery-slider/css/jquery.sidr.light.css') ?>" rel="stylesheet" type="text/css" media="screen"/>
<!-- END PLUGIN CSS -->
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?= base_url('assets/plugins/bootstrap-3.3.5-dist/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url('assets/plugins/bootstrap-3.3.5-dist/css/bootstrap-theme.min.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url('assets/plugins/font-awesome-4.3.0/css/font-awesome.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url('assets/css/select2.css') ?>" rel="stylesheet" type="text/css"/>
<!-- END CORE CSS FRAMEWORK -->
<!-- BEGIN CSS TEMPLATE -->
<link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url('assets/css/responsive.css') ?>" rel="stylesheet" type="text/css"/>
<link href="<?= base_url('assets/css/custom-icon-set.css') ?>" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?= base_url('assets/css/select2.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/css/datepicker3.css') ?>" />
<link rel="stylesheet" href="<?= base_url('assets/css/pnotify.custom.min.css') ?>" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.treetable.css') ?>" media="all" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/jquery.treetable.theme.default.css') ?>" media="all" />
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/user-defined.css') ?>" media="all" />

<!-- END CSS TEMPLATE -->
<script src="<?= base_url('assets/plugins/jquery-1.8.3.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js') ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url('assets/js/jquery.cookies.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/select2.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/bootbox.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/library.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/jquery.blockUI.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/jquery.form.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/tinymce/tinymce.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/pnotify.custom.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/js/jquery.treetable.js') ?>"></script>
<script type="text/javascript">
            
    function ganti_pwd() {
        $('.logoutbutton').toggle();
        $.ajax({
            type : 'GET',
            url: '<?= base_url("referensi/ganti_password") ?>',
            cache: false,
            success: function(data) {
                $('#loaddata').html(data);
            }
        });
    }
    function load_menu(val){
        $.cookie('url', val);
        $.ajax({
            url: val,
            cache: false
        }).done(function( data ) {
            $('#loaddata').html(data);
        });
        return false;
    }
    $(function() {
        $(".page-content").css("min-height", function(){ 
            return $(this).height();
        });
        $('#loading_content').ajaxStart(function(){ $(this).fadeIn(); });
        $('#loading_content').ajaxComplete(function(){ $(this).fadeOut(); });

        $(".ui-icon-closethick").click(function(){
            $(".ui-effects-wrapper").hide();    
        });

        $('.home').click(function() {
            $.cookie('url',null);
        });
        if ($.cookie('url') !== null) {
            load_menu($.cookie('url'));
        }
        $('#loaddata').ajaxError(function(e, jqxhr, settings, exception) {
            var url = settings.url;
            var res = jqxhr.responseText;
            var status = jqxhr.statusText;
            var menu = $.cookie('url').trim();

            //alert_dinamic('Problem internal server, please contact your system administrator!');

//            $.ajax({
//                type : 'POST',
//                url: '<?= base_url('display/submit_error') ?>',               
//                data: "url="+url+"&response="+res+"&status="+status+"&menu="+menu,
//                cache: false,
//                success: function(data) {
//                   // location.reload();
//                }
//            }); 
        });
        $(document).ready(function(){

            $('#cssmenu > ul > li ul').each(function(index, e){
                var count = $(e).find('li').length;
                var content = '<span class="cnt">' + count + '</span>';
                $(e).closest('li').children('a').append(content);
            });
            $('#cssmenu ul ul li:odd').addClass('even');
            $('#cssmenu ul ul li:even').addClass('even');
            $('#cssmenu > ul > li > a').click(function() {

                $('#cssmenu li').removeClass('active');
                $(this).closest('li').addClass('active');	
                var checkElement = $(this).next();
                if((checkElement.is('ul')) && (checkElement.is(':visible'))) { // tutup
                    $(this).closest('li').removeClass('active');
                    checkElement.slideUp('normal');
                    //$.cookie('status', 'close');
                }
                if((checkElement.is('ul')) && (!checkElement.is(':visible'))) { // buka
                    $('#cssmenu ul ul:visible').slideUp('normal');
                    checkElement.slideDown('normal');
                    //$.cookie('status', 'open');
                    //alert('you');
                }
                if($(this).closest('li').find('ul').children().length === 0) {
                    return true;
                } else {
                    return false;	
                }		
            });

        });


        $('.fixed').fadeOut(15000);
        $('#hide').click(function() {
            $('#hide').hide();
            $('#show').show();
            $(".menu-detail").hide("slide", { direction: "left" }, 500);
            $('#loaddata').css('width','100%');
        });
        $('#show').click(function() {
            $('#show').hide();
            $('#hide').show();
            $(".menu-detail").show("slide", { direction: "left" }, 500);
            $('#loaddata').css('width','80%');
        });
    });

</script>
</head>
<!-- BEGIN BODY -->
<body class="">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse "> 
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
	<div class="header-seperation"> 
		<ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">	
		 <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" > <div class="iconset top-menu-toggle-white"></div> </a> </li>		 
		</ul>
      <!-- BEGIN LOGO -->	
      <div class="logo">KOPERASI <span>MICRO FINANCE</span></div>
      <!-- END LOGO --> 
      <ul class="nav pull-right notifcation-center">	
          <li class="dropdown" id="header_task_bar"> <a href="<?= base_url('user') ?>" class="dropdown-toggle active" data-toggle=""> <div class="iconset top-home"></div> </a> </li>
        <!--<li class="dropdown" id="header_inbox_bar" > <a href="email.html" class="dropdown-toggle" > <div class="iconset top-messages"></div>  <span class="badge" id="msgs-badge">2</span> </a></li>-->
		<li class="dropdown" id="portrait-chat-toggler" style="display:none"> <a href="#sidr" class="chat-menu-toggle"> <div class="iconset top-chat-white "></div> </a> </li>        
      </ul>
      </div>
      <!-- END RESPONSIVE MENU TOGGLER --> 
      <div class="header-quick-nav" > 
      <!-- BEGIN TOP NAVIGATION MENU -->
	  <div class="pull-left"> 
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle" >
            <div class="iconset top-menu-toggle-dark"></div>
            </a> </li>
        </ul>
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="#" class="" >
            <div class="iconset top-reload"></div>
            </a> </li>
          <li class="quicklinks"> <span class="h-seperate"></span></li>
          <li class="quicklinks"> <a href="#" class="" >
            <div class="iconset top-tiles"></div>
            </a> </li>
			<li class="m-r-10 input-prepend inside search-form no-boarder">
				<span class="add-on"> <span class="iconset top-search"></span></span>
				 <input name="" type="text"  class="no-boarder " placeholder="Search Dashboard" style="width:250px;">
			</li>
		  </ul>
	  </div>
	 <!-- END TOP NAVIGATION MENU -->
	 <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right"> 
		<div class="chat-toggler">	
				<a href="#" class="dropdown-toggle" id="my-task-list" data-placement="bottom"  data-content='' data-toggle="dropdown" data-original-title="Notifications">
					<div class="user-details"> 
						<div class="username">
							<span class="badge badge-important">3</span> 
							<?= $first_name ?> <span class="bold"><?= $last_name ?></span>									
						</div>						
					</div> 
					<div class="iconset top-down-arrow"></div>
				</a>	
				<div id="notification-list" style="display:none">
					<div style="width:300px">
						  <div class="notification-messages info">
									<div class="user-profile">
										<img src="assets/img/profiles/d.jpg"  alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
									</div>
									<div class="message-wrapper">
										<div class="heading">
											David Nester - Commented on your wall
										</div>
										<div class="description">
											Meeting postponed to tomorrow
										</div>
										<div class="date pull-left">
										A min ago
										</div>										
									</div>
									<div class="clearfix"></div>									
								</div>	
							<div class="notification-messages danger">
								<div class="iconholder">
									<i class="icon-warning-sign"></i>
								</div>
								<div class="message-wrapper">
									<div class="heading">
										Server load limited
									</div>
									<div class="description">
										Database server has reached its daily capicity
									</div>
									<div class="date pull-left">
									2 mins ago
									</div>
								</div>
								<div class="clearfix"></div>
							</div>	
							<div class="notification-messages success">
								<div class="user-profile">
									<img src="assets/img/profiles/h.jpg"  alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35">
								</div>
								<div class="message-wrapper">
									<div class="heading">
										You haveve got 150 messages
									</div>
									<div class="description">
										150 newly unread messages in your inbox
									</div>
									<div class="date pull-left">
									An hour ago
									</div>									
								</div>
								<div class="clearfix"></div>
							</div>							
						</div>				
				</div>
				<div class="profile-pic"> 
					<img src="<?= base_url('assets/img/profiles/logo.png') ?>"  alt="" data-src="<?= base_url('assets/img/profiles/logo.png') ?>" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35" /> 
				</div>       			
			</div>
		 <ul class="nav quick-section ">
			<li class="quicklinks"> 
				<a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">						
					<div class="iconset top-settings-dark "></div> 	
				</a>
				<ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
<!--                  <li><a href="user-profile.html"> My Account</a>
                  </li>
                  <li><a href="calender.html">My Calendar</a>
                  </li>
                  <li><a href="email.html"> My Inbox&nbsp;&nbsp;<span class="badge badge-important animated bounceIn">2</span></a>
                  </li>-->
                  <li class="divider"></li>                
                  <li><a href="<?= base_url('user/logout') ?>"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
               </ul>
			</li> 
<!--			<li class="quicklinks"> <span class="h-seperate"></span></li> 
			<li class="quicklinks"> 	
			<a id="chat-menu-toggle" href="#sidr" class="chat-menu-toggle" ><div class="iconset top-chat-dark "><span class="badge badge-important hide" id="chat-message-count">1</span></div>
			</a> 
				<div class="simple-chat-popup chat-menu-toggle hide" >
					<div class="simple-chat-popup-arrow"></div><div class="simple-chat-popup-inner">
						 <div style="width:100px">
						 <div class="semi-bold">David Nester</div>
						 <div class="message">Hey you there </div>
						</div>
					</div>
				</div>
			</li> -->
		</ul>
      </div>
	   <!-- END CHAT TOGGLER -->
      </div> 
      <!-- END TOP NAVIGATION MENU --> 
   
  </div>
  <!-- END TOP NAVIGATION BAR --> 
</div>

<!-- END HEADER --> 
<!-- BEGIN CONTAINER -->
<div class="page-container row"> 
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar" id="main-menu"> 
  <!-- BEGIN MINI-PROFILE -->
   <div class="user-info-wrapper">	
	<div class="profile-wrapper">
            <img src="<?= base_url('assets/img/profiles/logo.png') ?>"  alt="" data-src="<?= base_url('assets/img/profiles/logo.png') ?>" width="50" height="50" />
	</div>
    <div class="user-info">
      <div class="greeting">Welcome</div>
      <div class="username"><?= $this->session->userdata('nama') ?></div>
      <div class="status">Status<a href="#"><div class="status-icon green"></div>Online</a></div>
    </div>
   </div>
  <!-- END MINI-PROFILE -->
   
   <!-- BEGIN SIDEBAR MENU -->	
    <p class="menu-title">BROWSE <span class="pull-right"><a href="javascript:;"><i class="fa fa-refresh"></i></a></span></p>
    <ul>	
        
        <li class="start active "> <a class="home" href="<?= base_url('user') ?>"> <i class="icon-custom-home"></i> <span class="title">Dashboard<br/><div>Beranda sistem informasi</div></span> <span class="selected"></span></a> </li>
        <?php foreach ($master_menu as $data) { ?>
        <li class=" "> <a href="javascript:;;"> <i class="<?= $data->icon ?>"></i> <span class="title"><b><?= $data->nama ?></b> <span class="arrow "></span><div><?= $data->keterangan ?></div></span> <span class="selected"></span> </a> 
            <ul class="sub-menu">
                <?php foreach ($data->detail_menu as $data2) { ?>
                <li><a onclick="load_menu('<?= base_url($data2->url) ?>'); return false;" href="<?= base_url('') ?>"> <?= $data2->form_nama ?> </a> </li>
                <?php } ?>
            </ul>
        </li>
        <?php } ?>
    </ul>
    <br/><br/><br/>
	
	<a href="#" class="scrollup">Scroll</a>
	<div class="clearfix"></div>
    <!-- END SIDEBAR MENU --> 
  </div>
  <div class="footer-widget">
    <div class="progress transparent progress-small no-radius no-margin">
      <div class="progress-bar progress-bar-success animate-progress-bar" data-percentage="79%" style="width: 79%;"></div>
    </div>
    <div class="pull-right">
      <div class="details-status"> <span class="animate-number" data-value="86" data-animation-duration="560">86</span>% </div>
      <a href="lockscreen.html"><i class="fa fa-power-off"></i></a></div>
  </div>
  <!-- END SIDEBAR -->
  <!-- BEGIN PAGE CONTAINER-->
  <div class="page-content">
    <!-- BEGIN MODEL-->
    <div id="portlet-config" class="modal hide">
      <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button"></button>
        <h3>Widget Settings</h3>
      </div>
      <div class="modal-body"> Widget settings form goes here </div>
    </div>
    <!-- END MODEL-->
    <div class="clearfix"></div>
    <div id="loaddata">
        
    </div>
    
  </div>
</div>
<!-- BEGIN CHAT --> 
<div id="sidr" class="chat-window-wrapper">
	<div id="main-chat-wrapper" >
	<div class="chat-window-wrapper fadeIn" id="chat-users" >
		<div class="chat-header">	
		<div class="pull-left">
			<input type="text" placeholder="search">
		</div>		
			<div class="pull-right">
				<a href="#" class="" ><div class="iconset top-settings-dark "></div> </a>
			</div>			
		</div>	
		<div class="side-widget">
		   <div class="side-widget-title">group chats</div>
		    <div class="side-widget-content">
			 <div id="groups-list">
				<ul class="groups" >
					<li><a href="#"><div class="status-icon green"></div>Office work</a></li>
					<li><a href="#"><div class="status-icon green"></div>Personal vibes</a></li>
				</ul>
			</div>
			</div>
		</div>
		<div class="side-widget fadeIn">
		   <div class="side-widget-title">favourites</div>
		   <div id="favourites-list">
		    <div class="side-widget-content" >
				<div class="user-details-wrapper active" data-chat-status="online" data-chat-user-pic="assets/img/profiles/d.jpg" data-chat-user-pic-retina="assets/img/profiles/d2x.jpg" data-user-name="Jane Smith">
					<div class="user-profile">
						<img src="assets/img/profiles/d.jpg"  alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
					</div>
					<div class="user-details">
						<div class="user-name">
						Jane Smith
						</div>
						<div class="user-more">
						Hello you there?
						</div>
					</div>
					<div class="user-details-status-wrapper">
						<span class="badge badge-important">3</span>
					</div>
					<div class="user-details-count-wrapper">
						<div class="status-icon green"></div>
					</div>
					<div class="clearfix"></div>
				</div>	
				<div class="user-details-wrapper" data-chat-status="busy" data-chat-user-pic="assets/img/profiles/d.jpg" data-chat-user-pic-retina="assets/img/profiles/d2x.jpg" data-user-name="David Nester">
					<div class="user-profile">
						<img src="assets/img/profiles/c.jpg"  alt="" data-src="assets/img/profiles/c.jpg" data-src-retina="assets/img/profiles/c2x.jpg" width="35" height="35">
					</div>
					<div class="user-details">
						<div class="user-name">
						David Nester
						</div>
						<div class="user-more">
						Busy, Do not disturb
						</div>
					</div>
					<div class="user-details-status-wrapper">
						<div class="clearfix"></div>
					</div>
					<div class="user-details-count-wrapper">
						<div class="status-icon red"></div>
					</div>
					<div class="clearfix"></div>
				</div>					
			</div>
			</div>
		</div>
		<div class="side-widget">
		   <div class="side-widget-title">more friends</div>
			 <div class="side-widget-content" id="friends-list">
				<div class="user-details-wrapper" data-chat-status="online" data-chat-user-pic="assets/img/profiles/d.jpg" data-chat-user-pic-retina="assets/img/profiles/d2x.jpg" data-user-name="Jane Smith">
					<div class="user-profile">
						<img src="assets/img/profiles/d.jpg"  alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
					</div>
					<div class="user-details">
						<div class="user-name">
						Jane Smith
						</div>
						<div class="user-more">
						Hello you there?
						</div>
					</div>
					<div class="user-details-status-wrapper">
						
					</div>
					<div class="user-details-count-wrapper">
						<div class="status-icon green"></div>
					</div>
					<div class="clearfix"></div>
				</div>	
				<div class="user-details-wrapper" data-chat-status="busy" data-chat-user-pic="assets/img/profiles/d.jpg" data-chat-user-pic-retina="assets/img/profiles/d2x.jpg" data-user-name="David Nester">
					<div class="user-profile">
						<img src="assets/img/profiles/h.jpg"  alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35">
					</div>
					<div class="user-details">
						<div class="user-name">
						David Nester
						</div>
						<div class="user-more">
						Busy, Do not disturb
						</div>
					</div>
					<div class="user-details-status-wrapper">
						<div class="clearfix"></div>
					</div>
					<div class="user-details-count-wrapper">
						<div class="status-icon red"></div>
					</div>
					<div class="clearfix"></div>
				</div>		
				<div class="user-details-wrapper" data-chat-status="online" data-chat-user-pic="assets/img/profiles/d.jpg" data-chat-user-pic-retina="assets/img/profiles/d2x.jpg" data-user-name="Jane Smith">
					<div class="user-profile">
						<img src="assets/img/profiles/c.jpg"  alt="" data-src="assets/img/profiles/c.jpg" data-src-retina="assets/img/profiles/c2x.jpg" width="35" height="35">
					</div>
					<div class="user-details">
						<div class="user-name">
						Jane Smith
						</div>
						<div class="user-more">
						Hello you there?
						</div>
					</div>
					<div class="user-details-status-wrapper">
						
					</div>
					<div class="user-details-count-wrapper">
						<div class="status-icon green"></div>
					</div>
					<div class="clearfix"></div>
				</div>	
				<div class="user-details-wrapper" data-chat-status="busy" data-chat-user-pic="assets/img/profiles/d.jpg" data-chat-user-pic-retina="assets/img/profiles/d2x.jpg" data-user-name="David Nester">
					<div class="user-profile">
						<img src="assets/img/profiles/h.jpg"  alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35">
					</div>
					<div class="user-details">
						<div class="user-name">
						David Nester
						</div>
						<div class="user-more">
						Busy, Do not disturb
						</div>
					</div>
					<div class="user-details-status-wrapper">
						<div class="clearfix"></div>
					</div>
					<div class="user-details-count-wrapper">
						<div class="status-icon red"></div>
					</div>
					<div class="clearfix"></div>
				</div>				
			</div>		
		</div>
	</div>

	<div class="chat-window-wrapper fadeIn" id="messages-wrapper" style="display:none">
	<div class="chat-header">	
		<div class="pull-left">
			<input type="text" placeholder="search">
		</div>		
			<div class="pull-right">
				<a href="#" class="" ><div class="iconset top-settings-dark "></div> </a>
			</div>					
		</div>
	<div class="clearfix"></div>	
	<div class="chat-messages-header">
	<div class="status online"></div><span class="semi-bold">Jane Smith(Typing..)</span>
	<a href="#" class="chat-back"><i class="icon-custom-cross"></i></a>
	</div>
	<div class="chat-messages">
		<div class="sent_time">Yesterday 11:25pm</div>
		<div class="user-details-wrapper " >
			<div class="user-profile">
				<img src="assets/img/profiles/d.jpg"  alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
			</div>
			<div class="user-details">
			  <div class="bubble">	
					Hello, You there?
			   </div>
			</div>					
			<div class="clearfix"></div>
		   <div class="sent_time off">Yesterday 11:25pm</div>
		</div>		
		<div class="user-details-wrapper ">
			<div class="user-profile">
				<img src="assets/img/profiles/d.jpg"  alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
			</div>
			<div class="user-details">
			  <div class="bubble">	
					How was the meeting?
			   </div>
			</div>					
			<div class="clearfix"></div>
			<div class="sent_time off">Yesterday 11:25pm</div>
		</div>
		<div class="user-details-wrapper ">
			<div class="user-profile">
				<img src="assets/img/profiles/d.jpg"  alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
			</div>
			<div class="user-details">
			  <div class="bubble">	
					Let me know when you free
			   </div>
			</div>					
			<div class="clearfix"></div>
			<div class="sent_time off">Yesterday 11:25pm</div>
		</div>
		<div class="sent_time ">Today 11:25pm</div>
		<div class="user-details-wrapper pull-right">
			<div class="user-details">
			  <div class="bubble sender">	
					Let me know when you free
			   </div>
			</div>					
			<div class="clearfix"></div>
			<div class="sent_time off">Sent On Tue, 2:45pm</div>
		</div>		
	</div>
	</div>
	<div class="chat-input-wrapper" style="display:none">
		<textarea id="chat-message-input" rows="1" placeholder="Type your message"></textarea>
	</div>
	<div class="clearfix"></div>
	</div>
</div>
<!-- END CHAT --> 
<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<script src="<?= base_url('assets/plugins/boostrapv3/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/breakpoints.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery-unveil/jquery.unveil.min.js') ?>" type="text/javascript"></script>
<!-- END CORE JS FRAMEWORK -->
<!-- BEGIN PAGE LEVEL JS -->
<script src="<?= base_url('assets/plugins/pace/pace.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery-block-ui/jqueryblockui.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery-slider/jquery.sidr.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') ?>" type="text/javascript"></script> 
<!-- END PAGE LEVEL PLUGINS -->
<!-- PAGE JS -->
<script src="<?= base_url('assets/js/messages_notifications.js') ?>" type="text/javascript"></script>
<!-- BEGIN CORE TEMPLATE JS -->
<script src="<?= base_url('assets/js/core.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/chat.js') ?>" type="text/javascript"></script> 
<script src="<?= base_url('assets/js/demo.js') ?>" type="text/javascript"></script>
<!-- END CORE TEMPLATE JS -->
</body>
</html>