@section('jquery')
    <script>
        $(document).ready(function() {
            let years =<?php echo json_encode($years) ?>;
            let quarters =<?php echo json_encode($quarters) ?>;

            console.log(quarters);

            var viewModel = kendo.observable({ 
                buttonHandler : {
                    qpip_sg_web : function(e){
                        let year = $("#year_1").val();
                        let quarter = $("#quarter_1").val();
                        window.open("{{ route('qpip_sg_web') }}?year="+year+"&quarter="+quarter, '_blank');
                    },
                    qpip_ranknfile_web : function(e){
                        let year = $("#year_2").val();
                        let quarter = $("#quarter_2").val();
                        window.open("{{ route('qpip_ranknfile_web') }}?year="+year+"&quarter="+quarter, '_blank');
                    },
                    qpip_confi_web : function(e){
                        let year = $("#year_3").val();
                        let quarter = $("#quarter_3").val();
                        window.open("{{ route('qpip_confi_web') }}?year="+year+"&quarter="+quarter, '_blank');
                    }
                }
            });

            kendo.bind($("#viewModel"),viewModel);

            $("#year_1").kendoDropDownList({
                dataTextField: "dtr_year",
                dataValueField: "dtr_year",
                dataSource: years,
                index: 0,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#quarter_1").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: quarters,
                index: 0,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#year_2").kendoDropDownList({
                dataTextField: "dtr_year",
                dataValueField: "dtr_year",
                dataSource: years,
                index: 0,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#quarter_2").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: quarters,
                index: 0,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#year_3").kendoDropDownList({
                dataTextField: "dtr_year",
                dataValueField: "dtr_year",
                dataSource: years,
                index: 0,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            
            $("#quarter_3").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: quarters,
                index: 0,
                dataBound : function(e){
                  
                }
                //change: onChange
            });
          
        });
    </script>
@endsection