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
                                url : 'one-time/list/0',
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
                                url : 'one-time/list-types',
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
                                url : 'one-time/list-payroll-period',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        }
                    }),
                },
                toolbarHandler : {

                },
                buttonHandler : {
                    view : function(e) {
                        e.preventDefault(); 
                        viewModel.functions.showPOP();

                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        let url  = `one-time/read-header/${data.id}`;
                        read(url,viewModel);
                    },
                    createDeduction : function(){
                        viewModel.functions.showPOP();
                    },
                    closePop: function(){
                    
                    },
                    clear : function(){

                        viewModel.form.model.set('id',null);
                        viewModel.form.model.set('period_id',null);
                        viewModel.form.model.set('deduction_type',null);
                        viewModel.form.model.set('remarks',null);
                        viewModel.form.model.set('doc_status','DRAFT');
                        viewModel.form.model.set('encoded_by',null);
                        viewModel.form.model.set('encoded_on',null);
                    },

                },
                functions : {
                    showPOP : function(data){
                       
                       var myWindow = $("#pop");
                       
                       myWindow.kendoWindow({
                           width: "664", //1124 - 1152
                           height: "710",
                           title: "Deduction Details",
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
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 60,    
                    },
                    {
                        title : "Payroll Period",
                        field : "perio_id",
                        template : "#= template #",
                        width : 170,    
                    },
                    {
                        title : "Remarks",
                        field : "description",
                        // template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        // width : 120,    
                    },
                    {
                        title : "Status",
                        field : "doc_status",
                        width : 80,    
                    },
                    {
                        title : "Encoded By",
                        field : "encoder",
                        width : 110,    
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

                    console.log(selectedItem);

                    // let empListUrl = `manage-dtr/get-employee-list/${selectedItem.id}`;
                    // viewModel.ds.subgrid.transport.options.read.url = empListUrl;
                    // viewModel.ds.subgrid.read();
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
                    autoWidth: true,
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: fixedOption,
                   
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