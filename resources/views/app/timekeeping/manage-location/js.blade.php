@section('jquery')
<script type="text/x-kendo-template" id="logDrawer">
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.drawLogs }">
        </span>&nbsp; Draw Logs
    </button>
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.drawLogsM }">
    </span>&nbsp; Draw from Manual  DTR
    </button>
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.compute }">
        </span>&nbsp; Compute
    </button>
</script>	
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                selectedPeriod : null,
                selectedEmployee : null,
                ds : {
                    locations : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../settings/locations/get-locations',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            }
                        }
                    }),
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manage-location/list',
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
                    subgrid :  new kendo.data.DataSource({ //timekeeping/manage-location/get-employee-list/1
                        transport : {
                            read : {
                                url : 'manage-location/get-employee-list/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'manage-location/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    viewModel.ds.subgrid.read();
                                }
                            }
                           
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
                                    empname : { type: 'string', editable : false},
                                    loc_id : { type: "number" },
                                    location_name : { type: "string" },
                                    period_id : { type: "number" },
                                }
                            }
                        }
                    }),
                    dtrgrid :  new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manage-location/get-employee-dtr-logs/0/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'manage-location/update-dtr',
                                type : 'post',
                                dataType : 'json',
                                complete : function (e){

                                }
                            },
                            parameterMap : function(data,type)
                            {
                                console.log(type);
                                if(type=='update'){
                                    $.each(data.models,function(index,value){
                                        value.dtr_date =  kendo.toString(value.dtr_date,'yyyy-MM-dd')
                                    });
                                }
                                return data;
                            }
                           
                        },
                        batch: true,
                        //autoSync: true,
                        pageSize :14,
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
                                url : 'manage-location/get-employee-schedules',
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

                        $.post('manage-location/prepare',{
                            period_id : data.id
                        },function(){
                            let empListUrl = `manage-location/get-employee-list/${data.id}`;
                            viewModel.ds.subgrid.transport.options.read.url = empListUrl;
                            viewModel.ds.subgrid.read();
                        });
                    },
                    computeAll : function(e){
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);
                        
                        // $.post('manage-location/compute-all',{
                        //     period_id : data.id
                        // },function(){
                        //     Swal.fire({
                        //     //position: 'top-end',
                        //     icon: 'success',
                        //     title: 'DTR Logs has been processed.',
                        //     showConfirmButton: false,
                        //     timer: 1000
                        //     });	
                        // });

                    },
                    print : function(e){
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);

                        // console.log(data.id);
                        let url = `manage-location/${data.id}`;

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

                            let rawLogsUrl = `manage-location/get-employee-raw-logs/${viewModel.selectedPeriod.id}/${data.biometric_id}`;
                            // viewModel.ds.rawlogs.transport.options.read.url = rawLogsUrl;
                            // viewModel.ds.rawlogs.read();
                            $.get(rawLogsUrl,function(data){
                              
                                $("#raw-logs").html(data);
                            });


                            let dtrUrl = `manage-location/get-employee-dtr-logs/${viewModel.selectedPeriod.id}/${data.biometric_id}`;
                            viewModel.ds.dtrgrid.transport.options.read.url = dtrUrl;
                            viewModel.ds.dtrgrid.read();


                        }
                        
                    },
                    closePop : function(){

                    },
                    drawLogs : function()
                    {
                        
                        $.post('manage-location/draw-logs',{
                            period_id : viewModel.selectedPeriod.id,
                            biometric_id : viewModel.selectedEmployee
                        },function(){
                            viewModel.ds.dtrgrid.read();
                        });
                    },
                    drawLogsM : function()
                    {
                        
                        $.post('manage-location/draw-logs-manual',{
                            period_id : viewModel.selectedPeriod.id,
                            biometric_id : viewModel.selectedEmployee
                        },function(){
                            viewModel.ds.dtrgrid.read();
                        });
                    },
                    compute : function()
                    {
                        
                        $.post('manage-location/compute-logs',{
                            period_id : viewModel.selectedPeriod.id,
                            biometric_id : viewModel.selectedEmployee
                        },function(){
                            viewModel.ds.dtrgrid.read();
                        });
                    },
                },
                functions : {
                    showPop : function(data)
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "1124", //1124 - 1152
                            height: "460",
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
                filterable : true,
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
                        command: { text : 'Print',icon : 'print' ,click : viewModel.buttonHandler.print },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
                ],
                change : function(e){
                    var grid = $("#maingrid").data("kendoGrid");
                    var selectedItem = grid.dataItem(grid.select());

                    viewModel.set('selectedPeriod',selectedItem);

                    //console.log(selectedItem.id);
                    let empListUrl = `manage-location/get-employee-list/${selectedItem.id}`;
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
                editable : 'inline',
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
                        title : "Location",
                        field : "loc_id",
                        width : 180,    
                        template : "#=  data.location_name #",
                        editor : locationEditor
                    },
                    // {
                    //     command: { text : 'Edit',click : viewModel.buttonHandler.manage },
                    //     attributes : { style : 'font-size:10pt !important;'},
                    //     width : 85
                    // },
                    {
                        command : ['edit'],
                        width : 200
                    }
                  
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
                       
                         width : 120,
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
                        width : 80,
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
                        width : 80,
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
                        width : 70,
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
                        width : 80,
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
                    // {
                    //     title : "ND",
                    //     field : "night_diff",
                    //     width : 60, 
                    //     attributes: {
                    //         style: "font-size: 9pt;text-align:center",
                            
                    //     },
                    //     template : "# if(night_diff==0){#  #} else{# #= night_diff #  #}#",
                    //     headerAttributes: {
                    //         style: "font-size: 9pt;text-align:center",
                            
                    //     },
                    //     aggregates : ['sum'], 
                    //     footerTemplate: "<div style='text-align:center;font-size:9pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                    //     editor : dataEditor

                    // },
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
                    change : function(e)
                    {
                        let grid = $("#dtrgrid").data("kendoGrid");
                        let selectedRow = grid.dataItem(grid.select());
                        //console.log(e.sender);
                        //console.log(e.sender.text());
                        //console.log(selectedRow.set("schedule_desc"));
                        selectedRow.set("schedule_desc",e.sender.text());

                        
                    }
                    
                });
            }

            function dataEditor(container, options){
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoTextBox({
               
                });
            }

            function locationEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "location_name",
                    dataValueField: "id",
                    dataSource: viewModel.ds.locations,
                    change : function(e)
                    {
                        let grid = $("#subgrid").data("kendoGrid");
                        let selectedRow = grid.dataItem(grid.select());
                        //console.log(e.sender);
                        //console.log(e.sender.text());
                        //console.log(selectedRow.set("schedule_desc"));
                        // selectedRow.set("schedule_desc",e.sender.text());

                        
                    }
                    
                });
            }

            kendo.bind($("#viewModel"),viewModel);
           

        });
    </script>

@endsection
