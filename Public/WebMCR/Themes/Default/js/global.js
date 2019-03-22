var qx = {

	getUrlParam: function(name){
		name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
		var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
		return (results == null) ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	},

	getUrlParams: function(){
		var string = location.search.split('?')[1];

		var result = {};

		if(string==undefined || string=='undefined'){ return result; }

		$.each(string.split('&'), function(key, val){
			expl = val.split('=');

			result[expl[0]] = expl[1];
		});

		return result;
	},

	changeUrlParam: function(json){
		var get = this.getUrlParams();

		$.each(json, function(key, value){

			get[key] = value;
		});

		if(Object.keys(get).length<=0){ location.search = ''; return false; }

		var string = '?';

		$.each(get, function(key, val){
			string = string+key+'='+val+'&';
		});

		string = string.substring(0, string.length - 1);

		location.search = string;

		return true;
	},

	notify: function(text, title, type){

		var self = this;

		type = (type===true);

		title = (title===undefined) ? '' : title;

		var block = $('.a-alert');

		if(!block.length){
			$('body').append('<div class="a-alert"></div>');

			block = $('.a-alert');
		}

		var id = Math.random();

		block.append('<div class="alert-id" style="display: none;" data-id="'+id+'">' +
						'<div class="wrapper">' +
							'<div class="text">'+text+'</div>' +
							'<div class="footer-block">' +
								'<div class="block-left"><div class="title">'+title+'</div></div>' +
								'<div class="block-right">' +
									'<button class="btn btn-transparent col-white text-upper close-trigger">СКРЫТЬ</button>' +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>');

		block.find('.alert-id[data-id="'+id+'"]').fadeIn('fast');

		setTimeout(function(){
			self.notify_close(id);
		}, 3000);

		return type;
	},

	confirm_storage: {},

	confirm: function(text, title, callback_yes, callback_no, timeout){
		var self = this;

		if(timeout===undefined){
			timeout = 10000;
		}

		title = (title===undefined) ? '' : title;

		var block = $('.a-alert');

		if(!block.length){
			$('body').append('<div class="a-alert"></div>');

			block = $('.a-alert');
		}

		var id = 'alert_'+self.randstr(10);

		block.append('<div class="alert-id confirm" style="display: none;" data-id="'+id+'">' +
						'<div class="wrapper">' +
							'<div class="text">'+text+'</div>' +
							'<div class="footer-block">' +
								'<div class="block-left"><div class="title">'+title+'</div></div>' +
								'<div class="block-right">' +
									'<button class="btn btn-transparent col-white text-upper yes-trigger">Да</button> ' +
									'<button class="btn btn-transparent col-white text-upper no-trigger">Нет</button>' +
								'</div>' +
							'</div>' +
						'</div>' +
					'</div>');

		var alert = block.find('.alert-id[data-id="'+id+'"]');

		qx.confirm_storage[id] = {
			'yes': function(){ if(typeof callback_yes === 'function'){ callback_yes(); } },
			'no': function(){ if(typeof callback_no === 'function'){ callback_no(); } },
			'timeout': setTimeout(function(){
				delete qx.confirm_storage[id];
				alert.fadeOut('fast', function(){
					if(typeof callback_no === 'function'){ callback_no(); }
				});
			}, timeout)
		};

		alert.fadeIn('fast');
	},

	notify_close: function(id){

		if(id!==undefined){
			var alert = $('.a-alert > .alert-id[data-id="'+id+'"]');

			if(alert.hasClass('confirm')){
				return;
			}

			var closer = alert.find('.close-trigger');

			if(closer.attr('data-disabled')==='true'){
				return false;
			}

			if(!alert.length){
				return false;
			}

			alert.fadeOut('fast', function(){
				$(this).remove();
			});

			return false;
		}

		var alerts = $('.a-alert > .alert-id:not(.confirm)');

		if(!alerts.length){
			return false;
		}

		alerts.fadeOut('fast', function(){
			$(this).remove();
		});

		return false;
	},

	loader: function(to, type){
		if(type===undefined){ type = ''; }

		return $(to).html('<img src="'+this.theme_url+'img/loading'+type+'.gif" alt="Loading..." />');
	},

	loading: function(type){

		type = (type===true);

		if(!type){
			$('#js-loader').hide();
		}else{
			$('#js-notify').hide();

			$('#js-loader').show();
		}

		return type;
	},

	base64: function(string, decode){
		return (decode) ? decodeURIComponent(escape(window.atob(string))) : btoa(unescape(encodeURIComponent(string)));
	},

	array_unique_values: function(input){
		var newlist = [];

		if(!Array.isArray(input)){
			return newlist;
		}

		var size = input.length;

		if(size<=0){
			return newlist;
		}

		for(var i = 0; i < size; i++){
			if(newlist.indexOf(input[i])>=0){
				continue;
			}

			newlist.push(input[i]);
		}
		return newlist;
	},

	array_remove_value: function(array, value){

		var i = array.indexOf(value);

		if(i===-1){
			return array;
		}

		array.splice(i, 1);

		return array;
	},

	randint: function(min, max){
		return Math.round(min - 0.5 + Math.random() * (max - min + 1));
	},

	randstr: function(num){
		var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

		var str = '';

		for(var i = 0; i < num; i++){
			str += chars.charAt(this.randint(0, chars.length));
		}

		return str;
	},

	scrollTo: function(element, speed, complete){

		var speed = (speed==undefined) ? 300 : speed;
		var complete = (complete==undefined) ? function(){} : complete;

		$('html, body').animate({
			scrollTop: $(element).offset().top
		}, speed, complete);
	},

	load_elements: function(mod, params, success, disable_loading, error){
		var that = this;

		if(disable_loading==undefined){ that.loading(); }

		var formData = new FormData();

		formData.append('token', meta.token);

		if(params!==undefined){
			$.each(params, function(key, value){
				formData.append(key, value);
			});
		}

		$.ajax({
			url: mod,
			dataType: 'json',
			type: 'POST',
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			data: formData,

			error: function(data, textStatus, xhr){

				if(error!=undefined){
					error(data, textStatus, xhr);
				}else{
					console.log(data);

					return that.notify('Произошла ошибка выполнения запроса. Обратитесь к администрации');
				}
			},

			success: function(data, textStatus, xhr){

				success(data, textStatus, xhr);

				if(disable_loading==undefined){ that.loading(false); }
			}
		});
	},

	restyle_inputs: function(){
		$('input[type="file"].styled').each(function(i){
			var that = $(this);

			if(that.hasClass('activated')){ return; }

			var name = (that.attr('data-name')==undefined) ? 'ВЫБЕРИТЕ ФАЙЛ' : that.attr('data-name');
			that.attr('data-id', i).addClass('activated').before('<button type="button" class="btn block text-upper input-file-styled" data-id="'+i+'">'+name+'</button>');
		});
	},

	translate: function(text){
		var symbols = {
			'а':'a','А':'A',
			'б':'b','Б':'B',
			'в':'v','В':'V',
			'г':'g','Г':'G',
			'д':'d','Д':'D',
			'е':'e','Е':'E',
			'ж':'zh','Ж':'ZH',
			'з':'z','З':'Z',
			'и':'i','И':'I',
			'й':'y','Й':'Y',
			'к':'k','К':'K',
			'л':'l','Л':'L',
			'м':'m','М':'M',
			'н':'n','Н':'N',
			'о':'o','О':'O',
			'п':'p','П':'P',
			'р':'r','Р':'R',
			'с':'s','С':'S',
			'т':'t','Т':'T',
			'у':'u','У':'U',
			'ф':'f','Ф':'F',
			'х':'h','Х':'H',
			'ц':'c','Ц':'TS',
			'ч':'ch','Ч':'CH',
			'ш':'sh','Ш':'SH',
			'щ':'sch','Щ':'SHC',
			'ъ':'','Ъ':'',
			'ы':'i','Ы':'I',
			'ь':'','Ь':'',
			'э':'e','Э':'E',
			'ю':'yu','Ю':'YU',
			'я':'ya','Я':'YA',
			'і':'i','І':'I',
			'ї':'yi','Ї':'YI',
			'є':'e','Є':'E',
			' ':'-'
		};

		var len = text.length;
		var res = '';

		for(var i = 0; i<len; i++){
			if(symbols[text[i]] == undefined){
				if(text[i].match(/^[a-z0-9]+$/i)){
					res += text[i];
				}else{
					res += '';
				}
			}else{
				res += symbols[text[i]];
			}
		}

		return res;
	},

	getFormValues: function(form){

		var values = {};

		form.find('input, select, textarea').each(function(){
			var that = $(this);
			var name = that.attr('name');
			var type = that.attr('type');

			if(name==undefined){ return; }

			var multiple = (name.substr(-1)==']');

			if(type=='checkbox' || type=='radio'){
				if(that.prop('checked')){
					if(multiple){
						if(values[name]==undefined){ values[name] = []; }
						values[name].push(that.val());
					}else{
						values[name] = that.val();
					}
				}
			}else if(type=='file'){
				var filelen = that[0].files.length;

				if(filelen<=0){ return; }

				if(multiple && filelen>1){
					for(var fi = 0;fi<filelen;fi++){
						values[name+'_'+fi] = that[0].files[fi];
					}
				}else{
					values[name] = that[0].files[0];
				}
			}else{
				if(multiple){
					if(values[name]==undefined){ values[name] = []; }
					values[name].push(that.val());
				}else{
					values[name] = that.val();
				}
			}
		});

		return values;
	},

	objectLength: function(object){
		if(object===undefined){
			return 0;
		}
		var length = Object.keys(object).length;
		return (length===undefined) ? 0 : length;
	},

	number_range_render: function(){
		var self = this;
		$('.number-range').each(function(k, v){
			var that = $(v);

			if(that.attr('data-id')!=undefined){ return; }

			var id = Math.random();
			var min = parseInt(that.attr('min'));
			var max = parseInt(that.attr('max'));
			var list = '';
			var range = min+max;
			var size = that.outerWidth() + (that.outerWidth() / range * 0.6);
			var left = that.outerWidth() / range / 2 * 0.6;

			that.attr('data-id', id);

			var p = 0;

			for(var i = min; i <= max; i++){
				list += '<li data-val="'+i+'">'+i+'</li>';
				p++;
			}

			that.after('<div class="number-range-block" style="width: '+size+'px; left: -'+left+'px;" data-id="'+id+'">'
				+'<ul>'
				+list
				+'</ul>'
				+'<span class="scroller"></span>'
				+'</div>');
		});
	},

	select_style_render: function(){

		$('.select-style').each(function(k, v){
			var that = $(v);

			var id = Math.random();

			var options = that.find('option');

			var optionlist = '';

			var select_block = '';

			options.each(function(k){
				var disabled = 'false';
				var value = '';
				var selected = 'false';
				var html = $(this).html();

				var that = $(this);

				if(that.attr('value')!==undefined){
					value = $(this).attr('value');
				}

				if(that.prop('disabled')!==undefined){
					disabled = ($(this).prop('disabled')) ? 'true' : 'false';
				}

				if(that.prop('selected')!==undefined){
					selected = ($(this).prop('selected')) ? 'true' : 'false';

					if(selected==='true'){
						select_block = '<div class="select-style-selected" data-key="'+k+'" data-value="'+value+'">'+html+'</div>';
					}
				}

				if(selected!=='true'){
					optionlist += '<li data-key="'+k+'" class="select-style-li" data-value="'+value+'" data-selected="'+selected+'" data-disabled="'+disabled+'">'+html+'</li>';
				}

			});

			var select = $('.select-style-render[data-id="'+id+'"]');

			if(select.length>0){
				select.html(select_block+'<ul class="select-style-ul">'+optionlist+'</ul>');
			}else{
				that.before('<div class="select-style-render" data-id="'+id+'">'+
					select_block+
					'<ul class="select-style-ul">'+optionlist+'</ul>'+
					'</div>');
			}

			that.hide();
		});
	}
};

$(function(){

	$('form[method="POST"], form[method="post"]').prepend('<input name="token" value="'+meta.token+'" type="hidden">');

	qx.restyle_inputs();

	qx.select_style_render();

	setTimeout(function(){
		qx.notify_close();
	}, 3500);
	// JS Notify -

	$(window).on("scroll", function(){

		if(typeof global_scroll != 'undefined'){ clearTimeout(global_scroll); }

		global_scroll = setTimeout(function(){
			if($(window).scrollTop() <= 0){
				$(".global-scroll").fadeOut("slow");
			}else{
				$(".global-scroll").fadeIn("fast");
			}
		}, 50);
	});

	$('body').fadeIn().on('click', '.select-style-render > .select-style-selected', function(e){
		e.preventDefault();

		var that = $(this);

		that.closest('.select-style-render').toggleClass('open');
	}).on('click', '.a-alert > .alert-id:not(.confirm) .close-trigger', function(e){
		e.preventDefault();

		qx.notify_close($(this).closest('.alert-id').attr('data-id'));

	}).on('click', '.a-alert > .alert-id.confirm .yes-trigger, .a-alert > .alert-id.confirm .no-trigger', function(e){
		e.preventDefault();

		var that = $(this), yes;

		if(that.hasClass('yes-trigger')){
			yes = true;
		}else if(that.hasClass('no-trigger')){
			yes = false;
		}else{
			return;
		}

		var alert = that.closest('.alert-id');

		var id = alert.attr('data-id');
		clearTimeout(qx.confirm_storage[id].timeout);

		alert.fadeOut('fast', function(){
			$(this).remove();

			if(yes){
				qx.confirm_storage[id].yes();
			}else{
				qx.confirm_storage[id].no();
			}

			delete qx.confirm_storage[id];
		});

	}).on('click', '.input-file-styled', function(e){
		e.preventDefault();

		var that = $(this);

		var id = that.attr('data-id');

		$('input[type="file"][data-id="'+id+'"].styled').trigger('click');
	}).on('click', '.scroll-to', function(e){
		e.preventDefault();

		var that = $(this);

		var element = that.attr('href');

		var scroll = $(element).offset().top;

		$('html').animate({
			scrollTop: scroll
		}, 300);

		//return false;
	}).on('click', 'a[href="#control-menu-resize"]', function(e){
		e.preventDefault();

		var block = $(this).closest('.block-left');

		if(block.hasClass('min')){
			Cookies.remove('control-menu-resize');
		}else{
			Cookies.set('control-menu-resize', 'true');
		}

		block.toggleClass('min');
	}).on('click', '.modal-trigger', function(e){
		e.preventDefault();

		var that = $(this);

		var id = that.attr('data-modal-id');

		if(id===undefined){ return false; }

		var modal = $('.modal[data-target-id="'+id+'"]');

		if(modal.is(':visible')){ return false; }

		$('.modal').fadeOut('fast');

		var body = $('body');

		modal.fadeIn('fast');

		if(!body.hasClass('modal-opened')){
			body.addClass('modal-opened');
		}
	}).on('click', '.modal .close-modal', function(e){
		e.preventDefault();

		$(this).closest('.modal').fadeOut('fast');

		$('body').removeClass('modal-opened');
	}).on('click', '.modal', function(e){

		var target = $(e.target);

		if(target.closest('.modal-container').length<=0){

			e.preventDefault();
			$(this).closest('.modal').fadeOut('fast');
			$('body').removeClass('modal-opened');
		}
	}).on('click', '.tabs > .tab-links > li > a', function(e){
		e.preventDefault();

		var that = $(this);

		var tabs = that.closest('.tabs');

		var li = that.closest('li');

		if(li.hasClass('active')){ return; }

		tabs.find('.tab-list > .tab-id').removeClass('active');
		that.closest('.tab-links').children('li').removeClass('active');

		tabs.find('.tab-list > .tab-id[data-id="'+li.attr('data-id')+'"]').addClass('active');
		li.addClass('active');
	}).on('keydown', 'textarea[data-ctrl="true"]', function(e){
		if(e.keyCode==17){
			ctrl_press = true;
		}
	}).on('keyup', 'textarea[data-ctrl="true"]', function(e){
		if(e.keyCode==17){
			ctrl_press = false;
		}

		if(ctrl_press && e.keyCode==13){
			$(this).closest('form').find('[type="submit"]:visible:first-child').trigger('click');
		}
	}).on('click', 'form[method="GET"][data-form-route="1"] [type="submit"],form[method="get"][data-form-route="1"] [type="submit"]', function(e){
		e.preventDefault();

		var that = $(this);

		var form = that.closest('form');

		var separator = form.attr('data-form-separator');

		var values = qx.getFormValues(form);

		if(!$.isEmptyObject(values)){
			var str = '';

			var error = false;

			$.each(values, function(k, v){

				var input = form.find('[name="'+k+'"]');

				if(!v.match(input.attr('pattern'))){
					var error_text = input.attr('data-pattern-text');
					error = (error_text!==undefined) ? error_text : 'Форма заполнена неверно';
					return false;
				}

				str += separator+k+separator+v;
			});

			if(error){
				return qx.notify(error, 'Ошибка!');
			}
		}

		window.location.href = form.attr('action')+str.substring(separator.length);
	}).on('click', '.preventDefault', function(e){
		e.preventDefault();
	});

	var ctrl_press = false;
});