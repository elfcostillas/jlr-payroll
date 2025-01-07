@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createEmployee" > <span class="k-icon k-i-plus k-button-icon"></span>Create Employee</button>
    </script>
   
    <script>
        $(document).ready(function(){

            

            let months = [
                { text: "January", value: "1" },
                { text: "February", value: "2" },
                { text: "March", value: "3" },
                { text: "April", value: "4" },
                { text: "May", value: "5" },
                { text: "June", value: "6" },
                { text: "July", value: "7" },
                { text: "August", value: "8" },
                { text: "September", value: "9" },
                { text: "October", value: "10" },
                { text: "November", value: "11" },
                { text: "December", value: "12" },
            ];

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        // id : null,
                      
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
                    fy : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../reports/sg-contributions/year',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                           
                            model : {
                                id : 'fy',
                                fields : {
                                    fy : { type : 'number',editable :false },   
                                    
                                }
                            }
                        }
                    }),
                    
                },
                buttonHandler : {  
                    download : function()
                    {   
                        let m = $("#scripts_months").data("kendoDropDownList").value();
                        let y = $("#scripts_year").data("kendoDropDownList").value();

                        let url = `sg-contributions/generate/${y}/${m}`;

                        window.open(url);
                    },
                   
                    viewYearly : function(e){
                        let year = $("#tardy_year").data("kendoDropDownList").value();
                        let url = `tardiness-reports/yearly-tardiness/${year}`;

                        window.open(url);
                    },
                    runTardy : function(e){
                       
                        let m = $("#scripts_months").data("kendoDropDownList").value();
                        let y = $("#scripts_year").data("kendoDropDownList").value();

                        let url = `attendance/tardy-setter/${y}/${m}`;

                        window.open(url);
                    },
                    runAWOL : function(e){
                        let m = $("#scripts_months").data("kendoDropDownList").value();
                        let y = $("#scripts_year").data("kendoDropDownList").value();

                        let url = `attendance/awol-setter/${y}/${m}`;

                        window.open(url);
                    },

                    // leaveByEmployee : function()
                    // {
                    //     let from =  kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd');
                    //     let to =  kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd');
                    //     let url = `leave-reports/generate-by-employee/${from}/${to}`;

                    //     window.open(url);
                    // }
            
                },
                functions : {

                },
                callBack : function()
                {
                   
                }
            });


            $("#date_from").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#scripts_year").kendoDropDownList({
                dataSource: viewModel.ds.fy,
                dataTextField: "fy",
                dataValueField: "fy",
                index : -1,
                change : function(e){
                    //console.log(e.sender.value())
                }
            });

            $("#scripts_months").kendoDropDownList({
                dataSource: months,
                dataTextField: "text",
                dataValueField: "value",
                index : -1,
                change : function(e){
                    //console.log(e.sender.value())
                }
            });

            function process()
            {
                // $.get('employee-report/generate',{
                //     division : $("#division_id").data("kendoDropDownList").value()
                // },function(){

                // });
                let div =  $("#division_id").data("kendoDropDownList").value();
                

                let url = `employee-report/generate?division=${div}`;
                window.open(url);
            }
            
            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection