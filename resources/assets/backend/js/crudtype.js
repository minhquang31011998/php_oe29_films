$('#delete_type').on('click', function (e) {
    e.preventDefault();
    var form = event.target.form;
    swal({
        title: "Are you sure to delete this type?",
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
