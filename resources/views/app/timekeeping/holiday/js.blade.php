@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                holiday_id : null,
                location : [],
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'holiday/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'holiday/create',
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
                            update : {
                                url : 'holiday/update',
                                type : 'post',
                                dataType : 'json',
                                complete : function(e){
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
                                    data.holiday_date = kendo.toString(data.holiday_date,'yyyy-MM-dd');
                                    // data.date_to = kendo.toString(data.date_to,'yyyy-MM-dd');
                                    // data.date_release = kendo.toString(data.date_release,'yyyy-MM-dd');
                                }

                                return data;
                            }
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
                                    holiday_date : { type : 'date' },
                                    holiday_remarks : { type : 'string' },
                                    holiday_type : { type : 'number' },
                                    type_description: { type : 'string' },
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {  
                    setLocations : function(e){

                        e.preventDefault(); 
                       
                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);
                        
                        viewModel.functions.showPOP(data);
                        viewModel.set('holiday_id',data.id);

                        $.ajax({
                            url:'holiday/read-locations',
                            type:"POST",
                            data: {holiday_id : data.id },
                            dataType:"json",
                            success: function(data){
                            
                                let location = data['locations'];  
                            
                                let h = [];
                                
                                location.forEach(function(item,index){
                                    h.push(item.location_id.toString());
                                });

                                viewModel.set('location',h);

                                //console.log(viewModel.rights);
                            }	
                        });
                    },
                    closePop : function(e){

                    }
                },
                functions : {
                    showPOP : function(data){
                       
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "360", //1124 - 1152
                            height: "320",
                            title: "",
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
                        }).data("kendoWindow").center().open().title(data.holiday_remarks);
                        // myWindow.title(data.holiday_remarks);
                    },
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
                toolbar : [{ name :'create',text:'Add Holiday' }],
                editable : "inline",
                columns : [
                    {
                        title : "Date",
                        field : "holiday_date",
                        template : "#= (data.holiday_date) ? kendo.toString(data.holiday_date,'MM/dd/yyyy') : ''  #",
                        width : 120,    
                    },
                    {
                        title : "Type",
                        //field : "type_description",
                        field : "holiday_type",
                        template : "#: type_description #",
                        editor : holidayTypeEditor,
                        width : 160,    
                    },
                    {
                        title : "Description",
                        field : "holiday_remarks",
                    },
                    {
                        command : [{name:'edit'},{ text : 'Set Locations', click : viewModel.buttonHandler.setLocations}],
                        width : 190,    
                    },
                  
                ]
            });

            $('input:checkbox.urights').click(function(){
			var url = '';
                if($(this).prop('checked')){
                    url = 'holiday/location-create';
                }else{
                    url = 'holiday/location-destroy';
                }
                if($("#userid").val()!=""){
                    $.post(url,{ holiday_id : viewModel.holiday_id, location_id : this.value  },function(data){	});
                }
                
            });

            function holidayTypeEditor(container, options)
            {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoDropDownList({
                //.kendoComboBox({
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "type_description",
                    dataValueField: "id",
                    dataSource: {
                        //type: "json",
                        transport: {
                            read: 'holiday/types'
                        }
                    }
                });
            }

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection