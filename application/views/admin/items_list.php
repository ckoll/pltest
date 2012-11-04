<h2>Items</h2><br>
<?
if(!empty($items)){
    foreach($items as $val){
        ?>
<div><?=$val['title']?></div>
            <?
    }
}else{
    echo '<span class="err">Items not found</span>';
}
?>