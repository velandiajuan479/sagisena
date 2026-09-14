$(document).ready(function () {
  $('#example').DataTable({
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


$(function () {
  $.widget("custom.combobox", {
    _create: function () {
      this.wrapper = $("<span>")
        .addClass("custom-combobox")
        .insertAfter(this.element);

      this.element.hide();
      this._createAutocomplete();
      this._createShowAllButton();
    },

    _createAutocomplete: function () {
      var selected = this.element.children(":selected"),
        value = selected.val() ? selected.text() : "";

      this.input = $("<input>")
        .appendTo(this.wrapper)
        .val(value)
        .attr("title", "")
        .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left form-select")
        .autocomplete({
          delay: 0,
          minLength: 0,
          source: this._source.bind(this)
        })
        .tooltip({
          classes: {
            "ui-tooltip": "ui-state-highlight"
          }
        });

      this._on(this.input, {
        autocompleteselect: function (event, ui) {
          ui.item.option.selected = true;
          this._trigger("select", event, {
            item: ui.item.option
          });
        },

        autocompletechange: "_removeIfInvalid"
      });
    },

    _createShowAllButton: function () {
      var input = this.input,
        wasOpen = false;

      $("<a>")
        .attr("tabIndex", -1)
        .attr("title", "Show All Items")
        .tooltip()
        .appendTo(this.wrapper)
        .button({
          icons: {
            primary: "ui-icon-triangle-1-s"
          },
          text: false
        })
        .removeClass("ui-corner-all")
        .addClass("custom-combobox-toggle ui-corner-right")
        .on("mousedown", function () {
          wasOpen = input.autocomplete("widget").is(":visible");
        })
        .on("click", function () {
          input.trigger("focus");

          // Close if already visible
          if (wasOpen) {
            return;
          }

          // Pass empty string as value to search for, displaying all results
          input.autocomplete("search", "");
        });
    },

    _source: function (request, response) {
      var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
      response(this.element.children("option").map(function () {
        var text = $(this).text();
        if (this.value && (!request.term || matcher.test(text)))
          return {
            label: text,
            value: text,
            option: this
          };
      }));
    },

    _removeIfInvalid: function (event, ui) {

      // Selected an item, nothing to do
      if (ui.item) {
        return;
      }

      // Search for a match (case-insensitive)
      var value = this.input.val(),
        valueLowerCase = value.toLowerCase(),
        valid = false;
      this.element.children("option").each(function () {
        if ($(this).text().toLowerCase() === valueLowerCase) {
          this.selected = valid = true;
          return false;
        }
      });

      // Found a match, nothing to do
      if (valid) {
        return;
      }

      // Remove invalid value
      this.input
        .val("")
        .attr("title", value + " didn't match any item")
        .tooltip("open");
      this.element.val("");
      this._delay(function () {
        this.input.tooltip("close").attr("title", "");
      }, 2500);
      this.input.autocomplete("instance").term = "";
    },

    _destroy: function () {
      this.wrapper.remove();
      this.element.show();
    }
  });

  $("#combobox").combobox();
  $("#toggle").on("click", function () {
    $("#combobox").toggle();
  });
});

$(document).ready(function () {
  $('#tblca').DataTable({
    "order": [[0, "desc"]],
    "language": {
      "decimal": "",
      "emptyTable": "No hay información",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
      "infoFiltered": "(Filtrado de _MAX_ total entradas)",
      "infoPostFix": "",
      "thousands": ",",
      "lengthMenu": "Mostrar _MENU_ Entradas",
      "loadingRecords": "Cargando...",
      "processing": "Procesando...",
      "search": "Buscar:",
      "zeroRecords": "Sin resultados encontrados",
      "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Siguiente",
        "previous": "Anterior"
      }
    }
  });
});

function solonum(e) {
  key = e.keyCode || e.which;
  teclado = String.fromCharCode(key);
  numeros = "0123456789";
  var especiales = ["8", "45"];
  teclado_especial = false;
  for (var i in especiales) {
    if (key == especiales[i]) {
      teclado_especial = true;
    }
  }
  if (numeros.indexOf(teclado) == -1 && !teclado_especial) {
    return false;
  }
}

function sololet(f) {
  key = f.keyCode || f.which;
  teclado = String.fromCharCode(key);
  letras = " abcdefghijklmnñopqrstuvwxyzáéíóú" + " ABCDEFGHIJKLMNÑOPQRSTUVWXYZÁÉÍÓÚ";
  especiales = "8-37-38-46";
  teclado_especial = false;
  for (var u in especiales) {
    if (key == especiales[u]) {
      teclado_especial = true; break;
    }
  }
  if (letras.indexOf(teclado) == -1 && !teclado_especial) {
    return false;
  }
}

function validadat() {
  p1 = document.getElementById("pas1").value;
  p2 = document.getElementById("pas2").value;
  if (!p1 || !p2 || p1 != p2) {
    alert("La contraseña no coincide.");
    return false;
  }
}

function eliminar(link) {
  event.preventDefault(); // Previene el comportamiento por defecto del enlace

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
      window.location.href = link.href;
    }
  });

  return false;
}

