$('#status').on('click', function () {
    $.ajax({
        type: "POST",
        url: '/admin/user/changeStatus/' + $('#userId').val(),
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: (response) => {
            if (response.is_active == $('#active').val()) {
                $(this).html('<i class="fa fa-unlock-alt" aria-hidden="true" data-toggle="tooltip" title="Activate"></i>');
            } else {
                $(this).html('<i class="icon ion-ios-lock" data-toggle="tooltip" title="Hide"></i>');
            }
            swal({
                title: "Changed",
                text: "Successfully",
                icon: "success",
            });
        },
    });
})

$('#delete_user').on('click', function (e) {
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
