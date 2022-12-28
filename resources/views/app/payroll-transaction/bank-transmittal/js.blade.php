@section('jquery')
<script type="text/x-kendo-template" id="logDrawer">
    <button class="k-grid-save-changes k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="events: { click: buttonHandler.drawLogs }">
        </span>&nbsp; Draw Logs
    </button>
</script>	
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                selectedPeriod : null,
                selectedEmployee : null,
                ds : {
                    posted :  new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'bank-transmittal/get-periods',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                           
                        },
                        //pageSize :12,
                        schema : {
                            model : {
                                id : 'period_id',
                                fields : {
                                    period_id : { type : 'number' },
                                    period_range : {type:'string'}
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {
                    download : function(){
                        let period = $("#posted_period").data("kendoDropDownList");
                        let url = `bank-transmittal/download/${period.value()}`;
                        window.open(url)
                    },
                },
                // functions : {
                //     showPop : function(data)
                //     {
                //         var myWindow = $("#pop");
                        
                //         myWindow.kendoWindow({
                //             width: "1124", //1124 - 1152
                //             height: "410",
                //             //title: "Employee Information",
                //             visible: false,
                //             animation: false,
                //             actions: [
                //                 "Pin",
                //                 "Minimize",
                //                 "Maximize",
                //                 "Close"
                //             ],
                //             close : viewModel.buttonHandler.closePop,
                //             position : {
                //                 top : 0
                //             }
                //         }).data("kendoWindow").center().open().title('Manage DTR : '+data.empname + ' - ' +data.biometric_id  ) ;
                //     }

                // }
            });

            $("#posted_period").kendoDropDownList({
                dataTextField: "period_range",
                dataValueField: "id",
                dataSource: viewModel.ds.posted,
                //index: 0,
                autoWidth : true,
                dataBound : function(e){
                  
                }
                //change: onChange
            });

            kendo.bind($("#viewModel"),viewModel);
           

        });
    </script>

@endsection
