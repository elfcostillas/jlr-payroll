@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                buttonHandler : {
                    pdf : function(e){

                    },
                    web : function(e){

                    }
                }
            });

            
            
            
            

            $("#posted_period").kendoDropDownList({
                
            });

            $("#division_id").kendoDropDownList({
                
            });

            $("#department_id").kendoDropDownList({
                
            });

            $("#biometric_id").kendoComboBox({

            });

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'printBtn', type: "button", text: "Print PDF", icon: 'print', click : viewModel.buttonHandler.pdf },
                    { id : 'webBtn', type: "button", text: "View Web", icon: 'print', click : viewModel.buttonHandler.web },
                ]
            });
        });
    </script>
@endsection