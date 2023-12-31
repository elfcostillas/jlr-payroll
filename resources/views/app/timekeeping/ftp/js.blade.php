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
                                    ftp_reason : {type:"string"},
                                    employee_name: {type:"string"},

                                    time_in: {type:"string"},
                                    time_out: {type:"string"},
                                    ot_in: {type:"string"},
                                    ot_out: {type:"string"},
                                    
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {  
                    accept : function(e){

                        let tr = $(e.target).closest("tr");
                        let data = this.dataItem(tr);

                        Swal.fire({
                            title: 'Accept FTP?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Accept'
                        }).then((result) => {
                            if (result.value) {        
                                //console.log(data.id);               
                                $.post('ftp/approve',{
                                    ftp_id : data.id
                                },function(data,status,){
                                    
                                    if(data.success){
                                        Swal.fire({
                                            //position: 'top-end',
                                            icon: 'success',
                                            title: data.success,
                                            showConfirmButton: false,
                                            timer: 1000
                                        });	

                                        viewModel.ds.maingrid.read();
                                    }
                                    else {
                                        custom_error(data.error);
                                    }
                                },'json');
                            }
                        });
                    }
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
                //toolbar : [{ name :'create',text:'Add FTP' }],
                editable : "inline",
                columns : [
                    {
                        title : "Type",
                        field : "ftp_remarks",
                        width : 160,  
                    },
                    {
                        title : "Biometric ID",
                        field : "biometric_id",
                        width : 120,  
                    },
                    {
                        title : "Employee Name",
                        field : "employee_name",
                        // template : "#= (data.ftp_date) ? kendo.toString(data.ftp_date,'MM/dd/yyyy') : ''  #",
                        width : 220,    
                    },
                    {
                        title : "Date",
                        field : "ftp_date",
                        template : "#= (data.ftp_date) ? kendo.toString(data.ftp_date,'MM/dd/yyyy') : ''  #",
                        width : 120,    
                    },
                    {
                        title : "Time In",
                        field : "time_in",
                        width : 100,    
                    },
                    {
                        title : "Time Out",
                        field : "time_out",
                        width : 100,    
                    },
                    {
                        title : "O.T. In",
                        field : "ot_in",
                        width : 100,    
                    },
                    {
                        title : "O.T. Out",
                        field : "ot_out",
                        width : 100,    
                    },
                    {
                        title : "Remarks",
                        field : "ftp_reason",
                    },
                    {
                        command: { text : 'Receive',icon : 'edit' ,click : viewModel.buttonHandler.accept },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 110
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