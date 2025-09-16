@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createEmployee" > <span class="k-icon k-i-plus k-button-icon"></span>Create Employee</button>
    </script>
   
    <script>
        $(document).ready(function(){

            let obj = {
                    id : null,
                    firstname: null,
                    lastname: null,
                    middlename: null,
                 
            };

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                       
                        division_id: null,
                        dept_id: null,
                    },
                  
                },
                ds : {
                   
                    division : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../employee-files/divisions-departments/division/get-divisions',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                            model : {
                                id : 'id',
                                fields : {
                                    div_code : { type : 'string' },
                                    div_name : { type : 'string' },
                                }
                            }
                        }
                    }),
                    department : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../employee-files/divisions-departments/department/list-option/1',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                            model : {
                                id : 'id',
                                fields : {
                                    div_code : { type : 'string' },
                                    div_name : { type : 'string' },
                                }
                            }
                        }
                    }),
                    
                },
                buttonHandler : {  
                    download : function()
                    {   
                        let from =  kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let url = `leave-reports/generate/${from}/${to}`;

                        window.open(url);
                    },
                 
                    viewSummary : function()
                    {   
                        // let filtered = ($("#isMoreThan").is(':checked')) ? 'Y' : 'N';
                        let from =  kendo.toString($('#date_from2').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to2').data('kendoDatePicker').value(),'yyyy-MM-dd');

                        let hr1 = $("#range1").data('kendoNumericTextBox').value();
                        let hr2 = $("#range2").data('kendoNumericTextBox').value();

                        let url = `man-hours/generate/${from}/${to}/${hr1}/${hr2}`;

                        if(from==null || to ==null){
                            custom_error('Please select date from and date to.');
                        } else {
                            window.open(url);
                        }
                    },

                    viewPDF : function()
                    {   
                        let from =  kendo.toString($('#date_from2').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to2').data('kendoDatePicker').value(),'yyyy-MM-dd');

                        let hr1 = $("#range1").data('kendoNumericTextBox').value();
                        let hr2 = $("#range2").data('kendoNumericTextBox').value();

                        let url = `man-hours/pdf/${from}/${to}/${hr1}/${hr2}`;

                        if(from==null || to ==null){
                            custom_error('Please select date from and date to.');
                        } else {
                            window.open(url);
                        }
                    },
                    viewSummaryOT : function()
                    {   
                        // let filtered = ($("#isMoreThan").is(':checked')) ? 'Y' : 'N';
                         
                        let from =  kendo.toString($('#date_from3').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to3').data('kendoDatePicker').value(),'yyyy-MM-dd');

                        let hr1 = $("#range3").data('kendoNumericTextBox').value();
                        let hr2 = $("#range4").data('kendoNumericTextBox').value();

                        let url = `man-hours/generate-ot/${from}/${to}/${hr1}/${hr2}`;

                        if(from==null || to ==null){
                            custom_error('Please select date from and date to.');
                        } else {
                            window.open(url);
                        }
                    },

                    viewPDFOT : function()
                    {   
                        let from =  kendo.toString($('#date_from3').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to3').data('kendoDatePicker').value(),'yyyy-MM-dd');

                        let hr1 = $("#range3").data('kendoNumericTextBox').value();
                        let hr2 = $("#range4").data('kendoNumericTextBox').value();

                        let url = `man-hours/pdf-ot/${from}/${to}/${hr1}/${hr2}`;

                        if(from==null || to ==null){
                            custom_error('Please select date from and date to.');
                        } else {
                            window.open(url);
                        }
                    }
            
                },
                functions : {

                },
                callBack : function()
                {

                }
            });

            
            $("#date_from2").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to2").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#range1").kendoNumericTextBox({ restrictDecimals : true , decimals :0 });
            $("#range2").kendoNumericTextBox({ restrictDecimals : true, decimals :0 });

            
            $("#date_from3").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to3").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#range3").kendoNumericTextBox({ decimals :0});
            $("#range4").kendoNumericTextBox({ decimals :0});


            function process()
            {
            
                let div =  $("#division_id").data("kendoDropDownList").value();
                let url = `employee-report/generate?division=${div}`;
                window.open(url);
            }
            
            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection