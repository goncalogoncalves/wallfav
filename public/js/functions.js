/**
 * Arredonda valor
 * @param  {float} num      valor a arredondar
 * @param  {int} decimals 	nr de casas
 * @return {float}          valor arredondado
 */
function precise_round(num,decimals){
	return Math.round(num*Math.pow(10,decimals))/Math.pow(10,decimals);
}

/**
 * Gera timestamp
 * @return {string}  	timestamp
 */
function get_time_stamp() {
	var now = new Date();
	return ((now.getMonth() + 1) + '/' + (now.getDate()) + '/' + now.getFullYear() + " " + now.getHours() + ':'
		+ ((now.getMinutes() < 10) ? ("0" + now.getMinutes()) : (now.getMinutes())) + ':' + ((now.getSeconds() < 10) ? ("0" + now
			.getSeconds()) : (now.getSeconds())));
}

/**
 * Descobre o nome de um ficheiro de um caminho
 * @param  {string} path 	caminho
 * @return {string}      	nome do ficheiro
 */
function extract_filename(path) {
	if (path.substr(0, 12) == "C:\\fakepath\\")
	    return path.substr(12); // modern browser
	var x;
	x = path.lastIndexOf('/');
	  if (x >= 0) // Unix-based path
	  	return path.substr(x+1);
	  x = path.lastIndexOf('\\');
	  if (x >= 0) // Windows-based path
	  	return path.substr(x+1);
	  return path; // just the filename
}


 /**
  * Verifica a validade de um email
  * @param  {string} email  o email a validar
  * @return {boolean} se o email é valido ou nao
  */
function valida_email(email) {
	var reEmail = /^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!\.)){0,61}[a-zA-Z0-9]?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9\-](?!$)){0,61}[a-zA-Z0-9]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/;
	if(!email.match(reEmail)) {
		//console.log("Email errado");
		return false;
	}
	//console.log("Email correcto");
	return true;
}


/**
 * Valida a password. Mais tarde deve-se acrescentar outras verificações se necessário.
 * @param  {string} password  a password a validar
 * @return {boolean}          se a password é valida
 */
function valida_password(password) {
	var password = String(password);
	var tamanho_password = password.length;

	if (tamanho_password > 3) {
		return true;
	}else{
		return false;
	}
}

/**
 * Valida a nome. Mais tarde deve-se acrescentar outras verificações se necessário.
 * @param  {string} nome  a nome a validar
 * @return {boolean}          se a nome é valido
 */
function valida_nome(nome) {
	var nome = String(nome);
	var tamanho_nome = nome.length;

	if (tamanho_nome > 2) {
		return true;
	}else{
		return false;
	}
}


/* repeatString() returns a string which has been repeated a set number of times */
function repeatString(str, num) {
    out = '';
    for (var i = 0; i < num; i++) {
        out += str;
    }
    return out;
}

/*
dump() displays the contents of a variable like var_dump() does in PHP. dump() is
better than typeof, because it can distinguish between array, null and object.
Parameters:
  v:              The variable
  howDisplay:     "none", "body", "alert" (default)
  recursionLevel: Number of times the function has recursed when entering nested
                  objects or arrays. Each level of recursion adds extra space to the
                  output to indicate level. Set to 0 by default.
Return Value:
  A string of the variable's contents
Limitations:
  Can't pass an undefined variable to dump().
  dump() can't distinguish between int and float.
  dump() can't tell the original variable type of a member variable of an object.
  These limitations can't be fixed because these are *features* of JS. However, dump()
*/
function dump(v, howDisplay, recursionLevel) {
    howDisplay = (typeof howDisplay === 'undefined') ? "alert" : howDisplay;
    recursionLevel = (typeof recursionLevel !== 'number') ? 0 : recursionLevel;


    var vType = typeof v;
    var out = vType;

    switch (vType) {
        case "number":
            /* there is absolutely no way in JS to distinguish 2 from 2.0
            so 'number' is the best that you can do. The following doesn't work:
            var er = /^[0-9]+$/;
            if (!isNaN(v) && v % 1 === 0 && er.test(3.0))
                out = 'int';*/
        case "boolean":
            out += ": " + v;
            break;
        case "string":
            out += "(" + v.length + '): "' + v + '"';
            break;
        case "object":
            //check if null
            if (v === null) {
                out = "null";

            }
            //If using jQuery: if ($.isArray(v))
            //If using IE: if (isArray(v))
            //this should work for all browsers according to the ECMAScript standard:
            else if (Object.prototype.toString.call(v) === '[object Array]') {
                out = 'array(' + v.length + '): {\n';
                for (var i = 0; i < v.length; i++) {
                    out += repeatString('   ', recursionLevel) + "   [" + i + "]:  " +
                        dump(v[i], "none", recursionLevel + 1) + "\n";
                }
                out += repeatString('   ', recursionLevel) + "}";
            }
            else { //if object
                sContents = "{\n";
                cnt = 0;
                for (var member in v) {
                    //No way to know the original data type of member, since JS
                    //always converts it to a string and no other way to parse objects.
                    sContents += repeatString('   ', recursionLevel) + "   " + member +
                        ":  " + dump(v[member], "none", recursionLevel + 1) + "\n";
                    cnt++;
                }
                sContents += repeatString('   ', recursionLevel) + "}";
                out += "(" + cnt + "): " + sContents;
            }
            break;
    }

    if (howDisplay == 'body') {
        var pre = document.createElement('pre');
        pre.innerHTML = out;
        document.body.appendChild(pre)
    }
    else if (howDisplay == 'alert') {
        alert(out);
    }

    return out;
}
