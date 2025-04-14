<style>
    .titleLabel{
        /*font-size:7pt;*/
        /*color:#000;*/
        font-weight: bold ;
    }
    #div-noti-table{

    }
    .trow-noti-tr{
        padding:12px 0 12px 12px;
        /*background: #F9F4F4;*/
        width:100%;
        margin-top:15px;
        margin-bottom:15px;
        border:1px solid #CCCCCC;
    }
    .trow-right-tr{
        padding:12px;
        /*background: #F9F4F4;*/
        float: right;
        /*width:100%;*/
        margin-top:15px;
        margin-bottom:15px;
        border:1px solid #CCCCCC;
    }
    .trow-noti-left{
        /*padding:5px 0 5px 17px;border-left:4px solid #CCCCCC;*/
        padding:5px 0 5px 17px;border-left:4px solid #CCCCCC;
    }
    .line-noti{
        padding-bottom:10px;
        border-style:none none solid none;
        border-bottom-width:1px;
        border-bottom-color:#E4002B;
    }
    .trow-titulo{
        /*background-color: #CCCCCC;*/
        padding:10px 0 10px 0px;
    }

    .sub-title{
        font-size:13pt;
        color:#787878;
        font-weight: bold ;
        margin-top:8px;
    }

    .sub-title-mensaje{
        /*color: rgb(77, 77, 77); */
        color:#787878;
        font-size: 14pt; 
        /*font-family: sans-serif, serif, EmojiFont; */
        font-weight: bold;
    }

    .estado-pedido{
        font-size:15px;vertical-align:top;margin-right:5px;
    }


</style>


<?php
if (sizeof($CabPed) > 0) {
    $tipoMensaje="";
    switch ($Estado) {
        case "R":
            $tipoMensaje= "realizado";
            break;
        case "A":
            $tipoMensaje= "autorizado";
            break;
        case "F":
            $tipoMensaje= "facturado";
            break;
    }
    ?>





    <div id="div-noti-table">
        <div class="trow-titulo">
            <?php //echo CHtml::image(Yii::app()->theme->baseUrl . '/images/plantilla/logo.png', 'Utimpor', array('width' => '200px', 'height' => '50px'));  ?>
            <?php //echo CHtml::image('https://190.111.83.186'.Yii::app()->theme->baseUrl . '/images/plantilla/logo.png', 'Utimpor', array('width' => '200px', 'height' => '50px')); ?>

            <a href="http://pedidos.utimpor.com/" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" title="Utimpor.com">
                <img data-imagetype="External" src="http://www.utimpor.com/images/utimporImg/Logo2.jpg" originalsrc="http://www.utimpor.com/images/utimporImg/Logo2.jpg" width="200px" height="50px" alt="Utimpor" title="Utimpor" border="0">
            </a>
        </div>
        
        <div class="trow">
            <h3 class="sub-title" style="font-size:13pt;color:#787878;font-weight: bold;margin-top:8px;">pedidos.utimpor.com</h3>
        </div>

        <div class="trow">
            <div class="sub-title-mensaje" style="font-size:13pt;color:#787878;font-weight:bold;margin-top:8px;">            
                <?php echo $TituloData; ?>
            </div>
            <p>
                <label class="titleLabel" style="font-weight: bold;">Estimad@:</label><br>  <?php echo $CabPed[0]["NombreUser"] ?> <br> 
            </p>

            <!--<span style="color:#4D4D4D;font-size:20px;font-weight:400;">Hola <strong>Lorena </strong>.</span><br>-->
            <div style="color:#666666;font-size:17px;padding-top:5px;padding-bottom:5px;">
                Gracias por su compra, A continuación encuentre los detalles de su pedido <?php echo $tipoMensaje ?>:
            </div>
        </div>


        <div class="trow-noti-left" style="padding:5px 0 5px 17px;border-left:4px solid #CCCCCC;">
            <p>
                <label class="titleLabel" style="font-weight: bold;"><?php echo Yii::t('TIENDA', 'Cliente') ?> : </label>
                <span><?php echo Yii::app()->getSession()->get('CliNom', FALSE) ?></span><br>
                <label class="titleLabel" style="font-weight: bold;"><?php echo Yii::t('TIENDA', 'Pedido Num') ?> : </label>
                <span><?php echo $CabPed[0]["Numero"] ?></span><br>
                <label class="titleLabel" style="font-weight: bold;"><?php echo Yii::t('TIENDA', 'Store name') ?> : </label>
                <span><?php echo $CabPed[0]["NombreTienda"] ?></span><br>
                <label class="titleLabel" style="font-weight: bold;"><?php echo Yii::t('TIENDA', 'User order') ?> : </label>
                <span><?php echo $CabPed[0]["NombrePersona"] ?></span><br>
                <label class="titleLabel" style="font-weight: bold;"><?php echo Yii::t('TIENDA', 'Issue date') ?> : </label>
                <span><?php echo $CabPed[0]["FechaPedido"] ?></span>
            </p>

        </div>
        <br>
        <div class="trow-noti-left" style="padding:5px 0 5px 17px;border-left:4px solid #CCCCCC;">
            <p>
                Atentamente,<br>
                <label class="titleLabel" style="font-weight: bold;">Utimpor S.A.</label>
            </p>
        </div>
        

        <div class="line-noti" style="padding-bottom:10px;border-style:none none solid none;border-bottom-width:1px;border-bottom-color:#E4002B;"></div>
        <div class="trow-noti-tr" style="padding:12px 0 12px 12px;width:100%;margin-top:15px;margin-bottom:15px;border:1px solid #CCCCCC;">
            <div class="tcol-td">
                <th scope="row" style="color:#BCBCBC;font-size:15px;text-align:left;padding-left:10px;float:left;">
                    <?php if ($Estado == 'R') { ?>
                        <p style="color:#52BC00;"><strong class="estado-pedido">✓</strong>PEDIDO REALIZADO</p>
                    <?php } else { ?>
                        <p><strong class="estado-pedido">►</strong>PEDIDO REALIZADO</p>
                    <?php } ?>
                    <?php if ($Estado == "A") { ?>
                        <p style="color:#52BC00;"><strong class="estado-pedido">✓</strong>PEDIDO AUTORIZADO</p>
                    <?php } else { ?>
                        <p><strong class="estado-pedido">►</strong>PEDIDO AUTORIZADO</p>
                    <?php } ?>
                    <?php if ($Estado == "F") { ?>
                        <p style="color:#52BC00;"><strong class="estado-pedido">✓</strong>PEDIDO FACTURADO</p>
                    <?php } else { ?>
                        <p><strong class="estado-pedido">►</strong>PEDIDO FACTURADO</p>
                    <?php } ?>
                <!--<p><strong class="estado-pedido">►</strong>PEDIDO ENVIADO</p>-->
                </th>
            </div>
        </div>
        <div class="trow-right-tr" style="padding:12px 0 12px 12px;margin-top:15px;margin-bottom:15px;border:1px solid #CCCCCC;">
            <div class="tcol-td">
                <label class="titleLabel"><?php echo Yii::t('TIENDA', 'Total $') ?> : </label>
                <span><?php echo Yii::app()->format->formatNumber($CabPed[0]["ValorNeto"]) ?></span>
            </div>
        </div>

    </div>

<?php } ?>