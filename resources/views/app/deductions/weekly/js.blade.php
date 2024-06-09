@section('jquery')
    <script id="printTemplate" type="text/x-kendo-template">
        <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.print_file }">
            <span class="k-icon k-i-print k-button-icon"></span> Print
        </button>
    </script>
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                selectedPeriod : null,
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'weekly/list',
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
                    compgrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'weekly/emp-list/0',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            update : {
                                url : 'weekly/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
                                    swal_success(e);
                                    viewModel.ds.compgrid.read();
                                }
                            },
                            
                        },
                        batch : true,
                        pageSize :999,
                        // serverPaging : true,
                        // serverFiltering : true,
                        schema : {
                            // data : "data",
                            // total : "total",
                            model : {
                                id : 'line_id',
                                fields : {
                                    line_id : { type: 'number', editable:false },
                                    period_id : { type: 'number', editable:false },
                                    employee_name : { type: 'string', editable:false },
                                    earnings : { type: 'number', },
                                    deductions : { type: 'number', },
                                    retro_pay : { type: 'number', },
                                    canteen : { type: 'number', editable:false },
                                    remarks2 : { type: 'string', },

                                    canteen_bpn : { type: 'number', },
                                    canteen_bps : { type: 'number', },
                                    canteen_agg : { type: 'number', },
                                }
                            }
                        },
                        aggregate : [
                            { field : "canteen" , aggregate: "sum" },
                            { field : "canteen_bpn" , aggregate: "sum" },
                            { field : "canteen_bps" , aggregate: "sum" },
                            { field : "canteen_agg" , aggregate: "sum" },
                        ]
                    }),
                },
                buttonHandler : {
                    viewDeductions : function(e){
                        
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);

                        let url = `weekly/emp-list/${data.id}`;
                        viewModel.ds.compgrid.transport.options.read.url = url;
                        viewModel.ds.compgrid.read();

                        viewModel.selectedPeriod = data.id;

                        $("#period_id").val(data.drange)
                        
                        var myWindow = $("#pop");
                       
                        myWindow.kendoWindow({
                            width: "1224", //1124 - 1152
                            height: "700",
                            title: "Weekly Empolyees - Deductions",
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
                        }).data("kendoWindow").center().open();
                    },
                    print_file : function(e){
                        // console.log(viewModel.selectedPeriod);
                        let url = `weekly/print/${viewModel.selectedPeriod}`;

                        window.open(url);
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
                //toolbar : [{ name :'create',text:'Add Payroll Period'}],
               
                editable : "inline",
                columns : [
                    {
                        title : "ID",
                        field : "id",
                        width : 100,    
                    },
                    {
                        title : "Date Range",
                        field : "drange",
                        
                    },
                    // {
                    //     command : ['edit'],
                    //     width : 190,    
                    // },
                    {
                        command: [
                            { text : 'View', click : viewModel.buttonHandler.viewDeductions , },
                           
                        ],
                        //attributes : { style : 'font-size:10pt !important;'},
                        width : 90
                    },
                  
                ]
            });

            $("#compgrid").kendoGrid({
                dataSource : viewModel.ds.compgrid,
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
                        }
                    }
                },
                navigatable : true,
                sortable : true,
                height : 550,
                scrollable: true,
                // toolbar : ['save', kendo.template($("#printTemplate").html()) ], //
                // toolbar : kendo.template($("#printTemplate").html()),
                toolbar : [
                    {
                        name : 'save'
                    },
                    {
                       template : kendo.template($("#printTemplate").html())
                    }
                ],
                editable : true,
                columns : [
                    {
                        title : "ID",
                        field : "biometric_id",
                        width : 100,    
                    },
                    {
                        title : "Name",
                        field : "employee_name",
                        
                    },
                    // {
                    //     title : "Earnings",
                    //     field : "earnings",
                    //     width : 130,
                    //     attributes : {
                    //         style : "text-align:right"
                    //     },
                    //     template : "# if(earnings==0){#  #} else{# #= kendo.toString(earnings,'n2') #  #}#",
                    // },
                    // {
                    //     title : "Retro Pay",
                    //     field : "retro_pay",
                    //     width : 130,
                    //     attributes : {
                    //         style : "text-align:right"
                    //     },
                    //     template : "# if(retro_pay==0){#  #} else{# #= kendo.toString(retro_pay,'n2') #  #}#",
                    // },
                    {
                        title : "Deduction",
                        field : "deductions",
                        width : 130,
                        attributes : {
                            style : "text-align:right"
                        },
                        template : "# if(deductions==0){#  #} else{# #= kendo.toString(deductions,'n2') #  #}#",
                    },
                    
         
                    
                    {
                        title : "Canteen BPN",
                        field : "canteen_bpn",
                        width : 130,
                        attributes : {
                            style : "text-align:right"
                        },
                        template : "# if(canteen_bpn==0){#  #} else{# #= kendo.toString(canteen_bpn,'n2') #  #}#",
                        footerTemplate: "<div style='text-align:right;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                    },
                    {
                        title : "Canteen BPS",
                        field : "canteen_bps",
                        width : 130,
                        attributes : {
                            style : "text-align:right"
                        },
                        template : "# if(canteen_bps==0){#  #} else{# #= kendo.toString(canteen_bps,'n2') #  #}#",
                        footerTemplate: "<div style='text-align:right;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                    },
                    {
                        title : "Canteen AGG",
                        field : "canteen_agg",
                        width : 130,
                        attributes : {
                            style : "text-align:right"
                        },
                        template : "# if(canteen_agg==0){#  #} else{# #= kendo.toString(canteen_agg,'n2') #  #}#",
                        footerTemplate: "<div style='text-align:right;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                    },
                               {
                        title : "Canteen",
                        field : "canteen",
                        width : 130,
                        attributes : {
                            style : "text-align:right"
                        },
                        template : "# if(canteen==0){#  #} else{# #= kendo.toString(canteen,'n2') #  #}#",
                        footerTemplate: "<div style='text-align:right;font-size:10pt !important;font-weight : normal !important;'>#=kendo.toString(sum,'n2')#</div>" ,
                    },
                    {
                        title : "Remarks",
                        field : "remarks2",
                        width : 220,
                        attributes : {
                            // style : "text-align:right"
                        },
                        // template : "# if(deductions==0){#  #} else{# #= kendo.toString(deductions,'n2') #  #}#",
                    }
                ]
            });

            $("#period_id").kendoTextBox();

            function print()
            {
                console.log(123123);
            }

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection