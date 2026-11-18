@section('jquery')
    <script>
        $(document).ready(function(){

            let payroll_periods = <?php echo json_encode($payroll_periods) ?>;
        
            var viewModel = kendo.observable({
                ds : {

                },
                buttonHandler : {
                    canteenReport : function()
                    {
                        let period =  $("#payroll_period").data("kendoDropDownList").value();
                        let url = `canteen/print/${period}`;
                        window.open(url);
                    },
                    canteenReportHR : function()
                    {
                        let period =  $("#payroll_period").data("kendoDropDownList").value();
                        let url = `canteen/print-cover/${period}`;
                        window.open(url);
                    },
                }

            });

            $("#payroll_period").kendoDropDownList({
                dataTextField: "label",
                dataValueField: "jlr_payroll_period",
                dataSource: payroll_periods,
                index: 0,
                dataBound : function(e){
                  
                }
                
            });

            kendo.bind($("#viewModel"),viewModel);
        });
    </script>
@endsection