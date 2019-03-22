/**
 * @copyright 2017 Qexy
 * @author Qexy (https://vk.com/qexyorg)
 * @licence MIT
 * @version 2.0
 */

var dp = {
	i18: {
		'monthes': ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
		'day': 'д.',
		'month': 'м.',
		'year': 'г',
		'hours': 'ч.',
		'minutes': 'м.',
		'seconds': 'с.'
	},

	date_separator: '.',
	time_separator: ':',
	middle_separator: ' ',

	days_in_year: function(year, month){
		month += 1;
		var d = new Date(year, month, 0);
		return d.getDate();
	},

	parse_date: function(string){
		var split = string.split(this.middle_separator);

		var dates = split[0];
		var times = split[1];

		var date = new Date();

		var year = date.getFullYear();
		var month = date.getMonth();
		var day = date.getDate();
		var hour = date.getHours();
		var minute = date.getMinutes();
		var second = date.getSeconds();

		var split_dates = dates.split(this.date_separator);
		var dates_len = split_dates.length;

		if(dates_len==1){
			year = parseInt(split_dates[2]);
		}else if(dates_len==2){
			year = parseInt(split_dates[2]);
			month = parseInt(split_dates[1]);
		}else if(dates_len==2 || dates_len>2){
			year = parseInt(split_dates[2]);
			month = parseInt(split_dates[1]);
			day = parseInt(split_dates[0]);
		}

		var date_str = year+'-'+month+'-'+day;

		if(times==undefined){
			return date_str;
		}

		var split_times = times.split(this.time_separator);
		var times_len = split_times.length;

		if(times_len==1){
			hour = split_times[0];
		}else if(times_len==2){
			hour = split_times[0];
			minute = split_times[1];
		}else if(times_len==2 || times_len>2){
			hour = split_times[0];
			minute = split_times[1];
			second = split_times[2];
		}

		date_str = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;

		return date_str;
	},

	recalculation: function(id){

		var element = $('.datepicker-modal[data-dp-id="'+id+'"]');
		var input = $('input.datepicker[data-dp-id="'+id+'"]');

		var year = element.find('.year-box').attr('data-dp-year');
		var month = element.find('.month-box').attr('data-dp-month');
		var day = element.find('.days-box').attr('data-dp-day');

		var month = parseInt(month);

		if(isNaN(month)){
			month = 0;
		}

		element.find('.year-box > .year').text(year);
		element.find('.month-box > .month').text(dp.i18.monthes[month]);

		var days_in_month = this.days_in_year(year, month);

		if(element.find('.days-box > ul > li').length!=days_in_month){
			var day_list = '';

			if(day>days_in_month){
				day = days_in_month;
				element.find('.days-box').attr('data-dp-day', day);
			}

			for(var i = 1; i <= days_in_month; i++){
				day_list += (i==day) ? '<li class="active">'+i+'</li>' : '<li>'+i+'</li>';
			}

			element.find('.days-box > ul').html(day_list);
		}else{
			element.find('.days-box > ul > li.active').removeClass('active');
			element.find('.days-box > ul > li:nth-child('+day+')').addClass('active');
		}

		if(month+1<10){
			month = '0'+(month+1);
		}else{
			month = month+1;
		}

		if(day<10){
			day = '0'+day;
		}

		if(element.attr('data-dp-datetime')!='true'){

			input.val(day+'.'+(month)+'.'+year).trigger('input');

			input.trigger('change');

			return true;
		}

		var hour = element.find('.time-box > .hours-box > .input > input').val();
		var minute = element.find('.time-box > .minutes-box > .input > input').val();
		var second = element.find('.time-box > .seconds-box > .input > input').val();

		input.val(day+'.'+month+'.'+year+' '+hour+':'+minute+':'+second).trigger('input');

		input.trigger('change');

		return true;
	}
};

$(function(){

	$('body').on('click', 'input.datepicker', function(e){
		e.preventDefault();

		var that = $(this);

		if(that.attr('data-dp-id')==undefined){
			var id = Math.random().toString();

			var datetime = '';

			var datetime_class = 'false';

			if(that.attr('data-dp-datetime')=='true'){
				datetime = '<div class="time-box">'
					+'<div class="hours-box">'
					+'<div class="input"><input type="number" value="0" min="0" max="23"></div>'
					+'<div class="text">'+dp.i18.hours+'</div>'
					+'</div>'

					+'<div class="minutes-box">'
					+'<div class="input"><input type="number" value="0" min="0" max="59"></div>'
					+'<div class="text">'+dp.i18.minutes+'</div>'
					+'</div>'

					+'<div class="seconds-box">'
					+'<div class="input"><input type="number" value="0" min="0" max="59"></div>'
					+'<div class="text">'+dp.i18.seconds+'</div>'
					+'</div>'
					+'</div>';

				datetime_class = 'true';
			}

			that.attr('data-dp-id', id).after(''
				+'<div class="datepicker-modal" data-dp-id="'+id+'" data-dp-datetime="'+datetime_class+'">'
				+'<div class="wrapper-fix"></div>'
				+'<div class="wrapper scroll-styled">'
				+'<div class="modal-container window">'
				+'<div class="year-box">'
				+'<a href="#" class="prev"><</a>'
				+'<div class="year"></div>'
				+'<a href="#" class="next">></a>'
				+'</div>'

				+'<div class="month-box">'
				+'<a href="#" class="prev"><</a>'
				+'<div class="month"></div>'
				+'<a href="#" class="next">></a>'
				+'</div>'

				+'<div class="days-box">'
				+'<ul></ul>'
				+'</div>'

				+datetime
				+'</div>'
				+'</div>'
				+'</div>');
		}else{
			var id = that.attr('data-dp-id');
		}

		var value = that.val();

		var date = (value=='') ? new Date() : new Date(dp.parse_date(value));

		var year = date.getFullYear();
		var month = date.getMonth();
		var day = date.getDate();
		var hour = date.getHours();
		var minute = date.getMinutes();
		var second = date.getSeconds();

		var day_in_month = dp.days_in_year(year, month);

		var day_list = '';

		for(var i = 1; i <= day_in_month; i++){
			day_list += (i==day) ? '<li class="active">'+i+'</li>' : '<li>'+i+'</li>';
		}

		$('.datepicker-modal[data-dp-id="'+id+'"] .year-box > .year').text(year);
		$('.datepicker-modal[data-dp-id="'+id+'"] .month-box > .month').text(dp.i18.monthes[month]);
		$('.datepicker-modal[data-dp-id="'+id+'"] .days-box > ul').html(day_list);

		$('.datepicker-modal[data-dp-id="'+id+'"] .year-box').attr('data-dp-year', year);
		$('.datepicker-modal[data-dp-id="'+id+'"] .month-box').attr('data-dp-month', month);
		$('.datepicker-modal[data-dp-id="'+id+'"] .days-box').attr('data-dp-day', day);

		$('.datepicker-modal[data-dp-id="'+id+'"] .time-box > .hours-box > .input > input').val(hour);
		$('.datepicker-modal[data-dp-id="'+id+'"] .time-box > .minutes-box > .input > input').val(minute);
		$('.datepicker-modal[data-dp-id="'+id+'"] .time-box > .seconds-box > .input > input').val(second);

		$('.datepicker-modal[data-dp-id="'+id+'"]').show();
	}).on('click', '.datepicker-modal', function(e){
		e.preventDefault();

		if(!$(e.target).closest('.modal-container').length){
			$(this).fadeOut('fast');
		}
	}).on('click', '.datepicker-modal .days-box > ul > li', function(e){
		e.preventDefault();

		var that = $(this);
		var datepicker = that.closest('.datepicker-modal');
		var id = datepicker.attr('data-dp-id');
		var input = $('input.datepicker[data-dp-id="'+id+'"]');

		that.closest('.days-box').attr('data-dp-day', that.text());

		dp.recalculation(id);

		if(input.attr('data-dp-autoclose')=='true'){
			datepicker.fadeOut('fast');
		}
	}).on('click', '.datepicker-modal .month-box > .next, .datepicker-modal .month-box > .prev', function(e){
		e.preventDefault();

		var that = $(this);
		var date = new Date();
		var datepicker = that.closest('.datepicker-modal');

		var box = that.closest('.month-box');
		var year_box = datepicker.find('.year-box');

		var month = parseInt(box.attr('data-dp-month'));
		var year = parseInt(year_box.attr('data-dp-year'));

		if(isNaN(month)){ month = date.getMonth(); }

		if(isNaN(year)){ year = date.getFullYear(); }

		month = (that.hasClass('prev')) ? month-1 : month+1;

		if(month<0){
			year_box.attr('data-dp-year', (year-1));
			month = 11;
		}

		if(month>11){
			year_box.attr('data-dp-year', (year+1));
			month = 0;
		}

		box.attr('data-dp-month', month);

		dp.recalculation(datepicker.attr('data-dp-id'));
	}).on('click', '.datepicker-modal .year-box > .next, .datepicker-modal .year-box > .prev', function(e){
		e.preventDefault();

		var that = $(this);

		var box = that.closest('.year-box');

		var year = parseInt(box.attr('data-dp-year'));

		if(isNaN(year)){ year = date.getFullYear(); }

		year = (that.hasClass('prev')) ? year-1 : year+1;

		box.attr('data-dp-year', year);

		dp.recalculation(that.closest('.datepicker-modal').attr('data-dp-id'));
	}).on('input', '.time-box > .hours-box > .input > input, .time-box > .minutes-box > .input > input, .time-box > .seconds-box > .input > input', function(e){
		e.preventDefault();

		var that = $(this);

		dp.recalculation(that.closest('.datepicker-modal').attr('data-dp-id'));
	});
});