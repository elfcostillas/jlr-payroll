function swal_success(e){
	Swal.fire({
	  //position: 'top-end',
	  icon: 'success',
	  title: 'Record has been successfully saved.',
	  showConfirmButton: false,
	  timer: 1000
	});	
}

function swal_error(e){
	
	var errObj = e.responseJSON;
	 
	const keys = Object.keys(errObj);
	var errMsg='';
	  for (const key of keys) {
		//console.log(key +":"+ errObj[key] );
		errMsg += errObj[key]+'\n';
	  }
	
	  Swal.fire({
		icon: 'error',
		title: 'Oops...',
		text: errMsg,
		footer: '<a href>Why do I have this issue?</a>'
	  });
  }
  
  function custom_error(e){
	var errMsg=e;
	  Swal.fire({
		icon: 'error',
		title: 'Oops...',
		text: errMsg,
		footer: '<a href>Why do I have this issue?</a>'
	  });
  }

  function read(url,viewModel){
	$.get(url,function(data){
						  
	})
	.done(function(e) {
		$.each(e,function(index,value){
			viewModel.form.model.set(index,value);
		});
		viewModel.callBack();
	})
	.fail(function(e) {
		
	});
  }
  
  function pad (str, max) {
	str = str.toString();
	return str.length < max ? pad("0" + str, max) : str;
  }