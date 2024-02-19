@section('jquery')

<script id="template" type="text/x-kendo-template">
    <button class="k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:toolbarHandler.addFTP" > <span class="k-icon k-i-plus k-button-icon"></span>Add FTP</button>
</script>

    <script>
        $(document).ready(function(){

            let ftp_types =  [
                    { text: "Official Business", value: "OB" },
                    { text: "Personal Reason", value: "PR" },
                ];
            
            let obj = {
                id : null,
                        biometric_id : null,
                        ftp_date : null,
                        ftp_type : null,
                        ftp_reason : null,
                        time_in : null,
                        time_out : null,
                        ot_in : null,
                        ot_out : null,
                        ftp_status : null,
            };

            var viewModel = kendo.observable({ 
                holiday_id : null,
                location : [],
                form : {
                    model : {
                        id : null,
                        biometric_id : null,
                        ftp_date : null,
                        ftp_type : null,
                        ftp_reason : null,
                        time_in : null,
                        time_out : null,
                        ot_in : null,
                        ot_out : null,
                        ftp_status : null,
                    }
                },
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
                                    id : { type : "number", editable : false },
                                    biometric_id : {type:"number",editable : false},
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
                toolbarHandler : {  

                    addFTP : function(e) {
                        let url  = `ftp/read/0`;

                        read(url,viewModel);
                        viewModel.buttonHandler.clear();
                        
                        
                        viewModel.functions.showPOP();

                    },
                    viewFTP : function(e) {
                        e.preventDefault(); 
                        viewModel.functions.showPOP();

                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        let url  = `ftp/read/${data.id}`;

                        read(url,viewModel);

                    },
                    // accept : function(e){
                    //     let tr = $(e.target).closest("tr");
                    //     let data = this.dataItem(tr);
                    //     Swal.fire({
                    //         title: 'Accept FTP?',
                    //         text: "You won't be able to revert this!",
                    //         icon: 'warning',
                    //         showCancelButton: true,
                    //         confirmButtonColor: '#3085d6',
                    //         cancelButtonColor: '#d33',
                    //         confirmButtonText: 'Accept'
                    //     }).then((result) => {
                    //         if (result.value) {        
                    //             //console.log(data.id);               
                    //             $.post('ftp/approve',{
                    //                 ftp_id : data.id
                    //             },function(data,status,){
                    //                 if(data.success){
                    //                     Swal.fire({
                    //                         //position: 'top-end',
                    //                         icon: 'success',
                    //                         title: data.success,
                    //                         showConfirmButton: false,
                    //                         timer: 1000
                    //                     });	

                    //                     viewModel.ds.maingrid.read();
                    //                 }
                    //                 else {
                    //                     custom_error(data.error);
                    //                 }
                    //             },'json');
                    //         }
                    //     });
                    // }
                },
                buttonHandler : {
                    closePop: function(e){
                    
                    },

                    save : async function(e) {

                        await viewModel.functions.reAssignValues();
                        var json_data = JSON.stringify(viewModel.form.model);
                        
                        await $.post('ftp/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                          

                            if(data!=null)
                            {
                                let url  = `ftp/read/${data}`;
                                setTimeout(function(){
                                    read(url,viewModel);
                                }, 500);
                            }   
                            
                         
                            viewModel.ds.maingrid.read();
                           
                        })
                        .fail(function(data){
                           swal_error(data);
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });
                    },
                    post : async function(e){ 
                            await viewModel.functions.reAssignValues();
                            
                            if(viewModel.form.model.id != null){
                                Swal.fire({
                                    title: 'Finalize and Post FTP',
                                    text: "You won't be able to revert this!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Finalize'
                                }).then((result) => {
                                    if (result.value) {    
                                        
                                        
                                        viewModel.form.model.set('ftp_status','POSTED');
                                        var json_data = JSON.stringify(viewModel.form.model);
                                        
                                        $.post('ftp/save',{
                                            data : json_data
                                        },function(data,status){
                                            //console.log(status,data);
                                            //console.log(data.error)
                                            swal_success(data);

                                            if(data!=null)
                                            {
                                                let url  = `ftp/read/${data}`;
                                                setTimeout(function(){
                                                    read(url,viewModel);
                                                }, 500);
                                            }   

                                            viewModel.ds.maingrid.read();
                                            
                                        },'json');
                                    }
                                });
                            }else{
                                custom_error("Please save document first.");
                            }
                    },
                    clear : function(e){
                        for (var key in obj) {
                            //console.log(key); //console.log(key + " -> " + p[key]);
                            viewModel.form.model.set(key,null);
                        }
                        viewModel.form.model.ftp_status = 'DRAFT';
                        viewModel.callBack();
                        
                    }
                },
                functions : {
                    showPOP : function(data){
                       
                       var myWindow = $("#pop");
                       
                       myWindow.kendoWindow({
                           width: "810", //1124 - 1152
                           height: "360",
                           title: "Failure to Punch - Form",
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
                       }).data("kendoWindow").center().open();
                       
                    },
                    reAssignValues : function(){ 
                        viewModel.form.model.set('biometric_id',$('#biometric_id').data('kendoComboBox').value());
                        viewModel.form.model.set('ftp_date',kendo.toString($('#ftp_date').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        
                        viewModel.form.model.set('ftp_type',$('#ftp_type').data('kendoDropDownList').value());
                    }
                },
                callBack : function(e){
                    console.log(viewModel.form.model.ftp_status);
                    if(viewModel.form.model.ftp_status=='POSTED'){
                        activeToolbar.hide();
                        postedToolbar.show();

                        // let ptoolbar = $("#toolbar2").data("kendoToolBar");

                        // if(super_user=='Y'){
                        //     ptoolbar.show($("#reOpenBtn"));
                        // }else {
                        //     ptoolbar.hide($("#reOpenBtn"));
                        // }
                    }else{
                        activeToolbar.show();
                        postedToolbar.hide();
                    }
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
                // toolbar : [{ name :'create',text:'Add FTP' }],
                toolbar : [{ template : kendo.template($("#template").html())} ],
                editable : "inline",
                columns : [
                    {
                        title : "FTP ID",
                        field : "id",
                        width : 80
                    },
                    {
                        title : "Type",
                        field : "ftp_remarks",
                        width : 120,  
                    },
                    {
                        title : "Bio ID",
                        field : "biometric_id",
                        width : 75,  
                    },
                    {
                        title : "Employee Name",
                        field : "employee_name",
                        // template : "#= (data.ftp_date) ? kendo.toString(data.ftp_date,'MM/dd/yyyy') : ''  #",
                        width : 205,    
                    },
                    {
                        title : "Date",
                        field : "ftp_date",
                        template : "#= (data.ftp_date) ? kendo.toString(data.ftp_date,'MM/dd/yyyy') : ''  #",
                        width : 85,    
                    },
                    {
                        title : "Time In",
                        field : "time_in",
                        attributes : { style : 'text-align:center;'},
                        width : 85,    
                    },
                    {
                        title : "Time Out",
                        field : "time_out",
                        attributes : { style : 'text-align:center;'},
                        width : 90,    
                    },
                    {
                        title : "O.T. In",
                        field : "ot_in",
                        attributes : { style : 'text-align:center;'},
                        width : 75,    
                    },
                    {
                        title : "O.T. Out",
                        field : "ot_out",
                        attributes : { style : 'text-align:center;'},
                        width : 85,    
                    },
                    {
                        title : "Remarks",
                        field : "ftp_reason",
                    },
                    {
                        title : "Status",
                        field : "ftp_status",
                        attributes : { style : 'text-align:center;'},
                        width : 85,    
                    },
                    {
                        command: { text : 'View',icon : '' ,click : viewModel.toolbarHandler.viewFTP },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 70
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
                            read: 'employee-list'
                        }
                    }
                });
            }

            function nameEditor (container, options) {
                $('<input name="' + options.field + '"/>')
                .appendTo(container)
                .kendoComboBox({
               
                    //autoBind: false,
                    autoWidth: true,
                    dataTextField: "type_description",
                    dataValueField: "id",
                    dataSource: {
                        
                        transport: {
                            read: 'employee-list'
                        }
                    }
                });
            }

            $("#ftp_type").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: ftp_types,
                // index: 0,
                //change: onChange
            });

            $("#biometric_id").kendoComboBox({
                dataTextField: "employee_name",
                dataValueField: "biometric_id",
                filter: "contains",
                dataSource: {
                    transport: {
                        read: 'ftp/employee-list'
                    }
                }
               
                //change: onChange
            });

            $("#ftp_date").kendoDatePicker({
                format : "MM/dd/yyyy"
            });

            $("#time_in").kendoTextBox({
                change : formatTime
            });

            
            $("#time_out").kendoTextBox({
                change : formatTime
            });

            $("#ot_in").kendoTextBox({
                change : formatTime
            }); 

            $("#ot_out").kendoTextBox({
                change : formatTime
            }); 

            $("#ftp_reason").kendoTextBox({}); 

            function formatTime(e){
                // console.log(e.sender._value);
                let time = e.sender._value;

                let vtext = pad(time,4);

                if(time!="" && time != "00:00" ){
                    viewModel.form.model.set(e.sender.element[0].id,(vtext.includes(':')) ? vtext : vtext.substring(0,2)+':'+ vtext.substring(2,4));
                }else {
                    viewModel.form.model.set(e.sender.element[0].id,'');
                }

            }

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'postBtn', type: "button", text: "Post", icon: 'print', click : viewModel.buttonHandler.post },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                ]
            });

            var postedToolbar = $("#toolbar2").kendoToolBar({
                items : [
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                ]
            });

            kendo.bind($("#viewModel"),viewModel);

        });

    </script>

@endsection