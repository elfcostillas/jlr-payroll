@section('jquery')
    <script>
        $(document).ready(function(){

            let stopOption = [
                { text: "No", value: "N" },
                { text: "Yes", value: "Y" },
            ];

            var viewModel = kendo.observable({ 
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
                            create : {
                                url : 'payroll-period/create',
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
                                url : 'payroll-period/update',
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
                                    id : {ype : 'number',editable :false },
                                    date_from : { type : 'date' },
                                    date_to: { type : 'date' },
                                    date_release: { type : 'date' },
                                    man_hours: { type : 'number' },
                                    inProgress: { type : 'string' },
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
                        title : "In Progress",
                        field : "inProgress",
                        //template : "#=  : ''  #",
                        width : 120,    
                        editor : stopEditor
                    },
                    {
                        command : ['edit'],
                        width : 190,    
                    },
                  
                ]
            });

            function stopEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "text",
                    dataValueField: "value",
                    dataSource: stopOption,
                   
                });
            }

            //

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection