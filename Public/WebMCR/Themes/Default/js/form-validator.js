var fv = {
	toInt: function(value){
		value = parseInt(value);

		return (isNaN(value)) ? 0 : value;
	},
	setInvalid: function(el, text){
		var id = el.attr('data-fv-id');

		if(id===undefined){
			id = Math.random();
			el.attr('data-fv-id', id);
		}

		var tooltip = $('.fv-input-tooltip[data-fv-id="'+id+'"]');

		if(!tooltip.length){
			el.after('<div class="fv-input-tooltip" data-fv-id="'+id+'"></div>');
			tooltip = $('.fv-input-tooltip[data-fv-id="'+id+'"]');
		}

		var top = el.position().top;

		tooltip.css('top', top+'px').attr('title', text).html(text);

		var current = el.attr('data-fv-css');

		if(current==undefined){
			current = el.css('box-shadow');
			el.attr('data-fv-css', current);
		}

		el.css({
			'box-shadow': 'rgba(244,66,54, 0.3) 0 1px 6px, rgba(244,66,54, 0.5) 0 1px 4px'
		});

		tooltip.css({
			'box-shadow': 'rgba(244,66,54, 0.3) 0 1px 6px, rgba(244,66,54, 0.5) 0 1px 4px'
		});

		tooltip.fadeIn('fast');

		setTimeout(function(){
			el.css('box-shadow', current);
			tooltip.css('box-shadow', current);
		}, 800);
	}
};

$(function(){
	$('.fv-form [type="submit"]').on('click', function(e){

		var that = $(this);

		var valid = true;

		var form = that.closest('.fv-form');

		form.find('input.fv-input, textarea.fv-input').each(function(){
			var that = $(this);

			var value = that.val();
			var length = value.length;

			var min = fv.toInt(that.attr('minlength'));
			var max = fv.toInt(that.attr('maxlength'));
			var pattern = that.attr('pattern');
			var required = that.attr('required');

			required = (required!=undefined);

			if(required && !length){
				valid = false;
				fv.setInvalid(that, 'Поле является обязательным'); return;
			}

			if(required && min > 0 && length < min){
				valid = false;
				fv.setInvalid(that, 'Минимальная длина поля: '+min); return;

			}

			if(required && max > 0 && length > max){
				valid = false;
				fv.setInvalid(that, 'Максимальная длина поля: '+min); return;
			}

			if(required && pattern!==undefined){
				var regex = new RegExp(pattern, 'gim');

				if(regex.exec(value)==null){
					var pattern_error = that.attr('data-pattern-error');
					valid = false;
					var error_text = (pattern_error!==undefined) ? pattern_error : 'Поле заполнено неверно';
					fv.setInvalid(that, error_text); return;
				}
			}
		});

		if(!valid){
			e.preventDefault();
			e.stopPropagation();
			e.stopImmediatePropagation();
		}
	});

	$('body').on('click', '.fv-form .fv-input', function(e){
		e.preventDefault();

		var that = $(this);

		var id = that.attr('data-fv-id');

		if(id!=undefined){
			that.closest('.fv-form').find('.fv-input-tooltip[data-fv-id="'+id+'"]').hide();
		}
	});
});