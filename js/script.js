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

function buildUser () {
	$.post('apps/login.php', 
		{ 'fbId': fbId, 'fbToken': fbToken },
		function(res){
			console.log(res);
			var user = $.parseJSON(res);
			$('#uPicture').attr('src', 'http://graph.facebook.com/' + user.fbId + '/picture?type=large');
			$('#uName').text(user.name);
			$('#uCount').text(user.count);
			$('#uGoToProfile').attr('rel', user.fbId);

			$('#noLogin').fadeOut();
			$('body').removeClass('noSidebar');
		}
	);
}
function login (){
	function doLogin (){
		FB.api('/me', function(user){
			var fbAuth = FB.getAuthResponse();
				fbId = fbAuth.userID;
				fbToken = fbAuth.accessToken;
				buildUser();
				$('#muPictureForm').attr('src', 'http://graph.facebook.com/' + fbId + '/picture');
				if ( !thisDomain.match(/viewer/i) ){
					reload_pictures();
				}
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
				}, {scope: 'email, publish_actions, manage_notifications'});
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

					FB.ui({
						method: 'feed',
						link: cdomain + 'viewer/' + res[2],
						picture: cdomain + res[2] + '?thumb',
						name: 'Dannegm Picboard',
						caption: 'He subido una imagen'
					}, function (r) {
						console.log(r);
					});
					FB.api(
						'me/picboard:upload',
						'post', {
							type: 'picboard:picture',
							picture: cdomain + 'viewer/' + res[2] //http://dannegm.pro/picboard/viewer/r2JP5wYO
						}, function(r) {
							console.log(r);
						}
					);
				}else{
					if (res[1] == '1'){
						addUploadImg(picture, false);
						$('#newLink').val( cdomain + res[2]);
						$('#elink').fadeIn();
						$('#exist').css({'display':'block'});
						goToPicture(res[2]);
					}else{
						alert(r);
					}
				}
			}
		});
    }
}
function addUploadImg (src, uid){
	if (uid){
		$('#pictures').prepend('<li id="' + uid + '" rel="' + uid + '"><button class="del" rel="' + uid + '"></button><img class="gotopic" rel="' + uid + '" src="' + cdomain + uid + '?thumb"/></li>');
		$('#' + uid).addClass('in');
	}
	goToPicture(uid);
}

function list_pictures () {
	$('#pictures').fadeIn();
	$('#picture').fadeOut();
	var jsonPictures = 'json/listar.php?key=' + jsonKey + '&value=' + jsonValue + '&step=' + jsonStep;
	$.getJSON(jsonPictures,
		function (res){
			$('#pictures').append('<li id="gifloading"></li>');
			for(x in res){
				var btnDel = '';
				if ( res[x].author == fbId ) { btnDel = '<button class="del" rel="' + res[x].id + '"></button>'; }
				var tmp = '<li id="' + res[x].id + '" rel="' + res[x].id + '">' + btnDel + '<img class="gotopic" rel="' + res[x].id + '" src="' + cdomain + res[x].id + '?thumb"/></li>';
				//$('#pictures').append(tmp);
				$(tmp).insertBefore('#gifloading');
			}
			$('#gifloading').remove();
		}
	);
}
function reload_pictures () {
	jsonStep = 1;
	jsonKey = '';
	jsonValue = '';
	$('#pictures').html('');
	list_pictures();
}

function delImg (e) {
	e.preventDefault();
	var thisPic = $(this).attr('rel');
	var c = confirm('¿Estás seuguro de eliminar ésta imagen?');
	if (c) {
		$.post('apps/del.php', {
			'pic': thisPic
		}, function(r){
			console.log(r);
			$('li [rel="' + thisPic + '"]').remove();
		});
	}
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
			reload_pictures();
			goToPictures(); 
			break;
		case 'viewer': goToPicture(thisPic); break;
		case 'profile': break;
	}
}
var pUser;
function goToPicture (picId){
	visor = 1;

	var jsonPictures = picId + '?info';
	$.getJSON(jsonPictures,
		function(res){
			var uid = picId,
				date = res.date;

			$('#muPicture').attr('src', 'http://graph.facebook.com/' + res.author.fbId + '/picture?type=large');
			$('#muPictureForm').attr('src', 'http://graph.facebook.com/' + fbId + '/picture');
			$('#muName').text(res.author.name);
			$('.miniProfile').attr('rel', res.author.fbId);

			pUser = res.author;

			$('#pPicture').attr('src', cdomain + uid);
			$('#pLink').val(cdomain + uid);
			$('#goToPicture').attr('href', cdomain + uid);

			clickeableinput('#pLink');

			var fecha = date.split(' ');
			var diasem = parseInt( fecha[0] );

			fecha = fecha[1].split('-');
			var	dia = fecha[0],
				mes = parseInt( fecha[1] ),
				year = fecha[2];

			var tMes = 'nohay enero febrero marzo abril mayo junio julio agosto septiembre octubre noviembre diciembre',
				tDia = 'domingo lunes martes miércoles jueves viernes sábado';
				tMes = tMes.split(' ');
				tDia = tDia.split(' ');

				diasem = tDia[diasem];
				mes = tMes[mes];

			var formatDate = 'El ' + diasem + ' ' + dia + ' de ' + mes + ' del ' + year;
			$('#pDate').text(formatDate);

			$('#cContent').attr('rel', uid);
			$('#comments').html('');
			var comment = res.comments;
			for (x in comment) {
				var cDate = comment[x].date;
					cDate = cDate.split(' ');
				var cDiaSem = parseInt( cDate[0] ),
					cFecha = cDate[1],
					cHora = cDate[2];

				var cFecha = cFecha.split('-'),
					cDia = cFecha[0],
					cMes = parseInt( cFecha[1] ),
					cAno = cFecha[2];

					tMes = 'nohay ene feb mar abr may jun jul ago sep oct nov dic';
						tMes = tMes.split(' ');

					cDiaSem = tDia[cDiaSem];
					cMes = tMes[cMes];

				var cHora = cHora.split(':'),
					cHr = cHora[0],
					cMi = cHora[1],
					cMe = cHora[3];

				var cFormatDate = cDia + ' de ' + cMes + ' del ' + cAno + ' a las ' + cHr + ':' + cMi + cMe;

				var content = comment[x].content;
				var tmp = '<article><img src="http://graph.facebook.com/' + comment[x].author.fbId + '/picture" /><p><strong>' + comment[x].author.name + ' <time>' + cFormatDate + '</time></strong><span>' + content + '</span></p></article>';
				$('#comments').prepend(tmp);
			}

			window.history.pushState('viewer', 'Dannegm Picboard', '/picboard/#/viewer/' + uid);

			$('#pictures').fadeOut();
			$('#picture').fadeIn();
			$('html, body').scrollTop(0);
			$('header ul li').removeClass('active');

			$('#cContent').focus(function(){
				$(this).html('');
			});
			$('#cContent').blur(function(){
				if ( $(this).html() == '' ){
					$(this).html('Comenta ésta imagen...');
				}
			});
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

	$('#elink, #exist').hide();

	drag_drop();
	btn_upload();

	clickeableinput('#newLink');
	clickeableinput('#linkPicture');
	hasTargetBlank();

	list_pictures();

	if ( thisDomain.match(/viewer/i) ){
		var picIdn = thisDomain.split('/').reverse();
		thisPic = picIdn[0];
		goToPicture(picIdn[0]);

		$('#noLogin').hide();
		$('#about').hide();
		$('#container').css('height', 'auto');
	}

	$('#goToHome').live('click', function(e){
		e.preventDefault();
		buildUser();
		reload_pictures();
		goToPictures();

		$('header ul li').removeClass('active');
		$(this).addClass('active');

		if ( $('body').hasClass('noSidebar') ) {
			$('#noLogin').show();
		}
	});
	$('#goToProfile').live('click', function(e){
		e.preventDefault();
		buildUser();
		jsonStep = 1;
		jsonKey = 'author';
		jsonValue = fbId;
		$('#pictures').html('');
		list_pictures();
		goToPictures();

		$('header ul li').removeClass('active');
		$(this).addClass('active');
	});
	$('#goToAbout').live('click', function(e){
		e.preventDefault();
	});

	$('.gotopic').live('click', function() {
		thisPic = $(this).attr('rel');
		goToPicture( $(this).attr('rel') );
	});
	$('#goToPictures').live('click', function(e){
		e.preventDefault();
		goToPictures();
		$('header ul li').removeClass('active');

		if ( $('body').hasClass('noSidebar') ) {
			$('#noLogin').show();
		}
	});
	$('.viewProfile').live('click', function(e){
		e.preventDefault();
		window.history.pushState('profile', 'Dannegm Picboard', '/picboard/');

		jsonKey = 'author';
		jsonValue = $(this).attr('rel');
		jsonStep = 1;

		$('#pictures').html('');
		list_pictures();
		$('header ul li').removeClass('active');
	});
	$('.miniProfile').live('click', function(e){
		$('#uPicture').attr('src', 'http://graph.facebook.com/' + pUser.fbId + '/picture?type=large');
		$('#uName').text(pUser.name);
		$('#uCount').text(pUser.count);
		$('#uGoToProfile').attr('rel', pUser.fbId);

		$('#pictures').html("");

		jsonStep = 1;
		jsonKey = 'author';
		jsonValue = pUser.fbId;
		list_pictures();
		goToPictures();
	});

	$('#cContent').keypress(function(e){
		var key = e.keyCode;
		if(!e.shiftKey){
			if (key == '13'){
				e.preventDefault();
				$.post('apps/comment.php', {
					'do': 'it',
					'pic': $(this).attr('rel'),
					'user': fbId,
					'content': $('#cContent').text()
				}, function(re){
					var res = re.split(':');
					if (res[0] != '0'){
						var tmp = '<article><img src="http://graph.facebook.com/' + fbId + '/picture" /><p><strong>' + res[2] + '</strong><span>' + res[3] + '</span></p></article>';
						$('#comments').prepend(tmp);
						$('#cContent').html('');
					}else{
						alert(re);
					}
					console.log(re);
				});
			}
		}
	});

	$('.del').live('click', delImg)

	$(window).scroll(scroll_loaded);

	window.onbeforeunload = function () {
		if ( uploadingStatus == 1 ) {
			return 'Si sales ahora, la imagen que estás cargando podría perderse';
		}
	};
}
$(document).ready(run);