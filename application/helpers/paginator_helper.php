<?php
function pagination($pages){
if ($pages > 1) {
    
    $q_str = (!empty($_SERVER['QUERY_STRING']))?'?'.$_SERVER['QUERY_STRING'].'&':'?';
    if(strpos($q_str,'page='))
        $q_str = substr($q_str,0,strpos($q_str,'page='));
    
    $page = (isset($_GET['page'])) ? $_GET['page'] + 1 : 0;
    $begin = ($page - 5 > 0) ? ($page - 5) : 0;
    $end = ($page + 4 < $pages) ? ($page + 4) : $pages;
    ?><div class="pages">
        <strong>Page </strong>
        <?
        if ($_GET['page'] > 0) {
            ?><a class="page" href="<?=$q_str?>page=0"><< </a><a class="page" href="<?=$q_str?>page=<?= $_GET['page'] - 1 ?>"> <</a><?
    }

    for ($x = $begin; $x < $end; $x++) {
            ?><a class="page <? if ($x == $_GET['page'] || (!isset($_GET['page']) && $x == 0)) echo 'selected' ?>" href="<?=$q_str?>page=<?= $x ?>" ><?= $x + 1 ?></a><?
    }

    if ($_GET['page'] < $pages - 1) {
            ?><a class="page" href="<?=$q_str?>page=<?= $_GET['page'] + 1 ?>"> > </a><a class="page" href="<?=$q_str?>page=<?= $pages - 1 ?>"> >></a><?
    }
        ?>
    </div>
    <br class="clear">
    <?
}
}
?>