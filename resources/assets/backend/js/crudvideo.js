$(document).ready(function () {
    $('.channels').select2();
    $('#channels').select2();
    $('#status').on('click', function () {
        $.ajax({
            type: "POST",
            url: '/admin/video/changeStatus/' + $('#videoId').val(),
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

    $('#delete_video').on('click', function (e) {
        e.preventDefault();
        var form = event.target.form;
        swal({
            title: "Are you sure to delete this video?",
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
