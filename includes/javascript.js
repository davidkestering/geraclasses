/*
PARA QUE ESTA FUNÇÃO FUNCIONE CORRETAMENTE, O CAMPO A SER VALIDADO PRECISA TER NO SEU 
LABEL A PROPRIEDADE 'ID' IGUAL À PROPRIEDADE 'NAME' DO CAMPO E ESTE CAMPO PRECISA POSSUIR 
UMA PROPRIEDADE 'ID' QUE REPRESENTARÁ O TIPO DE VALIDAÇÃO QUE DEVE SER REALIZADA.
O FORMULÁRIO DEVERÁ POSSUIR NA PROPRIEDADE 'ID' UM VALOR VÁLIDO
A PÁGINA TERÁ QUE POSSUIR UMA TAG 'SPAN' COM A PROPRIEDADE 'ID' IGUAL A 'msg'
*/
function validaForm(form,cor_validado,cor_erro){
	var erro = false;
	var tipo = Array();
	var msg = '';
	for(var i=0; i<form.elements.length; i++) {
		if(form.elements[i].id != undefined) {
			switch(form.elements[i].id) {
				case 'vazio':
					if(!validaVazio(form.elements[i])) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('vazio') == -1)
							tipo.push('vazio');
					}
					else {
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
					}
				break;
				case 'vazioRadio':
					if(!validaVazioRadio(form.elements[i])) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('vazioRadio') == -1)
							tipo.push('vazioRadio');
					}
					else {
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
					}
				break;				
				case 'email':
					if(!validaEmail(form.elements[i])) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('email') == -1)
							tipo.push('email');
					}
					else {
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
					}
				break;
				case 'CEP':
					if(!validaCEP(form.elements[i])) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('CEP') == -1)
							tipo.push('CEP');
					}
					else {
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
					}
				break;
				case 'data':
					if(!validaData(form.elements[i])) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('data') == -1)
							tipo.push('data');
					}
					else {
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
					}
				break;
				case 'CPF':
					if(!validaCPF(form.elements[i])) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('CPF') == -1)
							tipo.push('CPF');
					}
					else
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
				break;
				case 'CNPJ':
					if(!validaCPF(form.elements[i]) || form.elements[i].value.length != 14) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('CNPJ') == -1)
							tipo.push('CNPJ');
					}
					else
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
				break;
				case 'numerico':
					if(!verificaNumerico(form.elements[i])) {
						window.document.getElementById(form.elements[i].name).style.color = cor_erro;
						erro = erro || true;
						aux = tipo.toString();
						if(aux.indexOf('numerico') == -1)
							tipo.push('numerico');
					}
					else
						window.document.getElementById(form.elements[i].name).style.color = cor_validado;
						erro = erro || false;
				break;
			}
		}
	}
	if(erro) {
		for(var i = 0; i < tipo.length; i++) {
			switch(tipo[i]) {
				case 'vazio':	
					msg += 'Os campos em destaque são obrigatórios<br>';
				break;
				case 'vazioRadio':	
					msg += 'Selecione a opção mais adequada para prosseguir<br>';
				break;
				case 'email':	
					msg += 'O E-mail informado é inválido<br>';
				break;
				case 'CEP':	
					msg += 'O CEP informado é inválido<br>';
				break;
				case 'data':	
					msg += 'A Data informada é inválida<br>';
				break;
				case 'CPF':	
					msg += 'O CPF informado é inválido<br>';
				break;
				case 'CNPJ':	
					msg += 'O CNPJ informado é inválido<br>';
				break;
				case 'numerico':	
					msg += 'O valor informado não é um numérico válido!<br>';
				break;
			}
		}
		inicio = '<table width="100%"  border="0" cellspacing="0" cellpadding="5" bgcolor="#BDDFF0" style="border: 1px solid #006699"><tr><td style="font-family: Verdana, Arial, Helvetica, sans-serif;	font-size: 11px;color: #FFFFFF;background-color: #006699;font-weight: bold;">Erro ao preencher o formulário</td></tr><tr><td style="border: 1px solid #006699"><tr><td style="font-family: Verdana, Arial, Helvetica, sans-serif;	font-size: 11px;color: #000000;">';
		fim = '</td></tr></table>';
		msg = inicio+msg+fim;
		window.document.getElementById('msg').innerHTML = msg;

		return false;
	}
	return true;
}

function validaVazio(campo) {
	if(!(campo.value)) {
		return false;
	}
	return true;
}

function validaVazioRadio(campo) {
	if(!(campo.checked)) {
		return false;
	}
	return true;
}

function validaEmail(campo) {
	var regExp = /^([_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[_a-z0-9-]+)+)$/;
	if(!regExp.test(campo.value)) {
		return false;
	}
	return true;
}

function validaCEP(campo) {
var regExp = /^[0-9]{8}$/;
	if(!regExp.test(campo.value)) {
		return false;
	}
	return true;
}

function validaData(campo){
	var vData = Array;
	var regExp = /^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/;
	
	if (!regExp.test(campo.value)) {	
		return false;
	}
	vData = campo.value.split('/');
	
	nDia = vData[0];
	nMes = vData[1];
	nAno = vData[2];
	
	if((nDia < 1) || (nDia > 31) || ((nDia == 31) && (nMes == 4 || nMes == 6 || nMes == 9 || nMes == 11 )))
		return false;
	
	if(nMes < 1 || nMes > 12)
		return false;
	
	if((nMes == 2 && nDia > 29) || (nMes == 2 && nDia == 29 && (parseInt(nAno/4) != nAno/4)))
		return false;
		
	return true;
}

function validaCPF(campo){
	if(campo.value == ""){
		return false;
	
	} else {
		var z = campo.value;
		if(z.length != 11 && z.length != 14){
			return false;
		
		} else {
			if (z.length == 11){
				var cpf2 = campo.value;
				var j = 10;
				x = 0;
				for(var i = 0; i <= 8; i = i+1){
					x += cpf2.charAt(i) * j;
					j--;
				}
				var resto = x % 11;
				if(resto == 0 || resto == 1) {
					dv1 = 0;
				
				} else {
					dv1 = 11-resto;
				}
				if(dv1 != cpf2.charAt(9)){
					return false;
				
				} else {
					var j = 11;
					var x2 = 0;
					for(i = 0; i <= 8; i++){
						x2 += cpf2.charAt(i)*j;
						j--;
					}
					x2 += dv1*2;
					resto2 = x2 % 11;
					if(resto2 == 0 || resto2 == 1) {
						dv2 = 0;
					
					} else {
						dv2 = 11-resto2;
					}
					if(dv2 != cpf2.charAt(10)){
						return false;
					}
					return true;
				}
			}
			else{
				var j = 5;
				var x = 0;
				var cpf2 = campo.value;
				for(i = 0; i <= 3; i++){
					x += cpf2.charAt(i)*j;
					j--;
				}
				j = 9;
				for (i = 4; i <= 11; i++){
					x += cpf2.charAt(i)*j;
					j--;
				}
				resto = x % 11;
				if(resto == 0 || resto == 1) {
					dv1 = 0;
				
				} else {
					dv1 = 11-resto;
				}
				if(dv1 != cpf2.charAt(12)){
					return false;
				
				} else {
					var j = 6;
					var x = 0;
					for( i = 0; i <= 4; i++){
						x += cpf2.charAt(i)*j;
						j--;
					}
					var j = 9;
					for ( i = 5; i <= 11; i++){
						x += cpf2.charAt(i)*j;
						j--;
					}
					x += dv1*2;
					resto = x % 11;
					if(resto == 1 || resto == 0){
						dv2 = 0;
					
					} else {
						dv2 = 11 - resto;
					}
					if(dv2 != cpf2.charAt(13)){
						return false;
					
					} else {
						return true;
					}
				}
			}
		}
	}
}

function verificaNumerico(campo) {
	if (isNaN(parseInt(campo.value)) || (campo.value < 0)) {	
		return false;
	} 
	return true;
}

function destacaLinha(linha,cor){
	linha.className = cor;
}

// MUDA AUTOMATICAMENTE O FOCO PARA O PRÓXIMO CAMPO DE UM FORMULÁRIO, CASO UM LIMITE
// DE TAMANHO DO CAMPO SEJA ATINGIDO, NO PREENCHIMENTO DO FORMULÁRIO. USAR EM CONJUNTO
// COM O EVENTO onKeyUp NO CAMPO A PARTIR DO QUAL SE DESEJA AVANÇAR.
function mudaFoco(nLimiteMax,form,campo){
	if (campo.value.length == nLimiteMax){
		for(i = 0; i < form.length; i++){
			if (form.elements[i].name == campo.name){
				proximo = i + 1;
				if (proximo < form.length){
					form.elements[proximo].focus();
				}//if (i + 1 < form.length)
			}//if (form.elements[i].name == campo.name)
		}//for(i = 0; form.length; i++)
	}//if (campo.length == nLimiteMax)
}

function alteraImagem(sForm,sBotao,nStatus){
	var form = document.getElementById(sForm);
	var nChecados = retornaChecados(form,sForm.replace('form',''));
	var oBotao = document.getElementById(sBotao);
	var oAjuda = document.getElementById("ajuda"+sForm.replace('form',''));
	var nTipo = oBotao.id.substr(oBotao.id.length-1,1);
	
	// ALTERA OS ÍCONES (ATIVO/INATIVO) DE ACORDO COM O TIPO
	// CASO O TIPO FOR 1 SÓ DESTACARÁ O ÍCONE CASO SÓ HAJA UM CHECK MARCADO
	// CASO O TIPO FOR 2 SÓ DESTACARÁ O ÍCONE CASO HAJA ALGUM CHECK MARCADO
	if(((nTipo == 1) && (nChecados != 1)) || ((nTipo == 2) && (nChecados == 0)) || ((nTipo == 0) && (nChecados != 0)))
		oBotao.src = oBotao.src.replace(/\d/,0);	
	else
		oBotao.src = oBotao.src.replace(/\d/,nStatus);
	
	// ESCREVE O TEXTO DE AJUDA
	oAjuda.innerHTML = recuperaAjuda(oBotao.id,nStatus);
	return;
}

function retornaChecados(form,sCategoria){
	var nChecados = 0;
	
	// RETORNA O NÚMERO DE CHECK'S MARCADOS
	for(var i = 0; i < form.elements.length; i++) {
		if(form.elements[i].name.indexOf(sCategoria) > 0)
			if(form.elements[i].checked)
				nChecados++;
	}
	return nChecados;
}

function atualizaImagens(sForm,sCategoria){
	var form = document.getElementById(sForm);
	
	for(var i = 0; i < document.images.length; i++) {
		var nTipo = document.images[i].id.substr(document.images[i].id.length-1,1);
			
		// ALTERA OS ÍCONES (ATIVO/INATIVO) DE ACORDO COM O TIPO
		// CASO O TIPO FOR 1 SÓ DESTACARÁ O ÍCONE CASO SÓ HAJA UM CHECK MARCADO
		// CASO O TIPO FOR 2 SÓ DESTACARÁ O ÍCONE CASO HAJA ALGUM CHECK MARCADO
		if(((nTipo == 1) && (retornaChecados(form,sCategoria) != 1)) || ((nTipo == 2) && (retornaChecados(form,sCategoria) == 0)) || ((nTipo == 0) && (retornaChecados(form,sCategoria) != 0)))
			document.images[i].src = document.images[i].src.replace(/\d/,0);
		else
			document.images[i].src = document.images[i].src.replace(/\d/,1);
	}
	return;
}

function alteraTodos(sForm){
	var form = document.getElementById(sForm);
	var nQtd = nQtdChecados = 0;
	
	// CONTA O NÚMERO DE CHECK'S MARCADOS
	for(var i = 0; i < form.elements.length; i++) {
		if(form.elements[i].type == 'checkbox')
			nQtd++;
			if(form.elements[i].checked)
				nQtdChecados++;
	}
	// CASO HAJA CHECK(S) DESMARCADO(S) MARCA TODOS, CASO CONTRÁRIO DESMARCA TODOS
	for(var i = 0; i < form.elements.length; i++) {
			form.elements[i].checked = (form.elements.length != nQtdChecados) ? true : false;
	}
	return;
}

function submeteForm(sForm,sBotao,sDestino){
	var form = document.getElementById(sForm);
	var oBotao = document.getElementById(sBotao);
	
	// SUBMETE O FORMULÁRIO CASO O ÍCONE ESTEJA ATIVO
	if(oBotao.src.match(/\d/) != 0){
		form.action = sDestino;
		form.submit();
	}
	return;	
}

function recuperaAjuda(sImagem,nStatus){
	if(nStatus == 1)
		return '';
	if(sImagem.indexOf('Cadastrar') > 0)
		return "Cadastrar";
	if(sImagem.indexOf('Detalhe') > 0)
		return "Detalhe";
	if(sImagem.indexOf('Editar') > 0)
		return "Editar";
	if(sImagem.indexOf('Excluir') > 0)
		return "Excluir";
	if(sImagem.indexOf('Saida') > 0)
		return "Saída";
	if(sImagem.indexOf('Consignar') > 0)
		return "Consignar";
	return;

}
function selecionaOpcao(layer,acao){
	var objeto = document.getElementById(layer);
	objeto.style.visibility = acao;
}

function alteraImagemMenu(sId,sOrigem) {
  var oImagem = document.getElementById(sId);
  oImagem.src = sOrigem;
  return;
}