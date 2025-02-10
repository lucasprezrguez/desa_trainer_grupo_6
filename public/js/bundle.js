function deleteItem(itemId, itemName) {
    const SwalBS = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-light btn-lg text-danger',
            cancelButton: 'btn btn-light btn-lg'
        },
        buttonsStyling: false
    });

    SwalBS.fire({
        title: `¿Eliminar ${itemName}?`,
        text: "Esta acción es irreversible.",
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + itemId).submit();
        }
    });
}

$(document).ready(function() {
    if (window.session) {
        if (window.session.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                text: window.session.success,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        if (window.session.error) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                text: window.session.error,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }
    }

    $('tbody tr').on('click', function(e) {
        if (!$(e.target).is('a') && !$(e.target).is('button')) {
            $(this).find('a[data-toggle="modal"]').click();
        }
    });

    $('.results').DataTable({
        "language": {
            "lengthMenu": "_MENU_",
            "zeroRecords": "No hay resultados.",
            "search": "Buscar:",
            "paginate": {
                "previous": "<i class='ri-arrow-left-s-line text-lg'></i>",
                "next": "<i class='ri-arrow-right-s-line text-lg'></i>"
            }
        },
        "pagingType": "simple",
        "layout": {
            "topStart": {
                "search": {
                    "placeholder": "Buscar...",
                }
            },
            "topEnd": 'paging',
            "bottomStart": null,
            "bottomEnd": null,
        },
        "ordering": true,
        "columnDefs": [
            { "orderable": false, "targets": "_all" },
            { "orderable": true, "targets": 0 }
        ],
        "order": [[0, "asc"]],
        "paging": true,
        "autoWidth": true,
        "responsive": true,
    });
});
