@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                ds : {
                    payperiod : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'payslip-weekly/posted-period',
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
                                    period_id : { number : 'string' },
                                    date_range : { type : 'string' },
                                }
                            }
                        }
                    }),
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
                                url : '../employee-files/divisions-departments/department/list-option/0',
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
                    employees : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'payslip-weekly/get-employees/0/0/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                            model : {
                                id : 'biometric_id',
                                fields : {
                                    emp_name : { type : 'string' },
                                   // firstname : { type : 'string' },
                                    suffixname : { type : 'string' },
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {
                    pdf : function(e){
                        
                    },
                    web : function(e){
                        let period = $("#posted_period").data("kendoDropDownList").value();
                        let division = ($("#division_id").data("kendoDropDownList").value()=='') ? 0 : $("#division_id").data("kendoDropDownList").value();
                        let department = ($("#department_id").data("kendoDropDownList").value()=='') ? 0 : $("#department_id").data("kendoDropDownList").value();
                        let biometric_id = ($("#biometric_id").data("kendoComboBox").value()=='') ? 0 : $("#biometric_id").data("kendoComboBox").value();

                        // let department = $("#department_id").data("kendoDropDownList").value(); 
                        // let biometric_id = ($("#biometric_id").data("kendoComboBox").value()!="") ? $("#biometric_id").data("kendoComboBox").value() : 0;

                        let url = `payslip-weekly/web-view/${period}/${division}/${department}/${biometric_id}`;
                        window.open(url);
                    },
                    dtr : function(e){
                        let period = $("#posted_period").data("kendoDropDownList").value();
                        let division = ($("#division_id").data("kendoDropDownList").value()=='') ? 0 : $("#division_id").data("kendoDropDownList").value();
                        let department = ($("#department_id").data("kendoDropDownList").value()=='') ? 0 : $("#department_id").data("kendoDropDownList").value();
                        let biometric_id = ($("#biometric_id").data("kendoComboBox").value()=='') ? 0 : $("#biometric_id").data("kendoComboBox").value();

                        // let department = $("#department_id").data("kendoDropDownList").value(); 
                        // let biometric_id = ($("#biometric_id").data("kendoComboBox").value()!="") ? $("#biometric_id").data("kendoComboBox").value() : 0;

                        let url = `payslip-weekly/dtr-summary/${period}/${division}/${department}/${biometric_id}`;
                        window.open(url);
                    }
                }
            });

            $("#posted_period").kendoDropDownList({
                dataTextField: "date_range",
                dataValueField: "period_id",
                dataSource: viewModel.ds.payperiod,
                index: 0,
                change: function(e){
                  
                },
                
            });

            $("#division_id").kendoDropDownList({
                dataTextField: "div_name",
                dataValueField: "id",
                dataSource: viewModel.ds.division,
                index: 0,
                change: function(e){
                    let selected = e.sender.dataItem();
                    let deptUrl = `../employee-files/divisions-departments/department/list-option/${selected.id}`;
                    viewModel.ds.department.transport.options.read.url = deptUrl;
                    viewModel.ds.department.read();
                },
                optionLabel: {
                    id: 0,
                    div_name: "ALL"
                }
            });

            $("#department_id").kendoDropDownList({
                dataTextField: "dept_name",
                dataValueField: "id",
                dataSource: viewModel.ds.department,
                ///index: 1,
                dataBound : function(e){
                    
                    
                },
                change: function(e){

                    let period = $("#posted_period").data("kendoDropDownList").value();
                    let division = $("#division_id").data("kendoDropDownList").value();
                    let department = $("#department_id").data("kendoDropDownList").value(); 

                    let url = `payslip-weekly/get-employees/${period}/${division}/${department}`;
                    viewModel.ds.employees.transport.options.read.url = url;
                    viewModel.ds.employees.read();
                },
                optionLabel: {
                    id: 0,
                    dept_name: "ALL"
                }
            });

            $("#biometric_id").kendoComboBox({
                dataTextField: "emp_name",
                dataValueField: "biometric_id",
                dataSource: viewModel.ds.employees,
                ///index: 1,
                //template: "#= lastname # , #= firstname #",
                filter : "contains",
                dataBound : function(e){
                    
                    
                },
                change: function(e){
                       
                },
                optionLabel: {
                    biometric_id : 0,
                    lastname: "ALL"
                }
            });

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'printBtn', type: "button", text: "Print PDF", icon: 'print', click : viewModel.buttonHandler.pdf },
                    { id : 'webBtn', type: "button", text: "View Web", icon: 'print', click : viewModel.buttonHandler.web },
                    { id : 'dtrBtn', type: "button", text: "DTR Summary", icon: 'table', click : viewModel.buttonHandler.dtr },
                ]
            });
        });
    </script>
@endsection