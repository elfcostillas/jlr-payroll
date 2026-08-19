
@section('jquery')

<script>
    $(document).ready(function(){
        var viewModel = kendo.observable({ 
            ds : {
                maingrid : new kendo.data.DataSource({
                    transport : {
                        read : {
                            url : 'dtr-summary-manage/employee-list',
                            //data : { search : $("#employee_search").data('kendoTextBox').value() },
                            type : 'get',
                            dataType : 'json',
                            complete : function(e){
                                
                            }
                        },
                        update : {
                            url : 'dtr-summary-manage/update',
                            type : 'post',
                            dataType : 'json',
                            complete : function(e){
                                //console.log(e.status);

                                if(e.status==500)
                                {
                                    swal_error(e);
                                }
                                else{
                                    swal_success(e);
                                }
                                viewModel.ds.maingrid.read();
                            }
                        }
                        
                    },
                    batch: true,
                    pageSize :499,
                    serverPaging : true,
                    serverFiltering : true,
                    schema : {
                        data : "data",
                        total : "total",
                        model : {
                            id : 'id',
                            fields : {
                                biometric_id : { type : 'number' },
                                employee_name : { type : 'string' },
                                total_ndays: { type : 'number' },
                                ndays: { type : 'number' },
                                late_eq: { type : 'number' },
                                under_time: { type : 'number' },
                                over_time: { type : 'number' },
                                vl_wp: { type : 'number' },
                                vl_wop: { type : 'number' },
                                sl_wp: { type : 'number' },
                                sl_wop: { type : 'number' },
                                bl: { type : 'number' },
                                brv: { type : 'number' },
                                awol: { type : 'number' },
                                restday_hrs: { type : 'number' },
                                reghol_pay: { type : 'number' },
                                reghol_hrs: { type : 'number' },
                                sphol_pay: { type : 'number' },
                                sphol_hrs: { type : 'number' },
                                svl : { type : 'number' },
                                mpl : { type : 'number' }
                            }
                        }
                    }
                })
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
                    }
                }
            },
            sortable : true,
            height : 550,
            scrollable: true,
            selectable : true,
            navigatable : true,
            toolbar: [
                { name: "save", text: "Save" }
            ],
            editable : true,
            columns : [
                {
                    title : "Bio ID",
                    field : "biometric_id",
                    width : 80,    
                    locked : true,
                },
                {
                    title : "Employee Name",
                    field : "employee_name",
                    width : 220,    
                    locked : true,
                },
                {
                    title : "Total Days",
                    field : "total_ndays",
                    width : 110,
                    locked : true,
                },
                {
                    title : "Days Worked",
                    field : "ndays",
                    width : 115,
                },
                {
                    title : "Late *",
                    field : "late_eq", 
                    width : 80,
                    template : "#if(late_eq==0){#  #}else{# #= late_eq # #}# ",
                },
                {
                    title : "UT *",
                    field : "under_time",
                    width : 80,
                    template : "#if(under_time==0){#  #}else{# #= under_time # #}# ",
                },
                {
                    title : "OT *",
                    field : "over_time",
                    width : 80,
                    template : "#if(over_time==0){#  #}else{# #= over_time # #}# ",  
                },
                {
                    title : "VL /w Pay",
                    field : "vl_wp",
                    width : 100,
                    template : "#if(vl_wp==0){#  #}else{# #= vl_wp # #}# ",  
                },
                {
                    title : "VL w/o Pay",
                    field : "vl_wop",
                    width : 100,
                    template : "#if(vl_wop==0){#  #}else{# #= vl_wop # #}# ",
                },
                {
                    title : "SL /w Pay",
                    field : "sl_wp",
                    width : 100,
                    template : "#if(sl_wp==0){#  #}else{# #= sl_wp # #}# ",
                },
                {
                    title : "SL w/o Pay",
                    field : "sl_wop",
                    width : 100,
                    template : "#if(sl_wop==0){#  #}else{# #= sl_wop # #}# ",
                },
                {
                    title : "BL",
                    field : "bl",
                    width : 70,
                    template : "#if(bl==0){#  #}else{# #= bl # #}# ",
                },
                {
                    title : "BRV",
                    field : "brv",
                    width : 70,
                    template : "#if(brv==0){#  #}else{# #= brv # #}# ",
                },
                {
                    title : "SVL",
                    field : "svl",
                    width : 80,
                    template : "#if(svl==0){#  #}else{# #= svl # #}# ",
                },
                {
                    title : "Mat / Pat Leave",
                    field : "mpl",
                    width : 140,
                    template : "#if(mpl==0){#  #}else{# #= mpl # #}# ",
                },
                {
                    title : "AWOL",
                    field : "awol",
                    width : 80,
                    template : "#if(awol==0){#  #}else{# #= awol # #}# ",
                },
                {
                    title : "RD Hrs",
                    field : "restday_hrs",
                    width : 80,
                    template : "#if(restday_hrs==0){#  #}else{# #= restday_hrs # #}# ",
                },
                {
                    title : "Reg Hol *",
                    field : "reghol_pay",
                    width : 100,
                    template : "#if(reghol_pay==0){#  #}else{# #= reghol_pay # #}# ",
                },
                {
                    title : "Reg Hol Hrs *",
                    field : "reghol_hrs",
                    width : 120,
                    template : "#if(reghol_hrs==0){#  #}else{# #= reghol_hrs # #}# ",
                },
                {
                    title : "Sp Hol *",
                    field : "sphol_pay",
                    width : 90,
                    template : "#if(sphol_pay==0){#  #}else{# #= sphol_pay # #}# ",
                },
                {
                    title : "Sp Hol Hrs *",
                    field : "sphol_hrs",
                    width : 110,
                    template : "#if(sphol_hrs==0){#  #}else{# #= sphol_hrs # #}# ",
                }
                

                /*
                restday_hrs
                reghol_pay
                reghol_hrs
                sphol_pay
                sphol_hrs
                */


                
            ]
        });

        
        let emp_level = [
                { text: "All", value: "all" },
                { text: "Non-Confi", value: "non-confi" },
                { text: "Confi", value: "confi" },
        ];

        let pay_type = [
                { text: "All", value: "all" },
                { text: "Semi Monthly", value: "semi-monthly" },
                { text: "Daily", value: "daily" },
        ];

        $("#emp_level").kendoDropDownList({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: emp_level,
            index: 0,
            dataBound : function(e){
                
            }
            //change: onChange
        });

        $("#pay_type").kendoDropDownList({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: pay_type,
            index: 0,
            dataBound : function(e){
                
            }
            //change: onChange
        });

        $("#refrehButton").kendoButton({
                icon: "refresh",
                click : function(e){
                    // let year = ($("#fy").data("kendoDropDownList").value()=='') ? 2022 : $("#fy").data("kendoDropDownList").value();
                    // //alert(year);
                    let letUrl = `dtr-summary-manage/employee-list?emp_level=${$("#emp_level").data("kendoDropDownList").value()}&pay_type=${$("#pay_type").data("kendoDropDownList").value()}`;
                    viewModel.ds.maingrid.transport.options.read.url = letUrl;
                    viewModel.ds.maingrid.read();
                }
            });

        kendo.bind($("#viewModel"),viewModel);
    });
</script>

@endsection