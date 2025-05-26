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

$(document).ready(function () {
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

    $('tbody tr').on('click', function (e) {
        if (!$(e.target).is('a') && !$(e.target).is('button')) {
            $(this).find('a[data-toggle="modal"]').click();
        }
    });

    $('.results').DataTable({
        "language": {
            "lengthMenu": "_MENU_",
            "zeroRecords": "¯\\_(ツ)_/¯",
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

// ========== DASHBOARD SCRIPTS ========== //
// Actualiza el BPM del metrónomo y resalta el botón seleccionado
window.updateBpm = function (bpm) {
    // Quita la clase 'active' de todos los botones
    document.querySelectorAll('.btn-group .btn').forEach(button => {
        button.classList.remove('active');
    });
    // Agrega la clase 'active' al botón seleccionado
    document.querySelector(`[data-bpm="${bpm}"]`).classList.add('active');
    // Envía la actualización al backend
    fetch(window.updateBpmUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({ bpm })
    })
        .then(response => {
            if (!response.ok) throw new Error('Error al actualizar el BPM');
            return response.json();
        })
        .then(data => {
            console.log('BPM actualizado:', data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al actualizar el BPM.');
        });
};

// Habilita o deshabilita un escenario y actualiza el botón visualmente
window.toggleScenario = function (scenarioId) {
    // Selecciona el botón correspondiente al escenario
    const button = document.querySelector(`.scenario-btn:nth-child(${scenarioId})`);
    const isEnabled = button.classList.contains('btn-primary');
    // Cambia la clase del botón según el estado
    button.classList.toggle('btn-primary', !isEnabled);
    button.classList.toggle('btn-outline-primary', isEnabled);
    // Envía la actualización al backend
    fetch(window.toggleScenarioUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: JSON.stringify({
            scenario_id: scenarioId,
            is_enabled: !isEnabled
        })
    })
        .then(response => {
            if (!response.ok) throw new Error('Error al guardar los cambios');
            return response.json();
        })
        .then(data => {
            console.log('Estado actualizado:', data);
        })
        .catch(error => {
            console.error('Error:', error);
            // Revierte el cambio visual si hay error
            button.classList.toggle('btn-primary', isEnabled);
            button.classList.toggle('btn-outline-primary', !isEnabled);
        });
};

$(function () {
    // Inicializa tooltips de Bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    // ========== Lógica para el control de duración de la RCP ==========
    const rcpDurationRange = document.getElementById('rcpDurationRange');
    const rcpDurationLabel = document.getElementById('rcpDurationLabel');
    const rcpResetBtn = document.getElementById('rcpResetBtn');
    // Muestra u oculta el botón de reset según el valor
    function updateResetBtnDisplay(val) {
        if (rcpResetBtn) {
            rcpResetBtn.style.display = (parseFloat(val) !== 2.0) ? 'inline' : 'none';
        }
    }
    if (rcpDurationRange && rcpDurationLabel) {
        // Actualiza el label y el botón de reset al mover el slider
        updateResetBtnDisplay(rcpDurationRange.value);
        rcpDurationRange.addEventListener('input', function () {
            rcpDurationLabel.textContent = this.value.replace('.', ',');
            updateResetBtnDisplay(this.value);
        });
        // Envía el nuevo valor al backend al soltar el slider
        rcpDurationRange.addEventListener('change', function () {
            const minutes = parseFloat(this.value);
            const seconds = Math.round(minutes * 60);
            fetch(window.updateWaitingTimeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: JSON.stringify({ instruction_id: 4, waiting_time: seconds })
            })
                .then(response => {
                    if (!response.ok) throw new Error('Error al actualizar la duración de la RCP');
                    return response.json();
                })
                .then(data => {
                    console.log('Duración de RCP actualizada:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al actualizar la duración de la RCP.');
                });
        });
    }
    // Restablece el valor por defecto al hacer click en el botón de reset
    if (rcpResetBtn && rcpDurationRange && rcpDurationLabel) {
        rcpResetBtn.addEventListener('click', function () {
            rcpDurationRange.value = 2.0;
            rcpDurationLabel.textContent = '2,0';
            updateResetBtnDisplay(2.0);
            const event = new Event('change');
            rcpDurationRange.dispatchEvent(event);
        });
    }

    // ========== Gráficos con Chart.js ==========
    // Gráfico de barras horizontales para los usuarios con más escenarios completados
    if (window.topUsersLabels && window.topUsersData && window.topUsersScenarios) {
        const topUsersCtx = document.getElementById('topUsersChart')?.getContext('2d');
        if (topUsersCtx) {
            new Chart(topUsersCtx, {
                type: 'bar',
                data: {
                    labels: window.topUsersLabels,
                    datasets: [{
                        label: 'Completados',
                        data: window.topUsersData,
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Barras horizontales
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                // Muestra el detalle de escenarios completados por usuario
                                label: function (context) {
                                    const userIndex = context.dataIndex;
                                    const scenarios = window.topUsersScenarios[userIndex];
                                    const total = context.raw;
                                    const scenarioTexts = scenarios.map(scenario => `${scenario.name} (${scenario.count})`);
                                    return [...scenarioTexts, `Total: ${total}`];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    }
                }
            });
        }
    }
    // Gráfico de torta (pie) para los escenarios más completados
    if (window.scenarioStatsLabels && window.scenarioStatsData) {
        const scenarioStatsCtx = document.getElementById('scenarioStatsChart')?.getContext('2d');
        if (scenarioStatsCtx) {
            new Chart(scenarioStatsCtx, {
                type: 'pie',
                data: {
                    labels: window.scenarioStatsLabels,
                    datasets: [{
                        data: window.scenarioStatsData,
                        backgroundColor: [
                            'rgba(60,141,188,0.9)',   // Azul
                            'rgba(40,167,69,0.9)',    // Verde
                            'rgba(255,193,7,0.9)',    // Amarillo
                            'rgba(220,53,69,0.9)',    // Rojo
                            'rgba(23,162,184,0.9)',   // Cian
                            'rgba(111,66,193,0.9)',   // Violeta
                            'rgba(255,87,34,0.9)',    // Naranja
                            'rgba(108,117,125,0.9)'   // Gris
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        }
    }
});

// ========== TinyMCE LOGIC ========== //
// Desactiva enforceFocus de Bootstrap para todos los modales (solución universal para TinyMCE en modales)
if ($.fn.modal && $.fn.modal.Constructor && $.fn.modal.Constructor.prototype) {
    $.fn.modal.Constructor.prototype._enforceFocus = function() {};
}

$(document).ready(function() {
    $('.modal').on('shown.bs.modal', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.wysiwyg-editor',
                height: 300,
                zindex: 20000,
                plugins: 'autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste wordcount emoticons advlist',
                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table charmap emoticons | code fullscreen preview',
                menubar: false,
                convert_urls: false,
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
        }
    });

    $('form').submit(function() {
        if (tinymce.activeEditor) {
            tinymce.activeEditor.save();
        }
        return true;
    });

    $('.modal').on('hidden.bs.modal', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.remove('.wysiwyg-editor');
        }
    });
});
