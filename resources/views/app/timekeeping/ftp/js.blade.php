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
                                url : 'ftp/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            create : {
                                url : 'ftp/create',
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
                                url : 'ftp/update',
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
                                    data.ftp_date = kendo.toString(data.ftp_date,'yyyy-MM-dd');
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
                                    biometric_id : {type:"number"},
                                    ftp_date : {type:"date"},
                                    ftp_time : {type:"string"},
                                    ftp_state : {type:"string"},
                                    encoded_by : {type:"number"},
                                    ftp_remarks : {type:"string"},
                                    
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {  
                   
                },
                functions : {
                    
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
                toolbar : [{ name :'create',text:'Add FTP' }],
                editable : "inline",
                columns : [
                    {
                        title : "Date",
                        field : "holiday_date",
                        template : "#= (data.ftp_date) ? kendo.toString(data.ftp_date,'MM/dd/yyyy') : ''  #",
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
                        command : ['edit']
                    }
                  
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