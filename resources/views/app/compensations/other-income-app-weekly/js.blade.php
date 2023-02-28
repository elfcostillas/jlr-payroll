@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'other-income-app-weekly/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            // create : {
                            //     url : 'payroll-period-weekly/create',
                            //     type : 'post',
                            //     dataType : 'json',
                            //     complete : function(e){
                            //         swal_success(e);
                            //         viewModel.ds.maingrid.read();
                            //     }
                            // },
                            // update : {
                            //     url : 'payroll-period-weekly/update',
                            //     type : 'post',
                            //     dataType : 'json',
                            //     complete : function(e){
                            //         swal_success(e);
                            //         viewModel.ds.maingrid.read();
                            //     }
                            // },
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
                                    id : { type: 'number', editable:false },
                                    date_from : { type : 'date' },
                                    date_to: { type : 'date' },
                                    date_release: { type : 'date' },
                                    man_hours: { type : 'number' },
                                }
                            }
                        }
                    }),
                },
                toolbarHandler : {

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
                toolbar : [{ name :'create',text:'Add Payroll Period'}],
                editable : "inline",
                columns : [
                    {
                        title : "ID",
                        field : "id",
                        width : 80,    
                    },
                    {
                        title : "Date Range",
                        field : "drange",
                        width : 80,  
                    },
                    {
                        command : ['edit'],
                        width : 190,    
                    },
                  
                ]
            });

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection