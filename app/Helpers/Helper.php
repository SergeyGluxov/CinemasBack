<?php


namespace App\Helpers;


class Helper
{
    public static function getCountryIvi(int $value)
    {
        switch ($value) {
            case 131:
                return "Парагвай";
            case 61:
                return "Португалия";
            case 22:
                return "Казахстан";
            case 77:
                return "Венесуэла";
            case 59:
                return "Бразилия";
            case 13:
                return "Таиланд";
            case 86:
                return "Хорватия";
            case 5:
                return "Новая Зеландия";
            case 104:
                return "Мальта";
            case 51:
                return "Исландия";
            case 75:
                return "Кипр";
            case 69:
                return "Ливан";
            case 81:
                return "Филиппины";
            case 57:
                return "Греция";
            case 96:
                return "Уругвай";
            case 157:
                return "Камбоджа";
            case 186:
                return "Ангилья";
            case 154:
                return "Коста-Рика";
            case 24:
                return "Германия";
            case 54:
                return "Финляндия";
            case 62:
                return "Венгрия";
            case 56:
                return "Индия";
            case 44:
                return "Латвия";
            case 52:
                return "Аргентина";
            case 67:
                return "Иран";
            case 41:
                return "Узбекистан";
            case 19:
                return "ЮАР";
            case 12:
                return "Китай";
            case 82:
                return "Тайвань";
            case 21:
                return "Нидерланды";
            case 23:
                return "Гонконг";
            case 97:
                return "Чили";
            case 58:
                return "Чехия";
            case 60:
                return "Бельгия";
            case 99:
                return "Албания";
            case 79:
                return "Перу";
            case 98:
                return "ОАЭ";
            case 30:
                return "Румыния";
            case 78:
                return "Люксембург";
            case 4:
                return "США";
            case 49:
                return "Швеция";
            case 48:
                return "Испания";
            case 28:
                return "Ирландия";
            case 20:
                return "Япония";
            case 10:
                return "Швейцария";
            case 2:
                return "Беларусь";
            case 15:
                return "Канада";
            case 34:
                return "Азербайджан";
            case 16:
                return "Австралия";
            case 25:
                return "Южная Корея";
            case 71:
                return "Израиль";
            case 17:
                return "Польша";
            case 50:
                return "Колумбия";
            case 31:
                return "Мексика";
            case 247:
                return "Суринам";
            case 27:
                return "Австрия";
            case 36:
                return "Словакия";
            case 107:
                return "Словения";
            case 74:
                return "Малайзия";
            case 63:
                return "Индонезия";
            case 91:
                return "Болгария";
            case 94:
                return "Пакистан";
            case 1:
                return "Россия";
            case 6:
                return "Великобритания";
            case 8:
                return "Франция";
            case 29:
                return "Италия";
            case 18:
                return "Дания";
            case 55:
                return "Норвегия";
            case 32:
                return "Турция";
            case 87:
                return "СССР";
            case 37:
                return "Грузия";
            case 38:
                return "Киргизия";
            case 35:
                return "Армения";
            case 33:
                return "Украина";
            default:
                return "Undefined";
        }
    }


    public static function getTypeContent(int $value)
    {
        switch ($value) {
            case 14:
                return "Фильм";
            case 15:
                return "Сериал";
            default:
                return "Undefined";
        }
    }

    public static function getTypeContentMore(string $value)
    {
        switch ($value) {
            case 'MOVIE':
                return "Фильм";
            case 'SERIES':
                return "Сериал";
            default:
                return "Undefined";
        }
    }

    public static function getGenreIvi(int $value)
    {
        switch ($value) {
            case 161:
                return "Артхаус";
            case 225:
                return "Вестерн";
            case 160:
                return "Для детей";
            case 211:
                return "Зарубежные";
            case 95:
                return "Комедии";
            case 201:
                return "Мистические";
            case 101:
                return "Приключения";
            case 168:
                return "Советские";
            case 99:
                return "Ужасы";
            case 226:
                return "Биография";
            case 103:
                return "Военные";
            case 109:
                return "Документальные";
            case 192:
                return "Исторические";
            case 218:
                return "Криминал";
            case 189:
                return "Музыкальные";
            case 205:
                return "Русские";
            case 228:
                return "Спорт";
            case 94:
                return "Боевики";
            case 97:
                return "Детективы";
            case 105:
                return "Драмы";
            case 263:
                return "Катастрофы";
            case 107:
                return "Мелодрамы";
            case 198:
                return "Семейные";
            case 127:
                return "Триллеры";
            case 204:
                return "Фэнтези";
            default:
                return "Undefined";
        }
    }

    public static function getGenreMore(int $value)
    {
        switch ($value) {
            case 113:
                return "Фантастика";
            case 225:
                return "Вестерн";
            case 160:
                return "Для детей";
            case 211:
                return "Зарубежные";
            case 95:
                return "Комедии";
            case 201:
                return "Мистические";
            case 101:
                return "Приключения";
            case 168:
                return "Советские";
            case 99:
                return "Ужасы";
            case 226:
                return "Биография";
            case 103:
                return "Военные";
            case 109:
                return "Документальные";
            case 192:
                return "Исторические";
            case 218:
                return "Криминал";
            case 189:
                return "Музыкальные";
            case 205:
                return "Русские";
            case 228:
                return "Спорт";
            case 13:
                return "Боевик";
            case 97:
                return "Детективы";
            case 105:
                return "Драмы";
            case 263:
                return "Катастрофы";
            case 107:
                return "Мелодрамы";
            case 198:
                return "Семейные";
            case 127:
                return "Триллеры";
            case 204:
                return "Фэнтези";
            default:
                return "Undefined";
        }
    }
}
