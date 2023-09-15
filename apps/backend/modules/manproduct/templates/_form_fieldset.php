<?php use_javascript('/js/jquery.dnd_th.js') ?> 
<script language="javascript">
    $(document).ready(function() {
        function startSort(){
            //$('.sf_admin_form_field_Photoalbums div div table tbody tr td table tbody tr td table').each(function(i){if(i==2) $(this).addClass("PhotosTable");});
            $('#sf_fieldset______________________________________ > div.sf_admin_form_row.sf_admin_text.sf_admin_form_field_Photoalbums > div > div.content > table > tbody > tr > td > table > tbody > tr:nth-child(4) > td > table').addClass("PhotosTable");

            $('.PhotosTable').children().children().each(function(i){
                $(this).attr("id",$(this).find("#product_Photoalbums_0_Photos_"+i+"_id").val())
            });
<? /* $('.PhotosTable').children().children().each(function(i){
  $(this).find("#product_Photoalbums_0_Photos_"+i+"_position").parent().parent().css("display","none");
  }); */ ?>
                        $('.PhotosTable').children().children().each(function(i){
                            $(this).attr("title",$(this).find("#product_Photoalbums_0_Photos_"+i+"_position").val())
                        });
        
                        $('.PhotosTable').children().children().children("th").each(function(i){$(this).addClass("dragable");});;
        
                        $(".PhotosTable").tableDnD(
                        {
                            onDragClass: "myDragClass",

                            dragHandle: "dragable",
                            onDrop: function(table, row) 
                            {
                                var rows = table.tBodies[0].rows;

                                var next = "";
                                var rowsList = "";
                                for (var i = 0; i < rows.length; i++) 
                                {

                                    if (rows[i].id == row.id)
                                    {
                                        if (i < rows.length-1)
                                        {
                                            next = rows[i+1].title;

                                        }
                                    }
                                    rowsList += rows[i].title + ",";
                                }

                    	     
                                $.ajax(
                                {
                                    type: "POST",
                                    url: "/backend.php/product/photosSortChange",
                                    timeout: 30000,

                                    data: "table=photos&rowId=" + row.id + "&nextSortOrder=" + next + "&currentSortOrder=" + row.title + "&rowsList=" + rowsList + "&productId=<?= $product->getId() ?>",
                                    success: function(data){$(".sf_admin_form_field_Photoalbums").children("div").children("div.content").html(data);
                                        startSort();
                                    },

                                    error: function(data){$("div#info").html("Error" + data);}
                                });

                            }
                        });
                    }
                    startSort();
                });
</script>

<fieldset id="sf_fieldset_<?php echo preg_replace('/[^a-z0-9_]/', '_', strtolower($fieldset)) ?>">
    <?php if ('NONE' != $fieldset): ?>
        <h2><?php echo __($fieldset, array(), 'messages') ?></h2>
    <?php endif; ?>

    <?php foreach ($fields as $name => $field): ?>
        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
        <?php
        include_partial('manproduct/form_field', array(
            'name' => $name,
            'attributes' => $field->getConfig('attributes', array()),
            'label' => $field->getConfig('label'),
            'help' => $field->getConfig('help'),
            'form' => $form,
            'field' => $field,
            'class' => 'sf_admin_form_row sf_admin_' . strtolower($field->getType()) . ' sf_admin_form_field_' . $name,
        ))
        ?>
    <?php endforeach; ?>
</fieldset>
