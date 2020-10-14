$(document).ready(function () {
    function css() {
        $('#types-table_length').addClass('main__table-text');
        $('#types-table_paginate').addClass('paginator');
        $('#types-table_length label select').select2();
    }

    function dataTable(title = '', sort = '') {
        var myTable = $('#types-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url" : '/admin/type/data',
                "data": {
                    "title": title,
                    "sort": sort,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        css();
    }
    dataTable();

    $('.filter__item-menu li').on('click', function (e) {
        e.preventDefault();
        var sort = $('.filter__item-btn input').val();
        var title = $('#filter').val();
        $.ajax({
            url: '/admin/type/data',
            data: {
                sort : sort,
                title : title,
            },
            success: function (response) {
                dataTable(title, sort);
            },
        })
    })

    function search(e) {
        var sort = $('.filter__item-btn input').val();
        var title = $('#filter').val();
        $.ajax({
            url: '/admin/type/data',
            data: {
                sort : sort,
                title : title,
            },
            success: function (response) {
                dataTable(title, sort);
            },
        })
    }

    $('#filter').on('change',function (e) {
        e.preventDefault();
        window.setTimeout(search, 200);
    })
})
