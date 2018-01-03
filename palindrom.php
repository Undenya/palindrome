<?php

// Запустить из консоли, командой "php palindrom.php 99999", где 99999 - необходимый, максимальный сомножитель
// При первом запуске скрипта, создается файл "data.json" с результатами вычислений, для более быстрого повторного использования скрипта

$matrix = [];
ini_set('max_execution_time', 900); // Увеличеное время исполнения скрипта
$maxCoFactor = 0; // Максимальный сомножитель
$minCoFactor = $maxCoFactor; // Минимальный сомножитель
$firstCoFactor = $maxCoFactor; // Первый сомножитель
$secondCoFactor = 2; // Второй сомножитель
$maxResult = 0; // Максимальный палиндром
$dateStart = microtime(true); // Время старта скрипта
$maxMatrix = 0; // Максимальное значение сомножителя из файла с готовыми результатами
$matrix = []; //Массив для результатов вычислений
$arr = []; // Массив для проверки данных массива $matrix

//Проверяем существует ли файл "data.json" с результатами вычислений
if (file_exists('data.json'))
{
    $file = file_get_contents('data.json', 'a+');

    // Записываем данные из файла в массив и находим максимальное значение сомножителя
    $matrix = json_decode($file, TRUE);
    $maxMatrix = max(array_keys($matrix));
}

// Проверяем аргумент указанный при запуске скрипта
if (isset($argv[1]))
{
    $maxCoFactor = $argv[1];

    //Назначаем первый сомножитель
    for ($max = $maxCoFactor; $max > $secondCoFactor; $max--)
    {
        // Проверяем простое ли число
        if(gmp_prob_prime($max) == '2')
        {
            //Проверяем, есть ли данные числа в массиве с результатами
            if ($maxMatrix < $max || !isset($matrix[$max]))
            {
                // Назначаем второй сомножитель
                for ($min = $max; $min > $secondCoFactor; $min--)
                {
                    if(gmp_prob_prime($min) == '2')
                    {
                        // Вычисляем произведение сомножителей
                        $res = $max * $min;

                        // Переворачиваем строку и проверяем является ли произведение полиндромом
                        if ($res == strrev($res))
                        {
                            // Записываем в переменную большее произведение и его сомножителей
                            if ($maxResult <= $res)
                            {
                                $maxResult = $res;
                                $firstCoFactor = $max;
                                $secondCoFactor = $min;

                                // "Запекание" результатов в массив для более быстрого повторного использования скрипта
                                $matrix[$max][$min] = $res;
                            }
                            break;
                        }
                    }
                }
            }
            else
            {
                // Перебираем массив с готовыми вычислениями, для нахождения максимального палиндрома
                foreach ($matrix as $k => $v)
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
                if ($maxResult < max(array_keys($arr)))
                {
                    $maxResult = max(array_keys($arr));
                    $firstCoFactor = $arr[$maxResult][0];
                    $secondCoFactor = $arr[$maxResult][1];
                }
                break;
            }
        }
    }
    $dateStop = microtime(true) - $dateStart; // Время остановки скрипта

    // Выводим результаты скрипта
    echo "Результат: ".$maxResult."\n";
    echo "Сомножители: ".$firstCoFactor.", ".$secondCoFactor."\n";
    echo "Затраченное время: ".$dateStop."\n";

    // Записываем обновленные данные вычислений в файл
    file_put_contents('data.json', json_encode($matrix));
}
else
{
    echo "Запустить из консоли, командой \"php palindrom.php 99999\", где 99999 - необходимый, максимальный сомножитель";
}

