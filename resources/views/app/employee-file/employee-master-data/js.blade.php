@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createEmployee" > <span class="k-icon k-i-plus k-button-icon"></span>Create Employee</button>
    </script>
   
    <script>
        $(document).ready(function(){

            let genderOptions = [
                    { text: "Male", value: "M" },
                    { text: "Female", value: "F" },
                ];
            
                let civilStatusOptions = [
                    { text: "Sinlge", value: 1 },
                    { text: "Married", value: 2 },
                    { text: "Divorced", value: 3 },
                    { text: "Widowed", value: 4 },
                ];

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        id : null,
                        firstname: null,
                        lastname: null,
                        middlename: null,
                        suffixname: null,
                        biometric_id: null,
                        primary_addr: null,
                        secondary_addr: null,
                        remarks: null,
                        sss_no: null,
                        deduct_sss: null,
                        tin_no: null,
                        phic_no: null,
                        deduct_phic: null,
                        hdmf_no: null,
                        deduct_hdmf: null,
                        hdmf_contri: null,
                        civil_status: null,
                        gender: null,
                        birthdate: null,
                        employee_stat: null,
                        bank_acct: null,
                        basic_salary: null,
                        is_daily: null,
                        exit_status: null,
                    },
                    reAssignValue : function(){

                    },
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'employee-master-data/list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                          
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
                                    biometric_id : { type : 'number' },
                                    lastname : { type : 'string' },
                                    firstname : { type : 'string' },
                                    middlename : { type : 'string' },
                                    primary_addr : { type : 'string' },
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {  
                   
                    closePop : function(e){

                    },
                    createEmployee : function(e){
                        viewModel.functions.showPOP();
                    },
                    save :  function(e){
                      
                        var json_data = JSON.stringify(viewModel.form.model);

                        console.log(json_data);

                        // $.post('purchase-order/save-header',{
                        //     data : json_data
                        // },function(data,staus){
                        //     swal_success(data);
                        //     viewModel.maingrid.formReload(data);
                        // })
                        // .fail(function(data){
                        //    swal_error(data);
                           
                        // }).always(function() {
                        //     viewModel.maingrid.ds.read();
                        // });
                    }
                },
                functions : {
                    showPOP : function(data){
                       
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "864", //1124 - 1152
                            height: "710",
                            title: "Employee Information",
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
                toolbar : [
                    { template: kendo.template($("#template").html()) }
                ],
                //editable : "inline",
                columns : [
                    {
                        title : "Bio ID",
                        field : "biometric_id",
                       
                        width : 80,    
                    },
                    {
                        title : "Last Name",
                        field : "lastname",
                        width : 130,    
                    },
                    {
                        title : "First Name",
                        field : "firstname",
                        width : 120,  
                    },
                    {
                        title : "Middle Name",
                        field : "middlename",
                        width : 120,  
                    },
                    {
                        title : "JR/SR",
                        field : "suffixname",
                        width : 60,  
                    },
                    {
                        command : ['edit'],
                        width : 90,    
                    },
                ]
            });

            // $("#po_date").kendoDatePicker({
            // format: "MM/dd/yyyy"
            // });
            
            $("#firstname").kendoTextBox({ });
            $("#lastname").kendoTextBox({ });
            $("#middlename").kendoTextBox({ });
            $("#suffixname").kendoTextBox({ });
            $("#primary_addr").kendoTextBox({ });
            $("#secondary_addr").kendoTextBox({ });

            $("#gender").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: genderOptions,
                index: 0,
                //change: onChange
            });

            $("#birthdate").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#civil_status").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: civilStatusOptions,
                index: 0,
                //change: onChange
            });

            $("#contact_no").kendoTextBox({ });

            $("#sss_no").kendoTextBox({ });
            $("#phic_no").kendoTextBox({ });
            $("#hdmf_no").kendoTextBox({ });
            $("#tin_no").kendoTextBox({ });
            $("#hdmf_contri").kendoTextBox({ });
            $("#biometric_id").kendoTextBox({ });
            
            //<input type="checkbox" data-bind="checked: isChecked" /> <input class="form-check-input" type="checkbox">

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                ]
            });
            
            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection