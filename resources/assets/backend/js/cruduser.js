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
        },
    });
})
