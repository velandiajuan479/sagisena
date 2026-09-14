<?php

function titulo($ic,$ti){
    $txt = '';
        $txt .= '<div class="tit">';
            $txt .= '<i class="'.$ico.'"></i>';
            $txt .= ''.$ti;
            $txt .= '<hr class="hrtit">';
    $txt .= '</div>';
    $txt .= '<div class="titua">';
        $txt .= '<i class="fa-solid fa-circle-plus"></i>';
        $txt .= '<i  class="fa-solid fa-circle-minus"</i>';
    $txt .= '</div>';
    echo $txt;
}
?>