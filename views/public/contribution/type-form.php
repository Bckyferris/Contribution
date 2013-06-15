<?php 

//Separate out the contribution form fields and the contributor information form fields

$this->itemTypeForm = '';
$this->profileForm = '';


foreach($type->getTypeElements() as $typeElement){
	$elementId = $typeElement->element_id;
	$elementTable = get_db()->getTable('Element');
	$setElements = $elementTable->findBySet('Contributor Information');	
}

$contributorElementIds = array();
foreach($setElements as $setElement){
	$contributorElementIds[] = $setElement->id;	
}

foreach ($type->getTypeElements() as $contributionTypeElement) {
    if(in_array( $contributionTypeElement->element_id, $contributorElementIds)) {
        $profileForm .= $this->elementForm($contributionTypeElement->Element, $item, array('contributionTypeElement'=>$contributionTypeElement)); 
    } else {
        $itemTypeForm .= $this->elementForm($contributionTypeElement->Element, $item, array('contributionTypeElement'=>$contributionTypeElement));        
    }
}


?>


<form method="post" id="contribution-form" action="" enctype="multipart/form-data">
			<div id="form-container">
            <div id="bx-pager">
              <a data-slide-index="0" href="">Your Contribution </a>
              <img src="plugins/Contribution/views/public/css/images/arrow.png"/>
              <a data-slide-index="1" href="">Where</a>
               <img src="plugins/Contribution/views/public/css/images/arrow.png"/>
               <a data-slide-index="2" href="">When</a>
                <img src="plugins/Contribution/views/public/css/images/arrow.png"/>
              <a data-slide-index="3" href="">About You</a>
               <img src="plugins/Contribution/views/public/css/images/arrow.png"/>
              <a data-slide-index="4" href="">Share</a>
            </div>
            
            <input type='hidden' name='contribution_type' value='1' />
            
            <ul class="bxslider">
                
                <li>
                    <div class="form">
                        <?php if (!$type): ?>
						<p>You must choose a contribution type to continue.</p>
						<?php else: ?>
						<h2>Contribute Your <?php echo $type->display_name;?></h2>
                        <?php echo $itemTypeForm ?>
                        <?php if ($type->isFileRequired()): $required = true;endif;?>
                        <div class="field">
        				<?php echo $this->formLabel('contributed_file', 'Upload a file'); ?>
       				    <?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
						</div>

						<?php endif; ?>

						<?php if (!isset($required) && $type->isFileAllowed()):?>

						<div id="file" class="field">
       					<?php echo $this->formLabel('contributed_file', 'Upload a file (Optional)'); ?>
        				<?php echo $this->formFile('contributed_file', array('class' => 'fileinput')); ?>
</div>
						<?php endif; ?>

						<?php $user = current_user(); ?>
						<?php if(get_option('contribution_simple') && !current_user()) : ?>
						<div class="field">
   						<?php echo $this->formLabel('contribution_simple_email', 'Email (Required)'); ?>
    					<?php echo $this->formText('contribution_simple_email'); ?>
</div>
						<?php endif; ?>
                    </div>
                </li>
                

                <li>
                    <div class="form">
                        <h2>Where</h2>
                            <?php echo get_specific_plugin_hook_output('Geolocation', 'contribution_type_form', array('view' => $this, 'item' => $item, 'type'=>$type) ); ?>
                            <!-- figure out how the calendar will work :) , and do the same thing!   -->				</div>

                </li>
                
                 <li>
                    <div class="form" >
                        <h2>When</h2>
                       <!-- figure out how the calendar will work :) , and do the same thing! -->
                       
                   </div>

                </li>
                                
                
                <li>
                    <div class="form">
                        <h2>About You</h2>
                        <?php echo $profileForm ?>
                    </div>
                </li>
             <li>
                    <div class="form">
                        <h2>Share</h2>
                        <fieldset id="contribution-confirm-submit" <?php if (!isset($type)) { echo 'style="display: none;"'; }?>>
                        <?php if(get_option('contribution_simple') && !current_user()) : ?>
                            <div class="field">
                                <?php echo $this->formLabel('contribution_simple_email', 'Email (Required)'); ?>
                                <?php echo $this->formText('contribution_simple_email'); ?>
                            </div>
                        <?php endif; ?>
                            <div class="inputs">
                                <?php $public = isset($_POST['contribution-public']) ? $_POST['contribution-public'] : 0; ?>
                                <?php echo $this->formCheckbox('contribution-public', $public, null, array('1', '0')); ?>
                                <?php echo $this->formLabel('contribution-public', 'Publish my contribution on the web.'); ?>
                            </div>
                            <div class="inputs">
                                <?php $anonymous = isset($_POST['contribution-anonymous']) ? $_POST['contribution-anonymous'] : 0; ?>
                                <?php echo $this->formCheckbox('contribution-anonymous', $anonymous, null, array(1, 0)); ?>
                                <?php echo $this->formLabel('contribution-anonymous', "Contribute anonymously."); ?>
                            </div>
                            <p>In order to contribute, you must read and agree to the <a href="<?php echo contribution_contribute_url('terms') ?>" target="_blank">Terms and Conditions.</a></p>
                            <div class="inputs" id="terms">
                                <?php $agree = isset( $_POST['terms-agree']) ?  $_POST['terms-agree'] : 0 ?>
                                <?php echo $this->formCheckbox('terms-agree', $agree, null, array('1', '0')); ?>
                                <?php echo $this->formLabel('terms-agree', 'I agree to the Terms and Conditions.'); ?>
                            </div>
                        </fieldset>
                    <?php echo $this->formSubmit('form-submit', 'Contribute', array('class' => 'submitinput')); ?>                           
                    </div>
                </li>
            </ul>
            </div>
        </form>
        
 <script>
$(document).ready(function(){
jQuery('.bxslider').bxSlider({
  infiniteLoop: false,
  hideControlOnEnd: true,
  pagerCustom: '#bx-pager'
});
});

$("#form-submit").click(function(event){
	
	if ($('#terms-agree').is(':checked')){
		return true;
	}
	else{
		event.preventDefault();
		$('#term-warning').remove();
		$('#terms').append("<p id='term-warning' >*** You must agree to the terms and conditions to submit your contribution ***</p>");
	};
});
</script>
                







