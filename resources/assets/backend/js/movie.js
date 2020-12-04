$(document).ready(function(){
    function css() {
        $('#movies-table_length').addClass('main__table-text');
        $('#movies-table_paginate').addClass('paginator');
        $('#movies-table_length label select').select2();
    };

    function dataTable(name = '', slug = '', sort = '') {
        $('#movies-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url": '/admin/movie/data',
                "data": {
                    "name": name,
                    "slug": slug,
                    "sort": sort,
                },
            },
            columns: [
                {data: 'id', name: 'id', orderable: false, searchable: false},
                {data: 'name', name: 'name', orderable: false, searchable: false, class: 'td-with'},
                {data: 'slug', name: 'slug', orderable: false, searchable: false, class: 'td-with'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
            });
        css();
    };

    dataTable();

    $('.filter__item-menu li').on('click', function (e) {
        e.preventDefault();
        var sort = $('.filter__item-btn input').val();
        var name = $('#filter').val();;
        var slug = '';
        $.ajax({
            "url": '/admin/movie/data',
            data: {
                sort: sort,
                name: name,
                slug: slug,
            },
            success: function (response) {
                dataTable(name, slug, sort);
            },
            error: function (error) {
                alert('The system is maintenance');
            }
        })
    })

    function search(e) {
        var sort = $('#sort').val();
        var name = $('#filter').val();
        var slug = '';
        $.ajax({
            url: '/admin/movie/data',
            data: {
                sort: sort,
                name: name,
                slug: slug,
            },
            success: function (response) {
                dataTable(name, slug, sort);
            },
            error: function (error) {
                alert('The system is maintenance');
            }
        })
        $('#movies-table').dataTable
    };

    $('#filter').on('change', function (e) {
        e.preventDefault();
        window.setTimeout(search, 200);
    });

    $('#movies-table').on('click', '.btn-nominations', function (e) {
        e.preventDefault();
        let status = $(this).attr('data_status');
        let id = $(this).attr('movie_id');
        $.ajax({
            type: 'get',
            url: '/admin/movie/' + id + '/nominations',
            success: (result) => {
                if (result.nominations == 1) {
                    $(this).attr('title', 'Turn off nomination');
                    $(this).removeClass('main__table-btn--delete');
                    $(this).addClass('main__table-btn--edit');
                    $(this).remove('icon');
                    $(this).html('<icon class="icon ion-ios-radio-button-on">');
                } else {
                    $(this).attr('title', 'Turn on nomination');
                    $(this).addClass('main__table-btn--delete');
                    $(this).removeClass('main__table-btn--edit');
                    $(this).remove('icon');
                    $(this).html('<icon class="icon ion-ios-radio-button-off">');
                }
                swal({
                    title: "Changed",
                    text: "Successfully",
                    icon: "success",
                });
            }
        });
    });
});
