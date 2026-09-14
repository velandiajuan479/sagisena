<form id="login1" tabindex="500" action="models/control.php" method="POST">
	<h3>Ingreso</h3>

	<div class="mail">
		<input type="text" name="usu" required>
		<label>Usuario</label>
	</div>

	<div class="passwd">
		<input type="password" name="con" required>
		<label>Contraseña</label>
	</div>

	<!-- Mensaje aquí, cerca de los inputs -->
	<div id="mensaje-error" class="error-login"></div>

	<div class="submit">
		<button class="dark">Ingreso</button>
	</div>

	<div class="mail">
		<a href="#" onclick="ingreso(3);">¿Olvidó su contraseña?</a>
	</div>

	<?php
		$error = isset($_GET['error']) ? $_GET['error'] : NULL;
		if ($error == "ok") {
			echo "<script>
				document.addEventListener('DOMContentLoaded', function () {
					// Mostrar ventana de login
					if (typeof ingreso === 'function') {
						ingreso(1);
					}
					// Mostrar mensaje
					const errorDiv = document.getElementById('mensaje-error');
					if (errorDiv) {
						errorDiv.textContent = 'Datos inválidos. Vuelve a intentarlo.';
						errorDiv.style.display = 'block';
					}
				});
			</script>";
		}
	?>
</form>