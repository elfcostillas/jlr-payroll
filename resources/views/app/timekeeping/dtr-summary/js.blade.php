@section('jquery')

<script id="template" type="text/x-kendo-template">
    <button class="k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:toolbarHandler.downloadBal" > <span class="k-icon k-i-plus k-button-icon"></span>Download Balance</button>
</script>

    <script>

        $(document).ready(function(){
            var viewModel = kendo.observable({ 
                ds : {
                    fy : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'leave-credits/year',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                           
                            model : {
                                id : 'fy',
                                fields : {
                                    fy : { type : 'number',editable :false },   
                                    
                                }
                            }
                        }
                    }),
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'dtr-summary/period-list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            // create : {
                            //     url : 'payroll-period/create',
                            //     type : 'post',
                            //     dataType : 'json',
                            //     complete : function(e,status){
                            //         if(status=='error'){
                            //             swal_error(e);
                            //         }else {
                            //             swal_success(e);
                            //             viewModel.ds.maingrid.read();
                            //         }
                            //     }
                            // },
                            // update : {
                            //     url : 'leave-credits/save',
                            //     type : 'post',
                            //     dataType : 'json',
                            //     complete : function(e,status){
                            //         if(status=='error'){
                            //             swal_error(e);
                            //         }else {
                            //             swal_success(e);
                            //             viewModel.ds.maingrid.read();
                            //         }
                            //     }
                            },
                            parameterMap: function (data, type) {
                                 if(type=='create' || type=='update'){
                                //     data.date_from = kendo.toString(data.date_from,'yyyy-MM-dd');
                                //     data.date_to = kendo.toString(data.date_to,'yyyy-MM-dd');
                                //     data.date_release = kendo.toString(data.date_release,'yyyy-MM-dd');
                                    // $.each(data.models,function(index,value){
                                    //         value.fy_year =  ($("#fy").data("kendoDropDownList").value()=='') ? 2022 : $("#fy").data("kendoDropDownList").value();
                                    // });
                                // }

                                return data;
                            }
                        },
                        pageSize :999,
                        batch : true,
                        //serverPaging : true,
                        //serverFiltering : true,
                        schema : {
                            //data : "data",
                            //total : "total",
                            model : {
                                id : 'id',
                                fields : {
                                    // biometric_id: { type : 'number',editable :false },   
                                    // fy_year: { type : 'number',editable :false },
                                    // vacation_leave: { type : 'number' },
                                    // sick_leave: { type : 'number' },
                                    // summer_vacation_leave: { type : 'number' },
                                    // paternity_leave: { type : 'number' },
                                    //fy : { type : 'number',editable :false },   
                                    // id : {ype : 'number',editable :false },
                                    // date_from : { type : 'date' },
                                    // date_to: { type : 'date' },
                                    // date_release: { type : 'date' },
                                    // man_hours: { type : 'number' },
                                    period_range : { type : 'string' },
                                }
                            }
                        }
                    }),
                },
                toolbarHandler : {  
                    downloadBal : function(e){
                        let year = ($("#fy").data("kendoDropDownList").value()=='') ? 2022 : $("#fy").data("kendoDropDownList").value();
                        
                        let url = `leave-credits/download-balance/${year}`;

                        window.open(url);
                    },
                    download : function(e){
                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);

                        // let year = ($("#fy").data("kendoDropDownList").value()=='') ? 2022 : $("#fy").data("kendoDropDownList").value();
                       

                        let url = `dtr-summary/download/${data.id}`;

                        window.open(url);

                        // alert();
                    },
                    prepareBlank : function(e){
                        alert();
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
                // toolbar : [{ name :'save',text:'Save Changes'},{ template: kendo.template($("#template").html())  }],
                editable : true,
                navigatable : true,
                filterable : {
                    extra: false,
                    operators: {
                        string: {
                            contains : "Contains"
                        }
                    }
                },
                columns : [
                    {
                        title : 'ID',
                        field : 'id',
                        width : 100,
                    },
                    {
                        title : 'Period Range',
                        field : 'period_range',
                        //width : 320,
                    },
                   
                    {
                        command: [
                            // { text : 'Prepare Blank',click : viewModel.toolbarHandler.prepareBlank , },
                            { text : 'Download',click : viewModel.toolbarHandler.download , icon : 'download' },
                            //{ text : 'Download',click : viewModel.buttonHandler.download , }
                        ],
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 110
                    },
                  
                ]
            });

            $("#fy").kendoDropDownList({
                dataSource: viewModel.ds.fy,
                dataTextField: "fy",
                dataValueField: "fy",
                index : -1,
                change : function(e){
                    //console.log(e.sender.value())
                }
            });

            $("#refrehButton").kendoButton({
                icon: "refresh",
                click : function(e){
                    let year = ($("#fy").data("kendoDropDownList").value()=='') ? 2022 : $("#fy").data("kendoDropDownList").value();
                    //alert(year);
                    let letUrl = `leave-credits/employees/${year}`;
                        viewModel.ds.maingrid.transport.options.read.url = letUrl;
                        viewModel.ds.maingrid.read();
                }
            });

            kendo.bind($("#viewModel"),viewModel);

        });

        function downloadBal(){
             
        }
    </script>

@endsection