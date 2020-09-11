/***********************************************************************************/
/*** CUSTOM MODAL ********************************/
/***********************************************************************************/

function deleteConfirmation(url,id,title,text,isAjax,parentid){
    
        if (typeof parentid == 'undefined') {
            parentid = 0;
        }
        
	if(isAjax=='true'){
		$('#deleteConfirmationAjax').modal('show');
		$('#deleteConfirmationAjax #deleteModalAjax').attr('action',url);
		$('#deleteConfirmationAjax .delete_id input').val(id);
                $('#deleteConfirmationAjax .parent_id input').val(parentid);
		$('#deleteConfirmationAjax .modal-title').html('<i class="fa fa-trash"></i> '+title);
		$('#deleteConfirmationAjax .modal-text').html(text);
	}else{
		$('#deleteConfirmation').modal('show');	
		$('#deleteConfirmation #deleteModal').attr('action',url);
		$('#deleteConfirmation .delete_id input').val(id);
                $('#deleteConfirmation .parent_id input').val(parentid);
		$('#deleteConfirmation .modal-title').html('<i class="fa fa-trash"></i> '+title);
		$('#deleteConfirmation .modal-text').html(text);
	}	
}

function deleteAllConfirmation(url,id,title,text,isAjax,errorMsg){
	//Get All Selected Checkboxe ids
	//'#'+id+'_datatable_ajax'	
	var selected = '';
	jQuery('#'+id+'_datatable_ajax input:checked').each(function() {
		if($(this).val()!='on'){
			if(selected==''){
				selected = $(this).val();		
			}else{
				selected = selected + ',' + $(this).val();
			}
		}
	});
	
	if(selected!=""){
		$('#deleteConfirmation .btn-danger').show();
		
		if(isAjax=='true'){
			$('#deleteConfirmationAjax').modal('show');	
			$('#deleteConfirmationAjax #deleteModalAjax').attr('action',url);
			$('#deleteConfirmationAjax .delete_id input').val(selected);
			$('#deleteConfirmationAjax .modal-title').html('<i class="fa fa-trash"></i> '+title);
			$('#deleteConfirmationAjax .modal-text').html(text);
		}else{		
			$('#deleteConfirmation').modal('show');	
			$('#deleteConfirmation #deleteModal').attr('action',url);
			$('#deleteConfirmation .delete_id input').val(selected);
			$('#deleteConfirmation .modal-title').html('<i class="fa fa-trash"></i> '+title);
			$('#deleteConfirmation .modal-text').html(text);
		}
	}
	else{
		$('#deleteConfirmation .btn-danger').hide();
		$('#deleteConfirmation').modal('show');	
		$('#deleteConfirmation .modal-title').html(title+' <i class="fa fa-info"></i>');		
		$('#deleteConfirmation .modal-text').html(errorMsg);
	}
}

/***********************************************************************************/
/*** END CUSTOM MODAL ********************************/
/***********************************************************************************/
