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
                    unposted : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'payroll-register/unposted-payroll',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                           
                           
                        },
                        pageSize :11,
                     
                        schema : {
                            model : {
                                id : 'id',
                                fields : {
                                    id : { type : 'number',editable : false },
                                    period_range : {type:'string'}
                                }
                            }
                        }
                    }),
                    posted :  new kendo.data.DataSource({ 
                        transport : {
                            read : {
                                url : 'payroll-register/posted-payroll',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                           
                        },
                        pageSize :12,
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
                    compute : function(){

                        let period = $("#unposted_period").data("kendoDropDownList");
                        //console.log(period.value());
                        let url = `payroll-register/compute/${period.value()}`;
                        window.open(url)
                    },

                    view : function(){
                        alert('view');
                    },

                    download : function(){
                        alert('download');
                    },

                    post : function(){

                        let period = $("#unposted_period").data("kendoDropDownList");

                        Swal.fire({
                                title: 'Finalize and Post Payroll',
                                text: "You won't be able to revert this!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Finalize'
                            }).then((result) => {
                                if (result.value) {                       
                                    $.post('payroll-register/post',{
                                        period_id : period.value()
                                    },function(data,status,){
                                        //console.log(status,data);
                                        //console.log(data.error)

                                        if(data.success){
                                            Swal.fire({
                                            //position: 'top-end',
                                            icon: 'success',
                                            title: data.success,
                                            showConfirmButton: false,
                                            timer: 1000
                                            });	

                                            viewModel.ds.unposted.read();
                                        }
                                        else {
                                            custom_error(data.error);
                                        }
                                    },'json');
                                }
                            });
                    },

                },
                functions : {
                    showPop : function(data)
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "1124", //1124 - 1152
                            height: "410",
                            //title: "Employee Information",
                            visible: false,
                            animation: false,
                            actions: [
                                "Pin",
                                "Minimize",
                                "Maximize",
                                "Close"
                            ],
                            close : viewModel.buttonHandler.closePop,
                            position : {
                                top : 0
                            }
                        }).data("kendoWindow").center().open().title('Manage DTR : '+data.empname + ' - ' +data.biometric_id  ) ;
                    }

                }
            });

            $("#unposted_period").kendoDropDownList({
                dataTextField: "period_range",
                dataValueField: "id",
                dataSource: viewModel.ds.unposted,
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
