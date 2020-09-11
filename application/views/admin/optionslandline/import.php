<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Download Sample
if($this->input->post('download_sample') === 'true'){
    
}
?>
<?php $this->load->view('admin/header.php'); ?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        
        <?php $this->load->view('admin/topnavigation.php'); ?>
        
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
        
            <?php $this->load->view('admin/sidebar.php'); ?>
        	
            
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="<?php echo base_url('admin/dashboard');?>"><?php echo lang('bread_home');?></a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?php echo base_url('admin/optionslandline');?>"><?php echo lang('page_optionslandline');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    echo lang('page_import_optionlandline'); 
                                    ?>
                                </span>
                            </li>
                            
                        </ul>
                        
                        <div class="page-toolbar">
                            <div class="btn-group btn-group-devided pull-right">  
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_optionlandline1') );?>
                                
                                    <?php echo form_hidden('download_sample','true'); ?>    
                                    <button type="submit" class="btn sbold green btn-sm"><i class="fa fa-file-excel-o"></i> <?php echo lang('download_sample');?></button>                                                                
                                
                                <?php echo form_close();?>    
                            </div>
                        </div>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->
                    
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"> 
                        <i class="fa fa-file-excel-o"></i>
                        <?php
                        echo lang('page_import_optionlandline');
                        ?>                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    <div class="m-heading-1 border-blue m-bordered">
                        <ul>
                            <li><?php echo lang('import_note1');?></li>                      
                            <li class="text-danger"><?php echo sprintf(lang('import_note3'),lang('page_fl_optiontitle'));?></li>
                        </ul>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-12">
                            <?php                            
                            //print_r($db_fields);
                            ?>
                            <div class="table-responsive no-dt">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                        <?php
                                            $total_fields = 0; 
                                            foreach($db_fields as $field){
                                                if(in_array($field,$not_importable)){continue;}
                                                ?>
                                                <th class="bold <?php if($field == 'optionnr' || $field == 'optiontitle'){ echo "text-danger"; }?>"><?php echo ucfirst($field);?></th>
                                                <?php 
                                                $total_fields++;
                                            } 
                                        ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php for($i = 0; $i<1;$i++){
                                                echo '<tr>';
                                                for($x = 0; $x<$total_fields;$x++){
                                                        echo '<td>'.$sample_data[$x].'</td>';
                                                }
                                                echo '</tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_optionlandline') );?>
                        <?php echo form_hidden('import_csv','true'); ?>
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">                                
                                <div class="portlet-body form">

                                    <div class="form-body">
 
                                        <div class="form-group">
                                            <label><?php echo lang('choose_csv_file');?> <span class="required"> * </span></label>
                                            <?php echo form_upload('file_csv', '', 'class="form-control"');?>
                                        </div>
                                        
                                        <div class="form-actions">
                                            <button type="submit" class="btn blue"><?php echo lang('import');?></button>
                                            <a href="<?php echo base_url('admin/optionslandline')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>
                         
                        <?php echo form_close();?>
                    </div>
                    
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
                        
            
        </div>
        <!-- END CONTAINER -->

<script>
    var form_id = 'form_optionlandline'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    file_csv: {
                        required: true,
                        extension: "csv"
                    },					
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

                submitHandler: function (form) {
					//success1.show();
                    error1.hide();
					App.scrollTo(error1, -200);
					return true;
                }
            });
    }
</script>
        
<?php $this->load->view('admin/footer.php'); ?>        