
function resizeBanner() {
    $('#banner .item').css({
        'height': $( window ).height()-$( "nav.navbar" ).height() +'px'
    });
}

function resizeTable() {
	totalwidth = 0;
	$('#conta table thead tr th').each(function(){
		totalwidth += $(this).outerWidth();
	});

	$('#conta table thead').css({ 'width' : totalwidth+'px' });
	$('#conta table tbody').css({ 'width' : totalwidth+'px' });
}

$(document).ready(function(){

	$('.unslider-arrow.next').html('<i class="fa fa-angle-right" aria-hidden="true"></i>');
	$('.unslider-arrow.prev').html('<i class="fa fa-angle-left" aria-hidden="true"></i>');
	
	$('.navbar-toggle').click(function(e){
		if( $('#app-navbar-collapse:visible').length == 1 ) {
			$('#app-navbar-collapse').hide();
			$(this).find('.fa').removeClass('fa-times');
			$(this).find('.fa').addClass('fa-bars');
		} else {
			$(this).find('.fa').removeClass('fa-bars');
			$(this).find('.fa').addClass('fa-times');
			$('#app-navbar-collapse').show();
		}
	});

	if( $('#banner').length > 0 ) {
		$('#banner .item').each(function(){
			urlimg = $(this).find('img').attr('src');
			if( urlimg.length > 1 ) {
				$(this).css({'background-image':"url('"+ urlimg +"')"});
				$(this).find('img').remove();
			}
		});
	}

	if( $( window ).width() >= 1024 ) {
        resizeBanner();
        $(window).resize(function() {
            resizeBanner();
        });
    } else {
    	resizeTable();
        $(window).resize(function() {
            resizeTable();
        });
    }

	if( $('.banner img').length > 0 ) {
		urlimg = $('.banner img').attr('src');
		if( urlimg.length > 1 ) {
			$('.banner').css({'background-image':"url('"+ urlimg +"')"});
			$('.banner img').remove();
		}
	}

	$('#club .btn').click(function(e){
		$("#inscricao").show();
		$("#club").hide();
	});

	$('#showmore').click(function(e){
		$("#resgate").show();
		$("#resumo").hide();
	});

	$('[type="radio"]').each(function(e){
		$(this).parent().css({ 'padding-top':'5px' });
	});

	$('[type="radio"] + label').click(function(e){
		$('[type="radio"]').removeAttr('checked');
		$(this).prev().attr('checked','checked');
	});

	$('#cpf').mask('999.999.999-99');
	$('#cpf').attr('placeholder','   .   .   -  ');
	$('#nascimento').mask('99/99/9999');
	$('#nascimento').attr('placeholder','  /  /    ');
	$('#cep').mask('99.999-999');
	$('#cep').attr('placeholder','  .   -   ');
	$('#telefone').mask('(99) 9 9999-9999');
	$('#telefone').attr('placeholder','(  )       -    ');
});