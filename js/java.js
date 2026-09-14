$(document).ready(function () {
    $(".veen .rgstr-btn button").click(function () {
        $('.veen .wrapper').addClass('move');
        $('.body').css('background', '#e0b722');
        $(".veen .login-btn button").removeClass('active');
        $(this).addClass('active');

    });
    $(".veen .login-btn button").click(function () {
        $('.veen .wrapper').removeClass('move');
        $('.body').css('background', '#ff4931');
        $(".veen .rgstr-btn button").removeClass('active');
        $(this).addClass('active');
    });
});

function ingreso(t) {
    document.getElementById("venflo").style.height = "0px";
    document.getElementById("login1").style.display = "none";
    document.getElementById("olv1").style.display = "none";
    document.getElementById("register1").style.display = "none";
    if (t == 1) {
        document.getElementById("venflo").style.height = "267px";
        document.getElementById("login1").style.display = "inherit";
    } else if (t == 2) {
        document.getElementById("venflo").style.height = "0px";
        /* document.getElementById("register1").style.display = "inherit"; */
    } else if (t == 3) {
        document.getElementById("venflo").style.height = "240px";
        document.getElementById("olv1").style.display = "inherit";
    }
}

$(document).ready(function () {
    let table = document.getElementById("mytpag");
    if (!table) return;
    $('#mytpag').DataTable({
        //para cambiar el lenguaje a español
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        },
        "order": [[0, "desc"]],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fa-solid fa-copy fa-2x"></i> ',
                titleAttr: 'Copiar',
                className: 'btn'
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa-solid fa-file-csv fa-2x"></i> ',
                titleAttr: 'Exportar a CSV',
                className: 'btn'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa-solid fa-file-excel fa-2x"></i> ',
                titleAttr: 'Exportar a Excel',
                className: 'btn'
            },
            // {
            //     extend: 'pdfHtml5',
            //     text:   '<i class="fa-solid fa-file-pdf fa-2x"></i> ',
            //     titleAttr: 'Exportar a PDF',
            //     className: 'btn'
            // }
        ]
    });
});

$(document).ready(function () {
    let table = document.getElementById("myt");
    if (!table) return;
    $('#myt').DataTable({
        //para cambiar el lenguaje a español
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        },
        "order": [[0, "desc"]],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fa-solid fa-copy fa-2x"></i> ',
                titleAttr: 'Copiar',
                className: 'btn'
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa-solid fa-file-csv fa-2x"></i> ',
                titleAttr: 'Exportar a CSV',
                className: 'btn'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa-solid fa-file-excel fa-2x"></i> ',
                titleAttr: 'Exportar a Excel',
                className: 'btn'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa-solid fa-file-pdf fa-2x"></i> ',
                titleAttr: 'Exportar a PDF',
                className: 'btn'
            }
        ]
    });
});


$(document).ready(function () {
    let table = document.getElementById("myt2");
    if (!table) return;
    $('#myt2').DataTable({
        //para cambiar el lenguaje a español
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        },
        "order": [[0, "desc"]],
        dom: 'Bfrtip'
    });
});


$(document).ready(function () {
    let table = document.getElementById("myta");
    if (!table) return;
    $('#myta').DataTable({
        //para cambiar el lenguaje a español
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fa-solid fa-copy fa-2x"></i> ',
                titleAttr: 'Copiar',
                className: 'btn'
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa-solid fa-file-csv fa-2x"></i> ',
                titleAttr: 'Exportar a Excel',
                className: 'btn'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa-solid fa-file-excel fa-2x"></i> ',
                titleAttr: 'Exportar a Excel',
                className: 'btn'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa-solid fa-file-pdf fa-2x"></i> ',
                titleAttr: 'Exportar a Excel',
                className: 'btn'
            }
        ]
    });
});


//function eli(){
//   let v = confirm("¿Está seguro de eliminar este registro?");
//   return v;
//}

function eli(link) {
    event.preventDefault(); // Previene el comportamiento del enlace

    Swal.fire({
        title: '¿Está seguro de eliminar este registro?',
        text: "Verifique antes de continuar",
        icon: 'question',
        width: '500px',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#00af00',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, redirigimos al enlace original
            window.location.href = link.href;
        }
    });

    return false;
}

document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');

    if (msg === 'eliminado') {
        Swal.fire({
            icon: 'success',
            width: '500px',
            title: 'Eliminado',
            text: 'El registro se eliminó correctamente.',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#00af00',
        });

        // Limpia la URL para evitar que el mensaje reaparezca al recargar
        const newUrl = window.location.href.split('?')[0] + '?pg=' + params.get('pg');
        window.history.replaceState({}, document.title, newUrl);
    }
});

function eliminarBitacora(element) {
    event.preventDefault(); // Evita el comportamiento por defecto

    const link = element.getAttribute('data-href');

    Swal.fire({
        title: '¿Está seguro de eliminar esta bitácora?',
        text: "No podrá recuperar la información una vez eliminada.",
        icon: 'question',
        width: '500px',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#00af00',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link;
        }
    });

    return false;
}

function eliAprendiz(link) {
    event.preventDefault();

    Swal.fire({
        title: '¿Está seguro de eliminar a este aprendiz de la ficha?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link.href;
        }
    });
}

function eliusu(link) {
    event.preventDefault();
    Swal.fire({
        title: 'Eliminar usuario',
        text: 'Se va a eliminar todo registro relacionado con este usuario, en agendas y minutas. Después de eliminar no se puede recuperar la información. ¿Está seguro?',
        icon: 'warning',
        width: '500px',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        confirmButtonColor: '#d33',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link.href;
        }
    });
    return false;
}

function verf(link) {
    event.preventDefault();
    Swal.fire({
        title: 'Agregar agenda',
        text: 'Va a agregar una nueva agenda. Si existen agendas ya registradas de este usuario dentro de las fechas seleccionadas, se eliminarán estos registros. ¿Está seguro?',
        icon: 'question',
        width: '500px',
        showCancelButton: true,
        confirmButtonText: 'Sí, agregar',
        confirmButtonColor: '#00af00',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link.href;
        }
    });
    return false;
}

function verfs(link) {
    event.preventDefault();
    Swal.fire({
        title: 'Asignar agenda a todos',
        text: 'Va a asignar una nueva agenda a todos los usuarios. Si existen agendas ya registradas para estos usuarios dentro de las fechas seleccionadas, se eliminarán estos registros. ¿Está seguro?',
        icon: 'question',
        width: '500px',
        showCancelButton: true,
        confirmButtonText: 'Sí, asignar',
        confirmButtonColor: '#00af00',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = link.href;
        }
    });
    return false;
}


//function eliusu() {
//    let v = confirm("Se va a eliminar todo registro relacionado con este usuario, en agendas y minutas. Después de eliminar no se puede recuperar la información. ¿Está seguro de eliminar este registro?");
//    return v;
//}

//function verf() {
// let v = confirm("Va a agregar una nueva agenda, tenga en cuenta que si existen agendas ya registradas de este usuario dentro de las fechas seleccionadas, se eliminarán estos registros.\n\n¿Está seguro de agregar este registro?");
// return v;
//}

//function verfs() {
//let v = confirm("Va a asignar una nueva agenda a todos los usuarios, tenga en cuenta que si existen agendas ya registradas para estos usuarios dentro de las fechas seleccionadas, se eliminarán estos registros.\n\n¿Está seguro de asignar esta agenda?");
// return v;
//}

function ocultar(ocu) {
    document.getElementById('ocultar').style.display = ocu;
    if (ocu == "none") {
        document.getElementById('mos').style.display = "inline-block";
        document.getElementById('cer').style.display = "none";
    } else {
        document.getElementById('mos').style.display = "none";
        document.getElementById('cer').style.display = "inline-block";
    }
}

function actBusc() {
    var search = prompt("Termino de busqueda:");
    if (search == null || search == "") {
        // alert("User cancelled");
    } else {
        find(search)
    }

}

document.addEventListener('DOMContentLoaded', function () {
    var home = document.querySelector('.home');
    if (!home) return; // 👈 esto evita el error si .home no existe

    var btnmod = home.querySelectorAll('.btnmod');
    if (btnmod.length === 1) home.style.gridTemplateColumns = '1fr';
    else if (btnmod.length === 2) home.style.gridTemplateColumns = '1fr 1fr';
});


function err(mess = "") {
    if (mess) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            html: "<strong>Error:</strong> ¡" + mess + "!",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }
}

function previewFile(id) {
    document.getElementById(id).addEventListener("change", function () {
        const fileNameElement = document.getElementById("fileName");
        if (this.files && this.files.length > 0) {
            fileNameElement.textContent = this.files[0].name;
        } else {
            fileNameElement.textContent = "Arrastre y suelte o haga clic para cargar";
        }
    });
}

function initImageUploader({
    inputId,          // id del input file
    dropzoneSelector, // selector del contenedor donde se arrastra y muestra preview
    originalSrc = null, // ruta original (ej: de BD)
    previewClass = "fot-prev-usu", // clase para la img preview
    btnChangeClass = "btn btn-success", // clase para el botón cambiar
    btnRemoveClass = "btn btn-danger ms-2" // clase para el botón remover
}) {
    const fileInput = document.getElementById(inputId);
    const dropzone = document.querySelector(dropzoneSelector);

    let originalImage = originalSrc;
    let preview = dropzone.querySelector("img#preview");

    // Si ya hay imagen de BD, tomarla como original
    if (preview && preview.src) {
        originalImage = preview.src;
    }

    // Escuchar eventos
    fileInput.addEventListener("change", e => previewImage(e.target.files[0]));
    dropzone.addEventListener("dragover", e => e.preventDefault());
    dropzone.addEventListener("drop", e => handleDrop(e));

    function previewImage(file) {
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = e => setPreview(e.target.result);
            reader.readAsDataURL(file);
        }
    }

    function handleDrop(event) {
        event.preventDefault();
        const file = event.dataTransfer.files[0];
        if (file && file.type.startsWith("image/")) {
            fileInput.files = event.dataTransfer.files;
            previewImage(file);
        }
    }

    function setPreview(src) {
        let dropPreview = dropzone.querySelector("#dropPreview");

        if (dropPreview) dropPreview.remove();

        if (!preview) {
            preview = document.createElement("img");
            preview.id = "preview";
            preview.classList.add(previewClass);
            dropzone.prepend(preview);

            // Crear contenedor botones si no existe
            let btns = dropzone.querySelector(".mt-2");
            if (!btns) {
                btns = document.createElement("div");
                btns.classList.add("mt-2");
                btns.innerHTML = `
                    <button type="button" class="${btnChangeClass}" 
                        onclick="document.getElementById('${inputId}').click()">Cambiar imagen</button>
                `;
                dropzone.appendChild(btns);
            }
        }

        preview.src = src;
        toggleRemoveBtn(src);
    }

    function toggleRemoveBtn(currentSrc) {
        let removeBtn = dropzone.querySelector("#removeBtn");
        const btns = dropzone.querySelector(".mt-2");

        if (currentSrc !== originalImage) {
            if (!removeBtn) {
                removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.id = "removeBtn";
                removeBtn.className = btnRemoveClass;
                removeBtn.innerText = "Remover";
                removeBtn.onclick = () => removeImage();
                btns.appendChild(removeBtn);
            }
        } else {
            if (removeBtn) removeBtn.remove();
        }
    }

    function removeImage() {
        fileInput.value = "";

        if (originalImage) {
            preview.src = originalImage;
            toggleRemoveBtn(originalImage);
        } else {
            if (preview) preview.remove();
            const btns = dropzone.querySelector(".mt-2");
            if (btns) btns.remove();

            const dropPreview = document.createElement("div");
            dropPreview.id = "dropPreview";
            dropPreview.classList.add("upload-preview");
            dropPreview.innerHTML = `
                <i class="fa-solid fa-cloud-arrow-up upload-info-icon"></i>
                <p class="upload-hint">Arrastre y suelte o haga clic para cargar</p>
            `;
            dropzone.appendChild(dropPreview);
        }
    }

    return { previewImage, removeImage, setPreview };
}


function previewImgTable() {
    document.addEventListener("DOMContentLoaded", function () {
        const lightbox = document.getElementById("lightbox");
        const lightboxImg = document.getElementById("lightbox-img");
        const closeBtn = document.querySelector(".close");

        document.querySelectorAll(".zoom-img").forEach(img => {
            img.addEventListener("click", function () {
                lightbox.style.display = "block";
                lightboxImg.src = this.src;
            });
        });

        closeBtn.addEventListener("click", function () {
            lightbox.style.display = "none";
        });

        // cerrar al hacer click fuera de la imagen
        lightbox.addEventListener("click", function (e) {
            if (e.target === lightbox) {
                lightbox.style.display = "none";
            }
        });
    });
}