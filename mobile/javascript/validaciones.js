// JavaScript Document
//---------------------------------------------------------------
//---------------FUNCIONES PARA LETRAS---------------------------
//---------------------------------------------------------------
function soloLetras(e) {
	var retorno = true;
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    digitos = " áéíóúàèìòùüabcdefghijklmnñopqrstuvwxyz";
	especiales = [8, 37, 39, 241, 209, 224, 225, 192, 193, 232, 233, 200, 201, 236, 237, 204, 205, 242, 243, 210, 211, 249, 250, 217, 218, 252, 220];//8=borrar, 37=flecha mover izq,39=flecha mover der, 241=ñ, 209=Ñ, 224=à, 225=á, 192=À, 193=Á, 232=è, 233=é, 200=È, 201=É, 236=ì 237=í, 204=Ì, 205=Í, 242=ò, 243=ó, 210=Ò, 211=Ó, 249=ù, 250=ú, 217=Ù, 218=Ú, 252=ü, 220=ü
    tecla_especial = false;
	//alert(key+'-'+tecla);
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(digitos.indexOf(tecla) == -1 && !tecla_especial){
        retorno = false;
	}
	if(tecla == "'" || tecla == '.'){
		retorno = false;
	}
	return retorno;
}
function limpiaLetras(id,num) {
    var valor = document.getElementById(id).value;
	var exp_reg = /^[A-Za-zzáéíóúñÁÉÍÓÚ\s\xF1\xD1]+$/;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	var verifica = exp_reg.test(valor);
	if(verifica == false){
		verifica = verificaAcentos(valor);
	}
	if ( (verifica == false) || (caracEspeciales(valor) == false) ){
		formulario.modificaEstado(num,'error');
		mostrar(idComentario);
	}
	else {
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}	
}
function letrasYnum(e) {
	var retorno = true;
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    digitos = " áéíóúàèìòùüabcdefghijklmnñopqrstuvwxyz0123456789";
    especiales = [8, 37, 39, 241, 209, 224, 225, 192, 193, 232, 233, 200, 201, 236, 237, 204, 205, 242, 243, 210, 211, 249, 250, 217, 218, 252, 220];//8=borrar, 37=flecha mover izq,39=flecha mover der, 241=ñ, 209=Ñ, 224=à, 225=á, 192=À, 193=Á, 232=è, 233=é, 200=È, 201=É, 236=ì 237=í, 204=Ì, 205=Í, 242=ò, 243=ó, 210=Ò, 211=Ó, 249=ù, 250=ú, 217=Ù, 218=Ú, 252=ü, 220=ü
    tecla_especial = false;
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(digitos.indexOf(tecla) == -1 && !tecla_especial){
        retorno = false;
	}
	if(tecla == "'" || tecla == '.'){
		retorno = false;
	}
	return retorno;
}
function limpiaLetrasYnum(id,num) {
    var valor = document.getElementById(id).value;
	var exp_reg = /^[A-Za-z0-9áéíóúñÁÉÍÓÚ.\s\xF1\xD1]+$/;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	var verifica = exp_reg.test(valor);
	if(verifica == false){
		verifica = verificaAcentos(valor);
	}
	if ( (verifica == false) || (caracEspeciales(valor) == false) ){
		formulario.modificaEstado(num,'error');
		mostrar(idComentario);
	}
	else {
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}	
}
//---------------------------------------------------------------
//---------------FUNCIONES PARA PASSWORDS------------------------
//---------------------------------------------------------------
function tecla_password(e) {
	var retorno = true;
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
	//alert(key+'-'+tecla);
    digitos = "abcdefghijklmnñopqrstuvwxyz0123456789";
   	especiales = [8, 37, 39];//8=borrar, 37=flecha mover izq,39=flecha mover der
    tecla_especial = false;
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(digitos.indexOf(tecla) == -1 && !tecla_especial){
        retorno = false;
	}
	if(tecla == "'" || tecla == '.'){
		retorno = false;
	}
	return retorno;
}
function limpiaPassword(id,num) {
    var valor = document.getElementById(id).value;
	var exp_reg = /^[A-Za-z0-9]{4,15}$/;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	var verifica = exp_reg.test(valor);
	if ( (verifica == false) || (caracEspeciales(valor) == false) ){
		formulario.modificaEstado(num,'error');
		mostrar(idComentario);
	}
	else {
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}	
}
function compara(id1,id2,num){
	var valor1 = document.getElementById(id1).value;
	var valor2 = document.getElementById(id2).value;
	var idComentario = '#';
		idComentario = idComentario.concat(id2);
		idComentario = idComentario.concat('Com');
	//alert(num);
	if( (valor1 != valor2) || (caracEspeciales(valor2) == false) || (valor2 == '') ){
		formulario.modificaEstado(num,'error');
		document.getElementById(id2).value = '';
		mostrar(idComentario);
	}
	else{
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}	
}
//---------------------------------------------------------------
//---------------FUNCIONES PARA EL DNI---------------------------
//---------------------------------------------------------------
function tecla_dni(e) {
	var retorno = true;
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key);
    digitos = "ABCDEFGHJKLMNPQRSTVWXYZ0123456789";
    especiales = [8, 37, 39];//8=borrar, 37=flecha mover izq,39=flecha mover der
    tecla_especial = false;
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(digitos.indexOf(tecla) == -1 && !tecla_especial){
        retorno = false;
	}
	if(tecla == "'" || tecla == '.'){
		retorno = false;
	}
	return retorno;
}
function limpiadni(id,num) {
    var valor = document.getElementById(id).value;
	valor = valor.toUpperCase();
	var exp_reg = /^\d{8}[A-Z]$/;
	var digitos = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E','T'];
	var verifica = exp_reg.test(valor);
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	if ( (verifica == false) || (caracEspeciales(valor) == false) ){
		formulario.modificaEstado(num,'error');
		mostrar(idComentario);
	}
	else {
		if( valor.charAt(8) != digitos[(valor.substring(0, 8))%23] ) {
			formulario.modificaEstado(num,'error');
			mostrar(idComentario);
		}
		else {
			formulario.modificaEstado(num,'ok');
			ocultar(idComentario);
		}
	}	
}
//-------------------------------------------------------------------
//---------------FUNCIONES PARA EL NUMEROS---------------------------
//-------------------------------------------------------------------
function numeros(e) {
	var retorno = true;
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key);
    digitos = "0123456789";
    especiales = [8, 37, 39];//8=borrar, 37=flecha mover izq,39=flecha mover der
    tecla_especial = false;
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(digitos.indexOf(tecla) == -1 && !tecla_especial){
        retorno = false;
	}
	if(tecla == "'" || tecla == '.'){
		retorno = false;
	}
	return retorno;
}
function limpiaNumeros(id,num,modo) {
    var valor = document.getElementById(id).value;
	var verifica = false;
	if(id == 'telefono' || id.indexOf('telefono') != -1 ){
		var exp_reg =  /^\d{9}$/ ;
		verifica = exp_reg.test(valor);
	}
	else{
		if(valor > 0){
			verifica = true;
		}
		//var exp_reg =  /^\d{5}$/ ;
	}
	var idComentario = '#';
	idComentario = idComentario.concat(id);
	idComentario = idComentario.concat('Com');
	//var verifica = exp_reg.test(valor);
	if ( (verifica == false) || (caracEspeciales(valor) == false) ){
		if(modo == 0){//opcion para que pueda ser null
			if(valor == ''){
				formulario.modificaEstado(num,null);
			}
			else{
				formulario.modificaEstado(num,'error');
				mostrar(idComentario);

			}
		}
		else{
			formulario.modificaEstado(num,'error');
			mostrar(idComentario);
		}
	}
	else if (id == 'telefono'){
		var pos1 = valor.substring(0, 1);
		if( ( pos1!= '6') && (pos1 != '7') && (pos1 != '9') ){
			formulario.modificaEstado(num,'error');
			mostrar(idComentario);
		}
		else {
			ocultar(idComentario);
			formulario.modificaEstado(num,'ok');
		}
	}
	else {
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}	
}
/*function compruebaNumero(id,num){
	var valor = document.getElementById(id).value;
	var idComentario = '#';
	idComentario = idComentario.concat(id);
	idComentario = idComentario.concat('Com');
	alert(valor);
	if(!isNaN(valor)){
		ocultar(idComentario);
		formulario.modificaEstado(num,'ok');
	}
	else if(valor == ''){
		formulario.modificaEstado(num,null);
		ocultar(idComentario);
	}
	else{
		formulario.modificaEstado(num,'error');
		mostrar(idComentario);
	}
}*/
//-----------------------------------------------------------------
//---------------FUNCIONES PARA EL EMAIL---------------------------
//-----------------------------------------------------------------
function tecla_email(e) {
	var retorno = true;
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
	//alert(key+'-'+tecla);
    digitos = "abcdefghijklmnopqrstuvwxyz@._-0123456789";
    especiales = [8, 37, 39];//8=borrar, 37=flecha mover izq,39=flecha mover der
    tecla_especial = false;
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(digitos.indexOf(tecla) == -1 && !tecla_especial){
        retorno = false;
	}
	if(tecla == "'"){
		retorno = false;
	}
	return retorno;
}
function limpiaEmail(id,num) {
    var valor = document.getElementById(id).value;
	var exp_reg =  /(\w+)(\.?)(\w*)(\@{1})(\w+)(\.?)(\-?)(\w*)(\.{1})(\w{2,3})/ ;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	var verifica = exp_reg.test(valor);
	if ( (verifica == false) || (valor.indexOf('@') != valor.lastIndexOf('@')) || (caracEspeciales(valor) == false) ){
		if(id == 'cuenta_paypal'){//compruebo cuenta_paypal porque no es obligatorio aunque si se inserta hay que comprobar
			if(valor == ''){
				formulario.modificaEstado(num,null);
			}
			else{
				formulario.modificaEstado(num,'error');
				mostrar(idComentario);
			}
		}
		else {
			formulario.modificaEstado(num,'error');
			mostrar(idComentario);
		}
	}
	else {
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}	
}
//------------------------------------------------------------------
//---------------FUNCIONES PARA DIRECCION---------------------------
//------------------------------------------------------------------
function tecla_direccion(e) {
	var retorno = true;
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    digitos = " áéíóúàèìòùüabcdefghijklmnñopqrstuvwxyz0123456789/,";
	especiales = [8, 37, 39, 241, 209, 224, 225, 192, 193, 232, 233, 200, 201, 236, 237, 204, 205, 242, 243, 210, 211, 249, 250, 217, 218, 252, 220];//8=borrar, 37=flecha mover izq,39=flecha mover der, 241=ñ, 209=Ñ, 224=à, 225=á, 192=À, 193=Á, 232=è, 233=é, 200=È, 201=É, 236=ì 237=í, 204=Ì, 205=Í, 242=ò, 243=ó, 210=Ò, 211=Ó, 249=ù, 250=ú, 217=Ù, 218=Ú, 252=ü, 220=ü
    tecla_especial = false;
    for(var i in especiales) {
        if(key == especiales[i]) {
            tecla_especial = true;
            break;
        }
    }
    if(digitos.indexOf(tecla) == -1 && !tecla_especial){
        retorno = false;
	}
	if(tecla == "'" || tecla == '.'){
		retorno = false;
	}
	return retorno;
}
function limpiaDireccion(id,num,modo) {
    var valor = document.getElementById(id).value;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	var verifica = caracEspeciales(valor);
	if(verifica == false){
		verifica = verificaAcentos(valor);
	}
	if ( (verifica == false) || (valor == '') ){
		if(modo == 0){
			formulario.modificaEstado(num,null);
		}
		else{
			formulario.modificaEstado(num,'error');
			mostrar(idComentario);
		}
	}
	else {
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}	
}
//------------------------------------------------------------------
//---------------FUNCIONES PARA DIRECCION WEB-----------------------
//------------------------------------------------------------------
function limpiaDireccionWeb(id,num,modo) {
    var valor = document.getElementById(id).value;
	var valor = valor.toLowerCase();
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	if(valor != ''){
		var w = valor.indexOf("www.");
		var s = valor.indexOf("https://");
		var n = valor.indexOf("http://");
		if(w != -1 || s != -1 || n != -1 || valor == 'copiar y pegar el enlace'){//si encuentro alguno de estos indicios de web entro
			formulario.modificaEstado(num,'ok');
			ocultar(idComentario);
		}
		else{
			formulario.modificaEstado(num,'error');
			mostrar(idComentario);
		}
	}
	else{
		formulario.modificaEstado(num,'null');
		mostrar(idComentario);
	}
}
//------------------------------------------------------------------
//---------------FUNCIONES PARA CHECK---------------------------
//------------------------------------------------------------------
function check(id,num) {
	var elemento = document.getElementById(id);
	if( !elemento.checked ) {
  		formulario.modificaEstado(num,'error');
	}
	else{
		formulario.modificaEstado(num,'ok');
	}
}
//------------------------------------------------------------------
//---------------FUNCIONES PARA SELECT---------------------------
//------------------------------------------------------------------
function lista(id,num) {
	var indice = document.getElementById(id).selectedIndex;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	if( indice == null || indice == 0 || indice == '') {
  		formulario.modificaEstado(num,'error');
		mostrar(idComentario);
	}
	else{
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}
}
//------------------------------------------------------------------
//---------------FUNCIONES PARA ARCHIVOS---------------------------
//------------------------------------------------------------------
function compruebaImagen(id,num,tamano) {
	var valor = document.getElementById(id).value;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	var extension = (valor.substring(valor.lastIndexOf("."))).toLowerCase();
	if(valor != ''){//entra si no es vacío
		if(extensionImagen(extension)){//entra si la extensión es válida
			if(tamano != 0){//con limite de tamaño
				var imagen = document.getElementById(id);
				var file = imagen.files[0];
				if(file.size <= tamano){//entra si el tamaño es menor al máximo
					formulario.modificaEstado(num,'ok');
					ocultar(idComentario);
				}
				else{//si el tamaño es superior 
					formulario.modificaEstado(num,'error');
					mostrar(idComentario);
				}
			}
			else{//sin limite de tamaño
				formulario.modificaEstado(num,'ok');
				ocultar(idComentario);
			}
		}
		else{//extensión erronea
			formulario.modificaEstado(num,'error');
			mostrar(idComentario);
		}
	}
}
function extensionImagen(ext){
	var retorno = false;
	if(ext == '.jpg' || ext == '.jpeg' || ext == '.png' || ext == '.bmp' || ext == '.gif' || ext == '.JPG' || ext == '.JPEG' || ext == '.PNG' || ext == '.BMP' || ext == '.GIF'){
		retorno = true;
	}
	else{
		retorno = false;
	}
	return retorno;
}
//------------------------------------------------------------------
//---------------FUNCIONES PARA FECHAS---------------------------
//------------------------------------------------------------------
function fecha(id,num) {
	var valor = document.getElementById(id).value;
	var idComentario = '#';
		idComentario = idComentario.concat(id);
		idComentario = idComentario.concat('Com');
	if(valor != ''){
		formulario.modificaEstado(num,'ok');
		ocultar(idComentario);
	}
	else{
		formulario.modificaEstado(num,'null');
		mostrar(idComentario);
	}
	/*hoy = new Date();
	var buscar="/"; 
	var final=valor.replace(new RegExp(buscar,"g") ,"-");*/
}
function existeFecha(fecha){
	var retorno = true;	
    var fechaf = fecha.split("-");
	var year = fechaf[0];
    var month = fechaf[1];
    var day = fechaf[2];
    var date = new Date(year,month,'0');
    if((day-0)>(date.getDate()-0)){
       retorno = false;
    }
    return retorno;
}
//------------------------------------------------------------------
//---------------------FUNCION PARA CARACTERES ESPECIALES-----------
//------------------------------------------------------------------
function verificaAcentos(valor){
	var retorno = false;
	for (var i = 0; i< valor.length; i++) {
			 //var c = valor.charAt(i);
			 var c = valor.charCodeAt(i);
			 if (c == 241 || c == 209 || c == 224 || c == 225 || c == 192 || c == 193 || c == 232 || c == 233 || c == 200 || c == 201 || c == 236 || c == 237 || c == 204 || c == 205 || c == 242 || c == 243 || c == 210 || c == 211 || c == 249 || c == 250 || c == 217 || c == 218 || c == 252 || c == 220){
				 retorno = true;
				 //alert('encontrado'+c);
			  }
    }
	return retorno;
}
function caracEspeciales (valor){
	if( (valor.indexOf('ª') != -1) || (valor.indexOf('|') != -1) || (valor.indexOf('!') != -1) || (valor.indexOf('"') != -1) || (valor.indexOf('·') != -1) || (valor.indexOf('#') != -1) || (valor.indexOf('$') != -1) || (valor.indexOf('~') != -1) || (valor.indexOf('%') != -1) || (valor.indexOf('&') != -1) || (valor.indexOf('¬') != -1) || (valor.indexOf('(') != -1) || (valor.indexOf(')') != -1) || (valor.indexOf('=') != -1) || (valor.indexOf("'") != -1) || (valor.indexOf('?') != -1) || (valor.indexOf('¿') != -1) || (valor.indexOf('¡') != -1) || (valor.indexOf('`') != -1) || (valor.indexOf('^') != -1) || (valor.indexOf('[') != -1) || (valor.indexOf(']') != -1) || (valor.indexOf('+') != -1) || (valor.indexOf('*') != -1) || (valor.indexOf('´') != -1) || (valor.indexOf('{') != -1) || (valor.indexOf('}') != -1) || (valor.indexOf(';') != -1) || (valor.indexOf('¨') != -1) || (valor.indexOf(':') != -1) || (valor.indexOf(' OR ') != -1) || (valor.indexOf(' or ') != -1) || (valor.indexOf('\x00') != -1) || (valor.indexOf('\x1a') != -1) ) {
		return false;
	}
	else {
		return true;
	}
}
function eliminarInyeccion(valor){
	var cadenas = ['"."','=""','= ""', "=' '", "=''", "'.'", "%", " OR ", " or ", " AND ", " and ", "`", "*", " FROM ", " from ", " WHERE ", " where ", " UNION SELECT ", " union select ", "&", " LIKE ", " like "];
	var longitud = cadenas.length;
	for(var i=0; i<longitud; i++){
		valor.replace(cadenas[$i], " ");
	}
	return valor;
}
function verificar_formulario(form){//valida los caracteres especiales en formulario
	var num = form.elements.length;
	var retorno = true;
	for(var i=0; i<num; i++){
		if( caracEspeciales(form.elements[i].value) == false){//entra existen caracteres no permitidos
			retorno = false;
		}
	}
	return retorno;
}
function regula_nulos(frm,ini,fin){//se indica el inicio y fin y estos campos si estan vacios se ponen a null
	for(var i=ini; i<fin; i++){
		if(frm.elements[i].value == ''){
			formulario.modificaEstado(i,'null');
		}
	}	
}
//------------------------------------------------------------------
//--------------------FUNCIONES PARA MOSTRAR------------------------
//------------------------------------------------------------------
function mostrar(id){
	//document.getElementsById('ocultar').style.display = 'none';
	$(id).show();
}
function ocultar(id){
	//document.getElementsById('ocultar').style.display = 'none';
	$(id).hide();
}
function error_respuesta(id,mensaje){
	texto = '<span class="error">'+mensaje+'</span>';
	document.getElementById(id).innerHTML = texto;
}
//------------------------------------------------------------------
//-----------------------CLASE FORMULARIO---------------------------
//------------------------------------------------------------------

