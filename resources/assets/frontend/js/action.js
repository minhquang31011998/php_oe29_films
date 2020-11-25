$(document).ready(function () {
    $('#comment_form').on('submit', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#comment_form meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/comment/store",
            type: 'POST',
            data: formData,
            success: function (data) {
                $('#comment_form')[0].reset();
                $('#comment_id').val('');
                load_comment();
            }
        })
    })

    load_comment();
    function load_comment() {
        var movie_id = $('#movie_id').val();
        $.ajax({
            url: "/comment/data",
            type: 'get',
            data: {
                movie_id: movie_id
            },
            success: function (data) {
                $('#display_comment').html(data);
            }
        })
    }

    $(document).on('click', '.reply', function () {
        var comment_id = $(this).attr("id");
        $('#comment_id').val(comment_id);
        $('.comment_content').focus();
    })

    $(document).on('click', '.delete', function () {
        var comment_id = $(this).attr("id");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#comment_form meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/comment/delete/" + comment_id,
            type: 'delete',
            success: function (data) {
                if (data) {
                    swal({
                        title: "Deleted",
                        text: "Successfully",
                        icon: "success",
                    });
                }
                else {
                    swal({
                        title: "Error",
                        text: "Only admin is allowed to delete",
                        icon: "error",
                    });
                }
                load_comment();
            },
        })
    })

    $('#rate_form').on('submit', function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('#comment_form meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/rate",
            type: 'POST',
            data: formData,
            success: function (data) {
                swal({
                    title: "Rate",
                    text: "Successfully",
                    icon: "success",
                });
            }
        })
    })

    var pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
    encrypted: true,
    cluster: "ap1"
    });
    var channel = pusher.subscribe('Notification');
    channel.bind('send-message', function(data) {
        var id = $('#idUser').val();
        if (id == data.idUser) {
        var newNotificationHtml = `
            <li>
                <a style="color:#167ac6"><b>${data.nameUser}</b> reply to comment in movie: <b>${data.nameMovie}</b></a>
            </li>
            `;
            if ($('#numberNoti').html() > 0) {
                var i = $('#numberNoti').html();
                var number = new Number(i) + 1;
                var span = '<i class="fa fa-bell"></i><span class="badge badge-warning navbar-badge" id="numberNoti">' + number +'</span>';
                $('.notification').html(span);
            } else {
                var span = '<i class="fa fa-bell"></i><span class="badge badge-warning navbar-badge" id="numberNoti">1</span>';
                $('.notification').html(span);
            }
        $('#mCSB_1_container').prepend(newNotificationHtml);
        }
    });

    $('.notification').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: "/comment/mark-noti",
            type: 'get',
            success: function () {
                $(".badge-warning").remove();
            }
        })
    })
})