function Eliminar(link) {
  event.preventDefault();

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
      window.location.href = link.href;
    }
  });

  return false;
}

function ocul(mos = 0, est = 0) {
  if (mos == 1) {
    if (est == 1) {
      document.getElementById("frmins").style.display = "inherit";
      document.getElementById("mas").style.display = "none";
      document.getElementById("menos").style.display = "inline-block";
    } else {
      document.getElementById("frmins").style.display = "none";
      document.getElementById("mas").style.display = "inline-block";
      document.getElementById("menos").style.display = "none";
    }
  }
}

function ocudt(nu, alto, cdvl) {
  if (nu == 1) {
    ocuall(cdvl);
  }
  if (nu == 1) {
    document.getElementById(cdvl).style.display = "block";
    // document.getElementById(cdvl).style.position = "relative";
    // document.getElementById(cdvl).style.width = "100%";
    // document.getElementById(cdvl).style.height = alto;
    // document.getElementById(cdvl).style.transition = "all 1s";
    // document.getElementById(cdvl).style.opacity = "1";
    // document.getElementById(cdvl).style.left = "0px";
    document.getElementById("ocu" + cdvl).style.display = "block";
    //document.getElementById("mos"+cdvl).style.display = "none";
  } else {
    document.getElementById(cdvl).style.display = "none";
    // document.getElementById(cdvl).style.position = "absolute";
    // document.getElementById(cdvl).style.width = "0px";
    // document.getElementById(cdvl).style.height = "0px";
    // document.getElementById(cdvl).style.transition = "all 2s";
    // document.getElementById(cdvl).style.opacity = "0";
    // document.getElementById(cdvl).style.left = "-1000px";
    document.getElementById("ocu" + cdvl).style.display = "none";
    //document.getElementById("mos"+cdvl).style.display = "block";
    window.scroll(0, 0);
  }
}

function ocuall(cdvl) {
  //1001,1002,1003,1004,1101,1102,1103,1104,1105,1106,1107,1108,1110,1111,1112,1113,1114,1115,1116,1117
  //alert(cdvl);
  for (let u = 1001; u <= 1020; u++) {
    if (cdvl != u) {
      document.getElementById(u).style.display = "none";
      // document.getElementById(u).style.position = "absolute";
      // document.getElementById(u).style.width = "0px";
      // document.getElementById(u).style.height = "0px";
      // document.getElementById(u).style.transition = "all 2s";
      // document.getElementById(u).style.opacity = "0";
      // document.getElementById(u).style.left = "-1000px";
      window.scroll(0, 0);
    }
  }
}

function recCiudad(value) {
  //alert("Si le llega "+value);
  var parametros = {
    "valor": value
  };
  $.ajax({
    data: parametros,
    url: 'selmun.php',
    type: 'post',
    success: function (response) {
      $("#reloadMun").html(response);
    }
  });
}

//------------- Creación controles JS -----------------------
res = new Array();
val = new Array();

function registra(v, n) {
  res.push(v);
  val.push(n);
  mostrar();
}
function mostrar() {
  //var arv = res.toString();
  var micapa = document.getElementById('camp');
  var controles = '<input type="hidden" value="' + res.length + '" name="cant" /><br>';
  controles += '<table width="100%">';
  for (i = 0; i < res.length; i++) {
    //tb = document.getElementById("titbtn"+val[i]).innerHTML;
    controles += '<tr>';
    controles += '<td>' + (i + 1) + '.</td>';
    controles += '<td><input type="radio" name="valres[]" checked></td>';
    controles += '<td><input type="text" name="txtres[]" maxlength="200" required class="form-control"></td>';
    controles += '</tr>';
  }
  controles += '</table>';
  micapa.innerHTML = controles;
  //alert(arv);
}

function eliarr(n) {
  var t = n.toString();
  var index = res.indexOf(t);
  //alert(n+" - "+t+" - "+index);
  if (index > -1) {
    res.splice(index, 1);
    val.splice(index, 1);
  }
  mostrar();
}

function enfoque() {
  document.getElementById("inic").focus();
}

///ComboBoxCustom
$(function () {
  $.widget("custom.combobox", {
    _create: function () {
      this.wrapper = $("<span>")
        .addClass("custom-combobox")
        .insertAfter(this.element);

      this.element.hide();
      this._createAutocomplete();
      this._createShowAllButton();
    },

    _createAutocomplete: function () {
      var selected = this.element.children(":selected"),
        value = selected.val() ? selected.text() : "";

      this.input = $("<input>")
        .appendTo(this.wrapper)
        .val(value)
        .attr("title", "")
        .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
        .attr("style", "background-color: #fff;")
        .attr("placeholder", "Seleccione producto")
        .autocomplete({
          delay: 0,
          minLength: 0,
          source: $.proxy(this, "_source")
        })
        .tooltip({
          classes: {
            "ui-tooltip": "ui-state-highlight"
          }
        });

      this._on(this.input, {
        autocompleteselect: function (event, ui) {
          ui.item.option.selected = true;
          this._trigger("select", event, {
            item: ui.item.option
          });
        },

        autocompletechange: "_removeIfInvalid"
      });
    },

    _createShowAllButton: function () {
      var input = this.input,
        wasOpen = false;

      $("<a>")
        .attr("tabIndex", -1)
        .attr("title", "Show All Items")
        .tooltip()
        .appendTo(this.wrapper)
        .button({
          icons: {
            primary: "ui-icon-triangle-1-s"
          },
          text: false
        })
        .removeClass("ui-corner-all")
        .addClass("custom-combobox-toggle ui-corner-right")
        .on("mousedown", function () {
          wasOpen = input.autocomplete("widget").is(":visible");
        })
        .on("click", function () {
          input.trigger("focus");

          // Close if already visible
          if (wasOpen) {
            return;
          }

          // Pass empty string as value to search for, displaying all results
          input.autocomplete("search", "");
        });
    },

    _source: function (request, response) {
      var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
      response(this.element.children("option").map(function () {
        var text = $(this).text();
        if (this.value && (!request.term || matcher.test(text)))
          return {
            label: text,
            value: text,
            option: this
          };
      }));
    },

    _removeIfInvalid: function (event, ui) {

      // Selected an item, nothing to do
      if (ui.item) {
        return;
      }

      // Search for a match (case-insensitive)
      var value = this.input.val(),
        valueLowerCase = value.toLowerCase(),
        valid = false;
      this.element.children("option").each(function () {
        if ($(this).text().toLowerCase() === valueLowerCase) {
          this.selected = valid = true;
          return false;
        }
      });

      // Found a match, nothing to do
      if (valid) {
        return;
      }

      // Remove invalid value
      this.input
        .val("")
        .attr("title", value + " didn't match any item")
        .tooltip("open");
      this.element.val("");
      this._delay(function () {
        this.input.tooltip("close").attr("title", "");
      }, 2500);
      this.input.autocomplete("instance").term = "";
    },

    _destroy: function () {
      this.wrapper.remove();
      this.element.show();
    }
  });

  $("#combobox").combobox();
  $("#toggle").on("click", function () {
    $("#combobox").toggle();
  });
});

// $( "#combobox1" ).combobox();
//     $( "#toggle1" ).on( "click", function() {
//       $( "#combobox1" ).toggle();
//     });
// } );

function nFfin(ff) {
  const fecha = new Date(ff);

  const fechaMas1Mes = new Date(fecha);
  fechaMas1Mes.setMonth(fechaMas1Mes.getMonth() + 1);
  const fechaMas3Meses = new Date(fecha);
  fechaMas3Meses.setMonth(fechaMas3Meses.getMonth() + 3);

  // Formatear en formato YYYY-MM-DD
  const formatearFecha = (f) => {
    const anio = f.getFullYear();
    const mes = String(f.getMonth() + 1).padStart(2, '0');
    const dia = String(f.getDate()).padStart(2, '0');
    return `${anio}-${mes}-${dia}`;
  };

  // Asignar al input
  const input = document.getElementById("feclin");
  if (input) {
    input.value = formatearFecha(fechaMas1Mes);
    input.max = formatearFecha(fechaMas3Meses);
  }
}

//Buscador...


let buscador = document.getElementById("buscador");
if (buscador) {
  let timer;
  let resultados = document.getElementById("resultados");
  buscador.addEventListener("keyup", function () {
    clearTimeout(timer);
    const query = this.value.trim();

    if (query.length < 2) {
      resultados.style.display = "none";
      resultados.innerHTML = '';
      return;
    }

    timer = setTimeout(() => {
      fetch("controllers/buscadormod.php?q=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
          const resultados = document.getElementById("resultados");
          resultados.innerHTML = "";

          if (data.length > 0) {
            resultados.style.display = "block";
            data.forEach(op => {
              const li = document.createElement("li");
              li.classList.add("result-item");
              li.textContent = op.nompag + " (Módulo: " + op.nommod + ")";
              li.onclick = () => {
                const form = document.getElementById("form-" + op.idmod);
                if (form) {
                  form.querySelector("input[name='pg']").value = op.idpag;
                  form.submit();
                }
              };
              resultados.appendChild(li);
            });
          } else {
            resultados.style.display = "block";
            resultados.innerHTML = "<li class='result-empty'>No se encontraron resultados para \"" + query + "\"</li>";
          }
        });
    }, 500);
  });
  // Ocultar resultados cuando se pierde el foco
  buscador.addEventListener("blur", () => {
    setTimeout(() => {
      resultados.style.display = "none";
    }, 200);
  });

  // Mostrar resultados si vuelve a tener foco y ya hay contenido
  buscador.addEventListener("focus", () => {
    if (resultados.innerHTML.trim() !== "") {
      resultados.style.display = "block";
    }
  });
}
