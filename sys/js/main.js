//FILTROS
$(document).ready(function(){
	$(".filtro").change(function(){
		var filtro_key = $(this).attr("id")
		var filtro_val = $(this).val()
		var url_atual = $(location).attr("href")
			
		changeUrl(url_atual, filtro_val, filtro_key)
		
	})	
	
})

//Ordena tabela
$(document).ready(function order(){
	$("tr .filtro").css("cursor", "pointer")
	$("tr .filtro").click(function(){
		var filtro_key = "order"
		var filtro_val = $(this).attr("id")
		var url_atual = $(location).attr("href")

		changeUrl(url_atual, filtro_val, filtro_key)
	})
})

function changeUrl(url_atual, filtro_val, filtro_key){
	var url_new = ""
	var find = filtro_key+"/"
		
	if(url_atual.substr(-1, 1)=='/')
		url_atual = url_atual.substr(0, url_atual.length-1)
		
	if(filtro_val != ''){
		if(url_atual.search(filtro_key)==-1){
			url_new = url_atual+"/"+filtro_key+"/"+filtro_val
		}else{
			var id_num = url_atual.search(find)+find.length
			var id = url_atual.slice(id_num)
			
			url_new = url_atual.replace(find+id, find+filtro_val)
		}
	}else{
		if(url_atual.search(filtro_key)==-1){
			url_new = url_atual
		}else{
			var id_num = url_atual.search(find)+find.length
			var id = url_atual.slice(id_num)
			
			url_new = url_atual.replace(find+id, '')
		}
	}

	$(location).attr("href", url_new)
}

//JANELA MODAL
$(document).ready(function() {
	//seleciona os elementos a com atributo name="modal"
	$('.modal').click(function(e) {
		//cancela o comportamento padrão do link
		e.preventDefault();
		//armazena o atributo href do link
		var page = "#dialog";
		var link = $(this).attr('href');
		var title = $(this).attr("value")
		
		//define o título da página modal
		$("#dialog h2").html(title)
		
		//armazena a largura e a altura da tela
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
		
		//Define largura e altura do div#mask iguais ás dimensões da tela
		$('#mask').css({'width':'100%','height':'100%'});
		//efeito de transição
		$('#mask').fadeIn(500);
		$('#mask').fadeTo("slow",0.8);
		
		$("#dialog").height("auto");
		$("#dialog").width("60%");
		
		//armazena a largura e a altura da janela
		var winH = $(window).height();
		var winW = $(window).width();
		//centraliza na tela a janela popup
		$(page).css('top',  25);
		$(page).css({'left': '10%', 'margin-right': '10%'});
		
		//efeito de transição
		$(page).fadeIn(1000);
		$(page+" article").load(link);
	});
		
	//se o botãoo fechar for clicado
	$('.barra .close').click(function (e) {
		//cancela o comportamento padrão do link
		e.preventDefault();
		$('#mask, .window').hide();
	});
	
	//se div#mask for clicado
	$('#mask').click(function () {
		$(this).hide();
		$('.window').hide();
	});
});

//MENU RESPONSIVO
$(document).ready(function(){
	$("#open_menu").click(function(){
		$("header menu").attr("class", "open")		
	})
	$("main").click(function(){
		$(".open").attr("class", null)
	})
})