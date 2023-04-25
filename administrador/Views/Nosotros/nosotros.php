<?php

/*tiendaHeader($data);
//$banner = media()."/tienda/images/bg-01.jpg";
$banner = $data['page']['portada'];
$idpagina = $data['page']['idpost'];
?>

<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url(<?= $banner ?>);">
	<h2 class="ltext-105 cl0 txt-center">
		<?= $data['page']['titulo'] ?>
	</h2>
</section>
<!-- Content page -->
<?php
if (viewPage($idpagina)) {
	echo $data['page']['contenido'];
} else {
?>
	<div>
		<div class="container-fluid py-5 text-center">
			<img src="<?= media() ?>/images/construction.png" alt="En construcción">
			<h3>Estamos trabajando para usted.</h3>
		</div>
	</div>
<?php
}

tiendaFooter($data);*/
?>
<?php echo tiendaHeader($data); ?>

	<script>
		//Agregar la clase header-v4 al header solo en estas vista
		document.querySelector('header').classList.add('header-v4');
	</script>

	<!-- Title page -->
	<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= media(); ?>/tienda/images/bg-01.jpg');">
		<h2 class="ltext-105 cl0 txt-center">
			Nosotros
		</h2>
	</section>	


	<!-- Content page -->
	<section class="bg0 p-t-75 p-b-120">
		<div class="container">
			<div class="row p-b-148">
				<div class="col-md-7 col-lg-8">
					<div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
						<h3 class="mtext-111 cl2 p-b-16">
							¿QUIÉNES SOMOS?
						</h3>

						<p class="stext-113 cl6 p-b-26">
						Somos una empresa de tecnología, desde 2005 ofrecemos nuestra experiencia como proveedores de servicios informáticos, desarrollando software a la medida, mejorando e integrando plataformas tecnológicas para diferentes entidades del sector público y privado.
						</p>

						<p class="stext-113 cl6 p-b-26">
						A través de nuestra trayectoria nos hemos preocupado por alcanzar certificaciones, lo cual avala nuestra capacidad y ratifica el compromiso con nuestros clientes de ser los mejores.
						</p>

						<p class="stext-113 cl6 p-b-26">
						Dentro de nuestro portafolio contamos con el sistema SEA la cual es una herramienta utilizada por algunas empresas para la gestión de sus actividades financieras.
						</p>

						<p class="stext-113 cl6 p-b-26">
						Contamos con nuestro laboratorio de soluciones de testing cuyo propósito es diseñar y construir soluciones de Pruebas de Software centradas en el usuario. Nos preocupamos por mejorar las prácticas de Testing y apoyar la construcción de software de alta calidad.
						</p>
					</div>
				</div>

				<div class="col-11 col-md-5 col-lg-4 m-lr-auto">
					<div class="how-bor1 ">
						<div class="hov-img0">
							<img src="<?= media(); ?>/tienda/images/about-01.jpg" alt="IMG">
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="order-md-2 col-md-7 col-lg-8 p-b-30">
					<div class="p-t-7 p-l-85 p-l-15-lg p-l-0-md">
						<h3 class="mtext-111 cl2 p-b-16">
							Nuestra Misión
						</h3>

						<p class="stext-113 cl6 p-b-26">
						Queremos acompañar el proceso de transformación digital de las empresas, ayudándoles a entender, planear, diseñar e implementar las soluciones tecnológicas que necesitan para asegurar su competitividad y sostenibilidad en el tiempo. 
						</p>

						<div class="bor16 p-l-29 p-b-9 m-t-22">
							<p class="stext-114 cl6 p-r-40 p-b-11">
							La creatividad es simplemente conectar cosas. Cuando le preguntas a las personas creativas cómo hicieron algo, se sienten un poco culpables porque en realidad no lo hicieron, solo vieron algo. Les pareció obvio después de un rato.
							</p>

							<span class="stext-111 cl8">
								- Steve Job’s 
							</span>
						</div>
					</div>
				</div>

				<div class="order-md-1 col-11 col-md-5 col-lg-4 m-lr-auto p-b-30">
					<div class="how-bor2">
						<div class="hov-img0">
							<img src="<?= media(); ?>/tienda/images/about-02.jpg" alt="IMG">
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	

	<?php echo tiendaFooter($data); ?>