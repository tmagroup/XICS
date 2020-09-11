<!-- BEGIN PAGE MESSAGE-->
<?php $this->load->view('admin/alerts_modal'); ?>
<!-- BEGIN PAGE MESSAGE-->

<table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
    <thead>
        <tr role="row" class="heading">                                                                                                        
            <th class="text-nowrap"><?php echo lang('page_fl_shippingnr');?></th>
            <th class="text-nowrap"><?php echo lang('page_dt_tracking');?></th>                                                    
        </tr>    
    </thead>   
    <tbody>
        <?php
        if(isset($shippingslipproducts) && count($shippingslipproducts)>0){
            foreach($shippingslipproducts as $pkey=>$shippingslipproduct){
                ?>
                <tr>
                    <td><?php echo $shippingslipproduct['shippingnr'];?></td>
                    <td></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>

<div class="clearfix"></div>
