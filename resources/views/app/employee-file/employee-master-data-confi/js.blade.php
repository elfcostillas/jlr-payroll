@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createEmployee" > <span class="k-icon k-i-plus k-button-icon"></span>Create Employee</button>
    </script>
   
    <script>
        $(document).ready(function(){

            let emp_stat =<?php echo json_encode($emp_stat) ?>;
            let exit_stat =<?php echo json_encode($exit_stat) ?>;
            let pay_type =<?php echo json_encode($pay_type) ?>;
            let emp_level =<?php echo json_encode($level_desc) ?>;

            $("#employee_search").kendoTextBox({
                change : function (e){
                        let value = e.sender.value();

                        if(value.trim()!='')
                        {
                            viewModel.ds.maingrid.read({search : $("#employee_search").data('kendoTextBox').value() });
                            
                        }
                }
               
            });

            let genderOptions = [
                    { text: "Male", value: "M" },
                    { text: "Female", value: "F" },
                ];
            
                let civilStatusOptions = [
                    { text: "Single", value: 1 },
                    { text: "Married", value: 2 },
                    { text: "Divorced", value: 3 },
                    { text: "Widowed/Widower", value: 4 },
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
                    exit_date: null,
                    contact_no : null,
                    division_id: null,
                    dept_id: null,
                    location_id: null,
                    pay_type: null,
                    date_hired : null,
                    emp_level : null,
                    job_title_id : null,
                    daily_allowance: null,
                    monthly_allowance: null,
                    blood_type: null,
                    email: null,
                    emergency_person: null,
                    emergency_relation: null,
                    emergency_phone: null,
                    date_regularized: null,
                    sched_mtwtf: null,
                    sched_sat: null,
                    deduct_wtax : null,
                    retired : null,
                    manual_wtax : null,
                    fixed_rate : null

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
                        exit_status : null,
                        exit_date : null,
                        contact_no : null,
                        division_id: null,
                        dept_id: null,
                        location_id: null,
                        pay_type: null,
                        date_hired : null,
                        emp_level : null,
                        job_title_id : null,
                        daily_allowance: null,
                        monthly_allowance: null,
                        blood_type: null,
                        email: null,
                        emergency_person: null,
                        emergency_relation: null,
                        emergency_phone: null,
                        date_regularized: null,
                        sched_mtwtf: null,
                        sched_sat: null,
                        deduct_wtax : null,
                        retired : null,
                        manual_wtax : null,
                        fixed_rate : null
                    },
                    mirror : {
                        is_daily : false,
                        deduct_sss : false,
                        deduct_hdmf : false,
                        deduct_phic : false,
                        deduct_wtax : false,
                        retired : false
                    }
                  
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'employee-master-data-confi/list',
                                //data : { search : $("#employee_search").data('kendoTextBox').value() },
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
                                    biometric_id : { type : 'number' },
                                    lastname : { type : 'string' },
                                    firstname : { type : 'string' },
                                    middlename : { type : 'string' },
                                    primary_addr : { type : 'string' },
                                    division_id: { type : 'number' },
                                    dept_id: { type : 'number' },
                                }
                            }
                        }
                    }),
                    ratesgrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'employee-master-data-confi/get-emp-rates/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'employee-master-data-confi/create-emp-rates',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },parameterMap: function (data, type) {
                                if(type=='create'){
                                    data.emp_id = viewModel.form.model.id;
                                }

                                return data;
                            }
                            
                        },
                        // pageSize :11,
                        // serverPaging : true,
                        // serverFiltering : true,
                        schema : {
                            data : "data",
                            model : {
                                id : 'id',
                                fields : {
                                    emp_id : { type : 'number' },
                                    rates : { type : 'number', },
                                    date_added  : { type : 'datetime' },
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
                                url : 'employee-master-data-confi/job-titles/0',
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
                    sched : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../timekeeping/manage-dtr/get-employee-schedules',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                           
                        },
                        schema : {
                            model : {
                                id : 'schedule_id',
                                fields : {
                                    schedule_id : { type:'number',  },
                                    schedule_desc : { type:'string',  },
                                }
                            }
                        }
                    })
                    
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

                        console.log(json_data);
                        
                        $.post('employee-master-data-confi/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                            if(viewModel.form.model.id==null)
                            {
                                let url  = `employee-master-data-confi/read/${data}`;
                                setTimeout(function(){
                                    read(url,viewModel);
                                }, 500);
                            }   
                            
                            //let url  = `employee-master-data-confi/read/${viewModel.selected.id}`;
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

                        let url  = `employee-master-data-confi/read/${data.id}`;
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
                    },
                    copy : function(e)
                    {
                        $.post('employee-master-data-confi/copy-onlinerequest',{
                            id : viewModel.form.model.id 
                        },function(data,staus){
                            swal_success(data);

                        })
                        .fail(function(data){
                           swal_error(data);
                        }).always(function() {
                            
                        });
                    },
                    rates : function(e)
                    {
                        if(viewModel.form.model.id){
                            var myWindow2 = $("#ratePop");
                            
                            myWindow2.kendoWindow({
                                width: "520", //1124 - 1152
                                height: "560",
                                title: "Rates",
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
                        }
                    }
                },
                functions : {
                    showPOP : function(data){
                       
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "920", //1124 - 1152
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
                        viewModel.form.model.set('fixed_rate',(viewModel.form.mirror.fixed_rate) ? 'Y':'N');
                        viewModel.form.model.set('deduct_wtax',(viewModel.form.mirror.deduct_wtax) ? 'Y':'N');
                        viewModel.form.model.set('retired',(viewModel.form.mirror.retired) ? 'Y':'N');
                        viewModel.form.model.set('date_hired',kendo.toString($('#date_hired').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('exit_date',kendo.toString($('#exit_date').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('date_regularized',kendo.toString($('#exit_date').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('location_id',($('#location_id').data('kendoDropDownList').value()!='') ? $('#location_id').data('kendoDropDownList').value() : 0 );
                        viewModel.form.model.set('sched_mtwtf',($('#sched_mtwtf').data('kendoDropDownList').value()!='') ? $('#sched_mtwtf').data('kendoDropDownList').value() : null );
                        viewModel.form.model.set('sched_sat',($('#sched_sat').data('kendoDropDownList').value()!='') ? $('#sched_sat').data('kendoDropDownList').value() : null );
                        //viewModel.form.model.set('deduct_sss',(viewModel.form.model.deduct_sss) ? 'Y':'N');
                        

                    },
                    prepareForm : function(data)
                    {
                       
                        let deptUrl = `divisions-departments/department/list-option/${data.division_id}`;
                        viewModel.ds.department.transport.options.read.url = deptUrl;
                        viewModel.ds.department.read();

                        let jobtitleUrl = `employee-master-data-confi/job-titles/${data.dept_id}`;
                        viewModel.ds.job_titles.transport.options.read.url = jobtitleUrl;
                        viewModel.ds.job_titles.read();

                        viewModel.ds.ratesgrid.transport.options.read.url = `employee-master-data-confi/get-emp-rates/${data.id}`;
                        viewModel.ds.ratesgrid.read();
                    }
                   

                },
                callBack : function()
                {
                  
                    viewModel.form.mirror.set('deduct_sss',(viewModel.form.model.deduct_sss=='Y') ? true:false);
                    viewModel.form.mirror.set('deduct_phic',(viewModel.form.model.deduct_phic=='Y') ? true:false);
                    viewModel.form.mirror.set('is_daily',(viewModel.form.model.is_daily=='Y') ? true:false);
                    viewModel.form.mirror.set('fixed_rate',(viewModel.form.model.fixed_rate=='Y') ? true:false);
                    viewModel.form.mirror.set('deduct_wtax',(viewModel.form.model.deduct_wtax=='Y') ? true:false);
                    viewModel.form.mirror.set('retired',(viewModel.form.model.retired=='Y') ? true:false);
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
                       
                        width : 90,    
                    },
                    {
                        title : "Last Name",
                        field : "lastname",
                        width : 130,    
                    },
                    {
                        title : "First Name",
                        field : "firstname",
                        width : 130,  
                    },
                    {
                        title : "Middle Name",
                        field : "middlename",
                        width : 130,  
                    },
                    {
                        title : "Jr,Sr,II,III",
                        field : "suffixname",
                        width : 90,  
                    },
                    {
                        title : "Division",
                        field : "div_code",
                        //width : 90,  
                    },
                    {
                        title : "Department",
                        field : "dept_code",
                        //width : 90,  
                    },
                    // {
                    //     title : "Emp Status",
                    //     field : "estatus_desc",
                    //     width : 100,  
                    // }, 
                    {
                        title : "Emp Type",
                        field : "pay_description",
                        width : 100,  
                    },
                    {
                        title : "Status",
                        field : "exit_status",
                        template : "#: status_desc #",
                        width : 80,  
                        filterable: {
                            ui: statusFilter
                        }
                    },
                    {
                        command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
                ]
            });

            $("#ratesTable").kendoGrid({
                dataSource : viewModel.ds.ratesgrid,
                pageable : {
                    refresh : true,
                    buttonCount : 5
                },
                height : 480,
                noRecords: true,
                editable: true,
                toolbar : ['create','save'],
                columns : [
                    {
                        title : "Rates",
                        field : "rates",
                        width : 100,
                         attributes: {
                            style: "font-size: 9pt"
                        },
                        headerAttributes: {
                            style: "font-size: 9pt"
                        },
                        template : "#=kendo.toString(rates,'n2')#",
                    },
                    {
                        title : "Added On",
                        field : "date_added",
                        width : 80,
                         attributes: {
                            style: "font-size: 9pt"
                        },
                        headerAttributes: {
                            style: "font-size: 9pt"
                        },
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
            $("#bank_acct").kendoTextBox({ });
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

            $("#exit_date").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_regularized").kendoDatePicker({
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
                        let jobtitleUrl = `employee-master-data-confi/job-titles/${selected.id}`;
                        viewModel.ds.job_titles.transport.options.read.url = jobtitleUrl;
                        viewModel.ds.job_titles.read();
                }
            });

            $("#location_id").kendoDropDownList({
                dataTextField: "location_name",
                dataValueField: "id",
                autoWidth : true,
                dataSource: viewModel.ds.location,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#employee_stat").kendoDropDownList({
                dataTextField: "estatus_desc",
                dataValueField: "id",
                dataSource: emp_stat,
                index: 1,
                dataBound : function(e){
                  
                }
                
            });

            $("#exit_status").kendoDropDownList({
                dataTextField: "status_desc",
                dataValueField: "id",
                dataSource: exit_stat,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#pay_type").kendoDropDownList({
                dataTextField: "pay_description",
                dataValueField: "id",
                dataSource: pay_type,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#emp_level").kendoDropDownList({
                dataTextField: "level_desc",
                dataValueField: "id",
                dataSource: emp_level,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#job_title_id").kendoDropDownList({
                dataTextField: "job_title_name",
                dataValueField: "id",
                dataSource: viewModel.ds.job_titles,
                index: 1,
                dataBound : function(e){
                    $('#job_title_id').data("kendoDropDownList").value(viewModel.form.model.job_title_id);
                }
                //change: onChange
            });

            $("#sched_mtwtf").kendoDropDownList({
                dataTextField: "schedule_desc",
                dataValueField: "schedule_id",
                dataSource: viewModel.ds.sched,
                index: 0,
                optionLabel: {
                    schedule_desc: "",
                    schedule_id: null
                },
                dataBound : function(e){
                  
                }
                //change: onChange ":3,"
            });

            $("#sched_sat").kendoDropDownList({
                dataTextField: "schedule_desc",
                dataValueField: "schedule_id",
                dataSource: viewModel.ds.sched,
                index: 0,
                optionLabel: {
                    schedule_desc: "",
                    schedule_id: null
                },
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
            $("#blood_type").kendoTextBox({ });
            $("#email").kendoTextBox({ });
            $("#manual_wtax").kendoTextBox({ });

            $("#emergency_person").kendoTextBox({ });
            $("#emergency_relation").kendoTextBox({ });
            $("#emergency_phone").kendoTextBox({ });

            $("#basic_salary").kendoNumericTextBox({  decimals: 2});
            // $("#basic_salary").kendoTextBox({  decimals: 2});

            $("#daily_allowance").kendoNumericTextBox({  decimals: 2});
            $("#monthly_allowance").kendoNumericTextBox({  decimals: 2});
            
            //<input type="checkbox" data-bind="checked: isChecked" /> <input class="form-check-input" type="checkbox">

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                    { id : 'copy', type: "button", text: "Copy to Online Request", icon: 'copy', click : viewModel.buttonHandler.copy },
                    { id : 'copy2', type: "button", text: "Manage Rates", icon: 'dollar', click : viewModel.buttonHandler.rates },
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