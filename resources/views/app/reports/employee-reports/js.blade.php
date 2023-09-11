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
                        process();
                    },
                    download_weekly : function()
                    {
                        process2();
                    },

            
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

            $("#dept_id").kendoDropDownList({
                dataTextField: "dept_name",
                dataValueField: "id",
                dataSource: viewModel.ds.department,
                index: 1,
                //change: onChange
            });

            function process()
            {
              
                let div =  $("#division_id").data("kendoDropDownList").value();
                let url = `employee-report/generate?division=${div}`;
                window.open(url);
            }

            function process2()
            {
               
                let div =  $("#division_id").data("kendoDropDownList").value();
                let url = `employee-report/generate-weekly?division=${div}`;
                window.open(url);
            }
            
            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection