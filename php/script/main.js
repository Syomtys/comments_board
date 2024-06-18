$(document).ready(function() {

	window.comments = {
		data: {},
		page: 0,
		filter: 'date',
		sorted: 'desc',
		captcha: {
			get: function(){
				$.ajax({
					url:     '/api/v1/captcha/create/',
					type:     'GET',
					dataType: 'html',
					success: function(response) {
						result = $.parseJSON(response);
						$('#captcha-block #captcha-img').empty();
						$('#captcha-block #captcha-img').append('<img src="/api/v1/captcha/get/?code=' + result.code + '" />');
					},
					error: function(response) {
						console.log('error');
					}
				});
			}
		}
	};

	function capthcaChecker(userInputCode) {
		return new Promise((resolve, reject) => {
			let code = $('#captcha-img img').attr('src');
			let status = {};
			code = code.split('=')[1];

			$.ajax({
				url: '/api/v1/captcha/check/',
				type: 'GET',
				dataType: 'html',
				data: { code: code, input: userInputCode },
				success: function (response) {
					let result = $.parseJSON(response);
					if (result.status == true) {
						status = { status: result.status, message: 'успешно пройдена' };
					} else {
						status = { status: result.status, message: 'не пройдена' };
					}
					resolve(status);
				},
				error: function (response) {
					status = { status: false, message: 'ошибка сервера' };
					resolve(status);
				}
			});
		});
	}

	function drawFilter(){
		$('.item-filter').removeClass('active');
		$('.item-filter[filter="' + window.comments.filter + '"]').addClass('active');
		$('.item-filter.active svg').attr('class', window.comments.sorted);

		getData({filter: window.comments.filter, sorted: window.comments.sorted});
	}

	function printDataPage(data){
		$('#comments').empty();
		for (let i = 0; i < data.length; i++) {
			$('#comments').append(`
				<div class="comment">
				    <div class="user-info">
				        <div class="user-photo">` + data[i].name[0].toUpperCase() + `</div>
				        <div class="user-info-text">
							<p class="name">` + data[i].name + `</p>
							<p class="email">` + data[i].email + `</p>
				        </div>
				    </div>
				<p class="text-comment">` + data[i].comment.replace(/\\n/g, '<br>').replace(/\\r/g, '') + `</p>
				<p class="date-comment">` + data[i].date + `</p>
				</div>`);
		}

	}

	function SwipePage(item){
		printDataPage(window.comments.data[item]);
	}
	function createSwiperPage(){
		let pages = Object.keys(window.comments.data).length;
		$('footer').empty();
		for (let i = 1; i <= pages; i++) {
			let activeClass = '';
			if (i === window.comments.page) {
				activeClass = 'active';
			}
			$('footer').append(`
				<span class="swiper-pagination ` + activeClass + `" page="` + i + `">` + i + `</span>
			`);
		}
	}

	function createPageData(data){
		let commentsPages = {};
		let pageCounter = 0;

		for (let i = 0; i < data.length; i++) {
			if (i % 5 === 0) {
				pageCounter += 1;
				commentsPages[pageCounter] = []; // Initialize as an array
			}
			commentsPages[pageCounter].push(data[i]);
		}

		window.comments.data = commentsPages;
		window.comments.page = 1;
		SwipePage(1);
		createSwiperPage();
	}

	function getData(data){
		$.ajax({
			url:     '/api/v1/comments/get/',
			type:     'GET',
			dataType: 'html',
			data: data,
			success: function(response) {
				result = $.parseJSON(response);
				createPageData(result.data);
			},
			error: function(response) {
				console.log('error');
			}
		});
	}
	function clearForm(){
		$('#add-new-comment #form-name-email input').val('');
		$('#add-new-comment textarea').val('');
		$('#add-new-comment input[name="captcha"]').val('');
	}
	function  showServerInsertRequest(data, status){

		$('.request-info').css('display', 'block');
		if (status) {
			clearForm()
			$('#captcha-block').css('display','none');
			$('#form-name-email').css('display', 'none');
			$('#add-new-comment textarea').css('display', 'none');
			$('#form-buttons').css('display', 'none');
		} else {
			window.comments.captcha.get();
		}
		for (var key in data) {
			// console.log(data[key]);
			$('#' + key).html(data[key]);
		}
		setTimeout(function(){
			$('.request-info').css('display', 'none');
			if (status) {
				$('#add-new-comment').css('background', 'var(--dark-gray)');
				$('#show-form').css('display', 'block');
				window.comments.captcha.get();
			}
		},4000);
	}
	function insertComment(data){
		let captcha = data.find(item => item.name === 'captcha').value;
		let responseData = {
			'request-status': '',
			'request-db': '',
			'request-validator': '',
			'request-captcha': ''
		};
		capthcaChecker(captcha).then(captchaChecked => {
			if (captchaChecked.status) {
				$.ajax({
					url:     '/api/v1/comments/insert/',
					type:     'GET',
					dataType: 'html',
					data: data,
					success: function(response) {
						result = $.parseJSON(response);
						if (result.data) {
							responseData['request-status'] = 'статус: успех';
						} else {
							responseData['request-status'] = 'статус: ошибка';
						}
						responseData['request-captcha'] = 'ответ от капчи: ' + captchaChecked.message;
						responseData['request-db'] = 'ответ от базы данных: ' + result.dbinfo.message;
						responseData['request-validator'] = 'ответ от валидатора: ' + result.message;

						showServerInsertRequest(responseData, result.data);
						getData({filter: window.comments.filter, sorted: window.comments.sorted});
					},
					error: function(response) {
						console.log('error');
					}
				});
			} else {
				responseData['request-status'] = 'статус: ошибка';
				responseData['request-captcha'] = 'ответ от капчи: ' + captchaChecked.message;
				responseData['request-db'] = 'ответ от базы данных: не обработано';
				responseData['request-validator'] = 'ответ от валидатора: не обработано';

				showServerInsertRequest(responseData, captchaChecked.status);
			}
		});
	}

	$(document).on('click', '#show-form', function () {

		$('#captcha-block').css('display','flex');
		$('#form-name-email').css('display','block');
		$('#add-new-comment textarea').css('display','block');
		$('#form-buttons').css('display','block');
		$('#add-new-comment').css('background', 'var(--ligth)');
		$(this).css('display', 'none');
	});
	$('#add-new-comment').submit(function (e) {
		e.preventDefault();
		var data = $('#add-new-comment').serializeArray();
		insertComment(data);
	});
	$(document).on('click', '#clear-form', function () {
		clearForm();
	});
	$(document).on('click', '.swiper-pagination', function () {
		let selectedPage = $(this).attr('page');
		window.comments.page = Number(selectedPage);
		createSwiperPage();
		SwipePage(selectedPage);
	});
	$(document).on('click', '.item-filter', function () {
		window.comments.filter = $(this).attr('filter');
		let invertSorted = $(this).find('svg').attr('class');
		if (invertSorted == 'asc') {
			window.comments.sorted = 'desc';
		} else {
			window.comments.sorted = 'asc';
		}
		drawFilter();
	});

	drawFilter();

	window.comments.captcha.get();
	// setTimeout(function (){console.log(capthcaChecker('hrn2r4'));},1000);

});