function getDatatable(id, route, columns, data = []) 
{
    var table = $(id).DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json'
        },
        dom: "ltip",
        processing: true,
        serverSide: true,
        pageLength: 25,
        lengthMenu: [25,50,100],
        ajax: {
            url: route,
            type: 'POST',
            dataType: 'json',
            data: data
        },
        columns: columns
    });

    return table;
}