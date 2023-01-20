@section('jquery')
<script type="text/x-kendo-template" id="logDrawer">
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.drawLogs }">
        </span>&nbsp; Draw Logs
    </button>

    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.compute }">
        </span>&nbsp; Compute
    </button>

    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.clear }">
        </span>&nbsp; Clear
    </button>
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.iPrint }">
    </span>&nbsp; Print
</button>
</script>	

<script type="text/x-kendo-template" id="subgidcommand">
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.makeDTR }">
        </span>&nbsp; Make Blank
    </button>

    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.print }">
        </span>&nbsp; Print
    </button>
</script>	

    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                selectedPeriod : null,
                selectedEmployee : null,
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'payroll-period/list',
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
                                    date_from : { type : 'date' },
                                    date_to: { type : 'date' },
                                    date_release: { type : 'date' },
                                    man_hours: { type : 'number' },
                                }
                            }
                        }
                    }),
                    subgrid :  new kendo.data.DataSource({ //timekeeping/manage-dtr/get-employee-list/1
                        transport : {
                            read : {
                                url : 'manage-dtr/get-employee-list/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                           
                        },
                        pageSize :12,
                        serverPaging : true,
                        serverFiltering : true,
                        schema : {
                            data : "data",
                            total : "total",
                            model : {
                                id : 'id',
                                fields : {
                                    biometric_id : { type : 'number',editable : false },
                                    empname : { type: 'string'}
                                }
                            }
                        }
                    }),
                    dtrgrid :  new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manage-dtr/get-employee-dtr-logs/0/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'manage-dtr/update-dtr',
                                type : 'post',
                                dataType : 'json',
                                complete : function (e){
                                    viewModel.ds.dtrgrid.read();
                                }
                            },
                            parameterMap : function(data,type)
                            {
                                
                                if(type=='update'){
                                   
                                    $.each(data.models,function(index,value){
                                        value.dtr_date =  kendo.toString(value.dtr_date,'yyyy-MM-dd');
                                    
                                        if(value.time_in!=null){
                                            value.time_in = pad(value.time_in,4);
                                            value.time_in = (value.time_in.includes(':')) ? value.time_in : value.time_in.substring(0,2)+':'+ value.time_in.substring(2,4);
                                        }

                                        if(value.time_out!=null){
                                            value.time_out = pad(value.time_out,4);
                                            value.time_out = (value.time_out.includes(':')) ? value.time_out : value.time_out.substring(0,2)+':'+ value.time_out.substring(2,4);
                                        }

                                        if(value.ot_in!=null){
                                           
                                            value.ot_in = pad(value.ot_in,4);
                                            value.ot_in = (value.ot_in.includes(':')) ? value.ot_in : value.ot_in.substring(0,2)+':'+ value.ot_in.substring(2,4);
                                        }

                                        if(value.ot_out!=null){
                                            value.ot_out = pad(value.ot_out,4);
                                            value.ot_out = (value.ot_out.includes(':')) ? value.ot_out : value.ot_out.substring(0,2)+':'+ value.ot_out.substring(2,4);
                                        }
                                    });
                                }
                                return data;
                            }
                           
                        },
                        batch: true,
                        //autoSync: true,
                        pageSize :16,
                        schema : {
                            // data : "data",
                            // total : "total",
                            model : {
                                id : 'id',
                                fields : {
                                    biometric_id : { type:'number',editable : false  },
                                    day_name : { type:'string',editable : false  },
                                    dtr_date : { type:'date',editable : false  },
                                    time_in : { type:'string', },
                                    time_out : { type:'string', },
                                    ot_in : { type:'string', },
                                    ot_out : { type:'string', },
                                    late : { type:'number', },
                                    late_eq : { type:'number', },
                                    under_time : { type:'number', },
                                    over_time : { type:'number', },
                                    night_diff : { type:'number', },
                                    night_diff_ot : { type:'number', },
                                    schedule_id : { type:'number', },
                                    schedule_desc : { type: 'string' },
                                    ndays : { type:'number', },
                                    restday_hrs : { type:'number', },
                                    restday_ot : { type:'number', },
                                    restday_nd : { type:'number', },
                                    restday_ndot: { type:'number', },
                                    reghol_pay : { type:'number', },
                                    reghol_hrs : { type:'number', },
                                    reghol_ot : { type:'number', },
                                    reghol_rd : { type:'number', },
                                    reghol_rdnd : { type:'number', },
                                    reghol_rdot : { type:'number', },
                                    reghol_nd : { type:'number', },
                                    reghol_ndot: { type:'number', },
                                    sphol_pay : { type:'number', },
                                    sphol_hrs : { type:'number', },
                                    sphol_ot : { type:'number', },
                                    sphol_rd : { type:'number', },
                                    sphol_rdnd : { type:'number', },
                                    sphol_rdot : { type:'number', },
                                    sphol_nd : { type:'number', },
                                    sphol_ndot : { type:'number', },
                                    dblhol_pay : { type:'number', },
                                    dblhol_hrs : { type:'number', },
                                    dblhol_ot : { type:'number', },
                                    dblhol_rd : { type:'number', },
                                    dblhol_rdnd : { type:'number', },
                                    dblhol_rdot : { type:'number', },
                                    dblhol_nd : { type:'number', },
                                    dblhol_ndot : { type:'number', },

                                    reghol_rdndot : { type:'number', },
                                    sphol_rdndot : { type:'number', },
                                    dblhol_rdndot  : { type:'number', },

                                    holiday_type : { type:'string', editable : false }
                                }
                            }
                        },
                        aggregate : [
                            { field : "late" , aggregate: "sum" },
                            { field : "late_eq" , aggregate: "sum" },
                            { field : "under_time" , aggregate: "sum" },
                            { field : "over_time" , aggregate: "sum" },
                            { field : "night_diff" , aggregate: "sum" },
                            { field : "night_diff_ot" , aggregate: "sum" },
                            { field : "ndays" , aggregate: "sum" },

                            { field : "lh_ot" , aggregate: "sum" },
                            { field : "lhot_rd" , aggregate: "sum" },
                            { field : "sh_ot" , aggregate: "sum" },
                            { field : "shot_rd" , aggregate: "sum" },
                            { field : "sun_ot" , aggregate: "sum" },
                           
                        ]
                    }),
                    sched : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manage-dtr/get-employee-schedules',
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
                    prepare : function(e){
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);

                        $.post('manage-dtr/prepare',{
                            period_id : data.id
                        },function(){
                            let empListUrl = `manage-dtr/get-employee-list/${data.id}`;
                            viewModel.ds.subgrid.transport.options.read.url = empListUrl;
                            viewModel.ds.subgrid.read();

                        });
                    },
                    download : function(e){
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);

                        let url = `manage-dtr/download/${data.id}`;
                        window.open(url);

                    },
                    manage : function(e){
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);
                        
                        let grid = $("#subgrid").data("kendoGrid");
                        let row = grid.table.find("[data-uid=" + data.uid + "]");
                        grid.select(row)

                        if(viewModel.selectedPeriod!=null)
                        {
                            viewModel.functions.showPop(data);
                            viewModel.set('selectedEmployee',data.biometric_id);

                            let rawLogsUrl = `manage-dtr/get-employee-raw-logs/${viewModel.selectedPeriod.id}/${data.biometric_id}`;
                            // viewModel.ds.rawlogs.transport.options.read.url = rawLogsUrl;
                            // viewModel.ds.rawlogs.read();
                            $.get(rawLogsUrl,function(data){
                              
                                $("#raw-logs").html(data);
                            });


                            let dtrUrl = `manage-dtr/get-employee-dtr-logs/${viewModel.selectedPeriod.id}/${data.biometric_id}`;
                            viewModel.ds.dtrgrid.transport.options.read.url = dtrUrl;
                            viewModel.ds.dtrgrid.read();


                        }
                        
                    },
                    closePop : function(){

                    },
                    drawLogs : function()
                    {
                        
                        $.post('manage-dtr/draw-logs',{
                            period_id : viewModel.selectedPeriod.id,
                            biometric_id : viewModel.selectedEmployee
                        },function(){
                            viewModel.ds.dtrgrid.read();
                        });
                    },
                    compute : function()
                    {
                        
                        $.post('manage-dtr/compute-logs',{
                            period_id : viewModel.selectedPeriod.id,
                            biometric_id : viewModel.selectedEmployee
                        },function(){
                            viewModel.ds.dtrgrid.read();
                        });
                    },
                    makeDTR : function (){

                    },
                    print : function (){
                        let url = `manage-dtr/print/${viewModel.selectedPeriod.id}`;
                        window.open(url);
                    },   
                    clear : function()
                    {
                        $.post('manage-dtr/clear-logs',{
                            period_id : viewModel.selectedPeriod.id,
                            biometric_id : viewModel.selectedEmployee
                        },function(){
                            viewModel.ds.dtrgrid.read();
                        });
                    },
                    iPrint : function()
                    {
                        let url = `manage-dtr/iprint/${viewModel.selectedPeriod.id}/${viewModel.selectedEmployee}`;
                        window.open(url);
                    }

                },
                functions : {
                    showPop : function(data)
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "1134", //1124 - 1152
                            height: "700",
                            //title: "Employee Information",
                            visible: false,
                            animation: false,
                            actions: [
                                "Pin",
                                "Minimize",
                                "Maximize",
                                "Close"
                            ],
                            close : viewModel.buttonHandler.closePop,
                            position : {
                                top : 0
                            }
                        }).data("kendoWindow").center().open().title('Manage DTR : '+data.empname + ' - ' +data.biometric_id  ) ;
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
                selectable : true,
                columns : [
                    {
                        title : "ID",
                        field : "id",
                        width : 80,    
                    },
                    {
                        title : "Start Date",
                        field : "date_from",
                        template : "#= (data.date_from) ? kendo.toString(data.date_from,'MM/dd/yyyy') : ''  #",
                        
                    },
                    {
                        title : "End Date",
                        field : "date_to",
                        template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        
                    },
                    {
                        title : "Man Hours",
                        field : "man_hours",
                        //template : "#=  : ''  #",
                        width : 110,    
                    },
                    {
                        command: [
                            { text : 'Prepare',click : viewModel.buttonHandler.prepare , },
                            { text : 'Download',click : viewModel.buttonHandler.download , }
                        ],
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 160
                    },
                  
                ],
                change : function(e){
                    var grid = $("#maingrid").data("kendoGrid");
                    var selectedItem = grid.dataItem(grid.select());

                    viewModel.set('selectedPeriod',selectedItem);

                    //console.log(selectedItem.id);
                    let empListUrl = `manage-dtr/get-employee-list/${selectedItem.id}`;
                    viewModel.ds.subgrid.transport.options.read.url = empListUrl;
                    viewModel.ds.subgrid.read();

                }
            });

            $("#subgrid").kendoGrid({
                dataSource : viewModel.ds.subgrid,
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
                selectable : true,
                toolbar : [
                    {
                       template : kendo.template($("#subgidcommand").html())
                    }
                ],
                columns : [
                    {
                        title : "Bio ID",
                        field : "biometric_id",
                        width : 80,    
                    },
                    {
                        title : "Employee Name",
                        field : "empname",
                        
                    },
                    {
                        command: { text : 'Edit',click : viewModel.buttonHandler.manage },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
                ],
                
            });

            $("#dtrgrid").kendoGrid({
                dataSource : viewModel.ds.dtrgrid,
                pageable : {
                    refresh : true,
                    buttonCount : 5
                },
                noRecords: true,
                //filterable : true,
                editable: true,
                //height : 550,
                scrollable: true,
                selectable : true,
                navigatable : true,
                dataBound: function() {
                    for (var i =18; i < this.columns.length; i++) {
                        this.autoFitColumn(i);
                    }
                },
                toolbar : [
                    {
                        name : 'save'
                    },
                    {
                        name : 'cancel'
                    },
                    {
                       template : kendo.template($("#logDrawer").html())
                    }
                ],
                columns : [
                    {
                        title : "Day",
                        field : "day_name",
                        width : 50,
                         attributes: {
                            style: "font-size: 9pt"
                        },
                        headerAttributes: {
                            style: "font-size: 9pt"
                        },
                        locked : true,
                    },
                    {
                        title : "Date",
                        field : "dtr_date",
                        template : "#= (data.dtr_date) ? kendo.toString(data.dtr_date,'MM/dd/yyyy')  : ''  #",
                        width : 90,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                        locked : true,  
                    },
                    {
                        title : "Schedule",
                        field : "schedule_id",
                        //template : "#= (schedule_desc) ? schedule_desc : data.schedule_desc #",
                        template : "#if(data.schedule_desc==null){# #} else {#  #=data.schedule_desc# #}#",
                        //template : "#= schedule_desc #",  #=data.schedule_id #
                        //template : "#= if(data.schedule_desc==null) #"
                       
                        width : 110,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        editor : scheduleEditor ,
                        locked : true,
                    },
                    {
                        title : "Time In",
                        field : "time_in",
                        width : 70,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",

                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },    
                        template : "# if(time_in=='00:00'||time_in==null){#  #} else{# #= time_in #  #}#",
                        locked : true,
                    },
                    {
                        title : "Time Out",
                        field : "time_out",
                        width : 70,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        template : "# if(time_out=='00:00'||time_out==null){#  #} else{# #= time_out #  #}#",
                        locked : true,
                    },
                    {
                        title : "Days",
                        field : "ndays",
                        width : 60,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        template : "# if(ndays==0){#  #} else{# #= ndays #  #}#",
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        aggregates : ['sum'], 
                        footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n1')#</div>" 
                    },
                    {
                        title : "Late",
                        field : "late",
                        width : 60,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        template : "# if(late==0){#  #} else{# #= late #  #}#",
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        aggregates : ['sum'], 
                        footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n0')#</div>" 
                    },
                    {
                        title : "Late(Hrs)",
                        field : "late_eq",
                        width : 70,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        template : "# if(late_eq==0){#  #} else{# #= late_eq #  #}#",
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        aggregates : ['sum'], 
                        footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" 
                    },
                    {
                        title : "UT",
                        field : "under_time",
                        width : 60,
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        template : "# if(under_time==0){#  #} else{# #= under_time #  #}#",
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        aggregates : ['sum'], 
                        footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>",
                        editor : dataEditor 
                    },
                    {
                        title : "ND",
                        field : "night_diff",
                        width : 60, 
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        template : "# if(night_diff==0){#  #} else{# #= night_diff #  #}#",
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        aggregates : ['sum'], 
                        footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                        editor : dataEditor

                    },
                    {
                        title : "OT In",
                        field : "ot_in",
                        width : 70,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        }    
                    },
                    {
                        title : "OT Out",
                        field : "ot_out",
                        width : 70,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        }    
                    },
                    {
                        title : "Reg OT",
                        field : "over_time",
                        width : 60,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        template : "# if(over_time==0){#  #} else{# #= over_time #  #}#",
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        aggregates : ['sum'], 
                        footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>",
                        editor : dataEditor
                    },
                    {
                        title : "ND OT",
                        field : "night_diff_ot",
                        width : 60, 
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        template : "# if(night_diff_ot==0){#  #} else{# #= night_diff_ot #  #}#",
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        aggregates : ['sum'], 
                        footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                        editor : dataEditor

                    },
                    {
                        title : '-',
                        width : 15,
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : "RD Hrs",
                        field : "restday_hrs",
                        width : 75, 
                        template : "# if(restday_hrs==0){#  #} else{# #= restday_hrs #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                    },
                    {
                        title : "RD OT",
                        field : "restday_ot",
                        width : 75, 
                        template : "# if(restday_ot==0){#  #} else{# #= restday_ot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                    },
                    {
                        title : "RD ND",
                        field : "restday_nd",
                        width : 75, 
                        template : "# if(restday_nd==0){#  #} else{# #= restday_nd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                    },
                    {
                        title : "RD ND OT",
                        field : "restday_ndot",
                        width : 75, 
                        template : "# if(restday_ndot==0){#  #} else{# #= restday_ndot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                    },
                    {
                        title : '-',
                        width : 15,
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    
                    {
                        title : 'Hol Type',
                        field : 'holiday_type',
                        width : 90,
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol Pay',
                        field : 'reghol_pay',
                        width : 90,
                        template : "# if(reghol_pay==0){#  #} else{# #= reghol_pay #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol Hrs',
                        field : 'reghol_hrs',
                        template : "# if(reghol_hrs==0){#  #} else{# #= reghol_hrs #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol OT',
                        field : 'reghol_ot',
                        template : "# if(reghol_ot==0){#  #} else{# #= reghol_ot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol RD',
                        field : 'reghol_rd',
                        template : "# if(reghol_rd==0){#  #} else{# #= reghol_rd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol RD ND',
                        field : 'reghol_rdnd',
                        template : "# if(reghol_rdnd==0){#  #} else{# #= reghol_rdnd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol RD OT',
                        field : 'reghol_rdot',
                        template : "# if(reghol_rdot==0){#  #} else{# #= reghol_rdot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol ND',
                        field : 'reghol_nd',
                        template : "# if(reghol_nd==0){#  #} else{# #= reghol_nd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol ND OT',
                        field : 'reghol_ndot',
                        template : "# if(reghol_ndot==0){#  #} else{# #= reghol_ndot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'Reg Hol RD ND OT',
                        field : 'reghol_rdndot',
                        template : "# if(reghol_rdndot==0){#  #} else{# #= reghol_rdndot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : '-', 
                        width : 15
                    },
                    {
                        title : 'SP Hol pay',
                        field : 'sphol_pay',
                        template : "# if(sphol_pay==0){#  #} else{# #= sphol_pay #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'SP Hol Hrs',
                        field : 'sphol_hrs',
                        template : "# if(sphol_hrs==0){#  #} else{# #= sphol_hrs #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'SP Hol OT',
                        field : 'sphol_ot',
                        template : "# if(sphol_ot==0){#  #} else{# #= sphol_ot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'SP Hol RD',
                        field : 'sphol_rd',
                        template : "# if(sphol_rd==0){#  #} else{# #= sphol_rd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'SP Hol RD ND',
                        field : 'sphol_rdnd',
                        template : "# if(sphol_rdnd==0){#  #} else{# #= sphol_rdnd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'SP Hol RD OT',
                        field : 'sphol_rdot',
                        template : "# if(sphol_rdot==0){#  #} else{# #= sphol_rdot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'SP Hol ND',
                        field : 'sphol_nd',
                        template : "# if(sphol_nd==0){#  #} else{# #= sphol_nd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    }, 
                    {
                        title : 'SP Hol ND OT',
                        field : 'sphol_ndot',
                        template : "# if(sphol_ndot==0){#  #} else{# #= sphol_ndot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    }, 
                    {
                        title : 'SP Hol RD ND OT',
                        field : 'sphol_rdndot',
                        template : "# if(sphol_rdndot==0){#  #} else{# #= sphol_rdndot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    }, 
                    {
                        title : '-', 
                        width : 15
                    }, 
                    {
                        title : 'DBL Hol Pay',
                        field : 'dblhol_pay',
                        template : "# if(dblhol_pay==0){#  #} else{# #= dblhol_pay #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'DBL Hol Hrs',
                        field : 'dblhol_hrs',
                        template : "# if(dblhol_hrs==0){#  #} else{# #= dblhol_hrs #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'DBL Hol OT',
                        field : 'dblhol_ot',
                        template : "# if(dblhol_ot==0){#  #} else{# #= dblhol_ot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'DBL Hol RD',
                        field : 'dblhol_rd',
                        template : "# if(dblhol_rd==0){#  #} else{# #= dblhol_rd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'DBL Hol RD ND',
                        field : 'dblhol_rdnd',
                        template : "# if(dblhol_rdnd==0){#  #} else{# #= dblhol_rdnd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'DBL Hol RDOT',
                        field : 'dblhol_rdot',
                        template : "# if(dblhol_rdot==0){#  #} else{# #= dblhol_rdot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    },
                    {
                        title : 'DBL Hol ND',
                        field : 'dblhol_nd',
                        template : "# if(dblhol_nd==0){#  #} else{# #= dblhol_nd #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    }, 
                    {
                        title : 'DBL Hol ND OT',
                        field : 'dblhol_ndot',
                        template : "# if(dblhol_ndot==0){#  #} else{# #= dblhol_ndot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    }, 
                    {
                        title : 'DBL Hol RD ND OT',
                        field : 'dblhol_rdndot',
                        template : "# if(dblhol_rdndot==0){#  #} else{# #= dblhol_rdndot #  #}#",
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }, 
                    }, 
                   
                  
                ],
                
            }); 

            function scheduleEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "schedule_desc",
                    dataValueField: "schedule_id",
                    dataSource: viewModel.ds.sched,
                    optionLabel : {
                        schedule_desc : "-",
                        schedule_id : 0,
                    },
                    change : function(e)
                    {
                        let grid = $("#dtrgrid").data("kendoGrid");
                        let selectedRow = grid.dataItem(grid.select());
                        //console.log(e.sender);
                        //console.log(e.sender.text());
                        //console.log(selectedRow.set("schedule_desc"));
                        selectedRow.set("schedule_desc",e.sender.text());
                        selectedRow.set("schedule_id",e.sender.value());
                        
                    }
                    
                });
            }

            function dataEditor(container, options){
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoTextBox({
               
                });
            }

            kendo.bind($("#viewModel"),viewModel);
           

        });
    </script>

@endsection
