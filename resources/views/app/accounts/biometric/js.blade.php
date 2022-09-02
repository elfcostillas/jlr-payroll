@section('jquery')
    <script>
        $(document).ready(function(){
            
            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        biometric_id : null
                    }
                },
                buttonHandler : {
                    save : function() {
                        $.post('biometric/save',{
                            biometric_id : $('#biometric_id').val()
                        },function(data){
                            swal_success(data);
                        });
                    }
                }
                ,callBack : function(){

                }
            });

            read('biometric/get-id',viewModel);

            $('#biometric_id').kendoTextBox({});
            
            kendo.bind($("#viewModel"),viewModel);
            
        });
    </script>

@endsection