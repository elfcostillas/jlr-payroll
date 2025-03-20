@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
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
                            create : {
                                url : 'payroll-period-weekly/create',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    swal_success(e);
                                    viewModel.ds.maingrid.read();
                                }
                            },
                            update : {
                                url : 'payroll-period-weekly/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    swal_success(e);
                                    viewModel.ds.maingrid.read();
                                }
                            },
                            parameterMap: function (data, type) {
                                if(type=='create' || type=='update'){
                                    data.date_from = kendo.toString(data.date_from,'yyyy-MM-dd');
                                    data.date_to = kendo.toString(data.date_to,'yyyy-MM-dd');
                                    data.date_release = kendo.toString(data.date_release,'yyyy-MM-dd');
                                }

                                return data;
                            }
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
                                    pyear: { type : 'string' },
                                    cut_off : { type : 'number' }
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
                        title : "Start Date",
                        field : "date_from",
                        template : "#= (data.date_from) ? kendo.toString(data.date_from,'MM/dd/yyyy') : ''  #",
                        //width : 120,    
                    },
                    {
                        title : "End Date",
                        field : "date_to",
                        template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        //width : 120,    
                    },
                    {
                        title : "Man Hours",
                        field : "man_hours",
                        //template : "#=  : ''  #",
                        width : 120,    
                    },
                    {
                        title : "Year",
                        field : "pyear",
                        //template : "#=  : ''  #",
                        width : 120,    
                    },
                    {
                        title : "Cut Off",
                        field : "cut_off",
                        //template : "#=  : ''  #",
                        width : 120,    
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