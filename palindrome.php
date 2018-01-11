<?php

// Запустить из консоли, командой "php palindrome.php 99999", где 99999 - необходимый, максимальный сомножитель
// При первом запуске скрипта, создается файл "data.json" с результатами вычислений, для более быстрого повторного использования скрипта

// Проверяем аргумент указанный при запуске скрипта
if (isset($argv[1]))
{
    //Запускаем скрипт
    startUp($argv[1]);
}
else
{
    //Выводим ошибку
    echo "Запустить из консоли, командой \"php palindrome.php 99999\", где 99999 - необходимый, максимальный сомножитель \n";
}


function startUp($params)
{
    $dateStart = microtime(true); // Время старта скрипта
    $maxCoFactor = $params; // Максимальный сомножитель
    $minCoFactor = $maxCoFactor; // Минимальный сомножитель
    $firstCoFactor = $maxCoFactor; // Первый сомножитель
    $secondCoFactor = 2; // Второй сомножитель
    $maxResult = 0; // Максимальный палиндром
    $maxMatrix = 0; // Максимальное значение сомножителя из файла с готовыми результатами
    $matrix = []; //Массив для результатов вычислений
    $arr = []; // Массив для перебора данных массива $matrix


    //Проверяем существует ли файл "data.json" с результатами вычислений и записываем данные из файла в массив
    $matrix = getResultFile();

    //Находим максимальное значение сомножителя
    $maxMatrix = max(array_keys($matrix));

    //Назначаем первый сомножитель
    setFirstCoFactor($params, $secondCoFactor, $matrix, $maxMatrix, $maxResult);

}



function getResultFile()
{
    if (file_exists(__DIR__.'/data.json'))
    {
        $file = file_get_contents(__DIR__.'/data.json', 'a+');
        $matrix = json_decode($file, TRUE);
        return $matrix;
    }
}

function checkPrime($number)
{
    // Проверяем простое ли число
    for ($i = $number; $i >= 0; $i--)
    {
        if(gmp_prob_prime($number) == '2')
        {
            return $i;
        }
    }
}

function setFirstCoFactor($params, $secondCoFactor, $matrix, $maxMatrix, $maxResult)
{
    $maxCoFactor = $params;
    for ($max = $maxCoFactor; $max > $secondCoFactor; $max--)
    {
        // Проверяем простое ли число
        $max = checkPrime($max);

        //Проверяем, есть ли данные числа в массиве с результатами
        checkResultFile($max, $matrix, $maxCoFactor, $maxMatrix, $maxResult, $secondCoFactor);
    }
    showResult();
}

function setSecondCoFactor($max, $secondCoFactor, $maxResult)
{
    for ($min = $max; $min > $secondCoFactor; $min--)
    {
        // Проверяем простое ли число
        $min = checkPrime($min);

        getResult($max, $min, $maxResult);
    }
}

function getResult($max, $min, $maxResult)
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
    }
}

function checkResultFile($max, $matrix, $maxCoFactor, $maxMatrix, $maxResult, $secondCoFactor)
{
    //Проверяем, есть ли данные числа в массиве с результатами
    if ($maxMatrix < $max || !isset($matrix[$max]))
    {
        setSecondCoFactor($max, $secondCoFactor, $maxResult);
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
    }
}

function showResult()
{
    $dateStop = microtime(true) - $dateStart; // Время остановки скрипта

    // Выводим результаты скрипта
    echo "Результат: ".$maxResult."\n";
    echo "Сомножители: ".$firstCoFactor.", ".$secondCoFactor."\n";
    echo "Затраченное время: ".$dateStop."\n";
}

function writeResultFile()
{
    file_put_contents(__DIR__.'/data.json', json_encode($matrix));
}

