<?php
$allowMoreOptionMobile = false;
if($GLOBALS['a_moreoptionmobile_permission']['create']){
    $allowMoreOptionMobile = true;
}

foreach($assignmentproducts as $pkey=>$assignmentproduct){

    $finished = (isset($assignmentproduct['finished']) && $assignmentproduct['finished']==1)?true:false;
    ?>
    <!-- ROW -->
    <tr id="row1_old_assignmentproduct_<?php echo $pkey;?>">
        <td class="text-center">
            <?php
            if($pkey==(count($assignmentproducts)-1)){
                                                                            //if($pkey==0){
                ?>
                <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addassignmentproduct" datarow="<?php echo $assignmentproduct['id'];?>" datatype="old" datainit="0"><i class="fa fa-plus"></i></a>
                <?php
            }
            else{
                ?>
                <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deleteassignmentproduct('<?php echo $assignmentproduct['id'];?>','old','<?php echo $pkey;?>')"><i class="fa fa-minus"></i></a>
                <?php
            }

            $formula = $assignmentproduct['formula']?$assignmentproduct['formula']:'A';
            if ($assignmentproduct['id']) {
                echo form_hidden('assignmentproductid['.$pkey.']', $assignmentproduct['id']);
                $data_hidden = array('type'=>'hidden', 'name'=>'old_formula_'.$pkey, 'value'=>$assignmentproduct['formula']);

            } else {
                $data_hidden = array('type'=>'hidden', 'name'=>'new_formula_'.$pkey, 'value'=>$formula);
            }
            echo form_input($data_hidden);
            ?>
        </td>
        <td><?php echo form_input('simnr['.$pkey.']', $assignmentproduct['simnr'], 'class="form-control noerror"');?></td>
        <td style="display: block; width: 150px; "><?php echo form_input('mobilenr['.$pkey.']', $assignmentproduct['mobilenr'], 'class="form-control"');?></td>
        <td><?php echo form_input('employee['.$pkey.']', $assignmentproduct['employee'], 'class="form-control noerror"');?></td>
        <td><?php echo form_dropdown('vvlneu['.$pkey.']', $vvlneu, $assignmentproduct['vvlneu'], 'class="form-control vvlneu" datarow="'.$pkey.'" datatype="old" ');?></td>
        <td id="old_newratemobile_box_<?php echo $assignmentproduct['id'];?>">
            <?php if($formula=='A'){ ?>
                <select name="newratemobile[<?= $pkey?>]" class="form-control newratemobile" id="old_newratemobile_<?= $pkey?>" datarow="<?= $pkey?>" datatype="old">
                    <option value=""><?= lang('page_option_select')?></option>
                    <?php foreach ($mobilerates as $key => $value): ?>
                        <option value="<?= $value['ratenr']?>" <?php (isset($assignmentproduct['newratemobile']) && $assignmentproduct['newratemobile'] == $value['ratenr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['ratetitle']?></option>
                    <?php endforeach ?>
                </select>
            <?php } else {
                echo form_input('newratemobile['.$pkey.']', $assignmentproduct['newratemobile'], 'class="form-control" id="old_newratemobile_'.$pkey.'" ');
            }?>
        </td>
        <td><?php echo form_input('value2['.$pkey.']', $assignmentproduct['value2'], 'class="form-control" id="old_value2_'.$pkey.'" ');?></td>

        <td class="text-center">
            <?php
            $extemtedterm = (isset($assignmentproduct['extemtedterm']) && $assignmentproduct['extemtedterm']==1)?true:false;
            $dc = array('name'=>'extemtedterm['.$pkey.']','class'=>'form-control','checked'=>$extemtedterm, 'value'=>1);
            echo form_checkbox($dc);?>
        </td>

        <?php
        if($GLOBALS['a_subscriptionlock2_permission']['create']){
            ?>
            <td class="text-center">
                <?php
                $subscriptionlock = (isset($assignmentproduct['subscriptionlock']) && $assignmentproduct['subscriptionlock']==1)?true:false;
                $dc = array('name'=>'subscriptionlock['.$pkey.']','class'=>'form-control','checked'=>$subscriptionlock, 'value'=>1);
                echo form_checkbox($dc);?>
            </td>
            <?php
        }
        ?>

        <td id="old_newoptionmobile_box_<?php echo $pkey;?>">
            <?php if($formula=='A'){ ?>
                <select name="newoptionmobile[<?= $pkey?>]" class="form-control newoptionmobile noerror" id="old_newoptionmobile_<?= $pkey?>" datarow="<?= $pkey?>" datatype="old">
                    <option value=""><?= lang('page_option_select')?></option>
                    <?php foreach ($mobileoptions as $key => $value): ?>
                        <option value="<?= $value['optionnr']?>" <?php (isset($assignmentproduct['newoptionmobile']) && $assignmentproduct['newoptionmobile'] == $value['optionnr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['optiontitle']?></option>
                    <?php endforeach ?>
                </select>

            <?php } else {
                echo form_input('newoptionmobile['.$pkey.']', $assignmentproduct['newoptionmobile'], 'class="form-control noerror" id="old_newoptionmobile_'.$pkey.'" ');
            }?>

            <!-- More Option -->
            <?php
            if($allowMoreOptionMobile){
                $rowMoreOptionMobiles = $this->Assignmentproductmoreoptionmobile_model->get("","",array(),"assignmentnr='".$assignment['assignmentnr']."' AND assignmentproductid='".$assignmentproduct['id']."'");
                $data_hidden = array('type'=>'hidden', 'id'=>'count_moreoptionmobile_'.$pkey, 'value'=>isset($rowMoreOptionMobiles)?count($rowMoreOptionMobiles):1);
                echo form_input($data_hidden);
            }
            ?>
            <!-- End More Option -->

            <?php
            if($allowMoreOptionMobile){
                if(isset($rowMoreOptionMobiles) && count($rowMoreOptionMobiles)>0){
                    foreach($rowMoreOptionMobiles as $kOpt=>$rowMoreOptionMobile){
                        ?>
                        <div id="div_more_old_newoptionmobile_<?php echo $pkey;?>_<?php echo $kOpt;?>" class="row">
                            <div>
                                <div class="form-group">
                                    <label class="col-md-1 control-label"><a href="javascript:void(0);" id="more_old_close_<?php echo $pkey;?>_<?php echo $kOpt;?>" onclick="row_deletemoreoptionmobile('<?php echo $pkey;?>','<?php echo $kOpt;?>','old');"><i class="fa fa-close"></i></a></label>
                                    <div class="col-md-9">
                                        <select name="more_newoptionmobile[<?= $pkey?>][<?= $kOpt?>]" class="form-control more_newoptionmobile noerror" id="more_old_newoptionmobile_<?= $pkey?>_<?= $kOpt?>" datamainrow="<?= $pkey?>" datarow="<?= $kOpt?>" datatype="old">
                                            <option value=""><?= lang('page_option_select')?></option>
                                            <?php foreach ($mobileoptions as $key => $value): ?>
                                                <option value="<?= $value['optionnr']?>" <?php (isset($rowMoreOptionMobile['newoptionmobile']) && $rowMoreOptionMobile['newoptionmobile'] == $value['optionnr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['optiontitle']?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </td>
        <td id="old_newoptionmobile_box_value_<?php echo $pkey;?>">
            <?php echo form_input('value4['.$pkey.']', $assignmentproduct['value4'], 'class="form-control noerror" id="old_value4_'.$pkey.'" ');?>

            <?php
            if($allowMoreOptionMobile){
                if(isset($rowMoreOptionMobiles) && count($rowMoreOptionMobiles)>0){
                    foreach($rowMoreOptionMobiles as $kOpt=>$rowMoreOptionMobile){
                        ?>
                        <div id="div_more_old_value4_<?php echo $pkey;?>_<?php echo $kOpt;?>" class="row">
                            <div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <?php
                                        echo form_input(array('name'=>'more_value4['.$pkey.']['.$kOpt.']','type'=>'number'), isset($rowMoreOptionMobile['value4'])?$rowMoreOptionMobile['value4']:'', 'class="form-control noerror" id="more_old_value4_'.$pkey.'_'.$kOpt.'" datamainrow="'.$pkey.'" datarow="'.$kOpt.'" datatype="old" ');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>

        </td>
        <td><?php
        if($assignmentproduct['hardwarecheck']==1){
            echo form_dropdown('hardware['.$pkey.']', $hardwares, $assignmentproduct['hardware'], 'class="form-control assignment_hardware noerror" datarow="'.$pkey.'" id="old_hardware_'.$pkey.'" ');
        }
        else{
            echo form_dropdown('hardware['.$pkey.']', $hardwares, $assignmentproduct['hardware'], 'class="form-control assignment_hardware noerror" datarow="'.$pkey.'" id="old_hardware_'.$pkey.'" ');
        }
        ?></td>

        <td class="text-center">
            <?php
            $cardstatus = (isset($assignmentproduct['cardstatus']) && $assignmentproduct['cardstatus']==1)?true:false;
            $dc = array('name'=>'cardstatus['.$pkey.']','class'=>'form-control','checked'=>$cardstatus, 'value'=>1);
            echo form_checkbox($dc);?>
        </td>

        <td>
            <?php
            if(!$finished){
                ?>
                <div id="old_form_date_<?php echo $pkey;?>" class="input-group date form_date">
                    <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control noerror', 'readonly'=>true, 'size'=>16, 'value'=> _d($assignmentproduct['endofcontract']));
                    echo form_input($dd);?>

                    <span class="input-group-btn">
                        <button class="btn default date-set" type="button">
                            <i class="fa fa-calendar"></i>
                        </button>
                    </span>
                </div>
                <?php
            }
            else{
                ?>
                <div>
                    <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control noerror', 'readonly'=>true, 'size'=>16, 'value'=> _d($assignmentproduct['endofcontract']));
                    echo form_input($dd);?>
                </div>
                <?php
            }
            ?>

            <?php
            $simcard_function_id = isset($assignmentproduct['simcard_function_id'])?$assignmentproduct['simcard_function_id']:'0';
            $simcard_function_nm = isset($assignmentproduct['simcard_function_nm'])?$assignmentproduct['simcard_function_nm']:'0';
            $simcard_function_qty = isset($assignmentproduct['simcard_function_qty'])?$assignmentproduct['simcard_function_qty']:'0';
            ?>
            <div id="old_simcard_function_<?php echo $pkey;?>"><input type="hidden" name="simcard_function_id[<?php echo $pkey;?>]" value="<?php echo $simcard_function_id;?>" /><input type="hidden" name="simcard_function_nm[<?php echo $pkey;?>]" value="<?php echo $simcard_function_nm;?>" /><input type="hidden" name="simcard_function_qty[<?php echo $pkey;?>]" value="<?php echo $simcard_function_qty;?>" /></div>
        </td>

        <td class="text-center">
            <?php
            if(!$finished){
                $dc = array('name'=>'finished['.$pkey.']','class'=>'form-control','checked'=>$finished, 'value'=>1);
                echo form_checkbox($dc);
            }
            else{
                echo form_hidden('finished['.$pkey.']', 1);
                echo '<i class="fa fa-check"></i>';
            }
            ?>
        </td>

    </tr>

    <tr id="row3_old_assignmentproduct_<?php echo $pkey;?>">
        <td></td>
        <td></td>
        <td></td>
        <td>
            <!-- PIN -->
            <?php
            if($allowPinPuk){
                ?>
                <?php echo lang('page_fl_pin');?>:<br>
                <?php echo form_input('pin['.$pkey.']', $assignmentproduct['pin'], 'class="form-control noerror"');?>
                <?php
            }
            ?>
        </td>
        <td>
            <!-- PUK -->
            <?php
            if($allowPinPuk){
                ?>
                <?php echo lang('page_fl_puk');?>:<br>
                <?php echo form_input('puk['.$pkey.']', $assignmentproduct['puk'], 'class="form-control noerror"');?>
                <?php
            }
            ?>
        </td>

        <td colspan="4">
            <div id="div3_old_assignmentproduct_<?php echo $pkey;?>" style="display:none;">
                <label>
                    <?php
                    $ultracard1 = (isset($assignmentproduct['ultracard1']) && $assignmentproduct['ultracard1']==1)?true:false;
                    $dc = array('name'=>'ultracard1['.$pkey.']','class'=>'form-control','checked'=>$ultracard1, 'value'=>1);
                    echo form_checkbox($dc);?>
                    <?php echo lang('page_fl_ultracard1');?>
                </label>
                <label>
                    <?php
                    $ultracard2 = (isset($assignmentproduct['ultracard2']) && $assignmentproduct['ultracard2']==1)?true:false;
                    $dc = array('name'=>'ultracard2['.$pkey.']','class'=>'form-control','checked'=>$ultracard2, 'value'=>1);
                    echo form_checkbox($dc);?>
                    <?php echo lang('page_fl_ultracard2');?>
                </label>
            </div>
        </td>

        <td>
            <?php
            if($allowMoreOptionMobile){
                ?>
                <a href="javascript:void(0);" class="btn btn-sm btn-default btn-editable yellow" onclick="AddMoreOptionMobile('<?php echo lang('page_lb_moreoption');?>','<?php echo $pkey;?>','old');"><i class="icon-plus"></i> <?php echo lang('page_lb_moreoption');?></a>
                <?php
            }
            ?>
        </td>

        <td colspan="5"></td>

    </tr>
    <!-- END ROW -->

    <?php

    if($formula=='A'){
        $row = $this->Ratemobile_model->get($assignmentproduct['newratemobile'],'ultracard');
        $row = (isset($row->ultracard) && $row->ultracard==1) ? 1 : 0;
        ?>
        <script>
            if("<?= (int)$row?>"==1){
                $('#div3_old_assignmentproduct_<?php echo $pkey;?>').show();
            } else {
                $('#div3_old_assignmentproduct_<?php echo $pkey;?>').hide();
            }
        </script>
        <?php
    }
}
?>