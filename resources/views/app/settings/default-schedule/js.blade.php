@section('jquery')
    <script>
        $(document).ready(function(){
            
            var viewModel = kendo.observable({ 
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'default-schedules/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'default-schedules/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    swal_success(e);
                                    viewModel.ds.maingrid.read();
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
                                id : 'dept_id',
                                fields : {
                                   //dept_id : { type: "number",editable : false},
                                   dept_code: { type: "string",editable : false},
                                   schedule_id: { type: "number"},
                                   sched_desc: { type: "string"} ,
                                   //work_schedules_default.line_id
                                }
                            }
                        }
                    }),
                    sched : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../timekeeping/manage-dtr-weekly/get-employee-schedules',
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
                form : {
                    model : {
                        biometric_id : null
                    }
                },
                buttonHandler : {
                    save : function() {
                        $.post('biometric/save',{
                            biometric_id : $('#biometric_id').val()
                        },function(data){
                            swal_success(data);
                        });
                    }
                }
                ,callBack : function(){

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
                //toolbar : [{ name :'create',text:'Add Location'}],
                editable : "inline",
                columns : [
                   
                    {
                        title : "Department",
                        field : "dept_code",
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 170,    
                    },
                    {
                        title : "Schedule",
                        field : "schedule_id",
                        //template : "#= data.sched_desc #"
                         template : "#= (data.sched_desc) ? data.sched_desc : ''  #",
                        // width : 120,  
                        editor : scheduleEditor  
                    },
                    {
                        command : ['edit'],
                        width : 190,    
                    },
                  
                ]
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
                        // let grid = $("#maingrid").data("kendoGrid");
                        // let selectedRow = grid.dataItem(grid.select());
                     
                        // selectedRow.set("sched_desc",e.sender.text());

                        
                    }
                    
                });
            }

            
            kendo.bind($("#viewModel"),viewModel);
            
        });
    </script>

@endsection