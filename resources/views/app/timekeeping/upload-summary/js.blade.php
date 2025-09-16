@section('jquery')
    <script>
        $(document).ready(function(){

            var token = $('meta[name="_token"]').attr('content');  

            $("#files").kendoUpload({
                async: {
                    //chunkSize: 11000,// bytes
                    saveUrl: "dtr-summary-uploader/upload",
                    //removeUrl: "remove",
                    autoUpload: false
                },
                validation: {
                    maxFileSize: 20000000,
                    allowedExtensions: [".csv"]
                },
                upload: onUpload
            });

            function onUpload(e) {
                var xhr = e.XMLHttpRequest;
                if (xhr) {
                    xhr.addEventListener("readystatechange", function (e) {
                        if (xhr.readyState == 1 /* OPENED */) {
                            xhr.setRequestHeader("X-CSRF-TOKEN", token);
                        }
                    
                    });

                    // e.data = {
                    //     period_id : 11
                    // };
                }
            }
            //e.data = { dropdownlist: $("#dropdownlist").val(), docnumber: $("#docnumber").val() };

        });
    </script>

@endsection