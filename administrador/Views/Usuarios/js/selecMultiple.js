$(document).ready(function() {
    let select = $('#multiple-select');
    let selectedTagsContainer = $('#selected-tags');
    let hiddenInput = $('#selectedValues');

    // Inicializar Select2 con AJAX para cargar opciones desde PHP
    /*select.select2({
        placeholder: "Selecciona opciones",
        allowClear: true,
        tags: true,
        ajax: {
            url: 'get_options.php',
            dataType: 'json',
            processResults: function (data) {
                return { results: data };
            }
        }
    });*/

    // Evento cuando el usuario selecciona un valor
    select.on('change', function() {
        updateTags();
    });

    // Función para actualizar etiquetas y el campo oculto
    function updateTags() {
        selectedTagsContainer.empty();
        let selectedValues = [];

        select.find(':selected').each(function() {
            let value = $(this).val();
            let text = $(this).text();

            selectedValues.push(value);

            let tag = $('<span class="tag"></span>').text(text);
            let removeBtn = $('<span class="remove">×</span>').click(function() {
                removeTag(value);
            });

            tag.append(removeBtn);
            selectedTagsContainer.append(tag);
        });

        hiddenInput.val(selectedValues.join(","));
    }

    // Función para eliminar un tag seleccionado
    function removeTag(value) {
        select.find(`option[value="${value}"]`).prop('selected', false);
        select.trigger('change');
    }
});

function guardarSeleccion() {
    let valores = $('#selectedValues').val();
    alert("Valores seleccionados: " + valores);
}