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

            let obj = {
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
                    contact_no : null,
                    division_id: null,
                    dept_id: null,
            };

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
                        contact_no : null,
                        division_id: null,
                        dept_id: null,
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
                                    division_id: { type : 'number' },
                                    dept_id: { type : 'number' },
                                }
                            }
                        }
                    }),
                    division : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'divisions-departments/division/get-divisions',
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
                                    div_code : { type : 'string' },
                                    div_name : { type : 'string' },
                                }
                            }
                        }
                    }),
                    department : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'divisions-departments/department/list-option/0',
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
                                    div_code : { type : 'string' },
                                    div_name : { type : 'string' },
                                }
                            }
                        }
                    }),
                    
                },
                buttonHandler : {  
                   
                    closePop : function(e){

                    },
                    createEmployee : function(e){
                        viewModel.buttonHandler.clear();
                        viewModel.functions.showPOP();
                    },
                    save : async function(e){

                        await viewModel.functions.reAssignValues();
                        
                        var json_data = JSON.stringify(viewModel.form.model);
                        
                        $.post('employee-master-data/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);
                            viewModel.ds.maingrid.read();
                            //viewModel.maingrid.formReload(data);
                        })
                        .fail(function(data){
                           swal_error(data);
                           
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });

                       
                    },
                    view : async function(e){
                        e.preventDefault(); 

                        viewModel.functions.showPOP();
                       
                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        let url  = `employee-master-data/read/${data.id}`;
                        await viewModel.functions.prepareForm(data);
                        read(url,viewModel);
                        //console.log(data);

                    }, 
                    clear : function(e){
                        //$.each(viewModel.form.model,function(index,value));
                        for (var key in obj) {
                            //console.log(key); //console.log(key + " -> " + p[key]);
                            viewModel.form.model.set(key,null);
                        }

                        viewModel.form.model.set('civil_status',1)
                        viewModel.form.model.set('gender','M')
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
                        
                    },
                    reAssignValues : function(){
                        viewModel.form.model.set('gender',$('#gender').data('kendoDropDownList').value());
                        viewModel.form.model.set('birthdate',kendo.toString($('#birthdate').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('civil_status',$('#civil_status').data('kendoDropDownList').value());
                        viewModel.form.model.set('division_id',$('#division_id').data('kendoDropDownList').value());
                        viewModel.form.model.set('dept_id',($('#dept_id').data('kendoDropDownList').value()!='') ? $('#dept_id').data('kendoDropDownList').value() : 0 );
                    },
                    prepareForm : function(data)
                    {
                       
                        let deptUrl = `divisions-departments/department/list-option/${data.division_id}`;
                        viewModel.ds.department.transport.options.read.url = deptUrl;
                        viewModel.ds.department.read();
                    }
                   

                },
                callBack : function()
                {

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
                toolbar : [
                    { template: kendo.template($("#template").html()) }
                ],
                //editable : "inline",
                columns : [
                    {
                        title : "Bio ID",
                        field : "biometric_id",
                       
                        width : 90,    
                    },
                    {
                        title : "Last Name",
                        field : "lastname",
                        width : 140,    
                    },
                    {
                        title : "First Name",
                        field : "firstname",
                        width : 140,  
                    },
                    {
                        title : "Middle Name",
                        field : "middlename",
                        width : 140,  
                    },
                    {
                        title : "Jr,Sr,II,III",
                        field : "suffixname",
                        width : 90,  
                    },
                    {
                        title : "Division",
                        field : "div_code",
                        //width : 90,  
                    },
                    {
                        title : "Department",
                        field : "dept_code",
                        //width : 90,  
                    },
                    {
                        command: { text : 'View',icon : 'edit' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'} 
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
                index: 1,
                //change: onChange
            });

            $("#birthdate").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#civil_status").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: civilStatusOptions,
                index: 1,
                //change: onChange
            });

            $("#civil_status").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: civilStatusOptions,
                index: 1,
                //change: onChange
            });

            $("#division_id").kendoDropDownList({
                dataTextField: "div_name",
                dataValueField: "id",
                dataSource: viewModel.ds.division,
                index: 1,
                change: function(e){
                    let selected = e.sender.dataItem();
                    let deptUrl = `divisions-departments/department/list-option/${selected.id}`;
                    viewModel.ds.department.transport.options.read.url = deptUrl;
                    viewModel.ds.department.read();
                }
            });

            $("#dept_id").kendoDropDownList({
                dataTextField: "dept_name",
                dataValueField: "id",
                dataSource: viewModel.ds.department,
                index: 1,
                dataBound : function(e){
                    if(viewModel.form.model.dept_id!=null){
                        $('#dept_id').data('kendoDropDownList').value(viewModel.form.model.dept_id);
                    }
                    
                }
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
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                ]
            });
            
            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection