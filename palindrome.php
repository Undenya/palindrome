<?php

// Запустить из консоли, командой "php palindrome2.php X" где X - кол-во знаков максимального сомножителя
// При первом запуске скрипта, создается файл "data.json" с результатами вычислений, для более быстрого повторного использования скрипта

if (isset($argv[1]))
{
    $maxCoFactor = str_repeat('9', $argv[1]); // Максимальный сомножитель
    $minCoFactor = '1'.str_repeat('0', mb_strlen($maxCoFactor)-1); // Минимальный сомножитель
    $result['maxStr'] = $argv[1]; // кол-во знаков максимального сомножителя
    $result['dateStart'] = microtime(true); // Время старта скрипта
    $result['matrix'][$result['maxStr']] = []; //Готовые значения скрипта
    $result['maxResult'] = 0; // Максимальный палиндром
    $result['firstCoFactor'] = 0; // Первый сомножитель
    $result['secondCoFactor'] = 0; // Второй сомножитель
}
else
{
    echo "Запустить из консоли, командой \"php palindrom.php X\", где X - кол-во знаков максимального сомножителя \n";
    exit;
}

//Проверяем существует ли файл "data.json" с результатами вычислений
if (file_exists(__DIR__.'/data.json'))
{
    $file = file_get_contents(__DIR__.'/data.json', 'a+');

    // Записываем данные из файла в массив и находим максимальное значение сомножителя
    $result['matrix'] = json_decode($file, TRUE);
    $maxMatrix = max(array_keys($result['matrix']));

    //Проверяем, есть ли данные числа в массиве с результатами
    if (!isset($result['matrix'][$result['maxStr']]))
    {
        //Запуск скрипта
        $result = startUp($maxCoFactor, $minCoFactor, $result);

        // Записываем обновленные данные вычислений в файл
        file_put_contents(__DIR__.'/data.json', json_encode($result['matrix']));

        //Вывод результатов
        showResult($result);
    }
    else
    {
        // Перебираем массив с готовыми вычислениями, для нахождения максимального палиндрома
        foreach ($result['matrix'][$result['maxStr']] as $k => $v)
        {
            // Выбираем только нужные результаты из массива
            if($k < $maxCoFactor)
            {
                foreach ($v as $i => $j)
                {
                    $arr[$j] = array($k, $i);
                }
            }
        }

        // Проверяем находится ли максимальный палиндром в массиве с готовыми вычислениями
        if ($result['maxResult'] < max(array_keys($arr)))
        {
            $result['maxResult'] = max(array_keys($arr));
            $result['firstCoFactor'] = $arr[$result['maxResult']][0];
            $result['secondCoFactor'] = $arr[$result['maxResult']][1];
        }

        //Вывод результатов
        showResult($result);
    }
}
else
{
    //Запуск скрипта
    $result = startUp($maxCoFactor, $minCoFactor, $result);

    // Записываем обновленные данные вычислений в файл
    file_put_contents(__DIR__.'/data.json', json_encode($result['matrix']));

    //Вывод результатов
    showResult($result);
}


function startUp($maxCoFactor, $minCoFactor, $result)
{
    //Назначаем первый сомножитель
    for ($max = $maxCoFactor; $max > $minCoFactor; $max--)
    {
        //Ищем простое число
        $max = checkPrime($max, $minCoFactor);

        // Назначаем второй сомножитель
        for ($min = $max; $min >= $minCoFactor; $min--)
        {
            $min = checkPrime($min, $minCoFactor);

            // Вычисляем произведение сомножителей
            $res = $max * $min;

            //Проводим проверку на палиндром
            if(checkPalindrome($res))
            {
                // Записываем в переменную больший палиндром и его сомножители
                if ($result['maxResult'] <= $res)
                {
                    $result['maxResult'] = $res;
                    $result['firstCoFactor'] = $max;
                    $result['secondCoFactor'] = $min;
                    $minCoFactor = $min;
                    // "Запекание" результатов в массив для более быстрого повторного использования скрипта
                    $result['matrix'][$result['maxStr']][$max][$min] = $res;
                }
                break;
            }
        }
    }
    return $result;
}

//Ищем простое число
function checkPrime($number, $minCoFactor)
{
    for ($i = $number; $i >= $minCoFactor; $i--)
    {
        if(gmp_prob_prime($i) == '2')
        {
            return $i;
        }
    }
}

// Переворачиваем строку и проверяем является ли произведение полиндромом
function checkPalindrome($res)
{
    if ($res == strrev($res))
    {
        return $res;
    }
    return false;
}

// Выводим результаты скрипта
function showResult($result)
{
    $dateStop = microtime(true) - $result['dateStart']; // Время остановки скрипта
    echo "Результат: ".$result['maxResult']."\n";
    echo "Сомножители: ".$result['firstCoFactor'].", ".$result['secondCoFactor']."\n";
    echo "Затраченное время: ".$dateStop."\n";
}




