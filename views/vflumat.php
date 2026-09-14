<?php

if (!isset($current_action) || $current_action === '') {
    $current_action = 'list_flows';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Flujos y Pasos</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body style="background: #fff;">
    <div class="container mt-5">
        <h1 class="text-center text-success mb-4" style="font-size:2.5rem; font-weight:bold;">
            Gestión de Flujos y Pasos de Matrícula
        </h1>

        <!-- Errores de formulario (si existen) -->
        <?php if (isset($form_errors) && !empty($form_errors)): ?>
            <div class="bg-red-100 text-red-800 p-3 mb-4 rounded-md text-sm font-medium">
                <p>Por favor, corrige los siguientes errores:</p>
                <ul class="list-disc list-inside mt-1">
                    <?php foreach ($form_errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Contenido principal basado en la acción actual -->
        <?php if ($current_action === 'list_flows'): ?>
            <!-- Sección para listar flujos -->
            <div class="mb-3 text-end">
                <a href="?action=create_flow" class="btn btn-success">
                    <i class="fa fa-plus"></i> Crear Nuevo Flujo
                </a>
            </div>
            <table class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Flujo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($flujos)): ?>
                        <?php foreach ($flujos as $flujo): ?>
                            <tr>
                                <td>
                                    <big><strong><?= htmlspecialchars($flujo['idflu']) ?> - <?= htmlspecialchars($flujo['nomflu']) ?></strong></big><br>
                                    Activo: <?= $flujo['fluacti'] ? '<span class="text-success">Sí</span>' : '<span class="text-danger">No</span>' ?>
                                </td>
                                <td>
                                    <a href="?action=list_steps&idflu=<?= htmlspecialchars($flujo['idflu']) ?>" title="Ver Pasos">
                                        <i class="fa-solid fa-list fa-2x"></i>
                                    </a>
                                    <a href="?action=edit_flow&idflu=<?= htmlspecialchars($flujo['idflu']) ?>" title="Editar">
                                        <i class="fa-solid fa-pen-to-square fa-2x"></i>
                                    </a>
                                    <form method="post" action="?action=delete_flow&idflu=<?= htmlspecialchars($flujo['idflu']) ?>" class="d-inline" onsubmit="return confirm('¿Eliminar este flujo?');" style="display:inline;">
                                        <button type="submit" title="Eliminar" style="border:none; background:none; padding:0;">
                                            <i class="fa-solid fa-trash-can fa-2x text-danger"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center text-muted">No hay flujos definidos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Flujo</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

        <?php elseif ($current_action === 'list_steps'): ?>
            <!-- Sección para listar pasos de un flujo específico -->
            <?php if ($flujo): ?>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Pasos para el Flujo: <span class="text-blue-600"><?= htmlspecialchars($flujo['nomflu']) ?></span></h2>
                <div class="mb-6 flex justify-between items-center">
                    <a href="?action=list_flows" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-200">
                        &#x2190; Volver a Flujos
                    </a>
                    <a href="?action=create_step&idflu=<?= htmlspecialchars($flujo['idflu']) ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-200">
                        Añadir Nuevo Paso
                    </a>
                </div>

                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-5 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tl-lg">ID Paso</th>
                                <th class="px-5 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descripción del Paso</th>
                                <th class="px-5 py-3 border-b-2 border-gray-300 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider rounded-tr-lg">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pasos)): ?>
                                <?php foreach ($pasos as $paso): ?>
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-5 py-5 text-sm text-gray-900"><?= htmlspecialchars($paso['idpas']) ?></td>
                                        <td class="px-5 py-5 text-sm text-gray-900"><?= htmlspecialchars($paso['descpas']) ?></td>
                                        <td class="px-5 py-5 text-sm text-center">
                                            <a href="?action=edit_step&idpaso=<?= htmlspecialchars($paso['idpas']) ?>" class="icon-btn text-yellow-500" title="Editar Paso">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zm-3.646 3.646a2 2 0 01.314-.314L15.286 1.714a2 2 0 012.828 2.828L13.793 10.793a2 2 0 01-.314.314L8.293 15.293a1 1 0 01-.707.293H6v-2.293a1 1 0 01.293-.707l4.353-4.353z" />
                                                </svg>
                                            </a>
                                            <form method="post" action="?action=delete_step&idpaso=<?= htmlspecialchars($paso['idpas']) ?>" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este paso?');">
                                                <button type="submit" class="icon-btn text-red-500" title="Eliminar Paso">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 11-2 0v6a1 1 0 112 0V8z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="px-5 py-5 text-sm text-gray-600 text-center">No hay pasos definidos para este flujo.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-red-500 text-center text-lg">Error: Flujo no encontrado.</p>
                <div class="mt-6 text-center">
                    <a href="?action=list_flows" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-200">
                        Volver a la lista de flujos
                    </a>
                </div>
            <?php endif; ?>

        <?php elseif ($current_action === 'create_flow' || $current_action === 'edit_flow'): ?>
            <!-- Sección para formulario de Crear/Editar Flujo -->
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                <?= $current_action === 'create_flow' ? 'Crear Nuevo Flujo' : 'Editar Flujo' ?>
            </h2>

            <form method="post" class="mb-3">
                <div class="form-group">
                    <label for="nombre_flujo">Nombre del Flujo:</label>
                    <input type="text" class="form-control" id="nombre_flujo" name="nombre_flujo"
                           value="<?= htmlspecialchars($flujo['nomflu'] ?? '') ?>"
                           required>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="activo" name="activo"
                           <?= (isset($flujo['fluacti']) && $flujo['fluacti']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>
                <button type="submit" class="btn btn-primary">
                    <?= $current_action === 'create_flow' ? 'Crear Flujo' : 'Guardar Cambios' ?>
                </button>
                <a href="?action=list_flows" class="btn btn-secondary">Cancelar</a>
            </form>

        <?php elseif ($current_action === 'create_step' || $current_action === 'edit_step'): ?>
            <!-- Sección para formulario de Crear/Editar Paso -->
            <?php if ($flujo): ?>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">
                    <?= $current_action === 'create_step' ? 'Añadir Nuevo Paso' : 'Editar Paso' ?> para Flujo: <span class="text-blue-600"><?= htmlspecialchars($flujo['nomflu']) ?></span>
                </h2>

                <form method="post" action="?action=<?= $current_action ?><?= isset($paso['idpas']) ? '&idpaso=' . htmlspecialchars($paso['idpas']) : '&idflu=' . htmlspecialchars($flujo['idflu']) ?>" class="space-y-4">
                    <div>
                        <label for="descripcion_paso" class="block text-sm font-medium text-gray-700">Descripción del Paso:</label>
                        <textarea id="descripcion_paso" name="descripcion_paso" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  required><?= htmlspecialchars($paso['descpas'] ?? '') ?></textarea>
                        <?php if (isset($form_errors['descripcion_paso'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($form_errors['descripcion_paso']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-200">
                            <?= $current_action === 'create_step' ? 'Crear Paso' : 'Guardar Cambios' ?>
                        </button>
                        <a href="?action=list_steps&idflu=<?= htmlspecialchars($flujo['idflu']) ?>" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-200">
                            Cancelar
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-red-500 text-center text-lg">Error: Flujo para el paso no encontrado.</p>
                <div class="mt-6 text-center">
                    <a href="?action=list_flows" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-200">
                        Volver a la lista de flujos
                    </a>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</body>
</html>
