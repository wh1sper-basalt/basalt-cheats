<?php

declare(strict_types=1);

/** @return list<array{year:string,tag:string,title:string,text:string,img:string}> */
function practice11_carousel_slides(): array
{
    return [
        ['year' => '1941', 'tag' => 'оборона столицы', 'title' => 'Битва за Москву', 'text' => 'Оборона Москвы занимает особое место в истории Великой Отечественной — от результата битвы за столицу СССР зависел весь дальнейший ход войны.', 'img' => 'assets/media/carousel/moscow.jpg'],
        ['year' => '1941—1944', 'tag' => '872 страшных дня', 'title' => 'Оборона Ленинграда', 'text' => 'Оборона Ленинграда стала тяжелейшим испытанием для жителей города на Неве и воинов Красной Армии.', 'img' => 'assets/media/carousel/leningrad.jpg'],
        ['year' => '1941—1942', 'tag' => 'Три вражеских штурма', 'title' => 'Оборона Севастополя', 'text' => 'Оборона Севастополя — великий подвиг советских воинов. 250 дней враг не мог сломить сопротивление защитников города русской славы.', 'img' => 'assets/media/carousel/sevastopol.jpg'],
        ['year' => '1942—1943', 'tag' => 'умереть, но не сдаться', 'title' => 'Ржевская битва', 'text' => 'Три сражения, которые измотали врага и позволили Красной Армии начать масштабное наступление.', 'img' => 'assets/media/carousel/rzhev.jpg'],
        ['year' => '1942—1943', 'tag' => 'коренной перелом', 'title' => 'Сталинградская битва', 'text' => 'Сталинградская битва изменила историю и переломила ход всей Второй мировой войны.', 'img' => 'assets/media/carousel/stalingrad.jpg'],
        ['year' => '1942—1943', 'tag' => 'неприступный хребет', 'title' => 'Битва за Кавказ', 'text' => 'В летнем наступлении 1942 года немцы стремились обеспечить себе выход к Волге и Кавказу. Но враг не прошёл.', 'img' => 'assets/media/carousel/caucasus.jpg'],
        ['year' => '1943', 'tag' => 'броня в огне', 'title' => 'Курская битва', 'text' => 'Важнейшим событием всей Второй мировой войны летом 1943 года стала битва на Курской дуге.', 'img' => 'assets/media/carousel/kursk.jpg'],
        ['year' => '1943', 'tag' => 'решительное наступление', 'title' => 'Битва за Днепр', 'text' => 'Битва за Днепр проходила в августе–декабре 1943 года и стала ключевым этапом освобождения Левобережной Украины.', 'img' => 'assets/media/carousel/dnepr.jpg'],
        ['year' => '1943—1944', 'tag' => 'мощный бросок', 'title' => 'Освобождение Правобережной Украины', 'text' => 'Наступление советских войск в декабре 1943 года — апреле 1944 года — один из крупнейших успехов Красной Армии.', 'img' => 'assets/media/carousel/ukraine.jpg'],
        ['year' => '1944', 'tag' => 'молниеносная атака', 'title' => 'Освобождение Крыма', 'text' => 'В 1944 году наши войска освободили город русской славы за три дня.', 'img' => 'assets/media/carousel/crimea.jpg'],
        ['year' => '1944', 'tag' => 'дорога на запад', 'title' => 'Операция «Багратион»', 'text' => 'Операция «Багратион» стала главным событием 1944 года.', 'img' => 'assets/media/carousel/bagration.jpg'],
        ['year' => '1944', 'tag' => 'путь к свободе', 'title' => 'Освобождение Румынии, Болгарии, Югославии', 'text' => 'Для СССР освобождение европейских государств было неотделимо от целей Великой Отечественной войны.', 'img' => 'assets/media/carousel/balkans.jpg'],
        ['year' => '1944', 'tag' => 'долгие переговоры', 'title' => 'Открытие второго фронта', 'text' => 'Антигитлеровская коалиция стала примером объединения стран с разными политическими системами.', 'img' => 'assets/media/carousel/front.jpg'],
        ['year' => '1944', 'tag' => 'от перемирия к миру', 'title' => 'Вывод из войны Финляндии', 'text' => 'После мощного наступления Красной Армии летом 1944 года финны признали своё поражение.', 'img' => 'assets/media/carousel/finland.jpg'],
        ['year' => '1944', 'tag' => 'развёрнутое наступление', 'title' => 'Освобождение Прибалтики', 'text' => 'Советские войска провели масштабную операцию, освободив большую часть Европы от фашистских войск.', 'img' => 'assets/media/carousel/baltic.jpg'],
        ['year' => '1944', 'tag' => 'от Вислы до Одера', 'title' => 'Освобождение Польши', 'text' => 'Освобождение Польши началось летом 1944 года.', 'img' => 'assets/media/carousel/poland.jpg'],
        ['year' => '1945', 'tag' => 'победный марш', 'title' => 'Освобождение Венгрии, Австрии и Чехословакии', 'text' => 'За их независимость СССР заплатил жизнями сотен тысяч своих лучших сынов.', 'img' => 'assets/media/carousel/central-europe.jpg'],
        ['year' => '1945', 'tag' => 'последний рывок', 'title' => 'Штурм Берлина', 'text' => 'Берлинская операция Красной Армии стала завершающей битвой Великой Отечественной войны.', 'img' => 'assets/media/carousel/berlin.jpg'],
        ['year' => '1945', 'tag' => 'наследие победы', 'title' => 'Итоги и уроки войны', 'text' => 'Советский солдат дал возможность сохранить национальную самостоятельность народам европейских стран.', 'img' => 'assets/media/carousel/victory.jpg'],
    ];
}

/** @return list<array{slug:string,name_ru:string,name_en:string,text:string,url:string,img:string}> */
function practice11_hero_cities(): array
{
    return [
        ['slug' => 'brest', 'name_ru' => 'Брестская крепость', 'name_en' => 'Brest Fortress', 'text' => 'Из всех городов Советского Союза именно Бресту выпала участь принять первый бой с немецкими захватчиками.', 'url' => 'https://may9.ru/victory/heroic-cities/brestskaya-krepost/', 'img' => 'assets/media/hero-city/brest.jpg'],
        ['slug' => 'kerch', 'name_ru' => 'Керчь', 'name_en' => 'Kerch', 'text' => 'Керчь была одним из первых городов, попавших под удар немецко‑фашистских войск в начале войны.', 'url' => 'https://may9.ru/victory/heroic-cities/kerc/', 'img' => 'assets/media/hero-city/kerch.jpg'],
        ['slug' => 'kiev', 'name_ru' => 'Киев', 'name_en' => 'Kyiv', 'text' => 'Внезапный удар с воздуха по Киеву немецкие войска нанесли 22 июня 1941 года.', 'url' => 'https://may9.ru/victory/heroic-cities/kiev/', 'img' => 'assets/media/hero-city/kiev.jpg'],
        ['slug' => 'leningrad', 'name_ru' => 'Ленинград', 'name_en' => 'Leningrad', 'text' => 'Ленинград был особым городом для СССР; ожесточённые бои на подступах начались 10 июля 1941 года.', 'url' => 'https://may9.ru/victory/heroic-cities/leningrad/', 'img' => 'assets/media/hero-city/leningrad.jpg'],
        ['slug' => 'minsk', 'name_ru' => 'Минск', 'name_en' => 'Minsk', 'text' => 'Минск с первых дней войны оказался в самом центре сражений.', 'url' => 'https://may9.ru/victory/heroic-cities/minsk/', 'img' => 'assets/media/hero-city/minsk.jpg'],
        ['slug' => 'moscow', 'name_ru' => 'Москва', 'name_en' => 'Moscow', 'text' => 'В агрессивных планах фашистской Германии захват Москвы имел первостепенное значение.', 'url' => 'https://may9.ru/victory/heroic-cities/moskva/', 'img' => 'assets/media/hero-city/moscow.jpg'],
        ['slug' => 'murmansk', 'name_ru' => 'Мурманск', 'name_en' => 'Murmansk', 'text' => 'Военная история Мурманска началась с наступления в 1941 году по нескольким направлениям.', 'url' => 'https://may9.ru/victory/heroic-cities/murmansk/', 'img' => 'assets/media/hero-city/murmansk.jpg'],
        ['slug' => 'novorossiysk', 'name_ru' => 'Новороссийск', 'name_en' => 'Novorossiysk', 'text' => 'После срыва немецкого плана на Кавказе начались атаки на Новороссийск.', 'url' => 'https://may9.ru/victory/heroic-cities/novorossiysk/', 'img' => 'assets/media/hero-city/novorossiysk.jpg'],
        ['slug' => 'odessa', 'name_ru' => 'Одесса', 'name_en' => 'Odessa', 'text' => 'Оборона Одессы длилась 73 дня силами армии и народного ополчения.', 'url' => 'https://may9.ru/victory/heroic-cities/odessa/', 'img' => 'assets/media/hero-city/odessa.jpg'],
        ['slug' => 'sevastopol', 'name_ru' => 'Севастополь', 'name_en' => 'Sevastopol', 'text' => 'Героическая защита города началась 30 октября 1941 года и продолжалась 250 дней.', 'url' => 'https://may9.ru/victory/heroic-cities/sevastopol/', 'img' => 'assets/media/hero-city/sevastopol.jpg'],
        ['slug' => 'smolensk', 'name_ru' => 'Смоленск', 'name_en' => 'Smolensk', 'text' => 'Смоленск оказался на пути главного удара фашистских войск, двигающихся на Москву.', 'url' => 'https://may9.ru/victory/heroic-cities/smolensk/', 'img' => 'assets/media/hero-city/smolensk.jpg'],
        ['slug' => 'stalingrad', 'name_ru' => 'Сталинград', 'name_en' => 'Stalingrad', 'text' => 'Волгоград — один из самых известных городов, носящих звание города‑героя.', 'url' => 'https://may9.ru/victory/heroic-cities/stalingrad/', 'img' => 'assets/media/hero-city/stalingrad.jpg'],
        ['slug' => 'tula', 'name_ru' => 'Тула', 'name_en' => 'Tula', 'text' => 'К октябрю 1941 года фашистским захватчикам удалось далеко продвинуться в глубь России.', 'url' => 'https://may9.ru/victory/heroic-cities/tula/', 'img' => 'assets/media/hero-city/tula.jpg'],
    ];
}
