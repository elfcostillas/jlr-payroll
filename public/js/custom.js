// var textboxPlugin = (function (init) {
// 	return kendo.ui.TextBox.extend({
// 		init: function (element, options) {
// 			var that = this;
// 			// The base call to initialize the widget.
// 			init.call(that, element, options);

// 			that.element.on("keyup", $.proxy(that._keyup, that));
// 			//that.element.on("keydown", $.proxy(that._keydown, that));
// 		},
// 		options: {
// 			name: "TextBox",
// 			autoBind: true
// 		},
// 		events: ["keyup"],
// 		_keyup: function () {
// 			var that = this;
// 			that.trigger("keyup")
// 		},
// 		// events: ["keydown"],
// 		// _keydown: function () {
// 		// 	var that = this;
// 		// 	that.trigger("keydown")
// 		// }
// 	});
//   })(kendo.ui.TextBox.fn.init);
//   kendo.ui.plugin(textboxPlugin);
  
function swal_success(e){
	Swal.fire({
	  //position: 'top-end',
	  icon: 'success',
	  title: 'Record has been successfully saved.',
	  showConfirmButton: false,
	  timer: 1000
	});	
}

// function swal_error(e){
	
// 	var errObj = e.responseJSON;
	 
// 	const keys = Object.keys(errObj);
// 	var errMsg='';
// 	  for (const key of keys) {
// 		//console.log(key +":"+ errObj[key] );
// 		errMsg += errObj[key]+'\n';
// 	  }
	
// 	  Swal.fire({
// 		icon: 'error',
// 		title: 'Oops...',
// 		text: errMsg,
// 		footer: '<a href>Why do I have this issue?</a>'
// 	  });
//   }

function swal_error(e){
	
	var errObj = e.responseJSON;
	 
	const keys = Object.keys(errObj);
	var errMsg='';
	  for (const key of keys) {
		//console.log(key +":"+ errObj[key] );
		if(typeof(errObj[key])==='object'){
			let newObj=errObj[key];
			const keys2 = Object.keys(newObj);
			for (const key2 of keys2) {
				errMsg += newObj[key2]+'\n';
			}
		}else{
			errMsg += errObj[key]+'\n';
		}
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