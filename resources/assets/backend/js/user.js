$(document).ready(function () {
    function css() {
        $('#admins-table_length').addClass('main__table-text');
        $('#admins-table_paginate').addClass('paginator');
        $('#admins-table_length label select').select2();
    }

    function dataTable(name = '', sort = '') {
        var myTable = $('#admins-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url": '/admin/user/data',
                "data": {
                    "name": name,
                    "sort": sort,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'name', name: 'name', orderable: false, searchable: false },
                { data: 'email', name: 'email', orderable: false, searchable: false },
                { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
        css();
    }

    dataTable();

    $('.filter__item-menu li').on('click', function (e) {
        e.preventDefault();
        var sort = $('.filter__item-btn input').val();
        var name = $('#filter').val();
        $.ajax({
            url: '/admin/user/data',
            data: {
                sort: sort,
                name: name,
            },
            success: function (response) {
                dataTable(name, sort);
            },
        })
    })

    function search(e) {
        var sort = $('.filter__item-btn input').val();
        var name = $('#filter').val();
        $.ajax({
            url: '/admin/user/data',
            data: {
                sort: sort,
                name: name,
            },
            success: function (response) {
                dataTable(name, sort);
            },
        })
    }

    $('#filter').on('change',function (e) {
        e.preventDefault();
        window.setTimeout(search, 200);
    })
})
