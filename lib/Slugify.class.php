<?php
class SlugifyClass
{
  public static function Slugify($text)
  {
    $text = mb_strtolower($text, 'utf-8');

    // replace cyrillic chars
    $text = SlugifyClass::replaceCyrillic($text);

    // strip all non word chars
    $text = preg_replace('/\W/', ' ', $text);

    // replace all white space sections with a dash
    $text = preg_replace('/\ +/', '-', $text);

    // trim dashes
    $text = preg_replace('/\-$/', '', $text);
    $text = preg_replace('/^\-/', '', $text);

    return $text;
  }

  public static function replaceCyrillic ($text)
  {
    $chars = array(
      'ґ'=>'g','ё'=>'e','є'=>'e','ї'=>'i','і'=>'i',
      'а'=>'a', 'А'=>'a', 'б'=>'b', 'в'=>'v',
      'г'=>'g', 'д'=>'d', 'е'=>'e', 'ё'=>'e',
      'ж'=>'zh', 'з'=>'z', 'и'=>'i', 'й'=>'i',
      'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n',
      'о'=>'o', 'п'=>'p', 'р'=>'r', 'с'=>'s',
      'т'=>'t', 'у'=>'u', 'ф'=>'f', 'х'=>'h',
      'ц'=>'c', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'sch',
      'ы'=>'y', 'э'=>'e', 'ю'=>'u', 'я'=>'ya', 'é'=>'e', '&'=>'and',
      'ь'=>'', 'ъ' => '',
    );

    return strtr($text, $chars);
  }
}