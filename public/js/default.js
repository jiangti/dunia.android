$(document).ready(function() {
	
    $(".ajaxForm input[type='submit']").live('click', function() {
    	var form      = $(this).closest('form');
    	var dialog    = $(form).closest('div.ui-dialog-content');
    	var dataParam = $(form).serialize();
    	$.ajax({
    	    type       : "POST",
    		url        : $(form).attr('action'),
    		data       : dataParam,
    		beforeSend : function(data){  
    			$(dialog).html('<label>Sending</label>');  
    		},
    		success : function(data) {
    		    if(data) {
    		        $(dialog).html(data);
    		    }
    		    else {
    		        $(dialog).dialog('close');
    		    }
    		},
    		error : function(data) {
    			alert(data);
    		}
    	});
    	return false;
    });
    
    /**
     * Ajax links.
     * An anchor tag with class 'ajax' will be treated as an ajax request. The address
     * contained in the 'href' attribute will be the request url. This way we ensure that
     * if JavaScript is disabled in the browser the link will still be followed.
     */
    $("a.ajax").live('click', function() {
    	$.ajax({
    		url      : $(this).attr('href'),
    		dataType : 'json',
    		success  : function(data) {
    				       $('#' + data.id).html(data.html);
    				   }
    	});
    	return false;
    });
    
    /**
     * Rounded divs
     */
    $('.rounded').corner();
});

String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};

