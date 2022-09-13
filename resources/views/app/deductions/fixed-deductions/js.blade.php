@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createDeduction" > <span class="k-icon k-i-plus k-button-icon"></span>Create Deduction</button>
    </script>

    <script>
        $(document).ready(function(){

            let stopOption = [
                { text: "No", value: "N" },
                { text: "Yes", value: "Y" },
            ];

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
                                url : 'fixed-deductions/create',
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
                                url : 'fixed-deductions/update',
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
                                    biometric_id: { type : 'string' },
                                    deduction_type: { type : 'number' },
                                    remarks: { type : 'string' },
                                    amount: { type : 'number' },
                                    is_stopped: { type : 'string' },
                                    //encoded_by: { type : 'number',editable:false },
                                    //encoded_on: { type : 'date',editable:false },
                                    employee_name: { type : 'string' },
                                    encoder: { type : 'string' ,editable:false},
                                    period_range: { type : 'string' },
                                    deduction_desc: { type : 'string' },
                                    period_id : { type : 'number' },
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
                filterable : {
                    extra: false,
                    operators: {
                        string: {
                            contains: "Contains"
                        }
                    }
                },
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
                   
                    // {
                    //     title : "ID",
                    //     field : "id",
                    //     width : 60,    
                    // },
                    // {
                    //     title : "Start Deduction",
                    //     field : "period_id",
                    //     template : "#= period_range #",
                    //     width : 170,    
                    //     editor : payperiodEditor
                    // },
                    {
                        title : "Deduction Type",
                        field : "deduction_type",
                        template : "#= deduction_desc #",
                        width : 140,    
                        editor : fixedOptionEditor
                    },
                    {
                        title : "Employee",
                        field : "biometric_id",
                        template : "#= employee_name #",
                        editor : employeeEditor
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
                        width : 80, 
                        editor : stopEditor,  
                    },
                    {
                        title : "Encoded By",
                        field : "encoder",
                        width : 110,    
                    },
                    // { command: [
                    //         {
                    //             name: "edit",
                    //             icon: "edit"
                    //         },
                    //         // {
                    //         //     name : "delete",
                    //         //     text : "Delete",
                    //         //     icon : 'delete'
                    //         // }
                    //     ],
                    //     width : 185
                    // }
                  
                ]
            });

           
            $("#typesgrid").kendoGrid({
                dataSource : viewModel.ds.typesgrid,
                pageable : {
                    refresh : true,
                    buttonCount : 5
                },
                noRecords: true,
                filterable : true,
                sortable : true,
                height : 550,
                scrollable: true,
                selectable: true,
                columns : [
                   
                    {
                        title : "ID",
                        field : "id",
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 60,    
                    },
                    {
                        title : "Description",
                        field : "description",
                        // template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        // width : 120,    
                    },
                ],change : function(e){   
                    let grid = $("#typesgrid").data("kendoGrid");
                    let selectedItem = grid.dataItem(grid.select());

                    let oneTimeUrl = `fixed-deductions/list/${selectedItem.id}`;

                    viewModel.ds.maingrid.transport.options.read.url = oneTimeUrl;
                    viewModel.ds.maingrid.read();
                }
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
                    dataTextField: "description",
                    dataValueField: "id",
                    dataSource: viewModel.ds.typesgrid,
                   
                });
            }

            function payperiodEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "template",
                    dataValueField: "id",
                    dataSource: viewModel.ds.payperiod,
                   
                });
            }

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