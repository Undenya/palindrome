<?php

// Запустить из консоли, командой "php palindrome_hard.php x", где x - кол-во знаков максимального сомножителя

if (isset($argv[1]))
{
    //Запуск скрипта
    $result = startUp($argv[1]);

    //Вывод результатов
    showResult($result);
}
else
{
    echo "Запустить из консоли, командой \"php palindrom.php X\", где X - кол-во знаков максимального сомножителя \n";
    exit;
}

function startUp($argv)
{
    $maxCoFactor = str_repeat('9', $argv); // Максимальный сомножитель
    $minCoFactor = '1'.str_repeat('0', mb_strlen($maxCoFactor)-1); // Минимальный сомножитель
    $result['dateStart'] = microtime(true); // Время старта скрипта
    $result['maxResult'] = 0; // Максимальный палиндром

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




