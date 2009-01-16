<?php
$this->addExternalJS("templates/js/jquery-1.2.6.js");
$this->addExternalJS("templates/js/ui.core.js");
$this->addExternalJS("templates/js/wz_jsgraphics.js");
$this->addExternalJS("templates/js/im.previewCanvasClass.js");
$this->addExternalJS("templates/js/js.inheritance.js");
$this->addExternalJS("templates/js/im.areaClass.js");
$this->addExternalJS("templates/js/im.areaRectClass.js");
$this->addExternalJS("templates/js/im.areaCircleClass.js");
$this->addExternalJS("templates/js/im.areaPolyClass.js");


$existingFields = $this->data->listAreas("\tcanvasObject.addArea(new area##shape##Class(),'##coords##','##alt##','##link##','##color##',0);\n");

$this->addInlineJS('
jQuery.noConflict();

function imagemapwizard_valueChanged(field) {  
    jQuery.ajaxSetup({
        url: "'.$this->getAjaxURL('wizard.php').'",
        global: false,
        type: "GET",
        success: function(data, textStatus) { 
            if(textStatus==\'success\') {
                /*alert(data);*/
                jQuery("#'.$this->getId().'").html(data);
            }
        },
        data: { context:"formAjax", 
                formField:field.name,
                value:field.value, 
                table:"'.$this->data->getTablename().'", 
                field:"'.$this->data->getFieldname().'", 
                uid:"'.$this->data->getUid().'"
        }
    });
    jQuery.ajax();    
}
');
$additionalWizardConf = array('fieldChangeFunc'=>array('imagemapwizard_valueChanged(field);'));
?>
<div id="<?php echo $this->getId(); ?>">
    
    <?php
        ob_start();
    ?>
    <div class="imagemap_wiz" style="padding:5px;overflow:hidden">
        <div id="<?php echo $this->getId(); ?>-canvas" style="position:relative">
        <?php
            echo $this->data->renderThumbnail('previewImageMaxWH',200);
        ?>
        </div>
    </div>
    <?php
        $imagepreview = ob_get_contents();
        ob_end_clean();
        echo $this->form->renderWizards(array($imagepreview,''),$this->wizardConf,$this->data->getTablename(),$this->data->getRow(),$this->data->getFieldname(),$additionalWizardConf,$this->formName,array(),1)
    ?>
    
    <?php
        if($this->data->hasDirtyState()) {
            echo '<div class="imagemap_wiz_message" style="width:180px;padding:4px;border:2px solid #ff6666;background:#ffdddd;">';
            $this->getLL('form.is_dirty',1);
            echo '</div>';
        }
    ?>
    <script type="text/javascript">

    jQuery(document).ready(function(){
        canvasObject = new previewCanvasClass();
        canvasObject.init("<?php echo $this->getId(); ?>-canvas","<?php echo $this->data->getThumbnailScale('previewImageMaxWH',200) ?>");
        <?php echo $existingFields; ?>
        jQuery(".imagemap_wiz_message").fadeOut(4000);
    });

    </script>
    <?php echo $this->form->getSingleHiddenField($this->data->getTablename(),$this->data->getFieldname(),$this->data->getRow()); ?>
</div>