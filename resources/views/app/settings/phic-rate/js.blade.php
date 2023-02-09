@section('jquery')
    <script>
        $(document).ready(function(){
            
            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        rate : null
                    }
                },
                buttonHandler : {
                    save : function() {
                        $.post('philhealth/save',{
                            rate : $('#rate').val()
                        },function(data){
                            swal_success(data);
                        });
                    }
                }
                ,callBack : function(){

                }
            });

            read('philhealth/get-rate',viewModel);

            $('#rate').kendoTextBox({});
            
            kendo.bind($("#viewModel"),viewModel);
            
        });
    </script>

@endsection