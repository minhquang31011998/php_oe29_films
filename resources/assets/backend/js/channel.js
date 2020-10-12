$(document).ready(function () {
    $(function () {
        function css() {
            $('#channels-table_length').addClass('main__table-text');
            $('#channels-table_paginate').addClass('paginator');
            $('#channels-table_length label select').select2();
        }
        function dataTable(title = '', sort = '') {
            var myTable = $('#channels-table').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                destroy: true,
                ajax: {
                    "url": '/admin/channel/data',
                    "data": {
                        "title": title,
                        "sort": sort,
                    },
                },
                columns: [
                    { data: 'id', name: 'id', orderable: false, searchable: false },
                    { data: 'title', name: 'title', orderable: false, searchable: false , class: 'td-with'},
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
            css();
        }

        dataTable();

        $('.filter__item-menu li').on('click', function (e) {
            e.preventDefault();
            var sort = $('#sort').val();
            var title = $('#filter').val();
            $.ajax({
                url: '/admin/channel/data',
                data: {
                    sort: sort,
                    title: title,
                    status: status,
                },
                success: function (response) {
                    dataTable(title, sort);
                },
            })
        })

        function search (e) {
            var sort = $('#sort').val();
            var title = $('#filter').val();
            $.ajax({
                url: '/admin/channel/data',
                data: {
                    sort: sort,
                    title: title,
                    status: status,
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
})
