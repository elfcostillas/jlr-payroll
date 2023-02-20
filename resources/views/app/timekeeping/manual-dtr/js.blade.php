@section('jquery')
<script id="template" type="text/x-kendo-template">
    <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createDTR" > <span class="k-icon k-i-plus k-button-icon"></span>Create DTR</button>
</script>
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        id : null,
                        remarks : null,
                        // date_from : null,
                        // date_to : null,
                        biometric_id : null,
                        period_id : null,

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
                    dtrgrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manual-dtr/details/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'manual-dtr/detail-update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    viewModel.ds.dtrgrid.read();
                                }
                            },
                            parameterMap: function (data, type) {
                                if(type=='update'){
                                    data.dtr_date = kendo.toString(data.dtr_date,'yyyy-MM-dd');
                                    
                                    if(data.time_in!=null){
                                        data.time_in = pad(data.time_in,4);
                                        data.time_in = (data.time_in.includes(':')) ? data.time_in : data.time_in.substring(0,2)+':'+ data.time_in.substring(2,4);
                                    }

                                    if(data.time_out!=null){
                                        data.time_out = pad(data.time_out,4);
                                        data.time_out = (data.time_out.includes(':')) ? data.time_out : data.time_out.substring(0,2)+':'+ data.time_out.substring(2,4);
                                    }

                                    if(data.overtime_in!=null){
                                        data.overtime_in = pad(data.overtime_in,4);
                                        data.overtime_in = (data.overtime_in.includes(':')) ? data.overtime_in : data.overtime_in.substring(0,2)+':'+ data.overtime_in.substring(2,4);
                                    }

                                    if(data.overtime_out!=null){
                                        data.overtime_out = pad(data.overtime_out,4);
                                        data.overtime_out = (data.overtime_out.includes(':')) ? data.overtime_out : data.overtime_out.substring(0,2)+':'+ data.overtime_out.substring(2,4);
                                    }

                                    if(data.time_in2!=null){
                                        data.time_in2 = pad(data.time_in2,4);
                                        data.time_in2 = (data.time_in2.includes(':')) ? data.time_in2 : data.time_in2.substring(0,2)+':'+ data.time_in2.substring(2,4);
                                    }

                                    if(data.time_out2!=null){
                                        data.time_out2 = pad(data.time_out2,4);
                                        data.time_out2 = (data.time_out2.includes(':')) ? data.time_out2 : data.time_out2.substring(0,2)+':'+ data.time_out2.substring(2,4);
                                    }

                                    if(data.overtime_in2!=null){
                                        data.overtime_in2 = pad(data.overtime_in2,4);
                                        data.overtime_in2 = (data.overtime_in2.includes(':')) ? data.overtime_in2 : data.overtime_in2.substring(0,2)+':'+ data.overtime_in2.substring(2,4);
                                    }

                                    if(data.overtime_out2!=null){
                                        data.overtime_out2 = pad(data.overtime_out2,4);
                                        data.overtime_out2 = (data.overtime_out2.includes(':')) ? data.overtime_out2 : data.overtime_out2.substring(0,2)+':'+ data.overtime_out2.substring(2,4);
                                    }
                                    
                                }

                                return data;
                            }
                        },
                        pageSize :11,
                        schema : {
                          
                            model : {
                                id : 'line_id',
                                fields : {
                                    header_id : { type: "number",editable:false },
                                    biometric_id : { type: "number",editable:false  },
                                    dtr_date : { type: "date",editable:false  },
                                    time_in : { type: "string" },
                                    time_out : { type: "string" },
                                    overtime_in : { type: "string" },
                                    overtime_out : { type: "string" },
                                    overtime_in2 : { type: "string" },
                                    overtime_out2 : { type: "string" },
                                    overtime_hrs : { type: "number" },
                                    reg_hrs : { type: "number" },
                                    reg_day : { type: "number" },
                                    rd_hrs : { type: "number" },
                                    rd_ot : { type: "number" },
                                    sh_hrs : { type: "number" },
                                    sh_ot : { type: "number" },
                                    lh_hrs : { type: "number" },
                                    lh_ot : { type: "number" },
                                    remarks : { type: "string" },
                                    dayname : { type: "string",editable:false },
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
                            width: "924", //1124 - 1152
                            height: "750",
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
                    },
                    view : async function (e)
                    {
                        e.preventDefault(); 

                        viewModel.functions.showPOP();

                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        // viewModel.set('selected',data);

                        let url  = `manual-dtr/header/${data.id}`;
                        await viewModel.functions.prepareForm(data);
                        read(url,viewModel);

                        let detailUrl = `manual-dtr/details/${data.id}`;
                        viewModel.ds.dtrgrid.transport.options.read.url = detailUrl;
                        viewModel.ds.dtrgrid.read();
                    },
                    save : async function(e){

                        await viewModel.functions.reAssignValues(); 

                        var json_data = JSON.stringify(viewModel.form.model);

                        $.post('manual-dtr/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                            let url  = `manual-dtr/header/${data}`;
                            read(url,viewModel);

                            let detailUrl = `manual-dtr/details/${data}`;
                            viewModel.ds.dtrgrid.transport.options.read.url = detailUrl;
                            viewModel.ds.dtrgrid.read();

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
                        viewModel.form.model.set('id',null);
                        viewModel.form.model.set('remarks',null);
                        //viewModel.form.model.set('date_from',null);
                        //viewModel.form.model.set('date_to',null);
                        viewModel.form.model.set('period_id',0);
                        viewModel.form.model.set('biometric_id',null);
                        
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

            $("#date_from").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#period_id").kendoDropDownList({
                dataSource : viewModel.ds.periods,
                dataTextField: "template",
                dataValueField: "id",
                optionLabel: {
                    template: "Select Period",
                    id: 0
                }
            });

            $("#doc_id").kendoTextBox({ });
            $("#remarks").kendoTextBox({ });

            $("#biometric_id").kendoComboBox({ 
                dataTextField: "empname",
                dataValueField: "biometric_id",
                filter: "contains",
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

            $("#dtrgrid").kendoGrid({
                dataSource : viewModel.ds.dtrgrid,
                pageable : {
                    refresh : true,
                    buttonCount : 5
                },
                noRecords: true,
                //filterable : true,
                //sortable : true,
                height : 374,
                scrollable: true,
                editable : "inline",
                columns : [
                    {
                        width : 190,
                        command : ['edit'],
                        locked: true,
                    },
                    {
                        title : "Day",
                        field : "dayname",
                        width : 80,    
                    },
                    {
                        title : "Date From",
                        field : "dtr_date",
                        width : 100,    
                        template : "#= (data.dtr_date) ? kendo.toString(data.dtr_date,'MM/dd/yyyy') : ''  #",
                    },
                    {
                        title : "IN A.M.",
                        field : "time_in",
                        width : 90,    
                        editor : timeEdior
                    },
                    {
                        title : "OUT A.M.",
                        field : "time_out",
                        width : 90,    
                        editor : timeEdior
                    },
                    {
                        title : "IN P.M.",
                        field : "time_in2",
                        width : 90,    
                        editor : timeEdior
                    },
                    {
                        title : "OUT P.M.",
                        field : "time_out2",
                        width : 90,    
                        editor : timeEdior
                    },
                    {
                        title : "Hrs",
                        field : "reg_hrs",
                        width : 90,    
                    },
                    {
                        title : "Reg Day",
                        field : "reg_day",
                        width : 90,    
                    },
                    {
                        title : "IN (OT)",
                        field : "overtime_in",
                        width : 90,    
                    },
                    {
                        title : "OUT (OT)",
                        field : "overtime_out",
                        width : 90,    
                    },
                    {
                        title : "IN (OT)",
                        field : "overtime_in2",
                        width : 90,    
                    },
                    {
                        title : "OUT (OT)",
                        field : "overtime_out2",
                        width : 90,    
                    },
                    {
                        title : "HRS (OT)",
                        field : "overtime_hrs",
                        width : 90,    
                    },

                    // {
                    //     title : "RD Hrs",
                    //     field : "rd_hrs",
                    //     width : 90,    
                    // },
                    // {
                    //     title : "RD OT",
                    //     field : "rd_ot",
                    //     width : 90,    
                    // },
                    // {
                    //     title : "SH Hrs",
                    //     field : "sh_hrs",
                    //     width : 90,    
                    // },
                    // {
                    //     title : "SH OT",
                    //     field : "sh_ot",
                    //     width : 90,    
                    // },
                    // {
                    //     title : "LH hrs",
                    //     field : "lh_hrs",
                    //     width : 90,    
                    // },
                    // {
                    //     title : "LH OT",
                    //     field : "lh_ot",
                    //     width : 90,    
                    // },

                    {
                        title : "Remarks",
                        field : "remarks",
                        width : 400,    
                    },
                    // {
                    //     //command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                    //     // attributes : { style : 'font-size:10pt !important;'},
                    //     width : 190,
                    //     command : ['edit']  
                    // },
                    
                ]
            });

            function timeEdior(container, options)
            {
                $('<input name="' + options.field + '" />')
                .appendTo(container)
                .kendoTextBox({
                    change : function(e)
                    {
                        // let v = e.sender.value();
                        // let nv = v.substring(0,2)+':'+ v.substring(2,4);
                        // e.sender.value = nv;
                        // e.sender.text = nv;
                   

                        // console.log(e.model);

                        // let grid = $("#dtrgrid").data("kendoGrid");
                        // let selectedRow = grid.dataItem(grid.select());
                        // //console.log(e.sender);
                        // //console.log(e.sender.text());
                        // //console.log(selectedRow.set("schedule_desc"));
                        // selectedRow.set("schedule_desc",e.sender.text());
                    },
                    edit : function(e){
                        console.log(e.model);
                    }
                    
                });
            }

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