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
                                url : 'job-title/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'job-title/create',
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
                                url : 'job-title/update',
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
                                    dept_id : { type : 'number' },
                                    job_title_code : { type : 'string' },
                                    job_title_name : { type : 'string' },
                                    dept_code : { type : 'string' },
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
                filterable : {
                    extra: false,
                    operators: {
                        string: {
                            contains : "Contains"
                        },
                       
                    }
                },
                sortable : true,
                height : 550,
                scrollable: true,
                toolbar : [{ name :'create',text:'Add Job Title' }],
                editable : "inline",
                columns : [
                    {
                        title : "Dept Code",
                        field : "dept_id",
                        template : '#= div_code # - #= dept_code #',
                        editor : departmentEditor,
                        width : 220,    
                    },
                    // {
                    //     title : "Job Code",
                    //     field : "job_title_code",
                    //     width : 120,    
                    // },
                    {
                        title : "Name",
                        field : "job_title_name",
                    },
                    {
                        command : ['edit'],
                        width : 185,    
                    },
                ]
            });

            function departmentEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "dept_code",
                    dataValueField: "id",
                    dataSource: {
                        //type: "json",
                        transport: {
                            read: 'job-title/get-departments'
                        }
                    }
                });
            }

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection