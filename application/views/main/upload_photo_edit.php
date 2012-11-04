<?php
list($crop_img_width,$crop_img_height) = getimagesize(base_url().'/files/users/uploads/'.$this->user['id'].'/'.$this->uri->segment(3).'_tmp.jpg');
$thumb_xy = unserialize($photo['thumb_xy']);
?>
<div class="sidebar">
    <? $this->tpl->render_blocks('lmenu'); ?>
    <div class="footer"></div>
</div>

<div id="content">
    <div class="page_title">
        <span>Upload and share fashion photos!</span>
    </div>
    <div class="bg">
        <div class="upload100">
            <img src="/files/users/uploads/<?= $this->user['id'] ?>/<?= $this->uri->segment(3) ?>_tmp.jpg">
        </div>
        <p class="center">
            <strong>Here is the photo you uploaded. What do you want to do?</strong><br><br>
            <img class="upload" src="/files/users/uploads/<?= $this->user['id'] ?>/<?= $this->uri->segment(3) ?>_tmp.jpg"><br>
            <a class="delete_photo" data-id="<?= $this->uri->segment(3) ?>">X Delete photo</a>
        </p>
        Items in the photo (up to 5, optional):<br>
        <form action="" method="post">
            <input type="hidden" name="upload_x1" value="<?=((empty($thumb_xy))?0:$thumb_xy['x1'])?>" />
            <input type="hidden" name="upload_y1" value="<?=((empty($thumb_xy))?0:$thumb_xy['y1'])?>" />
            <input type="hidden" name="upload_x2" value="<?=((empty($thumb_xy))?(($crop_img_height < $crop_img_width)?$crop_img_height:$crop_img_width):$thumb_xy['x2'])?>" />
            <input type="hidden" name="upload_y2" value="<?=((empty($thumb_xy))?(($crop_img_height < $crop_img_width)?$crop_img_height:$crop_img_width):$thumb_xy['y2'])?>" />
            <table>
                <? for ($x = 0; $x < 5; $x++) { ?>
                    <tr>
                        <td>
                            <select name="pos[]">
                                <option value="">(no selection)</option>
                                <option value="top" <? if ($tags[$x]['position'] == 'top') echo'selected="selected"' ?>>top</option>
                                <option value="shoes" <? if ($tags[$x]['position'] == 'shoes') echo'selected="selected"' ?>>shoes</option>
                                <option value="accessories" <? if ($tags[$x]['position'] == 'accessories') echo'selected="selected"' ?>>accessories</option>
                                <option value="bottoms" <? if ($tags[$x]['position'] == 'bottoms') echo'selected="selected"' ?>>bottoms</option>
                            </select>
                        </td>
                        <td><input type="text" name="tagname[]" value="<?= $tags[$x]['title'] ?>"></td>
                        <td><span class="left" style="margin-right: 5px;">by</span> 
                            <select data-placeholder="Select members" style="width:220px;" name="brand[]" class="chzn-select">
                                <option value="">(select brand)</option>
                                <?
                                if (!empty($brands)) {
                                    foreach ($brands as $val) {
                                        ?><option value="<?= $val['id'] ?>" <? if ($val['id'] == $tags[$x]['brand_id']) echo'selected="selected"' ?>><?= $val['title'] ?></option><?
                        }
                    }
                                ?>
                            </select>
                            <?
                            if (!empty($tags[$x])) {
                                ?><input type="hidden" name="edit[]" value="<?= $tags[$x]['id'] ?>"><?
                    }
                            ?>
                        </td>
                    </tr>
                <? } ?>
            </table>
            <br>
            Caption: (optional)<br>
            <textarea name="caption" style="width: 530px"><?= $photo['caption'] ?></textarea><br>
            <input type="submit" name="save" value="Save">
        </form>
        <br class="clear">
    </div>
    <div class="footer"></div>
</div>

<script>
    $(function(){
        $(".chzn-select").chosen();
        var photoWidth = 0
        var photoHeight = 0
        var firstClick = 0;
        rand = Math.floor(Math.random()*5000)
        
        
        updateLAvatar();
        function updateLAvatar(){
            var scaleX = 100 / ( <?=($crop_img_height < $crop_img_width)?$crop_img_height:$crop_img_width?> || 1)
            var scaleY = 100 / ( <?=($crop_img_height < $crop_img_width)?$crop_img_height:$crop_img_width?> || 1)
            $('img.upload').attr("src", $('img.upload').attr("src")).load(function() {
                uploadWidth = this.width
                uploadHeight = this.height
                width = uploadWidth * scaleX ;
                height = uploadHeight * scaleY ;
                $('.upload100 img').css('width',width+'px').css('height',height+'px')
            });
              
        }
updateLAvatar();
        var i=0;
        function showPreview(img, selection){
            $('div[class*=imgareaselect]').show();
            if(i==0){i++}else{
                var scaleX = 100 / (selection.width || 1);
                var scaleY = 100 / (selection.height || 1);
                $('div.upload100 img').css({
                    width: Math.round(scaleX * $('img.upload').width()) + 'px',
                    height: Math.round(scaleY * $('img.upload').height()) + 'px',
                    marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
                    marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
                });
            }
            
        }
        
        $('img.upload').imgAreaSelect({
            handles: true,
            aspectRatio: '1:1',
            onSelectChange: showPreview,
            minWidth: 25,
            minHeight: 25,
            x1: '<?=((empty($thumb_xy))?0:$thumb_xy['x1'])?>', 
            y1: '<?=((empty($thumb_xy))?0:$thumb_xy['y1'])?>', 
            x2: '<?=((empty($thumb_xy))?(($crop_img_height < $crop_img_width)?$crop_img_height:$crop_img_width):$thumb_xy['x2'])?>', 
            y2: '<?=((empty($thumb_xy))?(($crop_img_height < $crop_img_width)?$crop_img_height:$crop_img_width):$thumb_xy['y2'])?>',
            onSelectEnd: function(img, selection){
                $('input[name="upload_x1"]').val(selection.x1);
                $('input[name="upload_y1"]').val(selection.y1);
                $('input[name="upload_x2"]').val(selection.x2);
                $('input[name="upload_y2"]').val(selection.y2);
            }
        })
        
//        $('#avatar').mouseleave(function(){
//            $('.imgareaselect-outer').prev().show()
//        })
        
    })
</script>