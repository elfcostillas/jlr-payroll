@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        id : null,
                        remarks : null,
                        date_from : null,
                        date_to : null,
                        biometric_id : null
                    }
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manual-dtr/list',
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
                                    biometric_id : {type : 'number',editable :false },
                                    name : { type:'string' },
                                    remarks: { type:'string' },
                                    date_from: { type:'date' },
                                    date_to: { type:'date' },
                                    empname: { type:'string' },
                                }
                            }
                        }
                    }),
                    employees : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manual-dtr/employee-list',
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
                                    biometric_id : {type : 'number',editable :false },
                                    empname : { type:'string' },
                                }
                            }
                        }
                    }),
                },
                functions : {
                    showPOP : function()
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "864", //1124 - 1152
                            height: "710",
                            title: "Manual DTR Form",
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
                    reAssignValues : function (){
                        viewModel.form.model.set('date_from',kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('date_to',kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('biometric_id',$('#biometric_id').data('kendoComboBox').value());
                    },
                },
                toolbarHandler : {

                },
                buttonHandler : {
                    view : async function (e)
                    {
                        e.preventDefault(); 

                        viewModel.functions.showPOP();

                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        // viewModel.set('selected',data);

                        // let url  = `employee-master-data/read/${data.id}`;
                        // await viewModel.functions.prepareForm(data);
                        // read(url,viewModel);
                    },
                    save : async function(e){

                        await viewModel.functions.reAssignValues(); 

                        var json_data = JSON.stringify(viewModel.form.model);

                        $.post('manual-dtr/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);
                           
                            viewModel.ds.maingrid.read();
                            //viewModel.maingrid.formReload(data);
                        })
                        .fail(function(data){
                           swal_error(data);
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });
                    },
                    clear : function(e){

                    },
                    print : function(){

                    },
                },
                closePop : function ()
                {

                }
            });

            $("#date_from").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#doc_id").kendoTextBox({ });
            $("#remarks").kendoTextBox({ });

            $("#biometric_id").kendoComboBox({ 
                dataTextField: "empname",
                dataValueField: "biometric_id",
                dataSource: viewModel.ds.employees,
            });

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                    { id : 'clearBtn', type: "button", text: "Print", icon: 'print', click : viewModel.buttonHandler.print },
                ]
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
                toolbar : [{ name :'create'}],
                editable : "inline",
                columns : [
                    {
                        title : "ID",
                        field : "id",
                        width : 80,    
                    },
                    {
                        title : "BIO ID",
                        field : "biometric_id",
                        width : 80,    
                    },
                    {
                        title : "Employee Name",
                        field : "empname",
                        width : 135,    
                    },
                    {
                        title : "Date From",
                        field : "date_from",
                        width : 100,    
                        template : "#= (data.date_from) ? kendo.toString(data.date_from,'MM/dd/yyyy') : ''  #",
                    },
                    {
                        title : "Date To",
                        field : "date_to",
                        width : 100,    
                        template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                    },
                    {
                        title : "Remarks",
                        field : "remarks",
                           
                    },
                     {
                        title : "Encoded By",
                        field : "name",
                        width : 135,    
                    },
                    {
                        command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                    
                ]
            });

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection