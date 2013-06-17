var jsonStep = 1,
	jsonKey = '',
	jsonValue = '',
	visor = 0,
	fbToken = '',
	fbId = '',
	thisDomain = window.location.href,
	thisPic = '';
	cdomain = 'http://dannegm.pro/picboard/',
	uploadingStatus = 0;

function toDay () {
	var date = new Date(),
		dia = date.getDate(),
		mes = date.getMonth(),
		year = date.getFullYear(),
		diasem = date.getDay();
	return diasem + ' ' + dia + '-' + mes + '-' + year + ' ';
}

function login (){
	function doLogin (){
		FB.api('/me', function(user){
			var fbAuth = FB.getAuthResponse(),
				fbGraph = 'https://graph.facebook.com/' + fbId + '?access_token=' + fbToken;
				fbId = fbAuth.userID;
				fbToken = fbAuth.accessToken;

				if ( thisDomain.match(/viewer/i) ){
					window.fbComments.location.reload();
				}
			
				$.post('apps/login.php', 
					{ 'fbId': fbId },
					function(res){
						var user = $.parseJSON(res);
						$('#uPicture').attr('src', 'http://graph.facebook.com/' + user.fbId + '/picture?type=large');
						$('#uName').text(user.name);
						$('#uCount').text(user.count);
						$('#uGoToProfile').attr('rel', user.fbId);

						$('#noLogin').fadeOut();
						$('body').removeClass('noSidebar');
					}
				);
			
		});
	}
	function chkLogin (resp) {
		if (resp.authResponse) {
			doLogin();
		}else{
			$("#intoFace").live('click', function(e) {
				e.preventDefault();
				FB.login(function(resp) {
					if (resp.authResponse) {
						doLogin();
					}
				}, {scope: 'email, publish_actions'});
			});
		}
	}
	window.fbAsyncInit = function() {
		FB.init({
	    	appId      : '152670424917089',
	    	status     : true,
	    	cookie     : true,
	    	xfbml      : true
		});
		FB.getLoginStatus( chkLogin );
	};
}

function drag_drop () {
	var holder = document.getElementById('dropbox');

	holder.ondragover = function () { 
		this.className = 'hover';
		return false;
	};
	holder.ondragend = function () {
		this.className = '';
		return false;
	};
	holder.ondrop = function (e) {
		e.preventDefault();
		this.className = '';
		console.log(e);

		buildUpload(e.dataTransfer.files);
	};
}
function btn_upload () {
	$('#btn_upload').live('click', function(e){
		e.preventDefault();
		$('#uploadImages').trigger('click');
	});
	$('#uploadImages').live('change', function(){
		buildUpload(this.files);
	});
}
function buildUpload (files) {
	$('#elink, #exist, #preview').hide();

	var imgData = false, reader, picture, file = files[0];
	if(!!file.type.match(/image.*/)){
		if(window.FormData){
			imgData = new FormData();
		}
		if(window.FileReader){
			reader = new FileReader();
			reader.onloadend = function(e){
				$('#before').hide();
				$('#uploading').show();
				uploadingStatus = 1;
				picture = e.target.result;
			};
			reader.readAsDataURL(file);
		}
		if(imgData) {
			imgData.append('images', file);
		}
	}else{
		alert('Debes seleccionar una imagen');
	}

	var bg = document.getElementById('progress');
	var ctx = bg.getContext('2d');
	var imd = null;
	var circ = Math.PI * 2;
	var quart = Math.PI / 2;

	ctx.beginPath();
	ctx.strokeStyle = '#2F3640';
	ctx.lineCap = 'square';
	ctx.closePath();
	ctx.fill();
	ctx.lineWidth = 10.0;

	imd = ctx.getImageData(0, 0, 120, 120);

	var draw = function(current) {
	    ctx.putImageData(imd, 0, 0);
	    ctx.beginPath();
	    ctx.arc(60, 60, 50, -(quart), ((circ) * current) - quart, false);
	    ctx.stroke();
	}

	if(imgData){
		$.ajax({
			url : 'apps/upload.php',
			type : 'POST',
			data : imgData,
			processData : false,
			contentType : false,
			xhr: function(){
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function(evt){
					var percentComplete = evt.loaded / evt.total;
					var percent = parseFloat( Math.round( (percentComplete * 100) ) );

					$('#progressTxt').text(percent + '%');
					draw( percent / 100 );
				}, false);
				return xhr;
			},
			success : function(r){
				$('#before').show();
				$('#uploading').hide();
				uploadingStatus = 0;
				bg.width = bg.width;

				var res = r.split(':');
				if(res[0] == '1'){
					$('#newLink').val( cdomain + res[2]);
					$('#elink').fadeIn();
					addUploadImg(picture, res[2]);
					$('html, body').scrollTop(0);

					goToPictures();
				}else{
					if (res[1] == '1'){
						addUploadImg(picture, false);
						$('#newLink').val( cdomain + res[2]);
						$('#elink').fadeIn();
						$('#exist').css({'display':'block'});
					}else{
						alert(r);
					}
				}
			}
		});
    }
}
function addUploadImg (src, uid){
	$('#preview').attr('src', src).fadeIn();
	if (uid){
		$('#pictures').prepend('<li class="gotopic" id="' + uid + '" rel="' + uid + '"><img src="' + cdomain + uid + '?thumb"/></li>');
		$('#' + uid).addClass('in');
	}
}
function list_pictures () {
	$('#pictures').fadeIn();
	$('#picture').fadeOut();
	var jsonPictures = 'json/listar.php?key=' + jsonKey + '&value=' + jsonValue + '&step=' + jsonStep;
	$.getJSON(jsonPictures,
		function (res){
			$('#pictures').append('<li id="gifloading"></li>');
			for(x in res){
				var tmp = '<li class="gotopic" id="' + res[x].id + '" rel="' + res[x].id + '"><img src="' + cdomain + res[x].id + '?thumb"/></li>';
				//$('#pictures').append(tmp);
				$(tmp).insertBefore('#gifloading');
				$('#' + res[x].id).addClass('in');
			}
			$('#gifloading').remove();
		}
	);
}
function hasTargetBlank () {
	$('a').attr('target', '_blank');
}
function clickeableinput (input) {
	$(input).live('click', function(){
		this.select();
	});
}
function scroll_loaded() {
	if (visor == 0){
		var scrollpoint = ($('#pictures').height() - $(window).height()) - 50,
			scrollpos = $(window).scrollTop();

		if(scrollpos > scrollpoint) {
			jsonStep++;

			$('#loading').show();
			list_pictures();
		}
	}
}

window.onpopstate = function(e) {
	switch( e.state ){
		case 'home':
			jsonKey = '';
			jsonValue = '';
			jsonStep = 1;

			$('#pictures').html('');
			list_pictures();
			goToPictures(); 
			break;
		case 'viewer': goToPicture(thisPic); break;
		case 'profile': break;
	}
}

var picc;
function goToPicture (picId){
	visor = 1;

	var jsonPictures = 'json/listar.php?key=picture&value=' + picId;
	$.getJSON(jsonPictures,
		function(res){
			var uid = picId,
				date = res[0].date;

			$('#muPicture').attr('src', 'http://graph.facebook.com/' + res[0].author.fbId + '/picture?type=large');
			$('#muName').text(res[0].author.name);

			$('#pPicture').attr('src', cdomain + uid);
			$('#pLink').val(cdomain + uid);
			$('#goToPicture').attr('href', cdomain + uid);

			var fecha = date.split(' ');
			var diasem = parseInt( fecha[0] );

			fecha = fecha[1].split('-');
			var	dia = fecha[0],
				mes = parseInt( fecha[1] ),
				year = fecha[2];

			var tMes = 'nohay enero febrero marzo abril mayo junio julio agosto septiembre octubre noviembre diciembre',
				tDia = 'doming lunes martes miércoles jueves viernes sábado';
				tMes = tMes.split(' ');
				tDia = tDia.split(' ');

				diasem = tDia[diasem];
				mes = tMes[mes];

			var formatDate = 'El ' + diasem + ' ' + dia + ' de ' + mes + ' del ' + year;
			$('#pDate').text(formatDate);

			$('#fbComments').attr('src', 'apps/fbComments.php?uid=' + uid);

			window.history.pushState('viewer', 'Dannegm Picboard', '/picboard/#/viewer/' + uid);

			$('#fbLike').live('click', function(e){
				e.preventDefault();
				FB.api('me/og.likes', 'post', {
					object: cdomain + uid
				}, function(r) { console.log(r); });
			});

			$('#pictures').fadeOut();
			$('#picture').fadeIn();
			$('html, body').scrollTop(0);
		});
}
function goToPictures (){
	$('#pictures').fadeIn();
	$('#picture').fadeOut();

	window.history.pushState('home', 'Dannegm Picboard', '/picboard/');
	visor = 0;
}

function run () {
	login();

	$('#container').css({
		'min-height': $(window).innerHeight() + 'px'
	});
	$('#elink, #exist').hide();

	drag_drop();
	btn_upload();

	clickeableinput('#newLink');
	clickeableinput('#linkPicture');
	hasTargetBlank();

	list_pictures();
	//window.history.pushState('home', 'Dannegm Picboard', '/picboard/');

	if ( thisDomain.match(/viewer/i) ){
		var picIdn = thisDomain.split('/').reverse();
		thisPic = picIdn;
		goToPicture(picIdn[0]);
	}

	$('.gotopic').live('click', function() {
		thisPic = $(this).attr('rel');
		goToPicture( $(this).attr('rel') );
	});
	$('#goToPictures').live('click', function(e){
		e.preventDefault();
		goToPictures();
	});
	$('.viewProfile').live('click', function(e){
		e.preventDefault();
		window.history.pushState('profile', 'Dannegm Picboard', '/picboard/');

		jsonKey = 'author';
		jsonValue = $(this).attr('rel');
		jsonStep = 1;

		$('#pictures').html('');
		list_pictures();
	});

	$(window).scroll(scroll_loaded);

	window.onbeforeunload = function () {
		if ( uploadingStatus == 1 ) {
			return 'Si sales ahora, la imagen que estás cargando podría perderse';
		}
	};
}
$(document).ready(run);