// Сайт.

requirejs.config({
  paths: {
    site: '../app/site',
    lib:  '../app/lib',
    async: 'require/async'
  }
});

$(function() {
});

// ВНИМАНИЕ! 
//
// КОД, РАЗМЕЩАЕМЫЙ ЗДЕСЬ, ВЫПОЛНЯЕТСЯ СИНХРОННО В ГЛОБАЛЬНОМ ПРОСТРАНСТВЕ ИМЕН.
//
// ИСПОЛЬЗОВАНИЕ ЭТОГО ФАЙЛА ДЛЯ РАЗМЕЩЕНИЯ КОДА ДОПУСТИМО ТОЛЬКО ДЛЯ 
// СОВМЕСТИМОСТИ С РАНЕЕ НАПИСАННЫМ КОДОМ. ОБЪЕМ КОДА, РАЗМЕЩЕННОГО ЗДЕСЬ,
// ДОЛЖЕН БЫТЬ МИНИМАЛЬНЫМ.  В КОДЕ НЕДОСТУПНА НИ ОДНА БИБЛИОТЕКА, КРОМЕ JQUERY.
//
// ИСПОЛЬЗОВАНИЕ ЭТОГО ФАЙЛА ДЛЯ РАЗМЕЩЕНИЯ ОБЫЧНОГО КОДА ПРИЛОЖЕНИЯ СТРОГО
// ЗАПРЕЩЕНО.
//
