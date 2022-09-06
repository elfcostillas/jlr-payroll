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
                                url : 'deduction-type/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'deduction-type/create',
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
                                url : 'deduction-type/update',
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
                                    is_fixed: { type : 'string' },
                                    // date_release: { type : 'date' },
                                    // man_hours: { type : 'number' },
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
                toolbar : [{ name :'create',text:'Add Deduction'}],
                editable : "inline",
                columns : [
                   
                    {
                        title : "ID",
                        field : "id",
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 90,    
                    },
                    {
                        title : "Description",
                        field : "description",
                        // template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        // width : 120,    
                    },
                    {
                        title : "Fixed",
                        field : "is_fixed",
                        width : 90,    
                        attributes : {
                            style: "text-align:center"
                        },
                        editor : fixedOptionEditor
                        
                    },
                    {
                        command : ['edit'],
                        width : 190,    
                    },
                  
                ]
            });

            function fixedOptionEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: fixedOption,
                   
                });
            }

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection