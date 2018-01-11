<?php

// Запустить из консоли, командой "php palindrome_hard.php"

$maxCoFactor = 99999; // Максимальный сомножитель
$minCoFactor = 10000; // Минимальный сомножитель

//Запуск скрипта
$result = startUp($maxCoFactor, $minCoFactor);

//Вывод результатов
showResult($result);

function startUp($maxCoFactor, $minCoFactor)
{
    $result['dateStart'] = microtime(true); // Время старта скрипта
    $result['maxResult'] = 0; // Максимальный палиндром

    //Назначаем первый сомножитель
    for ($max = $maxCoFactor; $max > $minCoFactor; $max--)
    {
        //Ищем простое число
        $max = checkPrime($max);

        // Назначаем второй сомножитель
        for ($min = $max; $min >= $minCoFactor; $min--)
        {
            $min = checkPrime($min);

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
                }
                break;
            }
        }
    }
    return $result;
}

//Ищем простое число
function checkPrime($number)
{
    for ($i = $number; $i >= 10000; $i--)
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




