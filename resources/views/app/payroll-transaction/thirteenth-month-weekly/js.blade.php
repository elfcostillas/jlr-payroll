@section('jquery')
    <script>
        $(document).ready(function(){
            
            let years =<?php echo json_encode($years) ?>;
           
            var viewModel = kendo.observable({ 
                selectedYear : null,
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

                      
                    }
                    
                }
            });

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
                        type : "button",text : "Payslip", icon : 'print',click : viewModel.handler.print
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
        });
    </script>
@endsection