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

            let obj = {
                    id : null,
                    firstname: null,
                    lastname: null,
                    middlename: null,
                    suffixname: null,
                    biometric_id: null,
                    primary_addr: null,
                    secondary_addr: null,
                    remarks: null,
                    sss_no: null,
                    deduct_sss: null,
                    tin_no: null,
                    phic_no: null,
                    deduct_phic: null,
                    hdmf_no: null,
                    deduct_hdmf: null,
                    hdmf_contri: null,
                    civil_status: null,
                    gender: null,
                    birthdate: null,
                    employee_stat: null,
                    bank_acct: null,
                    basic_salary: null,
                    is_daily: null,
                    exit_status: null,
                    contact_no : null,
                    division_id: null,
                    dept_id: null,
            };

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        biometric_id : null,
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
                                url : '../timekeeping/leave-credits/year',
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
                    employeecombobox : new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'attendance/employee-list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            }
                        },
                        // serverPaging : true,
                        // serverFiltering : true,
                        // pageSize : 15,
                        schema : {
                            data : "data",
                            total : "total",
                            model : {
                                id : "biometric_id",
                                fields : {
                                    employee_name : { type : 'string' },
                                }
                            }
                        }
                    }),
                    
                },
                buttonHandler : {  
                    download : function()
                    {   
                        // let from =  kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        // let to =  kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        // let url = `leave-reports/generate/${from}/${to}`;

                        // window.open(url);
                    },
                    view : function()
                    {   
                        let from =  kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        // let div = $('#division_id').data('kendoDropDownList').value();
                        // let dept = $('#department_id').data('kendoDropDownList').value();

                        let url = `attendance/generate-detailed/${from}/${to}`;

                        window.open(url);
                    },
                    summarize : function()
                    {
                        let from =  kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let div = $('#division_id').data('kendoDropDownList').value();
                        let dept = $('#department_id').data('kendoDropDownList').value();
                        
                        if(from==null || to ==null){
                            custom_error('Please select date from and date to.');
                        } else {
                            let url = `tardiness-reports/generate-summary/${from}/${to}/${div}/${dept}`;

                            window.open(url);
                        }
                        
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
                    downloadDTR : function(e){
                        // let date_from = $("#date_from_dtr").data("kendoDatePicker").value();
                        // let date_to = $("#date_to_dtr").data("kendoDatePicker").value();
                        let biometric_id =  $("#biometric_id").data("kendoComboBox").value();
                        let date_from = kendo.toString($('#date_from_dtr').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let date_to = kendo.toString($('#date_to_dtr').data('kendoDatePicker').value(),'yyyy-MM-dd');

                        let url = `attendance/download-dtr/${biometric_id}/${date_from}/${date_to}`;

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

            // $("#division_id").kendoDropDownList({
            //     dataTextField: "div_name",
            //     dataValueField: "id",
            //     dataSource: viewModel.ds.division,
            //     //index: 1,
            //     change: function(e){
            //         let selected = e.sender.dataItem();
            //         let deptUrl = `../employee-files/divisions-departments/department/list-option/${selected.id}`;
            //         viewModel.ds.department.transport.options.read.url = deptUrl;
            //         viewModel.ds.department.read();
            //     },
            //     optionLabel: {
            //         div_name: "ALL",
            //         id: "0"
            //     }
            // });

            // $("#department_id").kendoDropDownList({
            //     dataTextField: "dept_name",
            //     dataValueField: "id",
            //     dataSource: viewModel.ds.department,
            //     //index: 1,
            //     change: function(e){
            //         // let selected = e.sender.dataItem();
            //         // let deptUrl = `../employee-files/divisions-departments/department/list-option/0`;
            //         // viewModel.ds.department.transport.options.read.url = deptUrl;
            //         // viewModel.ds.department.read();
            //     },
            //     optionLabel: {
            //         dept_name: "ALL",
            //         id: "0"
            //     }
            // });

            $("#date_from").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_from_dtr").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to_dtr").kendoDatePicker({
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

            $("#biometric_id").kendoComboBox({
                dataSource : viewModel.ds.employeecombobox,
                dataTextField: "employee_name",
                dataValueField: "biometric_id",
                filter : "contains",
                autoWidth : true,
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