document.addEventListener("DOMContentLoaded", function () {
    let select = document.getElementById("multiple-select");
    let selectedTagsContainer = document.getElementById("selected-tags");
    let hiddenInput = document.getElementById("selectedValues");

    // Cargar opciones desde el backend
    /*fetch("get_options.php")
        .then(response => response.json())
        .then(data => {
            data.forEach(option => {
                let opt = document.createElement("option");
                opt.value = option.id;
                opt.textContent = option.name;
                select.appendChild(opt);
            });
        });*/

    // Manejar cambios en la selección
    select.addEventListener("change", function () {
        updateTags();
    });

    function updateTags() {
        selectedTagsContainer.innerHTML = "";
        let selectedValues = [];
    
        for (let option of select.selectedOptions) {
            selectedValues.push(option.value);
    
            let tag = document.createElement("span");
            tag.className = "tag";
            tag.innerHTML = option.text + ' <span class="remove" onclick="removeTag(\'' + option.value + '\')">×</span><br>';
            selectedTagsContainer.appendChild(tag);
        }
    
        hiddenInput.value = selectedValues.join(",");
    }


    
});



function removeTag(value) {
    let select = document.getElementById("multiple-select");
    for (let option of select.options) {
        if (option.value === value) {
            option.selected = false;
            break;
        }
    }
    //updateTags();
}


function guardarSeleccion() {
    let valores = document.getElementById("selectedValues").value;
    alert("Valores seleccionados: " + valores);
}