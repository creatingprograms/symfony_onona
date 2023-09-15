  <a href="#" onClick="changePublic(<?php echo $photos_user->getId() ?>); return false;"><?php echo get_partial('photosuser/list_field_boolean', array('value' => $photos_user->getIsPublic())) ?></a>
        <script language="javascript">
            function changePublic(id){     
                $.ajax(
                {
                    type: "POST",
                    url: "/backend_dev.php/photosuser/publicChange",

                    timeout: 5000,

                    data: "id=" + id,
                    success: function(data){$("[value="+id+"]").parent().parent().children(".sf_admin_list_td_is_public").html(data); },

                    error: function(data){$(".sf_admin_list_td_is_public_"+id).html("Error" + data);}
                });
            }
        </script>