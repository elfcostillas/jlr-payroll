@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createEmployee" > <span class="k-icon k-i-plus k-button-icon"></span>Create Leave</button>
    </script>
   
    <script>
        $(document).ready(function(){

            let genderOptions = [
                    { text: "Male", value: "M" },
                    { text: "Female", value: "F" },
                ];
            
                let civilStatusOptions = [
                    { text: "Sinlge", value: 1 },
                    { text: "Married", value: 2 },
                    { text: "Divorced", value: 3 },
                    { text: "Widowed", value: 4 },
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
                    location_id: null,
                    pay_type: null,
                    date_hired : null,
                    emp_level : null,
                    job_title_id : null
            };

            var viewModel = kendo.observable({ 
                selected : null,
                form : {
                    model : {
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
                        location_id: null,
                        pay_type: null,
                        date_hired : null,
                        emp_level : null,
                        job_title_id : null
                    },
                    mirror : {
                        is_daily : false,
                        deduct_sss : false,
                        deduct_hdmf : false,
                        deduct_phic : false,

                    }
                  
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'leave-request/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                          
                        },
                        pageSize :11,
                        serverPaging : true,
                        serverFiltering : true,
                        schema : {
                            data : "data",
                            total : "total",
                            model : {
                                id : 'id',
                                fields : {
                                    biometric_id : { type:"number"},
                                    requesting_emp : { type:"string"},
                                    leave_type_code : { type:"string"},
                                    remarks : { type:"string"},
                                    document_status : {type:"string"},
                                    acknowledge_status : { type:"string"},
                                    approver_emp : { type:"string"},
                                    acknowledge_time : { type:"string"},
                                    hr_emp : { type:"string"},
                                    received_time : { type:"date"},


                                    // "id":2,
                                    // "biometric_id":847,
                                    // "requesting_emp":"Costillas, Elmer  ",
                                    // "leave_type_code":null,
                                    // "remarks":null,
                                    // "document_status":null,
                                    // "acknowledge_status":null,
                                    // "approver_emp":", ",
                                    // "acknowledge_time":null,
                                    // "hr_emp":", ",
                                    // "received_time":null
                                }
                            }
                        }
                    }),
                    division : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'divisions-departments/division/get-divisions',
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
                                url : 'divisions-departments/department/list-option/0',
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
                    location : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../settings/locations/get-locations',
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
                    job_titles : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'employee-master-data/job-titles/0',
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
                                    job_title_name : { type : 'string' },
                                }
                            }
                        }
                    }),
                    
                },
                buttonHandler : {  
                   
                    closePop : function(e){

                    },
                    createEmployee : function(e){
                        viewModel.buttonHandler.clear();
                        viewModel.functions.showPOP();
                    },
                    save : async function(e){

                        await viewModel.functions.reAssignValues();
                        
                        var json_data = JSON.stringify(viewModel.form.model);
                        
                        $.post('employee-master-data/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);
                            
                            // let url  = `employee-master-data/read/${viewModel.selected.id}`;
                            // viewModel.functions.prepareForm(viewModel.selected);
                            // setTimeout(function(){
                            //     read(url,viewModel);
                            // }, 500);
                           

                            viewModel.ds.maingrid.read();
                            //viewModel.maingrid.formReload(data);
                        })
                        .fail(function(data){
                           swal_error(data);
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });
                    },
                    view : async function(e){
                        e.preventDefault(); 

                        viewModel.functions.showPOP();
                       
                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        viewModel.set('selected',data);

                        let url  = `employee-master-data/read/${data.id}`;
                        await viewModel.functions.prepareForm(data);
                        read(url,viewModel);
                        //console.log(data);

                    }, 
                    clear : function(e){
                        //$.each(viewModel.form.model,function(index,value));
                        for (var key in obj) {
                            //console.log(key); //console.log(key + " -> " + p[key]);
                            viewModel.form.model.set(key,null);
                        }

                        viewModel.form.model.set('civil_status',1);
                        viewModel.form.model.set('gender','M');
                        viewModel.form.model.set('location_id',1);
                        viewModel.form.model.set('division_id',1);

                        viewModel.form.model.set('employee_stat',1);
                        viewModel.form.model.set('exit_status',1);
                        viewModel.form.model.set('pay_type',1);
                    }
                },
                functions : {
                    showPOP : function(data){
                       
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "864", //1124 - 1152
                            height: "710",
                            title: "Employee Information",
                            visible: false,
                            animation: false,
                            actions: [
                                "Pin",
                                "Minimize",
                                "Maximize",
                                "Close"
                            ],
                            close: viewModel.buttonHandler.closePop,
                            position : {
                                top : 0
                            }
                        }).data("kendoWindow").center().open();
                        
                    },
                    reAssignValues : function(){
                        viewModel.form.model.set('gender',$('#gender').data('kendoDropDownList').value());
                        viewModel.form.model.set('birthdate',kendo.toString($('#birthdate').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('civil_status',$('#civil_status').data('kendoDropDownList').value());
                        viewModel.form.model.set('division_id',$('#division_id').data('kendoDropDownList').value());
                        viewModel.form.model.set('dept_id',($('#dept_id').data('kendoDropDownList').value()!='') ? $('#dept_id').data('kendoDropDownList').value() : 0 );
                        viewModel.form.model.set('job_title_id',($('#job_title_id').data('kendoDropDownList').value()!='') ? $('#job_title_id').data('kendoDropDownList').value() : null );
                        viewModel.form.model.set('emp_level',($('#emp_level').data('kendoDropDownList').value()!='') ? $('#emp_level').data('kendoDropDownList').value() : null );

                        viewModel.form.model.set('employee_stat',($('#employee_stat').data('kendoDropDownList').value()!='') ? $('#employee_stat').data('kendoDropDownList').value() : 0 );
                        viewModel.form.model.set('exit_status',($('#exit_status').data('kendoDropDownList').value()!='') ? $('#exit_status').data('kendoDropDownList').value() : 0 );
                        viewModel.form.model.set('pay_type',($('#pay_type').data('kendoDropDownList').value()!='') ? $('#pay_type').data('kendoDropDownList').value() : 0 );
                        viewModel.form.model.set('deduct_sss',(viewModel.form.mirror.deduct_sss) ? 'Y':'N');
                        viewModel.form.model.set('deduct_phic',(viewModel.form.mirror.deduct_phic) ? 'Y':'N');
                        viewModel.form.model.set('is_daily',(viewModel.form.mirror.is_daily) ? 'Y':'N');
                        viewModel.form.model.set('date_hired',kendo.toString($('#date_hired').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('location_id',($('#location_id').data('kendoDropDownList').value()!='') ? $('#location_id').data('kendoDropDownList').value() : 0 );
                        //viewModel.form.model.set('deduct_sss',(viewModel.form.model.deduct_sss) ? 'Y':'N');
                        

                    },
                    prepareForm : function(data)
                    {
                       
                        let deptUrl = `divisions-departments/department/list-option/${data.division_id}`;
                        viewModel.ds.department.transport.options.read.url = deptUrl;
                        viewModel.ds.department.read();

                        let jobtitleUrl = `employee-master-data/job-titles/${data.dept_id}`;
                        viewModel.ds.job_titles.transport.options.read.url = jobtitleUrl;
                        viewModel.ds.job_titles.read();
                    }
                   

                },
                callBack : function()
                {
                  
                    viewModel.form.mirror.set('deduct_sss',(viewModel.form.model.deduct_sss=='Y') ? true:false);
                    viewModel.form.mirror.set('deduct_phic',(viewModel.form.model.deduct_phic=='Y') ? true:false);
                    viewModel.form.mirror.set('is_daily',(viewModel.form.model.is_daily=='Y') ? true:false);
                }
            });

            $("#maingrid").kendoGrid({
                dataSource : viewModel.ds.maingrid,
                pageable : {
                    refresh : true,
                    buttonCount : 5
                },
                noRecords: true,
                filterable : {
                    extra: false,
                    operators: {
                        string: {
                            contains : "Contains"
                        }
                    }
                },
                sortable : true,
                height : 550,
                scrollable: true,
                toolbar : [
                    { template: kendo.template($("#template").html()) }
                ],
                //editable : "inline",
                columns : [
                    {
                        title : "Bio ID",
                        field : "biometric_id",
                        width : 80,    
                    },
                    {
                        title : "Employee",
                        field : "requesting_emp",
                       // width : 180,    
                    },
                    {
                        title : "Type",
                        field : "leave_type_code",
                        width : 90,    
                    },
                    // {
                    //     title : "Reason",
                    //     field : "remarks",
                        
                    // },
                    {
                        title : "Status",
                        field : "document_status",
                        width : 90,    
                    },
                    {
                        
                        title : "Approval",
                        field : "acknowledge_status",
                        width : 90,  
                    }, 
                    {
                        title : "Approver",
                        field : "approver_emp",
                        width : 180,  
                    },
                    
                    {
                        title : "Received",
                        field : "hr_emp",
                        width : 180,  
                    },
                   
                    {
                        command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
                ]
            });

            // $("#po_date").kendoDatePicker({
            // format: "MM/dd/yyyy"
            // });
            
            $("#firstname").kendoTextBox({ });
            $("#lastname").kendoTextBox({ });
            $("#middlename").kendoTextBox({ });
            $("#suffixname").kendoTextBox({ });
            $("#primary_addr").kendoTextBox({ });
            $("#secondary_addr").kendoTextBox({ });

            $("#gender").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: genderOptions,
                index: 1,
                //change: onChange
            });

            $("#birthdate").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_hired").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#civil_status").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: civilStatusOptions,
                index: 1,
                //change: onChange
            });

            $("#civil_status").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: civilStatusOptions,
                index: 1,
                //change: onChange
            });

            $("#division_id").kendoDropDownList({
                dataTextField: "div_name",
                dataValueField: "id",
                dataSource: viewModel.ds.division,
                index: 1,
                change: function(e){
                    let selected = e.sender.dataItem();
                    let deptUrl = `divisions-departments/department/list-option/${selected.id}`;
                    viewModel.ds.department.transport.options.read.url = deptUrl;
                    viewModel.ds.department.read();
                }
            });

            $("#dept_id").kendoDropDownList({
                dataTextField: "dept_name",
                dataValueField: "id",
                dataSource: viewModel.ds.department,
                index: 1,
                dataBound : function(e){
                    if(viewModel.form.model.dept_id!=null){
                        $('#dept_id').data('kendoDropDownList').value(viewModel.form.model.dept_id);
                    }
                    
                },
                change: function(e){
                        let selected = e.sender.dataItem();
                        let jobtitleUrl = `employee-master-data/job-titles/${selected.id}`;
                        viewModel.ds.job_titles.transport.options.read.url = jobtitleUrl;
                        viewModel.ds.job_titles.read();
                }
            });

            $("#location_id").kendoDropDownList({
                dataTextField: "location_name",
                dataValueField: "id",
                dataSource: viewModel.ds.location,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            // $("#employee_stat").kendoDropDownList({
            //     dataTextField: "estatus_desc",
            //     dataValueField: "id",
            //     dataSource: emp_stat,
            //     index: 1,
            //     dataBound : function(e){
                  
            //     }
                
            // });

            // $("#exit_status").kendoDropDownList({
            //     dataTextField: "status_desc",
            //     dataValueField: "id",
            //     dataSource: exit_stat,
            //     index: 1,
            //     dataBound : function(e){
                  
            //     }
            //     //change: onChange
            // });

            // $("#pay_type").kendoDropDownList({
            //     dataTextField: "pay_description",
            //     dataValueField: "id",
            //     dataSource: pay_type,
            //     index: 1,
            //     dataBound : function(e){
                  
            //     }
            //     //change: onChange
            // });

            // $("#emp_level").kendoDropDownList({
            //     dataTextField: "level_desc",
            //     dataValueField: "id",
            //     dataSource: emp_level,
            //     index: 1,
            //     dataBound : function(e){
                  
            //     }
            //     //change: onChange
            // });

            $("#job_title_id").kendoDropDownList({
                dataTextField: "job_title_name",
                dataValueField: "id",
                dataSource: viewModel.ds.job_titles,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            
            

            $("#contact_no").kendoTextBox({ });

            $("#sss_no").kendoTextBox({ });
            $("#phic_no").kendoTextBox({ });
            $("#hdmf_no").kendoTextBox({ });
            $("#tin_no").kendoTextBox({ });
            $("#hdmf_contri").kendoTextBox({ });
            $("#biometric_id").kendoTextBox({ });
            $("#basic_salary").kendoNumericTextBox({  decimals: 2});
            
            //<input type="checkbox" data-bind="checked: isChecked" /> <input class="form-check-input" type="checkbox">

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                ]
            });

            function statusFilter(element) {
                element.kendoDropDownList({
                dataSource: exit_stat,
                dataTextField: "status_desc",
                dataValueField: "id",
                //optionLabel: "--Select Value--"
            });
            }
            
            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection