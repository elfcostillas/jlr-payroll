@section('jquery')
<script id="template" type="text/x-kendo-template">
    <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createDTR" > <span class="k-icon k-i-plus k-button-icon"></span>Create Memo</button>
</script>
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        id : null,
                        biometric_id : null,
                        memo_to : null,
                        memo_from : null,
                        memo_date : null,
                        memo_subject : null,
                        memo_upper_body : null,
                        memo_lower_body : null,
                        prep_by_text : null,
                        prep_by_name : null,
                        prep_by_position : null,
                        noted_by_text : null,
                        noted_by_name : null,
                        noted_by_position : null,
                        noted_by_text_dept : null,
                        noted_by_name_dept : null,
                        noted_by_position_dept : null,
                    }
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'tardiness-to-employee/list',
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
                                    //date_from: { type:'date' },
                                    //date_to: { type:'date' },
                                    empname: { type:'string' },
                                }
                            }
                        }
                    }),
                    employees : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'tardiness-to-employee/employee-list',
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
                                    employee_name : { type:'string' },
                                }
                            }
                        }
                    }),
                    
                    periods : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manual-dtr/weekly-period',
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
                                    date_from : { type: "date" },
                                    date_to : { type: "date" },
                                    template : { type: "string" },
                                }
                            }
                        }
                    })
                },
                functions : {
                    showPOP : function()
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "1124", //1124 - 1152
                            height: "660",
                            title: "Memo Form",
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
                        //viewModel.form.model.set('date_from',kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        //viewModel.form.model.set('date_to',kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('period_id',$("#period_id").data('kendoDropDownList').value());
                        
                        viewModel.form.model.set('biometric_id',$('#biometric_id').data('kendoComboBox').value());
                    },
                    prepareForm :function(data){

                    }
                },
                toolbarHandler : {
                    
                },
                buttonHandler : {
                    createDTR : function()
                    {
                        viewModel.buttonHandler.clear();
                        viewModel.functions.showPOP();

                        let url = `tardiness-to-employee/read/0`;
                        read(url,viewModel);

                        console.log(viewModel.form.model);
                    },
                    view : async function (e)
                    {
                        e.preventDefault(); 

                        viewModel.functions.showPOP();

                        // var tr = $(e.target).closest("tr");
                        // var data = this.dataItem(tr);

                        // // viewModel.set('selected',data);

                        // let url  = `manual-dtr/header/${data.id}`;
                        // await viewModel.functions.prepareForm(data);
                        // read(url,viewModel);

                        // let detailUrl = `manual-dtr/details/${data.id}`;
                        // viewModel.ds.dtrgrid.transport.options.read.url = detailUrl;
                        // viewModel.ds.dtrgrid.read();
                    },
                    save : async function(e){

                        // await viewModel.functions.reAssignValues(); 

                        // var json_data = JSON.stringify(viewModel.form.model);

                        // $.post('manual-dtr/save',{
                        //     data : json_data
                        // },function(data,staus){
                        //     swal_success(data);

                        //     let url  = `manual-dtr/header/${data}`;
                        //     read(url,viewModel);

                        //     let detailUrl = `manual-dtr/details/${data}`;
                        //     viewModel.ds.dtrgrid.transport.options.read.url = detailUrl;
                        //     viewModel.ds.dtrgrid.read();

                        //     viewModel.ds.maingrid.read();
                        //     //viewModel.maingrid.formReload(data);
                        // })
                        // .fail(function(data){
                        //    swal_error(data);
                        // }).always(function() {
                        //     //viewModel.maingrid.ds.read();
                        // });
                    },
                    clear : function(e){
                        // viewModel.form.model.set('id',null);
                        // viewModel.form.model.set('remarks',null);
                        // //viewModel.form.model.set('date_from',null);
                        // //viewModel.form.model.set('date_to',null);
                        // viewModel.form.model.set('period_id',0);
                        // viewModel.form.model.set('biometric_id',null);

                        // let detailUrl = `manual-dtr/details/0`;
                        // viewModel.ds.dtrgrid.transport.options.read.url = detailUrl;
                        // viewModel.ds.dtrgrid.read();
                        
                    },
                    print : function(){

                        let url = `manual-dtr/print/${viewModel.form.model.id}`;
                        window.open(url);
                    },
                },
                closePop : function ()
                {

                },
                callBack : function()
                {

                }
            });

            $("#memo_date").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

           $("#biometric_id").kendoTextBox({ });

            $("#memo_to").kendoComboBox({
                dataSource : viewModel.ds.employees,
                dataTextField: "employee_name",
                dataValueField: "biometric_id",
                autoWidth: true,
                filter : "contains",
                // optionLabel: {
                //     template: "Select Period",
                //     id: 0
                // }
            });

            $("#memo_subject").kendoTextBox({ });

            $("#memo_upper_body").kendoTextArea({ rows: 2 });
            $("#memo_lower_body").kendoTextArea({ rows : 10 });
    
            $("#prep_by_name").kendoTextBox({ });
            $("#prep_by_position").kendoTextBox({ });

            $("#noted_by_name").kendoTextBox({ });
            $("#noted_by_position").kendoTextBox({ });

            $("#noted_by_name_dept").kendoTextBox({ });
            $("#noted_by_position_dept").kendoTextBox({ });

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
                height : 450,
                scrollable: true,
                toolbar : [
                    { template: kendo.template($("#template").html()) }
                ],
                editable : false,
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

            /*
             change : function(e){
                let v = e.sender.value();
                let nv = v.substring(0,2)+':'+ v.substring(2,4);
                alert(nv);
              }
              */

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection