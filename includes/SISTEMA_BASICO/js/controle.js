function votaEnquete(nIdEnquete,nIdAlternativa){
	sDocumentoInsere = 'registra_voto_enquete.php';
	oDiv = document.getElementById('enquete');
	oDivResult = document.getElementById('resposta');
	oFormEnquete = document.getElementById('formEnquete');
	
	//oDiv.innerHTML = 'Carregando...';
	//oDiv.style.display = 'block';
	oXmlHttpSeleciona = inicializaXlmHttp();
	oXmlHttpSeleciona.open("GET",sDocumentoInsere+"?nIdEnquete="+nIdEnquete+"&nIdAlternativa="+nIdAlternativa,true);
	oXmlHttpSeleciona.onreadystatechange = function(){
		if(oXmlHttpSeleciona.readyState == 4){
			if(oXmlHttpSeleciona.status == 200){
				alert(oXmlHttp.responseText);
				oFormEnquete.submit();
				//oDiv.style.display = "none";
				//oDiv.id = "resposta";
				//oDivResult.style.display = "block";
				//oDivResult.id = "enquete";
			} else 
				alert('Problemas na conexão com o servidor! Tente novamente');
		}//if(oXmlHttp.readyState == 4)
	}
	oXmlHttpSeleciona.send(null);
	//alert("Candidatos selecionados com sucesso!");
}

function verResultadoEnquete(){
	oDiv = document.getElementById('enquete');
	oDivResult = document.getElementById('resposta');
	
	oDiv.style.display = "none";
	oDiv.id = "resposta";
	oDivResult.style.display = "block";
	oDivResult.id = "enquete";
}

// VALIDAÇÃO
function validaForm(oForm,cor_validado,cor_erro){
	var erro = false;
	var tipo = Array();
	var msg = '';
	var nGuiaMsg = '';
	for(var i=0; i<oForm.elements.length; i++) {		
		if(oForm.elements[i].lang != undefined) {
			if(oForm.elements[i].accept != undefined && oForm.elements[i].accept != "")
				nGuiaMsg = oForm.elements[i].accept;
			
			oLinha = recuperaLinhaCampo(oForm.elements[i]);
			if(oLinha.style.display != "none" && oLinha.style.visibility != "hidden") {
			
				switch(oForm.elements[i].lang) {
					case 'vazio':
						if(!validaVazio(oForm.elements[i])) {						
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
								
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('vazio') == -1)
								tipo.push('vazio');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFFFFF";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
					case 'vazioRadio':
						if(!validaVazioRadio(oForm.elements[i])) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('vazioRadio') == -1)
								tipo.push('vazioRadio');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFFFFF";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
					
					case 'vazioCheck':
						if(!validaVazioCheck(oForm.elements[i])) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('vazioCheck') == -1)
								tipo.push('vazioCheck');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFFFFF";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
					
					case 'email':
						if(!validaEmail(oForm.elements[i])) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('email') == -1)
								tipo.push('email');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFFFFF";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
					case 'CEP':
						if(!validaCEP(oForm.elements[i])) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('CEP') == -1)
								tipo.push('CEP');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFFFFF";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
					case 'data':
						if(!validaData(oForm.elements[i])) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('data') == -1)
								tipo.push('data');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
					case 'CPF':
						if(!validaCPF(oForm.elements[i])) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('CPF') == -1)
								tipo.push('CPF');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
					case 'CNPJ':
						if(!validaCPF(oForm.elements[i]) || oForm.elements[i].value.length != 14) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('CNPJ') == -1)
								tipo.push('CNPJ');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
					
							erro = erro || false;
						}
					break;
					case 'numerico':
						if(!validaNumerico(oForm.elements[i])) {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('numerico') == -1)
								tipo.push('numerico');
						}
						else {
							if(window.document.getElementById(oForm.elements[i].name).tagName == "INPUT")
								window.document.getElementById(oForm.elements[i].name).style.backgroundColor = "#FFD8CC";
							else
								window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							
							erro = erro || false;
						}
					break;
				}
				switch(oForm.elements[i].id) {
					case 'igual':
						var objetoAuxiliar = window.document.getElementById(oForm.elements[i].lang);
						if(objetoAuxiliar.value != oForm.elements[i].value) {
							window.document.getElementById(oForm.elements[i].name).style.color = cor_erro;
							erro = erro || true;
							aux = tipo.toString();
							if(aux.indexOf('igual') == -1)
								tipo.push('igual');
						}
						else {
							window.document.getElementById(window.document.getElementById(oForm.elements[i].id).name).style.color = cor_validado;
							window.document.getElementById(oForm.elements[i].name).style.color = cor_validado;
							erro = erro || false;
						}
					break;
				}
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

				case 'vazioCheck':	
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
					msg += 'O CPF/CNPJ informado é inválido<br>';
				break;
				case 'CNPJ':	
					msg += 'O CNPJ informado é inválido<br>';
				break;
				case 'numerico':	
					msg += 'O valor informado não é um numérico válido!<br>';
				break;
				case 'igual':	
					msg += 'A confirmação não coincide!<br>';
				break;
			}
		}

		window.document.getElementById('msg'+nGuiaMsg).style.display = 'block';
		window.document.getElementById('msg'+nGuiaMsg).innerHTML = msg;
		
		if(window.document.getElementById('msg2'+nGuiaMsg) ){
			window.document.getElementById('msg2'+nGuiaMsg).style.display = 'block';
			window.document.getElementById('msg2'+nGuiaMsg).innerHTML = msg;
		}

		return false;
	}
	return true;
}

function recuperaLinhaCampo(oElemento) {
	while(oElemento != null && oElemento.tagName != 'TR')
		oElemento = oElemento.parentNode;
	
	if(oElemento == null) {
		alert('Problemas:\nÉ preciso colocar todos os campos input dentro das tags (<tr><td></td></tr>)da tabela.');
		return;
	}
	return oElemento;
}

function validaVazio(campo) {
	var regExp = /[_a-zA-Z0-9-]+/;
	if(!regExp.test(campo.value)) {
		return false;
	}
	return true;
}

function validaVazioRadio(campo) {
	var radio = document.getElementsByName(campo.name);
	for(var i = 0; i < radio.length; i++)
		if(radio[i].checked)
			return true;

	return false;
}

function validaVazioCheck(campo) {
	var checkbox = document.getElementsByName(campo.name);
	for(var i = 0; i < checkbox.length; i++)
		if(checkbox[i].checked)
			return true;

	return false;
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

function validaNumerico(campo) {
	var regExp = /^[0-9]+(\.[0-9]{3})*(\,[0-9]+)?$/;

	if(!regExp.test(campo.value))
		return false;
	return true;
}

function validaNumericoObrigatorio(campo) {
var regExp = /^[0-9]+(\.[0-9]{3})*(\,[0-9]+)?$/;

	if(!regExp.test(campo.value))
		return false;
	return true;
}

function validaNumericoObrigatorioInteiro(campo) {
var regExp = /^[1-9]*([0-9]+(\,[0]*))?([1-9]+)[0-9]*(\.[0-9]{3})*(\,[0-9]+)?$/;

	if(!regExp.test(campo.value))
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





// AJAX ---------------------------------------------------------------------------------------------------------

//MÉTODO RESPONSÁVEL POR INICIALIZAR O OBJETO XMLHTTPREQUEST
function inicializaXlmHttp(){
	try{
    	oXmlHttp = new XMLHttpRequest();
	}catch(ee){
   		try{
        	oXmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    	}catch(e){
        	try{
            	oXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        	}catch(E){
				oXmlHttp = false;
        	}
		}
    }
	return oXmlHttp;
}

//FUNÇÃO RESPONSÁVEL POR MONTAR O SELECT
function montaSelect(nIdSelect,sConteudo,nIdSelecionado){
	oSelect = document.getElementById(nIdSelect);
	sConteudo = sConteudo.replace(/\+/g," ");
    sConteudo = unescape(sConteudo);
	limpaSelect(oSelect);
	vLinha = sConteudo.split('&');
	
	if(sConteudo) {
		//ADICIONA O SELECIONE
		oOption = new Option("Selecione","");
		oSelect.options[0] = oOption;
	
		for(var i = 0 ; i < vLinha.length ; i++){
			vCampo = vLinha[i].split('=');
			oOption = new Option(vCampo[1],vCampo[0]);
			if(nIdSelecionado == oOption.value)
				oOption.selected = true;
			oSelect.options[oSelect.length] = oOption;
		}
	} else {
		//ADICIONA O SELECIONE
		oOption = new Option("Não há itens cadastrados no sistema","");
		oSelect.options[0] = oOption;
	
	}
}

//MÉTODO RESPONSÁVEL POR LIMPAR O OBJETO SELECT
function limpaSelect(oSelect){
	while(oSelect.length != 0){
		oSelect.remove(0);
	}
}


/*
MÉTODO RESPONSÁVEL POR RECUPERAR O CONTEÚDO QUE IRÁ COMPOR O SELECT
nIdSelect => ID DO OBJETO HTML SELECT QUE SERÁ MONTADO
nIdCategoria => ID DA CATEGORIA NO BANCO DE DADOS QUE SERVIRÁ DE FILTRO PARA MONTAGEM DO SELECT
nIdSelecionado => ID DA OPÇÃO QUE FICARÁ MARCADA NO SELECT MONTADO
sDocumento => LOCALIZAÇÃO DO DOCUMENTO QUE ESTÁ REALIZANDO A CONSULTA AO BANCO
*/
function recuperaConteudoSelect(nIdSelect,nId,nIdSelecionado,sDocumento){
	//alert(sDocumento);
	oOption = new Option();
	oOption.value = '';
	oOption.text = 'Aguarde...Carregando';
	oOption.selected = true;
	oSelect = document.getElementById(nIdSelect);
	oSelect.options[oSelect.options.length] = oOption;
	oXmlHttp = inicializaXlmHttp();
	oXmlHttp.open("GET",sDocumento+"?nId="+nId,true);
	oXmlHttp.onreadystatechange = function(){
		if(oXmlHttp.readyState == 4){
			if(oXmlHttp.status == 200){
				var sConteudo = oXmlHttp.responseText;
				montaSelect(nIdSelect,sConteudo,nIdSelecionado);
			} else 
				alert('Problemas na conexão com o servidor! Tente novamente');
		}//if(oXmlHttp.readyState == 4)
	}
	oXmlHttp.send(null);
}

function recuperaConteudoSelectDiferente(nIdSelect,nId,nIdSelecionado,nIdEmp,nIdBloco,sDocumento){
	//alert(sDocumento);
	oOption = new Option();
	oOption.value = '';
	oOption.text = 'Aguarde...Carregando';
	oOption.selected = true;
	oSelect = document.getElementById(nIdSelect);
	oSelect.options[oSelect.options.length] = oOption;
	oXmlHttp = inicializaXlmHttp();
	oXmlHttp.open("GET",sDocumento+"?nId="+nId+"&nIdEmpreendimento="+nIdEmp+"&nIdBloco="+nIdBloco,true);
	oXmlHttp.onreadystatechange = function(){
		if(oXmlHttp.readyState == 4){
			if(oXmlHttp.status == 200){
				var sConteudo = oXmlHttp.responseText;
				montaSelect(nIdSelect,sConteudo,nIdSelecionado);
			} else 
				alert('Problemas na conexão com o servidor! Tente novamente');
		}//if(oXmlHttp.readyState == 4)
	}
	oXmlHttp.send(null);
}


//FUNÇÃO PARA PREENCHER O CONTEÚDO DE UM SELECT DIGITANDO UM TEXTO EM UM TEXTFIELD
function recuperaProduto(nIdSelect,sTexto,sDocumento){
	oOption = new Option();
	oOption.value = '';
	oOption.text = 'Aguarde...Carregando';
	oOption.selected = true;
	oSelect = document.getElementById(nIdSelect);
	oSelect.options[oSelect.options.length] = oOption;
	oXmlHttp = inicializaXlmHttp();
	oXmlHttp.open("GET","/extranet/pedido/"+sDocumento+"?sTexto="+sTexto,true);
	//oXmlHttp.open("GET",sDocumento);
	oXmlHttp.onreadystatechange = function(){
		if(oXmlHttp.readyState == 4){
			if(oXmlHttp.status == 200){
				var sConteudo = oXmlHttp.responseText;
				montaSelect(nIdSelect,sConteudo,'');
			} else 
				alert('Problemas na conexão com o servidor! Tente novamente');
		}//if(oXmlHttp.readyState == 4)
	}
	oXmlHttp.send(null);
}


function chamaRecuperaProduto(e){
	var nCodigoTecla;
	var vIdTarget;
	if(window.event) {// IE
		nCodigoTecla = window.event.keyCode;
		vIdTarget = window.event.srcElement.id.split('_');
		if (nCodigoTecla == 13)
			recuperaProduto('fIdProduto_'+vIdTarget[1],window.event.srcElement.value,'recupera_produto.php');
	
	} else {// Netscape/Firefox/Opera
		nCodigoTecla = e.keyCode;
		vIdTarget = e.target.id.split('_');//alert(vIdTarget[1]);
		if(nCodigoTecla == 13)
			recuperaProduto('fIdProduto_'+vIdTarget[1],e.target.value,'recupera_produto.php');
	}
	return;
}


// OPÇÕES DAS LISTAS -----------------------------------------------------------------------------------------------------------------------

function retornaForm(oElemento) {
	while(oElemento != null && oElemento.tagName != 'FORM')
		oElemento = oElemento.parentNode;
	
	if(oElemento == null) {
		alert('Problemas:\nÉ preciso criar um formulário na lista de elementos.');
		return;
	}
	return oElemento;
}

function retornaIconesOpcoes(oElemento) {
	var oForm = retornaForm(oElemento);
	var oDivIconeOpcoes;
	var sImagens = '';
	var bResultado = true;
	
	// CHECA SE EXISTE O FORMULÁRIO
	if(oForm == null)
		return;
	
	// RECUPERA OS DIV's DO FORMULÁRIO
	var voDivIconesOpcoes = oForm.getElementsByTagName('DIV');
	
	for(var nCount = 0; nCount < voDivIconesOpcoes.length; nCount++)
		if(voDivIconesOpcoes[nCount] != null && voDivIconesOpcoes[nCount].id == 'ICONES')
			oDivIconeOpcoes = voDivIconesOpcoes[nCount];
							
	if(oDivIconeOpcoes == null) {
		alert('Problema:\nÉ preciso criar um DIV com id="ICONES" dentro do formulário.');
		return;
	}
	
	var voImagemIconesOpcoes = oDivIconeOpcoes.getElementsByTagName('IMG');
	for(var nCount = 0; nCount < voImagemIconesOpcoes.length; nCount++)
		if(voImagemIconesOpcoes[nCount].alt == '') {
			sImagens += '\n'+voImagemIconesOpcoes[nCount].src;
			bResultado = false;
		}
		
		if(!bResultado)
			alert('Problema:\nÉ preciso que todos os ícones de opções tenham sua propriedade Alt preenchida. São elas:\n'+sImagens);
	
	return oDivIconeOpcoes;
}

function retornaAjudaOpcoes(oElemento) {
	var oForm = retornaForm(oElemento);
	var oSpanAjudaOpcoes;
	
	// CHECA SE EXISTE O FORMULÁRIO
	if(oForm == null)
		return;
	
	// RECUPERA OS DIV's DO FORMULÁRIO
	var voSpanAjudaOpcoes = oForm.getElementsByTagName('SPAN');
	
	for(var nCount = 0; nCount < voSpanAjudaOpcoes.length; nCount++)
		if(voSpanAjudaOpcoes[nCount] != null && voSpanAjudaOpcoes[nCount].id == 'AJUDA')
			oSpanAjudaOpcoes = voSpanAjudaOpcoes[nCount];
							
	if(oSpanAjudaOpcoes == null) {
		alert('Problema:\nÉ preciso criar um SPAN com id="AJUDA" dentro do formulário.');
		return;
	}
	return oSpanAjudaOpcoes;
}

function retornaChecados(oForm){
	// RECUPERA OS CHECKBOX's DO FORMULÁRIO
	var voCheckbox = oForm.getElementsByTagName('INPUT');
	var nChecados = 0;

	// RETORNA O NÚMERO DE CHECK'S MARCADOS
	for(var i = 0; i < voCheckbox.length; i++) {
		if(voCheckbox[i].checked)
			nChecados++;
	}
	return nChecados;
}

function submeteFormOpcoes(oElemento,sDestino){
	var oForm = retornaForm(oElemento);
	
	// SUBMETE O FORMULÁRIO CASO O ÍCONE ESTEJA ATIVO
	if(oElemento.src.match(/\d/) != 0){
		oForm.action = sDestino;
		oForm.submit();
	}
	return;	
}

function confirmaExclusaoLista(oElemento,sElementoExcluido){
	var oForm = retornaForm(oElemento);
	var oOP = document.getElementById('sOP');
	var sTextoConfirm = 'Confirma a exclusão ?';
	
	if(oOP == null) {
		alert('Problema:\nÉ preciso criar um Input Hidden com id="sOP" dentro do formulário.');
		return;
	}
	
	if(oForm.action == '') {
		alert('Problema:\nÉ preciso definir um destino (action) para o formulário.');
		return;
	}
	
	// VERIFICA SE ESTÁ O ÍCONE ATIVO
	if(oElemento.src.match(/\d/) != 0) {
		if(sElementoExcluido != '')
			sTextoConfirm = 'Confirma a exclusão d'+sElementoExcluido+' ?';
			
		if(confirm(sTextoConfirm)) {
			oForm.sOP.value = 'Excluir';
			oForm.submit();
		}
	}
}

function atualizaIconesOpcoes(oElemento){
	var oForm = retornaForm(oElemento);
	var oDivIconeOpcoes = retornaIconesOpcoes(oElemento);
	var voImagens = oDivIconeOpcoes.getElementsByTagName('img');
	var nChecados = retornaChecados(oForm);
	
	for(var i = 0; i < voImagens.length; i++) {
		var sTipo = voImagens[i].lang;
		
		// ALTERA OS ÍCONES (ATIVO/INATIVO) DE ACORDO COM O TIPO
		// CASO O TIPO FOR "Uma opcao" SÓ DESTACARÁ O ÍCONE CASO SÓ HAJA UM CHECK MARCADO
		// CASO O TIPO FOR "Varias opcoes" SÓ DESTACARÁ O ÍCONE CASO HAJA ALGUM CHECK MARCADO
		if(((sTipo == "Uma opcao") && (nChecados  != 1)) || ((sTipo == "Varias opcoes") && (nChecados  == 0)) || ((sTipo == "Nenhuma opcao") && (nChecados  != 0)))
			voImagens[i].src = voImagens[i].src.replace(/_\d.gif/,'_0.gif');
		else
			voImagens[i].src = voImagens[i].src.replace(/_\d.gif/,'_1.gif');
	}
	return;
}

function atualizaIconeOpcao(oElemento,sStatus){
	var oForm = retornaForm(oElemento);
	var nChecados = retornaChecados(oForm);
	var oAjuda = retornaAjudaOpcoes(oElemento);
	var sTipo = oElemento.lang;
	var nStatus = (sStatus == 'ativo') ? 2 : 1;
	
	// ALTERA OS ÍCONES (ATIVO/INATIVO) DE ACORDO COM O TIPO
	// CASO O TIPO FOR "Uma opcao" SÓ DESTACARÁ O ÍCONE CASO SÓ HAJA UM CHECK MARCADO
	// CASO O TIPO FOR "Varias opcoes" SÓ DESTACARÁ O ÍCONE CASO HAJA ALGUM CHECK MARCADO
	if(((sTipo == "Uma opcao") && (nChecados != 1)) || ((sTipo == "Varias opcoes") && (nChecados == 0)) || ((sTipo == "Nenhuma opcao") && (nChecados != 0)))
		oElemento.src = oElemento.src.replace(/\d/,0);	
	else
		oElemento.src = oElemento.src.replace(/\d/,nStatus);
	
	// ESCREVE O TEXTO DE AJUDA
	if(nStatus == 2)
		oAjuda.innerHTML = oElemento.alt;
	else
		oAjuda.innerHTML = '';
	return;
}


function alteraTodosCheckbox(oImagem){
	var oForm = retornaForm(oImagem);
	var nQtd = nQtdChecados = 0;
	
	// CONTA O NÚMERO DE CHECK'S MARCADOS
	for(var i = 0; i < oForm.elements.length; i++) {
		if(oForm.elements[i].type == 'checkbox')
			nQtd++;
			if(oForm.elements[i].checked)
				nQtdChecados++;
	}
	// CASO HAJA CHECK(S) DESMARCADO(S) MARCA TODOS, CASO CONTRÁRIO DESMARCA TODOS
	for(var i = 0; i < oForm.elements.length; i++) {
			oForm.elements[i].checked = (oForm.elements.length != nQtdChecados) ? true : false;
	}
	return;
}

// ORDENAÇÃO DAS LISTAS -----------------------------------------------------------------------------------------------------------------------

function retornaTabelaLista(oElemento) {
	while(oElemento != null && oElemento.tagName != 'TABLE')
		oElemento = oElemento.parentNode;
	
	if(oElemento == null) {
		alert('Problemas:\nÉ preciso criar uma tabela na lista de elementos.');
		return;
	}
	return oElemento;
}

function retornaColunaLista(oElemento) {
	while(oElemento != null && oElemento.tagName != 'TH')
		oElemento = oElemento.parentNode;
	
	if(oElemento == null) {
		alert('Problemas:\nÉ preciso criar uma tabela na lista de elementos. \nNão foi encontrado o título da coluna a ser ordenada.');
		return;
	}
	return oElemento;
}

function retornaLinhaLista(oElemento) {
	while(oElemento != null && oElemento.tagName != 'TR')
		oElemento = oElemento.parentNode;
	
	if(oElemento == null) {
		alert('Problemas:\nÉ preciso criar uma tabela na lista de elementos. \nNão foi encontrado a linha da coluna a ser ordenada.');
		return;
	}
	return oElemento;
}

function retornaCheckboxLista(oTabela) {
	var voInput = oTabela.getElementsByTagName('INPUT');

	for(var i = 0; i < voInput.length; i++)
		if(voInput[i] != null && voInput[i].type == 'checkbox') 
			return voInput[i];

	alert('Problemas:\nÉ preciso criar o checkbox dos elementos da lista.');	
	return false;
}

// LIMPA A TABELA QUE SERÁ REORDENADA
function reiniciaTabela(oTabela) {
	var nLinhas = oTabela.rows.length - 1;

	for(var i = 0; i < nLinhas; i++) {
		oTabela.deleteRow(1);
	}
	return;
}

//FUNÇÃO RESPONSÁVEL POR CRIAR UM ARRAY COM TODO O CONTEÚDO DA TABELA Q SE VAI ORDENAR
function criaArrayDados(oTabela){
	var vsTabela = new Array();
	var sDestinoLink = "";
	
	//COMEÇANDO A PERCORRER AS LINHAS
	for(nLinha = 1; nLinha < oTabela.rows.length; nLinha++){
		var vArray = new Array;
		
		//PERCORRENDO COLUNAS DE CADA LINHA
		for(nCol = 0 ; nCol < oTabela.rows[nLinha].cells.length ; nCol++){
			
			//PARA CADA ELEMENTO HTML DENTRO DA COLUNA
			for(nFilho = 0 ; nFilho < oTabela.rows[nLinha].cells[nCol].childNodes.length; nFilho++){
				
				//SE FOR INPUT PEGA O VALOR, SE FOR O UM LINK, TRATA-SE ESTE LINK
				if(oTabela.rows[nLinha].cells[nCol].childNodes[nFilho].tagName == 'INPUT'){
					vArray.push(oTabela.rows[nLinha].cells[nCol].childNodes[nFilho].value);
				} else if(oTabela.rows[nLinha].cells[nCol].childNodes[nFilho].tagName == "A") {
					sDestinoLink = oTabela.rows[nLinha].cells[nCol].childNodes[nFilho].href;
					vArray.push(oTabela.rows[nLinha].cells[nCol].childNodes[nFilho].innerHTML);
				} else {
					sDestinoLink = "";
					vArray.push(oTabela.rows[nLinha].cells[nCol].innerHTML);
				}
			}
		}
		vArray.push(sDestinoLink);
		vsTabela.push(vArray);
	}
	return vsTabela;
}

function retornaTipoOrdenacao(vsTabela,nColuna) {
	var nCount = -1;
	
	do {
		nCount++;
		sTextoModelo = vsTabela[nCount][nColuna];
	} while((vsTabela[nCount][nColuna] == '' || vsTabela[nCount][nColuna] == '&nbsp;') && vsTabela.length > nCount + 1)
	
	if(validaData(sTextoModelo))
		return 'data';
	
	if(validaNumerico(sTextoModelo))
		return 'numerico';
	
	return 'string';
}

function inArray(xValor,vVetor) {
  var sTexto = "¬" + vVetor.join("¬") + "¬";
  var oReg = new RegExp ("¬" + xValor + "¬", "gim");
  return (sTexto.match(oReg)) ? true : false;
}

// ORDENA VETOR DE DATAS
function ordenaVetorData(vsTabela,nColuna,sOrdenacao) {
	var vAux = Array();
	var vAux2 = Array();
	var vResultado = Array();
	var sCondicao = (sOrdenacao == 'crescente') ? "nDiaAnterior > nDiaPosterior" : "nDiaAnterior < nDiaPosterior";
	var nAux = 0;
	var nCount = 0;

	for(var i = 0; i < vsTabela.length; i++){
		vAux.push(vsTabela[i][nColuna]);
	}

	for(var i = 0; i < vAux.length; i++) {
		for(var j = vAux.length-1; j > i; j--) {
			var vDataHoraAnterior  = vAux[j-1].split(' ');
			var vDataHoraPosterior = vAux[j].split(' ');
			if(vDataHoraAnterior.length == 2) {
				var vDataAnterior  = vDataHoraAnterior[0].split('/');
				var vDataPosterior = vDataHoraPosterior[0].split('/');
				var vHoraAnterior  = vDataHoraAnterior[1].split(':');
				var vHoraPosterior = vDataHoraPosterior[1].split(':');
				var oDiaAnterior  = new Date(vDataAnterior[2],vDataAnterior[1],vDataAnterior[0],vHoraAnterior[0],vHoraAnterior[1],vHoraAnterior[2]);
				var oDiaPosterior = new Date(vDataPosterior[2],vDataPosterior[1],vDataPosterior[0],vHoraPosterior[0],vHoraPosterior[1],vHoraPosterior[2]);
			} else {
				var vDataAnterior  = vAux[j-1].split('/');
				var vDataPosterior = vAux[j].split('/');
				var oDiaAnterior  = new Date(vDataAnterior[2],vDataAnterior[1],vDataAnterior[0]);
				var oDiaPosterior = new Date(vDataPosterior[2],vDataPosterior[1],vDataPosterior[0]);
			}
			var nDiaAnterior  = oDiaAnterior.getTime();
			var nDiaPosterior = oDiaPosterior.getTime();

			if(eval(sCondicao)) {
				nAux = vAux[j];
				vAux[j] = vAux[j-1];
				vAux[j-1] = nAux;
			}				   
		}
	}

	for(var j = 0; j < vAux.length; j++) {
		for(var i = 0; i < vsTabela.length; i++) {
			if(vsTabela[i][nColuna] == vAux[j] && !inArray(i,vAux2)) {
				vResultado[nCount++] = vsTabela[i];
				vAux2.unshift(i);
			}
		}
	}
	
	return vResultado;
}

// ORDENA VETOR DE STRINGS
function ordenaVetorString(vsTabela,nColuna,sOrdenacao) {
	var vAux = Array(); 
	var vAux2 = Array();
	var vResultado = Array();
	var nCount = 0;
	for(var i = 0; i < vsTabela.length; i++)
		vAux.push(vsTabela[i][nColuna].toLowerCase());
	
	vAux.sort();
	
	if(sOrdenacao == 'decrescente')
		vAux.reverse();
	
	for(var j = 0; j < vAux.length; j++) {
		for(var i = 0; i < vsTabela.length; i++) {
			if(vsTabela[i][nColuna].toLowerCase() == vAux[j] && !inArray(i,vAux2)) {
				vResultado[nCount++] = vsTabela[i];
				vAux2.unshift(i);
			}
		}
	}
	
	return vResultado;
}

// ORDENA VETOR DE NÚMEROS
function ordenaVetorNumerico(vsTabela,nColuna,sOrdenacao) {
	var nAux = 0;
	var sCondicao = (sOrdenacao == 'crescente') ? "Number(vsTabela[j-1][nColuna].replace('.','').replace(',','.').replace('&nbsp;','')) > Number(vsTabela[j][nColuna].replace('.','').replace(',','.').replace('&nbsp;',''))" : "Number(vsTabela[j-1][nColuna].replace('.','').replace(',','.')) < Number(vsTabela[j][nColuna].replace('.','').replace(',','.'))";
	for(var i = 0; i < vsTabela.length; i++) {
		for(var j = vsTabela.length-1; j > i; j--) {
			if(eval(sCondicao)) {
				nAux = vsTabela[j];
				vsTabela[j] = vsTabela[j-1];
				vsTabela[j-1] = nAux;
			}
		}
	}
	return vsTabela;
}

// RETIRA O DESTAQUE DAS COLUNAS QUE NÃO ESTÃO SENDO USADAS PARA ORDERNAR
function retiraDestaqueTitulo(oLinha,nColunaSelecionada) {
	
	for(var nCount = 0; nCount < oLinha.cells.length; nCount++){
		//alert(oLinha.cells[0].className);
		if(nCount != nColunaSelecionada)
			oLinha.cells[nCount].className = '';
	}
	return;
}

// ORDENA A TABELA
function ordenaTabelaLista(oElemento) {
	var oTabelaLista = retornaTabelaLista(oElemento);
	var oColunaLista = retornaColunaLista(oElemento);
	var oLinhaLista = retornaLinhaLista(oElemento);
	var oCheckbox = retornaCheckboxLista(oTabelaLista);
	var vsDadosTabelaLista = criaArrayDados(oTabelaLista);
	
	// RETIRA O DESTAQUE DAS COLUNAS QUE NÃO ESTÃO SENDO USADAS PARA ORDERNAR
	retiraDestaqueTitulo(oLinhaLista,oColunaLista.cellIndex);	
	
	// LIMPA A TABELA QUE SERÁ REORDENADA
	reiniciaTabela(oTabelaLista);
	
	// ALTERA O ESTILO QUE INDICA A ORDENAÇÃO DA COLUNA
	switch(oColunaLista.className) {
		case '':
			sOrdenacao = 'decrescente';
			oColunaLista.className = 'col_ordem_dec';
		break;
		case 'col_ordem_dec':
			sOrdenacao = 'crescente';
			oColunaLista.className = 'col_ordem_cres';
		break;
		case 'col_ordem_cres':
			sOrdenacao = 'decrescente';
			oColunaLista.className = 'col_ordem_dec';
		break;
	}
	
	sTipoOrdenacao = retornaTipoOrdenacao(vsDadosTabelaLista,oColunaLista.cellIndex);

	// CHAMA O MÉTODO ADEQUADO DE ORDENAÇÃO, DE ACORDO COM O TIPO DE DADO DA COLUNA
	switch(sTipoOrdenacao) {
		case 'numerico':
			vsDadosTabelaLista = ordenaVetorNumerico(vsDadosTabelaLista,oColunaLista.cellIndex,sOrdenacao);
		break;
		case 'string':
			vsDadosTabelaLista = ordenaVetorString(vsDadosTabelaLista,oColunaLista.cellIndex,sOrdenacao);
		break;
		case 'data':
			vsDadosTabelaLista = ordenaVetorData(vsDadosTabelaLista,oColunaLista.cellIndex,sOrdenacao);
		break;
	}

	
	// CRIA AS LINHAS DA TABELA
	for(var nLinha = 0; nLinha < vsDadosTabelaLista.length; nLinha++) {
		var nQtdColunas = vsDadosTabelaLista[nLinha].length;
		var oTr = oTabelaLista.insertRow(1);
		
		oTr.className = ((vsDadosTabelaLista.length - nLinha) % 2 == 0) ? "zebra" : "";

		var oCheck = document.createElement("INPUT");
		oCheck.type = "checkbox";
		oCheck.name = oCheckbox.name;
		oCheck.value = vsDadosTabelaLista[nLinha][0];
		oCheck.onclick = function() {
			atualizaIconesOpcoes(this);
		}
		
		// INSERE AS COLUNAS
		for(var nNovaColuna = nQtdColunas-1; nNovaColuna > 0; nNovaColuna--) {
			var oTd = oTr.insertCell(0);
			
			if(nNovaColuna == 1)
				oTd.appendChild(oCheck);
			else {
				
				// DESTACA A COLUNA ORDENADA
				if(nNovaColuna - 1 == oColunaLista.cellIndex)
					oTd.className = 'col_destaca';
				
				//INSERE O LINK NAS COLUNAS
				if(vsDadosTabelaLista[nLinha][nQtdColunas-1] != "") {
					var oLink = document.createElement("A");

					oLink.href = vsDadosTabelaLista[nLinha][nQtdColunas-1];
					oLink.appendChild(document.createTextNode(vsDadosTabelaLista[nLinha][nNovaColuna-1].replace(/&nbsp;/g,'')));
					oTd.appendChild(oLink);
				} else
					oTd.appendChild(document.createTextNode(vsDadosTabelaLista[nLinha][nNovaColuna-1].replace(/&nbsp;/g,'')));
			}
		}
	}

	return;
}

function insereLinhaTabela(oLinha) {
	var oTabela = oLinha.parentNode.parentNode;

	if(oTabela.tagName == "TABLE") {
		// CRIA A NOVA LINHA DA TABELA
		var oNovaLinha = oTabela.insertRow(oTabela.rows.length);
		var nIndiceNovaLinha = oTabela.rows.length-2;
		
		// CRIA AS COLUNAS DA NOVA LINHA
		for(var nCount = 0; nCount < oLinha.cells.length; nCount++) {
			
			// INSERE A NOVA COLUNA DA LINHA 
			oNovaColuna = oNovaLinha.insertCell(oNovaLinha.cells.length);
			
			// CASO A COLUNA MODELO TENHA UM ESTILO OU UM ALINHAMENTO EM ESPECIAL APLICA-SE À NOVA COLUNA
			if(oLinha.cells[nCount].className != '')
				oNovaColuna.className = oLinha.cells[nCount].className;
				
			if(oLinha.cells[nCount].align != '')
				oNovaColuna.align = oLinha.cells[nCount].align;
			
			// INSERE O CONTEÚDO DA LINHA DE MODELO
			oNovaColuna.innerHTML = oLinha.cells[nCount].innerHTML.replace(/_\d/g,'_'+nIndiceNovaLinha);
			
			// CASO TENHA UM CALENDÁRIO NA COLUNA INSTANCIA MANUALMENTE
			if(oNovaColuna.innerHTML.indexOf('Calendar.setup') > 0) {
				
				// RECUPERA O NOME DO CAMPO DATA
				var sRegra = /(inputField:"f)(\w*)(_)/g;
				var sNomeData = new String(oNovaColuna.innerHTML.match(sRegra));
				sNomeData = sNomeData.replace(/(inputField:"f)(\w*)(_)/g,'$2');
	
				var sCampoData = "f"+sNomeData+"_"+nIndiceNovaLinha;
				var sBotaoData = "botao"+sNomeData+"_"+nIndiceNovaLinha;
				
				// CHAMA O MÉTODO QUE ATIVA O CALENDÁRIO
				Calendar.setup({ inputField:sCampoData, button:sBotaoData });
			}
		}
	} else 
		alert('É necessário passar como parâmetro a linha a ser criada.');
	
	return nIndiceNovaLinha;
}

function excluiLinhaTabela(oLinha) {
	var oTabela = oLinha.parentNode.parentNode;

	if(oTabela.tagName == "TABLE") {
		if(oTabela.rows.length > 3)
			oTabela.deleteRow(oTabela.rows.length-1);
		
	} else 
		alert('É necessário passar como parâmetro a linha a ser criada.');
		
	return;
}

function in_array(xValor,vVetor) {
  var sTexto = "¬" + vVetor.join("¬") + "¬";
  var oReg = new RegExp ("¬" + xValor + "¬", "gim");
  return (sTexto.match(oReg)) ? true : false;
}

function exibeBloco(sElementoVisivel) {
	var oDiv = document.getElementById('layer'+sElementoVisivel);
 	if(oDiv.style.display == 'block') {
		oDiv.style.display = 'none';
	} else {
		oDiv.style.display = 'block';
	}	
}

/*
FUNÇÃO RESPONSÁVEL POR INSERIR UM BLOCO DE INFORMAÇÕES DENTRO DO DOCUMENTO HTML
PARAMETROS
sTabelaId -> ID DA TABELA QUE IRÁ CRESCER PARA QUE SUAS LINHAS E COLUNAS RECEBAM NOVOS ELEMENTOS
nControlador -> NÚMERO QUE CONTROLA A QUANTIDADE DE BLOCOS JÁ INSERIDOS
sConteudo -> O BLOCO DE INFORMAÇÕES
sIdElementoConteudo -> ID DO ELEMENTO ONDE SERÁ INSERIDO O CONTEÚDO
*/
function insereHtml(sTableId,nControlador,sIdElementoConteudo){
	sConteudo = document.getElementById(sIdElementoConteudo).innerHTML;
	oTable = document.getElementById(sTableId);
	if(oTable.rows.length > 1)
		oTable.rows[oTable.rows.length-1].cells[1].innerHTML = "<a href='JavaScript: void(0);' onclick=\"JavaScript: excluiHtml('"+sTableId+"',"+(oTable.rows.length-1)+","+(oTable.rows.length-1)+",'"+sIdElementoConteudo+"',false);\"><input name='Excluir' type='button' value='Excluir'></a>";
	else
		oTable.rows[oTable.rows.length-1].cells[1].innerHTML = "";
	
	oNovoTr = oTable.insertRow(oTable.rows.length);
	sNovoConteudoParcial = sConteudo.replace(/\[\d/g,'['+oNovoTr.rowIndex);
	sNovoConteudo = sNovoConteudoParcial.replace(/_\d/g,'_'+oNovoTr.rowIndex);
	sNovoConteudo = sNovoConteudo.replace(/Contato \d/g,'Contato '+oNovoTr.rowIndex);
	oTd1 = oNovoTr.insertCell(0);
	oTd2 = oNovoTr.insertCell(1);
	oTd1.innerHTML = sNovoConteudo;
	//LIMPANDO CONTEUDO
	vInput = oTd1.getElementsByTagName('INPUT');
	for(var i = 0; i < vInput.length; i++)
		vInput[i].value = '';
	oTd2.innerHTML = "<a href='JavaScript: void(0);' onclick=\"JavaScript: insereHtml('"+sTableId+"',"+oNovoTr.rowIndex+",'"+sIdElementoConteudo+"');\"><input name='Adicionar' type='button' value='Adicionar'></a><br><a href='JavaScript: void(0);' onclick=\"JavaScript: excluiHtml('"+sTableId+"',"+(oTable.rows.length-1)+","+(oTable.rows.length-1)+",'"+sIdElementoConteudo+"',true);\"><input name='Excluir' type='button' value='Excluir'></a>";
}

/*
FUNÇÃO RESPONSÁVEL POR EXCLUIR UM BLOCO DE INFORMAÇÕES DENTRO DO DOCUMENTO HTML
PARAMETROS
sTabelaId -> ID DA TABELA QUE IRÁ CRESCER PARA QUE SUAS LINHAS E COLUNAS RECEBAM NOVOS ELEMENTOS
nControlador -> NÚMERO QUE CONTROLA A QUANTIDADE DE BLOCOS JÁ INSERIDOS
sConteudo -> O BLOCO DE INFORMAÇÕES
sIdElementoConteudo -> ID DO ELEMENTO ONDE SERÁ INSERIDO O CONTEÚDO
bUltimaLinha -> INDICADOR SE É O BLOCO FINAL DA TABELA
*/
function excluiHtml(sTableId,nIndiceLinha,nControlador,sIdElementoConteudo,bUltimaLinha){	
	oTable = document.getElementById(sTableId);
	//alert(bUltimaLinha);
	if(bUltimaLinha){
		oTable.deleteRow(oTable.rows.length-1);
		if(oTable.rows.length == 1)
			oTable.rows[oTable.rows.length-1].cells[1].innerHTML = "<a href='JavaScript: void(0);' onclick=\"JavaScript: insereHtml('"+sTableId+"',1,'"+sIdElementoConteudo+"');\"><input name='Adicionar' type='button' value='Adicionar'></a>";
		else 
			oTable.rows[oTable.rows.length-1].cells[1].innerHTML = "<a href='JavaScript: void(0);' onclick=\"JavaScript: insereHtml('"+sTableId+"',"+nControlador+",'"+sIdElementoConteudo+"');\"><input name='Adicionar' type='button' value='Adicionar'></a><br><a href='JavaScript: void(0);' onclick=\"JavaScript: excluiHtml('"+sTableId+"',"+(oTable.rows.length-1)+","+nControlador+",'"+sIdElementoConteudo+"',true);\"><input name='Excluir' type='button' value='Excluir'></a>";
		
	} else {
		oTable.deleteRow(nIndiceLinha);
		
		for(i=0 ; i<oTable.rows.length ; i++){
			//ALTERANDO OS INDICES DA TABELA COLUNA 1
			/*
			sConteudo = oTable.rows[i].cells[0].innerHTML;
			sNovoConteudoParcial = sConteudo.replace(/\[\d/g,'['+oTable.rows[i].rowIndex);
			sNovoConteudo = sNovoConteudoParcial.replace(/_\d/g,'_'+oTable.rows[i].rowIndex);
			sNovoConteudo = sNovoConteudo.replace(/Contato \d/g,'Contato '+oTable.rows[i].rowIndex);
			oTable.rows[i].cells[0].innerHTML = sNovoConteudo;
			*/
			//ALTERANDO OS INDICES DA TABELA COLUNA 2
			sFuncaoExcluir = oTable.rows[i].cells[1].innerHTML;
			sNovaFuncaoExcluir = sFuncaoExcluir.replace(/',\d/g,"',"+oTable.rows[i].rowIndex);
			oTable.rows[i].cells[1].innerHTML = sNovaFuncaoExcluir;
		}
		
	}
}