<?php echo tiendaHeader($data); ?>

<script>
	//Agregar la clase header-v4 al header solo en estas vista
	document.querySelector('header').classList.add('header-v4');
</script>

<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= media(); ?>/tienda/images/bg-03.jpg');">
	<h2 class="ltext-105 cl0 txt-center">
		Soporte y Mantenimiento
	</h2>
</section>


<!-- Content page -->
<section class="bg0 p-t-75 p-b-120">
	<div class="container">
		<div class="row">
			<div class="col-md-7 col-lg-8">
				<div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
					<h3 class="mtext-111 cl2 p-b-16">
						Servicio Técnico
					</h3>

					<p class="stext-113 cl6 p-b-26">
						Mantenga sus equipamientos en perfecto estado en todo momento gracias a un servicio de soporte y mantenimiento continuado que le permita mejorar la productividad.
					</p>

					<p class="stext-113 cl6 p-b-26">
						No nos dedicamos solo a la reparación de ordenadores y servidores, sino que nuestro soporte y mantenimiento informático es integral, formamos parte de su empresa para asesorarle y darle el mejor soporte tanto de software como de hardware en cada momento, con asistencia inmediata y poniendo a su disposición los mejores profesionales.
					</p>

					<p class="stext-113 cl6 p-b-26">
					Buscamos la satisfacción de nuestros clientes y el aumento de su rendimiento por lo que nuestra metodología de trabajo es exigente en todas las fases de nuestra colaboración con su empresa, así como con la formación de su personal, asegurando así que el rendimiento y la productividad mejoren.
					</p>


				</div>
			</div>

			<div class="col-11 col-md-5 col-lg-4 m-lr-auto">
				<div class="how-bor1 ">
					<div class="hov-img0">
						<img src="<?= media(); ?>/tienda/images/about-03.jpg" alt="IMG">
					</div>
				</div>
			</div>
		</div>
		<br><br><br><br>

		<div class="row">
			<!-- Product -->
			<section class="bg0 p-t-23 ">
				<div class="container">

					<div class="row">
						<div class="col-sm-6 col-md-4 p-b-40">
							<div class="blog-item">
								<div class="hov-img0" >									
										<img src="<?= media(); ?>/tienda/images/Servicio-01.png" alt="IMG" class="centerServicio"  style="width: 50px;" >									
								</div>
								<div class="p-t-15 vc_align_center">									
									<h4 class="p-b-12">
										<a href="#" class="mtext-101 cl2 hov-cl1 trans-04">
											Horas de asistencia <br>flexibles
										</a>
									</h4>
									<p class="stext-108 cl6">
									Nuestro objetivo es el mantenimiento informático constante de todos tus equipos, sin que el tiempo empleado para llevarlo a cabo en cada caso suponga ningún problema, ajustándolo al contrato anual de servicios.
									</p>
								</div>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-4 p-b-40">
							<div class="blog-item">
								<div class="hov-img0" >									
										<img src="<?= media(); ?>/tienda/images/Servicio-02.png" alt="IMG" class="centerServicio"  style="width: 50px;" >									
								</div>
								<div class="p-t-15 vc_align_center">									
									<h4 class="p-b-12">
										<a href="#" class="mtext-101 cl2 hov-cl1 trans-04">
										Mantenimiento a <br> domicilio
										</a>
									</h4>
									<p class="stext-108 cl6">
									Trabajamos siempre el mantenimiento de forma preventiva, pero si la avería se llega a producir estaremos ahí en el menor tiempo posible.
									</p>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-4 p-b-40">
							<div class="blog-item">
								<div class="hov-img0" >									
										<img src="<?= media(); ?>/tienda/images/Servicio-03.png" alt="IMG" class="centerServicio"  style="width: 50px;" >									
								</div>
								<div class="p-t-15 vc_align_center">									
									<h4 class="p-b-12">
										<a href="#" class="mtext-101 cl2 hov-cl1 trans-04">
										Atención telefónica
										</a>
									</h4>
									<p class="stext-108 cl6">
									Disponemos de profesionales que estarán a tu disposición para cualquier incidencia que pueda surgir en tu negocio.
									</p>
								</div>
							</div>
						</div>

					</div>

				</div>
			</section>

		</div>

	</div>
</section>

<section class="bg-img1 txt-center p-lr-15 p-tb-120" style="background-image: url('<?= media(); ?>/tienda/images/ServicioPie-01.jpg');">
	<h2 class="ltext-105 cl0 txt-center">
		Nuestra experiencia a <br>nivel técnico a su servicio
	</h2>
	<br>
	<a href="<?= WHATSAPP ?>" target="_blanck" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 btnContacto">
		Contáctanos &nbsp;&nbsp;  <i class="fa fa-whatsapp"></i>
	</a>
	
</section>

<?php echo tiendaFooter($data); ?>