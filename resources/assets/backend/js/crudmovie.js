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
