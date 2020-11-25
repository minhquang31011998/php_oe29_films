$(document).ready(function () {
    function css() {
        $('#sources-table_length').addClass('main__table-text');
        $('#sources-table_paginate').addClass('paginator');
        $('#sources-table_length label select').select2();
    }

    function dataTable(source_key = '') {
        var myTable = $('#sources-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url" : '/admin/video/source',
                "data" : {
                    "videoId" : $('#videoId').val(),
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'source_key', name: 'source_key', orderable: false, searchable: false, class: 'td-with' },
                { data: 'prioritize', name: 'prioritize', orderable: false, searchable: false },
                { data: 'channel_id', name: 'channel_id', orderable: false, searchable: false, class: 'td-with' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        css();
    }

    dataTable();

    $('#sources-table').on('click', '.edit-source', function (e) {
        e.preventDefault();
        var sourceId = $(this).attr('data-source');
        $.ajax({
            type: 'GET',
            url: '/admin/source/' + sourceId + '/edit/',
            success: function (data) {
                $("#frmEditSource input[name=source_key]").val(data.source.source_key);
                $("#frmEditSource input[name=source_id]").val(data.source.id);
                $("#frmEditSource input[name=prioritize]").val(data.source.prioritize);
                $("#frmEditSource .channels").val(data.source.channel_id);
                $("#frmEditSource .channels").select2();
                $("#edit-source").click();
            },
            error: function (data) {
                alert('The system is maintenance');
            }
        });
    });
    $("#btn-create").click(function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('prioritize', $("#frmCreateSource input[name='prioritize']").val());
        formData.append('channel_id', $('#frmCreateSource .channels').val());
        formData.append('source_key', $("#frmCreateSource input[name='source_key']").val());
        formData.append('source_id', $("#frmCreateSource input[name='source_id']").val());
        formData.append('video_id', $("#frmCreateSource input[name='video_id']").val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreateSource meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/admin/source/store',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (data) {
                $("#frmCreateSource input[name='prioritize']").val('');
                $("#frmCreateSource input[name='source_key']").val('');
                $("#frmCreateSource .prioritize").html('');
                $("#frmCreateSource .source_key").html('');
                dataTable();
                $("#frmCreateSource .modal__btn--dismiss").click();
                swal("Create source completed", " ", "success");
            },
            error: function (data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmCreateSource .prioritize").html('');
                $("#frmCreateSource .source_key").html('');
                $.each(errors.errors, function (key, value) {
                    $("#frmCreateSource ."+key).html(value);
                });
            }
        });
    });

    $("#btn-edit").click(function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('prioritize', $("#frmEditSource input[name='prioritize']").val());
        formData.append('channel_id', $('#frmEditSource .channels').val());
        formData.append('source_key', $("#frmEditSource input[name='source_key']").val());
        formData.append('source_id', $("#frmEditSource input[name='source_id']").val());
        formData.append('video_id', $("#frmCreateSource input[name='video_id']").val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/admin/source/update/' + $("#frmEditSource input[name=source_id]").val(),
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (data) {
                $("#frmEditSource .prioritize").html('');
                $("#frmEditSource .source_key").html('');
                dataTable();
                $("#frmEditSource .modal__btn--dismiss").click();
                swal("Update source completed", " ", "success");
            },
            error: function (data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmEditSource .prioritize").html('');
                $("#frmEditSource .source_key").html('');
                $.each(errors.errors, function (key, value) {
                    $("#frmEditSource ."+key).html(value);
                });
            }
        });
    });

    window.deleteSource = function (sourceId) {
        swal({
          title: "Are you sure to delete this source?",
          text: "This action can not be undone",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('#frmCreateSource meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '/admin/source/delete/'+sourceId,
                    dataType: 'json',
                    success: function(response) {
                        dataTable();
                        swal({
                            title: "Deleted",
                            text: "Successfully",
                            icon: "success",
                        });
                    },
                    error: function(data) {
                        dataTable();
                    }
                });
            }
        });
    }
});
