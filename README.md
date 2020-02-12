# UIN-validator

**Правила расчёта контрольного разряда УИН.**

Контрольный разряд УИН формируется по следующим правилам:
+ каждому разряду УИН, начиная со старшего разряда, присваивается набор весов, соответствующий натуральному ряду чисел от 1 до 10, далее набор весов повторяется;
+ каждая цифра УИН умножается на присвоенный вес разряда и вычисляется сумма полученных произведений;
+ контрольный разряд для УИН представляет собой остаток от деления полученной суммы на модуль «11». Контрольный разряд должен иметь значение от 0 до 9;
+ если получается остаток, равный 10, то для обеспечения одноразрядного контрольного разряда необходимо провести повторный расчет, применяя вторую последовательность весов, являющуюся результатом циклического сдвига исходной последовательности на два разряда влево (3, 4, 5, 6, 7, 8, 9, 10, 1, 2). Если, в случае повторного расчета, остаток от деления вновь сохраняется равным 10, то значение контрольного разряда проставляется равным «0».

 для того чтобы использовать валидатор на фронте(JS) создайте и отправляйте AJAX запросы на ajaxUinValidator.php
```php
<?php 

//ajaxUinValidator.php

require_once($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');

$uin = json_decode(file_get_contents('php://input'), true);

$result = evolenta\uinValidator\uinValidator::uinValidate($uin);

echo json_encode($result);

?>
```
    
мой файл uinValidator.php находился в #DOCUMENT_ROOT#/vendor/evolenta/uinValidator/uinValidator.php