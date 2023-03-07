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
                        // id : null,
                        // firstname: null,
                        // lastname: null,
                        // middlename: null,
                        // suffixname: null,
                        // biometric_id: null,
                        // primary_addr: null,
                        // secondary_addr: null,
                        // remarks: null,
                        // sss_no: null,
                        // deduct_sss: null,
                        // tin_no: null,
                        // phic_no: null,
                        // deduct_phic: null,
                        // hdmf_no: null,
                        // deduct_hdmf: null,
                        // hdmf_contri: null,
                        // civil_status: null,
                        // gender: null,
                        // birthdate: null,
                        // employee_stat: null,
                        // bank_acct: null,
                        // basic_salary: null,
                        // is_daily: null,
                        // exit_status: null,
                        // contact_no : null,
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
                    view : function()
                    {   
                        let from =  kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let to =  kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd');
                        let div = $('#division_id').data('kendoDropDownList').value();
                        let dept = $('#department_id').data('kendoDropDownList').value();

                        let url = `tardiness-reports/generate-detailed/${from}/${to}/${div}/${dept}`;

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

            $("#division_id").kendoDropDownList({
                dataTextField: "div_name",
                dataValueField: "id",
                dataSource: viewModel.ds.division,
                //index: 1,
                change: function(e){
                    let selected = e.sender.dataItem();
                    let deptUrl = `../employee-files/divisions-departments/department/list-option/${selected.id}`;
                    viewModel.ds.department.transport.options.read.url = deptUrl;
                    viewModel.ds.department.read();
                },
                optionLabel: {
                    div_name: "ALL",
                    id: "0"
                }
            });

            $("#department_id").kendoDropDownList({
                dataTextField: "dept_name",
                dataValueField: "id",
                dataSource: viewModel.ds.department,
                //index: 1,
                change: function(e){
                    // let selected = e.sender.dataItem();
                    // let deptUrl = `../employee-files/divisions-departments/department/list-option/0`;
                    // viewModel.ds.department.transport.options.read.url = deptUrl;
                    // viewModel.ds.department.read();
                },
                optionLabel: {
                    dept_name: "ALL",
                    id: "0"
                }
            });

            $("#date_from").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
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