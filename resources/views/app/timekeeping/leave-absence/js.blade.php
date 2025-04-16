@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createEmployee" > <span class="k-icon k-i-plus k-button-icon"></span>Create Leave</button>
    </script>
   
    <script>
        $(document).ready(function(){

            let leaveOptions = [
                { text: "Vacation Leave", value: "VL" },
                { text: "Sick leave", value: "SL" },
                { text: "Undertime", value: "UT" },
                { text: "Emergency", value: "EL" },
                { text: "Maternity/Paternity Leave", value: "MP" },
                { text: "Birthday Leave", value: "BL" },
                { text: "Others", value: "O" },
                { text: "SVL", value: "SVL" },
                { text: "Bereavement Leave", value: "BRV" },
            ];

            let stopOption = [
                { text: "No", value: "N" },
                { text: "Yes", value: "Y" },
            ];

            var validator = $("#leaveRequestForm").kendoValidator().data("kendoValidator");

            let obj = {
                id : null,
                biometric_id: null,
                date_from: null,
                date_to: null,
                dept_id: null,
                division_id: null,
                job_title_id: null,
                remarks: null,
                leave_type: null,
                reliever_id: null,
                document_status : null,
                received_by : null
            };

            let obj2= {
                biometric_id : null,
                division_id : null,
                division_desc : null,
                department_id : null,
                department_desc : null,
                job_title_id : null,
                job_title_desc : null,
                
            };

            var viewModel = kendo.observable({ 
                selected : null,
                form : {
                    model : {
                        id : null,
                        biometric_id: null,
                        date_from: null,
                        date_to: null,
                        dept_id: null,
                        division_id: null,
                        job_title_id: null,
                        remarks: null,
                        leave_type: null,
                        reliever_id: null,
                        document_status : null,
                        received_by : null

                    },
                },
                employee : {
                    biometric_id : null,
                    division_id : null,
                    division_desc : null,
                    department_id : null,
                    department_desc : null,
                    job_title_id : null,
                    job_title_desc : null,

                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'leaves-absences/list',
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
                                    employee_name : { type:"string"},
                                    leave_type_desc : { type:"string"},
                                    request_date: { type:"string"},
                                    daterange: { type:"string"},
                                    receiver: { type:"string"},

                                }
                            }
                        }
                    }),
            
                    employee : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../accounts/leave-request/employee-list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        serverPaging : true,
                        serverFiltering : true,
                        schema : {
                            model : {
                                id : 'biometric_id',
                                fields : {
                                    employee_name : { type : "string"},
                                    dept_id : { type : "number"},
                                    division_id : { type : "number"},
                                    job_title_id : { type : "number"},
                                    job_title_name : { type : "string"},
                                    dept_name : { type : "string"},
                                    div_name : { type : "string"}
                                }
                            }
                        }
                    }),

                    leaveDetails :  new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../accounts/leave-request/read-detail/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'leaves-absences/update-detail',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e,jqXhr){
                              
                                    if(e.status==500)
                                    {
                                        custom_error(e.responseJSON.error)
                                    }
                                    viewModel.ds.leaveDetails.read();
                                }
                            },
                            parameterMap : function (data,type){
                                if(type=='update'){
                                    // $.each(data.models,function(index,value){
                                    // });
                                    data.leave_date =  kendo.toString(data.leave_date,'yyyy-MM-dd');
                                }
                                return data;
                            }
                        },
                        pageSize :120,
                        schema : {
                            model : {
                                id : 'line_id',
                                fields : {
                
                                    leave_date : { type : "date",editable:false,},
                                    is_canceled : { type : "string"},
                                    time_from : { type : "string"},
                                    time_to : { type : "string"},
                                    days : { type : "number"},
                                    with_pay : { type : "number"},
                                    without_pay : { type : "number"},
                                }
                            }
                        },
                        aggregate : [
                            { field : "with_pay" , aggregate: "sum" },
                            { field : "without_pay" , aggregate: "sum" },
                        ]
                    })
                    
                },
                buttonHandler : {  
                   
                    closePop : function(e){

                    },
                    createEmployee : function(e){
                        viewModel.buttonHandler.clear();
                        viewModel.functions.showPOP();
                        viewModel.callBack();
                        
                    },
                    save : async function(e){

                        await viewModel.functions.reAssignValues();
                        
                        if(validator.validate()) 
                        {
                            var json_data = JSON.stringify(viewModel.form.model);
                            if(viewModel.form.model.id!=null){
                                viewModel.ds.leaveDetails.sync();
                            }
                           
                            
                            $.post('leave-request/save',{
                                data : json_data
                            },function(data,staus){
                                swal_success(data);

                                let url = `leave-request/read-header/${data}`;
                                read(url,viewModel);
                                viewModel.ds.maingrid.read();

                                let detailUrl = `leave-request/read-detail/${data}`;
                                viewModel.ds.leaveDetails.transport.options.read.url = detailUrl;
                                viewModel.ds.leaveDetails.read();

                                //viewModel.maingrid.formReload(data);
                            })
                            .fail(function(data){
                            swal_error(data);
                            }).always(function() {
                                //viewModel.maingrid.ds.read();
                            });
                        }
                    },
                    view : async function(e){
                        e.preventDefault(); 

                        viewModel.functions.showPOP();
                       
                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        viewModel.set('selected',data);

                        let url  = `../accounts/leave-request/read-header/${data.id}`;
                        read(url,viewModel);

                        let detailUrl = `../accounts/leave-request/read-detail/${data.id}`;
                        viewModel.ds.leaveDetails.transport.options.read.url = detailUrl;
                        viewModel.ds.leaveDetails.read();

                        //await viewModel.functions.prepareForm(data);
                        
                        //console.log(data);

                    }, 
                    clear : function(e){
                        //$.each(viewModel.form.model,function(index,value));
                      
                        for (var key in obj) {
                            //console.log(key); //console.log(key + " -> " + p[key]);
                            viewModel.form.model.set(key,null);
                        }

                        for (var key2 in obj2) {
                            //console.log(key); //console.log(key + " -> " + p[key]);
                          
                            viewModel.employee.set(key2,null);
                           
                        }

                        viewModel.form.model.set('document_status','DRAFT');

                        let detailUrl = `../accounts/leave-request/read-detail/0`;
                        viewModel.ds.leaveDetails.transport.options.read.url = detailUrl;
                        viewModel.ds.leaveDetails.read();

                        console.log(viewModel.employee);

                        // viewModel.form.model.set('civil_status',1);
                        // viewModel.form.model.set('gender','M');
                        // viewModel.form.model.set('location_id',1);
                        // viewModel.form.model.set('division_id',1);

                        // viewModel.form.model.set('employee_stat',1);
                        // viewModel.form.model.set('exit_status',1);
                        // viewModel.form.model.set('pay_type',1);
                    },
                    post : function(e){
                        Swal.fire({
                            title: 'Finalize and Post Leave Request',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Finalize'
                        }).then((result) => {
                            if (result.value) {                       
                                viewModel.form.model.set('document_status','POSTED'); 
                                viewModel.buttonHandler.save();
                            }
                        });
                    },
                    receive : function(e){
                        $.post('leaves-absences/receive',{
                                id : viewModel.form.model.id
                            },function(data,staus){
                                swal_success(data);
                               
                                let url = `../accounts/leave-request/read-header/${data}`;
                                read(url,viewModel);
                                viewModel.ds.maingrid.read();

                                // let detailUrl = `leave-request/read-detail/${data}`;
                                // viewModel.ds.leaveDetails.transport.options.read.url = detailUrl;
                                // viewModel.ds.leaveDetails.read();

                                //viewModel.maingrid.formReload(data);
                            })
                            .fail(function(data){
                            swal_error(data);
                            }).always(function() {
                                
                            });
                    },
                    unpost : function(e){
                        $.post('leaves-absences/unpost',{
                                id : viewModel.form.model.id
                            },function(data,staus){
                                swal_success(data);
                               
                                let url = `../accounts/leave-request/read-header/${data}`;
                                read(url,viewModel);
                                viewModel.ds.maingrid.read();

                                // let detailUrl = `leave-request/read-detail/${data}`;
                                // viewModel.ds.leaveDetails.transport.options.read.url = detailUrl;
                                // viewModel.ds.leaveDetails.read();

                                //viewModel.maingrid.formReload(data);
                            })
                            .fail(function(data){
                            swal_error(data);
                            }).always(function() {
                                
                            });
                    }
                },
                functions : {
                    showPOP : function(data){
                       
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "934", //1124 - 1152
                            height: "710",
                            title: "Leave Request Form",
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

                        viewModel.form.model.set('biometric_id',($('#biometric_id').data('kendoComboBox').value()!='') ? $('#biometric_id').data('kendoComboBox').value() : null );
                        viewModel.form.model.set('reliever_id',($('#reliever_id').data('kendoComboBox').value()!='') ? $('#reliever_id').data('kendoComboBox').value() : null );
                        viewModel.form.model.set('leave_type',($('#leave_type').data('kendoDropDownList').value()!='') ? $('#leave_type').data('kendoDropDownList').value() : null );

                        viewModel.form.model.set('date_from',kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('date_to',kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        // viewModel.form.model.set('gender',$('#gender').data('kendoDropDownList').value());
                        // viewModel.form.model.set('birthdate',kendo.toString($('#birthdate').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        // viewModel.form.model.set('civil_status',$('#civil_status').data('kendoDropDownList').value());
                        // viewModel.form.model.set('division_id',$('#division_id').data('kendoDropDownList').value());
                        // viewModel.form.model.set('dept_id',($('#dept_id').data('kendoDropDownList').value()!='') ? $('#dept_id').data('kendoDropDownList').value() : 0 );
                        // viewModel.form.model.set('job_title_id',($('#job_title_id').data('kendoDropDownList').value()!='') ? $('#job_title_id').data('kendoDropDownList').value() : null );
                        // viewModel.form.model.set('emp_level',($('#emp_level').data('kendoDropDownList').value()!='') ? $('#emp_level').data('kendoDropDownList').value() : null );

                        // viewModel.form.model.set('employee_stat',($('#employee_stat').data('kendoDropDownList').value()!='') ? $('#employee_stat').data('kendoDropDownList').value() : 0 );
                        // viewModel.form.model.set('exit_status',($('#exit_status').data('kendoDropDownList').value()!='') ? $('#exit_status').data('kendoDropDownList').value() : 0 );
                        // viewModel.form.model.set('pay_type',($('#pay_type').data('kendoDropDownList').value()!='') ? $('#pay_type').data('kendoDropDownList').value() : 0 );
                        // viewModel.form.model.set('deduct_sss',(viewModel.form.mirror.deduct_sss) ? 'Y':'N');
                        // viewModel.form.model.set('deduct_phic',(viewModel.form.mirror.deduct_phic) ? 'Y':'N');
                        // viewModel.form.model.set('is_daily',(viewModel.form.mirror.is_daily) ? 'Y':'N');
                        // viewModel.form.model.set('date_hired',kendo.toString($('#date_hired').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        // viewModel.form.model.set('location_id',($('#location_id').data('kendoDropDownList').value()!='') ? $('#location_id').data('kendoDropDownList').value() : 0 );
                        // //viewModel.form.model.set('deduct_sss',(viewModel.form.model.deduct_sss) ? 'Y':'N');
                        

                    },
                    prepareForm : function(data)
                    {
                       
                        // let deptUrl = `divisions-departments/department/list-option/${data.division_id}`;
                        // viewModel.ds.department.transport.options.read.url = deptUrl;
                        // viewModel.ds.department.read();

                        // let jobtitleUrl = `employee-master-data/job-titles/${data.dept_id}`;
                        // viewModel.ds.job_titles.transport.options.read.url = jobtitleUrl;
                        // viewModel.ds.job_titles.read();
                    }
                   

                },
                callBack : function()
                {
                  
                    // viewModel.form.mirror.set('deduct_sss',(viewModel.form.model.deduct_sss=='Y') ? true:false);
                    // viewModel.form.mirror.set('deduct_phic',(viewModel.form.model.deduct_phic=='Y') ? true:false);
                    // viewModel.form.mirror.set('is_daily',(viewModel.form.model.is_daily=='Y') ? true:false);
                    //div_name":"Shared Services","dept_id":9,"dept_name":"Information Technology","job_title_id":15,"job_title_name":"IT Programmer"
                    if(viewModel.form.model.biometric_id!=null){
                        viewModel.employee.set('biometric_id',viewModel.form.model.biometric_id);
                        viewModel.employee.set('division_desc',viewModel.form.model.div_name);
                        viewModel.employee.set('department_desc',viewModel.form.model.dept_name);
                        viewModel.employee.set('job_title_desc',viewModel.form.model.job_title_name);
                    }
                    // let active = $("#toolbar").data("kendoToolBar");
                    // let posted = $("#toolbar2").data("kendoToolBar");

                    //console.log(viewModel.form.model.document_status);
                    // biometric_id
                    // division_id
                    // division_desc
                    // department_id
                    // department_desc
                    // job_title_id
                    // job_title_desc
                    let grid = $("#subgrid").data('kendoGrid');
                
                    if(viewModel.form.model.document_status=='POSTED'){
                        // grid.hideColumn(6);
                        // grid.showColumn(7);

                        activeToolbar.hide();
                        postedToolbar.show();

                        var toolbar = $("#toolbar2").data("kendoToolBar");

                        if(viewModel.form.model.received_by==null || viewModel.form.model.received_by=='' ){
                            toolbar.show($("#setReceived"));
                            toolbar.hide($("#unpostBtn"));
                            //console.log('show');
                        }else{
                            toolbar.hide($("#setReceived"));
                            toolbar.show($("#unpostBtn"));
                            
                            //console.log('hide');
                        }

                    }else{
                        // grid.hideColumn(7);
                        // grid.showColumn(6);

                        activeToolbar.show();
                        postedToolbar.hide();
                    }

                    var toolbar = $("#toolbar").data("kendoToolBar");
                    //toolbar.hide($("#btn2"));
                  
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
                    // { template: kendo.template($("#template").html()) }
                ],
                //editable : "inline",
                columns : [
                    {
                        title : "ID",
                        field : "id",
                        width : 80,    
                    },
                    {
                        title : "Name",
                        field : "employee_name",
                       // width : 80,    
                    },
                    {
                        title : "Type",
                        field : "leave_type_desc",
                        width : 120,    
                    },
                    {
                        title : "Date Req.",
                        field : "request_date",
                        width : 100,    
                    },
                    {
                        title : "From - To",
                        field : "daterange",
                        width : 160,    
                    },
                    {
                        title : "Received By",
                        field : "receiver",
                        //width : 80,    
                    },
                    {
                        command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
                ]
            });

            $("#subgrid").kendoGrid({
                dataSource : viewModel.ds.leaveDetails,
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
                height : 310,
                scrollable: true,
                navigatable : true,
                toolbar: [
                    { name: "save", text: "Save" }
                ],
                editable : true,//"inline",
                columns : [
                    {
                        title : "Date",
                        field : "leave_date",
                        template : "#= (data.leave_date) ? kendo.toString(data.leave_date,'MM/dd/yyyy') : ''  #",
                        width : 90,    
                    },
                   
                    {
                        title : "Time From",
                        field : "time_from",
                        attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        width : 100,  
                        // width : 90,     
                    },
                    {
                        title : "Time To",
                        field : "time_to",
                        attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        width : 90,    
                    },
                    // {
                    //     title : "Day",
                    //     field : "days",
                    //     width : 70,   
                    //     aggregates : ['sum'], 
                    //     attributes: {
                    //         style: "font-size: 9pt;text-align:center"
                            
                    //     },
                    //     footerTemplate: "<div style='text-align:center;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" 
                     
                    // },
                    {
                        title : "W/Pay",
                        field : "with_pay",
                        attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        width : 80,   
                        footerTemplate: "<div style='text-align:center;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                        template : "#if(with_pay==0){#  #}else{# #= with_pay # #}# ",
                    },
                    {
                        title : "W/Out Pay",
                        field : "without_pay",
                        attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        width : 100,    
                        footerTemplate: "<div style='text-align:center;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                        template : "#if(without_pay==0){#  #}else{# #= without_pay # #}# ",
                    },
                    {
                        title : "Cancel",
                        field : "is_canceled",
                        attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        width : 90,    
                        editor : stopEditor
                    },
                    {
                        
                        title : "Remarks",
                        field : "leave_remarks",
                        attributes: {
                            style: "font-size: 9pt;text-align:left"
                            
                        },
                        
                    }
                  
                ]
            });
            
            $("#biometric_id").kendoComboBox({
                dataSource : viewModel.ds.employee,
                filter : "contains",
                dataTextField: "employee_name",
                dataValueField: "biometric_id",
                minLength: 3,
                change : function(e){
                    //console.log(e.sender.value());
                    let data = e.sender.dataItem();
                    
                    viewModel.employee.set('biometric_id',data.biometric_id);
                    viewModel.employee.set('division_id',data.division_id);
                    viewModel.employee.set('division_desc',data.div_name);
                    viewModel.employee.set('department_id',data.dept_id);
                    viewModel.employee.set('department_desc',data.dept_name);
                    viewModel.employee.set('job_title_id',data.job_title_id);
                    viewModel.employee.set('job_title_desc',data.job_title_name);

                    viewModel.form.model.set('dept_id',data.dept_id);
                    viewModel.form.model.set('division_id',data.division_id);
                    viewModel.form.model.set('job_title_id',data.job_title_id);
                    
                }
            });

            $("#reliever_id").kendoComboBox({ 
                dataSource : viewModel.ds.employee,
                filter : "contains",
                dataTextField: "employee_name",
                dataValueField: "biometric_id",
                minLength: 3
            });
            //$("#lastname").kendoTextBox({ });
            $("#job_title_desc").kendoTextBox({ });
            $("#department_desc").kendoTextBox({ });
            $("#division_desc").kendoTextBox({ });

            $("#remarks").kendoTextBox({ });

            //$("#secondary_addr").kendoTextBox({ });


            $("#date_from").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#leave_type").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: leaveOptions,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            function stopEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: stopOption,
                   
                });
            }
        
            //<input type="checkbox" data-bind="checked: isChecked" /> <input class="form-check-input" type="checkbox">

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                    { id : 'postBtn', type: "button", text: "Post", icon: 'print', click : viewModel.buttonHandler.post },
                ]
            });

            var postedToolbar = $("#toolbar2").kendoToolBar({
                items : [
                    //{ id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                    { id : 'setReceived', type: "button", text: "Set as Received", icon: 'save', click : viewModel.buttonHandler.receive },
                    { id : 'unpostBtn', type: "button", text: "Unpost", icon: 'save', click : viewModel.buttonHandler.unpost },
                    //{ id : 'postBtn', type: "button", text: "Post", icon: 'print', click : viewModel.buttonHandler.post },
                ]
            });

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection