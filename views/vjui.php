

<?php
// Inicializar variables si no están definidas para evitar warnings
if (!isset($datOne)) {
    $datOne = null;
}
if (!isset($icono)) {
    $icono = 'fa-solid fa-gavel'; // Icono por defecto para juicios
}

// Cargar datos del instructor desde la sesión si no están definidos
if (!isset($instructorLogueado)) {
    if (isset($_SESSION['idusu']) && isset($_SESSION['nomusu'])) {
        $instructorLogueado = [
            'idusu' => $_SESSION['idusu'],
            'nomusu' => $_SESSION['nomusu'],
            'ndocusu' => $_SESSION['ndocusu'] ?? ''
        ];
    } else {
        $instructorLogueado = null;
    }
}

// Cargar datos básicos si no están definidos
if (!isset($datFic)) {
    $datFic = [];
}
if (!isset($datUsu)) {
    $datUsu = [];
}

// Cargar competencias y resultados del instructor logueado
if (!isset($datCompe) || !isset($datRes)) {
    if (isset($_SESSION['idusu'])) {
        error_log("DEBUG VJUI: Cargando competencias y resultados para instructor ID: " . $_SESSION['idusu']);
        require_once('models/mjui.php');
        $mjui = new Mjui();
        
        // Cargar competencias del instructor
        $datCompe = $mjui->getCompetenciasPorInstructor($_SESSION['idusu']);
        error_log("DEBUG VJUI: Competencias cargadas: " . count($datCompe) . " elementos");
        
        // Cargar resultados del instructor
        $datRes = $mjui->getResultadosPorInstructor($_SESSION['idusu']);
        error_log("DEBUG VJUI: Resultados cargados: " . count($datRes) . " elementos");
    } else {
        error_log("DEBUG VJUI: No hay sesión de usuario, inicializando arrays vacíos");
        $datCompe = [];
        $datRes = [];
    }
} else {
    error_log("DEBUG VJUI: Competencias y resultados ya están definidos");
}
?>

<div class="conte">
	<?php echo titulo2("<i class='".$icono."'></i> Juicios",1); ?>
	<div class="inser">
		<!-- SECCIÓN DE EDICIÓN DE CALIFICACIÓN -->
		<div class="card border-primary">
			<div class="card-header bg-primary text-white">
				<h5 class="mb-0">
					<i class="fa-solid fa-edit"></i> Edición de Calificaciones
				</h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-info">
							<i class="fa-solid fa-info-circle"></i> 
							<strong>Instrucciones:</strong> Seleccione un juicio de la tabla a continuación y haga clic en el botón de edición para cambiar su calificación.
						</div>
					</div>
				</div>
				
				<!-- Área de edición (se llena dinámicamente) -->
				<div id="area-edicion" class="row" style="display: none;">
					<div class="col-md-12">
						<div class="card border-success">
							<div class="card-header bg-success text-white">
								<h6 class="mb-0">
									<i class="fa-solid fa-user-edit"></i> Cambiar Calificación
								</h6>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-8">
										<div class="mb-3">
											<strong>Estudiante:</strong> <span id="estudiante-edicion"></span>
										</div>
										<div class="mb-3">
											<strong>Competencia:</strong> <span id="competencia-edicion"></span>
										</div>
										<div class="mb-3">
											<strong>Resultado:</strong> <span id="resultado-edicion"></span>
										</div>
										<div class="mb-3">
											<strong>Calificación Actual:</strong> <span id="calificacion-actual" class="badge"></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="text-center">
											<p class="mb-3"><strong>Seleccione la nueva calificación:</strong></p>
											<div class="d-grid gap-2">
												<button type="button" class="btn btn-outline-success" id="btn-aprobado" onclick="cambiarCalificacion('APROBADO')">
													<i class="fa-solid fa-check"></i> Aprobado
												</button>
												<button type="button" class="btn btn-outline-danger" id="btn-no-aprobado" onclick="cambiarCalificacion('NO APROBADO')">
													<i class="fa-solid fa-times"></i> No Aprobado
												</button>
												<button type="button" class="btn btn-outline-warning" id="btn-por-evaluar" onclick="cambiarCalificacion('POR EVALUAR')">
													<i class="fa-solid fa-hourglass-half"></i> Por Evaluar
												</button>
											</div>
											<div class="mt-3">
												<button type="button" class="btn btn-secondary btn-sm" onclick="cancelarEdicion()">
													<i class="fa-solid fa-times"></i> Cancelar
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Área de mensajes -->
				<div id="mensajes-edicion" class="row" style="display: none;">
					<div class="col-md-12">
						<div id="mensaje-exito" class="alert alert-success" style="display: none;">
							<i class="fa-solid fa-check-circle"></i> <span id="texto-exito"></span>
						</div>
						<div id="mensaje-error" class="alert alert-danger" style="display: none;">
							<i class="fa-solid fa-exclamation-triangle"></i> <span id="texto-error"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Sección de Importación de Excel -->
	<div class="inser mt-4">
		<div class="row">
			<div class="col-md-12">
				<h5><i class="fa-solid fa-file-import"></i> Importación de Juicios en 2 Pasos</h5>
				<div class="alert alert-info">
					<h6><i class="fa-solid fa-info-circle"></i> Proceso de Importación</h6>
					<ol class="mb-0">
						<li><strong>Paso 1:</strong> Convertir Excel a CSV y mostrar vista previa</li>
						<li><strong>Paso 2:</strong> Confirmar e importar todos los registros a la base de datos</li>
					</ol>
				</div>
				<form id="frmimport" action="juicios.php?pg=<?=$pg;?>&opera=import" method="POST" enctype="multipart/form-data">
					<div class="row">
						<div class="form-group col-md-8">
							<label for="excel_file">Seleccionar archivo Excel</label>
							<input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xls,.xlsx" required>
							<small class="text-muted">Formatos soportados: .xls, .xlsx - La ficha de caracterización se extraerá automáticamente del archivo</small>
						</div>
						<div class="form-group col-md-4">
							<br>
							<button type="submit" class="btn btn-primary" id="btn-import">
								<i class="fa-solid fa-file-export"></i> Paso 1: Convertir y Previsualizar
							</button>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col-md-12">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="update_existing" name="update_existing" value="1">
								<label class="form-check-label" for="update_existing">
									Actualizar registros existentes
								</label>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<!-- Área de progreso -->
		<div class="row mt-3" id="import-progress" style="display: none;">
			<div class="col-md-12">
				<div class="progress">
					<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
						 style="width: 0%" id="progress-bar"></div>
				</div>
				<div class="mt-2" id="progress-text">Iniciando importación...</div>
			</div>
		</div>

		<!-- Área de resultados -->
		<div class="row mt-3" id="import-results" style="display: none;">
			<div class="col-md-12">
				<div class="alert" id="results-alert">
					<h6><i class="fa-solid fa-chart-simple"></i> Resultados de la Importación</h6>
					<div id="results-content"></div>
				</div>
			</div>
		</div>
	</div>


<!-- SECCIÓN DE VISUALIZACIÓN DE JUICIOS IMPORTADOS -->
<div class="inser mt-4">
	<div class="row">
		<div class="col-md-12">
			<h5><i class="fa-solid fa-table"></i> Visualización de Juicios Importados</h5>
			<div style="background-color: #00af00; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 2px solid #00af00;">
				<h6 style="color: white;"><i class="fa-solid fa-info-circle"></i> Filtros Inteligentes</h6>
				<p class="mb-0" style="color: white;">Utiliza los filtros para visualizar los juicios importados. Los filtros se aplican en cascada para mayor precisión.</p>
			</div>
			
			<!-- Información del Filtro Automático -->
			<div style="background-color: #00af00; color: white; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 2px solid #00af00;" id="info_filtro_usuario">
				<h6 style="color: white;"><i class="fa-solid fa-user-check"></i> Filtro Automático por Usuario</h6>
				<p class="mb-0" style="color: white;">Solo se muestran los juicios del usuario logueado actualmente. Este filtro se aplica automáticamente por seguridad.</p>
			</div>
			
			<!-- Filtros Inteligentes -->
			<div class="row mb-3">
				<div class="col-md-2">
					<label for="filtro_ficha" class="form-label">Ficha</label>
					<select id="filtro_ficha" class="form-control form-select">
						<option value="">Todas las fichas</option>
						<?php if($datFic){ foreach($datFic as $df){ ?>
							<option value="<?=$df['idfic'];?>"><?=$df['idfic'].' - '.$df['nomfic'];?></option>
						<?php }} ?>
					</select>
				</div>
				<div class="col-md-2">
					<label for="filtro_funcionario" class="form-label">Funcionario</label>
					<select id="filtro_funcionario" class="form-control form-select">
						<option value="">Todos los funcionarios</option>
					</select>
				</div>
				<div class="col-md-2">
					<label for="filtro_competencia" class="form-label">Competencia</label>
					<select id="filtro_competencia" class="form-control form-select">
						<option value="">Todas las competencias</option>
					</select>
				</div>
				<div class="col-md-2">
					<label for="filtro_estado" class="form-label">Estado Estudiante</label>
					<select id="filtro_estado" class="form-control form-select">
						<option value="">Todos los estados</option>
						<option value="EN FORMACION">EN FORMACION</option>
						<option value="RETIRO VOLUNTARIO">RETIRO VOLUNTARIO</option>
						<option value="CANCELADO">CANCELADO</option>
						<option value="TRASLADADO">TRASLADADO</option>
					</select>
				</div>
				<div class="col-md-2">
					<label for="filtro_modalidad" class="form-label">Calificación</label>
					<select id="filtro_modalidad" class="form-control form-select">
						<option value="">Todas las calificaciones</option>
						<option value="APROBADO">APROBADO</option>
						<option value="POR EVALUAR">POR EVALUAR</option>
					</select>
				</div>
				<div class="col-md-2">
					<label for="filtro_busqueda" class="form-label">Búsqueda</label>
					<input type="text" id="filtro_busqueda" class="form-control" placeholder="Documento, nombre, resultado...">
	</div>
</div>

			<!-- Botones de Acción -->
			<div class="row mb-3">
				<div class="col-md-12">
					<button type="button" id="btn_aplicar_filtros" class="btn btn-link" title="Aplicar Filtros">
						<i class="fa-solid fa-filter fa-2x" style="color: #00af00;"></i>
					</button>
					<button type="button" id="btn_limpiar_filtros" class="btn btn-link" title="Limpiar Filtros">
						<i class="fa-solid fa-eraser fa-2x" style="color: #00af00;"></i>
					</button>
					<button type="button" id="btn_exportar_csv" class="btn btn-link" title="Exportar CSV">
						<i class="fa-solid fa-download fa-2x" style="color: #00af00;"></i>
					</button>
					<button type="button" id="btn_exportar_excel" class="btn btn-link" title="Exportar Excel">
						<i class="fa-solid fa-file-excel fa-2x" style="color: #00af00;"></i>
					</button>
					<button type="button" id="btn_poblar_filtros" class="btn btn-link" title="Poblar Filtros">
						<i class="fa-solid fa-sync fa-2x" style="color: #00af00;"></i>
					</button>
				</div>
			</div>
			<!-- Título de la Ficha -->
			<div class="row mb-3">
				<div class="col-12">
					<div class="alert alert-success text-center" style="background-color: #00af00; color: white; border: none;">
						<h4 class="mb-0" id="titulo_ficha">
							<i class="fa-solid fa-file-alt"></i> Ficha: <span id="ficha_actual">Seleccione una ficha</span>
						</h4>
					</div>
				</div>
			</div>

			<!-- Tabla de Juicios Importados -->
			<div class="table-responsive">
				<table id="tabla_juicios_importados" class="table table-striped table-hover" style="width:100%; background-color: white; border: 2px solid #00af00;">
					<thead style="background-color: #00af00; color: white;">
						<tr>
							<th style="width: 300px; border: 1px solid #00af00; padding: 12px; color: white;">Estudiante</th>
							<th style="width: 350px; border: 1px solid #00af00; padding: 12px; color: white;">Competencia</th>
							<th style="width: 120px; border: 1px solid #00af00; padding: 12px; color: white;">Calificación</th>
							<th class="col-acciones" style="width: 80px; border: 1px solid #00af00; padding: 12px; color: white;">Acciones</th>
		</tr>
	</thead>
					<tbody id="tbody_juicios_importados" style="background-color: white;">
						<!-- Los datos se cargarán dinámicamente -->
					</tbody>
				</table>
			</div>
			
			<!-- Vista de tarjetas para móvil -->
			<div class="mobile-cards" id="mobile-cards-container">
				<!-- Los datos se cargarán dinámicamente -->
			</div>
			
			<!-- Paginación -->
			<div class="row mt-3">
				<div class="col-md-6">
					<div class="d-flex align-items-center">
						<label for="registros_por_pagina" class="form-label me-2 mb-0">Mostrar:</label>
						<select id="registros_por_pagina" class="form-select form-select-sm" style="width: auto;">
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<span class="ms-3 text-muted">
							Mostrando <span id="inicio_registros">1</span> a <span id="fin_registros">10</span> de <span id="total_registros">0</span> registros
						</span>
					</div>
				</div>
				<div class="col-md-6">
					<nav aria-label="Navegación de páginas">
						<ul class="pagination justify-content-end mb-0" id="paginacion">
							<!-- La paginación se generará dinámicamente -->
						</ul>
					</nav>
				</div>
			</div>
			
			<!-- Espacio adicional debajo de la paginación -->
			<div class="mt-3"></div>
		</div>
	</div>
</div>

<script>
// JavaScript para el formulario manual con validación inteligente
function limpiarFormulario() {
    document.getElementById('frmins').reset();
    
    // Resetear selects a primera opción (no tocar instructor y ficha que son de solo lectura)
    document.getElementById('estes').selectedIndex = 0;
    document.getElementById('caljui').selectedIndex = 0;
    document.getElementById('resapr').selectedIndex = 0;
    document.getElementById('compe').selectedIndex = 0;
    
    // Limpiar campos hidden
    const hiddenInputs = document.querySelectorAll('input[type="hidden"]');
    hiddenInputs.forEach(input => {
        if (input.name !== 'opera') {
            input.value = '';
        }
    });
    
    // Ocultar campos "Otro"
    document.getElementById('compe_otro').style.display = 'none';
    document.getElementById('resapr_otro').style.display = 'none';
    
    // Ocultar indicador de estado
    document.getElementById('estado-formulario').style.display = 'none';
    
    console.log('Formulario limpiado correctamente');
}

// === Utilidades de selección "inteligente" ===
function normalize(s){ return (s ?? '').toString().trim().toUpperCase(); }
function onlyDigits(s){ return (s ?? '').toString().replace(/\D+/g,''); }

/**
 * Intenta seleccionar una opción de <select> usando múltiples estrategias:
 * 1) Coincidencia exacta por value
 * 2) Coincidencia por texto (empieza con "VALOR - ..." o incluye)
 * 3) Coincidencia por dígitos (2773071A vs 2773071)
 * candidates: array de strings alternas (p.ej. idfic, ficca, denpr)
 */
function smartSelect(selectEl, candidates) {
  if (!selectEl || !candidates || !candidates.length) return false;
  const opts = Array.from(selectEl.options);
  for (const raw of candidates) {
    if (!raw) continue;
    const valN = normalize(raw);
    const digN = onlyDigits(raw);
    // 1) Exacto por value
    let opt = opts.find(o => normalize(o.value) === valN);
    if (opt) { selectEl.value = opt.value; return true; }
    // 2) Por texto
    opt = opts.find(o => {
      const t = normalize(o.textContent);
      // comienza con "ID - ..." o contiene el valor
      return t.startsWith(valN + ' -') || t.includes(valN);
    });
    if (opt) { selectEl.value = opt.value; return true; }
    // 3) Por dígitos
    opt = opts.find(o => {
      const vDig = onlyDigits(o.value);
      const tDig = onlyDigits(o.textContent);
      return (vDig && vDig === digN) || (tDig && tDig === digN);
    });
    if (opt) { selectEl.value = opt.value; return true; }
  }
  return false;
}

/** Selección simple por valor o por inclusión de texto (case-insensitive) */
function selectByValueOrText(selectEl, value){
  if (!selectEl || !value) return false;
  const vN = normalize(value);
  const opts = Array.from(selectEl.options);
  let opt = opts.find(o => normalize(o.value) === vN);
  if (!opt) opt = opts.find(o => normalize(o.textContent).includes(vN));
  if (opt){ selectEl.value = opt.value; return true; }
  return false;
}

// === Función robusta para buscar estudiante por documento ===
function buscarEstudiante() {
  const ndoce = document.getElementById('ndoce').value.trim();
  if (!ndoce) return;

  // Mostrar indicador de carga
  mostrarIndicadorCarga();

  const formData = new FormData();
  formData.append('opera', 'buscar_juicio');
  formData.append('ndoce', ndoce);

  fetch('controllers/cjui.php?opera=buscar_juicio', { method: 'POST', body: formData })
    .then(r => {
      const ct = r.headers.get('content-type') || '';
      if (!ct.includes('application/json')) return r.text().then(t => { throw new Error('NO_JSON:' + t.substring(0,200)); });
      return r.json();
    })
    .then(data => {
      console.log('Respuesta del servidor:', data);
      if (data.ok) {
        if (data.existe && data.juicio) {
          // Juicio existe, cargar datos para actualización
          cargarDatosEnFormulario(data);
          const estadoFormulario = document.getElementById('estado-formulario');
          const mensajeEstado = document.getElementById('mensaje-estado');
          estadoFormulario.className = 'alert alert-warning';
          mensajeEstado.innerHTML = '<i class="fa-solid fa-info-circle"></i> Número de documento encontrado. Se cargaron los datos para actualización.';
        } else {
          // No existe juicio, cargar solo datos del estudiante
          cargarDatosEnFormulario(data);
          const estadoFormulario = document.getElementById('estado-formulario');
          const mensajeEstado = document.getElementById('mensaje-estado');
          estadoFormulario.className = 'alert alert-success';
          mensajeEstado.innerHTML = '<i class="fa-solid fa-plus-circle"></i> Estudiante encontrado. Puede crear un nuevo juicio.';
        }
      } else {
        mostrarError('Error: ' + data.error);
      }
    })
    .catch(error => {
      console.error('Error en la búsqueda:', error);
      mostrarError('Error de conexión: ' + error.message);
    });
}



// Funciones helper para el sistema robusto
async function postJSON(url, data) {
    const resp = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: new URLSearchParams(data)
    });

    const ct = resp.headers.get('content-type') || '';
    if (!ct.includes('application/json')) {
        // Respuesta inesperada (HTML/errores). Registra y crea un objeto de error legible
        const text = await resp.text();
        console.error('Respuesta NO JSON:\n', text);
        throw new Error('El servidor no respondió JSON (revisa PHP/headers/outbuffer)');
    }
    return await resp.json();
}

function setSelect(selector, value) {
    const el = document.querySelector(selector);
    if (!el) return;
    
    // Buscar por valor exacto primero
    let opt = [...el.options].find(o => o.value == value);
    
    // Si no se encuentra, buscar por ID al inicio del formato "ID - NOMBRE"
    if (!opt && value) {
        opt = [...el.options].find(o => o.value.startsWith(value + ' - '));
    }
    
    if (opt) el.value = opt.value;
}

// Funciones para manejar campos "Otro"
function toggleCompetenciaOtro() {
    const compeSelect = document.getElementById('compe');
    const compeOtro = document.getElementById('compe_otro');
    
    if (compeSelect.value === '__OTRO__') {
        compeOtro.style.display = 'block';
        compeOtro.required = true;
    } else {
        compeOtro.style.display = 'none';
        compeOtro.required = false;
        compeOtro.value = '';
    }
}

function toggleResultadoOtro() {
    const resaprSelect = document.getElementById('resapr');
    const resaprOtro = document.getElementById('resapr_otro');
    
    if (resaprSelect.value === '__OTRO__') {
        resaprOtro.style.display = 'block';
        resaprOtro.required = true;
    } else {
        resaprOtro.style.display = 'none';
        resaprOtro.required = false;
        resaprOtro.value = '';
    }
}


// Funciones para manejo de errores
function mostrarErrorBusqueda(msg) {
    const box = document.getElementById('alerta-busqueda');
    if (box) { 
        box.textContent = msg; 
        box.classList.remove('d-none'); 
    }
}

function ocultarErrorBusqueda() {
    const box = document.getElementById('alerta-busqueda');
    if (box) { 
        box.classList.add('d-none'); 
    }
}

// Función para normalizar campos "Otro" antes de enviar
function normalizarOtro() {
    ['compe','resapr'].forEach(base => {
        const sel = document.getElementById(base);
        const inp = document.getElementById(base + '_otro');
        if (sel && sel.value === '__OTRO__' && inp && inp.value.trim() !== '') {
            // Copiamos el texto libre al valor real del campo antes de enviar
            sel.value = inp.value.trim();
        }
    });
}

// Función para actualizar resultados cuando cambia la competencia
function actualizarResultadosPorCompetencia() {
    const competenciaSelect = document.getElementById('compe');
    const resultadoSelect = document.getElementById('resapr');
    const competenciaValue = competenciaSelect.value;
    
    // Limpiar resultados actuales
    resultadoSelect.innerHTML = '<option value="">Seleccione un resultado de aprendizaje</option>';
    
    if (!competenciaValue || competenciaValue === '__OTRO__') {
        return;
    }
    
    // Extraer ID de competencia del formato "ID - DESCRIPCIÓN"
    const idcom = competenciaValue.split(' - ')[0];
    
    // Obtener resultados de la competencia
    fetch('controllers/cjui.php?opera=get_resultados_competencia', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `idcom=${idcom}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.resultados && data.resultados.length > 0) {
            data.resultados.forEach(resultado => {
                const option = document.createElement('option');
                option.value = `${resultado.idres} - ${resultado.nomres}`;
                option.textContent = resultado.nomres;
                resultadoSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Error al cargar resultados:', error);
    });
}

// Función para validar relación antes de guardar
function validarRelacionAntesDeGuardar() {
    const competenciaSelect = document.getElementById('compe');
    const resultadoSelect = document.getElementById('resapr');
    
    const competenciaValue = competenciaSelect.value;
    const resultadoValue = resultadoSelect.value;
    
    if (!competenciaValue || !resultadoValue || 
        competenciaValue === '__OTRO__' || resultadoValue === '__OTRO__') {
        return Promise.resolve(true); // Permitir si hay campos "Otro"
    }
    
    const idcom = competenciaValue.split(' - ')[0];
    const idres = resultadoValue.split(' - ')[0];
    
    return fetch('controllers/cjui.php?opera=validar_relacion', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `idcom=${idcom}&idres=${idres}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.valida) {
            alert('⚠️ El resultado seleccionado no pertenece a la competencia elegida.\n\nPor favor, seleccione un resultado válido para esta competencia.');
            return false;
        }
        return true;
    })
    .catch(error => {
        console.error('Error en validación:', error);
        return true; // Permitir en caso de error
    });
}

// Función para editar calificación rápidamente (ahora usa la sección fija)
function editarCalificacionRapida(idjui, calificacionActual) {
    // Obtener datos del juicio desde la tabla
    const fila = document.querySelector(`tr[data-id="${idjui}"]`);
    if (!fila) {
        alert('Error: No se encontró el juicio seleccionado');
        return;
    }
    
    // Extraer datos de la fila
    const estudiante = fila.cells[1].textContent.trim(); // Nombre del estudiante
    const competencia = fila.cells[2].textContent.trim(); // Competencia
    const resultado = fila.cells[3].textContent.trim(); // Resultado
    
    // Llenar la sección de edición
    document.getElementById('estudiante-edicion').textContent = estudiante;
    document.getElementById('competencia-edicion').textContent = competencia;
    document.getElementById('resultado-edicion').textContent = resultado;
    
    // Actualizar badge de calificación actual
    const badge = document.getElementById('calificacion-actual');
    badge.textContent = calificacionActual;
    badge.className = 'badge ' + getBadgeClass(calificacionActual);
    
    // Actualizar botones
    actualizarBotonesCalificacion(calificacionActual);
    
    // Guardar ID del juicio para uso posterior
    window.juicioEditando = idjui;
    
    // Mostrar la sección de edición
    document.getElementById('area-edicion').style.display = 'block';
    
    // Ocultar mensajes anteriores
    document.getElementById('mensajes-edicion').style.display = 'none';
    
    // Scroll suave hacia la sección de edición
    document.getElementById('area-edicion').scrollIntoView({ behavior: 'smooth' });
}

// Función para actualizar los botones según la calificación actual
function actualizarBotonesCalificacion(calificacionActual) {
    const botones = {
        'APROBADO': document.getElementById('btn-aprobado'),
        'NO APROBADO': document.getElementById('btn-no-aprobado'),
        'POR EVALUAR': document.getElementById('btn-por-evaluar')
    };
    
    Object.keys(botones).forEach(calificacion => {
        const boton = botones[calificacion];
        if (calificacion === calificacionActual) {
            boton.classList.add('disabled');
            boton.title = 'Calificación actual';
        } else {
            boton.classList.remove('disabled');
            boton.title = `Cambiar a ${calificacion}`;
        }
    });
}

// Función para obtener la clase CSS del badge según la calificación
function getBadgeClass(calificacion) {
    const clases = {
        'APROBADO': 'bg-success',
        'NO APROBADO': 'bg-danger',
        'POR EVALUAR': 'bg-warning'
    };
    return clases[calificacion] || 'bg-secondary';
}

// Función para cancelar la edición
function cancelarEdicion() {
    document.getElementById('area-edicion').style.display = 'none';
    window.juicioEditando = null;
}

// Función para cambiar calificación desde la nueva sección
function cambiarCalificacion(nuevaCalificacion) {
    if (!window.juicioEditando) {
        alert('Error: No hay juicio seleccionado para editar');
        return;
    }
    
    if (confirm(`¿Está seguro de cambiar la calificación a "${nuevaCalificacion}"?`)) {
        // Mostrar indicador de carga en el botón presionado
        const boton = event.target;
        const iconoOriginal = boton.innerHTML;
        boton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Actualizando...';
        boton.disabled = true;
        
        // Deshabilitar todos los botones temporalmente
        document.getElementById('btn-aprobado').disabled = true;
        document.getElementById('btn-no-aprobado').disabled = true;
        document.getElementById('btn-por-evaluar').disabled = true;
        
        // Enviar petición
        fetch('controllers/cjui.php?opera=editar_calificacion_rapida', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idjui=${window.juicioEditando}&calificacion=${nuevaCalificacion}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la tabla
                actualizarCalificacionEnTabla(window.juicioEditando, nuevaCalificacion);
                
                // Mostrar mensaje de éxito
                mostrarMensajeExito(data.mensaje);
                
                // Actualizar la sección de edición
                actualizarSeccionEdicion(nuevaCalificacion);
                
                // Ocultar la sección de edición después de un momento
                setTimeout(() => {
                    document.getElementById('area-edicion').style.display = 'none';
                    window.juicioEditando = null;
                }, 2000);
                
            } else {
                mostrarMensajeError(data.error);
                // Restaurar botones
                restaurarBotones(iconoOriginal);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarMensajeError('Error de conexión');
            // Restaurar botones
            restaurarBotones(iconoOriginal);
        });
    }
}

// Función para mostrar mensaje de éxito
function mostrarMensajeExito(mensaje) {
    const areaMensajes = document.getElementById('mensajes-edicion');
    const mensajeExito = document.getElementById('mensaje-exito');
    const mensajeError = document.getElementById('mensaje-error');
    
    // Ocultar mensaje de error si existe
    mensajeError.style.display = 'none';
    
    // Mostrar mensaje de éxito
    document.getElementById('texto-exito').textContent = mensaje;
    mensajeExito.style.display = 'block';
    areaMensajes.style.display = 'block';
}

// Función para mostrar mensaje de error
function mostrarMensajeError(mensaje) {
    const areaMensajes = document.getElementById('mensajes-edicion');
    const mensajeExito = document.getElementById('mensaje-exito');
    const mensajeError = document.getElementById('mensaje-error');
    
    // Ocultar mensaje de éxito si existe
    mensajeExito.style.display = 'none';
    
    // Mostrar mensaje de error
    document.getElementById('texto-error').textContent = mensaje;
    mensajeError.style.display = 'block';
    areaMensajes.style.display = 'block';
}

// Función para actualizar la sección de edición con la nueva calificación
function actualizarSeccionEdicion(nuevaCalificacion) {
    // Actualizar badge
    const badge = document.getElementById('calificacion-actual');
    badge.textContent = nuevaCalificacion;
    badge.className = 'badge ' + getBadgeClass(nuevaCalificacion);
    
    // Actualizar botones
    actualizarBotonesCalificacion(nuevaCalificacion);
}

// Función para restaurar botones después de un error
function restaurarBotones(iconoOriginal) {
    const boton = event.target;
    boton.innerHTML = iconoOriginal;
    boton.disabled = false;
    
    // Habilitar todos los botones
    document.getElementById('btn-aprobado').disabled = false;
    document.getElementById('btn-no-aprobado').disabled = false;
    document.getElementById('btn-por-evaluar').disabled = false;
}

// Función para confirmar cambio de calificación
function confirmarCambioCalificacion(idjui, nuevaCalificacion) {
    if (confirm(`¿Está seguro de cambiar la calificación a "${nuevaCalificacion}"?`)) {
        // Mostrar indicador de carga
        const boton = event.target;
        const iconoOriginal = boton.innerHTML;
        boton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Actualizando...';
        boton.disabled = true;
        
        // Enviar petición
        fetch('controllers/cjui.php?opera=editar_calificacion_rapida', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `idjui=${idjui}&calificacion=${nuevaCalificacion}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la tabla
                actualizarCalificacionEnTabla(idjui, nuevaCalificacion);
                
                // Mostrar mensaje de éxito
                alert('✅ ' + data.mensaje);
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalEdicionRapida'));
                modal.hide();
            } else {
                alert('❌ Error: ' + data.error);
                boton.innerHTML = iconoOriginal;
                boton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Error de conexión');
            boton.innerHTML = iconoOriginal;
            boton.disabled = false;
        });
    }
}

// Función para actualizar calificación en la tabla
function actualizarCalificacionEnTabla(idjui, nuevaCalificacion) {
    const fila = document.querySelector(`tr[data-id="${idjui}"]`);
    if (fila) {
        const celdaCalificacion = fila.querySelector('.celda-calificacion');
        if (celdaCalificacion) {
            const iconos = {
                'APROBADO': 'fa-check',
                'NO APROBADO': 'fa-times',
                'POR EVALUAR': 'fa-hourglass-half'
            };
            
            const estilos = {
                'APROBADO': { color: 'black', fontWeight: 'normal' },
                'NO APROBADO': { color: 'black', fontWeight: 'bold' },
                'POR EVALUAR': { color: '#6c757d', fontWeight: 'normal' }
            };
            
            const estilo = estilos[nuevaCalificacion] || { color: 'black', fontWeight: 'normal' };
            
            celdaCalificacion.innerHTML = `
                <div style="font-weight: ${estilo.fontWeight}; color: ${estilo.color};">
                    <i class="fa-solid ${iconos[nuevaCalificacion]}"></i>
                    ${nuevaCalificacion}
                </div>
            `;
        }
    }
}

// Función para auto-recargar la página después de guardar
function autoRecargar() {
    // Limpiar el formulario
    limpiarFormulario();
    
    // Mostrar mensaje de éxito
    const estadoFormulario = document.getElementById('estado-formulario');
    const mensajeEstado = document.getElementById('mensaje-estado');
    estadoFormulario.style.display = 'block';
    estadoFormulario.className = 'alert alert-success';
    mensajeEstado.innerHTML = '<i class="fa-solid fa-check-circle"></i> Juicio guardado correctamente. El formulario se ha limpiado.';
    
    // Ocultar mensaje después de 3 segundos
    setTimeout(() => {
        estadoFormulario.style.display = 'none';
    }, 3000);
}

// Función para cargar datos automáticamente si hay documento
function cargarDatosAutomaticos() {
    const ndoce = document.getElementById('ndoce').value.trim();
    if (ndoce) {
        console.log('Cargando datos automáticamente para documento:', ndoce);
        buscarEstudiante();
    }
}

// Función para cargar datos en el formulario
function cargarDatosEnFormulario(data) {
    console.log('Cargando datos en formulario:', data);
    
    // Cargar datos del usuario
    if (data.usuario) {
        document.getElementById('nomes').value = data.usuario.nomes || '';
        document.getElementById('apees').value = data.usuario.apees || '';
        console.log('Datos de usuario cargados:', data.usuario);
    }
    
    // Cargar datos del juicio si existe
    if (data.juicio) {
        document.getElementById('idusu').value = data.juicio.idusu || '';
        document.getElementById('idfic').value = data.juicio.idfic || '';
        document.getElementById('estes').value = data.juicio.estes || 'EN FORMACIÓN';
        document.getElementById('caljui').value = data.juicio.caljui || '';
        document.getElementById('compe').value = data.juicio.compe || '';
        document.getElementById('resapr').value = data.juicio.resapr || '';
        
        // Actualizar campo de Ficha con datos del juicio
        if (data.juicio.idfic && data.juicio.nomfic) {
            document.getElementById('ficha_display').value = data.juicio.idfic + ' - ' + data.juicio.nomfic;
        }
        
        console.log('Datos de juicio cargados:', data.juicio);
    }
    
    // El instructor siempre debe mostrar al usuario logueado (no cambiar)
    // Esto se mantiene desde la carga inicial del formulario
    
    // Cargar datos de la ficha si existe
    if (data.ficha) {
        console.log('Datos de ficha disponibles:', data.ficha);
        // La ficha se carga automáticamente en el select
    }
}

// Función para mostrar indicador de carga
function mostrarIndicadorCarga() {
    const estadoFormulario = document.getElementById('estado-formulario');
    const mensajeEstado = document.getElementById('mensaje-estado');
    estadoFormulario.style.display = 'block';
    estadoFormulario.className = 'alert alert-info';
    mensajeEstado.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Cargando datos del estudiante...';
}

// Función para mostrar error
function mostrarError(mensaje) {
    const estadoFormulario = document.getElementById('estado-formulario');
    const mensajeEstado = document.getElementById('mensaje-estado');
    estadoFormulario.style.display = 'block';
    estadoFormulario.className = 'alert alert-warning';
    mensajeEstado.innerHTML = '<i class="fa-solid fa-exclamation-triangle"></i> ' + mensaje;
}

// Función para limpiar formulario después de guardado exitoso
function limpiarFormularioDespuesDeGuardar() {
    // Verificar si hay parámetros de éxito en la URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('msg') === 'created' || urlParams.get('msg') === 'updated') {
        // Limpiar el formulario
        limpiarFormulario();
        
        // Mostrar mensaje de éxito
        const estadoFormulario = document.getElementById('estado-formulario');
        const mensajeEstado = document.getElementById('mensaje-estado');
        estadoFormulario.style.display = 'block';
        estadoFormulario.className = 'alert alert-success';
        mensajeEstado.innerHTML = '<i class="fa-solid fa-check-circle"></i> Juicio guardado correctamente. El formulario se ha limpiado.';
        
        // Ocultar mensaje después de 3 segundos
        setTimeout(() => {
            estadoFormulario.style.display = 'none';
        }, 3000);
        
        // Limpiar la URL para evitar que se ejecute de nuevo
        window.history.replaceState({}, document.title, window.location.pathname + '?pg=2117');
    }
}

// Disparadores
document.addEventListener('DOMContentLoaded', function(){
  // Verificar si se recargó después de guardar
  limpiarFormularioDespuesDeGuardar();
  
  // Cargar datos automáticamente si hay documento
  cargarDatosAutomaticos();
  
  // blur + Enter por comodidad
  const d = document.getElementById('ndoce');
  d.addEventListener('blur', buscarEstudiante);
  d.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); buscarEstudiante(); } });
  
  // Actualizar resultados cuando cambia competencia
  document.getElementById('compe').addEventListener('change', function() {
      actualizarResultadosPorCompetencia();
  });
  
  // Validación antes de enviar - requiere los 3 campos clave
  document.getElementById('frmins').addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Normalizar campos "Otro" antes de validar
      normalizarOtro();
      
      const ndoce = document.getElementById('ndoce').value.trim();
      const compe = document.getElementById('compe').value.trim();
      const resapr = document.getElementById('resapr').value.trim();
      
      if (!ndoce || !compe || !resapr) {
          alert('Debe completar: Número de documento, Competencia y Resultado de aprendizaje.');
          return false;
      }
      
      // Validar relación competencia-resultado antes de enviar
      validarRelacionAntesDeGuardar().then(esValida => {
          if (esValida) {
              // Mostrar indicador de carga
              const estadoFormulario = document.getElementById('estado-formulario');
              const mensajeEstado = document.getElementById('mensaje-estado');
              estadoFormulario.style.display = 'block';
              estadoFormulario.className = 'alert alert-info';
              mensajeEstado.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Guardando juicio...';
              
              // Enviar formulario
              this.submit();
          }
      });
  });
});

// Variables globales para la visualización
let datosJuicios = [];
let datosFiltrados = [];
let paginaActual = 1;
let registrosPorPagina = 10;

// Función para cargar juicios importados
function cargarJuiciosImportados() {
    // Mostrar loading
    $('#tbody_juicios_importados').html('<tr><td colspan="4" class="text-center"><i class="fa-solid fa-spinner fa-spin"></i> Cargando datos...</td></tr>');
    
    // Usar fetch API para obtener datos del endpoint AJAX
    fetch('controllers/cjui.php?opera=get_importados_json')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                datosJuicios = data.data;
                datosFiltrados = [...datosJuicios];
                
                console.log('Datos cargados:', datosJuicios.length, 'registros');
                
                // Poblar filtros iniciales con todos los datos disponibles
                poblarFiltrosIniciales();
                
                // Sincronizar dropdown de registros por página
                $('#registros_por_pagina').val(registrosPorPagina);
                
                // Mostrar datos paginados
                mostrarDatosPaginados();
                
                // Mostrar información del filtro de usuario
                mostrarInfoFiltroUsuario(data);
                
                // Actualizar información del filtro de usuario
                if (data.filtro_usuario && data.filtro_usuario.documento) {
                    $('#info_filtro_usuario p').html(
                        `<strong>🔒 Filtro Automático de Seguridad:</strong> Solo se muestran los juicios evaluados por el funcionario con documento: <strong>${data.filtro_usuario.documento}</strong><br>
                         <small class="text-muted">Por seguridad, no puede acceder a juicios de otros funcionarios.</small>`
                    );
                }
            } else {
                $('#tbody_juicios_importados').html(
                    `<tr><td colspan="4" class="text-center text-danger">
                        <i class="fa-solid fa-exclamation-triangle"></i> Error: ${data.message}
                    </td></tr>`
                );
            }
        })
        .catch(error => {
            console.error('Error al cargar datos:', error);
            $('#tbody_juicios_importados').html(
                `<tr><td colspan="4" class="text-center text-danger">
                    <i class="fa-solid fa-exclamation-triangle"></i> Error al cargar datos: ${error.message}
                </td></tr>`
            );
        });
}

// Función para poblar filtros iniciales
function poblarFiltrosIniciales() {
    console.log('Poblando filtros iniciales...');
    console.log('Datos disponibles:', datosJuicios.length, 'registros');
    
    if (!datosJuicios || datosJuicios.length === 0) {
        console.warn('No hay datos para poblar filtros');
        return;
    }
    
    // Poblar funcionarios (SOLO el del usuario logueado)
    const funcionarios = [...new Set(
        (datosJuicios || []).map(x => x?.funre).filter(Boolean)
    )];
    
    console.log('Funcionarios únicos encontrados:', funcionarios);
    
    // Solo mostrar el funcionario del usuario logueado (seguridad)
    if (funcionarios.length > 0) {
        $('#filtro_funcionario').html('<option value="">Todos los funcionarios</option>');
        funcionarios.forEach(funcionario => {
            $('#filtro_funcionario').append(`<option value="${funcionario}">${funcionario}</option>`);
        });
        
        // Si solo hay un funcionario, seleccionarlo automáticamente
        if (funcionarios.length === 1) {
            $('#filtro_funcionario').val(funcionarios[0]);
            console.log('Funcionario único seleccionado automáticamente:', funcionarios[0]);
        }
    } else {
        $('#filtro_funcionario').html('<option value="">No hay funcionarios disponibles</option>');
    }
    
    // Poblar competencias (todas las disponibles)
    const competencias = [...new Set(
        (datosJuicios || []).map(x => x?.compe).filter(Boolean)
    )];
    
    console.log('Competencias únicas encontradas:', competencias.length);
    
    $('#filtro_competencia').html('<option value="">Todas las competencias</option>');
    competencias.forEach(competencia => {
        // Mostrar competencia completa sin truncamiento para evitar confusión
        $('#filtro_competencia').append(`<option value="${competencia}" title="${competencia}">${competencia}</option>`);
    });
    
    // Poblar estados de estudiante
    const estados = [...new Set(
      (datosJuicios || []).map(x => x?.estado).filter(Boolean)
    )];
    
    console.log('Estados únicos encontrados:', estados);
    
    $('#filtro_estado').html('<option value="">Todos los estados</option>');
    estados.forEach(estado => {
        $('#filtro_estado').append(`<option value="${estado}">${estado}</option>`);
    });
    
    // Poblar calificaciones
    const calificaciones = [...new Set(
      (datosJuicios || []).map(x => x?.caljui).filter(Boolean)
    )];
    
    console.log('Calificaciones únicas encontradas:', calificaciones);
    
    $('#filtro_modalidad').html('<option value="">Todas las calificaciones</option>');
    calificaciones.forEach(calificacion => {
        // Normalizar calificación a mayúsculas para evitar problemas de comparación
        const calificacionNormalizada = calificacion.toUpperCase();
        $('#filtro_modalidad').append(`<option value="${calificacionNormalizada}">${calificacion}</option>`);
    });
}

// Función para actualizar filtros en cascada
function actualizarFiltrosCascada() {
    console.log('Actualizando filtros en cascada...');
    
    // Filtrar funcionarios por ficha
    const fichaSeleccionada = $('#filtro_ficha').val();
    let funcionarios = [];
    
    if (fichaSeleccionada) {
        funcionarios = [...new Set(datosJuicios
            .filter(item => item.idfic === fichaSeleccionada)
            .map(item => item.funre)
            .filter(funre => funre && funre.trim() !== ''))];
    } else {
        funcionarios = [...new Set(datosJuicios
            .map(item => item.funre)
            .filter(funre => funre && funre.trim() !== ''))];
    }
    
    console.log('Funcionarios encontrados:', funcionarios);
    
    // Actualizar dropdown de funcionarios (mantener solo los del usuario logueado)
    if (funcionarios.length > 0) {
        $('#filtro_funcionario').html('<option value="">Todos los funcionarios</option>');
        funcionarios.forEach(funcionario => {
            $('#filtro_funcionario').append(`<option value="${funcionario}">${funcionario}</option>`);
        });
        
        // Si solo hay un funcionario, seleccionarlo automáticamente
        if (funcionarios.length === 1) {
            $('#filtro_funcionario').val(funcionarios[0]);
        }
    } else {
        $('#filtro_funcionario').html('<option value="">No hay funcionarios disponibles</option>');
    }
    
    // Filtrar competencias por ficha y funcionario
    const funcionarioSeleccionado = $('#filtro_funcionario').val();
    let competencias = [];
    
    if (fichaSeleccionada || funcionarioSeleccionado) {
        competencias = [...new Set(datosJuicios
            .filter(item => {
                const matchFicha = !fichaSeleccionada || item.idfic === fichaSeleccionada;
                const matchFuncionario = !funcionarioSeleccionado || item.funre === funcionarioSeleccionado;
                return matchFicha && matchFuncionario;
            })
            .map(item => item.compe)
            .filter(compe => compe && compe.trim() !== ''))];
    } else {
        competencias = [...new Set(datosJuicios
            .map(item => item.compe)
            .filter(compe => compe && compe.trim() !== ''))];
    }
    
    console.log('Competencias encontradas:', competencias);
    
    // Actualizar dropdown de competencias
    $('#filtro_competencia').html('<option value="">Todas las competencias</option>');
    competencias.forEach(competencia => {
        // Mostrar competencia completa sin truncamiento para evitar confusión
        $('#filtro_competencia').append(`<option value="${competencia}" title="${competencia}">${competencia}</option>`);
    });
}

// Función para aplicar filtros con valores específicos (para filtros automáticos)
function aplicarFiltrosConValores(fichaEspecifica, funcionarioEspecifico, competenciaEspecifica, estadoEspecifico, calificacionEspecifica) {
    const ficha = fichaEspecifica !== null ? fichaEspecifica : $('#filtro_ficha').val();
    const funcionario = funcionarioEspecifico !== null ? funcionarioEspecifico : $('#filtro_funcionario').val();
    const competencia = competenciaEspecifica !== null ? competenciaEspecifica : $('#filtro_competencia').val();
    const estado = estadoEspecifico !== null ? estadoEspecifico : $('#filtro_estado').val();
    const calificacion = calificacionEspecifica !== null ? calificacionEspecifica : $('#filtro_modalidad').val();
    const busqueda = $('#filtro_busqueda').val().toLowerCase();
    
    console.log('Aplicando filtros con valores específicos:', { ficha, funcionario, competencia, estado, calificacion, busqueda });
    
    aplicarFiltrosInterno(ficha, funcionario, competencia, estado, calificacion, busqueda);
}

// Función para aplicar filtros (versión original)
function aplicarFiltros() {
    const ficha = $('#filtro_ficha').val();
    const funcionario = $('#filtro_funcionario').val();
    const competencia = $('#filtro_competencia').val();
    const estado = $('#filtro_estado').val();
    const calificacion = $('#filtro_modalidad').val(); // Cambiado de modalidad a calificación
    const busqueda = $('#filtro_busqueda').val().toLowerCase();
    
    console.log('Aplicando filtros:', { ficha, funcionario, competencia, estado, calificacion, busqueda });
    
    aplicarFiltrosInterno(ficha, funcionario, competencia, estado, calificacion, busqueda);
}

// Función interna para aplicar filtros (evita duplicación de código)
function aplicarFiltrosInterno(ficha, funcionario, competencia, estado, calificacion, busqueda) {
    
    datosFiltrados = datosJuicios.filter(item => {
        const matchFicha = !ficha || item.idfic === ficha;
        const matchFuncionario = !funcionario || item.funre === funcionario;
        const matchCompetencia = !competencia || item.compe === competencia;
        const matchEstado = !estado || item.estes === estado; // Cambiado de estfi a estes
        const matchCalificacion = !calificacion || item.caljui.toUpperCase() === calificacion; // Normalizar para comparación
        const matchBusqueda = !busqueda || 
            (item.ndoce && item.ndoce.toLowerCase().includes(busqueda)) ||
            (item.nomes && item.nomes.toLowerCase().includes(busqueda)) ||
            (item.apees && item.apees.toLowerCase().includes(busqueda)) ||
            (item.resapr && item.resapr.toLowerCase().includes(busqueda)) ||
            (item.compe && item.compe.toLowerCase().includes(busqueda)); // Agregado competencia a búsqueda
        
        return matchFicha && matchFuncionario && matchCompetencia && matchEstado && matchCalificacion && matchBusqueda;
    });
    
    console.log('Registros filtrados:', datosFiltrados.length);
    paginaActual = 1;
    mostrarDatosPaginados();
    
    // Mantener los valores seleccionados en los dropdowns después de aplicar filtros
    $('#filtro_ficha').val(ficha);
    $('#filtro_funcionario').val(funcionario);
    $('#filtro_competencia').val(competencia);
    $('#filtro_estado').val(estado);
    $('#filtro_modalidad').val(calificacion);
}

// Función para limpiar filtros
function limpiarFiltros() {
    console.log('Limpiando filtros...');
    
    $('#filtro_ficha').val('');
    $('#filtro_funcionario').val('');
    $('#filtro_competencia').val('');
    $('#filtro_estado').val('');
    $('#filtro_modalidad').val('');
    $('#filtro_busqueda').val('');
    
    datosFiltrados = [...datosJuicios];
    paginaActual = 1;
    actualizarFiltrosCascada(); // Actualizar filtros en cascada después de limpiar
    mostrarDatosPaginados();
    
    console.log('Filtros limpiados, registros totales:', datosFiltrados.length);
}

// Función para mostrar datos paginados
function mostrarDatosPaginados() {
    const inicio = (paginaActual - 1) * registrosPorPagina;
    const fin = inicio + registrosPorPagina;
    const datosPagina = datosFiltrados.slice(inicio, fin);
    
    let html = '';
    
    // Actualizar título de la ficha si hay datos
    if (datosPagina.length > 0) {
        const fichaActual = datosPagina[0].idfic || 'N/A';
        $('#ficha_actual').text(fichaActual);
    }
    
    if (datosPagina.length === 0) {
        html = '<tr><td colspan="4" class="text-center text-muted">No se encontraron resultados</td></tr>';
    } else {
        datosPagina.forEach(item => {
            const isExpanded = item.expanded || false;
            html += `
                <tr class="${isExpanded ? 'table-active' : ''}" data-id="${item.idjui || Math.random()}">
                    <td style="max-width:300px;">
                        <div class="text-break">
                            <strong>${(item.nomes || '') + ' ' + (item.apees || '')}</strong>
                            <br><small class="text-muted">Documento: ${item.ndoce || 'N/A'}</small>
                            <br><small class="text-muted">Estado: ${item.estes || 'N/A'}</small>
                            <br><small class="text-muted">Funcionario: ${item.funre || 'N/A'}</small>
                        </div>
                    </td>
                    <td style="max-width:350px;">
                        <div class="text-break">
                            <div><strong>${item.compe || 'N/A'}</strong></div>
                            <div style="font-size: 0.85em; color: #555; margin-top: 0.5rem;">
                                <em>${item.resapr || 'N/A'}</em>
                            </div>
                        </div>
                    </td>
                    <td class="text-center celda-calificacion" data-idjui="${item.idjui}">
                        <div style="font-weight: ${item.caljui === 'NO APROBADO' ? 'bold' : 'normal'}; color: ${item.caljui === 'APROBADO' ? 'black' : (item.caljui === 'NO APROBADO' ? 'black' : '#6c757d')};">
                            <i class="fa-solid ${item.caljui === 'APROBADO' ? 'fa-check' : (item.caljui === 'NO APROBADO' ? 'fa-times' : 'fa-hourglass-half')}"></i>
                            ${item.caljui || 'N/A'}
                        </div>
                    </td>
                    <td class="col-acciones">
                        <div class="btn-wrap">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarCalificacionRapida(${item.idjui}, '${item.caljui}')" title="Editar calificación">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm" style="background-color: #00af00; border: none; color: white;" onclick="toggleDetalleFila(this)" title="Ver detalles">
                                <i class="fa-solid ${isExpanded ? 'fa-chevron-up' : 'fa-chevron-down'}"></i>
                            </button>
                            <button type="button" class="btn btn-sm" style="background-color: #00af00; border: none; color: white;" onclick="mostrarDetalleCompleto(${JSON.stringify(item).replace(/"/g, '&quot;')})" title="Ver completo">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            
            // Fila expandible con detalles adicionales
            if (isExpanded) {
                html += `
                    <tr class="table-light detalle-fila" data-parent="${item.idjui || Math.random()}">
                        <td colspan="4">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fa-solid fa-file-text text-success"></i> Información del Juicio</h6>
                                    <p><strong>ID Juicio:</strong> ${item.idjui || 'N/A'}</p>
                                    <p><strong>ID Usuario:</strong> ${item.idusu || 'N/A'}</p>
                                    <p><strong>ID Ficha:</strong> ${item.idfic || 'N/A'}</p>
                                    <p><strong>ID Resultado:</strong> ${item.idres || 'N/A'}</p>
                                    <p><strong>Resultado de Aprendizaje:</strong> ${item.resapr || 'N/A'}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fa-solid fa-calendar text-success"></i> Fechas del Juicio</h6>
                                    <p><strong>Fecha Juicio:</strong> ${item.fecjuieva || item.fecju || 'N/A'}</p>
                                    <p><strong>Fecha Reporte:</strong> ${item.fecre || 'N/A'}</p>
                                    <p><strong>Fecha Importación:</strong> ${item.fecim || 'N/A'}</p>
                                    <p><strong>Tipo Documento:</strong> ${item.tidoc || 'N/A'}</p>
                                    <p><strong>Archivo Correlativo:</strong> ${item.arcor || 'N/A'}</p>
                                </div>
                            </div>
                        </td>
                    </tr>`;
            }
        });
    }
    
    $('#tbody_juicios_importados').html(html);
    
    // Generar tarjetas móviles
    generarTarjetasMoviles(datosPagina);
    
    // Actualizar información de paginación
    $('#inicio_registros').text(datosFiltrados.length > 0 ? inicio + 1 : 0);
    $('#fin_registros').text(Math.min(fin, datosFiltrados.length));
    $('#total_registros').text(datosFiltrados.length);
    
    // Generar paginación
    generarPaginacion();
}

// Función para expandir/contraer fila
function toggleDetalleFila(button) {
    const fila = $(button).closest('tr');
    const idFila = fila.data('id');
    const isExpanded = fila.hasClass('table-active');
    
    // Cambiar estado
    if (isExpanded) {
        fila.removeClass('table-active');
        $(button).find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        $(`.detalle-fila[data-parent="${idFila}"]`).remove();
    } else {
        fila.addClass('table-active');
        $(button).find('i').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        
        // Buscar el item correspondiente
        const item = datosFiltrados.find(d => (d.idjui || Math.random()) == idFila);
        if (item) {
            item.expanded = true;
            // La fila expandible se generará en la próxima llamada a mostrarDatosPaginados
            mostrarDatosPaginados();
        }
    }
}

// Función para mostrar detalle completo en modal
function mostrarDetalleCompleto(item) {
    const html = `
        <div class="row">
            <div class="col-md-6">
                <h6><i class="fa-solid fa-user text-success"></i> Información del Estudiante</h6>
                <p><strong>ID Usuario:</strong> ${item.idusu || 'N/A'}</p>
                <p><strong>Nombres:</strong> ${item.nomes || 'N/A'}</p>
                <p><strong>Apellidos:</strong> ${item.apees || 'N/A'}</p>
                <p><strong>Estado:</strong> ${item.estes || 'N/A'}</p>
                <p><strong>Documento:</strong> ${item.tidoc || ''} ${item.ndoce || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <h6><i class="fa-solid fa-file-text text-success"></i> Información del Juicio</h6>
                <p><strong>ID Juicio:</strong> ${item.idjui || 'N/A'}</p>
                <p><strong>ID Ficha:</strong> ${item.idfic || 'N/A'}</p>
                <p><strong>Competencia:</strong> ${item.compe || 'N/A'}</p>
                <p><strong>Calificación:</strong> ${item.caljui || 'N/A'}</p>
                <p><strong>ID Resultado:</strong> ${item.idres || 'N/A'}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h6><i class="fa-solid fa-list-check text-success"></i> Evaluación</h6>
                <p><strong>Resultado Aprendizaje:</strong> ${item.resapr || 'N/A'}</p>
                <p><strong>Fecha Juicio:</strong> ${item.fecju || 'N/A'}</p>
                <p><strong>Funcionario:</strong> ${item.funre || 'N/A'}</p>
                <p><strong>Fecha Reporte:</strong> ${item.fecre || 'N/A'}</p>
            </div>
            <div class="col-md-6">
                <h6><i class="fa-solid fa-calendar text-success"></i> Fechas y Documentos</h6>
                <p><strong>Fecha Importación:</strong> ${item.fecim || 'N/A'}</p>
                <p><strong>Tipo Documento:</strong> ${item.tidoc || 'N/A'}</p>
                <p><strong>Archivo Correlativo:</strong> ${item.arcor || 'N/A'}</p>
            </div>
        </div>
    `;
    
    // Crear modal dinámicamente si no existe
    if (!$('#modalDetalle').length) {
        $('body').append(`
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
                            <h5 class="modal-title" id="modalDetalleLabel">Detalle Completo del Juicio</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" id="modalDetalleBody">
                            <!-- El contenido se cargará dinámicamente -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
        `);
    }
    
    $('#modalDetalleBody').html(html);
    $('#modalDetalle').modal('show');
}

// Función para generar paginación
function generarPaginacion() {
    const totalPaginas = Math.ceil(datosFiltrados.length / registrosPorPagina);
    let html = '';
    
    if (totalPaginas > 1) {
        // Botón anterior
        html += `<li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="cambiarPagina(${paginaActual - 1}); return false;">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        </li>`;
        
        // Números de página
        for (let i = 1; i <= totalPaginas; i++) {
            if (i === 1 || i === totalPaginas || (i >= paginaActual - 2 && i <= paginaActual + 2)) {
                html += `<li class="page-item ${i === paginaActual ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="cambiarPagina(${i}); return false;">${i}</a>
                </li>`;
            } else if (i === paginaActual - 3 || i === paginaActual + 3) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Botón siguiente
        html += `<li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="cambiarPagina(${paginaActual + 1}); return false;">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        </li>`;
    }
    
    $('#paginacion').html(html);
}

// Función para cambiar página
function cambiarPagina(pagina) {
    if (pagina >= 1 && pagina <= Math.ceil(datosFiltrados.length / registrosPorPagina)) {
        paginaActual = pagina;
        mostrarDatosPaginados();
    }
}

// Función para generar tarjetas móviles
function generarTarjetasMoviles(datosPagina) {
    let html = '';
    
    if (datosPagina.length === 0) {
        html = `
            <div class="juicio-card">
                <div class="juicio-header">
                    <div class="juicio-title">No hay juicios disponibles</div>
                </div>
            </div>
        `;
    } else {
        datosPagina.forEach(item => {
            html += `
                <div class="juicio-card">
                    <div class="juicio-header">
                        <div class="juicio-title">
                            ${item.nomes || 'N/A'} ${item.apees || 'N/A'}
                        </div>
                        <div class="juicio-meta">
                            <div class="juicio-meta-item">
                                <strong>Documento:</strong> ${item.ndoce || 'N/A'}
                            </div>
                            <div class="juicio-meta-item">
                                <strong>Estado:</strong> ${item.estes || 'N/A'}
                            </div>
                        </div>
                    </div>
                    
                    <div class="juicio-details">
                        <div class="juicio-detail">
                            <div class="juicio-detail-label">Competencia</div>
                            <div class="juicio-detail-value">${item.compe || 'N/A'}</div>
                        </div>
                        <div class="juicio-detail">
                            <div class="juicio-detail-label">Resultado</div>
                            <div class="juicio-detail-value">${item.resapr || 'N/A'}</div>
                        </div>
                        <div class="juicio-detail">
                            <div class="juicio-detail-label">Calificación</div>
                            <div class="juicio-detail-value">
                                <span class="calificacion-badge">${item.caljui || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="juicio-detail">
                            <div class="juicio-detail-label">Funcionario</div>
                            <div class="juicio-detail-value">${item.funre || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div class="juicio-actions">
                        <a href="#" onclick="mostrarDetalleCompleto(${JSON.stringify(item).replace(/"/g, '&quot;')})" 
                           class="juicio-action" 
                           title="Ver Detalle"
                           data-bs-toggle="modal" 
                           data-bs-target="#modalDetalleCompleto">
                            <i class="fa-solid fa-eye"></i>
                            <span>Ver</span>
                        </a>
                        <a href="#" onclick="editarJuicio(${item.idjui || 0})" 
                           class="juicio-action" 
                           title="Editar">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <span>Editar</span>
                        </a>
                        <a href="#" onclick="eliminarJuicio(${item.idjui || 0})" 
                           class="juicio-action" 
                           title="Eliminar">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Eliminar</span>
                        </a>
                    </div>
                </div>
            `;
        });
    }
    
    $('#mobile-cards-container').html(html);
}

// Función para mostrar información del filtro de usuario
function mostrarInfoFiltroUsuario(response) {
    if (response.filtro_usuario) {
        $('#info_filtro_usuario').show();
    } else {
        $('#info_filtro_usuario').hide();
    }
}

// Funciones de exportación
function exportarCSV() {
    if (datosFiltrados.length === 0) {
        alert('No hay datos para exportar');
        return;
    }
    
    let csv = 'ID Juicio,ID Usuario,ID Ficha,Documento,Tipo Documento,Nombres,Apellidos,Estado,Competencia,ID Resultado,Resultado Aprendizaje,Calificación,Fecha Juicio,Funcionario,Fecha Reporte,Fecha Importación,Archivo Correlativo\n';
    
    datosFiltrados.forEach(item => {
        csv += `"${item.idjui || ''}","${item.idusu || ''}","${item.idfic || ''}","${item.ndoce || ''}","${item.tidoc || ''}","${item.nomes || ''}","${item.apees || ''}","${item.estes || ''}","${item.compe || ''}","${item.idres || ''}","${item.resapr || ''}","${item.caljui || ''}","${item.fecju || ''}","${item.funre || ''}","${item.fecre || ''}","${item.fecim || ''}","${item.arcor || ''}"\n`;
    });
    
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'juicios_importados.csv';
    link.click();
}

function exportarExcel() {
    if (datosFiltrados.length === 0) {
        alert('No hay datos para exportar');
        return;
    }
    
    // Crear tabla HTML para copiar
    let tabla = '<table border="1">';
    tabla += '<tr><th>ID Juicio</th><th>ID Usuario</th><th>ID Ficha</th><th>Documento</th><th>Tipo Documento</th><th>Nombres</th><th>Apellidos</th><th>Estado</th><th>Competencia</th><th>ID Resultado</th><th>Resultado Aprendizaje</th><th>Calificación</th><th>Fecha Juicio</th><th>Funcionario</th><th>Fecha Reporte</th><th>Fecha Importación</th><th>Archivo Correlativo</th></tr>';
    
    datosFiltrados.forEach(item => {
        tabla += `<tr><td>${item.idjui || ''}</td><td>${item.idusu || ''}</td><td>${item.idfic || ''}</td><td>${item.ndoce || ''}</td><td>${item.tidoc || ''}</td><td>${item.nomes || ''}</td><td>${item.apees || ''}</td><td>${item.estes || ''}</td><td>${item.compe || ''}</td><td>${item.idres || ''}</td><td>${item.resapr || ''}</td><td>${item.caljui || ''}</td><td>${item.fecju || ''}</td><td>${item.funre || ''}</td><td>${item.fecre || ''}</td><td>${item.fecim || ''}</td><td>${item.arcor || ''}</td></tr>`;
    });
    
    tabla += '</table>';
    
    // Copiar al portapapeles
    const textArea = document.createElement('textarea');
    textArea.value = tabla;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand('copy');
    document.body.removeChild(textArea);
    
    alert('Tabla copiada al portapapeles. Puedes pegarla en Excel.');
}

$(document).ready(function() {
    // Auto-focus en el primer campo del formulario manual
    $('#idusu').focus();
    
    // Cargar datos iniciales de juicios importados
    cargarJuiciosImportados();
    
    // Event listeners para filtros
    $('#btn_aplicar_filtros').on('click', aplicarFiltros);
    $('#btn_limpiar_filtros').on('click', limpiarFiltros);
    $('#btn_poblar_filtros').on('click', poblarFiltrosIniciales);
    $('#btn_exportar_csv').on('click', exportarCSV);
    $('#btn_exportar_excel').on('click', exportarExcel);
    
    // Event listeners para filtros en cascada que aplican automáticamente
$('#filtro_ficha').on('change', function() {
    const valorFicha = $(this).val();
    console.log('Filtro ficha cambiado:', valorFicha);
    actualizarFiltrosCascada();
    // Aplicar filtros automáticamente después de actualizar cascada
    setTimeout(() => aplicarFiltrosConValores(valorFicha, null, null, null, null), 100);
});

$('#filtro_funcionario').on('change', function() {
    const valorFuncionario = $(this).val();
    console.log('Filtro funcionario cambiado:', valorFuncionario);
    
    // Validación de seguridad: solo permitir funcionarios del usuario logueado
    const funcionariosPermitidos = [...new Set(datosJuicios
        .map(item => item.funre)
        .filter(funre => funre && funre.trim() !== ''))];
    
    if (valorFuncionario && !funcionariosPermitidos.includes(valorFuncionario)) {
        console.warn('Intento de seleccionar funcionario no autorizado:', valorFuncionario);
        alert('Solo puede seleccionar funcionarios autorizados para su usuario.');
        $(this).val(''); // Resetear a valor vacío
        return;
    }
    
    actualizarFiltrosCascada();
    // Aplicar filtros automáticamente después de actualizar cascada
    setTimeout(() => aplicarFiltrosConValores(null, valorFuncionario, null, null, null), 100);
});

// Event listeners para filtros individuales que aplican automáticamente
$('#filtro_competencia').on('change', function() {
    const valorCompetencia = $(this).val();
    console.log('Filtro competencia cambiado:', valorCompetencia);
    aplicarFiltrosConValores(null, null, valorCompetencia, null, null);
});

$('#filtro_estado').on('change', function() {
    const valorEstado = $(this).val();
    console.log('Filtro estado cambiado:', valorEstado);
    aplicarFiltrosConValores(null, null, null, valorEstado, null);
});

$('#filtro_modalidad').on('change', function() {
    const valorCalificacion = $(this).val();
    console.log('Filtro calificación cambiado:', valorCalificacion);
    aplicarFiltrosConValores(null, null, null, null, valorCalificacion);
});
    
    // Event listener para cambio de registros por página
    $('#registros_por_pagina').on('change', function() {
        registrosPorPagina = parseInt($(this).val());
        paginaActual = 1;
        mostrarDatosPaginados();
    });
    
    // Event listener para búsqueda en tiempo real
    $('#filtro_busqueda').on('input', function() {
        // Aplicar filtros después de un pequeño delay para evitar muchas consultas
        clearTimeout(window.busquedaTimeout);
        window.busquedaTimeout = setTimeout(aplicarFiltros, 300);
    });

});
</script>

<style>
/* Estilos para la tabla expandible */
.table-active {
    background-color: #e8f5e8 !important;
    border-left: 4px solid #28a745 !important;
}

.detalle-fila {
    background-color: #f8f9fa !important;
    border-left: 4px solid #28a745 !important;
}

.detalle-fila td {
    padding: 1.5rem !important;
    border-top: none !important;
}

.detalle-fila h6 {
    color: #28a745;
    font-weight: bold;
    margin-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.detalle-fila p {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.detalle-fila strong {
    color: #495057;
    font-weight: 600;
}

/* Mejoras en la tabla principal */
#tabla_juicios_importados th {
    background-color: #00af00 !important;
    color: white !important;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    border: none;
}

#tabla_juicios_importados td {
    vertical-align: middle;
    border: 1px solid #dee2e6;
}

/* Estilos para badges */
.badge {
    font-size: 0.9rem;
    padding: 0.7rem 1.2rem;
    font-weight: 600;
    text-align: center;
    display: inline-block;
    min-width: 120px;
    border-radius: 6px;
    transition: all 0.3s ease;
}

/* Estilos personalizados para los badges de estado y calificación */
.badge.bg-success,
.badge.bg-danger,
.badge.bg-secondary {
    font-size: 0.9rem;
    padding: 0.7rem 1.2rem;
    min-width: 120px;
    text-align: center;
    font-weight: 600;
    border-radius: 6px;
    transition: all 0.3s ease;
}

/* Badge POR EVALUAR - Verde más claro */
.badge.bg-success[style*="opacity: 0.8"] {
    background-color: #00af00 !important;
    opacity: 0.8;
    border: 1px solid #1e7e34;
}

/* Badge EN FORMACION - Verde teal */
.badge.bg-success[style*="background-color: #00af00"] {
    background-color: #00af00 !important;
    border: 1px solid #1ba085;
}

/* Badge APROBADO - Verde estándar */
.badge.bg-success:not([style*="opacity: 0.8"]):not([style*="background-color: #00af00"]) {
    background-color: #00af00 !important;
    border: 1px solid #146c43;
}

/* Estilos específicos para badges de peligro (Cancelado, No Aprobado) */
.badge.bg-danger {
    background-color: #dc3545 !important;
    border: 1px solid #b02a37;
    color: white !important;
}

/* Estilos específicos para badges secundarios (Retiro Voluntario, Trasladado) */
.badge.bg-secondary {
    background-color: #6c757d !important;
    border: 1px solid #5a6268;
    color: white !important;
}

/* Estilos para botones de acción */
.btn-outline-primary, .btn-outline-info, .btn[style*="background-color: #00af00"] {
    border-width: 2px;
    margin: 0 0.25rem;
    transition: all 0.2s ease;
}

/* Tu tabla de juicios */
#tabla_juicios_importados {
  table-layout: fixed;          /* evita "bailes" por textos largos */
  width: 100%;
}

#tabla_juicios_importados th,
#tabla_juicios_importados td {
  vertical-align: middle !important; /* centra verticalmente todo */
}

/* Columna Acciones */
#tabla_juicios_importados th:last-child,
#tabla_juicios_importados td:last-child {
  width: 110px !important;          /* ajusta a lo que necesites */
  white-space: nowrap !important;    /* no partir botones */
  text-align: center !important;
  overflow: visible;
}

/* Contenedor de los botones dentro de la celda */
#tabla_juicios_importados td:last-child .btn-wrap {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: .5rem;                        /* espacio entre botones */
}

/* Evitar floats/absolutos en botones de esa columna */
#tabla_juicios_importados td:last-child .btn-wrap .btn {
  float: none !important;
  position: static !important;
}

/* Columna de Competencia + Resultado - permitir múltiples líneas */
#tabla_juicios_importados td:nth-child(4) { /* Competencia */
  white-space: normal !important;
  text-overflow: initial !important;
  overflow: visible !important;
  min-width: 300px;
  padding: 0.75rem;
}

/* Estilos para el texto de Resultado de Aprendizaje */
#tabla_juicios_importados td:nth-child(4) div:last-child {
  border-top: 1px solid #e9ecef;
  padding-top: 0.5rem;
  margin-top: 0.5rem;
}

/* Centrar badges de Calificación y Estado */
#tabla_juicios_importados td:nth-child(5), /* Calificación */
#tabla_juicios_importados td:nth-child(8) { /* Estado */
  text-align: center;
  vertical-align: middle;
}

.btn-outline-primary:hover, .btn-outline-info:hover, .btn[style*="background-color: #00af00"]:hover {
    transform: scale(1.1);
    background-color: #008000 !important;
    box-shadow: 0 2px 8px rgba(0, 175, 0, 0.3);
}

/* Mejoras en filtros */
.form-select, .form-control {
    border: 2px solid #e9ecef;
    transition: border-color 0.2s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #00af00;
    box-shadow: 0 0 0 0.2rem rgba(0, 175, 0, 0.25);
}

/* Paginación mejorada */
.pagination .page-link {
    color: #00af00;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: #00af00;
    border-color: #00af00;
    color: white;
}

.pagination .page-link:hover {
    color: #1e7e34;
    background-color: #e8f5e8;
    border-color: #00af00;
}

/* Ocultar tarjetas en desktop por defecto */
.mobile-cards {
    display: none;
}

/* Responsividad mejorada */
@media (max-width: 768px) {
    /* Título responsive */
    .tit h1 {
        font-size: 2.2rem;
        text-align: center;
    }
    
    /* Cards responsive */
    .card {
        margin-bottom: 1rem;
    }
    
    .card-header h5 {
        font-size: 1.1rem;
    }
    
    /* Formularios táctiles */
    .form-control, .form-select {
        font-size: 1rem;
        padding: 0.75rem;
        min-height: 48px;
    }
    
    .btn {
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        min-height: 48px;
        margin-bottom: 0.5rem;
    }
    
    /* Ocultar tabla en móvil */
    #tabla_juicios_importados {
        display: none;
    }
    
    /* Diseño de tarjetas para móvil */
    .mobile-cards {
        display: block;
    }
    
    .juicio-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        min-height: 200px;
    }
    
    .juicio-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }
    
    .juicio-header {
        border-bottom: 2px solid #00af00;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }
    
    .juicio-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #00af00;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .juicio-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .juicio-meta-item {
        font-size: 0.8rem;
        color: #6c757d;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        display: inline-block;
    }
    
    .juicio-meta-item strong {
        color: #495057;
    }
    
    .juicio-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .juicio-detail {
        display: flex;
        flex-direction: column;
    }
    
    .juicio-detail-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .juicio-detail-value {
        font-size: 1.1rem;
        color: #495057;
        font-weight: 600;
    }
    
    .juicio-actions {
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }
    
    .juicio-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #6c757d;
        transition: all 0.3s ease;
        padding: 0.5rem;
        border-radius: 8px;
        min-width: 60px;
    }
    
    .juicio-action:hover {
        color: #00af00;
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
    
    .juicio-action i {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    
    .juicio-action span {
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
    }
    
    .calificacion-badge {
        background: #00af00;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    
    /* Detalles de fila */
    .detalle-fila .row {
        flex-direction: column;
    }
    
    .detalle-fila .col-md-6 {
        margin-bottom: 1rem;
    }
    
    /* Modal responsive */
    .modal-dialog {
        margin: 0.5rem;
        max-width: calc(100% - 1rem);
    }
    
    .modal-xl .modal-body {
        max-height: 60vh;
        font-size: 0.9rem;
    }
    
    /* Alertas responsive */
    .alert {
        font-size: 0.95rem;
        padding: 0.75rem;
    }
}

@media (max-width: 576px) {
    /* Título más pequeño */
    .tit h1 {
        font-size: 1.8rem;
    }
    
    /* Tabla más compacta */
    #tabla_juicios_importados {
        min-width: 700px;
        font-size: 0.8rem;
    }
    
    #tabla_juicios_importados th,
    #tabla_juicios_importados td {
        font-size: 0.75rem;
        padding: 0.4rem 0.2rem;
    }
    
    /* Botones más pequeños pero táctiles */
    .btn {
        font-size: 0.95rem;
        padding: 0.6rem 1.2rem;
    }
    
    /* Cards más compactas */
    .card-body {
        padding: 1rem;
    }
    
    /* Modal más compacto */
    .modal-xl .modal-body {
        max-height: 50vh;
        font-size: 0.85rem;
    }
}

@media (max-width: 400px) {
    /* Título extra pequeño */
    .tit h1 {
        font-size: 1.5rem;
    }
    
    /* Tabla muy compacta */
    #tabla_juicios_importados {
        min-width: 600px;
        font-size: 0.75rem;
    }
    
    #tabla_juicios_importados th,
    #tabla_juicios_importados td {
        font-size: 0.7rem;
        padding: 0.3rem 0.1rem;
    }
    
    /* Botones más compactos */
    .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
}

/* Animaciones para expansión */
.detalle-fila {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estilos para el modal de detalle completo */
.modal-xl .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.modal-body h6 {
    color: #28a745;
    font-weight: bold;
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.modal-body h6:first-child {
    margin-top: 0;
}

.modal-body p {
    margin-bottom: 0.75rem;
    line-height: 1.6;
}

.modal-body strong {
    color: #495057;
    font-weight: 600;
    min-width: 120px;
    display: inline-block;
}
</style>
