$(document).ready(function () {
    $('#types').select2({
        placeholder: "Choose type / types (*)"
    });

    $('#tags').ready( function () {
        var tags = $('#tags').val();
        $.ajax({
            type: 'get',
            url: '/admin/movie/tags',
            data: {
                tags: tags
            },
            success: function (response) {
                var t = [];
                var i;
                for (i = 0; i < response.length; i++) {
                    t.push(response[i].name);
                }
                $('#tags').tagsInput({
                    'autocomplete': {
                        source: t
                    }
                });
            }
        })
    });

    $('#channels').select2();

    function cssPlaylist() {
        $('#playlists-table_length').addClass('main__table-text');
        $('#playlists-table_paginate').addClass('paginator');
        $('#playlists-table_length label select').select2();
    }

    function cssChoosePlaylist() {
        $('#no-movie-playlists-table_length').addClass('main__table-text');
        $('#no-movie-playlists-table_paginate').addClass('paginator');
        $('#no-movie-playlists-table_length label select').select2();
    }

    function cssChooseVideo() {
        $('#videos-table_length').addClass('main__table-text');
        $('#videos-table_paginate').addClass('paginator');
        $('#videos-table_length label select').select2();
    }

    function dataTablePlaylist(title = '') {
        var myTable = $('#playlists-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url": "/admin/movie/moviePlaylists/" + $('#movieId').val(),
                "data": {
                    "title": title,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
                { data: 'order', name: 'order', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        cssPlaylist();
    }

    function dataTableChoosePlaylist(title = '') {
        var myTable = $('#no-movie-playlists-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy:true,
            ajax: {
                "url": "/admin/movie/choosePlaylists/" + $('#movieId').val(),
                "data": {
                    "title": title,
                },
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false },
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
                { data: 'order', name: 'order', orderable: false, searchable: false },
            ]
        });
        cssChoosePlaylist();
    }

    function dataTableChooseMovie(title = '') {
        var myTable = $('#videos-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url": "/admin/movie/chooseVideos/" + $('#movieId').val(),
                "data": {
                    "title": title,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false, searchable: false, class: 'td-with' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        cssChooseVideo();
    }

    dataTablePlaylist();
    dataTableChoosePlaylist();
    dataTableChooseMovie();

    window.choosePlaylist = function (playlistId) {
        $.ajax({
            type: "POST",
            url: "/admin/movie/updatePlaylist/" + $('#movieId').val(),
            "data" : {
                "playlistId" : playlistId,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                dataTablePlaylist();
                dataTableChoosePlaylist();
                swal({
                    title: "Updated",
                    text: "Successfully",
                    icon: "success",
                });
            },
        });
    }

    window.chooseVideo = function (videoId) {
        $.ajax({
            type: "POST",
            url: "/admin/movie/updateVideo/" + $('#movieId').val(),
            "data" : {
                "videoId" : videoId,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                swal({
                    title: "Updated",
                    text: "Successfully",
                    icon: "success",
                });
                location.reload();
            },
        });
    }

    window.detachPlaylist = function (playlistId) {
        $.ajax({
            type: "POST",
            url: '/admin/playlist/detach/' + playlistId,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                dataTablePlaylist();
                dataTableChoosePlaylist();
                swal({
                    title: "Detach",
                    text: "Successfully",
                    icon: "success",
                });
            },
        });
    }

    $("#btn-create-video").click(function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('title', $("#frmCreateVideo input[name='title']").val());
        formData.append('channel_id', $('#frmCreateVideo .channels').val());
        formData.append('source_key', $("#frmCreateVideo input[name='source_key']").val());
        formData.append('description', $("#frmCreateVideo textarea[name='description']").val());
        formData.append('tags', $("#frmCreateVideo input[name='tags']").val());
        formData.append('movie_id', $("#frmCreateVideo input[name='movie_id']").val());

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
            success: function(data) {
                dataTablePlaylist();
                $("#frmCreateVideo .modal__btn--dismiss").click();
                location.reload();
                window.swal("Create video completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmCreateVideo .title").html('');
                $("#frmCreateVideo .description").html('');
                $("#frmCreateVideo .video").html('');
                $.each(errors.errors, function(key, value){
                    $("#frmCreateVideo ." + key).html(value);
                });
            }
        });
    });

    $("#btn-create-playlist").click(function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('title', $("#frmCreatePlaylist input[name='title']").val());
        formData.append('description', $("#frmCreatePlaylist textarea[name='description']").val());
        formData.append('order', $("#frmCreatePlaylist input[name='order']").val());
        formData.append('movie_id', $("#frmCreatePlaylist input[name='movie_id']").val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreatePlaylist meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'POST',
            url: '/admin/playlist/store',
            data:formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function(data) {
                dataTablePlaylist();
                $("#frmCreatePlaylist .modal__btn--dismiss").click();
                $("#frmCreatePlaylist input[name='title']").val('');
                $("#frmCreatePlaylist textarea[name='description']").val('');
                $("#frmCreatePlaylist input[name='order']").val('');
                $("#frmCreatePlaylist .title").html('');
                $("#frmCreatePlaylist .description").html('');
                $("#frmCreatePlaylist .order").html('');
                swal("Create playlist completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmCreatePlaylist .title").html('');
                $("#frmCreatePlaylist .description").html('');
                $("#frmCreatePlaylist .order").html('');
                $.each(errors.errors, function(key, value){
                    $("#frmCreatePlaylist ." + key).html(value);
                });
            }
        });
    });

    $('#playlists-table').on('click', '.edit-playlist', function (e) {
        e.preventDefault();
        var playlistId = $(this).attr('data-playlist');
        $.ajax({
            type: 'GET',
            url: '/admin/playlist/show/' + playlistId,
            success: function(data) {
                $("#frmEditPlaylist input[name=playlist_id]").val(data.playlist.id);
                $("#frmEditPlaylist input[name=title]").val(data.playlist.title);
                $("#frmEditPlaylist textarea[name=description]").val(data.playlist.description);
                $("#frmEditPlaylist input[name=order]").val(data.playlist.order);
                $("#frmEditPlaylist .title").html('');
                $("#frmEditPlaylist .description").html('');
                $("#frmEditPlaylist .order").html('');
                $("#edit-playlist").click();
            },
        });
    })

    $("#btn-update-playlist").click(function (e) {
        e.preventDefault();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#frmCreatePlaylist meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'PUT',
            url: '/admin/playlist/update/' + $("#frmEditPlaylist input[name=playlist_id]").val(),
            data: {
                'title': $("#frmEditPlaylist input[name='title']").val(),
                'description': $("#frmEditPlaylist textarea[name='description']").val(),
                'order': $("#frmEditPlaylist input[name='order']").val(),
                'movie_id': $("#frmEditPlaylist input[name='movie_id']").val(),
                '_method' : 'PUT',
            },
            dataType: 'json',
            success: function(data) {
                dataTablePlaylist();
                $("#frmEditPlaylist .modal__btn--dismiss").click();
                $("#frmEditPlaylist input[name='title']").val('');
                $("#frmEditPlaylist textarea[name='description']").val('');
                $("#frmEditPlaylist input[name='order']").val('');
                swal("Update playlist completed", " ", "success");
            },
            error: function(data) {
                var errors = $.parseJSON(data.responseText);
                $("#frmEditPlaylist .title").html('');
                $("#frmEditPlaylist .description").html('');
                $("#frmEditPlaylist .order").html('');
                $.each(errors.errors, function(key, value){
                    $("#frmEditPlaylist ." + key).html(value);
                });
            }
        });
    });

    window.deletePlaylist = function (playlistId) {
        swal({
          title: "Are you sure to delete this playlist?",
          text: "This action can not be undone",
          icon: "warning",
          buttons: true,
          dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "POST",
                    url: '/admin/playlist/delete/' + playlistId,
                    data : {
                        '_method' : 'DELETE'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        dataTablePlaylist();
                        swal({
                            title : "Delete playlist completed",
                            icon : "success",
                            button : "Done",
                        });
                    },
                });
            }
        });
    }

    $('#delete_movie').on('click', function (e) {
        e.preventDefault();
        var form = event.target.form;
        swal({
            title: "Are you sure to delete this user?",
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
})
