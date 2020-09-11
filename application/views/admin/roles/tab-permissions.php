<?php
$r = 1;
foreach($permissions as $permission){
    ?>
    <tr>
       <td class=""><?php echo lang($permission['lang_name']);
       echo form_hidden('Permission[permissionid]['.$permission['permissionid'].']', $permission['permissionid']);
       ?></th>
       <td class="text-center">

                <?php
                //Viewable Permission
                if(in_array($permission['shortname'],$GLOBALS['viewable_permission'])){
                    $checked = has_role_permission($permission['shortname'], 'view', isset($role['roleid'])?$role['roleid']:'');
                    if($this->input->post()){ $checked = false; }
                    if(isset($role['Permission']['can_view'][$permission['permissionid']])){
                        $checked = true;
                    }

                    $ch = array(
                        'name' => 'Permission[can_view]['.$permission['permissionid'].']',
                        'value' => '1',
                        'checked' => $checked,
                        'class' => 'vchecker'
                    );
                    echo form_checkbox($ch);
                }
                ?>

       </th>
       <td class="text-center">

                <?php
                //Viewable Own Permission
                if(in_array($permission['shortname'],$GLOBALS['viewable_own_permission'])){
                    $checked = has_role_permission($permission['shortname'], 'view_own', isset($role['roleid'])?$role['roleid']:'');
                    if($this->input->post()){ $checked = false; }
                    if(isset($role['Permission']['can_view_own'][$permission['permissionid']])){
                        $checked = true;
                    }

                    $ch = array(
                        'name' => 'Permission[can_view_own]['.$permission['permissionid'].']',
                        'value' => '1',
                        'checked' => $checked,
                        'class' => 'vchecker'
                    );
                    echo form_checkbox($ch);
                }
                ?>

       </th>
       <td class="text-center">

                <?php
                //Creatable Permission
                if(in_array($permission['shortname'],$GLOBALS['creatable_permission'])){
                    $checked = has_role_permission($permission['shortname'], 'create', isset($role['roleid'])?$role['roleid']:'');
                    if($this->input->post()){ $checked = false; }
                    if(isset($role['Permission']['can_create'][$permission['permissionid']])){
                        $checked = true;
                    }

                    $ch = array(
                        'name' => 'Permission[can_create]['.$permission['permissionid'].']',
                        'value' => '1',
                        'checked' => $checked,
                        'class' => 'vchecker'
                    );
                    echo form_checkbox($ch);
                }
                ?>

       </th>
       <td class="text-center">

                <?php
                //Editable Permission
                if(in_array($permission['shortname'],$GLOBALS['editable_permission'])){
                    $checked = has_role_permission($permission['shortname'], 'edit', isset($role['roleid'])?$role['roleid']:'');
                    if($this->input->post()){ $checked = false; }
                    if(isset($role['Permission']['can_edit'][$permission['permissionid']])){
                        $checked = true;
                    }

                    $ch = array(
                        'name' => 'Permission[can_edit]['.$permission['permissionid'].']',
                        'value' => '1',
                        'checked' => $checked,
                        'class' => 'vchecker'
                    );
                    echo form_checkbox($ch);
                }
                ?>

       </th>
       <td class="text-center text-danger">

                <?php
                //Deletable Permission
                if(in_array($permission['shortname'],$GLOBALS['deletable_permission'])){
                    $checked = has_role_permission($permission['shortname'], 'delete', isset($role['roleid'])?$role['roleid']:'');
                    if($this->input->post()){ $checked = false; }
                    if(isset($role['Permission']['can_delete'][$permission['permissionid']])){
                        $checked = true;
                    }

                    $ch = array(
                        'name' => 'Permission[can_delete]['.$permission['permissionid'].']',
                        'value' => '1',
                        'checked' => $checked,
                        'class' => 'vchecker'
                    );
                    echo form_checkbox($ch);
                }
                ?>

       </th>
       <td class="text-center">

                <?php
                //Importable Permission
                if(in_array($permission['shortname'],$GLOBALS['importable_permission'])){
                    $checked = has_role_permission($permission['shortname'], 'import', isset($role['roleid'])?$role['roleid']:'');
                    if($this->input->post()){ $checked = false; }
                    if(isset($role['Permission']['can_import'][$permission['permissionid']])){
                        $checked = true;
                    }

                    $ch = array(
                        'name' => 'Permission[can_import]['.$permission['permissionid'].']',
                        'value' => '1',
                        'checked' => $checked,
                        'class' => 'vchecker'
                    );
                    echo form_checkbox($ch);
                }
                ?>

       </th>
    </tr>
    <?php
    $r++;
}
?>