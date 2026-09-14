<footer class="footer-sena">
    <div class="row">
        <!-- Logo -->
        <div class="col-3 col-md-1 bx-footer-logo">
            <a href="https://www.sena.edu.co">
                <img src="img/logoSenaB.png" alt="Logo SENA" class="footer-logo">
            </a>
        </div>

        <!-- Información del centro -->
        <div class="col-8 col-lg inf-cen">
            <p><?php if($val) echo $val[0]['foocof']?></p>
            <p>Dirección: Vereda Bojacá, Carrera 11, Sector El Darién, Lote 1 - Chía, Cundinamarca</p>
            <p>Tel: (601) 8844545</p>
            <p>Correo: <a class="corr-sn" href="mailto:serviciociudadano@sena.edu.co">serviciociudadano@sena.edu.co</a>
            </p>
        </div>
        <!-- Creditos -->
        <div class="col-12 col-lg-4 footer-textos">
            <p>Desarrollado por aprendices SENA</p>
            <p>Fichas 2773071, 2773186, 2996491, 2996494 Tecnólogo en Análisis y Desarrollo de Software.</p>
            <p>Fichas 2773096 Tecnólogo en Desarrollo de Medios Gráficos Visuales.</p>
            
            <p>Lider de Equipo: Ing. Robinson Rincón</p>
            <p>Versión <?php if($val) echo $val[0]['versoft']?> | Última actualización: <?php if($val) echo $val[0]['actsoft']?></p>

        </div>
        <!-- Redes sociales -->
        <div class="col-12 col-lg-3">
            <div class="footer-redes">
                <a href="https://www.facebook.com/senachiacda/" class="btn-red-social" title="Facebook"><i
                        class="fa-brands fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/senacundinamarca/" class="btn-red-social" title="Instagram"><i
                        class="fa-brands fa-instagram"></i></a>
                <a href="https://cdachia.blogspot.com/" class="btn-red-social" title="Blog Sena"><i
                        class="fa-solid fa-blog"></i></a>
                <a href="https://www.sena.edu.co/es-co/Paginas/default.aspx/" class="btn-red-social"
                    title="Pagina Principal Sena"><i class="fa-solid fa-house"></i></a>
            </div>
            <p class="copy-r">© 2025 Todos los derechos reservados</p>
        </div>
    </div>
</footer>
<script>
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
</script>