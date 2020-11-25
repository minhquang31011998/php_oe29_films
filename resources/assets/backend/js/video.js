$(document).ready(function () {
    function css() {
        $('#videos-table_length').addClass('main__table-text');
        $('#videos-table_paginate').addClass('paginator');
        $('#videos-table_length label select').select2();
    }
    function dataTable(title = '', sort = '') {
        var myTable = $('#videos-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            destroy: true,
            ajax: {
                "url" : '/admin/video/data',
                "data": {
                    "title": title,
                    "sort": sort,
                },
            },
            columns: [
                { data: 'id', name: 'id', orderable: false, searchable: false },
                { data: 'title', name: 'title', orderable: false, searchable: false , class: 'td-with' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
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

        $('body').append('<p>' + sort + '</p>');
        $.ajax({
            url: '/admin/video/data',
            data: {
                sort: sort,
                title: title,
            },
            success: function (response) {
                dataTable(title, sort);
            },
            error: function (error) {
                alert('The system is maintenance');
            }
        })
    })
    function search (e) {
        var sort = $('.filter__item-btn input').val();
        var title = $('#filter').val();
        $.ajax({
            url: '/admin/video/data',
            data: {
                sort: sort,
                title: title,
            },
            success: function (response) {
                dataTable(title, sort);
            },
            error: function (error) {
                alert('The system is maintenance');
            }
        })
    }

    $('#filter').on('change',function (e) {
        e.preventDefault();
        window.setTimeout(search, 200);
    })
})
