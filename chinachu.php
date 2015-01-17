<?php
/**
 * Created by PhpStorm.
 * User: luis @lu_iskun
 * Date: 2015/01/17
 * Time: 17:21
 */

/**
 * 移動先の設定
 */
echo "初期設定をします\n";
if (file_exists('path.txt') == false) {
    echo "移動先の録画パスを”絶対値”で入力してください\n";
    $path = trim(fgets(STDIN, 4096));
    file_put_contents('path.txt', $path);
    echo "移動先の録画パスは " . $path . " です\n";
} else {
    $path = file_get_contents('path.txt');
    echo "移動先の録画パスは " . $path . " です\n";
}

/**
 * ファイルの一覧を取得してテキストに保存
 */
if (file_exists('dir.txt') == false && file_exists('list.txt') == false) {
    echo "現在の一時的な録画パス設定します\n";
    echo "現在の録画パスを”絶対値”入力してください\n";
    $dir = trim(fgets(STDIN, 4096));
    file_put_contents('dir.txt', $dir);
    $command = "find " . $dir . " -type f -name " . "*.ts";
    $list = shell_exec($command);
    file_put_contents('list.txt', $list);
    echo "現在録画されているファイルは\n" . $list . " です\n";
} else {
    $dir = file_get_contents('dir.txt');
    $list = system($command);
    echo "現在の録画パスは " . $dir . " で、現在録画されているファイルは\n" . $list . " です\n";
}

/**
 * ファイル移動
 */
//比較用に現在の録画されてるファイル一覧を取得
$size_list = filesize('list.txt');
$command = "find " . $dir . " -type f -name " . "*.ts";
file_put_contents('tmp_list.txt', shell_exec($command));
$size_temp_list = filesize('tmp_list.txt');
if ($size_temp_list > $size_list) {
    echo "前回の記録からファイルが追加されているためファイルを移動します\n";
    toMoveFiles($dir, $path);
    //list.txtに保存
    file_put_contents('list.txt', shell_exec($command));
}






function toMoveFiles($dir, $path)
{
    $command = 'mv ' . $dir . '/*.ts' . ' ' . $path;
    system($command);
}
