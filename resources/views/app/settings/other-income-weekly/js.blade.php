@section('jquery')
    <script>
        $(document).ready(function(){

            let fixedOption = [
                    { text: "Yes", value: "Y" },
                    { text: "No", value: "N" },
                ];

            var viewModel = kendo.observable({ 
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'other-income-weekly/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'other-income-weekly/create',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(e.status==500){
                                        swal_error(e);
                                    }else{
                                        swal_success(e);
                                        viewModel.ds.maingrid.read();
                                    }
                                   
                                }
                            },
                            update : {
                                url : 'other-income-weekly/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    if(e.status==500){
                                        swal_error(e);
                                    }else{
                                        swal_success(e);
                                        viewModel.ds.maingrid.read();
                                    }
                                }
                            },
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
                                    id : { type : 'string',editable : false },
                                    description : { type : 'string' },
                                    amount: { type : 'number' },

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
                filterable : {
                    extra : false,
                    operator : {
                        string : {
                            contains : "Contains"
                        }
                    }
                },
                sortable : true,
                height : 550,
                scrollable: true,
                toolbar : [{ name :'create',text:'Add Income'}],
                editable : "inline",
                columns : [
                   
                    {
                        title : "ID",
                        field : "id",
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 100,    
                    },
                    {
                        title : "Description",
                        field : "description",
                        // template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        // width : 120,    
                    },
                    {
                        title : "Amount",
                        field : "amount",
                        width : 160,    
                        attributes : {
                            style: "text-align:right"
                        },
                        template :"#= kendo.toString(amount,'n2') #"
                        
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