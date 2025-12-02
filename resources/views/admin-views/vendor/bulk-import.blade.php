@extends('layouts.admin.app')

@section('title',translate('Store Bulk Import'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/resturant.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('messages.stores_bulk_import')}}
                </span>
            </h1>
        </div>
        <!-- Content Row -->
        <div class="card">
            <div class="card-body">
                <div class="export-steps-2">
                    <div class="row g-4">
                        <div class="col-sm-6 col-lg-4">
                            <div class="export-steps-item-2 h-100">
                                <div class="top">
                                    <div>
                                        <h3 class="fs-20">{{translate('Step 1')}}</h3>
                                        <div>
                                            {{translate('Download_Excel_File')}}
                                        </div>
                                    </div>
                                    <img src="{{asset('/public/assets/admin/img/bulk-import-1.png')}}" alt="">
                                </div>
                                <h4>{{ translate('Instruction') }}</h4>
                                <ul class="m-0 pl-4">
                                    <li>
                                        {{ translate('Download_the_format_file_and_fill_it_with_proper_data.') }}
                                    </li>
                                    <li>
                                        {{ translate('You_can_download_the_example_file_to_understand_how_the_data_must_be_filled.') }}
                                    </li>
                                    <li>
                                        {{ translate('Have_to_upload_excel_file.') }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="export-steps-item-2 h-100">
                                <div class="top">
                                    <div>
                                        <h3 class="fs-20">{{translate('Step 2')}}</h3>
                                        <div>
                                            {{translate('Match_Spread_sheet_data_according_to_instruction')}}
                                        </div>
                                    </div>
                                    <img src="{{asset('/public/assets/admin/img/bulk-import-2.png')}}" alt="">
                                </div>
                                <h4>{{ translate('Instruction') }}</h4>
                                <ul class="m-0 pl-4">
                                    <li>
                                        {{ translate('Fill_up_the_data_according_to_the_format.') }}
                                    </li>
                                    <li>
                                        {{ translate('Make_sure_the_phone_numbers_and_email_addresses_are_unique.') }}
                                    </li>
                                    <li>
                                        {{ translate('You_can_get_module_id_and_zone_id_from_their_list,_please_input_the_right_ids.')}}
                                    </li>
                                    <li>
                                        {{ translate('For_delivery_time_the_format_is_"from-to_type"_for_example:_"30-40_min"._Also_you_can_use_days_or_hours_as_type._Please_be_carefull_about_this_format_or_leave_this_field_empty.') }}
                                    </li>
                                    <li>
                                        {{ translate('Latitude_must_be_a_number_between_-90_to_90_and_Longitude_must_a_number_between_-180_to_180._Otherwise_it_will_create_server_error') }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="export-steps-item-2 h-100">
                                <div class="top">
                                    <div>
                                        <h3 class="fs-20">{{translate('Step 3')}}</h3>
                                        <div>
                                            {{translate('Validate data and complete import')}}
                                        </div>
                                    </div>
                                    <img src="{{asset('/public/assets/admin/img/bulk-import-3.png')}}" alt="">
                                </div>
                                  <h4>{{ translate('Instruction') }}</h4>
                                <ul class="m-0 pl-4">
                                    <li>
                                        {{ translate('In_the_Excel_file_upload_section,_first_select_the_upload_option.') }}
                                     </li>
                                     <li>
                                        {{ translate('Upload_your_file_in_.xls,_.xlsx_format.') }}
                                     </li>
                                     <li>
                                        {{ translate('Finally_click_the_upload_button.') }}
                                     </li>
                                    <li>
                                       {{ translate('After_uploading_stores_you_need_to_edit_them_and_set_stores`s_logo_and_cover.`s_path')}}
                                    </li>
                                    <li>
                                       {{ translate('You_can_upload_your_store_images_in_store_folder_from_gallery,_and_copy_image`s_path') }}
                                    </li>
                                    <li>
                                       {{ translate('Default_password_for_store_is_12345678.') }}
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center pb-4">
                    <h3 class="mb-3 export--template-title font-regular">{{translate('download_spreadsheet_template')}}</h3>
                    <div class="btn--container justify-content-center export--template-btns">

                        <a href="{{asset('public/assets/stores_bulk_format.xlsx')}}" download="" class="btn btn--primary btn-outline-primary">{{ translate('Template with Existing Data') }}</a>
                        <a href="{{asset('public/assets/stores_bulk_format_nodata.xlsx')}}" download="" class="btn btn--primary">{{ translate('Template without Data') }}</a>

                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Section -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="tio-bug"></i> Debug Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-info" onclick="checkLogs()">
                            <i class="tio-eye"></i> Check Recent Logs
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-warning" onclick="clearLogs()">
                            <i class="tio-delete"></i> Clear Logs
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-success" onclick="analyzeFile()">
                            <i class="tio-analytics"></i> Analyze Excel File
                        </button>
                    </div>
                </div>
                <div id="debug-output" class="mt-3" style="display: none;">
                    <pre id="log-content" style="background: #f8f9fa; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto;"></pre>
                </div>
                <div id="analysis-output" class="mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6>Excel File Analysis</h6>
                        </div>
                        <div class="card-body">
                            <div id="analysis-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Debug Section -->

        <form class="product-form" id="import_form" action="{{route('admin.store.bulk-import')}}" method="POST"
        enctype="multipart/form-data">
            @csrf

        <input type="hidden" name="button" id="btn_value">
        <div class="card mt-2 rest-part">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <h5 class="text-capitalize mb-3">{{ translate('Select_Data_Upload_type') }}</h5>
                        <div class="module-radio-group border rounded">
                            <label class="form-check form--check">
                                <input class="form-check-input "   value="import" type="radio" name="upload_type" checked>
                                <span class="form-check-label py-20">
                                    {{ translate('Upload_New_Data') }}
                                </span>
                            </label>
                            <label class="form-check form--check">
                                <input class="form-check-input " value="update" type="radio" name="upload_type">
                                <span class="form-check-label py-20">
                                    {{ translate('Update_Existing_Data') }}
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="text-capitalize mb-3">{{ translate('Import_Stores_file') }}</h5>
                        <label class="uploadDnD d-block">
                            <div class="form-group inputDnD input_image input_image_edit position-relative">
                                <div class="upload-text">
                                    <div>
                                        <img src="{{asset('/public/assets/admin/img/bulk-import-3.png')}}" alt="">
                                    </div>
                                    <div class="filename">{{translate('Must_be_Excel_files_using_our_Excel_template_above')}}</div>
                                </div>
                                <input type="file" name="products_file" class="form-control-file text--primary font-weight-bold action-upload-section-dot-area" id="products_file">
                            </div>
                        </label>

                    </div>
                </div>
                <div class="btn--container justify-content-end mt-3">
                    <button id="reset_btn" type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="button" class="btn btn--primary update_or_import">{{translate('messages.Upload')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>


    </div>
@endsection

@push('script_2')
    <script>
        $('#reset_btn').click(function(){
            $('#bulk__import').val(null);
        })

        $('#reset_btn').click(function(){
        $('#products_file').val('');
        $('.filename').text('{{translate('Must_be_Excel_files_using_our_Excel_template_above')}}');
        })


    $(document).on("click", ".update_or_import", function(e){
    e.preventDefault();
    let upload_type = $('input[name="upload_type"]:checked').val();
    myFunction(upload_type)
});

$(".action-upload-section-dot-area").on("change", function () {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = () => {
                let imgName = this.files[0].name;
                $(this).closest(".uploadDnD").find('.filename').text(imgName);
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    function myFunction(data) {
        Swal.fire({
        title: '{{ translate('Are you sure?') }}' ,
        text: "{{ translate('You_want_to_') }}" +data,
        type: 'warning',
        showCancelButton: true,
        cancelButtonColor: 'default',
        confirmButtonColor: '#FC6A57',
        cancelButtonText: '{{translate('messages.no')}}',
        confirmButtonText: '{{translate('messages.yes')}}',
        reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $('#btn_value').val(data);
                $("#import_form").submit();
            }
        })
    }
        </script>
    <script>
        function checkLogs() {
            fetch('{{ route("admin.store.debug-bulk-import") }}')
                .then(response => response.json())
                .then(data => {
                    const output = document.getElementById('debug-output');
                    const content = document.getElementById('log-content');
                    
                    if (data.recent_logs && data.recent_logs.length > 0) {
                        content.textContent = data.recent_logs.join('\n');
                    } else {
                        content.textContent = 'No recent bulk import logs found.';
                    }
                    
                    output.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching logs:', error);
                    document.getElementById('log-content').textContent = 'Error fetching logs: ' + error.message;
                    document.getElementById('debug-output').style.display = 'block';
                });
        }

        function clearLogs() {
            if (confirm('Are you sure you want to clear the logs?')) {
                fetch('{{ route("admin.store.debug-bulk-import") }}?clear=1')
                    .then(response => response.json())
                    .then(data => {
                        alert('Logs cleared successfully');
                        document.getElementById('debug-output').style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Error clearing logs:', error);
                        alert('Error clearing logs: ' + error.message);
                    });
            }
        }

        function analyzeFile() {
            const fileInput = document.querySelector('input[name="products_file"]');
            if (!fileInput.files[0]) {
                alert('Please select a file first');
                return;
            }

            const formData = new FormData();
            formData.append('products_file', fileInput.files[0]);

            fetch('{{ route("admin.store.analyze-excel-file") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                const output = document.getElementById('analysis-output');
                const content = document.getElementById('analysis-content');
                
                let html = '<div class="row">';
                html += '<div class="col-md-6"><strong>Total Rows:</strong> ' + data.total_rows + '</div>';
                html += '<div class="col-md-6"><strong>Columns Found:</strong> ' + data.columns.join(', ') + '</div>';
                html += '</div><hr>';
                
                if (data.duplicate_emails.length > 0) {
                    html += '<div class="alert alert-warning"><strong>Duplicate Emails Found:</strong><br>' + data.duplicate_emails.join(', ') + '</div>';
                }
                
                if (data.duplicate_phones.length > 0) {
                    html += '<div class="alert alert-warning"><strong>Duplicate Phones Found:</strong><br>' + data.duplicate_phones.join(', ') + '</div>';
                }
                
                if (data.empty_emails > 0) {
                    html += '<div class="alert alert-info"><strong>Empty Emails:</strong> ' + data.empty_emails + ' rows</div>';
                }
                
                if (data.empty_phones > 0) {
                    html += '<div class="alert alert-info"><strong>Empty Phones:</strong> ' + data.empty_phones + ' rows</div>';
                }
                
                if (data.sample_data && data.sample_data.length > 0) {
                    html += '<hr><h6>Sample Data (First 3 rows):</h6>';
                    html += '<pre style="background: #f8f9fa; padding: 10px; border-radius: 3px; font-size: 12px;">' + JSON.stringify(data.sample_data, null, 2) + '</pre>';
                }
                
                content.innerHTML = html;
                output.style.display = 'block';
            })
            .catch(error => {
                console.error('Error analyzing file:', error);
                document.getElementById('analysis-content').innerHTML = '<div class="alert alert-danger">Error analyzing file: ' + error.message + '</div>';
                document.getElementById('analysis-output').style.display = 'block';
            });
        }
    </script>
@endpush
