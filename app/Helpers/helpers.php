<?php

declare(strict_types=1);


/**
 * Quill Delta(JSON) を HTML に変換
 *
 * @param string $deltaJson
 * @return string
 */
function quillDeltaToHtml(string $deltaJson): string
{
    $ops = json_decode($deltaJson);
    $html = '';

    foreach ($ops as $op) {
         foreach ($op as $item) {
            // 画像の場合
            if (isset($item->insert->image)) {
              $src = asset('storage/' . $item->insert->image); // パス補正
              $html .= '<p><img src="' . e($src) . '" alt=""></p>';
            }
            // テキストの場合
            elseif (isset($item->insert) && is_string($item->insert)) {
                $text = nl2br(e($item->insert));
                $html .= "<p>{$text}</p>";
            }
         }
    }
    return $html;
}