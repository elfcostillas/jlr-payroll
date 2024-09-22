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