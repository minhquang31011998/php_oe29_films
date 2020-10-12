$('#channel_type').select2();

$('#status').on('click', function () {
    $.ajax({
        type: "POST",
        url: '/admin/channel/changeStatus/' + $('#channelId').val(),
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
        }
    });
})
