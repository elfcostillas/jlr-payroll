@section('jquery')
    <script>

        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                buttonHandler : { 
                    download : function(e){
                        let from =  kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        
                        let url = 'att/download';

                        $.post(url,{
                            from : from,
                            to : to
                        },function(data){
                            swal_success(data);
                        });
                    }
                }
            });

            $("#date_from").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            kendo.bind($("#viewModel"),viewModel);
        });

        

    </script>

@endsection