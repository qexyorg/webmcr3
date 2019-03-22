$(function(){

	$('body').on('input', '.autocomplete', function(){
		var that = $(this);

		var url = that.attr('data-url');

		if(url===undefined || url===''){ return; }

		var value = that.val();

		if(that.attr('data-append')==='true'){
			value = value.replace(' ', '');

			var split = value.split(',');

			value = split[split.length-1];
		}

		var id = that.attr('data-ac-id');

		var list = $('.autocomplete-list[data-id="'+id+'"]');

		if(value===''){
			list.empty();
			return;
		}

		var params = {
			'token': meta.token,
			'value': value
		};

		qx.load_elements(url, params, function(data){
			list.empty();
			if(!data.type){ return; }

			$.each(data.list, function(k, v){
				list.append('<li data-id="'+v.id+'" data-value="'+v.value+'">'+v.value+'</li>');
			});

			if(list.html()!==''){
				list.show();
			}

		}, false, function(data){
			list.empty();
			console.log(data);
		});
	}).on('focus', '.autocomplete', function(){
		var that = $(this);

		var id = that.attr('data-ac-id');

		if(id===undefined){
			id = Math.random().toString();
			that.attr('data-ac-id', id);

			that.after('<ul class="autocomplete-list" data-id="'+id+'"></ul>');
		}

		var pos = that.position();

		var list = $('.autocomplete-list[data-id="'+id+'"]');

		list.css({
			'top': (pos.top+that.outerHeight())+'px',
			'left': pos.left+'px',
			'width': that.outerWidth()+'px',
			'overflow': 'auto'
		});

		if(list.html()!==''){
			list.show();
		}
	}).on('click', '.autocomplete-list > li', function(e){
		e.preventDefault();

		var that = $(this);

		var value = that.attr('data-value');

		var list = that.closest('.autocomplete-list');

		var id = list.attr('data-id');

		var ac = $('.autocomplete[data-ac-id="'+id+'"]');

		if(ac.attr('data-append')==='true'){
			var val = ac.val().replace(' ', '');

			var split = val.split(',');

			split[split.length-1] = value;

			value = qx.array_unique_values(split).join(', ');
		}

		ac.val(value);

		setTimeout(function(){
			ac[0].focus();
		}, 100);


		list.empty().hide();
	});

	$('html').on('click', 'body', function(e){

		var target = $(e.target);

		var ac = target.closest('.autocomplete');
		var acl = target.closest('.autocomplete-list');

		if(!ac.length && !acl.length){
			$('.autocomplete-list').hide();
		}else{
			var id = (!ac.length) ? acl.attr('data-id') : ac.attr('data-ac-id');

			$('.autocomplete-list:not([data-id="'+id+'"])').hide();
		}
	});
});