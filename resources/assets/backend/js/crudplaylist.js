$(document).ready(function () {
    $('#channels').select2({
        placeholder: "Choose roles"
    });
    function cssVideo() {
        $('#videos-table_length').addClass('main__table-text');
        $('#videos-table_paginate').addClass('paginator');
        $('#videos-table_length label select').select2();
    }
    function cssOptionVideo() {
        $('#no-playlist-videos-table_length').addClass('main__table-text');
        $('#no-playlist-videos-table_paginate').addClass('paginator');
        $('#no-playlist-videos-table_length label select').select2();
    }
    function dataTableVideo() {
        var myTable = $('#videos-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url": '/admin/playlist/playlistVideos/' + $('#playlistId').val(),
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
                { data: 'chap', name: 'chap', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        cssVideo();
    }
    function dataTableOptionVideo() {
        var myTable = $('#no-playlist-videos-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url": '/admin/playlist/videos/' + $('#playlistId').val(),
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
            ]
        });
        cssOptionVideo();
    }
    dataTableVideo();
    dataTableOptionVideo();
    $('#status').on('click', function () {
        $.ajax({
            type: "POST",
            url: '/admin/playlist/changeStatus/' + $('#playlistId').val(),
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.status == 1) {
                    $(this).html('<i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>');
                } else {
                    $(this).html('<i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>');
                }
                swal({
                    title: "Changed",
                    text: "Successfully",
                    icon: "success",
                });
            }
        });
    })
    $("#btn-create-video").click(function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('title', $("#frmCreateVideo input[name='title']").val());
        formData.append('channel_id', $('#frmCreateVideo .channels').val());
        formData.append('source_key', $("#frmCreateVideo input[name='source_key']").val());
        formData.append('chap', $("#frmCreateVideo input[name='chap']").val());
        formData.append('description', $("#frmCreateVideo textarea[name='description']").val());
        formData.append('tags', $("#frmCreateVideo input[name='tags']").val());
        formData.append('playlist_id', $("#frmCreateVideo input[name='playlist_id']").val());
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreateVideo meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/admin/video/store',
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (data) {
                $("#frmCreateVideo input[name='title']").val('');
                $("#frmCreateVideo input[name='source_key']").val('');
                $("#frmCreateVideo input[name='chap']").val('');
                $("#frmCreateVideo textarea[name='description']").val('');
                $("#frmCreateVideo input[name='tags']").val('');
                $("#video1").html('');
                $("#frmCreateVideo .modal__btn--dismiss").click();
                dataTableVideo();
                swal("Create video completed", " ", "success");
            },
            error: function (data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmCreateVideo .title").html('');
                $("#frmCreateVideo .description").html('');
                $("#frmCreateVideo .chap").html('');
                $.each(errors.errors, function(key, value){
                    $("#frmCreateVideo ."+key).html(value);
                });
            }
        });
    });
    window.chooseVideo = function(videoId) {
        $.ajax({
            type: "POST",
            url: '/admin/playlist/chooseVideo/' + $('#playlistId').val(),
            "data" : {
                "videoId" : videoId,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                dataTableVideo();
                dataTableOptionVideo();
                swal("Choose video completed", " ", "success");
            },
        });
    }

    window.detachVideo = function (videoId) {
        swal({
            title: "Are you sure to remove this video?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((remove) => {
            if (remove) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: '/admin/video/detach/'+videoId,
                    success: function () {
                        dataTableVideo();
                        dataTableOptionVideo();
                        swal({
                            title: "Detach",
                            text: "Successfully",
                            icon: "success",
                        });
                    },
                });
            }
        });
    }
    $('#delete_playlist').on('click', function (e) {
        e.preventDefault();
        var form = event.target.form;
        swal({
            title: "Are you sure to delete this playlist?",
            text: "This action can not be undone",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
        })
    })
});
