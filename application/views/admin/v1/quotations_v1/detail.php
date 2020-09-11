<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
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
                                <a href="<?php echo base_url('admin/quotations');?>"><?php echo lang('page_quotations');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_quotation'); 
                                    ?>
                                </span>
                            </li>
                            
                        </ul>
                        
                    </div>
                    <!-- END PAGE BAR -->
                    
                    
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->
                    
                   
                    <div class="row">
                        
                        
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light"  style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
                                <div class="portlet-title" style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
                                    <div class="caption">                                    
                                        <i class="fa fa-file"></i>
                                        <?php
                                        echo lang('page_detail_quotation');
                                        ?>
                                    </div>
                                    
                                    
                                    <div class="actions">
                                        <a href="<?php echo base_url('admin/quotations/printquotation/'.$quotation['quotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_quotation');?></a>

                                        <a href="<?php echo base_url('admin/quotations/printhardwarequotation/'.$quotation['quotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_hardware_quotation');?></a>

                                        <a href="<?php echo base_url('admin/quotations/printconsultationprotocol/'.$quotation['quotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_consultation_protocol');?></a>

                                        <a href="<?php echo base_url('admin/quotations/printinvoiceprotocol/'.$quotation['quotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_invoice_protocol');?></a>

                                        <?php
                                        if (total_rows('tblassignments', array('quotationid' => $quotation['quotationnr']))) {
                                        }
                                        else if($GLOBALS['quotationtoassignment_permission']['create']){   
                                        ?>
                                            <div class="btn-group btn-group-devided" data-toggle="buttons">                                                    
                                                <a href="javascript:void(0);" onclick="FormAjax('<?php echo base_url('admin/quotations/addAssignment/'.$quotation['quotationnr']);?>','<?php echo base_url('admin/quotations/getQuotation/'.$quotation['quotationnr']);?>','<?php echo lang('page_lb_create_a_assignment');?>','assignment');" class="btn sbold green btn-sm"> <i class="fa fa-plus"></i> <?php echo lang('page_lb_create_a_assignment');?></a>
                                            </div>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                        if($GLOBALS['quotation_permission']['edit']){
                                            ?>
                                            <a href="<?php echo base_url('admin/quotations/quotation/'.$quotation['quotationnr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_quotation');?></a>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    
                                </div>                            
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                   
                    
                        <?php
                        //Only Editable 
                        $tab_document = '';
                        $tab_reminder = '';
                        if(empty($quotation['quotationnr'])){
                           $tab_document = 'none';
                           $tab_reminder = 'none';
                        }					
                        ?>

                    
                    
                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_quotation') );?>
                        
                        
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_document;?>">
                                <a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_document');?></a>
                            </li>
                            <li style="display:<?php echo $tab_reminder;?>">
                                <a href="#tab_reminder" data-toggle="tab"><?php echo lang('page_lb_reminder');?></a>
                            </li>
                        </ul>
                        
                        
                        <div class="tab-content">
                            
                            <div class="tab-pane active" id="tab_profile">
                                        
                                <div class="col-md-6">
                            
                            
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                    
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?>:</label>
                                                    <?php echo $quotation['company'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quotationdate');?>:</label>
                                                    <?php echo _d($quotation['quotationdate']);?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quotationstatus');?>:</label>
                                                    <?php echo $quotation['quotationstatusname'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quotationprovider');?>:</label>
                                                    <?php echo $quotation['providercompanynr'];?>
                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                           
                                </div>
                                
                                
                                <div class="col-md-6">
                                    
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                            
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_customer');?>:</label>
                                                    <?php echo $quotation['customer'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $quotation['responsible'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?>:</label>
                                                    <?php echo $quotation['recommend'];?>
                                                </div>
                                                
                                            </div>    
                                            
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                    
                                </div>
                                
                                
                                
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                            
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_currentdiscountlevel');?>:</label>
                                                        <?php echo $quotation['currentdiscountlevel'];?>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_newdiscountlevel');?>:</label>
                                                        <?php echo $quotation['newdiscountlevel'];?>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                            
                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_quotationproducts');?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    
                                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                                                        <thead>
                                                            <tr role="row" class="heading">                                                                                                        
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_productenterform');?></th>
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_mobilenr');?></th>                                                    
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_vvl_neu');?></th>
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_currentratemobile');?></th>
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>                                                    
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_use');?></th>
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_newratemobile');?></th>
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>                                                    
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_endofcontract');?></th>
                                                                <th class="text-nowrap text-center"><?php echo lang('page_fl_hardware');?></th>
                                                            </tr>                                                
                                                        </thead>   
                                                        <tbody id="quotationproduct_inputbox">
                                                        <?php
                                                        if(isset($quotationproducts) && count($quotationproducts)>0){
                                                            foreach($quotationproducts as $pkey=>$quotationproduct){
                                                                ?>
                                                                <!-- ROW -->
                                                                <tr id="row1_old_quotationproduct_<?php echo $quotationproduct['id'];?>">
                                                                    <td class="text-nowrap text-center"><?php echo $quotationproduct['formula']=='M'?lang('page_lb_manual'):lang('page_lb_auto');?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['mobilenr'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['vvlneu'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['currentratemobile'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['value1'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['use'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['newratemobile'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['value2'];?></td>
                                                                    <td class="text-center"><?php echo _d($quotationproduct['endofcontract']);?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['hardware'];?></td>
                                                                </tr>
                                                                <tr id="row2_old_quotationproduct_<?php echo $quotationproduct['id'];?>">
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['currentoptionmobile'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['value3'];?></td>
                                                                    <td></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['newoptionmobile'];?></td>
                                                                    <td class="text-center"><?php echo $quotationproduct['value4'];?></td>
                                                                    <td colspan="2">
                                                                        <?php
                                                                        if($quotationproduct['activationdate']!="" && $quotationproduct['activationdate']!="0000-00-00"){
                                                                            ?>
                                                                            <table>
                                                                                <tr>
                                                                                    <td><label><?php echo lang('page_fl_activationdate');?>: </label>
                                                                                    <?php echo _d($quotationproduct['activationdate']);?></td>
                                                                                </tr>
                                                                            </table>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        
                                                                        <div style="display:none"><?php if($quotationproduct['formula']=='A'){ echo lang('page_fl_fqty'.$quotationproduct['simcard_function_id']).': '.$quotationproduct['simcard_function_qty']; }?></div>
                                                                    </td>
                                                                </tr>
                                                                <!-- END ROW -->                                                                                                                                
                                                                <?php
                                                                
                                                            }
                                                        }
                                                        ?>
                                                        </tbody>                                                                
                                                    </table>    
                                                   
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                    
                                </div>
                        
                            </div>
                            
                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">
                                
                                <?php 
                                $this->load->view('admin/quotations/tab-document', array('quotation'=>$quotation,'categories'=>$categories)); 
                                ?>
                                
                            </div>
                            
                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">
                                
                                <?php 
                                $this->load->view('admin/quotations/tab-reminder', array('quotation'=>$quotation)); 
                                ?>
                                
                            </div>
                        </div>      
                            
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered"> 
                                <?php
                                $this->load->view('admin/quotations/tab-comment', array('quotation'=>$quotation));
                                ?>
                            </div> 
                            <!-- END SAMPLE FORM PORTLET-->                            
                        </div>      
                        
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light">                                
                                <div class="portlet-body">
                                    <div class="form-body">
                                        <div class="form-actions">
                                            <!--<button type="submit" class="btn blue"><?php echo lang('save');?></button>-->
                                            <a href="<?php echo base_url('admin/quotations')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>                

                        
                        <?php //echo form_close();?>
                    </div>
                    
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
                        
            
        </div>
        <!-- END CONTAINER -->
<?php $this->load->view('admin/footer.php'); ?>        
<?php $this->load->view('admin/quotations/quotationjs',array('quotation'=>$quotation, 'remindersubjects'=>$remindersubjects));?>