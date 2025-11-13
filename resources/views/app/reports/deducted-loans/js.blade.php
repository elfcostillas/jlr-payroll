@section('jquery')
    <script id="template" type="text/x-kendo-template">
        <button class="k-grid-add k-button k-button-md k-rounded-md k-button-solid k-button-solid-base" data-bind="click:buttonHandler.createEmployee" > <span class="k-icon k-i-plus k-button-icon"></span>Create Employee</button>
    </script>
   
    <script>
        $(document).ready(function(){

            // let payroll_period =<?php echo json_encode($payroll_period) ?>;
            // let payroll_period_sg =<?php echo json_encode($payroll_period_sg) ?>;

            let loan_types =<?php echo json_encode($loan_types) ?>;
            let loan_types_sg =<?php echo json_encode($loan_types) ?>;

            let fy_year =<?php echo json_encode($fy_year) ?>;

            let months = [
                { text: "January", value: "1" },
                { text: "February", value: "2" },
                { text: "March", value: "3" },
                { text: "April", value: "4" },
                { text: "May", value: "5" },
                { text: "June", value: "6" },
                { text: "July", value: "7" },
                { text: "August", value: "8" },
                { text: "September", value: "9" },
                { text: "October", value: "10" },
                { text: "November", value: "11" },
                { text: "December", value: "12" },
            ];

            $("#payroll_period").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: months,
                index: 0,
                dataBound : function(e){
                  
                }
                
            });

            $("#fy_year_jlr").kendoDropDownList({
                dataTextField: "fy",
                dataValueField: "fy",
                dataSource: fy_year,
                index: 0,
                dataBound : function(e){
                  
                }
                
            });

            $("#fy_year_sg").kendoDropDownList({
                dataTextField: "fy",
                dataValueField: "fy",
                dataSource: fy_year,
                index: 0,
                dataBound : function(e){
                  
                }
                
            });

            $("#payroll_period_sg").kendoDropDownList({
                dataTextField: "text",
                dataValueField: "value",
                dataSource: months,
                index: 0,
                dataBound : function(e){
                  
                }
                
            });

            $("#loan_tye").kendoDropDownList({
                dataTextField: "description",
                dataValueField: "id",
                dataSource: loan_types,
                index: 0,
                dataBound : function(e){
                  
                }
                
            });

             $("#loan_tye_sg").kendoDropDownList({
                dataTextField: "description",
                dataValueField: "id",
                dataSource: loan_types_sg,
                index: 0,
                dataBound : function(e){
                  
                }
                
            });

            var viewModel = kendo.observable({ 
                included : [],
                form : {
                    model : {
                        
                        division_id: null,
                        dept_id: null,
                    },
                  
                },
                ds : {
                    fy : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../reports/contributions-jlr/year',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    
                                }
                            },
                        },
                        schema : {
                           
                            model : {
                                id : 'fy',
                                fields : {
                                    fy : { type : 'number',editable :false },   
                                    
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {  
                    download : function()
                    {
                        // process();
                        let period =  $("#payroll_period").data("kendoDropDownList").value();
                        let fy_year =  $("#fy_year_jlr").data("kendoDropDownList").value();
                        let loan_type =  $("#loan_tye").data("kendoDropDownList").value();

                        let url = `govt-loans/download/${period}/${fy_year}/${loan_type}/semi`;
                        window.open(url);

                        // console.log(period,fy_year,loan_type);
                    },

                    download_confi : function()
                    {
                        // process();
                        let period =  $("#payroll_period").data("kendoDropDownList").value();
                        let fy_year =  $("#fy_year_jlr").data("kendoDropDownList").value();
                        let loan_type =  $("#loan_tye").data("kendoDropDownList").value();

                        let url = `govt-loans/download/${period}/${fy_year}/${loan_type}/confi`;
                        window.open(url);

                        // console.log(period,fy_year,loan_type);
                    },

                    download_sg : function()
                    {
                        // process();
                        let period =  $("#payroll_period_sg").data("kendoDropDownList").value();
                        let fy_year =  $("#fy_year_sg").data("kendoDropDownList").value();
                        let loan_type =  $("#loan_tye_sg").data("kendoDropDownList").value();

                        let url = `govt-loans/download/${period}/${fy_year}/${loan_type}/sg`;
                        window.open(url);

                        // console.log(period,fy_year,loan_type);
                    },
                }
            });

            function process()
            {
                // let period =  $("#payroll_period").data("kendoDropDownList").value();
                // let fy_year =  $("#fy_year_jlr").data("kendoDropDownList").value();
                // let loan_type =  $("#loan_tye").data("kendoDropDownList").value();
                // let url = `reports/govt-loansdivision=${period}&department=${fy_year}&location=${loan_type}`;
                // window.open(url);
            }

            kendo.bind($("#viewModel"),viewModel);

            /*
            $("#employee_stat").kendoDropDownList({
                dataTextField: "estatus_desc",
                dataValueField: "id",
                dataSource: emp_stat,
                index: 1,
                dataBound : function(e){
                  
                }
                
            });

            var viewModel = kendo.observable({ 
                included : [],
                form : {
                    model : {
                        
                        division_id: null,
                        dept_id: null,
                    },
                  
                },
                ds : {
                   
                    division : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../employee-files/divisions-departments/division/get-divisions',
                                type : 'get',
                                dataType : 'json',
                                complete : function(e){
                                    // viewModel.ds.division.add({
                                    //     "id": 99,
                                    //     "div_code": "QA",
                                    //     "div_name": "QA"
                                    // });

                                    // console.log('im here');
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
                    division_w : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../employee-files/divisions-departments/division/get-divisions',
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
                                url : '../employee-files/divisions-departments/department/list-option/1',
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
                    
                    department_w : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../employee-files/divisions-departments/department/list-option/1',
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
                    location : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../settings/locations/get-locations',
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
                    location_w: new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : '../settings/locations/get-locations',
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
                                    // div_code : { type : 'string' },
                                    // div_name : { type : 'string' },
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {  
                    download : function()
                    {
                        process();
                    },
                    download_weekly : function()
                    {
                        process2();
                    },
                    download_qr : function()
                    {
                        process3();
                    },
                    download_custom : function()
                    {
                        let url = 'employee-report/custom-report';
                        window.open(url);
                    },
                    download_custom_sg : function()
                    {
                        let url = 'employee-report/custom-report-sg';
                        window.open(url);
                    },
            
                },
                functions : {

                },
                callBack : function()
                {

                }
            });

            $("#division_id").kendoDropDownList({
                dataTextField: "div_name",
                dataValueField: "id",
                dataSource: viewModel.ds.division,
                //index: 1,
                change: function(e){
                    let selected = e.sender.dataItem();
                    let deptUrl = `../employee-files/divisions-departments/department/list-option/${selected.id}`;
                    viewModel.ds.department.transport.options.read.url = deptUrl;
                    viewModel.ds.department.read();
                },
                optionLabel: {
                    div_name: "ALL",
                    id: "0"
                }
            });

            $("#dept_id").kendoDropDownList({
                dataTextField: "dept_name",
                dataValueField: "id",
                dataSource: viewModel.ds.department,
                index: 0,
                //change: onChange
                optionLabel: {
                    dept_name: "ALL",
                    id: "0"
                }
            });

            $("#location_id").kendoDropDownList({
                dataTextField: "location_name",
                dataValueField: "id",
                dataSource: viewModel.ds.location,
                index: 0,
                optionLabel: {
                    location_name: "ALL",
                    id: "0"
                }
                //change: onChange
            });
            
            $("#location_id_qr").kendoDropDownList({
                dataTextField: "location_name",
                dataValueField: "id",
                dataSource: viewModel.ds.location_w,
                index: 0,
                optionLabel: {
                    location_name: "ALL",
                    id: "0"
                }
                //change: onChange
            });

            $("#dept_id_qr").kendoDropDownList({
                dataTextField: "dept_name",
                dataValueField: "id",
                dataSource: viewModel.ds.department_w,
                index: 0,
                //change: onChange
                optionLabel: {
                    dept_name: "ALL",
                    id: "0"
                }
            });

            $("#division_id_qr").kendoDropDownList({
                dataTextField: "div_name",
                dataValueField: "id",
                dataSource: viewModel.ds.division_w,
                //index: 1,
                change: function(e){
                    let selected = e.sender.dataItem();
                    let deptUrl = `../employee-files/divisions-departments/department/list-option/${selected.id}`;
                    viewModel.ds.department_w.transport.options.read.url = deptUrl;
                    viewModel.ds.department_w.read();
                },
                optionLabel: {
                    div_name: "ALL",
                    id: "0"
                }
            });


            function process()
            {
                let loc =  $("#location_id").data("kendoDropDownList").value();
                let div =  $("#division_id").data("kendoDropDownList").value();
                let dept =  $("#dept_id").data("kendoDropDownList").value();
                let url = `employee-report/generate?division=${div}&department=${dept}&location=${loc}`;
                window.open(url);
            }

            function process2()
            {
                let loc =  $("#location_id").data("kendoDropDownList").value();
                let div =  $("#division_id").data("kendoDropDownList").value();
                let dept =  $("#dept_id").data("kendoDropDownList").value();
                let url = `employee-report/generate-weekly?division=${div}&department=${dept}&location=${loc}`;
                window.open(url);
                // console.log(url);
            }

            function process3()
            {
                let loc =  $("#location_id_qr").data("kendoDropDownList").value();
                let div =  $("#division_id_qr").data("kendoDropDownList").value();
                let dept =  $("#dept_id_qr").data("kendoDropDownList").value();

                let url = `employee-report/print-weekly?division=${div}&department=${dept}&location=${loc}`;
                window.open(url);
            }

            $('input:checkbox.include_header').click(function(){
			var url = '';
                if($(this).prop('checked')){
                    url = 'employee-report/include-header';
                }else{
                    url = 'employee-report/remove-header';
                }
                if($("#userid").val()!=""){
                    $.post(url,{ header_id : this.value  },function(data){	});
                }
                
            });

            $.ajax({
                url:'employee-report/get-header',
                type:"GET",
                dataType:"json",
                success: function(data){
                
                    let header = data;  

                    let h = [];
                    
                    header.forEach(function(item,index){
                        h.push(item.id.toString());
                    });

                    viewModel.set('included',h);

                }	
            });
            
            kendo.bind($("#viewModel"),viewModel);
            */

        });
    </script>

@endsection