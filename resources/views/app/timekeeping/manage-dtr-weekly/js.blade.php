@section('jquery')
<script type="text/x-kendo-template" id="logDrawer">
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.drawLogs }">
        </span>&nbsp; Draw Logs
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
                                url : 'payroll-period-weekly/list',
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
                    subgrid :  new kendo.data.DataSource({ //timekeeping/manage-dtr-weekly/get-employee-list/1
                        transport : {
                            read : {
                                url : 'manage-dtr-weekly/get-employee-list/0',
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
                                url : 'manage-dtr-weekly/get-employee-dtr-logs/0/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'manage-dtr-weekly/update-dtr',
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
                                    late : { type:'number', },
                                    late_eq : { type:'number', },
                                    under_time : { type:'number', },
                                    over_time : { type:'number', },
                                    night_diff : { type:'number', },
                                    schedule_id : { type:'number', },
                                    schedule_desc : { type: 'string' }
                                }
                            }
                        }
                    }),
                    sched : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'manage-dtr-weekly/get-employee-schedules',
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

                        $.post('manage-dtr-weekly/prepare',{
                            period_id : data.id
                        },function(){

                        });
                    },
                    manage : function(e){
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);
                        if(viewModel.selectedPeriod!=null)
                        {
                            viewModel.functions.showPop(data);
                            viewModel.set('selectedEmployee',data.biometric_id);

                            let rawLogsUrl = `manage-dtr-weekly/get-employee-raw-logs/${viewModel.selectedPeriod.id}/${data.biometric_id}`;
                            // viewModel.ds.rawlogs.transport.options.read.url = rawLogsUrl;
                            // viewModel.ds.rawlogs.read();
                            $.get(rawLogsUrl,function(data){
                              
                                $("#raw-logs").html(data);
                            });


                            let dtrUrl = `manage-dtr-weekly/get-employee-dtr-logs/${viewModel.selectedPeriod.id}/${data.biometric_id}`;
                            viewModel.ds.dtrgrid.transport.options.read.url = dtrUrl;
                            viewModel.ds.dtrgrid.read();


                        }
                        
                    },
                    closePop : function(){

                    },
                    drawLogs : function()
                    {
                        
                        $.post('manage-dtr-weekly/draw-logs',{
                            period_id : viewModel.selectedPeriod.id,
                            biometric_id : viewModel.selectedEmployee
                        },function(){
                            viewModel.ds.dtrgrid.read();
                        });
                    }
                },
                functions : {
                    showPop : function(data)
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "1124", //1124 - 1152
                            height: "410",
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
                        title : "Man Hours",
                        field : "man_hours",
                        //template : "#=  : ''  #",
                        width : 110,    
                    },
                    {
                        command: { text : 'Prepare',click : viewModel.buttonHandler.prepare , },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                  
                ],
                change : function(e){
                    var grid = $("#maingrid").data("kendoGrid");
                    var selectedItem = grid.dataItem(grid.select());

                    viewModel.set('selectedPeriod',selectedItem);

                    //console.log(selectedItem.id);
                    let empListUrl = `manage-dtr-weekly/get-employee-list/${selectedItem.id}`;
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
                filterable : true,
                sortable : true,
                height : 550,
                scrollable: true,
                selectable : true,
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
                filterable : true,
                editable: true,
                //height : 550,
                scrollable: true,
                selectable : true,
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
                        width : 60,
                         attributes: {
                            style: "font-size: 9pt"
                        },
                        headerAttributes: {
                            style: "font-size: 9pt"
                        } 
                    },
                    {
                        title : "Date",
                        field : "dtr_date",
                        template : "#= (data.dtr_date) ? kendo.toString(data.dtr_date,'MM/dd/yyyy') : ''  #",
                        width : 90,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }    
                    },
                    {
                        title : "Schedule",
                        field : "schedule_id",
                        //template : "#= (schedule_desc) ? schedule_desc : data.schedule_desc #",
                        template : "#if(data.schedule_desc==null){# #} else {#  #=data.schedule_desc# #}#",
                        //template : "#= schedule_desc #",  #=data.schedule_id #
                        //template : "#= if(data.schedule_desc==null) #"
                       
                        width : 100,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        editor : scheduleEditor 
                    },
                    {
                        title : "Time In",
                        field : "time_in",
                        width : 90,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }    
                    },
                    {
                        title : "Time Out",
                        field : "time_out",
                        width : 90,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        }    
                    },
                    {
                        title : "Late",
                        field : "late",
                        width : 90,
                         attributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center"
                            
                        }    
                    },
                    {
                        title : "OT",
                        field : "over_time",
                        width : 90,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }    
                    },
                    {
                        title : "UT",
                        field : "over_time",
                        width : 90,
                         attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }    
                    },
                    {
                        title : "Night Diff",
                        field : "night_diff",
                        width : 90,    
                        attributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        },
                        headerAttributes: {
                            style: "font-size: 9pt;text-align:center",
                            
                        }
                    }
                  
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

            kendo.bind($("#viewModel"),viewModel);
           

        });
    </script>

@endsection