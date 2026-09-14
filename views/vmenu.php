<?php include('controllers/cmenu.php'); ?>
<div class="menu--btn-spt-men">
	<i class="fa-solid fa-bars"></i>
</div>
<nav class="main-menu">
	<div class="branding">
		<?php if ($val): ?>
			<img src="img/<?= $val[0]['logcof'] ?>" alt="Logo" class="branding-logo">
			<?php
			$partes = explode(" ", $val[0]['titcof']);
			$pal1 = isset($partes[0]) ? $partes[0] : '';
			$pal2 = isset($partes[1]) ? $partes[1] : '';
			?>
			<div class="branding-title">
				<span class="palabra1"><?= $pal1 ?></span>
				<span class="palabra2"><?= $pal2 ?></span>
			</div>
		<?php endif; ?>
	</div>

	<div class="scrollbar" id="style-1">
		<ul>
			<li>
				<a href="mod.php">
					<i class="fa fa-solid fa-home" style="top:10px;"></i>
					<span class="nav-text">Módulos</span>
				</a>
			</li>
			<?php
			if ($dat) {
				foreach ($dat as $dt) {
					?>
					<li class="darkerli">
						<a href="home.php?pg=<?= $dt['idpag']; ?>">
							<i class="<?= $dt['icopag']; ?>" style="top:10px;"></i>
							<span class="nav-text"><?= $dt['nompag']; ?></span>
						</a>
					</li>
					<?php
				}
			}
			?>
			<li class="logout">
				<a href="views/vsal.php">
					<i class="fa fa-solid fa-power-off" style="top:10px;"></i>
					<span class="nav-text">Salir</span>
				</a>
			</li>
		</ul>



	</div>
</nav>

<script>
	let btnMenu = document.querySelector('.menu--btn-spt-men')
	btnMenu.addEventListener('click', () => {
		let mainMen = document.querySelector('.main-menu');
		mainMen.classList.toggle('menu-despleg')
		mainMen.classList.toggle('expanded')
		btnMenu.classList.toggle('menu-btn-style')
	})
</script>