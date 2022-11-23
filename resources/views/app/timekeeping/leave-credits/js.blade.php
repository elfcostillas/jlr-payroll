@section('jquery')
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
                                url : 'leave-credits/employees/2022',
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
                            update : {
                                url : 'leave-credits/save',
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
                                //     data.date_from = kendo.toString(data.date_from,'yyyy-MM-dd');
                                //     data.date_to = kendo.toString(data.date_to,'yyyy-MM-dd');
                                //     data.date_release = kendo.toString(data.date_release,'yyyy-MM-dd');
                                    $.each(data.models,function(index,value){
                                            value.fy_year =  ($("#fy").data("kendoDropDownList").value()=='') ? 2022 : $("#fy").data("kendoDropDownList").value();
                                    });
                                }

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
                                id : 'line_id',
                                fields : {
                                    biometric_id: { type : 'number',editable :false },   
                                    fy_year: { type : 'number',editable :false },
                                    vacation_leave: { type : 'number' },
                                    sick_leave: { type : 'number' },
                                    //fy : { type : 'number',editable :false },   
                                    // id : {ype : 'number',editable :false },
                                    // date_from : { type : 'date' },
                                    // date_to: { type : 'date' },
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
                //toolbar : [{ name :'create',text:'Add Payroll Period'}],
                toolbar : [{ name :'save',text:'Save Changes'}],
                editable : true,
                navigatable : true,
                columns : [
                    {
                        title : 'Bio ID',
                        field : 'biometric_id',
                        width : 100,
                    },
                    {
                        title : 'Empployee Name',
                        field : 'employee_name',
                        width : 320,
                    },
                    {
                        title : 'Vacation Leave',
                        field : 'vacation_leave',
                        width : 140,
                    },
                    {
                        title : 'Sick Leave',
                        field : 'sick_leave',
                        width : 140,
                    },
                    {

                    }
                  
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
    </script>

@endsection