function formularioUsuario(){//FORMULARIO DE REGISTRO DE USUARIO
	this.inputs = ['error','error','error',null,'error','error',null,null,null,null,'error','error','error','error'];
	this.modificaEstado = function (num,estado){this.inputs[num] = estado;}
	this.obtenTotal = function (){
		var resultado = true;
		var tam = 14;
		var texto = '';
		for(var i=0; i<tam; i++){
			/*texto = texto.concat(i);
			texto = texto.concat(this.inputs[i]);
			texto = texto.concat('\n');*/
			if(this.inputs[i] == 'error'){
				resultado = false;
				break;
			}
		}
	//return texto;
	return resultado;
	}
}
function formularioIndex(){//FORMULARIO DE ACCESO A LA WEB
	this.inputs = ['error','error'];
	this.modificaEstado = function (num,estado){this.inputs[num] = estado;}
	this.obtenTotal = function (){
		var resultado = true;
		var tam = 2;
		var texto = '';
		for(var i=0; i<tam; i++){
			/*texto = texto.concat(i);
			texto = texto.concat(this.inputs[i]);
			texto = texto.concat('\n');*/
			if(this.inputs[i] == 'error'){
				resultado = false;
				break;
			}
		}
	//return texto;
	return resultado;
	}
}

function formularioLiga(uno,dos,tres,cuatro,cinco){//FORMULARIO VER/MODIFICAR LIGA
	this.inputs = [uno,dos,tres,cuatro,cinco,null];
	this.modificaEstado = function (num,estado){this.inputs[num] = estado;}
	this.obtenTotal = function (){
		var resultado = true;
		var tam = 6;
		var texto = '';
		for(var i=0; i<tam; i++){
			/*texto = texto.concat(i);
			texto = texto.concat(this.inputs[i]);
			texto = texto.concat('\n');*/
			if(this.inputs[i] == 'error'){
				resultado = false;
				break;
			}
		}
	//return texto;
	return resultado;
	}
}

function formularioDivision(precio,fecha,primero,segundo,tercero,cuarto,quinto,todos){//FORMULARIO VER/MODIFICAR LIGA
	this.inputs = [precio,fecha,primero,segundo,tercero,cuarto,quinto,todos];
	this.modificaEstado = function (num,estado){this.inputs[num] = estado;}
	this.obtenTotal = function (){
		var resultado = true;
		var tam = 8;
		var texto = '';
		for(var i=0; i<tam; i++){
			/*texto = texto.concat(i);
			texto = texto.concat(this.inputs[i]);
			texto = texto.concat('\n');*/
			if(this.inputs[i] == 'error'){
				resultado = false;
				break;
			}
		}
	//return texto;
	return resultado;
	}
}
function formularioGeneralQuince(cero,uno,dos,tres,cuatro,cinco,seis,siete,ocho,nueve,diez,once,doce,trece,catorce){//FORMULARIO GENERAL DE REGISTRO DE 15 CAMPOS
	this.inputs = [cero,uno,dos,tres,cuatro,cinco,seis,siete,ocho,nueve,diez,once,doce,trece,catorce];
	this.modificaEstado = function (num,estado){this.inputs[num] = estado;}
	this.obtenTotal = function (){
		var resultado = true;
		var tam = 15;
		var texto = '';
		for(var i=0; i<tam; i++){
			/*texto = texto.concat(i);
			texto = texto.concat(this.inputs[i]);
			texto = texto.concat('\n');*/
			if(this.inputs[i] == 'error'){
				resultado = false;
				break;
			}
		}
	//return texto;
	return resultado;
	}
}
function formularioGeneralVeinticinco(cero,uno,dos,tres,cuatro,cinco,seis,siete,ocho,nueve,diez,once,doce,trece,catorce,quince,diezyseis,diezysiete,diezyocho,diezynueve,veinte,veintiuno,veintidos,veintitres,veinticuatro){//FORMULARIO GENERAL DE REGISTRO DE 15 CAMPOS
	this.inputs = [cero,uno,dos,tres,cuatro,cinco,seis,siete,ocho,nueve,diez,once,doce,trece,catorce,quince,diezyseis,diezysiete,diezyocho,diezynueve,veinte,veintiuno,veintidos,veintitres,veinticuatro];
	this.modificaEstado = function (num,estado){this.inputs[num] = estado;}
	this.obtenTotal = function (){
		var resultado = true;
		var tam = 25;
		var texto = '';
		for(var i=0; i<tam; i++){
			/*texto = texto.concat(i);
			texto = texto.concat(this.inputs[i]);
			texto = texto.concat('\n');*/
			if(this.inputs[i] == 'error'){
				resultado = false;
				break;
			}
		}
	//return texto;
	//alert(texto);
	return resultado;
	}
}
//--------------------------------------------------------------------
//--------------------------VALIDACION--------------------------------
//--------------------------------------------------------------------
