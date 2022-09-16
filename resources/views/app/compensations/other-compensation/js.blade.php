@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createDeduction" > <span class="k-icon k-i-plus k-button-icon"></span>Add Compensation</button>
    </script>

    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                selected : null,
                form : {
                    model : {
                        id : null,
                        period_id : null,
                        compensation_type : null,
                        remarks : null,
                    }
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'other-compensations/list/0',
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
                                    description : { type : 'string' },
                                    remarks: { type : 'string' },
                                    doc_status: { type : 'string' },
                                    template: { type : 'string' },
                                    encoder: { type : 'string' },
                                    // date_release: { type : 'date' },
                                    // man_hours: { type : 'number' },
                                }
                            }
                        }
                    }),
                    typesgrid : new kendo.data.DataSource({
                        transport : {   
                            read : {
                                url : 'other-compensations/list-types',
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
                                url : 'other-compensations/list-payroll-period',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        }
                    }),
                    detailsgrid : new kendo.data.DataSource({
                        transport : {   
                            read : {
                                url : 'other-compensations/list-details/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'other-compensations/create-detail',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(e.status==500){
                                        swal_error(e);
                                    }else{
                                        swal_success(e);
                                        viewModel.ds.detailsgrid.read();
                                    }
                                    
                                }
                            },
                            update : {
                                url : 'other-compensations/update-detail',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(e.status==500){
                                        swal_error(e);
                                    }else{
                                        swal_success(e);
                                        viewModel.ds.detailsgrid.read();
                                    }
                                }
                            },
                            destroy : {
                                url : 'other-compensations/delete-detail',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    viewModel.ds.detailsgrid.read();
                                }
                            },
                            parameterMap: function (data, type) {
                               
                                if(type=='create'||type=='update'){
                                    // data.header_id = viewModel.form.model.id;
                                    $.each(data.models,function(index,value){
                                        value.header_id =  viewModel.form.model.id;
                                    });
                                }

                                return data;
                            },
                        },
                        batch : true,
                        pageSize :14,
                        serverPaging : true,
                        serverFiltering : true,
                        aggregate: [ { field: "total_amount", aggregate: "sum" },],
                        schema : {
                            data : "data",
                            total : "total",
                            model : {
                                id : 'line_id',
                                fields : { 
                                    
                                    biometric_id: { type : 'number',editable : false },
                                    employee_name: { type : 'string' ,editable : false },
                                    exit_status: { type : 'number' , },
                                    total_amount: { type : 'number' , },
                                    header_id: { type : 'number' ,editable : false  },
                                }
                            }
                        }
                    }),
                    employee : new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'other-compensations/employee-list',
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
                    save : async function(e){
                        await viewModel.functions.reAssignValues();
                        
                        var json_data = JSON.stringify(viewModel.form.model);
                        
                        $.post('other-compensations/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                            let url  = `other-compensations/read-header/${data}`;
                            read(url,viewModel);

                            // if(viewModel.form.model.id==null)
                            // {
                            //     let url  = `other-compensations/read-header/${data}`;
                            //     read(url,viewModel);
                            //     setTimeout(function(){
                            //         read(url,viewModel);
                            //     }, 500);
                            // }   
                            viewModel.ds.detailsgrid.transport.options.read.url = `other-compensations/list-details/${data}`;
                            viewModel.ds.detailsgrid.read();

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

                        let url  = `other-compensations/read-header/${data.id}`;
                        read(url,viewModel);

                        viewModel.ds.detailsgrid.transport.options.read.url = `other-compensations/list-details/${data.id}`;
                        viewModel.ds.detailsgrid.read();
                    },
                    createDeduction : function(){
                        viewModel.buttonHandler.clear();
                        viewModel.functions.showPOP();

                        console.log(viewModel.selected);
                        console.log(viewModel.form.model);

                    },
                    closePop: function(){
                    
                    },
                    clear : function(){

                        viewModel.form.model.set('id',null);
                        viewModel.form.model.set('period_id',null);
                        viewModel.form.model.set('compensation_type',viewModel.selected);
                        viewModel.form.model.set('remarks',null);
                        viewModel.form.model.set('doc_status','DRAFT');
                        viewModel.form.model.set('encoded_by',null);
                        viewModel.form.model.set('encoded_on',null);

                        viewModel.ds.detailsgrid.transport.options.read.url = `other-compensations/list-details/0`;
                        viewModel.ds.detailsgrid.read();

                        viewModel.callBack();
                    },
                    post : function()
                    {
                        Swal.fire({
                            title: 'Finalize and Post Compensation',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Finalize'
                        }).then((result) => {
                            if (result.value) {                       
                                viewModel.form.model.set('doc_status','POSTED'); 
                                viewModel.buttonHandler.save();
                            }
                        });
                    }

                },
                functions : {
                    showPOP : function(data){
                       
                       var myWindow = $("#pop");
                       
                       myWindow.kendoWindow({
                           width: "810", //1124 - 1152
                           height: "730",
                           title: "Compensation Details - Other Compensation",
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
                        viewModel.form.model.set('period_id',($('#period_id').data('kendoDropDownList').value()!='') ? $('#period_id').data('kendoDropDownList').value() : 0 );
                        viewModel.form.model.set('compensation_type',($('#compensation_type').data('kendoComboBox').value()!='') ? $('#compensation_type').data('kendoComboBox').value() : 0 );
                        
                    }
                },
                callBack : function(){
                    
                    let grid = $("#detailsgrid").data('kendoGrid');
                
                    if(viewModel.form.model.doc_status=='POSTED'){
                        // grid.hideColumn(3);
                        // grid.showColumn(4);

                        activeToolbar.hide();
                        postedToolbar.show();
                    }else{
                        // grid.hideColumn(4);
                        // grid.showColumn(3);

                        activeToolbar.show();
                        postedToolbar.hide();
                    }
                    viewModel.rebuild();

                },rebuild : function(){
                
                    $("#detailsgrid").empty();

                    if(viewModel.form.model.doc_status=='POSTED'){
                        $("#detailsgrid").kendoGrid({
                            dataSource : viewModel.ds.detailsgrid,
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
                            height : 500,
                            scrollable: true,
                            selectable : true,
                            editable : true,
                            navigatable : true,
                            columns : [
                                {
                                    title : "ID",
                                    field : "",
                                    //template : "#= biometric_id #",
                                    template : "#if(biometric_id==null){# # }else{ # #= biometric_id # # }#",
                                    width : 90,    
                                },
                                {
                                    title : "Employee",
                                    field : "employee_name",
                                    editor : employeeEditor,
                                    template : "#if(biometric_id==0){#  #}else {# #= employee_name #  #}#"
                                },
                                {
                                    title : "Amount",
                                    field : "total_amount",
                                    width : 130,  
                                    //template : "#=kendo.toString(total_amount,'n2')#",
                                    template : "#if(total_amount==0){#  #}else {# #=kendo.toString(total_amount,'n2')#  #}#",
                                    attributes : {
                                        style : 'text-align:right;'
                                    },
                                    footerTemplate: "<div style='text-align:right;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" 

                                },
                            
                            ],
                            
                        });
                    }else{
                        $("#detailsgrid").kendoGrid({
                            dataSource : viewModel.ds.detailsgrid,
                            pageable : {
                                refresh : true,
                                buttonCount : 5
                            },
                            toolbar : ['save'],
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
                            height : 500,
                            scrollable: true,
                            selectable : true,
                            editable : true,
                            navigatable : true,
                            columns : [
                                {
                                    title : "ID",
                                    field : "",
                                    //template : "#= biometric_id #",
                                    template : "#if(biometric_id==null){# # }else{ # #= biometric_id # # }#",
                                    width : 90,    
                                },
                                {
                                    title : "Employee",
                                    field : "employee_name",
                                    editor : employeeEditor,
                                    template : "#if(biometric_id==0){#  #}else {# #= employee_name #  #}#"
                                },
                                {
                                    title : "Amount",
                                    field : "total_amount",
                                    width : 130,  
                                    //template : "#=kendo.toString(total_amount,'n2')#",
                                    template : "#if(total_amount==0){#  #}else {# #=kendo.toString(total_amount,'n2')#  #}#",
                                    attributes : {
                                        style : 'text-align:right;'
                                    },
                                    footerTemplate: "<div style='text-align:right;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" 

                                },
                            
                            ],
                            
                        });
                    }
                    
                    
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
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 60,    
                        attributes : {
                            style : "font-size:9pt;"
                        }
                    },
                    {
                        title : "Payroll Period",
                        field : "perio_id",
                        template : "#= template #",
                        width : 150,    
                        attributes : {
                            style : "font-size:9pt;"
                        }
                    },
                    {
                        title : "Remarks",
                        field : "remarks",
                        // template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        // width : 120, 
                        template: "#= description # "   
                    },
                    {
                        title : "Status",
                        field : "doc_status",
                        width : 80,    
                        attributes : {
                            style : "font-size:9pt;"
                        }
                    },
                    {
                        title : "Encoded By",
                        field : "encoder",
                        width : 110,    
                        attributes : {
                            style : "font-size:9pt;"
                        }
                    },
                    {
                        command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
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
                        width : 55,    
                        attributes : {
                            style : "font-size:10pt;"
                        }
                    },
                    {
                        title : "Description",
                        field : "description",
                        attributes : {
                            style : "font-size:10pt;"
                        }
                        // template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        // width : 120,    
                    },
                ],change : function(e){   
                    let grid = $("#typesgrid").data("kendoGrid");
                    let selectedItem = grid.dataItem(grid.select());

                    let oneTimeUrl = `other-compensations/list/${selectedItem.id}`;

                    viewModel.set('selected',selectedItem.id);

                    viewModel.ds.maingrid.transport.options.read.url = oneTimeUrl;
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

            $("#compensation_type").kendoComboBox({
                dataSource : viewModel.ds.typesgrid,
                dataTextField: "description",
                dataValueField: "id",
                filter : "contains",
                autoWidth : true,
            });

            $("#remarks").kendoTextBox({ });
            


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