<?php
$alertclass = "";
if($this->session->flashdata('message-success')){
$alertclass = "success";
} else if ($this->session->flashdata('message-warning')){
$alertclass = "warning";
} else if ($this->session->flashdata('message-info')){
$alertclass = "info";
} else if ($this->session->flashdata('message-danger')){
$alertclass = "danger";
}
if($this->session->flashdata('message-'.$alertclass)){ 
	?>    
	<div class="alert alert-<?php echo $alertclass;?>">
		<button class="close" data-close="alert"></button>
		<span><?php echo $this->session->flashdata('message-'.$alertclass); ?></span>
	</div>
	<?php 
} 
?>

<div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span><?php echo lang('admin_auth_validation_error'); ?></span>
</div>