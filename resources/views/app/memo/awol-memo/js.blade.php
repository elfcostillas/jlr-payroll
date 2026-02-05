@section('jquery')
<script id="template" type="text/x-kendo-template">
    <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createDTR" > <span class="k-icon k-i-plus k-button-icon"></span>Create Memo</button>
</script>
    <script>
        $(document).ready(function(){
            const generateYears = (startYear,endYear) => {
                const years = [];
               
                // for (let i = endYear; i >= startYear; i--) {
                //     years.push(i);
                // }

                for(let i = startYear; i <= endYear; i++)
                {
                    years.push({ text : i, value : i });
                }

                return years;
            };
            const currentDate = new Date();

            let years = generateYears(2026,new Date().getFullYear()+1)

            let monthOptions = [
                { text: "January", value: "1" },
                { text: "February", value: "2" },
                { text: "March", value: "3" },
                { text: "April", value: "4" },
                { text: "May", value: "5" },
                { text: "June", value: "6" },
                { text: "July", value: "7N" },
                { text: "August", value: "8" },
                { text: "September", value: "9" },
                { text: "October", value: "10" },
                { text: "November", value: "11" },
                { text: "December", value: "12" },
            ];

            var viewModel = kendo.observable({ 
                form : {
                    model : {
                        id : null,
                        biometric_id : null,
                        memo_to : null,
                        memo_from : null,
                        memo_date : null,
                        memo_subject : null,
                        memo_upper_body : null,
                        memo_lower_body : null,
                        prep_by_text : null,
                        prep_by_name : null,
                        prep_by_position : null,
                        noted_by_text : null,
                        noted_by_name : null,
                        noted_by_position : null,
                        noted_by_text_dept : null,
                        noted_by_name_dept : null,
                        noted_by_position_dept : null,
                        memo_month : null,
                        memo_year : null
                    }
                },
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : `awol/list/${currentDate.getFullYear()}/${currentDate.getMonth()}`,
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
                                    biometric_id : {type : 'number',editable :false },
                                    name : { type:'string' },
                                    remarks: { type:'string' },
                                    //date_from: { type:'date' },
                                    //date_to: { type:'date' },
                                    empname: { type:'string' },
                                }
                            }
                        }
                    }),
                    employees : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'tardiness-to-employee/employee-list',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                            
                        },
                        
                        schema : {
                          
                            model : {
                                id : 'biometric_id',
                                fields : {
                                    biometric_id : {type : 'number',editable :false },
                                    employee_name : { type:'string' },
                                }
                            }
                        }
                    }),
                    
                    periods : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'tardiness-to-employee/year',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                            model : {
                                id : 'dtr_year',
                                fields : {
                                    dtr_year : { type: "number" },
                                  
                                }
                            }
                        }
                    })
                },
                functions : {
                    showPOP : function()
                    {
                        var myWindow = $("#pop");
                        
                        myWindow.kendoWindow({
                            width: "1124", //1124 - 1152
                            height: "660",
                            title: "Memo Form",
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
                    reAssignValues : function (){
                        //viewModel.form.model.set('date_from',kendo.toString($('#date_from').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        //viewModel.form.model.set('date_to',kendo.toString($('#date_to').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        //viewModel.form.model.set('period_id',$("#period_id").data('kendoDropDownList').value());
                        
                        viewModel.form.model.set('biometric_id',$('#biometric_id').data('kendoComboBox').value());
                        viewModel.form.model.set('memo_to',$('#biometric_id').data('kendoComboBox').text());
                        //viewModel.form.model.set('memo_to',$('#memo_to').data('kendoComboBox').text());
                        viewModel.form.model.set('memo_date',kendo.toString($('#memo_date').data('kendoDatePicker').value(),'yyyy-MM-dd'));
                        viewModel.form.model.set('memo_month',$("#memo_month").data('kendoDropDownList').value());
                        viewModel.form.model.set('memo_year',$("#memo_year").data('kendoDropDownList').value());
                        // console.log($('#memo_to').data('kendoComboBox').value());
                        // console.log($('#memo_to').data('kendoComboBox').text());
                    },
                    prepareForm :function(data){

                    }
                },
                toolbarHandler : {
                    
                },
                buttonHandler : {
                    createDTR : function()
                    {
                        viewModel.buttonHandler.clear();
                        viewModel.functions.showPOP();

                        let url = `tardiness-to-employee/read/0`;
                        read(url,viewModel);
                        // viewModel.form.model.set('memo_month',null);
                        // viewModel.form.model.set('memo_year',null);
                        //console.log(viewModel.form.model);
                        console.log(viewModel.form.model);
                    },
                    view : async function (e)
                    {
                        e.preventDefault(); 

                        // viewModel.functions.showPOP();

                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);

                        let url  = `awol/print/${data.payroll_year}/${data.payroll_month}/${data.emp_id}/${data.awol_group_no}`;

                        window.open(url);
                        // console.log(data);

                        // // viewModel.set('selected',data);

                        // let url  = `tardiness-to-employee/read/${data.id}`;
                        // //await viewModel.functions.prepareForm(data);
                        // read(url,viewModel);

                        // let detailUrl = `manual-dtr/details/${data.id}`;
                        // viewModel.ds.dtrgrid.transport.options.read.url = detailUrl;
                        // viewModel.ds.dtrgrid.read();
                    },
                    save : async function(e){

                        await viewModel.functions.reAssignValues(); 

                        var json_data = JSON.stringify(viewModel.form.model);

                        $.post('tardiness-to-employee/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                            let url  = `tardiness-to-employee/read/${data}`;
                            read(url,viewModel);

                            viewModel.ds.maingrid.read();
                            //viewModel.maingrid.formReload(data);
                        })
                        .fail(function(data){
                           swal_error(data);
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });
                    },

                    reload : function(e){
                        let month = $("#memo_month").data('kendoDropDownList').value();
                        let year = $("#memo_year").data('kendoDropDownList').value();

                        let url = `http://172.17.42.108/memos/awol/list/${year}/${month}`;
                    },

                    regroup : function(e){
                        alert('regroup');
                    },

                    saveAsNew: async function(e){
                        
                        await viewModel.functions.reAssignValues(); 

                        viewModel.form.model.set('id',null);
                        var json_data = JSON.stringify(viewModel.form.model);

                        $.post('tardiness-to-employee/save',{
                            data : json_data
                        },function(data,staus){
                            swal_success(data);

                            let url  = `tardiness-to-employee/read/${data}`;
                            read(url,viewModel);

                            viewModel.ds.maingrid.read();
                            //viewModel.maingrid.formReload(data);
                        })
                        .fail(function(data){
                        swal_error(data);
                        }).always(function() {
                            //viewModel.maingrid.ds.read();
                        });
                    },
                    clear : function(e){
                        // viewModel.form.model.set('id',null);
                        // viewModel.form.model.set('remarks',null);
                        // //viewModel.form.model.set('date_from',null);
                        // //viewModel.form.model.set('date_to',null);
                        // viewModel.form.model.set('period_id',0);
                        // viewModel.form.model.set('biometric_id',null);

                        // let detailUrl = `manual-dtr/details/0`;
                        // viewModel.ds.dtrgrid.transport.options.read.url = detailUrl;
                        // viewModel.ds.dtrgrid.read();
                        //console.log(viewModel.form.model);
                        $("#memo_year").data('kendoDropDownList').select(0)
                        
                    },
                    print : function(){

                        let url = `tardiness-to-employee/print/${viewModel.form.model.id}`;
                        window.open(url);
                    },
                },
                closePop : function ()
                {

                },
                callBack : function()
                {

                }
            });

            $("#memo_date").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

            $("#date_to").kendoDatePicker({
                format: "MM/dd/yyyy"
            });

        //    $("#biometric_id").kendoTextBox({ });

            $("#biometric_id").kendoComboBox({
                dataSource : viewModel.ds.employees,
                dataTextField: "employee_name",
                dataValueField: "biometric_id",
                autoWidth: true,
                filter : "contains",
                // optionLabel: {
                //     template: "Select Period",
                //     id: 0
                // }
            });

            $("#memo_subject").kendoTextBox({ });

            $("#memo_upper_body").kendoTextArea({ rows: 2 });
            $("#memo_lower_body").kendoTextArea({ rows : 10 });
    
            $("#prep_by_name").kendoTextBox({ });
            $("#prep_by_position").kendoTextBox({ });

            $("#noted_by_name").kendoTextBox({ });
            $("#noted_by_position").kendoTextBox({ });

            $("#noted_by_name_dept").kendoTextBox({ });
            $("#noted_by_position_dept").kendoTextBox({ });

            $("#memo_month").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: monthOptions,
                index: 0,
                dataBound : function(e){
                    // console.log(e.sender.value);
                    // form.model.memo_month 
                    viewModel.form.model.set('memo_month',$("#memo_month").data('kendoDropDownList').value());
                        
                },
 
                //change: onChange
            });

            $("#memo_year").kendoDropDownList({
                dataTextField: "dtr_year",
                dataValueField: "dtr_year",
                dataSource: viewModel.ds.periods,
                index: 1,
                dataBound : function(e){
                  
                },
                //change: onChange
                optionLabel: {
                    dtr_year: "",
                    dtr_year: ""
                }
            });

            var activeToolbar = $("#toolbar").kendoToolBar({
                items : [
                    { id : 'saveBtn', type: "button", text: "Save", icon: 'save', click : viewModel.buttonHandler.save },
                    { id : 'clearBtn', type: "button", text: "Clear", icon: 'delete', click : viewModel.buttonHandler.clear },
                    { id : 'clearBtn', type: "button", text: "Print", icon: 'print', click : viewModel.buttonHandler.print },
                    { id : 'saveBtn', type: "button", text: "Save as New", icon: 'save', click : viewModel.buttonHandler.saveAsNew },
                ]
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
                height : 450,
                scrollable: true,
                toolbar : [
                    // { template: kendo.template($("#template").html()) }
                ],
                editable : false,
                columns : [
                    {
                        title : "ID",
                        field : "awol_group_no",
                        width : 80,    
                        
                    },
                    {
                        title : "BIO ID",
                        field : "emp_id",
                        width : 80,    
                    },
                    {
                        title : "Employee Name",
                        field : "employee_name",
                        width : 180,    
                    },
                    {
                        title : "No. of Days",
                        field : "awol",
                        width : 135
                    },
                    {
                        title : "Subject",
                        field : "",
                        template : " AWOL memo for #= payroll_month_label # #: payroll_year #"
                    },
                    // {
                    //     title : "Encoded By",
                    //     field : "name",
                    //     width : 135,    
                    // },
                    {
                        command: { text : 'View',icon : 'print' ,click : viewModel.buttonHandler.view },
                        attributes : { style : 'font-size:10pt !important;'},
                        width : 85
                    },
                    
                ]
            });

            /*
             change : function(e){
                let v = e.sender.value();
                let nv = v.substring(0,2)+':'+ v.substring(2,4);
                alert(nv);
              }
              */

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection