<?php 

function getRareRank($all_shapes,$all_colors){

    $repeat_shapes = 0;
    $repeat_shape_1 = 0;
    $repeat_shape_2 = 0;
    $repeat_shape_3 = 0;
    $repeat_colors = 0;
    $repeat_color_1 = 0;
    $repeat_color_2 = 0;
    $repeat_color_3 = 0;
    $repeat_color_4 = 0;
    $repeat_color_5 = 0;
    $repeat_color_6 = 0;
    $repeat_color_7 = 0;
    $repeat_color_8 = 0;

    for ($i = 0;$i<count($all_shapes);$i++)
    {
        if ($all_shapes[$i]=='shape_1') $repeat_shape_1++;
        else if ($all_shapes[$i]=='shape_2') $repeat_shape_2++;
        else if ($all_shapes[$i]=='shape_3') $repeat_shape_3++;
    }

    $repeat_shapes = max($repeat_shape_1,$repeat_shape_2,$repeat_shape_3);

    for ($i = 0;$i<count($all_colors);$i++)
    {
        if ($all_colors[$i]=='color_1') $repeat_color_1++;
        else if ($all_colors[$i]=='color_2') $repeat_color_2++;
        else if ($all_colors[$i]=='color_3') $repeat_color_3++;
        else if ($all_colors[$i]=='color_4') $repeat_color_4++;
        else if ($all_colors[$i]=='color_5') $repeat_color_5++;
        else if ($all_colors[$i]=='color_6') $repeat_color_6++;
        else if ($all_colors[$i]=='color_7') $repeat_color_7++;
        else if ($all_colors[$i]=='color_8') $repeat_color_8++;
    }

    $repeat_colors = max($repeat_color_1,$repeat_color_2,$repeat_color_3,$repeat_color_4,$repeat_color_5,$repeat_color_6,$repeat_color_7,$repeat_color_8);

    $scores = $repeat_shapes * 3 + $repeat_colors * 8;
    $rare = 0;

    // sort
    if ($scores <= 20 + 11 ){
        $rare = 1;
    }else if ($scores < 27+ 11){
        $rare = 2;
    }else if ($scores < 33+ 11){
        $rare = 3;
    }else if ($scores < 41+ 11){
        $rare = 4;
    }else if ($scores < 49+ 11){
        $rare = 5;
    }else if ($scores < 57+ 11){
        $rare = 6;
    }else if ($scores < 65+ 11){
        $rare = 7;
    }else if ($scores >= 66+ 11){
        $rare = 8;
    }

    return $rare;
}

function getRareName($rare){
    $names = ['普通','稀有','卓越','史诗','神话','传说','超神','宇宙之主'];
    return $names[$rare];
}



$gene = $_GET['gene'];

if ($gene == "")
{
$gene = sha1("hello".$_SERVER['REMOTE_ADDR']).sha1(time);
//echo $gene;
}
$shapes = ["shape_1","shape_2","shape_3"];
$colors = ["color_1","color_2","color_3","color_4","color_5","color_6","color_7","color_8"];
$parts  = ["fin.png","tail.png","bottom.png","body.png","streak.png","head.png","middle.png"];




$bg=imagecreatetruecolor(1024,1024);
$width=imagesx($bg);
$height=imagesy($bg);
$color=imagecolorallocate($bg,255,255,255);
imagefill($bg,0,0,$color);

$all_shapes = [];
$all_colors = [];

for ($i=0;$i<count($parts);$i++)
{

/**
 * 
 * 76 / 7 = 10
 * 0 - 9  -> shape & color.
 * 6      ->  0,1,2    3,4,5
 *74821 75533 -> 748%3   217%8 
 * 
 */


    $part_i = substr($gene,$i*10,10);
    $shape_i = intval(substr($part_i,0,3)) % count($shapes) | 0;
    $color_i = intval(substr($part_i,3,3)) % count($colors) | 0;

    $shape = $shapes[$shape_i];
    array_push($all_shapes,$shape);
    $color = $colors[$color_i];
    array_push($all_colors,$color);

    $pic_path="img/".$shape."/".$color."/".$parts[$i];

    //echo "<br>path".$pic_path;



    $f01=imagecreatefromstring(file_get_contents($pic_path));
    imagecopyresampled($bg,$f01,0,0,0,0,$width,$height,$width,$height);

}


$rare = getRareRank($all_shapes,$all_colors);
$textcolor = imagecolorallocate($bg, 0, 0, 0);


$fontsize = 20;
// 使用字体 
$ttf = '../res/fonts/'.'Microsoft-Yahei.ttf';  
imagettftext($bg,$fontsize,0,450,800,$textcolor,$ttf,'稀有度: '.getRareName($rare));

// Write the string at the top left
//imagestring($bg, 5, 450, 800, '稀有度 '.$rank, $textcolor);

/*

$f01=imagecreatefromstring(file_get_contents("F01.png"));
$f02=imagecreatefromstring(file_get_contents("F02.png"));
$f03=imagecreatefromstring(file_get_contents("F03.png"));
$f04=imagecreatefromstring(file_get_contents("F04.png"));
$f05=imagecreatefromstring(file_get_contents("F05.png"));
$f06=imagecreatefromstring(file_get_contents("F06.png"));
$f07=imagecreatefromstring(file_get_contents("F07.png"));


// up fin
imagecopyresampled($bg,$f05,0,0,0,0,$width,$height,$width,$height);

// tail
imagecopyresampled($bg,$f07,0,0,0,0,$width,$height,$width,$height);

// bottom fin
imagecopyresampled($bg,$f06,0,0,0,0,$width,$height,$width,$height);

// body
imagecopyresampled($bg,$f04,0,0,0,0,$width,$height,$width,$height);

// streak
imagecopyresampled($bg,$f03,0,0,0,0,$width,$height,$width,$height);

// head
imagecopyresampled($bg,$f01,0,0,0,0,$width,$height,$width,$height);

// middle fin
imagecopyresampled($bg,$f02,0,0,0,0,$width,$height,$width,$height);
*/


header("Content-type:image/png");
imagepng($bg);
imagedestroy($bg);

?>