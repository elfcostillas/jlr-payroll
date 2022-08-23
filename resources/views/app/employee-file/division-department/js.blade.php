@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                holiday_id : null,
                location : [],
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'divisions-departments/division/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'divisions-departments/division/create',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e,status){
                                    if(status=='error'){
                                        swal_error(e);
                                    }else {
                                        swal_success(e);
                                        viewModel.ds.maingrid.read();
                                    }
                                   
                                }
                            },
                            update : {
                                url : 'divisions-departments/division/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(status=='error'){
                                        swal_error(e);
                                    }else {
                                        swal_success(e);
                                        viewModel.ds.maingrid.read();
                                    }
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
                                    div_code : { type : 'string' },
                                    div_name: { type : 'string' },
                                }
                            }
                        }
                    }),
                    subgrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'divisions-departments/department/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'divisions-departments/department/create',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e,status){
                                    if(status=='error'){
                                        swal_error(e);
                                    }else {
                                        swal_success(e);
                                        viewModel.ds.subgrid.read();
                                    }
                                   
                                }
                            },
                            update : {
                                url : 'divisions-departments/department/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(status=='error'){
                                        swal_error(e);
                                    }else {
                                        swal_success(e);
                                        viewModel.ds.subgrid.read();
                                    }
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
                                    dept_code : { type : 'string' },
                                    dept_name: { type : 'string' },
                                    dept_div_id: { type : 'number' },
                                    div_code : { type : 'string' },
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {  
                   
                    closePop : function(e){

                    }
                },
                functions : {
                    showPOP : function(data){
                       
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "360", //1124 - 1152
                            height: "320",
                            title: "",
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
                        }).data("kendoWindow").center().open().title(data.holiday_remarks);
                        // myWindow.title(data.holiday_remarks);
                    },
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
                toolbar : [{ name :'create',text:'Add Division' }],
                editable : "inline",
                columns : [
                    {
                        title : "Code",
                        field : "div_code",
                        width : 100,    
                    },
                    {
                        title : "Name",
                        //field : "type_description",
                        field : "div_name",
                         //  editor : holidayTypeEditor,
                        //width : 160,    
                    },
        
                    {
                        command : ['edit'],
                        width : 185,    
                    },
                  
                ]
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
                toolbar : [{ name :'create',text:'Add Department' }],
                editable : "inline",
                columns : [
                    {
                        title : "Division",
                        field : "dept_div_id",
                        template : '#= div_code #',
                        width : 100,    
                        editor : divisionEditor,
                    },
                    {
                        title : "Dept. Code",
                        //field : "type_description",
                        field : "dept_code",
                         //  editor : holidayTypeEditor,
                        //width : 160,    
                    },
                    {
                        title : "Dept. Name",
                        //field : "type_description",
                        field : "dept_name",
                         //  editor : holidayTypeEditor,
                        //width : 160,    
                    },
                    {
                        command : ['edit'],
                        width : 185,    
                    },
                  
                ]
            });

          

            function divisionEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "div_code",
                    dataValueField: "id",
                    dataSource: {
                        //type: "json",
                        transport: {
                            read: 'divisions-departments/division/get-divisions'
                        }
                    }
                });
            }

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection