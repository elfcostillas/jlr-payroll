@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'sss-table/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'sss-table/save',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    swal_success(e);
                                    viewModel.ds.maingrid.read();
                                }
                            },
                            update : {
                                url : 'sss-table/save',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    swal_success(e);
                                    viewModel.ds.maingrid.read();
                                }
                            },
                           
                        },
                        pageSize :15,
                        serverPaging : true,
                        serverFiltering : true,
                        batch :true,
                        schema : {
                            data : "data",
                            total : "total",
                            model : {
                                id : 'line_id',
                                fields : {
                                    range1 : { type : 'number' },
                                    range2: { type : 'number' },
                                    salary_credit: { type : 'number' },
                                    ec: { type : 'number' },
                                    er_share: { type : 'number' },
                                    ee_share: { type : 'number' },
                                    total_share: { type : 'number' },
                                    mpf: { type : 'number' },
                                    total_msalarycredit: { type : 'number' },
                                    mpf_er: { type : 'number' },
                                    mpf_ee: { type : 'number' },
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
                height : 590,
                scrollable: true,
                toolbar : [{ name :'create',text:'Add'},{name : 'save', text : 'Save' }],
                editable : true,
                navigatable :true,
                columns : [
                   
                    {
                        title : "Range1",
                        field : "range1",
                        template : "#=kendo.toString(range1,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                           
                        },
                    },
                    {
                        title : "Range2",
                        field : "range2",
                        template : "#=kendo.toString(range2,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    {
                        title : "SC",
                        field : "salary_credit",
                        template : "#=kendo.toString(salary_credit,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    {
                        title : "WISP",
                        field : "mpf",
                        template : "#=kendo.toString(mpf,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    {
                        title : "ER",
                        field : "er_share",
                        template : "#=kendo.toString(er_share,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    {
                        title : "EE",
                        field : "ee_share",
                        template : "#=kendo.toString(ee_share,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    {
                        title : "EC",
                        field : "ec",
                        template : "#=kendo.toString(ec,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    {
                        title : "WISP ER",
                        field : "mpf_er",
                        template : "#=kendo.toString(mpf_er,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    {
                        title : "WISP EE",
                        field : "mpf_ee",
                        template : "#=kendo.toString(mpf_ee,'n2')#",
                        attributes : {
                            style : 'font-size:9pt;text-align:right',
                        },
                    },
                    
                  
                ]
            });

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection