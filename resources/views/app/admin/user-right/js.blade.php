@section('jquery')
    <script>
        $(document).ready(function(){

            var viewModel = kendo.observable({ 
                rights : [],
                user_id : null,
                ds : {
                    maingrid : new kendo.data.DataSource({
                        transport : {
                            read : {
                                url : 'admin/user-list',
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
                                    date_from : { type : 'date' },
                                    date_to: { type : 'date' },
                                    date_release: { type : 'date' },
                                    man_hours: { type : 'number' },
                                }
                            }
                        }
                    }),
                },
                buttonHandler : {
                    viewRights : function(e){
                        viewModel.functions.showPOP();

                        var tr = $(e.target).closest("tr");
                        var data = this.dataItem(tr);
                        
                        //viewModel.userrights(data.id);
                        
                        let url = `admin/show-user-rights/${data.id}`;

                        viewModel.set('user_id',data.id);

                        $.ajax({
                            url:'admin/userrights',
                            type:"POST",
                            data: {user_id : data.id },
                            dataType:"json",
                            success: function(data){
                            
                                let user_rights = data['rights'];  
                            
                                let h = [];
                                
                                user_rights.forEach(function(item,index){
                                    h.push(item.sub_menu_id.toString());
                                });

                                viewModel.set('rights',h);

                                //console.log(viewModel.rights);
                            }	
                        });
                    },
                    closeRights : function(e){

                    }
                },
                functions : {
                    showPOP : function(e){
				
                        var myWindow = $("#pop");

                        myWindow.kendoWindow({
                            width: "540", //1124 - 1152
                            height: "580",
                            title: "User Rights",
                            visible: false,
                            animation: false,
                            actions: [
                                "Pin",
                                "Minimize",
                                "Maximize",
                                "Close"
                            ],
                            close: viewModel.buttonHandler.closeRights,
                            position : {
                                top : 0
                            }
                        }).data("kendoWindow").center().open();
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
                //toolbar : [{ name :'create',text:'Add Payroll Period'}],
                editable : "inline",
                columns : [
                    {
                        title : "ID",
                        field : "id",
                        //template : "#= (data.date_from) ? kendo.toString(data.date_from,'MM/dd/yyyy') : ''  #",
                        width : 80,    
                    },
                    {
                        title : "Name",
                        field : "name",
                        //template : "#= (data.date_to) ? kendo.toString(data.date_to,'MM/dd/yyyy') : ''  #",
                        width : 190,    
                    },
                    {
                        title : "User Name",
                        field : "email",
                        //template : "#= (data.date_release) ? kendo.toString(data.date_release,'MM/dd/yyyy') : ''  #",
                        //width : 120,    
                    },
                    {
                        width : 80,
                        command: { text : 'View' ,click : viewModel.buttonHandler.viewRights },
                        attributes : { style : 'font-size:10pt !important;'} 
                    }

                ]
            });

            $('input:checkbox.urights').click(function(){
			var url = '';
                if($(this).prop('checked')){
                    url = 'admin/rights-create';
                }else{
                    url = 'admin/rights-destroy';
                }
                if($("#userid").val()!=""){
                    $.post(url,{ user_id : viewModel.user_id, rights_id : this.value  },function(data){	});
                }
                
            });

            kendo.bind($("#viewModel"),viewModel);

        });
    </script>

@endsection