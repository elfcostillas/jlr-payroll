@section('jquery')
<script>
    $(document).ready(function(){

        let years =<?php echo json_encode($years) ?>;

        let months =<?php echo json_encode($months) ?>;

        var viewModel = kendo.observable({ 
                selectedYear : null,
                ds : {
                    location : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../settings/locations/get-locations',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                            model : {
                                id : 'id',
                                fields : {
                                    location_name : { type : 'string' },
                                  
                                }
                            }
                        }
                    }),
                },
                handler : {
                    show : function(data){
                        let selectedYear = $("#pyear").data("kendoDropDownList");
                        let selectedMonth = $("#pmonth").data("kendoDropDownList");
                        // console.log(selected.value());

                        let url = `thirteenth-month-confi/show-table/${selectedYear.value()}/${selectedMonth.value()}`;

                        // console.log(url);
                        // window.open(url);
                        $.get(url,function(data){
                            // console.log(data);
                            $("#resultTable").html(data);
                        });
                    },
                    setSelectedYear : function()
                    {
                        let year = $("#pyear").data("kendoDropDownList").value();

                        console.log(year);
                    },
                    download : function()
                    {
                        let selectedYear = $("#pyear").data("kendoDropDownList");
                        let selectedMonth = $("#pmonth").data("kendoDropDownList");

                        let url = `thirteenth-month-confi/download-excel/${selectedYear.value()}/${selectedMonth.value()}`;
                        window.open(url);
                    },

                    banktransmittal : function()
                    {
                        let selectedYear = $("#pyear").data("kendoDropDownList");
                        let selectedMonth = $("#pmonth").data("kendoDropDownList");

                        let url = `thirteenth-month-confi/download-banktransmittal/${selectedYear.value()}/${selectedMonth.value()}`;
                        window.open(url);
                    },
                    post : function()
                    {
                        let selectedYear = $("#pyear").data("kendoDropDownList");
                        let selectedMonth = $("#pmonth").data("kendoDropDownList");
                
                        Swal.fire({
                                title: 'Finalize and Post 13th Month (Confi)',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Finalize'
                            }).then((result) => {
                                if (result.value) {                       
                                    $.post('thirteenth-month-confi/post',{
                                        cyear : selectedYear.value(),
                                        cmonth : selectedMonth.value()
                                    },function(data,staus){
                                        if(data.success){
                                            Swal.fire({
                                            //position: 'top-end',
                                            icon: 'success',
                                            title: data.success,
                                            showConfirmButton: false,
                                            timer: 2000
                                            });	

                                           
                                        }
                                    })
                                    .fail(function(data){
                                    swal_error(data);
                                    }).always(function() {
                                        //viewModel.maingrid.ds.read();
                                    });
                                }
                            });

                      
                    },
                    showPop : function(data)
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "464px", //1124 - 1152
                            height: "280",
                            title: "13th Month Payslip",
                            visible: false,
                            animation: false,
                            actions: [
                                "Pin",
                                "Minimize",
                                "Maximize",
                                "Close"
                            ],
                            close : viewModel.handler.closePop,
                            position : {
                                top : 0
                            }
                        }).data("kendoWindow").center().open().title() ;
                    },
                    closePop : function(){},
                    print : function(){
                        
                        let selectedYear = $("#pyear").data("kendoDropDownList");
                        let selectedMonth = $("#pmonth").data("kendoDropDownList");
                        
                        let url = `thirteenth-month-confi/print-payslip/${selectedYear.value()}/${selectedMonth.value()}`;
                        window.open(url);
                    },
                    conso : function() {
                        let selectedYear = $("#pyear").data("kendoDropDownList");
                        let selectedMonth = $("#pmonth").data("kendoDropDownList");
                        
                        let url = `thirteenth-month-confi/download-conso/${selectedYear.value()}/${selectedMonth.value()}`;
                        window.open(url);
                    },
                    downloadInActive : function() {
                        let selectedYear = $("#pyear").data("kendoDropDownList");
                        let selectedMonth = $("#pmonth").data("kendoDropDownList");

                        let url = `thirteenth-month-confi/download-excel-inactive/${selectedYear.value()}/${selectedMonth.value()}`;
                        window.open(url);
                    }
                }
            });

        kendo.bind($("#viewModel"),viewModel);

        $("#toolbar").kendoToolBar({
            items : [
                // { type: "button", text: "Button" },
                // { id : 'saveBtn', type: "button", text: "Save", icon: 'save', },
                {
                    type : "dropdown",
                    template : "<input id='pyear'>",
                    overflow: "never"
                },
                {
                    type : "dropdown",
                    template : "<input id='pmonth'>",
                    overflow: "never",
    

                },

                {
                    type : "button",text : "Show", icon : 'table',click : viewModel.handler.show
                },
                {
                    type : "button",text : "Download Excel", icon : 'download',click : viewModel.handler.download
                },
                {
                    type : "button",text : "Post", icon : 'save',click : viewModel.handler.post
                },
                {
                    type : "button",text : "Bank Transmittal", icon : 'print',click : viewModel.handler.banktransmittal
                },
                {
                    type : "button",text : "Payslip", icon : 'print',click : viewModel.handler.print
                },
                {
                    type : "button",text : "Consolidated", icon : 'table',click : viewModel.handler.conso
                },
                {
                        type : "button",text : "Download Excel - Inactive Employees", icon : 'download',click : viewModel.handler.downloadInActive
                }
                // {
                //     type : "button",text : "Consolidated Bank Transmittal", icon : 'print',click : viewModel.handler.banktransmittalconso
                // },
                
            ]
        });

        $("#pyear").kendoDropDownList({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: years,
            index: 0,
            // change: viewModel.handler.setSelectedYear()
            change: function(e){
                //e.sender.dataItem()
                // console.log(e.sender.dataItem().value);
                let dataItem = e.sender.dataItem();


            }
        });

        $("#pmonth").kendoDropDownList({
            dataTextField: "label",
            dataValueField: "value",
            dataSource: months,
            index: 0,
            
            // change: viewModel.handler.setSelectedYear()
            change: function(e){
                //e.sender.dataItem()
                // console.log(e.sender.dataItem().value);
                let dataItem = e.sender.dataItem();


            }
        });

        $("#popyear").kendoDropDownList({
            dataTextField: "text",
            dataValueField: "value",
            dataSource: years,
            index: 0,
            // change: viewModel.handler.setSelectedYear()
            change: function(e){
                //e.sender.dataItem()
                // console.log(e.sender.dataItem().value);
                //let dataItem = e.sender.dataItem();
            }
        });
        
    });
</script>
@endsection