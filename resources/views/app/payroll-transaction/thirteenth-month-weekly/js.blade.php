@section('jquery')
    <script>
        $(document).ready(function(){
            
            let years =<?php echo json_encode($years) ?>;
           
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
                        let selected = $("#pyear").data("kendoDropDownList");
                        // console.log(selected.value());

                        let url = `thirteenth-month-weekly/show-table/${selected.value()}`;

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
                        let selected = $("#pyear").data("kendoDropDownList");
                        let url = `thirteenth-month-weekly/download-excel/${selected.value()}`;
                        window.open(url);
                    },

                    banktransmittal : function()
                    {
                        let selected = $("#pyear").data("kendoDropDownList");

                        let url = `thirteenth-month-weekly/download-banktransmittal/${selected.value()}`;
                        window.open(url);
                    },
                    post : function()
                    {
                        let selected = $("#pyear").data("kendoDropDownList");
                
                        Swal.fire({
                                title: 'Finalize and Post 13th Month (Support Group)',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Finalize'
                            }).then((result) => {
                                if (result.value) {                       
                                    $.post('thirteenth-month-weekly/post',{
                                        cyear : selected.value()
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

                        let year = $("#popyear").data("kendoDropDownList");
                        let location = $("#ddLocation").data("kendoDropDownList");
                        
                        let url = `thirteenth-month-weekly/print/${year.value()}/${location.value()}`;
                        window.open(url);
                    },
                    conso : function ()
                    {
                        let selected = $("#pyear").data("kendoDropDownList");

                        let url = `thirteenth-month-weekly/download-conso/${selected.value()}`;
                        window.open(url);
                    },
                    banktransmittalconso : function()
                    {
                        let selected = $("#pyear").data("kendoDropDownList");

                        let url = `thirteenth-month-weekly/download-banktransmittal-conso/${selected.value()}`;
                        window.open(url);
                    },
                    downloadInActive : function()
                    {
                        let selected = $("#pyear").data("kendoDropDownList");
                        let url = `thirteenth-month-weekly/download-excel-inactive/${selected.value()}`;
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
                        type : "button",text : "Payslip", icon : 'print',click : viewModel.handler.showPop
                    },
                    {
                        type : "button",text : "Consolidated", icon : 'table',click : viewModel.handler.conso
                    },
                    {
                        type : "button",text : "Consolidated Bank Transmittal", icon : 'print',click : viewModel.handler.banktransmittalconso
                    },
                    {
                        type : "button",text : "Download Excel - Inactive Employee", icon : 'download',click : viewModel.handler.downloadInActive
                    }
                  
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

            $("#ddLocation").kendoDropDownList({
                dataTextField: "location_name",
                dataValueField: "id",
                dataSource: viewModel.ds.location,
                index: 1,
                optionLabel: {
                    id: 0,
                    location_name: "ALL"
                }
                //change: onChange
            });

           
        });
    </script>
@endsection