<?php
if (sfContext::getInstance()->getUser()->hasPermission("All") or sfContext::getInstance()->getUser()->hasPermission("Admin contentportal")):
    ?>  <a href="#" onClick="changePublic(<?php echo $comments->getId() ?>);
                return false;"><?php echo get_partial('comments/list_field_boolean', array('value' => $comments->getIsPublic())) ?></a>
    <script language="javascript">
        function changePublic(id) {
            $.ajax(
                    {
                        type: "POST",
                        url: "/backend_dev.php/comments/publicChange",
                        timeout: 5000,
                        data: "id=" + id,
                        success: function (data) {
                            $("[value=" + id + "]").parent().parent().children(".sf_admin_list_td_is_public").html(data);
                        },
                        error: function (data) {
                            $(".sf_admin_list_td_is_public_" + id).html("Error" + data);
                        }
                    });
        }
    </script>
<?php else: ?>
    <?php echo get_partial('comments/list_field_boolean', array('value' => $comments->getIsPublic())) ?>
<?php endif; ?>
