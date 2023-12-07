@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createDeduction" > <span class="k-icon k-i-plus k-button-icon"></span>Create Deduction</button>
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.showAll" > <span class="k-icon k-i-edit k-button-icon"></span>Show All</button>

        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.downloadNonConfi" > <span class="k-icon k-i-download k-button-icon"></span>Download Non Confi</button>
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.downloadConfi" > <span class="k-icon k-i-download k-button-icon"></span>Download Confi</button>
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
                        id: null,
                        period_id: null,
                        biometric_id: null,
                        deduction_type: null,
                        remarks: null,
                        total_amount: null,
                        terms: null,
                        ammortization: null,
                        is_stopped: null,
                        deduction_sched: null,
                    }
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'installments/list/0',
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
                                    id : { type : 'string',editable : false },
                                    employee : { type : 'string' },
                                    descriptiongit : { type : 'string' },
                                    total_amount : { type : 'number' },
                                    // date_release: { type : 'date' },
                                    // man_hours: { type : 'number' },
                                }
                            }
                        }
                    }),
                    typesgrid : new kendo.data.DataSource({
                        transport : {   
                            read : {
                                url : 'installments/list-types',
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
                                url : 'installments/list-payroll-period',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        }
                    }),
                    employeegrid : new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'installments/employee-list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            }
                        },
                        serverPaging : true,
                        serverFiltering : true,
                        pageSize : 15,
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
                    employeecombobox : new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'installments/employee-list',
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
                    schedlist : new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'installments/deduct-sched-list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            }
                        },
                        pageSize : 15,
                        schema : {
                          
                            model : {
                                id : "id",
                                fields : {
                                    code1 : { type : 'string' },
                                    sched_desc : { type : 'string' },
                                }
                            }
                        }
                    })
                    
                },
                toolbarHandler : {

                },
                buttonHandler : {
                    save : async function(e){
                        await viewModel.functions.reAssignValues();
                        
                        var json_data = JSON.stringify(viewModel.form.model);
                        
                        $.post('installments/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                            let url  = `installments/read-header/${data}`;
                            read(url,viewModel);

                            viewModel.ds.maingrid.read();
                            //viewModel.maingrid.formReload(data);
                        })
                        .fail(function(data){
                           swal_error(data);
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });
                    },
                    saveAS : async function(e){
                        await viewModel.functions.reAssignValues();
                        viewModel.form.model.set('id',null);

                        var json_data = JSON.stringify(viewModel.form.model);
                        
                        $.post('installments/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                            let url  = `installments/read-header/${data}`;
                            read(url,viewModel);

                            viewModel.ds.maingrid.read();
                            //viewModel.maingrid.formReload(data);
                        })
                        .fail(function(data){
                           swal_error(data);
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });
                    },
                    view : function(e) {
                        e.preventDefault(); 
                        viewModel.functions.showPOP();

                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        let url  = `installments/read-header/${data.id}`;
                        read(url,viewModel);

                        // viewModel.ds.detailsgrid.transport.options.read.url = `installments/list-details/${data.id}`;
                        // viewModel.ds.detailsgrid.read();
                    },
                    createDeduction : function(){
                        viewModel.buttonHandler.clear();
                        viewModel.functions.showPOP();
                    },
                    closePop: function(){
                    
                    },
                    clear : function(){
                        viewModel.form.model.set('id',null);
                        viewModel.form.model.set('period_id',null);
                        viewModel.form.model.set('biometric_id',null);
                        viewModel.form.model.set('deduction_type',null);
                        viewModel.form.model.set('remarks',null);
                        viewModel.form.model.set('total_amount',null);
                        viewModel.form.model.set('terms',null);
                        viewModel.form.model.set('ammortization',null);
                        viewModel.form.model.set('is_stopped','N');
                        //viewModel.form.model.set('deduction_sched',1);
                    },
                    post : function()
                    {
                        // Swal.fire({
                        //     title: 'Finalize and One Time Deduction',
                        //     text: "You won't be able to revert this!",
                        //     icon: 'warning',
                        //     showCancelButton: true,
                        //     confirmButtonColor: '#3085d6',
                        //     cancelButtonColor: '#d33',
                        //     confirmButtonText: 'Finalize'
                        // }).then((result) => {
                        //     if (result.value) {                       
                        //         viewModel.form.model.set('doc_status','POSTED'); 
                        //         viewModel.buttonHandler.save();
                        //     }
                        // });
                    },
                    showAll : function()
                    {
                        viewModel.ds.maingrid.transport.options.read.url = `installments/list/0`;
                        viewModel.ds.maingrid.read();
                    },

                    downloadNonConfi : function() {
                        let url = 'installments/download-non-confi';
                        window.open(url);
                    },
                    downloadConfi: function() {

                    },

                },
                functions : {
                    showPOP : function(data){
                       
                       var myWindow = $("#pop");
                       
                       myWindow.kendoWindow({
                           width: "810", //1124 - 1152
                           height: "360",
                           title: "Deduction Details - Installment",
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
                        viewModel.form.model.set('period_id',($('#period_id').data('kendoDropDownList').value()!='') ? $('#period_id').data('kendoDropDownList').value() : null );
                        viewModel.form.model.set('deduction_type',($('#deduction_type').data('kendoComboBox').value()!='') ? $('#deduction_type').data('kendoComboBox').value() : null );
                        viewModel.form.model.set('is_stopped',($('#is_stopped').data('kendoDropDownList').value()!='') ? $('#is_stopped').data('kendoDropDownList').value() : null );
                        //viewModel.form.model.set('deduction_sched',($('#deduction_sched').data('kendoDropDownList').value()!='') ? $('#deduction_sched').data('kendoDropDownList').value() : null );
                        viewModel.form.model.set('biometric_id',($('#biometric_id').data('kendoComboBox').value()!='') ? $('#biometric_id').data('kendoComboBox').value() : null );
                    }
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
                //editable : "inline",
                toolbar : [
                    { template: kendo.template($("#template").html()) }
                ],
                selectable: true,
                columns : [
                   
                    {
                        title : "ID",
                        field : "id",
                       
                        width : 60,    
                    },
                    {
                        title : "Employee",
                        field : "employee_name",
                        width : 180,    
                       
                    },
                    {
                        title : "Deduction Type",
                        field : "description",
                        width : 160,    
                    },
                    {
                        title : "Remarks",
                        field : "remarks",
                        
                    },
                    {
                        title : "Amount",
                        field : "total_amount",
                        width : 110,    
                        template : "#=kendo.toString(total_amount,'n2')#",
                        attributes : {
                            style : "text-align:right;"
                        }
                    },
                    {
                        command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
                ]
            });

            $("#employeegrid").kendoGrid({
                dataSource : viewModel.ds.employeegrid,
                pageable : {
                    refresh : true,
                    buttonCount : 5
                },
                noRecords: true,
                filterable : {
                    extra : false,
                    operators: {
                        string: {
                            contains: "Contains"
                        }
                    }
                },
                sortable : true,
                height : 550,
                scrollable: true,
                selectable: true,
                columns : [
                   
                    {
                        title : "ID",
                        field : "biometric_id",
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 60,    
                    },
                    {
                        title : "Description",
                        field : "employee_name",
                        // template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        // width : 120,    
                    },
                ],change : function(e){   
                    let grid = $("#employeegrid").data("kendoGrid");
                    let selectedItem = grid.dataItem(grid.select());

                    viewModel.ds.maingrid.transport.options.read.url = `installments/list/${selectedItem.biometric_id}`;
                    viewModel.ds.maingrid.read();
                }
            });

            $("#period_id").kendoDropDownList({
                dataTextField: "template",
                dataValueField: "id",
                dataSource: viewModel.ds.payperiod,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            $("#deduction_type").kendoComboBox({
                dataSource : viewModel.ds.typesgrid,
                dataTextField: "description",
                dataValueField: "id",
                filter : "contains",
                autoWidth : true,
            });

            $("#biometric_id").kendoComboBox({
                dataSource : viewModel.ds.employeecombobox,
                dataTextField: "employee_name",
                dataValueField: "biometric_id",
                filter : "contains",
                autoWidth : true,
            });

            $("#total_amount").kendoNumericTextBox({ 
                format : "n2",
                change : function(e){
                    let ammortization = viewModel.form.model.total_amount / viewModel.form.model.terms;
                    viewModel.form.model.set('ammortization',ammortization);
                },
            });

            $("#terms").kendoNumericTextBox({ 
                format : "n0",
                change : function(e){
                    let ammortization = viewModel.form.model.total_amount / viewModel.form.model.terms;
                    viewModel.form.model.set('ammortization',ammortization);
                }, 
            });
            $("#ammortization").kendoNumericTextBox({format : "n2"});

            // $("#remarks").kendoTextBox({ rows:2 });
            $("#remarks").kendoTextArea({ });
            
            $("#is_stopped").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: stopOption,
                index: 1,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            // $("#deduction_sched").kendoDropDownList({
            //     dataTextField: "code1",
            //     dataValueField: "id",
            //     dataSource: viewModel.ds.schedlist,
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

            function fixedOptionEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    placeHolder : "",
                    autoWidth: true,
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: fixedOption,
                    optionLabel : "",
                   
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
                    dataSource: viewModel.ds.employeecombobox,
                    filter : "contains"
                   
                });
            }

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                    { id : 'saveAsBtn', type: "button", text: "Save as New", icon: 'save', click : viewModel.buttonHandler.saveAS },
                //  { id : 'postBtn', type: "button", text: "Post", icon: 'print', click : viewModel.buttonHandler.post },
                ]
            });

           

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection