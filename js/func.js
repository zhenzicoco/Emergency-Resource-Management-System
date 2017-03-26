////// functions
function check_empty(obj, error_text)
{
	if(obj.val().length <= 0 )
	{
		return prevent(obj,true, error_text);	
	}else{
		return prevent(obj,false, error_text);
	}
}

// check type of the value
function check_type(obj, type, error_text)
{
	/*
	if( type == 'float'){
		if(obj.val().match( /^-{0,1}[0-9]{1,3}\.[0-9]{1,9}$/) == null ){
			return prevent(obj,true, error_text);
		}
	}
	*/

	if( type == 'float' ){
		if(obj.val().match(/^-?\d*\.{0,1}\d+$/) == null ){
			return prevent(obj,true, error_text);
		}
	}

	if( type == 'int'){
		if(obj.val().match( /^[0-9]+$/ ) == null ){
			return prevent(obj,true, error_text);
		}
	}
	
	if( type == 'string'){
		if(obj.val().match( /^[a-z][A-Z]+$/ ) == null){
			return prevent(obj,true, error_text);
		}
	}

	if( type == 'date' ){
		if( obj.val().match(/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/) == null ){
			return prevent(obj, true, error_text);
		}
	}

	return prevent(obj,false);
}

//check range
function check_range(obj, min, max, error_text)
{
	if( obj.val() >= min && obj.val() <= max ){
		return prevent(obj, false, error_text);
	}else{
		return prevent(obj, true, error_text);
	}
}


//change the disable of form and display error_text
function prevent(obj, flag, error_text)
{
	if(flag){
		$('input:submit').attr('disabled',true);
		obj.parent().next().find('font').text(error_text);
		return false
	}else{
		
		$('input:submit').attr('disabled',false);
		obj.parent().next().find('font').text('');
		return true;
	}
}	
