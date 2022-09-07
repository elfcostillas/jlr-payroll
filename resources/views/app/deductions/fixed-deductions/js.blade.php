@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createDeduction" > <span class="k-icon k-i-plus k-button-icon"></span>Create Deduction</button>
    </script>

    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        id : null,
                        period_id : null,
                        deduction_type : null,
                        remarks : null,
                    }
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'fixed-deductions/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'create',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(e.status==500){
                                        swal_error(e);
                                    }else{
                                        swal_success(e);
                                        viewModel.ds.maingrid.read();
                                    }
                                   
                                }
                            },
                            update : {
                                url : 'deduction-type/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(e.status==500){
                                        swal_error(e);
                                    }else{
                                        swal_success(e);
                                        viewModel.ds.maingrid.read();
                                    }
                                }
                            },
                            // parameterMap: function (data, type) {
                            //     if(type=='create' || type=='update'){
                            //         data.date_from = kendo.toString(data.date_from,'yyyy-MM-dd');
                            //         data.date_to = kendo.toString(data.date_to,'yyyy-MM-dd');
                            //         data.date_release = kendo.toString(data.date_release,'yyyy-MM-dd');
                            //     }

                            //     return data;
                            // }
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
                                    id : { type : 'number',editable:false },
                                    biometric_id: { type : 'number' },
                                    deduction_type: { type : 'string' },
                                    remarks: { type : 'string' },
                                    amount: { type : 'number' },
                                    is_stopped: { type : 'string' },
                                    encoded_by: { type : 'number',editable:false },
                                    encoded_on: { type : 'date',editable:false },
                                    employee_name: { type : 'string' },
                                    encoder: { type : 'string' ,editable:false},
                                    period_range: { type : 'string' },
                                    deduction_desc: { type : 'string' },
                                }
                            }
                        }
                    }),
                    typesgrid : new kendo.data.DataSource({
                        transport : {   
                            read : {
                                url : 'fixed-deductions/list-types',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        }
                    }),
                    payperiod : new kendo.data.DataSource({
                        transport : {   
                            read : {
                                url : 'fixed-deductions/list-payroll-period',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        }
                    }),
                    employee : new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'fixed-deductions/employee-list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            }
                        },
                        serverFiltering : true,
                        schema : {
                            model : {
                                id : "biometric_id",
                                fields : {
                                    employee_name : { type : 'string' },
                                }
                            }
                        }
                    })
                },
                toolbarHandler : {

                },
                buttonHandler : {
                    

                },
                functions : {
                   
                },
                callBack : function(){
                    
                 
                }
            });

            $("#maingrid").kendoGrid({
                dataSource : viewModel.ds.maingrid,
                pageable : {
                    refresh : true,
                    buttonCount : 5
                },
                noRecords: true,
                filterable : true,
                sortable : true,
                height : 550,
                scrollable: true,
                //toolbar : [{ name :'create',text:'Add Deduction'}],
                editable : "inline",
                toolbar : [
                   'create'
                ],
                selectable: true,
                columns : [
                   
                    {
                        title : "ID",
                        field : "id",
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 60,    
                    },
                    {
                        title : "Payroll Period",
                        field : "perio_id",
                        template : "#= period_range #",
                        width : 170,    
                    },
                    {
                        title : "Deduction Type",
                        field : "deduction_type",
                        template : "#= deduction_desc #",
                        width : 170,    
                    },
                    {
                        title : "Employee",
                        field : "biometric_id",
                        template : "#= employee_name #",
                        
                    },
                    {
                        title : "Amount",
                        field : "amount",
                        template : "#=kendo.toString(amount,'n2')#",
                        width : 110,
                        attributes : {
                            style : 'text-align:right'
                        }

                    },
                    {
                        title : "Stop",
                        field : "is_stopped",
                        width : 75,    
                    },
                    {
                        title : "Encoded By",
                        field : "encoder",
                        width : 110,    
                    },
                    { command: [
                            {
                                name: "edit",
                                icon: "edit"
                            },
                            {
                                name : "delete",
                                text : "Delete",
                                icon : 'delete'
                            }
                        ],
                        width : 185
                    }
                  
                ]
            });

           


            // $("#emp_level").kendoDropDownList({
            //     dataTextField: "level_desc",
            //     dataValueField: "id",
            //     dataSource: emp_level,
            //     index: 1,
            //     dataBound : function(e){
                  
            //     }
            //     //change: onChange
            // });

            function fixedOptionEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: fixedOption,
                   
                });
            }

            function employeeEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoComboBox({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "employee_name",
                    dataValueField: "biometric_id",
                    dataSource: viewModel.ds.employee,
                    filter : "contains"
                   
                });
            }

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
                    //{ id : 'postBtn', type: "button", text: "Post", icon: 'print', click : viewModel.buttonHandler.post },
                ]
            });

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection