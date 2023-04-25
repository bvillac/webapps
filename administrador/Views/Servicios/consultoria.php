<?php echo tiendaHeader($data); ?>

<script>
	//Agregar la clase header-v4 al header solo en estas vista
	document.querySelector('header').classList.add('header-v4');
</script>

<!-- Title page -->
<section class="bg-img1 txt-center p-lr-15 p-tb-92" style="background-image: url('<?= media(); ?>/tienda/images/bg-03.jpg');">
	<h2 class="ltext-105 cl0 txt-center">
		Consultoría y proyectos
	</h2>
</section>


<!-- Content page -->
<section class="bg0 p-t-75 p-b-120">
	<div class="container">
		<div class="row">
			<div class="col-md-7 col-lg-8">
				<div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
					<h3 class="mtext-111 cl2 p-b-16">
						Te Asesoramos 
					</h3>

					<p class="stext-113 cl6 p-b-26">
					Para mejorar siempre hay que conocerse a uno mismo, lo mismo sucede con las empresas, nosotros nos encargaremos de conocer en profundidad sus servicios y sus necesidades, porque solo así podremos ofrecerle las mejor solución informática.
					</p>

					<p class="stext-113 cl6 p-b-26">
					El proceso de consultoría busca someter a examen los procesos e identificar las irregularidades que puedan existir, con el fin de proporcionar las herramientas adecuadas para optimizarlos. 
					</p>

					<p class="stext-113 cl6 p-b-26">
					Después de realizar un análisis exhaustivo de la situación de cada negocio se debe elaborar un plan de acción, una hoja de ruta que nos permita intervenir en base a los resultados del diagnóstico. 
					</p>

					<p class="stext-113 cl6 p-b-26">
					El proyecto resultante de todo este análisis será el que ofrezcamos a su empresa antes de comenzar a trabajar juntos.
					</p>


				</div>
			</div>

			<div class="col-11 col-md-5 col-lg-4 m-lr-auto">
				<div class="how-bor1 ">
					<div class="hov-img0">
						<img src="<?= media(); ?>/tienda/images/about-04.jpg" alt="IMG">
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
										<img src="<?= media(); ?>/tienda/images/Servicio-04.png" alt="IMG" class="centerServicio"  style="width: 50px;" >									
								</div>
								<div class="p-t-15 vc_align_center">									
									<h4 class="p-b-12">
										<a href="#" class="mtext-101 cl2 hov-cl1 trans-04">
										Consultoría informática
										</a>
									</h4>
									<p class="stext-108 cl6">
									Siempre realizaremos una auditoría completa de los equipos y las necesidades de tu negocio para que la implantación sea la adecuada para aumentar la productividad.
									</p>
								</div>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-4 p-b-40">
							<div class="blog-item">
								<div class="hov-img0" >									
										<img src="<?= media(); ?>/tienda/images/Servicio-05.png" alt="IMG" class="centerServicio"  style="width: 50px;" >									
								</div>
								<div class="p-t-15 vc_align_center">									
									<h4 class="p-b-12">
										<a href="#" class="mtext-101 cl2 hov-cl1 trans-04">
										Integración
										</a>
									</h4>
									<p class="stext-108 cl6">
									Pasaremos a ser un departamento más en tu empresa, ocupándonos de todos los temas que estén relacionados con el soporte informático.
									</p>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-4 p-b-40">
							<div class="blog-item">
								<div class="hov-img0" >									
										<img src="<?= media(); ?>/tienda/images/Servicio-06.png" alt="IMG" class="centerServicio"  style="width: 50px;" >									
								</div>
								<div class="p-t-15 vc_align_center">									
									<h4 class="p-b-12">
										<a href="#" class="mtext-101 cl2 hov-cl1 trans-04">
										Ciberseguridad
										</a>
									</h4>
									<p class="stext-108 cl6">
									Disponemos de un departamento especializado que se encargará de proteger lo más valioso en tu empresa, tus datos.
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